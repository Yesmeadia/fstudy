<x-app-layout>
    <x-slot name="title">Executive Dashboard — School Feasibility Study Portal</x-slot>

    <div class="space-y-6">
        <!-- Page Header -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold text-slate-900 tracking-tight">Feasibility Study Executive Dashboard</h1>
                <p class="text-sm text-slate-500 mt-1">Overview of school feasibility applications, field evaluations, and approval pipelines.</p>
            </div>
            <div class="flex items-center gap-3">
                <a href="{{ route('reports.export.csv') }}" class="inline-flex items-center gap-2 px-3.5 py-2 text-xs font-semibold text-slate-700 bg-white border border-slate-300 rounded-lg hover:bg-slate-50 transition shadow-xs">
                    <svg class="w-4 h-4 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    Export Applications
                </a>

                <a href="{{ route('applications.create') }}" class="inline-flex items-center gap-2 px-4 py-2 text-xs font-semibold text-white bg-indigo-600 hover:bg-indigo-500 rounded-lg transition shadow-sm">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    New Application
                </a>
            </div>
        </div>

        <!-- Pending Action Banners (if any) -->
        @if (!empty($pendingActions) && array_sum($pendingActions) > 0)
            <div class="bg-gradient-to-r from-indigo-500/10 via-indigo-500/5 to-transparent border-l-4 border-indigo-600 rounded-r-xl p-4 bg-white shadow-xs">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 rounded-lg bg-indigo-100 text-indigo-700 flex items-center justify-center shrink-0">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div class="flex-1">
                        <h4 class="text-sm font-semibold text-slate-900">Immediate Actions Requiring Attention</h4>
                        <div class="flex flex-wrap gap-4 mt-1 text-xs text-slate-600">
                            @if (!empty($pendingActions['submitted_review']))
                                <span><strong class="text-indigo-700 font-bold">{{ $pendingActions['submitted_review'] }}</strong> completed checklists awaiting Super Admin takeover review</span>
                            @endif
                            @if (!empty($pendingActions['assigned_drafts']))
                                <span><strong class="text-amber-700 font-bold">{{ $pendingActions['assigned_drafts'] }}</strong> inspection checklists assigned to you pending completion / revision</span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        @endif

        <!-- Primary KPI Metrics Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            <x-stat-card label="Total Feasibility Inspections" :value="$totalApps" subtext="Running school takeover files" color="blue">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
            </x-stat-card>

            <x-stat-card label="Awaiting Super Admin Review" :value="$submittedApps" subtext="Submitted by inspectors" color="purple">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                </svg>
            </x-stat-card>

            <x-stat-card label="Approved Takeovers" :value="$approvedApps + $conditionallyApprovedApps" subtext="{{ $approvedApps }} Full, {{ $conditionallyApprovedApps }} Conditional" color="emerald">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </x-stat-card>

            <x-stat-card label="Average Compliance Rate" :value="$avgScore > 0 ? $avgScore . '%' : 'N/A'" subtext="10-section checklist benchmark" color="amber">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.488 9H15V3.512A9.025 9.025 0 0120.488 9z" />
                </svg>
            </x-stat-card>
        </div>

        <!-- Status Breakdown Grid -->
        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-3">
            <div class="bg-white border border-slate-200/80 rounded-xl p-3.5 shadow-xs">
                <div class="text-[11px] font-semibold text-slate-500 uppercase">Drafts</div>
                <div class="text-xl font-bold text-slate-700 mt-1">{{ $draftApps }}</div>
            </div>
            <div class="bg-white border border-slate-200/80 rounded-xl p-3.5 shadow-xs">
                <div class="text-[11px] font-semibold text-blue-600 uppercase">Submitted</div>
                <div class="text-xl font-bold text-blue-700 mt-1">{{ $submittedApps }}</div>
            </div>
            <div class="bg-white border border-slate-200/80 rounded-xl p-3.5 shadow-xs">
                <div class="text-[11px] font-semibold text-amber-600 uppercase">Revision Needed</div>
                <div class="text-xl font-bold text-amber-700 mt-1">{{ $returnedApps }}</div>
            </div>
            <div class="bg-white border border-slate-200/80 rounded-xl p-3.5 shadow-xs">
                <div class="text-[11px] font-semibold text-emerald-600 uppercase">Approved</div>
                <div class="text-xl font-bold text-emerald-700 mt-1">{{ $approvedApps }}</div>
            </div>
            <div class="bg-white border border-slate-200/80 rounded-xl p-3.5 shadow-xs">
                <div class="text-[11px] font-semibold text-purple-600 uppercase">Conditional</div>
                <div class="text-xl font-bold text-purple-700 mt-1">{{ $conditionallyApprovedApps }}</div>
            </div>
            <div class="bg-white border border-slate-200/80 rounded-xl p-3.5 shadow-xs">
                <div class="text-[11px] font-semibold text-rose-600 uppercase">Rejected</div>
                <div class="text-xl font-bold text-rose-700 mt-1">{{ $rejectedApps }}</div>
            </div>
        </div>

        <!-- Recent Applications Table Section -->
        <x-card title="Recent Feasibility Applications" subtitle="Latest applications undergoing assessment, verification, or management decision" noPadding="true">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm text-slate-600">
                    <thead class="bg-slate-50/80 text-xs uppercase font-semibold text-slate-500 border-b border-slate-100">
                        <tr>
                            <th class="px-5 py-3.5">Application</th>
                            <th class="px-5 py-3.5">School Name</th>
                            <th class="px-5 py-3.5">Location</th>
                            <th class="px-5 py-3.5">Status</th>
                            <th class="px-5 py-3.5">Score</th>
                            <th class="px-5 py-3.5">Assigned Officer</th>
                            <th class="px-5 py-3.5 text-right">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse ($recentApplications as $app)
                            <tr class="hover:bg-slate-50/60 transition">
                                <td class="px-5 py-3.5">
                                    <span class="font-mono text-xs font-semibold text-indigo-600">{{ $app->application_number }}</span>
                                    <span class="block text-[11px] text-slate-400">{{ $app->created_at->format('d M Y') }}</span>
                                </td>
                                <td class="px-5 py-3.5">
                                    <div class="font-medium text-slate-900 leading-snug">{{ $app->school->name }}</div>
                                    <div class="text-xs text-slate-500">{{ $app->school->management_organization }}</div>
                                </td>
                                <td class="px-5 py-3.5">
                                    <div class="text-slate-900">{{ $app->school->city }}, {{ $app->school->district }}</div>
                                    <div class="text-xs text-slate-400">{{ $app->school->state }}</div>
                                </td>
                                <td class="px-5 py-3.5">
                                    <x-badge :variant="$app->status->color()">
                                        {{ $app->status->label() }}
                                    </x-badge>
                                </td>
                                <td class="px-5 py-3.5">
                                    @if ($app->overall_score)
                                        <div class="font-bold text-slate-900">{{ $app->overall_score }}%</div>
                                        <div class="text-[11px] text-slate-500 capitalize">{{ str_replace('_', ' ', $app->feasibility_rating) }}</div>
                                    @else
                                        <span class="text-xs text-slate-400">Incomplete</span>
                                    @endif
                                </td>
                                <td class="px-5 py-3.5">
                                    <span class="text-xs font-medium text-slate-700">
                                        {{ $app->assignedOfficer?->name ?? 'Unassigned' }}
                                    </span>
                                </td>
                                <td class="px-5 py-3.5 text-right">
                                    <div class="flex items-center justify-end gap-2">
                                        @if ($app->canBeEdited())
                                            <a href="{{ route('applications.wizard.step', [$app, $app->current_step]) }}" class="text-xs font-semibold text-indigo-600 hover:text-indigo-800 bg-indigo-50 hover:bg-indigo-100 px-2.5 py-1 rounded-md transition">
                                                Resume
                                            </a>
                                        @endif
                                        <a href="{{ route('applications.show', $app) }}" class="text-xs font-semibold text-slate-700 hover:text-slate-900 bg-slate-100 hover:bg-slate-200 px-2.5 py-1 rounded-md transition">
                                            View
                                        </a>
                                        @if ($app->overall_score)
                                            <a href="{{ route('reports.application.pdf', $app) }}" title="Download PDF Report" class="text-xs font-semibold text-rose-700 bg-rose-50 hover:bg-rose-100 px-2 py-1 rounded-md transition">
                                                PDF
                                            </a>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-5 py-8 text-center text-slate-400">
                                    No feasibility applications submitted yet.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <x-slot name="footer">
                <span>Showing latest {{ $recentApplications->count() }} records</span>
                <a href="{{ route('applications.index') }}" class="font-semibold text-indigo-600 hover:text-indigo-700">View All Applications &rarr;</a>
            </x-slot>
        </x-card>
    </div>
</x-app-layout>
