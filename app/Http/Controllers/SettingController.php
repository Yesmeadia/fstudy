<?php

namespace App\Http\Controllers;

use App\Models\AuditLog;
use App\Models\EvaluationCriterion;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;

class SettingController extends Controller
{
    public function index()
    {
        abort_unless(auth()->user()?->isSuperAdmin() || auth()->user()?->isAdmin(), 403, 'Access denied. Super Admin only.');

        $settings = Setting::all()->groupBy('group');
        $criteria = EvaluationCriterion::orderBy('sort_order')->get();

        return view('settings.index', compact('settings', 'criteria'));
    }

    public function update(Request $request)
    {
        abort_unless(auth()->user()?->isSuperAdmin() || auth()->user()?->isAdmin(), 403, 'Access denied. Super Admin only.');

        $data = $request->except(['_token', '_method', 'criteria_weights']);

        foreach ($data as $key => $value) {
            Setting::set($key, $value);
        }

        // Update evaluation criteria weights if provided
        if ($request->has('criteria_weights')) {
            foreach ($request->input('criteria_weights', []) as $critId => $weight) {
                EvaluationCriterion::where('id', $critId)->update(['weight_percentage' => (float) $weight]);
            }
        }

        AuditLog::log('settings_updated', null, null, ['updated_by' => auth()->user()->name]);

        return back()->with('success', 'Portal configuration and criteria weights saved successfully.');
    }

    /**
     * Trigger on-demand compliance data retention purge.
     */
    public function purgeExpiredData(Request $request)
    {
        abort_unless(auth()->user()?->isSuperAdmin() || auth()->user()?->isAdmin(), 403, 'Access denied. Super Admin only.');

        Artisan::call('compliance:purge-expired');
        $output = trim(Artisan::output());

        return back()->with('success', 'Statutory data retention purge executed successfully.');
    }

    /**
     * Record a security incident or suspected breach for statutory reporting (CERT-In / DPDP Act).
     */
    public function recordIncident(Request $request)
    {
        abort_unless(auth()->user()?->isSuperAdmin() || auth()->user()?->isAdmin(), 403, 'Access denied. Super Admin only.');

        $validated = $request->validate([
            'incident_title' => ['required', 'string', 'max:255'],
            'severity' => ['required', 'in:low,medium,high,critical'],
            'affected_systems' => ['required', 'string', 'max:255'],
            'incident_details' => ['required', 'string'],
            'containment_actions' => ['nullable', 'string'],
        ]);

        AuditLog::log(
            action: 'security_incident_reported',
            model: null,
            newValues: array_merge($validated, [
                'reported_by' => auth()->user()->name,
                'reporter_email' => auth()->user()->email,
                'certin_reporting_deadline' => now()->addHours(6)->toDateTimeString(),
                'dpdp_reporting_deadline' => now()->addHours(72)->toDateTimeString(),
            ])
        );

        return back()->with('success', "Security incident [{$validated['incident_title']}] logged into immutable audit trail. Mandatory CERT-In (6h) and DPDP (72h) response protocols activated.");
    }
}
