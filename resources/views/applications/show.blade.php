<x-app-layout>
    <x-slot name="title">{{ $application->application_number }} — Takeover Feasibility Assessment</x-slot>

    <!-- Full-Width Page Canvas -->
    <div class="w-full space-y-6 pb-16">
        <!-- Master Application Header -->
        <div class="bg-white rounded-2xl border border-slate-200/90 p-6 shadow-sm">
            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-6">
                <div>
                    <div class="flex items-center gap-3 flex-wrap">
                        <span class="font-mono text-xs font-extrabold text-indigo-600 bg-indigo-50 px-2.5 py-1 rounded-md border border-indigo-100 uppercase tracking-wider">
                            {{ $application->application_number }}
                        </span>
                        <x-badge :variant="$application->status->color()" size="lg">
                            {{ $application->status->label() }}
                        </x-badge>
                        @if ($checklist->overall_recommendation)
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold
                                {{ $checklist->overall_recommendation === 'recommended' ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' :
                                   ($checklist->overall_recommendation === 'conditionally_recommended' ? 'bg-amber-50 text-amber-700 border border-amber-200' : 'bg-rose-50 text-rose-700 border border-rose-200') }}">
                                {{ ucfirst(str_replace('_', ' ', $checklist->overall_recommendation)) }}
                            </span>
                        @endif
                    </div>

                    <h1 class="text-2xl font-black text-slate-900 tracking-tight mt-2.5">
                        {{ $checklist->institution_name ?? ($application->school?->name ?? 'Unknown School') }}
                    </h1>
                    <p class="text-xs text-slate-500 mt-1">
                        Management: <strong class="text-slate-700">{{ $checklist->present_management ?? ($application->school?->management_organization ?? '—') }}</strong> &bull;
                        Location: <span class="text-slate-700">{{ $checklist->location ?? (($application->school ? $application->school->city . ', ' . $application->school->state : '—')) }}</span> &bull;
                        Lead Inspector: <strong class="text-indigo-600">{{ $application->assignedOfficer?->name ?? 'Unassigned' }}</strong>
                    </p>
                </div>

                <!-- Action Buttons -->
                <div class="flex items-center gap-2 flex-wrap">
                    @if ($application->canBeEdited() || auth()->user()->isSuperAdmin())
                        <a href="{{ route('applications.checklist.edit', $application) }}"
                           class="inline-flex items-center gap-2 px-4 py-2 text-xs font-bold text-white bg-indigo-600 hover:bg-indigo-500 rounded-xl shadow-sm transition">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                            </svg>
                            Edit Checklist Form
                        </a>
                    @endif

                    <a href="{{ route('reports.application.pdf', $application) }}"
                       class="inline-flex items-center gap-2 px-3.5 py-2 text-xs font-bold text-rose-700 bg-rose-50 hover:bg-rose-100 border border-rose-200 rounded-xl transition">
                        <svg class="w-4 h-4 text-rose-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        Download PDF
                    </a>

                    <a href="{{ route('reports.application.view', $application) }}" target="_blank"
                       class="inline-flex items-center gap-2 px-3.5 py-2 text-xs font-bold text-slate-700 bg-white hover:bg-slate-50 border border-slate-300 rounded-xl transition">
                        <svg class="w-4 h-4 text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                        </svg>
                        Print Preview
                    </a>

                    @if (auth()->user()->isSuperAdmin())
                        @if ($application->isArchived())
                            <form method="POST" action="{{ route('applications.unarchive', $application) }}" class="inline" id="show-unarchive-app">
                                @csrf
                                <button type="button"
                                    onclick="window.customConfirm('Do you want to restore application #{{ $application->application_number }} from Archive?', { title: 'Unarchive Application', confirmText: 'Yes, Unarchive', cancelText: 'Cancel', type: 'primary' }).then(ok => { if (ok) document.getElementById('show-unarchive-app').submit(); })"
                                    class="inline-flex items-center gap-2 px-3.5 py-2 text-xs font-bold text-amber-700 bg-amber-50 hover:bg-amber-100 border border-amber-200 rounded-xl transition cursor-pointer">
                                    <svg class="w-4 h-4 text-amber-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                                    </svg>
                                    Unarchive
                                </button>
                            </form>
                        @else
                            <form method="POST" action="{{ route('applications.archive', $application) }}" class="inline" id="show-archive-app">
                                @csrf
                                <button type="button"
                                    onclick="window.customConfirm('Are you sure you want to archive application #{{ $application->application_number }}?', { title: 'Archive Application', subtext: 'Archived applications remain viewable and can be restored at any time.', confirmText: 'Yes, Archive', cancelText: 'Cancel', type: 'warning' }).then(ok => { if (ok) document.getElementById('show-archive-app').submit(); })"
                                    class="inline-flex items-center gap-2 px-3.5 py-2 text-xs font-bold text-slate-700 bg-slate-100 hover:bg-slate-200 border border-slate-300 rounded-xl transition cursor-pointer">
                                    <svg class="w-4 h-4 text-slate-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4" />
                                    </svg>
                                    Archive
                                </button>
                            </form>
                        @endif

                        <form method="POST" action="{{ route('applications.destroy', $application) }}" class="inline" id="show-delete-app">
                            @csrf
                            @method('DELETE')
                            <button type="button"
                                onclick="window.customConfirm('Are you sure you want to move application #{{ $application->application_number }} to the Recycle Bin?', { title: 'Move to Recycle Bin', subtext: 'This application will be safely soft-deleted and can be restored from the Recycle Bin at any time.', confirmText: 'Yes, Move to Trash', cancelText: 'Cancel', type: 'danger' }).then(ok => { if (ok) document.getElementById('show-delete-app').submit(); })"
                                class="inline-flex items-center gap-2 px-3.5 py-2 text-xs font-bold text-rose-700 bg-rose-50 hover:bg-rose-100 border border-rose-200 rounded-xl transition cursor-pointer">
                                <svg class="w-4 h-4 text-rose-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                </svg>
                                Delete
                            </button>
                        </form>
                    @endif
                </div>
            </div>

            <!-- KPI Metric Bar -->
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 mt-6 pt-5 border-t border-slate-100 text-xs">
                <div class="bg-slate-50 rounded-xl p-3 border border-slate-100">
                    <span class="text-slate-500 block text-[11px] font-semibold uppercase">Compliance Rate</span>
                    <span class="text-lg font-extrabold text-indigo-700 mt-0.5 block">{{ $complianceSummary['percentage'] }}%</span>
                    <span class="text-[10px] text-slate-400">{{ $complianceSummary['yes'] }} of {{ $complianceSummary['total'] }} verified</span>
                </div>

                <div class="bg-slate-50 rounded-xl p-3 border border-slate-100">
                    <span class="text-slate-500 block text-[11px] font-semibold uppercase">Verified Yes</span>
                    <span class="text-lg font-extrabold text-emerald-600 mt-0.5 block">{{ $complianceSummary['yes'] }}</span>
                    <span class="text-[10px] text-slate-400">Compliant criteria</span>
                </div>

                <div class="bg-slate-50 rounded-xl p-3 border border-slate-100">
                    <span class="text-slate-500 block text-[11px] font-semibold uppercase">Deficiencies (No)</span>
                    <span class="text-lg font-extrabold text-rose-600 mt-0.5 block">{{ $complianceSummary['no'] }}</span>
                    <span class="text-[10px] text-slate-400">Items requiring action</span>
                </div>

                <div class="bg-slate-50 rounded-xl p-3 border border-slate-100">
                    <span class="text-slate-500 block text-[11px] font-semibold uppercase">Inspection Dates</span>
                    <span class="text-xs font-bold text-slate-800 mt-1 block truncate">{{ $checklist->inspection_dates ?: 'Not recorded' }}</span>
                    <span class="text-[10px] text-slate-400">On-site audit</span>
                </div>
            </div>
        </div>

        <!-- Super Admin Decision & Assignment Control Panel -->
        @if (auth()->user()->isSuperAdmin())
            <div class="bg-gradient-to-r from-slate-900 to-indigo-950 text-white rounded-2xl p-6 shadow-md space-y-4">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 border-b border-slate-800 pb-3">
                    <div>
                        <h3 class="text-sm font-extrabold tracking-wide uppercase text-indigo-300 flex items-center gap-2">
                            <svg class="w-4 h-4 text-indigo-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                            </svg>
                            Super Admin Governance & Approval Actions
                        </h3>
                        <p class="text-xs text-slate-400 mt-0.5">Assign inspectors, make takeover approvals, or request corrections.</p>
                    </div>

                    <!-- Assign Inspector Form -->
                    <form method="POST" action="{{ route('applications.assign', $application) }}" class="flex items-center gap-2">
                        @csrf
                        <select name="assigned_to" class="text-xs rounded-xl bg-slate-800 border-slate-700 text-slate-200 focus:border-indigo-400 focus:ring-indigo-400 py-1.5">
                            <option value="">— Reassign Inspector —</option>
                            @foreach ($availableOfficers as $officer)
                                <option value="{{ $officer->id }}" {{ $application->assigned_to === $officer->id ? 'selected' : '' }}>
                                    {{ $officer->name }} ({{ $officer->designation ?? 'Inspector' }})
                                </option>
                            @endforeach
                        </select>
                        <button type="submit" class="px-3 py-1.5 text-xs font-semibold bg-indigo-600 hover:bg-indigo-500 rounded-xl transition">
                            Assign
                        </button>
                    </form>
                </div>

                <!-- Decision Action Form -->
                <form method="POST" action="{{ route('applications.status', $application) }}" class="space-y-3">
                    @csrf
                    <div>
                        <label class="block text-xs font-semibold text-slate-300 mb-1">Takeover Decision Remarks / Conditions</label>
                        <input type="text" name="remarks" placeholder="Enter board resolution notes or conditions..."
                               class="w-full text-xs rounded-xl bg-slate-800/80 border-slate-700 text-white placeholder-slate-500 focus:border-indigo-400 focus:ring-indigo-400">
                    </div>

                    <div class="flex flex-wrap items-center gap-3 pt-1">
                        <button type="submit" name="status" value="approved"
                                class="px-4 py-2 text-xs font-bold text-white bg-emerald-600 hover:bg-emerald-500 rounded-xl transition shadow-xs flex items-center gap-1.5">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                            Approve Takeover
                        </button>

                        <button type="submit" name="status" value="conditionally_approved"
                                class="px-4 py-2 text-xs font-bold text-white bg-purple-600 hover:bg-purple-500 rounded-xl transition shadow-xs">
                            Conditionally Approve
                        </button>

                        <button type="submit" name="status" value="returned_for_correction"
                                class="px-4 py-2 text-xs font-bold text-white bg-amber-600 hover:bg-amber-500 rounded-xl transition shadow-xs">
                            Return to Inspector for Correction
                        </button>

                        <button type="button"
                                onclick="window.customConfirm('Are you sure you want to reject this takeover application?', { title: 'Reject Takeover Application', subtext: 'This action will reject the feasibility study and transition proposal.', confirmText: 'Yes, Reject Application', cancelText: 'Cancel', type: 'danger' }).then(ok => { if (ok) { const f = this.closest('form'); const input = document.createElement('input'); input.type = 'hidden'; input.name = 'status'; input.value = 'rejected'; f.appendChild(input); f.submit(); } });"
                                class="px-4 py-2 text-xs font-bold text-white bg-rose-600 hover:bg-rose-500 rounded-xl transition shadow-xs cursor-pointer">
                            Reject Takeover
                        </button>
                    </div>
                </form>

                <div class="border-t border-slate-800/80 pt-3.5 flex flex-wrap items-center justify-between gap-3 text-xs">
                    <div class="text-slate-400 flex items-center gap-1.5">
                        <span class="text-slate-500">Record Lifecycle:</span>
                        @if ($application->isArchived())
                            <span class="inline-flex items-center px-2 py-0.5 rounded text-[11px] font-bold bg-amber-500/20 text-amber-300 border border-amber-500/30">
                                Archived
                            </span>
                        @else
                            <span class="inline-flex items-center px-2 py-0.5 rounded text-[11px] font-bold bg-emerald-500/20 text-emerald-300 border border-emerald-500/30">
                                Active Proposal
                            </span>
                        @endif
                    </div>
                    <div class="flex items-center gap-2">
                        @if ($application->isArchived())
                            <button type="button"
                                onclick="document.getElementById('show-unarchive-app')?.querySelector('button')?.click()"
                                class="px-3 py-1.5 font-semibold text-amber-300 bg-amber-950/50 hover:bg-amber-900/70 border border-amber-500/40 rounded-xl transition cursor-pointer">
                                Unarchive Proposal
                            </button>
                        @else
                            <button type="button"
                                onclick="document.getElementById('show-archive-app')?.querySelector('button')?.click()"
                                class="px-3 py-1.5 font-semibold text-slate-300 bg-slate-800 hover:bg-slate-700 border border-slate-700 rounded-xl transition cursor-pointer">
                                Archive Proposal
                            </button>
                        @endif
                        <button type="button"
                            onclick="document.getElementById('show-delete-app')?.querySelector('button')?.click()"
                            class="px-3 py-1.5 font-semibold text-rose-300 bg-rose-950/50 hover:bg-rose-900/70 border border-rose-500/40 rounded-xl transition cursor-pointer">
                            Move to Recycle Bin
                        </button>
                    </div>
                </div>
            </div>
        @endif

        <!-- Document Metadata Header Table (Direct match to Image 1) -->
        <div class="bg-white rounded-2xl border border-slate-200/90 shadow-sm p-6 space-y-4">
            <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                <h3 class="text-xs font-extrabold uppercase tracking-wider text-slate-800">
                    YES India Foundation &bull; RUNNING SCHOOL TAKEOVER / FEASIBILITY ASSESSMENT CHECKLIST
                </h3>
                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Confidential Working Document</span>
            </div>

            <table class="w-full text-left text-xs border border-slate-300">
                <tr class="border-b border-slate-300">
                    <td class="w-1/3 px-4 py-3 font-bold bg-slate-50 text-slate-700 border-r border-slate-300">Proposed Institution / School</td>
                    <td class="px-4 py-3 font-semibold text-slate-900">{{ $checklist->institution_name ?? ($application->school?->name ?? '—') }}</td>
                </tr>
                <tr class="border-b border-slate-300">
                    <td class="px-4 py-3 font-bold bg-slate-50 text-slate-700 border-r border-slate-300">Location</td>
                    <td class="px-4 py-3 text-slate-800">{{ $checklist->location ?? (($application->school ? $application->school->city . ', ' . $application->school->state : '—')) }}</td>
                </tr>
                <tr class="border-b border-slate-300">
                    <td class="px-4 py-3 font-bold bg-slate-50 text-slate-700 border-r border-slate-300">Present Owner / Management</td>
                    <td class="px-4 py-3 text-slate-800">{{ $checklist->present_management ?? ($application->school?->management_organization ?? '—') }}</td>
                </tr>
                <tr class="border-b border-slate-300">
                    <td class="px-4 py-3 font-bold bg-slate-50 text-slate-700 border-r border-slate-300">Date(s) of feasibility inspection</td>
                    <td class="px-4 py-3 text-slate-800 font-semibold">{{ $checklist->inspection_dates ?: 'Pending record' }}</td>
                </tr>
                <tr>
                    <td class="px-4 py-3 font-bold bg-slate-50 text-slate-700 border-r border-slate-300">Team members of inspection</td>
                    <td class="px-4 py-3 text-slate-800">{{ $checklist->inspection_team ?? ($application->assignedOfficer?->name ?? 'Inspector') }}</td>
                </tr>
            </table>
        </div>

        <!-- 10 Checklist Sections Display -->
        <div class="space-y-6">
            <!-- 1. LEGAL IDENTITY -->
            <div class="bg-white rounded-2xl border border-slate-200/90 shadow-sm overflow-hidden">
                <div class="px-6 py-3.5 bg-slate-900 text-white font-bold text-xs uppercase tracking-wider flex items-center justify-between">
                    <span>1. LEGAL IDENTITY, OWNERSHIP & STATUTORY RECOGNITION</span>
                    <span class="text-[10px] text-slate-400 font-normal">Section 1</span>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs border-collapse">
                        <thead class="bg-slate-100 text-slate-700 uppercase font-bold text-[10px] border-b border-slate-200">
                            <tr>
                                <th class="w-12 px-3 py-2.5 text-center border-r border-slate-200">No.</th>
                                <th class="w-2/5 max-w-sm px-4 py-2.5 border-r border-slate-200">Verification / Point to Check</th>
                                <th class="w-16 px-2 py-2.5 text-center border-r border-slate-200">Yes</th>
                                <th class="w-16 px-2 py-2.5 text-center border-r border-slate-200">No</th>
                                <th class="w-2/5 min-w-[280px] px-4 py-2.5">Remarks / Action Required</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-200">
                            @foreach ($checklist->section1_legal ?? [] as $item)
                                <tr class="hover:bg-slate-50/80 transition">
                                    <td class="px-3 py-2.5 text-center font-bold text-slate-600 bg-slate-50 border-r border-slate-200">{{ $item['no'] }}</td>
                                    <td class="px-4 py-2.5 text-slate-800 border-r border-slate-200">{{ $item['point'] }}</td>
                                    <td class="px-2 py-2.5 text-center border-r border-slate-200 font-bold {{ ($item['compliance'] ?? '') === 'yes' ? 'text-emerald-600 bg-emerald-50' : 'text-slate-300' }}">
                                        {{ ($item['compliance'] ?? '') === 'yes' ? '✓' : '—' }}
                                    </td>
                                    <td class="px-2 py-2.5 text-center border-r border-slate-200 font-bold {{ ($item['compliance'] ?? '') === 'no' ? 'text-rose-600 bg-rose-50' : 'text-slate-300' }}">
                                        {{ ($item['compliance'] ?? '') === 'no' ? '✗' : '—' }}
                                    </td>
                                    <td class="px-4 py-2.5 text-slate-600 italic">{{ $item['remarks'] ?: '—' }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- 2. ROOMS; DIMENSIONS & CAPACITY -->
            <div class="bg-white rounded-2xl border border-slate-200/90 shadow-sm overflow-hidden">
                <div class="px-6 py-3.5 bg-slate-900 text-white font-bold text-xs uppercase tracking-wider flex items-center justify-between">
                    <span>2. ROOMS; DIMENSIONS & CAPACITY</span>
                    <span class="text-[10px] text-slate-400 font-normal">Section 2</span>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs border-collapse">
                        <thead class="bg-slate-100 text-slate-700 uppercase font-bold text-[10px] border-b border-slate-200">
                            <tr>
                                <th class="px-4 py-2.5">TYPE (classroom/office/hall size)</th>
                                <th class="w-24 px-3 py-2.5 text-center">Number of Rooms</th>
                                <th class="w-28 px-3 py-2.5">SIZE In feet</th>
                                <th class="px-4 py-2.5">CURRENT USE</th>
                                <th class="px-4 py-2.5">FURNITURE</th>
                                <th class="w-2/5 min-w-[280px] px-4 py-2.5">Remarks (ventilation, lighting)</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-200">
                            @forelse ($checklist->section3_rooms ?? [] as $room)
                                <tr class="hover:bg-slate-50/80 transition">
                                    <td class="px-4 py-2.5 font-semibold text-slate-800">{{ $room['type'] ?: '—' }}</td>
                                    <td class="px-3 py-2.5 text-center font-bold text-indigo-700">{{ $room['count'] ?: '—' }}</td>
                                    <td class="px-3 py-2.5 text-slate-700">{{ $room['size_feet'] ?: '—' }}</td>
                                    <td class="px-4 py-2.5 text-slate-700">{{ $room['current_use'] ?: '—' }}</td>
                                    <td class="px-4 py-2.5 text-slate-700">{{ $room['furniture'] ?: '—' }}</td>
                                    <td class="px-4 py-2.5 text-slate-600 italic">{{ $room['remarks'] ?: '—' }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-4 py-4 text-center text-slate-400 italic">No rooms recorded yet.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- 3. PHYSICAL INFRASTRUCTURE & CAMPUS CONDITION -->
            <div class="bg-white rounded-2xl border border-slate-200/90 shadow-sm overflow-hidden">
                <div class="px-6 py-3.5 bg-slate-900 text-white font-bold text-xs uppercase tracking-wider flex items-center justify-between">
                    <span>3. PHYSICAL INFRASTRUCTURE & CAMPUS CONDITION</span>
                    <span class="text-[10px] text-slate-400 font-normal">Section 3</span>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs border-collapse">
                        <thead class="bg-slate-100 text-slate-700 uppercase font-bold text-[10px] border-b border-slate-200">
                            <tr>
                                <th class="w-12 px-3 py-2.5 text-center border-r border-slate-200">No.</th>
                                <th class="w-2/5 max-w-sm px-4 py-2.5 border-r border-slate-200">Verification / Point to Check</th>
                                <th class="w-16 px-2 py-2.5 text-center border-r border-slate-200">Yes</th>
                                <th class="w-16 px-2 py-2.5 text-center border-r border-slate-200">No</th>
                                <th class="w-2/5 min-w-[280px] px-4 py-2.5">Remarks / Action Required</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-200">
                            @foreach ($checklist->section2_infrastructure ?? [] as $item)
                                <tr class="hover:bg-slate-50/80 transition">
                                    <td class="px-3 py-2.5 text-center font-bold text-slate-600 bg-slate-50 border-r border-slate-200">{{ $item['no'] }}</td>
                                    <td class="px-4 py-2.5 text-slate-800 border-r border-slate-200">{{ $item['point'] }}</td>
                                    <td class="px-2 py-2.5 text-center border-r border-slate-200 font-bold {{ ($item['compliance'] ?? '') === 'yes' ? 'text-emerald-600 bg-emerald-50' : 'text-slate-300' }}">
                                        {{ ($item['compliance'] ?? '') === 'yes' ? '✓' : '—' }}
                                    </td>
                                    <td class="px-2 py-2.5 text-center border-r border-slate-200 font-bold {{ ($item['compliance'] ?? '') === 'no' ? 'text-rose-600 bg-rose-50' : 'text-slate-300' }}">
                                        {{ ($item['compliance'] ?? '') === 'no' ? '✗' : '—' }}
                                    </td>
                                    <td class="px-4 py-2.5 text-slate-600 italic">{{ $item['remarks'] ?: '—' }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- 4. STUDENTS ENROLMENT STATEMENT -->
            <div class="bg-white rounded-2xl border border-slate-200/90 shadow-sm overflow-hidden">
                <div class="px-6 py-3.5 bg-slate-900 text-white font-bold text-xs uppercase tracking-wider flex items-center justify-between">
                    <span>4. STUDENTS ENROLMENT STATEMENT</span>
                    <span class="text-[10px] text-slate-400 font-normal">Section 4</span>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs border-collapse">
                        <thead class="bg-slate-100 text-slate-700 uppercase font-bold text-[10px] border-b border-slate-200">
                            <tr>
                                <th class="px-4 py-2.5">CLASS & DIVISION</th>
                                <th class="w-24 px-3 py-2.5 text-center">BOYS</th>
                                <th class="w-24 px-3 py-2.5 text-center">GIRLS</th>
                                <th class="w-24 px-3 py-2.5 text-center bg-slate-200 text-slate-900">TOTAL</th>
                                <th class="w-32 px-3 py-2.5 text-center">LAST YEAR COUNT TOTAL</th>
                                <th class="w-36 px-3 py-2.5 text-center">TREND / STATUS</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-200">
                            @php
                                $sumBoys = 0; $sumGirls = 0; $sumTotal = 0; $sumLast = 0;
                            @endphp
                            @forelse ($checklist->section5_enrolment ?? [] as $row)
                                @php
                                    $b = (int) ($row['boys'] ?? 0);
                                    $g = (int) ($row['girls'] ?? 0);
                                    $t = (int) ($row['total'] ?? ($b + $g));
                                    $l = (int) ($row['last_year_total'] ?? 0);
                                    $sumBoys += $b; $sumGirls += $g; $sumTotal += $t; $sumLast += $l;
                                    $trend = $row['trend'] ?? '';
                                    if (!$trend && ($t > 0 || $l > 0)) {
                                        $trend = $t > $l ? 'Increased' : ($t < $l ? 'Decreased' : 'Stayed same');
                                    }
                                @endphp
                                <tr class="hover:bg-slate-50/80 transition">
                                    <td class="px-4 py-2.5 font-semibold text-slate-800">{{ $row['class_division'] ?: '—' }}</td>
                                    <td class="px-3 py-2.5 text-center text-slate-700">{{ $b ?: '0' }}</td>
                                    <td class="px-3 py-2.5 text-center text-slate-700">{{ $g ?: '0' }}</td>
                                    <td class="px-3 py-2.5 text-center font-extrabold text-indigo-700 bg-indigo-50/40">{{ $t ?: '0' }}</td>
                                    <td class="px-3 py-2.5 text-center text-slate-700">{{ $l ?: '0' }}</td>
                                    <td class="px-3 py-2.5 text-center">
                                        @if($trend === 'Increased')
                                            <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                                Increased &uarr;
                                            </span>
                                        @elseif($trend === 'Decreased')
                                            <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold bg-rose-50 text-rose-700 border border-rose-200">
                                                Decreased &darr;
                                            </span>
                                        @elseif($trend === 'Stayed same')
                                            <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold bg-slate-50 text-slate-700 border border-slate-200">
                                                Stayed same =
                                            </span>
                                        @else
                                            <span class="text-slate-400">—</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-4 py-4 text-center text-slate-400 italic">No enrolment statement recorded.</td>
                                </tr>
                            @endforelse
                        </tbody>
                        <tfoot class="bg-slate-100 font-extrabold border-t-2 border-slate-300">
                            <tr>
                                <td class="px-4 py-2.5 text-slate-900 uppercase">CUMULATIVE TOTAL</td>
                                <td class="px-3 py-2.5 text-center text-slate-800">{{ $sumBoys }}</td>
                                <td class="px-3 py-2.5 text-center text-slate-800">{{ $sumGirls }}</td>
                                <td class="px-3 py-2.5 text-center text-indigo-700 bg-indigo-100">{{ $sumTotal }}</td>
                                <td class="px-3 py-2.5 text-center text-slate-800">{{ $sumLast }}</td>
                                <td class="px-3 py-2.5 text-center">
                                    @php
                                        $totTrend = $sumTotal > $sumLast ? 'Increased' : ($sumTotal < $sumLast ? 'Decreased' : 'Stayed same');
                                    @endphp
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded text-[10px] font-extrabold {{ $totTrend === 'Increased' ? 'bg-emerald-100 text-emerald-800' : ($totTrend === 'Decreased' ? 'bg-rose-100 text-rose-800' : 'bg-slate-200 text-slate-800') }}">
                                        {{ $totTrend }}
                                    </span>
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>

            <!-- 5. STUDENT POPULATION & ADMISSIONS -->
            <div class="bg-white rounded-2xl border border-slate-200/90 shadow-sm overflow-hidden">
                <div class="px-6 py-3.5 bg-slate-900 text-white font-bold text-xs uppercase tracking-wider flex items-center justify-between">
                    <span>5. STUDENT POPULATION & ADMISSIONS</span>
                    <span class="text-[10px] text-slate-400 font-normal">Section 5</span>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs border-collapse">
                        <thead class="bg-slate-100 text-slate-700 uppercase font-bold text-[10px] border-b border-slate-200">
                            <tr>
                                <th class="w-12 px-3 py-2.5 text-center border-r border-slate-200">No.</th>
                                <th class="w-2/5 max-w-sm px-4 py-2.5 border-r border-slate-200">Verification / Point to Check</th>
                                <th class="w-16 px-2 py-2.5 text-center border-r border-slate-200">Yes</th>
                                <th class="w-16 px-2 py-2.5 text-center border-r border-slate-200">No</th>
                                <th class="w-2/5 min-w-[280px] px-4 py-2.5">Remarks / Action Required</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-200">
                            @foreach ($checklist->section4_students ?? [] as $item)
                                <tr class="hover:bg-slate-50/80 transition">
                                    <td class="px-3 py-2.5 text-center font-bold text-slate-600 bg-slate-50 border-r border-slate-200">{{ $item['no'] }}</td>
                                    <td class="px-4 py-2.5 text-slate-800 border-r border-slate-200">{{ $item['point'] }}</td>
                                    <td class="px-2 py-2.5 text-center border-r border-slate-200 font-bold {{ ($item['compliance'] ?? '') === 'yes' ? 'text-emerald-600 bg-emerald-50' : 'text-slate-300' }}">
                                        {{ ($item['compliance'] ?? '') === 'yes' ? '✓' : '—' }}
                                    </td>
                                    <td class="px-2 py-2.5 text-center border-r border-slate-200 font-bold {{ ($item['compliance'] ?? '') === 'no' ? 'text-rose-600 bg-rose-50' : 'text-slate-300' }}">
                                        {{ ($item['compliance'] ?? '') === 'no' ? '✗' : '—' }}
                                    </td>
                                    <td class="px-4 py-2.5 text-slate-600 italic">{{ $item['remarks'] ?: '—' }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- 6. CURRICULUM -->
            <div class="bg-white rounded-2xl border border-slate-200/90 shadow-sm overflow-hidden">
                <div class="px-6 py-3.5 bg-slate-900 text-white font-bold text-xs uppercase tracking-wider flex items-center justify-between">
                    <span>6. CURRICULUM</span>
                    <span class="text-[10px] text-slate-400 font-normal">Section 6</span>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs border-collapse">
                        <thead class="bg-slate-100 text-slate-700 uppercase font-bold text-[10px] border-b border-slate-200">
                            <tr>
                                <th class="w-12 px-3 py-2.5 text-center border-r border-slate-200">No.</th>
                                <th class="w-2/5 max-w-sm px-4 py-2.5 border-r border-slate-200">Verification / Point to Check</th>
                                <th class="w-16 px-2 py-2.5 text-center border-r border-slate-200">Yes</th>
                                <th class="w-16 px-2 py-2.5 text-center border-r border-slate-200">No</th>
                                <th class="w-2/5 min-w-[280px] px-4 py-2.5">Remarks / Action Required</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-200">
                            @foreach ($checklist->section6_academic ?? [] as $item)
                                <tr class="hover:bg-slate-50/80 transition">
                                    <td class="px-3 py-2.5 text-center font-bold text-slate-600 bg-slate-50 border-r border-slate-200">{{ $item['no'] }}</td>
                                    <td class="px-4 py-2.5 text-slate-800 border-r border-slate-200">{{ $item['point'] }}</td>
                                    <td class="px-2 py-2.5 text-center border-r border-slate-200 font-bold {{ ($item['compliance'] ?? '') === 'yes' ? 'text-emerald-600 bg-emerald-50' : 'text-slate-300' }}">
                                        {{ ($item['compliance'] ?? '') === 'yes' ? '✓' : '—' }}
                                    </td>
                                    <td class="px-2 py-2.5 text-center border-r border-slate-200 font-bold {{ ($item['compliance'] ?? '') === 'no' ? 'text-rose-600 bg-rose-50' : 'text-slate-300' }}">
                                        {{ ($item['compliance'] ?? '') === 'no' ? '✗' : '—' }}
                                    </td>
                                    <td class="px-4 py-2.5 text-slate-600 italic">{{ $item['remarks'] ?: '—' }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- 7. STAFF, HR & EMPLOYMENT LIABILITIES -->
            <div class="bg-white rounded-2xl border border-slate-200/90 shadow-sm overflow-hidden">
                <div class="px-6 py-3.5 bg-slate-900 text-white font-bold text-xs uppercase tracking-wider flex items-center justify-between">
                    <span>7. STAFF, HR & EMPLOYMENT LIABILITIES</span>
                    <span class="text-[10px] text-slate-400 font-normal">Section 7</span>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs border-collapse">
                        <thead class="bg-slate-100 text-slate-700 uppercase font-bold text-[10px] border-b border-slate-200">
                            <tr>
                                <th class="w-12 px-3 py-2.5 text-center border-r border-slate-200">No.</th>
                                <th class="w-2/5 max-w-sm px-4 py-2.5 border-r border-slate-200">Verification / Point to Check</th>
                                <th class="w-16 px-2 py-2.5 text-center border-r border-slate-200">Yes</th>
                                <th class="w-16 px-2 py-2.5 text-center border-r border-slate-200">No</th>
                                <th class="w-2/5 min-w-[280px] px-4 py-2.5">Remarks / Action Required</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-200">
                            @foreach ($checklist->section7_staff ?? [] as $item)
                                <tr class="hover:bg-slate-50/80 transition">
                                    <td class="px-3 py-2.5 text-center font-bold text-slate-600 bg-slate-50 border-r border-slate-200">{{ $item['no'] }}</td>
                                    <td class="px-4 py-2.5 text-slate-800 border-r border-slate-200">{{ $item['point'] }}</td>
                                    <td class="px-2 py-2.5 text-center border-r border-slate-200 font-bold {{ ($item['compliance'] ?? '') === 'yes' ? 'text-emerald-600 bg-emerald-50' : 'text-slate-300' }}">
                                        {{ ($item['compliance'] ?? '') === 'yes' ? '✓' : '—' }}
                                    </td>
                                    <td class="px-2 py-2.5 text-center border-r border-slate-200 font-bold {{ ($item['compliance'] ?? '') === 'no' ? 'text-rose-600 bg-rose-50' : 'text-slate-300' }}">
                                        {{ ($item['compliance'] ?? '') === 'no' ? '✗' : '—' }}
                                    </td>
                                    <td class="px-4 py-2.5 text-slate-600 italic">{{ $item['remarks'] ?: '—' }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- 8. FINANCIAL ASSESSMENT -->
            <div class="bg-white rounded-2xl border border-slate-200/90 shadow-sm overflow-hidden">
                <div class="px-6 py-3.5 bg-slate-900 text-white font-bold text-xs uppercase tracking-wider flex items-center justify-between">
                    <span>8. FINANCIAL ASSESSMENT – INCOME & EXPENDITURE</span>
                    <span class="text-[10px] text-slate-400 font-normal">Section 8</span>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs border-collapse">
                        <thead class="bg-slate-100 text-slate-700 uppercase font-bold text-[10px] border-b border-slate-200">
                            <tr>
                                <th class="w-12 px-3 py-2.5 text-center border-r border-slate-200">No.</th>
                                <th class="w-2/5 max-w-sm px-4 py-2.5 border-r border-slate-200">Verification / Point to Check</th>
                                <th class="w-16 px-2 py-2.5 text-center border-r border-slate-200">Yes</th>
                                <th class="w-16 px-2 py-2.5 text-center border-r border-slate-200">No</th>
                                <th class="w-2/5 min-w-[280px] px-4 py-2.5">Remarks / Action Required</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-200">
                            @foreach ($checklist->section8_financial ?? [] as $item)
                                <tr class="hover:bg-slate-50/80 transition">
                                    <td class="px-3 py-2.5 text-center font-bold text-slate-600 bg-slate-50 border-r border-slate-200">{{ $item['no'] }}</td>
                                    <td class="px-4 py-2.5 text-slate-800 border-r border-slate-200">{{ $item['point'] }}</td>
                                    <td class="px-2 py-2.5 text-center border-r border-slate-200 font-bold {{ ($item['compliance'] ?? '') === 'yes' ? 'text-emerald-600 bg-emerald-50' : 'text-slate-300' }}">
                                        {{ ($item['compliance'] ?? '') === 'yes' ? '✓' : '—' }}
                                    </td>
                                    <td class="px-2 py-2.5 text-center border-r border-slate-200 font-bold {{ ($item['compliance'] ?? '') === 'no' ? 'text-rose-600 bg-rose-50' : 'text-slate-300' }}">
                                        {{ ($item['compliance'] ?? '') === 'no' ? '✗' : '—' }}
                                    </td>
                                    <td class="px-4 py-2.5 text-slate-600 italic">{{ $item['remarks'] ?: '—' }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- 9. BRANDING -->
            <div class="bg-white rounded-2xl border border-slate-200/90 shadow-sm overflow-hidden">
                <div class="px-6 py-3.5 bg-slate-900 text-white font-bold text-xs uppercase tracking-wider flex items-center justify-between">
                    <span>9. BRANDING</span>
                    <span class="text-[10px] text-slate-400 font-normal">Section 9</span>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs border-collapse">
                        <thead class="bg-slate-100 text-slate-700 uppercase font-bold text-[10px] border-b border-slate-200">
                            <tr>
                                <th class="w-12 px-3 py-2.5 text-center border-r border-slate-200">No.</th>
                                <th class="w-2/5 max-w-sm px-4 py-2.5 border-r border-slate-200">Verification / Point to Check</th>
                                <th class="w-16 px-2 py-2.5 text-center border-r border-slate-200">Yes</th>
                                <th class="w-16 px-2 py-2.5 text-center border-r border-slate-200">No</th>
                                <th class="w-2/5 min-w-[280px] px-4 py-2.5">Remarks / Action Required</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-200">
                            @foreach ($checklist->section9_brand ?? [] as $item)
                                <tr class="hover:bg-slate-50/80 transition">
                                    <td class="px-3 py-2.5 text-center font-bold text-slate-600 bg-slate-50 border-r border-slate-200">{{ $item['no'] }}</td>
                                    <td class="px-4 py-2.5 text-slate-800 border-r border-slate-200">{{ $item['point'] }}</td>
                                    <td class="px-2 py-2.5 text-center border-r border-slate-200 font-bold {{ ($item['compliance'] ?? '') === 'yes' ? 'text-emerald-600 bg-emerald-50' : 'text-slate-300' }}">
                                        {{ ($item['compliance'] ?? '') === 'yes' ? '✓' : '—' }}
                                    </td>
                                    <td class="px-2 py-2.5 text-center border-r border-slate-200 font-bold {{ ($item['compliance'] ?? '') === 'no' ? 'text-rose-600 bg-rose-50' : 'text-slate-300' }}">
                                        {{ ($item['compliance'] ?? '') === 'no' ? '✗' : '—' }}
                                    </td>
                                    <td class="px-4 py-2.5 text-slate-600 italic">{{ $item['remarks'] ?: '—' }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- 10. PRIMARY QUALITY -->
            <div class="bg-white rounded-2xl border border-slate-200/90 shadow-sm overflow-hidden">
                <div class="px-6 py-3.5 bg-slate-900 text-white font-bold text-xs uppercase tracking-wider flex items-center justify-between">
                    <span>10. PRIMARY QUALITY</span>
                    <span class="text-[10px] text-slate-400 font-normal">Section 10</span>
                </div>
                @php
                    $sec10 = $checklist->section10_primary_assessment;
                    $sec10Classes = $sec10['classes'] ?? ['Class 1', 'Class 2', 'Class 3', 'Class 4', 'Class 5'];
                    $sec10Rows = $sec10['rows'] ?? [];
                @endphp
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs border-collapse">
                        <thead class="bg-slate-100 text-slate-700 uppercase font-bold text-[10px] border-b border-slate-200">
                            <tr>
                                <th rowspan="2" class="px-4 py-2.5 border-r border-slate-200 min-w-[200px]">PARTICULARS</th>
                                <th colspan="5" class="px-2 py-1 text-center border-b border-r border-slate-200 bg-slate-200 text-slate-800 font-black tracking-wider">CLASS</th>
                                <th rowspan="2" class="w-2/5 min-w-[280px] px-4 py-2.5">REMARKS / EVIDENCE / OBSERVATIONS</th>
                            </tr>
                            <tr class="bg-slate-100 text-center font-bold text-[10px]">
                                @for ($c = 0; $c < 5; $c++)
                                    <th class="px-2 py-1 border-r border-slate-200 min-w-[70px] text-slate-700 bg-slate-50">
                                        {{ $sec10Classes[$c] ?? ('Class ' . ($c + 1)) }}
                                    </th>
                                @endfor
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-200">
                            @foreach ($sec10Rows as $item)
                                <tr class="hover:bg-slate-50/80 transition">
                                    <td class="px-4 py-2.5 font-semibold text-slate-800 border-r border-slate-200 bg-slate-50">{{ $item['particular'] ?? '' }}</td>
                                    @for ($c = 1; $c <= 5; $c++)
                                        <td class="px-2 py-2.5 text-center font-bold text-indigo-700 border-r border-slate-200">{{ ($item['c' . $c] ?? '') ?: '—' }}</td>
                                    @endfor
                                    <td class="px-4 py-2.5 text-slate-600 italic">{{ ($item['remarks'] ?? '') ?: '—' }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- 11. SECONDARY QUALITY -->
            <div class="bg-white rounded-2xl border border-slate-200/90 shadow-sm overflow-hidden">
                <div class="px-6 py-3.5 bg-slate-900 text-white font-bold text-xs uppercase tracking-wider flex items-center justify-between">
                    <span>11. SECONDARY QUALITY</span>
                    <span class="text-[10px] text-slate-400 font-normal">Section 11</span>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs border-collapse">
                        <thead class="bg-slate-100 text-slate-700 uppercase font-bold text-[10px] border-b border-slate-200">
                            <tr>
                                <th rowspan="2" class="px-4 py-2.5 border-r border-slate-200 min-w-[200px]">PARTICULARS</th>
                                <th colspan="5" class="px-2 py-1 text-center border-b border-r border-slate-200 bg-slate-200 text-slate-800 font-black tracking-wider">CLASS</th>
                                <th rowspan="2" class="w-2/5 min-w-[280px] px-4 py-2.5">REMARKS / EVIDENCE / OBSERVATIONS</th>
                            </tr>
                            <tr class="bg-slate-100 text-center font-bold text-[10px]">
                                <th class="w-14 py-1 border-r border-slate-200">Class 6</th>
                                <th class="w-14 py-1 border-r border-slate-200">Class 7</th>
                                <th class="w-14 py-1 border-r border-slate-200">Class 8</th>
                                <th class="w-14 py-1 border-r border-slate-200">Class 9</th>
                                <th class="w-14 py-1 border-r border-slate-200">Class 10</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-200">
                            @php
                                $sec11 = $checklist->section11_secondary_assessment;
                                $sec11Rows = isset($sec11['rows']) && is_array($sec11['rows']) ? $sec11['rows'] : (is_array($sec11) ? $sec11 : []);
                            @endphp
                            @foreach ($sec11Rows as $item)
                                <tr class="hover:bg-slate-50/80 transition">
                                    <td class="px-4 py-2.5 font-semibold text-slate-800 border-r border-slate-200 bg-slate-50">{{ $item['particular'] ?? '' }}</td>
                                    @for ($c = 1; $c <= 5; $c++)
                                        <td class="px-2 py-2.5 text-center font-bold text-slate-800 border-r border-slate-200">{{ ($item['c' . $c] ?? '') ?: '—' }}</td>
                                    @endfor
                                    <td class="px-4 py-2.5 text-slate-600 italic">{{ ($item['remarks'] ?? '') ?: '—' }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- 12. TEACHERS' ASSESSMENT -->
            <div class="bg-white rounded-2xl border border-slate-200/90 shadow-sm overflow-hidden">
                <div class="px-6 py-3.5 bg-slate-900 text-white font-bold text-xs uppercase tracking-wider flex items-center justify-between">
                    <span>12. TEACHERS' ASSESSMENT</span>
                    <span class="text-[10px] text-slate-400 font-normal">Section 12</span>
                </div>
                @php
                    $sec12 = $checklist->section12_teachers_assessment;
                    $sec12Teachers = $sec12['teachers'] ?? ['Teacher 1', 'Teacher 2', 'Teacher 3', 'Teacher 4', 'Teacher 5', 'Teacher 6', 'Teacher 7'];
                    $sec12Rows = $sec12['rows'] ?? [];
                @endphp
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs border-collapse">
                        <thead class="bg-slate-100 text-slate-700 uppercase font-bold text-[10px] border-b border-slate-200">
                            <tr>
                                <th rowspan="2" class="px-4 py-2.5 border-r border-slate-200 min-w-[200px]">PARTICULARS</th>
                                <th colspan="7" class="px-2 py-1 text-center border-b border-r border-slate-200 bg-slate-200 text-slate-800 font-black tracking-wider">TEACHER NAME</th>
                                <th rowspan="2" class="w-2/5 min-w-[280px] px-4 py-2.5">REMARKS / EVIDENCE</th>
                            </tr>
                            <tr class="bg-slate-100 text-center font-bold text-[10px]">
                                @for ($t = 0; $t < 7; $t++)
                                    <th class="px-2 py-1 border-r border-slate-200 min-w-[70px] text-slate-700 bg-slate-50">
                                        {{ $sec12Teachers[$t] ?? ('Teacher ' . ($t + 1)) }}
                                    </th>
                                @endfor
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-200">
                            @foreach ($sec12Rows as $item)
                                <tr class="hover:bg-slate-50/80 transition">
                                    <td class="px-4 py-2.5 font-semibold text-slate-800 border-r border-slate-200 bg-slate-50">{{ $item['particular'] ?? '' }}</td>
                                    @for ($t = 1; $t <= 7; $t++)
                                        <td class="px-1.5 py-2.5 text-center font-bold text-indigo-700 border-r border-slate-200">{{ ($item['t' . $t] ?? '') ?: '—' }}</td>
                                    @endfor
                                    <td class="px-4 py-2.5 text-slate-600 italic">{{ ($item['remarks'] ?? '') ?: '—' }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- 13. EVIDENCE PHOTOS & AUDIT OBSERVATIONS GALLERY (Item 20) -->
            @php
                $evidenceRecords = $checklist->getAllRemarksWithEvidence();
                $photosOnly = array_filter($evidenceRecords, fn($r) => $r['has_photo']);
            @endphp
            <div class="bg-white rounded-2xl border border-slate-200/90 shadow-sm overflow-hidden">
                <div class="px-6 py-3.5 bg-slate-900 text-white font-bold text-xs uppercase tracking-wider flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <svg class="w-4 h-4 text-indigo-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                        <span>13. EVIDENCE PHOTOS & AUDIT OBSERVATIONS GALLERY</span>
                    </div>
                    <span class="text-[10px] text-slate-300 font-semibold bg-slate-800 px-2.5 py-0.5 rounded-full border border-slate-700">
                        {{ count($photosOnly) }} Photo{{ count($photosOnly) === 1 ? '' : 's' }} Attached
                    </span>
                </div>

                <div class="p-6">
                    @if(count($photosOnly) > 0)
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                            @foreach($photosOnly as $rec)
                                <div class="bg-slate-50 border border-slate-200 rounded-xl overflow-hidden hover:shadow-sm transition flex flex-col">
                                    <a href="{{ $rec['photo_url'] }}" target="_blank" class="block aspect-video bg-slate-950/5 relative overflow-hidden group">
                                        <img src="{{ $rec['photo_url'] }}" alt="{{ $rec['item_label'] }}" class="w-full h-full object-cover group-hover:scale-105 transition duration-300">
                                        <div class="absolute inset-0 bg-black/25 opacity-0 group-hover:opacity-100 flex items-center justify-center text-white text-xs font-bold transition">
                                            Open Full Resolution &nearr;
                                        </div>
                                    </a>
                                    <div class="p-3 flex-1 flex flex-col justify-between space-y-2">
                                        <div>
                                            <span class="inline-block px-1.5 py-0.5 rounded text-[9px] font-bold bg-indigo-100 text-indigo-800 uppercase tracking-wider mb-1">
                                                {{ $rec['section_title'] }}
                                            </span>
                                            <h4 class="text-xs font-bold text-slate-900 leading-snug line-clamp-2">
                                                {{ $rec['item_label'] }}
                                            </h4>
                                        </div>
                                        @if(!empty($rec['remarks']))
                                            <div class="bg-white border border-slate-200 rounded-lg p-2 text-[11px] text-slate-700 italic">
                                                "{{ $rec['remarks'] }}"
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-8 text-slate-500 text-xs">
                            <svg class="w-10 h-10 mx-auto text-slate-300 mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                            <p class="font-bold text-slate-700">No Evidence Photos Attached</p>
                            <p class="text-[11px] text-slate-400 mt-0.5">Supporting photos uploaded with inspection remarks will appear in this gallery.</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- 14. FINAL ASSESSMENT REMARKS (Item 20) -->
            <div class="bg-white rounded-2xl border border-slate-200/90 shadow-sm overflow-hidden">
                <div class="px-6 py-3.5 bg-slate-900 text-white font-bold text-xs uppercase tracking-wider flex items-center justify-between">
                    <span>14. FINAL ASSESSMENT REMARKS</span>
                    <span class="text-[10px] text-slate-400 font-normal">Section 14</span>
                </div>
                <div class="p-6 space-y-3 divide-y divide-slate-100 text-xs">
                    @php
                        $finalRemarks = $checklist->section13_final_remarks ?? [];
                        $categories = [
                            'academic' => 'Academic',
                            'financial' => 'Financial',
                            'infrastructural' => 'Infrastructural',
                            'legal' => 'Legal',
                            'administration_staff' => 'Administration Staff',
                            'teaching_staff' => 'Teaching Staff',
                            'safety' => 'Safety',
                        ];
                    @endphp
                    @foreach ($categories as $key => $label)
                        <div class="pt-3 first:pt-0 grid grid-cols-1 sm:grid-cols-4 gap-2">
                            <span class="font-bold text-slate-900 sm:col-span-1">{{ $label }}:</span>
                            <span class="text-slate-700 sm:col-span-3 leading-relaxed">{{ $finalRemarks[$key] ?? '—' }}</span>
                        </div>
                    @endforeach
                    @if (!empty($finalRemarks['signed_by']))
                        <div class="pt-3 grid grid-cols-1 sm:grid-cols-4 gap-2 border-t-2 border-slate-200">
                            <span class="font-bold text-slate-900 sm:col-span-1">SIGNED BY;</span>
                            <span class="font-semibold text-indigo-700 sm:col-span-3">{{ $finalRemarks['signed_by'] }}</span>
                        </div>
                    @endif
                </div>
            </div>

            <!-- 15. INSPECTOR RECOMMENDATION & EXECUTIVE SIGN-OFF (Item 20) -->
            <div class="bg-white rounded-2xl border border-slate-200/90 shadow-sm p-6 space-y-4">
                <div class="px-6 py-3.5 -mx-6 -mt-6 bg-slate-900 text-white font-bold text-xs uppercase tracking-wider flex items-center justify-between rounded-t-2xl mb-4">
                    <span>15. INSPECTOR RECOMMENDATION & EXECUTIVE SIGN-OFF</span>
                    <span class="text-[10px] text-slate-400 font-normal">Section 15</span>
                </div>

                @if ($checklist->overall_summary)
                    <div class="bg-slate-50 rounded-xl p-4 border border-slate-200 text-xs leading-relaxed text-slate-800">
                        <strong class="block text-slate-900 mb-1">Feasibility Conclusion & Takeover Viability:</strong>
                        {{ $checklist->overall_summary }}
                    </div>
                @endif

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 text-xs pt-2">
                    <div>
                        <span class="text-slate-400 block text-[11px]">Final Takeover Recommendation:</span>
                        <span class="font-bold text-slate-900 text-sm">
                            {{ $checklist->overall_recommendation ? ucfirst(str_replace('_', ' ', $checklist->overall_recommendation)) : 'Pending submission' }}
                        </span>
                    </div>

                    <div>
                        <span class="text-slate-400 block text-[11px]">Inspector Signature:</span>
                        <span class="font-bold text-slate-900 text-sm">{{ $checklist->inspector_name ?: 'Pending' }}</span>
                    </div>

                    <div>
                        <span class="text-slate-400 block text-[11px]">Inspection Signed Date:</span>
                        <span class="font-bold text-slate-900 text-sm">
                            {{ $checklist->signed_at ? $checklist->signed_at->format('d M Y, h:i A') : 'Draft status' }}
                        </span>
                    </div>
                </div>
            </div>


            <!-- Status History Timeline -->
            @if ($application->statusHistories->isNotEmpty())
                <div class="bg-white rounded-2xl border border-slate-200/90 shadow-sm p-6 space-y-3">
                    <h3 class="text-xs font-bold text-slate-900 uppercase tracking-wider border-b border-slate-100 pb-2">
                        Audit Trail & Decision History
                    </h3>
                    <div class="space-y-3 text-xs">
                        @foreach ($application->statusHistories as $hist)
                            <div class="flex items-start gap-3">
                                <div class="w-2 h-2 rounded-full bg-indigo-600 mt-1.5 shrink-0"></div>
                                <div>
                                    <span class="font-bold text-slate-900">{{ $hist->new_status instanceof \App\Enums\ApplicationStatus ? $hist->new_status->label() : ucfirst(str_replace('_', ' ', (string) $hist->new_status)) }}</span>
                                    <span class="text-slate-400 text-[11px]">&bull; {{ $hist->created_at->format('d M Y, h:i A') }}</span>
                                    @if ($hist->changer)
                                        <span class="text-indigo-600 font-semibold">by {{ $hist->changer->name }}</span>
                                    @endif
                                    @if ($hist->remarks)
                                        <p class="text-slate-600 text-[11px] mt-0.5">{{ $hist->remarks }}</p>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
