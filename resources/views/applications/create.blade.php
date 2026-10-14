<x-app-layout>
    <x-slot name="title">Initiate Takeover Feasibility Inspection — YES INDIA FOUNDATION</x-slot>

    @php
        $schools = $schools ?? collect();
        $inspectors = $inspectors ?? collect();
        if (! isset($schoolsWithActiveApplications)) {
            $schoolIdsWithActiveApplication = \App\Models\FeasibilityApplication::where('status', '!=', \App\Enums\ApplicationStatus::CANCELLED->value)
                ->pluck('school_id')
                ->unique();
            $currentUser = auth()->user();
            if ($currentUser) {
                $baseQuery = ($currentUser->isSuperAdmin() || $currentUser->hasPermissionTo('schools.manage'))
                    ? \App\Models\School::query()
                    : \App\Models\School::where('user_id', $currentUser->id);
                $schoolsWithActiveApplications = $baseQuery->whereIn('id', $schoolIdsWithActiveApplication)->get();
            } else {
                $schoolsWithActiveApplications = collect();
            }
        }
    @endphp

    <!-- Full-Width Page Canvas -->
    <div class="w-full space-y-6">
        <!-- Top Institutional Header Card -->
        <div class="w-full bg-white rounded-2xl border border-slate-200/90 shadow-xs p-6 sm:p-8">
            <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4">
                <div class="space-y-1.5">
                    <div class="flex items-center gap-2.5 flex-wrap">
                        <span class="text-xs text-slate-400 font-medium">&bull; Institutional Takeover Feasibility
                            Portal</span>
                    </div>
                    <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight">
                        Initiate Running School Takeover Feasibility Inspection
                    </h1>
                    <p class="text-xs sm:text-sm text-slate-500 max-w-3xl">
                        Launch the comprehensive feasibility audit covering statutory recognition, physical campus
                        infrastructure, enrolment rosters, staffing liabilities, and financial viability.
                    </p>
                </div>

                <div class="flex items-center gap-3 shrink-0">
                    <a href="{{ route('applications.index') }}"
                        class="px-4 py-2.5 text-xs font-semibold text-slate-700 bg-white border border-slate-300 rounded-xl hover:bg-slate-50 hover:border-slate-400 transition shadow-xs flex items-center gap-2">
                        <svg class="w-4 h-4 text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                        </svg>
                        <span>All Inspections</span>
                    </a>
                    <a href="{{ route('schools.create') }}"
                        class="px-4 py-2.5 text-xs font-semibold text-white bg-indigo-600 hover:bg-indigo-500 rounded-xl shadow-xs transition flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                        <span>Register New School</span>
                    </a>
                </div>
            </div>

            <!-- Workflow Progress Indicator Steps -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-3 mt-6 pt-6 border-t border-slate-100">
                <div
                    class="flex items-center gap-3 p-3 rounded-xl {{ $schools->isEmpty() && $schoolsWithActiveApplications->isEmpty() ? 'bg-amber-50/80 border border-amber-200/80 text-amber-900' : ($schools->isEmpty() ? 'bg-slate-50 border border-slate-200/80 text-slate-500' : 'bg-emerald-50/80 border border-emerald-200/80 text-emerald-900') }}">
                    <div
                        class="w-8 h-8 rounded-lg {{ $schools->isEmpty() && $schoolsWithActiveApplications->isEmpty() ? 'bg-amber-500 text-white' : ($schools->isEmpty() ? 'bg-slate-200 text-slate-600' : 'bg-emerald-600 text-white') }} flex items-center justify-center font-bold text-xs shrink-0">
                        1
                    </div>
                    <div class="min-w-0">
                        <p class="text-xs font-bold leading-tight">School Profile</p>
                        <p
                            class="text-[10px] {{ $schools->isEmpty() && $schoolsWithActiveApplications->isEmpty() ? 'text-amber-700' : ($schools->isEmpty() ? 'text-slate-400' : 'text-emerald-700') }} truncate">
                            {{ $schools->isEmpty() && $schoolsWithActiveApplications->isEmpty() ? 'Required first step' : ($schools->isEmpty() ? 'All schools already have reports' : 'Available schools (' . $schools->count() . ')') }}
                        </p>
                    </div>
                </div>

                <div
                    class="flex items-center gap-3 p-3 rounded-xl {{ $schools->isEmpty() ? 'bg-slate-50 border border-slate-200/80 text-slate-500' : 'bg-indigo-50/80 border border-indigo-200/80 text-indigo-900' }}">
                    <div
                        class="w-8 h-8 rounded-lg {{ $schools->isEmpty() ? 'bg-slate-200 text-slate-600' : 'bg-indigo-600 text-white' }} flex items-center justify-center font-bold text-xs shrink-0">
                        2
                    </div>
                    <div class="min-w-0">
                        <p class="text-xs font-bold leading-tight">Inspection Assignment</p>
                        <p
                            class="text-[10px] {{ $schools->isEmpty() ? 'text-slate-400' : 'text-indigo-700' }} truncate">
                            {{ $schools->isEmpty() ? 'Awaiting available school' : 'Assign lead inspector' }}
                        </p>
                    </div>
                </div>

                <div
                    class="flex items-center gap-3 p-3 rounded-xl bg-slate-50 border border-slate-200/80 text-slate-500">
                    <div
                        class="w-8 h-8 rounded-lg bg-slate-200 text-slate-600 flex items-center justify-center font-bold text-xs shrink-0">
                        3
                    </div>
                    <div class="min-w-0">
                        <p class="text-xs font-bold leading-tight">13-Section Feasibility Audit</p>
                        <p class="text-[10px] text-slate-400 truncate">On-site audit & scores</p>
                    </div>
                </div>

                <div
                    class="flex items-center gap-3 p-3 rounded-xl bg-slate-50 border border-slate-200/80 text-slate-500">
                    <div
                        class="w-8 h-8 rounded-lg bg-slate-200 text-slate-600 flex items-center justify-center font-bold text-xs shrink-0">
                        4
                    </div>
                    <div class="min-w-0">
                        <p class="text-xs font-bold leading-tight">Governing Board Decision</p>
                        <p class="text-[10px] text-slate-400 truncate">PDF report & approval</p>
                    </div>
                </div>
            </div>
        </div>

        @if ($schools->isEmpty() && $schoolsWithActiveApplications->isEmpty())
            <!-- Full-Width Onboarding Hero Card (When No School Exists) -->
            <div class="w-full bg-white rounded-3xl border border-slate-200/90 shadow-sm overflow-hidden">
                <div class="p-8 sm:p-12">
                    <div class="max-w-4xl mx-auto text-center space-y-4">
                        <div
                            class="w-16 h-16 rounded-2xl bg-indigo-50 border border-indigo-100 text-indigo-600 flex items-center justify-center mx-auto shadow-xs">
                            <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75"
                                    d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                            </svg>
                        </div>
                        <div class="space-y-1">
                            <h2 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight">
                                No School Registered Yet
                            </h2>
                            <p class="text-sm text-slate-500 max-w-2xl mx-auto leading-relaxed">
                                Before initiating an official feasibility inspection, create the school profile with its
                                statutory affiliation, governing trust details, and campus address.
                            </p>
                        </div>

                        <div class="pt-3">
                            <a href="{{ route('schools.create') }}"
                                class="inline-flex items-center gap-3 px-7 py-3.5 text-sm font-bold text-white bg-indigo-600 hover:bg-indigo-500 rounded-2xl shadow-lg shadow-indigo-600/25 hover:shadow-indigo-600/35 transition transform hover:-translate-y-0.5">
                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 4v16m8-8H4" />
                                </svg>
                                <span>Register School Profile First</span>
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M14 5l7 7m0 0l-7 7m7-7H3" />
                                </svg>
                            </a>
                        </div>
                    </div>

                    <!-- 3 Explanatory Informational Cards across the full width -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-12 pt-10 border-t border-slate-100">
                        <div class="bg-slate-50/70 rounded-2xl p-6 border border-slate-200/70 space-y-2.5">
                            <div
                                class="w-10 h-10 rounded-xl bg-blue-100 text-blue-700 flex items-center justify-center font-bold text-sm">
                                01
                            </div>
                            <h3 class="text-sm font-bold text-slate-900">Institutional Identity</h3>
                            <p class="text-xs text-slate-500 leading-relaxed">
                                Enter the school's legal registration name, affiliation board (CBSE, ICSE, State Board),
                                composite type (K-12, Senior Secondary), and medium of instruction.
                            </p>
                        </div>

                        <div class="bg-slate-50/70 rounded-2xl p-6 border border-slate-200/70 space-y-2.5">
                            <div
                                class="w-10 h-10 rounded-xl bg-indigo-100 text-indigo-700 flex items-center justify-center font-bold text-sm">
                                02
                            </div>
                            <h3 class="text-sm font-bold text-slate-900">Governing Trust & Society</h3>
                            <p class="text-xs text-slate-500 leading-relaxed">
                                Capture the legal entity controlling the institution (Public Trust, Educational Society,
                                Section 8 Company), registration numbers, and applicant credentials.
                            </p>
                        </div>

                        <div class="bg-slate-50/70 rounded-2xl p-6 border border-slate-200/70 space-y-2.5">
                            <div
                                class="w-10 h-10 rounded-xl bg-emerald-100 text-emerald-700 flex items-center justify-center font-bold text-sm">
                                03
                            </div>
                            <h3 class="text-sm font-bold text-slate-900">Physical Campus Location</h3>
                            <p class="text-xs text-slate-500 leading-relaxed">
                                Specify the exact campus street address, city, district, state, and pincode to establish
                                regional jurisdiction and catchment area mapping.
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Preview Banner for the 13 Feasibility Sections -->
                <div class="bg-slate-900 text-slate-300 p-6 sm:p-8">
                    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-5">
                        <div>
                            <span class="text-[10px] font-bold text-cyan-400 tracking-wider uppercase">Standardised
                                Inspection Scope</span>
                            <h3 class="text-lg font-bold text-white tracking-tight">
                                Official 13-Section Feasibility Format Ready to Launch
                            </h3>
                        </div>
                        <span class="text-xs text-slate-400">Standardised across all YES India Foundation takeover
                            appraisals</span>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3 text-xs">
                        <div class="p-3 rounded-xl bg-slate-800/80 border border-slate-700/80 text-slate-300">
                            <span class="font-bold text-cyan-400">1.</span> Statutory Recognition & NOCs
                        </div>
                        <div class="p-3 rounded-xl bg-slate-800/80 border border-slate-700/80 text-slate-300">
                            <span class="font-bold text-cyan-400">2.</span> Rooms; Dimensions & Capacity
                        </div>
                        <div class="p-3 rounded-xl bg-slate-800/80 border border-slate-700/80 text-slate-300">
                            <span class="font-bold text-cyan-400">3.</span> Land Title & Building Safety
                        </div>
                        <div class="p-3 rounded-xl bg-slate-800/80 border border-slate-700/80 text-slate-300">
                            <span class="font-bold text-cyan-400">4.</span> Class-wise Student Data
                        </div>
                        <div class="p-3 rounded-xl bg-slate-800/80 border border-slate-700/80 text-slate-300">
                            <span class="font-bold text-cyan-400">5.</span> Admissions & Trend Dynamics
                        </div>
                        <div class="p-3 rounded-xl bg-slate-800/80 border border-slate-700/80 text-slate-300">
                            <span class="font-bold text-cyan-400">6.</span> Curriculum
                        </div>
                        <div class="p-3 rounded-xl bg-slate-800/80 border border-slate-700/80 text-slate-300">
                            <span class="font-bold text-cyan-400">7.</span> Teaching Staff & Payroll
                        </div>
                        <div class="p-3 rounded-xl bg-slate-800/80 border border-slate-700/80 text-slate-300">
                            <span class="font-bold text-cyan-400">8.</span> Financial Audit & Accounts
                        </div>
                        <div class="p-3 rounded-xl bg-slate-800/80 border border-slate-700/80 text-slate-300">
                            <span class="font-bold text-cyan-400">9.</span> Brand Reputation & Goodwill
                        </div>
                        <div class="p-3 rounded-xl bg-slate-800/80 border border-slate-700/80 text-slate-300">
                            <span class="font-bold text-cyan-400">10.</span> Primary Section Competencies
                        </div>
                        <div class="p-3 rounded-xl bg-slate-800/80 border border-slate-700/80 text-slate-300">
                            <span class="font-bold text-cyan-400">11.</span> Middle Section Competencies
                        </div>
                        <div class="p-3 rounded-xl bg-slate-800/80 border border-slate-700/80 text-slate-300">
                            <span class="font-bold text-cyan-400">12.</span> Secondary Exam Benchmarks
                        </div>
                        <div
                            class="p-3 rounded-xl bg-slate-800/80 border border-slate-700/80 text-slate-300 sm:col-span-2 lg:col-span-4">
                            <span class="font-bold text-cyan-400">13.</span> Final Risk Appraisal & Feasibility
                            Recommendations
                        </div>
                    </div>
                </div>
            </div>
        @elseif ($schools->isEmpty() && $schoolsWithActiveApplications->isNotEmpty())
            {{-- All schools already have an active report --}}
            <div class="w-full bg-white rounded-3xl border border-amber-200/80 shadow-sm overflow-hidden">
                <div class="p-8 sm:p-12">
                    <div class="max-w-4xl mx-auto text-center space-y-4">
                        <div
                            class="w-16 h-16 rounded-2xl bg-amber-50 border border-amber-100 text-amber-600 flex items-center justify-center mx-auto shadow-xs">
                            <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75"
                                    d="M12 9v2m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z" />
                            </svg>
                        </div>
                        <div class="space-y-1">
                            <h2 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight">
                                All Schools Already Have Active Reports
                            </h2>
                            <p class="text-sm text-slate-500 max-w-2xl mx-auto leading-relaxed">
                                Every registered school already has an ongoing or submitted feasibility report.
                                Only one active report is permitted per school. To start a new report, the existing
                                one must first be cancelled or completed.
                            </p>
                        </div>
                        <div class="pt-3 flex items-center justify-center gap-3 flex-wrap">
                            <a href="{{ route('applications.index') }}"
                                class="inline-flex items-center gap-2 px-6 py-3 text-sm font-bold text-white bg-indigo-600 hover:bg-indigo-500 rounded-2xl shadow-lg shadow-indigo-600/25 transition transform hover:-translate-y-0.5">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                </svg>
                                <span>View All Inspections</span>
                            </a>
                            <a href="{{ route('schools.create') }}"
                                class="inline-flex items-center gap-2 px-6 py-3 text-sm font-bold text-slate-700 bg-white border border-slate-300 rounded-2xl hover:bg-slate-50 transition">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                </svg>
                                <span>Register New School</span>

                            </a>
                        </div>
                    </div>

                    {{-- List existing reports --}}
                    <div class="mt-10 pt-8 border-t border-slate-100 space-y-3">
                        <p class="text-xs font-bold text-slate-500 uppercase tracking-wider mb-4">Schools with Active Reports</p>
                        @foreach ($schoolsWithActiveApplications as $school)
                            @php $app = $school->applications->first(); @endphp
                            @if ($app)
                                <div class="flex items-center justify-between gap-4 p-4 bg-slate-50 rounded-xl border border-slate-200/80">
                                    <div class="min-w-0">
                                        <p class="text-sm font-semibold text-slate-900 truncate">{{ $school->name }}</p>
                                        <p class="text-xs text-slate-400">{{ $school->city }}, {{ $school->state }}</p>
                                    </div>
                                    <div class="flex items-center gap-3 shrink-0">
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-[11px] font-semibold border {{ $app->status?->badgeClass() ?? 'bg-slate-100 text-slate-700' }}">{{ $app->status?->label() ?? 'Active' }}</span>
                                        <a href="{{ route('applications.show', $app) }}" class="text-xs text-indigo-600 hover:text-indigo-700 font-semibold hover:underline">View Report →</a>
                                    </div>
                                </div>
                            @endif
                        @endforeach
                    </div>
                </div>
            </div>
        @else
            <!-- Full-Width Form Grid when Schools Exist -->
            <form method="POST" action="{{ route('applications.store') }}" class="space-y-6">
                @csrf

                <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
                    <!-- Left Column: Primary Selection Form (7 cols) -->
                    <div class="lg:col-span-7 space-y-6">
                        <div class="bg-white rounded-2xl border border-slate-200/90 p-6 sm:p-7 shadow-xs space-y-5">
                            <div class="border-b border-slate-100 pb-4 flex items-center justify-between">
                                <div class="flex items-center gap-2.5">
                                    <div
                                        class="w-8 h-8 rounded-lg bg-indigo-100 text-indigo-600 flex items-center justify-center font-bold">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                        </svg>
                                    </div>
                                    <div>
                                        <h2 class="text-sm font-bold text-slate-900">Select Target Institution</h2>
                                        <p class="text-xs text-slate-400">Choose from registered school profiles</p>
                                    </div>
                                </div>
                                <a href="{{ route('schools.create') }}"
                                    class="text-xs text-indigo-600 hover:text-indigo-700 font-semibold hover:underline flex items-center gap-1">
                                    <span>+ Add New School</span>
                                </a>
                            </div>

                            <div class="space-y-4">
                                <div>
                                    <label for="school_id" class="block text-xs font-semibold text-slate-700 mb-1.5">
                                        Proposed Institution / School <span class="text-rose-500">*</span>
                                    </label>
                                    <select name="school_id" id="school_id" required
                                        class="w-full text-sm rounded-xl border-slate-300 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 py-3 shadow-xs">
                                        @foreach ($schools as $school)
                                            <option value="{{ $school->id }}" {{ (string) old('school_id', request()->query('school_id')) === (string) $school->id ? 'selected' : '' }}>
                                                {{ $school->name }} ({{ $school->city }}, {{ $school->state }}) —
                                                {{ $school->management_organization ?? 'Management' }}
                                                [{{ strtoupper($school->board?->value ?? $school->board ?? 'BOARD') }}]
                                            </option>
                                        @endforeach
                                    </select>
                                    <p class="text-[11px] text-slate-400 mt-1.5">
                                        Select the school entity undergoing takeover evaluation.
                                    </p>
                                </div>

                                {{-- Informational: schools that already have an active report --}}
                                @if ($schoolsWithActiveApplications->isNotEmpty())
                                    <div class="pt-3 border-t border-slate-100">
                                        <p class="text-[11px] font-semibold text-slate-500 mb-2.5 flex items-center gap-1.5">
                                            <svg class="w-3.5 h-3.5 text-amber-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M12 9v2m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z" />
                                            </svg>
                                            Schools not available — already have an active report:
                                        </p>
                                        <div class="space-y-2">
                                            @foreach ($schoolsWithActiveApplications as $blockedSchool)
                                                @php $app = $blockedSchool->applications->first(); @endphp
                                                @if ($app)
                                                    <div class="flex items-center justify-between gap-3 px-3 py-2 bg-amber-50/60 border border-amber-200/70 rounded-lg">
                                                        <span class="text-xs text-slate-700 font-medium truncate">{{ $blockedSchool->name }}</span>
                                                        <div class="flex items-center gap-2 shrink-0">
                                                            <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-semibold border {{ $app->status?->badgeClass() ?? 'bg-slate-100 text-slate-700' }}">{{ $app->status?->label() ?? 'Active' }}</span>
                                                            <a href="{{ route('applications.show', $app) }}" class="text-[10px] text-indigo-600 hover:underline font-semibold">View →</a>
                                                        </div>
                                                    </div>
                                                @endif
                                            @endforeach
                                        </div>
                                    </div>
                                @endif

                                @if (auth()->user()->isSuperAdmin())
                                    <div class="pt-3 border-t border-slate-100">
                                        <label for="assigned_to" class="block text-xs font-semibold text-slate-700 mb-1.5">
                                            Assign Lead Inspector
                                        </label>
                                        <select name="assigned_to" id="assigned_to"
                                            class="w-full text-sm rounded-xl border-slate-300 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 py-3 shadow-xs">
                                            <option value="">— Unassigned (Assign Inspector Later) —</option>
                                            @foreach ($inspectors as $inspector)
                                                <option value="{{ $inspector->id }}" {{ old('assigned_to') == $inspector->id ? 'selected' : '' }}>
                                                    {{ $inspector->name }} ({{ $inspector->designation ?? 'Inspector' }}) &bull;
                                                    {{ $inspector->email }}
                                                </option>
                                            @endforeach
                                        </select>
                                        <p class="text-[11px] text-slate-400 mt-1.5">
                                            The assigned inspector will be responsible for conducting on-site verification and
                                            completing the 13 feasibility sections.
                                        </p>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <!-- Primary Inspection Details Card -->
                        <div class="bg-white rounded-2xl border border-slate-200/90 p-6 sm:p-7 shadow-xs space-y-5">
                            <div class="border-b border-slate-100 pb-4 flex items-center justify-between">
                                <div class="flex items-center gap-2.5">
                                    <div
                                        class="w-8 h-8 rounded-lg bg-emerald-100 text-emerald-700 flex items-center justify-center font-bold">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                                        </svg>
                                    </div>
                                    <div>
                                        <h2 class="text-sm font-bold text-slate-900">Primary Inspection Details</h2>
                                        <p class="text-xs text-slate-400">Institutional context, schedule &amp; evaluation team</p>
                                    </div>
                                </div>
                                <span class="text-[11px] font-semibold text-rose-600 bg-rose-50 border border-rose-200/80 px-2.5 py-1 rounded-lg">
                                    Mandatory
                                </span>
                            </div>

                            <div class="space-y-4 text-xs">
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                    <div class="sm:col-span-2">
                                        <label for="institution_name" class="block font-semibold text-slate-700 mb-1.5">
                                            Proposed Institution / School Name <span class="text-rose-500">*</span>
                                        </label>
                                        <input type="text" name="institution_name" id="institution_name" required
                                            value="{{ old('institution_name') }}"
                                            class="w-full text-xs rounded-xl border-slate-300 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 py-2.5 px-3 shadow-xs">
                                        @error('institution_name')
                                            <p class="text-rose-500 text-[11px] mt-1">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <div>
                                        <label for="location" class="block font-semibold text-slate-700 mb-1.5">
                                            Campus Location &amp; Jurisdiction <span class="text-rose-500">*</span>
                                        </label>
                                        <input type="text" name="location" id="location" required
                                            value="{{ old('location') }}"
                                            placeholder="e.g. Kozhikode, Kerala"
                                            class="w-full text-xs rounded-xl border-slate-300 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 py-2.5 px-3 shadow-xs">
                                        @error('location')
                                            <p class="text-rose-500 text-[11px] mt-1">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <div>
                                        <label for="present_management" class="block font-semibold text-slate-700 mb-1.5">
                                            Present Owner / Management <span class="text-rose-500">*</span>
                                        </label>
                                        <input type="text" name="present_management" id="present_management" required
                                            value="{{ old('present_management') }}"
                                            placeholder="e.g. Educational Trust / Society"
                                            class="w-full text-xs rounded-xl border-slate-300 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 py-2.5 px-3 shadow-xs">
                                        @error('present_management')
                                            <p class="text-rose-500 text-[11px] mt-1">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <div>
                                        <label for="inspection_dates" class="block font-semibold text-slate-700 mb-1.5">
                                            Date(s) of Feasibility Inspection <span class="text-rose-500">*</span>
                                        </label>
                                        <input type="text" name="inspection_dates" id="inspection_dates" required
                                            value="{{ old('inspection_dates', date('d F Y')) }}"
                                            placeholder="e.g. 24–25 September 2026"
                                            class="w-full text-xs rounded-xl border-slate-300 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 py-2.5 px-3 shadow-xs">
                                        @error('inspection_dates')
                                            <p class="text-rose-500 text-[11px] mt-1">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <div>
                                        <label for="inspection_team" class="block font-semibold text-slate-700 mb-1.5">
                                            Team Members of Inspection <span class="text-rose-500">*</span>
                                        </label>
                                        <input type="text" name="inspection_team" id="inspection_team" required
                                            value="{{ old('inspection_team', auth()->user()->name) }}"
                                            placeholder="Lead Inspector &amp; Accompanying Officers"
                                            class="w-full text-xs rounded-xl border-slate-300 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 py-2.5 px-3 shadow-xs">
                                        @error('inspection_team')
                                            <p class="text-rose-500 text-[11px] mt-1">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Action Controls -->
                        <div
                            class="flex items-center justify-between gap-4 bg-white rounded-2xl border border-slate-200/90 p-5 shadow-xs">
                            <a href="{{ route('applications.index') }}"
                                class="px-5 py-2.5 text-xs font-semibold text-slate-700 bg-white border border-slate-300 rounded-xl hover:bg-slate-50 transition">
                                Cancel
                            </a>
                            <button type="submit"
                                class="px-6 py-3 text-xs font-bold text-white bg-indigo-600 hover:bg-indigo-500 rounded-xl shadow-lg shadow-indigo-600/20 hover:shadow-indigo-600/30 transition flex items-center gap-2">
                                <span>Initiate & Open Takeover Checklist</span>
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M14 5l7 7m0 0l-7 7m7-7H3" />
                                </svg>
                            </button>
                        </div>
                    </div>

                    <!-- Right Column: Inspection Scope & Briefing (5 cols) -->
                    <div class="lg:col-span-5 space-y-6">
                        <div
                            class="bg-indigo-950 text-indigo-100 rounded-2xl p-6 border border-indigo-900 shadow-sm space-y-4">
                            <div class="flex items-center gap-2.5">
                                <div
                                    class="w-8 h-8 rounded-lg bg-indigo-800 text-indigo-300 flex items-center justify-center font-bold">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                </div>
                                <h3 class="font-bold text-sm text-white">Official 13-Section Scope</h3>
                            </div>
                            <p class="text-xs text-indigo-200 leading-relaxed">
                                Once initiated, a unique inspection dossier is created containing the standardised YES India
                                Foundation feasibility framework:
                            </p>
                            <div class="grid grid-cols-1 gap-2 text-[11px] text-indigo-200">
                                <div class="flex items-center gap-2">
                                    <span
                                        class="w-5 h-5 rounded-full bg-indigo-900 text-cyan-400 font-bold flex items-center justify-center text-[10px]">1</span>
                                    <span>Legal Identity & Statutory Recognition</span>
                                </div>
                                <div class="flex items-center gap-2">
                                    <span
                                        class="w-5 h-5 rounded-full bg-indigo-900 text-cyan-400 font-bold flex items-center justify-center text-[10px]">2</span>
                                    <span>Rooms; Dimensions & Capacity</span>
                                </div>
                                <div class="flex items-center gap-2">
                                    <span
                                        class="w-5 h-5 rounded-full bg-indigo-900 text-cyan-400 font-bold flex items-center justify-center text-[10px]">3</span>
                                    <span>Campus Infrastructure & Structural Safety</span>
                                </div>
                                <div class="flex items-center gap-2">
                                    <span
                                        class="w-5 h-5 rounded-full bg-indigo-900 text-cyan-400 font-bold flex items-center justify-center text-[10px]">4</span>
                                    <span>Class-wise Student Data</span>
                                </div>
                                <div class="flex items-center gap-2">
                                    <span
                                        class="w-5 h-5 rounded-full bg-indigo-900 text-cyan-400 font-bold flex items-center justify-center text-[10px]">5</span>
                                    <span>Student Population & Admission Trends</span>
                                </div>
                                <div class="flex items-center gap-2">
                                    <span
                                        class="w-5 h-5 rounded-full bg-indigo-900 text-cyan-400 font-bold flex items-center justify-center text-[10px]">6</span>
                                    <span>Curriculum</span>
                                </div>
                                <div class="flex items-center gap-2">
                                    <span
                                        class="w-5 h-5 rounded-full bg-indigo-900 text-cyan-400 font-bold flex items-center justify-center text-[10px]">7</span>
                                    <span>Teaching Staff, HR & Payroll Liabilities</span>
                                </div>
                                <div class="flex items-center gap-2">
                                    <span
                                        class="w-5 h-5 rounded-full bg-indigo-900 text-cyan-400 font-bold flex items-center justify-center text-[10px]">8</span>
                                    <span>Financial Assessment (Income & Exp.)</span>
                                </div>
                                <div class="flex items-center gap-2">
                                    <span
                                        class="w-5 h-5 rounded-full bg-indigo-900 text-cyan-400 font-bold flex items-center justify-center text-[10px]">9</span>
                                    <span>Branding, Reputation, Community & Goodwill</span>
                                </div>
                                <div class="flex items-center gap-2">
                                    <span
                                        class="w-5 h-5 rounded-full bg-indigo-900 text-cyan-400 font-bold flex items-center justify-center text-[10px]">10-12</span>
                                    <span>Primary, Middle & Secondary Academic Benchmarks</span>
                                </div>
                                <div class="flex items-center gap-2">
                                    <span
                                        class="w-5 h-5 rounded-full bg-indigo-900 text-cyan-400 font-bold flex items-center justify-center text-[10px]">13</span>
                                    <span>Final Takeover Risk Appraisal & Sign-off</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        @endif
    </div>

    @push('scripts')
        <script>
            const schoolsMap = @json($schools->keyBy('id'));
            const preselectedSchoolId = '{{ request()->query('school_id', old('school_id', '')) }}';

            function syncSchoolPrimaryDetails() {
                const select = document.getElementById('school_id');
                if (!select) return;
                const schoolId = select.value;
                const school = schoolsMap[schoolId];
                if (!school) return;

                const nameInput = document.getElementById('institution_name');
                const locInput = document.getElementById('location');
                const mgmtInput = document.getElementById('present_management');

                if (nameInput && (!nameInput.value || nameInput.dataset.auto === '1' || nameInput.value === nameInput.dataset.prev)) {
                    nameInput.value = school.name;
                    nameInput.dataset.auto = '1';
                    nameInput.dataset.prev = school.name;
                }
                if (locInput && (!locInput.value || locInput.dataset.auto === '1' || locInput.value === locInput.dataset.prev)) {
                    const loc = [school.address, school.city, school.state].filter(Boolean).join(', ');
                    locInput.value = loc;
                    locInput.dataset.auto = '1';
                    locInput.dataset.prev = loc;
                }
                if (mgmtInput && (!mgmtInput.value || mgmtInput.dataset.auto === '1' || mgmtInput.value === mgmtInput.dataset.prev)) {
                    mgmtInput.value = school.management_organization || '';
                    mgmtInput.dataset.auto = '1';
                    mgmtInput.dataset.prev = school.management_organization || '';
                }
            }

            document.addEventListener('DOMContentLoaded', function() {
                const select = document.getElementById('school_id');
                if (select) {
                    if (preselectedSchoolId && select.querySelector(`option[value="${preselectedSchoolId}"]`)) {
                        select.value = preselectedSchoolId;
                    }
                    select.addEventListener('change', syncSchoolPrimaryDetails);
                    syncSchoolPrimaryDetails();
                }
            });
        </script>
    @endpush
</x-app-layout>