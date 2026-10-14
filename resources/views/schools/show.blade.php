@php
    $activeApp = $school->applications->first(fn($a) => $a->status !== \App\Enums\ApplicationStatus::CANCELLED);
@endphp

<x-app-layout>
    <x-slot name="title">{{ $school->name }} — School Profile</x-slot>

    <div class="space-y-6">
        <!-- Header -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <div class="flex items-center gap-3">
                    <h1 class="text-2xl font-bold text-slate-900 tracking-tight">{{ $school->name }}</h1>
                    <x-badge variant="blue">{{ $school->board?->label() ?? 'CBSE' }}</x-badge>
                </div>
                <p class="text-xs font-mono text-slate-500 mt-1">Code: {{ $school->school_code }} &bull; Managed by
                    {{ $school->management_organization }}
                </p>
            </div>
            <div class="flex items-center gap-2">
                <a href="{{ route('schools.edit', $school) }}"
                    class="px-3.5 py-2 text-xs font-semibold text-slate-700 bg-white border border-slate-300 rounded-lg hover:bg-slate-50 shadow-xs">
                    Edit Profile
                </a>
                @if ($activeApp)
                    <a href="{{ route('applications.checklist.edit', $activeApp) }}"
                        class="px-4 py-2 text-xs font-semibold text-white bg-emerald-600 hover:bg-emerald-500 rounded-lg shadow-sm transition flex items-center gap-1.5">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                        </svg>
                        <span>Inspect Checklist (#{{ $activeApp->application_number }}) &rarr;</span>
                    </a>
                @else
                    <a href="{{ route('applications.create', ['school_id' => $school->id]) }}"
                        class="px-4 py-2 text-xs font-semibold text-white bg-indigo-600 hover:bg-indigo-500 rounded-lg shadow-sm transition flex items-center gap-1.5">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                        <span>Start Feasibility Inspection</span>
                    </a>
                @endif
            </div>
        </div>

        <!-- Details Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <div class="lg:col-span-2 space-y-6">
                <x-card title="Institutional Overview">
                    <dl class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-xs">
                        <div>
                            <dt class="text-slate-400 font-medium">Managing Entity</dt>
                            <dd class="text-slate-900 font-semibold mt-0.5">{{ $school->management_organization }}
                                ({{ $school->trust_society_type }})</dd>
                        </div>
                        <div>
                            <dt class="text-slate-400 font-medium">Registration Details</dt>
                            <dd class="text-slate-900 font-semibold mt-0.5">{{ $school->registration_number ?? 'N/A' }}
                                ({{ $school->registration_date?->format('d M Y') ?? 'Date Not Provided' }})</dd>
                        </div>
                        <div>
                            <dt class="text-slate-400 font-medium">School Type & Medium</dt>
                            <dd class="text-slate-900 font-semibold mt-0.5">
                                {{ $school->school_type?->label() ?? 'Composite K-12' }} &bull; {{ $school->medium }}
                            </dd>
                        </div>
                        <div>
                            <dt class="text-slate-400 font-medium">Established / Planned</dt>
                            <dd class="text-slate-900 font-semibold mt-0.5">
                                {{ $school->established_year ?? 'Year Not Set' }}
                                ({{ $school->is_existing_school ? 'Existing Campus' : 'New Proposed School' }})
                            </dd>
                        </div>
                        <div class="sm:col-span-2">
                            <dt class="text-slate-400 font-medium">Campus Address</dt>
                            <dd class="text-slate-900 font-semibold mt-0.5">{{ $school->address }}, {{ $school->city }},
                                {{ $school->district }}, {{ $school->state }} - {{ $school->pincode }}
                            </dd>
                        </div>
                        <div class="sm:col-span-2">
                            <dt class="text-slate-400 font-medium">GPS Satellite Coordinates & Map</dt>
                            <dd class="text-slate-900 font-semibold mt-1 space-y-3">
                                @if ($school->latitude && $school->longitude)
                                    <div class="flex items-center flex-wrap gap-3">
                                        <span
                                            class="font-mono text-emerald-800 bg-emerald-50 border border-emerald-200 px-2.5 py-1 rounded-lg text-xs font-semibold inline-flex items-center gap-1.5">
                                            <svg class="w-3.5 h-3.5 text-emerald-600" fill="none" viewBox="0 0 24 24"
                                                stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                            </svg>
                                            {{ number_format($school->latitude, 7) }}° N,
                                            {{ number_format($school->longitude, 7) }}° E
                                        </span>
                                        <a href="{{ $school->google_maps_url }}" target="_blank" rel="noopener noreferrer"
                                            class="inline-flex items-center gap-1 text-xs text-indigo-600 hover:text-indigo-800 hover:underline font-semibold">
                                            <span>Open in Google Maps</span>
                                        </a>
                                        <a href="https://earth.google.com/web/search/{{ $school->latitude }},{{ $school->longitude }}"
                                            target="_blank" rel="noopener noreferrer"
                                            class="inline-flex items-center gap-1 text-xs text-emerald-600 hover:text-emerald-800 hover:underline font-semibold">
                                            <span>Google Earth 3D</span>
                                        </a>
                                    </div>

                                    <!-- Interactive Satellite Map Preview -->
                                    <div class="rounded-2xl border border-slate-200 overflow-hidden shadow-xs bg-slate-900">
                                        <div id="show-campus-map" style="width: 100%; min-height: 280px; height: 280px;"
                                            class="relative z-0"></div>
                                    </div>
                                @else
                                    <span class="text-slate-400 italic text-xs">Coordinates not recorded</span>
                                    <a href="{{ route('schools.edit', $school) }}"
                                        class="text-indigo-600 hover:underline text-xs font-medium">+ Add GPS
                                        Coordinates</a>
                                @endif
                            </dd>
                        </div>
                    </dl>
                </x-card>

                <!-- Associated Feasibility Study Applications -->
                <x-card title="Feasibility Study Applications"
                    subtitle="Historical and active feasibility studies associated with this school" noPadding="true">
                    <div class="overflow-x-auto">
                        <table class="w-full text-left text-sm text-slate-600">
                            <thead
                                class="bg-slate-50 text-xs uppercase font-semibold text-slate-500 border-b border-slate-100">
                                <tr>
                                    <th class="px-5 py-3">Application Number</th>
                                    <th class="px-5 py-3">Status</th>
                                    <th class="px-5 py-3">Score</th>
                                    <th class="px-5 py-3">Submission</th>
                                    <th class="px-5 py-3 text-right">Action</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                @forelse ($school->applications as $app)
                                    <tr class="hover:bg-slate-50/60">
                                        <td class="px-5 py-3.5">
                                            <a href="{{ route('applications.show', $app) }}"
                                                class="font-mono text-xs font-semibold text-indigo-600 hover:text-indigo-800">
                                                {{ $app->application_number }}
                                            </a>
                                        </td>
                                        <td class="px-5 py-3.5">
                                            <x-badge :variant="$app->status->color()">{{ $app->status->label() }}</x-badge>
                                        </td>
                                        <td class="px-5 py-3.5">
                                            <span
                                                class="font-bold text-slate-900">{{ $app->overall_score ? $app->overall_score . '%' : 'Incomplete' }}</span>
                                        </td>
                                        <td class="px-5 py-3.5 text-xs text-slate-500">
                                            {{ $app->submission_date?->format('d M Y') ?? 'Draft' }}
                                        </td>
                                        <td class="px-5 py-3.5 text-right whitespace-nowrap">
                                            <a href="{{ route('applications.checklist.edit', $app) }}"
                                                class="inline-flex items-center text-xs font-semibold text-emerald-600 hover:text-emerald-700 mr-3">
                                                Inspect &rarr;
                                            </a>
                                            <a href="{{ route('applications.show', $app) }}"
                                                class="inline-flex items-center text-xs font-semibold text-slate-700 hover:text-indigo-600">
                                                Overview
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="px-5 py-6 text-center text-slate-400 text-xs">
                                            No feasibility studies initiated yet for this school.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </x-card>
            </div>

            <!-- Contact Panel -->
            <div class="space-y-6">
                <x-card title="Applicant Authority">
                    <div class="space-y-3 text-xs">
                        <div>
                            <div class="text-slate-400 font-medium">Authorised Contact</div>
                            <div class="font-bold text-slate-900 mt-0.5">{{ $school->applicant_name }}</div>
                        </div>
                        <div>
                            <div class="text-slate-400 font-medium">Email Address</div>
                            <a href="mailto:{{ $school->applicant_email }}"
                                class="text-indigo-600 hover:underline font-medium block mt-0.5">{{ $school->applicant_email }}</a>
                        </div>
                        <div>
                            <div class="text-slate-400 font-medium">Mobile / Phone</div>
                            <div class="text-slate-900 font-medium mt-0.5">{{ $school->applicant_phone }}</div>
                        </div>
                        @if ($school->website)
                            <div>
                                <div class="text-slate-400 font-medium">Website</div>
                                <a href="{{ $school->website }}" target="_blank"
                                    class="text-indigo-600 hover:underline font-medium block mt-0.5">{{ $school->website }}</a>
                            </div>
                        @endif
                    </div>
                </x-card>
            </div>
        </div>
    </div>

    @if ($school->latitude && $school->longitude)
        @push('styles')
            <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
            <style>
                .leaflet-container {
                    font-family: inherit;
                    background: #0f172a;
                }

                .mapcn-custom-pin-wrapper {
                    background: transparent !important;
                    border: none !important;
                }
            </style>
        @endpush

        @push('scripts')
            <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
            <script>
                let showMapInstance = null;
                let showTileLayer = null;

                const googleSatelliteUrl = 'https://mt1.google.com/vt/lyrs=y&x={x}&y={y}&z={z}';
                const googleSatelliteOptions = {
                    maxZoom: 22,
                    subdomains: ['mt0', 'mt1', 'mt2', 'mt3'],
                    attribution: '&copy; Google Maps'
                };

                document.addEventListener('DOMContentLoaded', function () {
                    try {
                        const el = document.getElementById('show-campus-map');
                        if (!el || typeof L === 'undefined') return;

                        const lat = {{ $school->latitude }};
                        const lng = {{ $school->longitude }};

                        showMapInstance = L.map('show-campus-map', {
                            center: [lat, lng],
                            zoom: 17,
                            zoomControl: true,
                            attributionControl: true
                        });

                        showTileLayer = L.tileLayer(googleSatelliteUrl, googleSatelliteOptions).addTo(showMapInstance);

                        const pinIcon = L.divIcon({
                            className: 'mapcn-custom-pin-wrapper',
                            html: `
                                                                                                <div class="relative flex items-center justify-center -translate-x-1/2 -translate-y-full">
                                                                                                    <span class="absolute w-8 h-8 rounded-full bg-emerald-500/40 animate-ping" style="bottom:-4px;"></span>
                                                                                                    <div class="relative z-10 flex items-center justify-center w-8 h-10 drop-shadow-lg">
                                                                                                        <svg class="w-8 h-10 text-emerald-600 fill-current" viewBox="0 0 24 32">
                                                                                                            <path d="M12 0C5.373 0 0 5.373 0 12c0 9 12 20 12 20s12-11 12-20c0-6.627-5.373-12-12-12zm0 17c-2.761 0-5-2.239-5-5s2.239-5 5-5 5 2.239 5 5-2.239 5-5 5z"/>
                                                                                                        </svg>
                                                                                                    </div>
                                                                                                </div>
                                                                                            `,
                            iconSize: [0, 0],
                            iconAnchor: [0, 0]
                        });

                        const popupContent = document.createElement('div');
                        const strongTitle = document.createElement('strong');
                        strongTitle.textContent = {!! json_encode($school->name) !!};
                        popupContent.appendChild(strongTitle);
                        popupContent.appendChild(document.createElement('br'));
                        popupContent.appendChild(document.createTextNode(`${lat.toFixed(6)}° N, ${lng.toFixed(6)}° E`));

                        L.marker([lat, lng], { icon: pinIcon })
                            .addTo(showMapInstance)
                            .bindPopup(popupContent)
                            .openPopup();
                    } catch (e) {
                        console.warn('Campus map preview initialization warning:', e);
                    }
                });
            </script>
        @endpush
    @endif
</x-app-layout>