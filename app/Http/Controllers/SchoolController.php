<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreSchoolRequest;
use App\Models\AuditLog;
use App\Models\School;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class SchoolController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        $query = School::withCount('applications')->latest();

        if ($user->isApplicant()) {
            $query->where('user_id', $user->id);
        }

        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('city', 'like', "%{$search}%")
                    ->orWhere('district', 'like', "%{$search}%")
                    ->orWhere('state', 'like', "%{$search}%")
                    ->orWhere('school_code', 'like', "%{$search}%");
            });
        }

        if ($board = $request->input('board')) {
            $query->where('board', $board);
        }

        if ($schoolType = $request->input('school_type')) {
            $query->where('school_type', $schoolType);
        }

        $schools = $query->paginate(10)->withQueryString();

        return view('schools.index', compact('schools'));
    }

    public function create()
    {
        $user = auth()->user();

        return view('schools.create', compact('user'));
    }

    public function store(StoreSchoolRequest $request)
    {
        try {
            $data = $request->validated();
            $data['user_id'] = $request->user()->id;

            if (empty($data['school_code'])) {
                $cleanedState = preg_replace('/[^A-Za-z]/', '', $data['state'] ?? 'IND');
                $statePrefix = strtoupper(substr($cleanedState, 0, 3));
                if (strlen($statePrefix) < 3) {
                    $statePrefix = str_pad($statePrefix, 3, 'X');
                }
                do {
                    $generatedCode = 'SCH-'.$statePrefix.'-'.date('Y').'-'.strtoupper(Str::random(4));
                } while (School::withTrashed()->where('school_code', $generatedCode)->exists());

                $data['school_code'] = $generatedCode;
            }

            // Defensively check if latitude/longitude exist in database schema before saving
            if (! Schema::hasColumn('schools', 'latitude')) {
                unset($data['latitude'], $data['longitude']);
            }

            $school = DB::transaction(function () use ($data) {
                return School::create($data);
            });

            try {
                AuditLog::log('school_created', $school, null, $data);
            } catch (\Throwable $auditError) {
                Log::warning('AuditLog creation failed: '.$auditError->getMessage());
            }

            if ($request->input('action') === 'start_inspection') {
                return redirect()->route('applications.create', ['school_id' => $school->id])
                    ->with('success', "School '{$school->name}' created successfully. Now confirm primary inspection details to begin the feasibility assessment.");
            }

            return redirect()->route('schools.show', $school)
                ->with('success', 'School profile created successfully. You can now initiate a feasibility application.');
        } catch (\Throwable $e) {
            Log::error('Error creating school profile: '.$e->getMessage(), ['exception' => $e]);

            $isSuperAdmin = auth()->check() && (auth()->user()->isSuperAdmin() || auth()->user()->isAdmin());
            $errorMessage = (config('app.debug') || $isSuperAdmin)
                ? 'Unable to create school profile: '.$e->getMessage()
                : 'Unable to create school profile due to an unexpected system error. Please review your inputs and try again.';

            return back()->withInput()->with('error', $errorMessage);
        }
    }

    public function show(School $school)
    {
        $this->authorizeSchoolAccess($school);
        $school->load(['applications.assignedOfficer', 'user']);

        return view('schools.show', compact('school'));
    }

    public function edit(School $school)
    {
        $this->authorizeSchoolAccess($school, true);

        return view('schools.edit', compact('school'));
    }

    public function update(StoreSchoolRequest $request, School $school)
    {
        $this->authorizeSchoolAccess($school, true);

        try {
            $oldData = $school->toArray();
            $data = $request->validated();

            $school->update($data);

            AuditLog::log('school_updated', $school, $oldData, $data);

            return redirect()->route('schools.show', $school)
                ->with('success', 'School profile updated successfully.');
        } catch (\Throwable $e) {
            Log::error('Error updating school profile: '.$e->getMessage(), ['exception' => $e, 'school_id' => $school->id]);

            return back()->withInput()->with('error', 'Unable to update school profile due to an unexpected system error. Please try again.');
        }
    }

    public function destroy(School $school)
    {
        $this->authorizeSchoolAccess($school, true);

        try {
            if ($school->applications()->exists()) {
                return back()->with('error', 'Cannot delete school with existing feasibility study applications.');
            }

            AuditLog::log('school_deleted', $school, $school->toArray(), null);
            $school->delete();

            return redirect()->route('schools.index')->with('success', 'School profile removed successfully.');
        } catch (\Throwable $e) {
            Log::error('Error deleting school profile: '.$e->getMessage(), ['exception' => $e, 'school_id' => $school->id]);

            return back()->with('error', 'Unable to remove school profile: '.$e->getMessage());
        }
    }

    protected function authorizeSchoolAccess(School $school, bool $modify = false): void
    {
        $user = auth()->user();
        if (! $user) {
            abort(401);
        }

        if ($user->isSuperAdmin() || $user->hasPermissionTo('schools.manage')) {
            return;
        }

        if ($school->user_id === $user->id) {
            return;
        }

        abort(403, 'Unauthorized access to this school profile.');
    }
}
