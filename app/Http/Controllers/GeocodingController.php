<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

class GeocodingController extends Controller
{
    /**
     * Reverse geocode GPS coordinates into an Indian postal address.
     * Caches results, normalises Indian administrative boundaries, and supports graceful fallbacks.
     */
    public function reverseGeocode(Request $request): JsonResponse
    {
        try {
            $validated = $request->validate([
                'latitude' => ['required', 'numeric', 'between:-90,90'],
                'longitude' => ['required', 'numeric', 'between:-180,180'],
            ]);

            $latitude = (float) $validated['latitude'];
            $longitude = (float) $validated['longitude'];

            // Cache based on coordinates rounded to 4 decimal places (~11 meters resolution)
            $cacheKey = 'reverse_geo_'.round($latitude, 4).'_'.round($longitude, 4);

            $addressData = Cache::remember($cacheKey, 86400, function () use ($latitude, $longitude) {
                return $this->performReverseGeocoding($latitude, $longitude);
            });

            if ($addressData && (! empty($addressData['pincode']) || ! empty($addressData['state']) || ! empty($addressData['city']) || ! empty($addressData['district']))) {
                return response()->json([
                    'success' => true,
                    'message' => 'Indian postal address successfully resolved from GPS coordinates.',
                    'data' => array_merge([
                        'latitude' => $latitude,
                        'longitude' => $longitude,
                    ], $addressData),
                ]);
            }

            return response()->json([
                'success' => false,
                'message' => 'Coordinates captured, but reverse geocoding could not locate an exact postal address. Please complete address fields manually.',
                'data' => [
                    'latitude' => $latitude,
                    'longitude' => $longitude,
                    'pincode' => '',
                    'state' => '',
                    'district' => '',
                    'city' => '',
                    'address' => '',
                ],
            ]);
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid coordinates provided.',
                'errors' => $e->errors(),
            ], 422);
        } catch (\Throwable $e) {
            Log::error('Reverse geocoding exception: '.$e->getMessage(), ['exception' => $e]);

            return response()->json([
                'success' => false,
                'message' => 'Geocoding service encountered an error. Coordinates are saved.',
                'data' => [
                    'latitude' => (float) $request->input('latitude', 0),
                    'longitude' => (float) $request->input('longitude', 0),
                    'pincode' => '',
                    'state' => '',
                    'district' => '',
                    'city' => '',
                    'address' => '',
                ],
            ], 500);
        }
    }

    /**
     * Forward geocode an Indian address query into GPS coordinates.
     * Caches results, normalises query, and provides structured campus coordinates.
     */
    public function forwardGeocode(Request $request): JsonResponse
    {
        try {
            $validated = $request->validate([
                'query' => ['required', 'string', 'min:2', 'max:300'],
            ]);

            $query = trim($validated['query']);
            $cacheKey = 'forward_geo_'.md5(mb_strtolower($query));

            $location = Cache::remember($cacheKey, 86400, function () use ($query) {
                return $this->performForwardGeocoding($query);
            });

            if ($location) {
                return response()->json([
                    'success' => true,
                    'message' => 'Campus address successfully located on satellite map.',
                    'data' => $location,
                ]);
            }

            return response()->json([
                'success' => false,
                'message' => 'Could not resolve coordinates for this address. You can click on the satellite map directly to place the school pin.',
                'data' => null,
            ], 404);
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Please provide a valid address search query.',
                'errors' => $e->errors(),
            ], 422);
        } catch (\Throwable $e) {
            Log::error('Forward geocoding exception: '.$e->getMessage(), ['exception' => $e, 'query' => $request->input('query')]);

            return response()->json([
                'success' => false,
                'message' => 'Geocoding service temporarily unavailable. Please click on the satellite map to position the pin directly.',
                'data' => null,
            ], 500);
        }
    }

    /**
     * Primary lookup via OpenStreetMap Nominatim with BigDataCloud secondary fallback.
     */
    protected function performReverseGeocoding(float $lat, float $lon): ?array
    {
        // 1. Primary Provider: OpenStreetMap Nominatim (High Indian boundary precision)
        try {
            $response = Http::withHeaders([
                'User-Agent' => 'SchoolFeasibilityPortal/1.0 (info@yesindiafoundation.com)',
                'Accept' => 'application/json',
                'Accept-Language' => 'en',
            ])->timeout(6)->get('https://nominatim.openstreetmap.org/reverse', [
                'lat' => $lat,
                'lon' => $lon,
                'format' => 'jsonv2',
                'addressdetails' => 1,
                'zoom' => 18,
            ]);

            if ($response->successful() && $json = $response->json()) {
                $address = $json['address'] ?? [];

                $pincode = $this->extractPinCode($address);
                $state = $address['state'] ?? $address['province'] ?? '';
                $district = $this->extractDistrict($address);
                $city = $this->extractCityTown($address);
                $street = $this->extractStreetLandmark($address);

                if (! empty($state) || ! empty($district) || ! empty($city)) {
                    return [
                        'pincode' => $pincode,
                        'state' => $state,
                        'district' => $district,
                        'city' => $city,
                        'address' => $street,
                        'display_name' => $json['display_name'] ?? '',
                    ];
                }
            } elseif ($response->status() === 429) {
                Log::warning('Nominatim reverse geocode rate limit reached (HTTP 429). Falling back to secondary provider.');
            }
        } catch (\Throwable $e) {
            Log::warning('Nominatim reverse geocode lookup failed: '.$e->getMessage());
        }

        // 2. Secondary Provider Fallback: BigDataCloud (Free, robust client reverse geocoder)
        try {
            $fallbackResponse = Http::timeout(5)->get('https://api.bigdatacloud.net/data/reverse-geocode-client', [
                'latitude' => $lat,
                'longitude' => $lon,
                'localityLanguage' => 'en',
            ]);

            if ($fallbackResponse->successful() && $fbJson = $fallbackResponse->json()) {
                $pincode = preg_replace('/[^0-9]/', '', (string) ($fbJson['postcode'] ?? ''));
                if (strlen($pincode) !== 6) {
                    $pincode = '';
                }

                $state = $fbJson['principalSubdivision'] ?? '';
                $city = $fbJson['locality'] ?? $fbJson['city'] ?? '';
                $district = '';

                // Extract district from administrative levels
                if (! empty($fbJson['localityInfo']['administrative'])) {
                    foreach ($fbJson['localityInfo']['administrative'] as $admin) {
                        $order = $admin['order'] ?? 0;
                        if ($order >= 5 && $order <= 7 && ! empty($admin['name'])) {
                            $district = $admin['name'];
                            break;
                        }
                    }
                }

                return [
                    'pincode' => $pincode,
                    'state' => $state,
                    'district' => $district ?: $city,
                    'city' => $city,
                    'address' => '',
                    'display_name' => $fbJson['locality'] ?? '',
                ];
            }
        } catch (\Throwable $e) {
            Log::warning('BigDataCloud reverse geocode fallback failed: '.$e->getMessage());
        }

        return null;
    }

    /**
     * Primary forward geocode via OpenStreetMap Nominatim search for Indian addresses.
     */
    protected function performForwardGeocoding(string $query): ?array
    {
        try {
            $response = Http::withHeaders([
                'User-Agent' => 'SchoolFeasibilityPortal/1.0 (info@yesindiafoundation.com)',
                'Accept' => 'application/json',
                'Accept-Language' => 'en',
            ])->timeout(6)->get('https://nominatim.openstreetmap.org/search', [
                'q' => $query,
                'format' => 'jsonv2',
                'addressdetails' => 1,
                'limit' => 1,
                'countrycodes' => 'in',
            ]);

            if ($response->successful()) {
                $results = $response->json();
                if (! empty($results) && is_array($results)) {
                    $item = $results[0];
                    $lat = (float) $item['lat'];
                    $lon = (float) $item['lon'];
                    $address = $item['address'] ?? [];

                    return [
                        'latitude' => $lat,
                        'longitude' => $lon,
                        'display_name' => $item['display_name'] ?? '',
                        'pincode' => $this->extractPinCode($address),
                        'state' => $address['state'] ?? $address['province'] ?? '',
                        'district' => $this->extractDistrict($address),
                        'city' => $this->extractCityTown($address),
                        'address' => $this->extractStreetLandmark($address),
                    ];
                }
            } elseif ($response->status() === 429) {
                Log::warning('Nominatim forward geocode rate limit reached (HTTP 429).');
            }
        } catch (\Throwable $e) {
            Log::warning('Nominatim forward search lookup failed: '.$e->getMessage(), ['query' => $query]);
        }

        return null;
    }

    /**
     * Extract 6-digit Indian PIN code.
     */
    protected function extractPinCode(array $address): string
    {
        $postcode = (string) ($address['postcode'] ?? '');
        $digitsOnly = preg_replace('/[^0-9]/', '', $postcode);

        // Indian PIN codes are 6 digits
        if (preg_match('/^[1-9][0-9]{5}$/', $digitsOnly)) {
            return $digitsOnly;
        }

        return '';
    }

    /**
     * Extract and normalise Indian District.
     */
    protected function extractDistrict(array $address): string
    {
        $district = $address['state_district'] ?? $address['district'] ?? $address['county'] ?? '';

        // Clean up common suffixes like "District" or "district"
        $district = preg_replace('/\s+District$/i', '', trim($district));

        return $district;
    }

    /**
     * Extract Indian City, Town, Municipality, or Village.
     */
    protected function extractCityTown(array $address): string
    {
        return trim(
            $address['city'] ??
            $address['town'] ??
            $address['municipality'] ??
            $address['city_district'] ??
            $address['suburb'] ??
            $address['village'] ??
            $address['hamlet'] ??
            ''
        );
    }

    /**
     * Extract Street / Landmark / Road for campus address suggestion.
     */
    protected function extractStreetLandmark(array $address): string
    {
        $parts = array_filter([
            $address['road'] ?? null,
            $address['neighbourhood'] ?? null,
            $address['suburb'] ?? null,
        ]);

        return implode(', ', array_unique($parts));
    }
}
