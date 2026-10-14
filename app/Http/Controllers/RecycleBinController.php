<?php

namespace App\Http\Controllers;

use App\Models\AuditLog;
use App\Models\FeasibilityApplication;
use App\Models\School;
use Illuminate\Http\Request;

class RecycleBinController extends Controller
{
    public function index(Request $request)
    {
        abort_unless(auth()->user()->isSuperAdmin(), 403, 'Access denied. Only Super Admin can access the Recycle Bin.');

        $tab = $request->input('tab', 'applications');

        // Query for Trashed Applications
        $appQuery = FeasibilityApplication::onlyTrashed()
            ->with(['school', 'applicant', 'assignedOfficer', 'checklist'])
            ->latest('deleted_at');

        if ($search = $request->input('search')) {
            $appQuery->where(function ($q) use ($search) {
                $q->where('application_number', 'like', "%{$search}%")
                    ->orWhereHas('school', function ($sq) use ($search) {
                        $sq->where('name', 'like', "%{$search}%")
                            ->orWhere('city', 'like', "%{$search}%")
                            ->orWhere('district', 'like', "%{$search}%");
                    });
            });
        }

        $trashedApplications = $appQuery->paginate(15, ['*'], 'applications_page')->withQueryString();

        // Query for Trashed Schools (if any exist)
        $schoolQuery = School::onlyTrashed()->with('user')->latest('deleted_at');
        if ($search) {
            $schoolQuery->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('city', 'like', "%{$search}%")
                    ->orWhere('state', 'like', "%{$search}%");
            });
        }
        $trashedSchools = $schoolQuery->paginate(15, ['*'], 'schools_page')->withQueryString();

        $stats = [
            'total_applications' => FeasibilityApplication::onlyTrashed()->count(),
            'total_schools' => School::onlyTrashed()->count(),
            'recent_deletions_count' => FeasibilityApplication::onlyTrashed()->whereDate('deleted_at', '>=', now()->subDays(7))->count(),
        ];

        return view('recycle_bin.index', compact('trashedApplications', 'trashedSchools', 'stats', 'tab'));
    }

    public function restoreApplication($id)
    {
        abort_unless(auth()->user()->isSuperAdmin(), 403, 'Access denied. Only Super Admin can restore applications.');

        $application = FeasibilityApplication::onlyTrashed()->findOrFail($id);
        $appNumber = $application->application_number;
        $application->restore();

        AuditLog::log('application_restored', $application, null, [
            'application_number' => $appNumber,
            'restored_by' => auth()->user()->name,
        ]);

        return back()->with('success', "Application #{$appNumber} has been successfully restored from the Recycle Bin.");
    }

    public function restoreAllApplications()
    {
        abort_unless(auth()->user()->isSuperAdmin(), 403, 'Access denied. Only Super Admin can restore applications.');

        $trashed = FeasibilityApplication::onlyTrashed()->get();
        $count = $trashed->count();

        foreach ($trashed as $app) {
            $app->restore();
            AuditLog::log('application_restored', $app, null, [
                'application_number' => $app->application_number,
                'restored_by' => auth()->user()->name,
            ]);
        }

        return back()->with('success', "Successfully restored {$count} application(s) from the Recycle Bin.");
    }

    public function restoreSchool($id)
    {
        abort_unless(auth()->user()->isSuperAdmin(), 403, 'Access denied. Only Super Admin can restore schools.');

        $school = School::onlyTrashed()->findOrFail($id);
        $schoolName = $school->name;
        $school->restore();

        AuditLog::log('school_restored', $school, null, [
            'school_name' => $schoolName,
            'restored_by' => auth()->user()->name,
        ]);

        return back()->with('success', "School '{$schoolName}' has been successfully restored from the Recycle Bin.");
    }
}
