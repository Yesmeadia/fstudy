<x-app-layout>
    <x-slot name="title">Recycle Bin — School Feasibility Portal</x-slot>

    <div class="space-y-6">
        <!-- Header -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <div class="flex items-center gap-2">
                    <span class="inline-flex items-center justify-center p-2 rounded-xl bg-rose-50 text-rose-600 border border-rose-100 shadow-xs">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                        </svg>
                    </span>
                    <h1 class="text-2xl font-bold text-slate-900 tracking-tight">Recycle Bin</h1>
                </div>
                <p class="text-sm text-slate-500 mt-1">
                    Safely inspect and restore soft-deleted feasibility study records. Zero unauthorized data destruction guaranteed.
                </p>
            </div>

            <div class="flex items-center gap-3">
                <a href="{{ route('applications.index') }}"
                    class="inline-flex items-center gap-2 px-3.5 py-2 text-xs font-semibold text-slate-700 bg-white border border-slate-300 rounded-xl hover:bg-slate-50 transition shadow-xs">
                    <svg class="w-4 h-4 text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    Back to Applications
                </a>

                @if ($stats['total_applications'] > 0)
                    <form method="POST" action="{{ route('recycle_bin.restore_all_applications') }}" id="restore-all-form">
                        @csrf
                        <button type="button"
                            onclick="window.customConfirm('Are you sure you want to restore all {{ $stats['total_applications'] }} soft-deleted feasibility applications back to active status?', {
                                title: 'Restore All Applications',
                                subtext: 'All soft-deleted applications will immediately return to active feasibility listings.',
                                confirmText: 'Yes, Restore All',
                                cancelText: 'Cancel',
                                type: 'primary'
                            }).then(confirmed => {
                                if (confirmed) document.getElementById('restore-all-form').submit();
                            })"
                            class="inline-flex items-center gap-2 px-4 py-2 text-xs font-semibold text-white bg-indigo-600 hover:bg-indigo-500 rounded-xl shadow-xs transition cursor-pointer">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                            </svg>
                            Restore All Applications ({{ $stats['total_applications'] }})
                        </button>
                    </form>
                @endif
            </div>
        </div>

        <!-- Metric KPI Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
            <div class="bg-white rounded-2xl border border-slate-200/90 p-5 shadow-xs">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-bold uppercase tracking-wider text-slate-500">Deleted Applications</span>
                    <span class="p-2 rounded-xl bg-rose-50 text-rose-600 border border-rose-100">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                    </span>
                </div>
                <div class="text-3xl font-black text-slate-900 mt-2">{{ $stats['total_applications'] }}</div>
                <p class="text-xs text-slate-400 mt-1">Preserved in trash &bull; restorable at any time</p>
            </div>

            <div class="bg-white rounded-2xl border border-slate-200/90 p-5 shadow-xs">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-bold uppercase tracking-wider text-slate-500">Deleted In Last 7 Days</span>
                    <span class="p-2 rounded-xl bg-amber-50 text-amber-600 border border-amber-100">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </span>
                </div>
                <div class="text-3xl font-black text-slate-900 mt-2">{{ $stats['recent_deletions_count'] }}</div>
                <p class="text-xs text-slate-400 mt-1">Recent removals by Super Admin</p>
            </div>

            <div class="bg-white rounded-2xl border border-slate-200/90 p-5 shadow-xs">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-bold uppercase tracking-wider text-slate-500">Protection Status</span>
                    <span class="p-2 rounded-xl bg-emerald-50 text-emerald-600 border border-emerald-100">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                        </svg>
                    </span>
                </div>
                <div class="text-base font-extrabold text-emerald-700 mt-2">Soft-Delete Only</div>
                <p class="text-xs text-slate-400 mt-1">Zero permanent loss without manual DB approval</p>
            </div>
        </div>

        <!-- Navigation Tabs -->
        <div class="flex items-center gap-2 border-b border-slate-200">
            <a href="{{ route('recycle_bin.index', ['tab' => 'applications']) }}"
                class="px-4 py-2.5 text-xs font-bold border-b-2 transition flex items-center gap-2 {{ $tab !== 'schools' ? 'border-indigo-600 text-indigo-600' : 'border-transparent text-slate-500 hover:text-slate-700' }}">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
                Feasibility Applications
                <span class="px-2 py-0.5 rounded-full text-[10px] {{ $tab !== 'schools' ? 'bg-indigo-50 text-indigo-700' : 'bg-slate-100 text-slate-600' }}">
                    {{ $stats['total_applications'] }}
                </span>
            </a>

            @if ($stats['total_schools'] > 0)
                <a href="{{ route('recycle_bin.index', ['tab' => 'schools']) }}"
                    class="px-4 py-2.5 text-xs font-bold border-b-2 transition flex items-center gap-2 {{ $tab === 'schools' ? 'border-indigo-600 text-indigo-600' : 'border-transparent text-slate-500 hover:text-slate-700' }}">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                    </svg>
                    Schools
                    <span class="px-2 py-0.5 rounded-full text-[10px] {{ $tab === 'schools' ? 'bg-indigo-50 text-indigo-700' : 'bg-slate-100 text-slate-600' }}">
                        {{ $stats['total_schools'] }}
                    </span>
                </a>
            @endif
        </div>

        <!-- Filter Bar -->
        <div class="bg-white rounded-2xl border border-slate-200/80 p-4 shadow-xs">
            <form method="GET" action="{{ route('recycle_bin.index') }}" class="flex flex-col sm:flex-row gap-3">
                <input type="hidden" name="tab" value="{{ $tab }}">
                <div class="flex-1">
                    <input type="text" name="search" value="{{ request('search') }}"
                        placeholder="Search deleted applications by Application #, school name, district, or city..."
                        class="w-full text-xs rounded-xl border-slate-300 focus:border-indigo-500 focus:ring-indigo-500 py-2">
                </div>
                <div class="flex items-center gap-2">
                    <button type="submit"
                        class="py-2 px-4 text-xs font-bold text-white bg-slate-800 hover:bg-slate-700 rounded-xl transition cursor-pointer">
                        Filter
                    </button>
                    @if (request('search'))
                        <a href="{{ route('recycle_bin.index', ['tab' => $tab]) }}"
                            class="py-2 px-3 text-xs font-bold text-slate-600 bg-slate-100 hover:bg-slate-200 rounded-xl transition">
                            Reset
                        </a>
                    @endif
                </div>
            </form>
        </div>

        @if ($tab !== 'schools')
            <!-- Trashed Feasibility Applications Table -->
            <x-card noPadding="true">
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm text-slate-600">
                        <thead class="bg-slate-50 text-xs uppercase font-semibold text-slate-500 border-b border-slate-100">
                            <tr>
                                <th class="px-5 py-3.5">Application #</th>
                                <th class="px-5 py-3.5">School Name</th>
                                <th class="px-5 py-3.5">Location</th>
                                <th class="px-5 py-3.5">Status Prior to Deletion</th>
                                <th class="px-5 py-3.5">Deleted At</th>
                                <th class="px-5 py-3.5 text-right">Action</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @forelse ($trashedApplications as $app)
                                <tr class="hover:bg-slate-50/60 transition">
                                    <td class="px-5 py-4">
                                        <div class="flex items-center gap-2">
                                            <span class="font-mono text-xs font-extrabold text-slate-700">
                                                {{ $app->application_number }}
                                            </span>
                                            <span class="px-1.5 py-0.5 rounded text-[10px] font-bold bg-rose-50 text-rose-700 border border-rose-100">
                                                Trashed
                                            </span>
                                        </div>
                                        <span class="text-[11px] text-slate-400">Created {{ $app->created_at->format('d M Y') }}</span>
                                    </td>
                                    <td class="px-5 py-4">
                                        <div class="font-semibold text-slate-900 leading-snug">
                                            {{ $app->school?->name ?? 'Unknown School' }}
                                        </div>
                                        <div class="text-xs text-slate-400">
                                            {{ $app->school?->management_organization ?? '—' }}
                                        </div>
                                    </td>
                                    <td class="px-5 py-4">
                                        <div class="text-slate-800 text-xs">
                                            {{ $app->school?->city ?? '—' }}{{ $app->school?->district ? ', ' . $app->school->district : '' }}
                                        </div>
                                        <div class="text-[11px] text-slate-400">{{ $app->school?->state ?? '' }}</div>
                                    </td>
                                    <td class="px-5 py-4">
                                        <x-badge :variant="$app->status->color()">
                                            {{ $app->status->label() }}
                                        </x-badge>
                                    </td>
                                    <td class="px-5 py-4">
                                        <div class="text-xs font-medium text-slate-800">
                                            {{ $app->deleted_at->format('d M Y, h:i A') }}
                                        </div>
                                        <div class="text-[10px] text-rose-500 font-semibold">
                                            {{ $app->deleted_at->diffForHumans() }}
                                        </div>
                                    </td>
                                    <td class="px-5 py-4 text-right">
                                        <form method="POST" action="{{ route('recycle_bin.restore_application', $app->id) }}" class="inline-block" id="restore-app-{{ $app->id }}">
                                            @csrf
                                            <button type="button"
                                                onclick="window.customConfirm('Are you sure you want to restore application #{{ $app->application_number }} back to active status?', {
                                                    title: 'Restore Application',
                                                    subtext: 'This will bring {{ $app->application_number }} back into active feasibility inspection lists.',
                                                    confirmText: 'Yes, Restore Application',
                                                    cancelText: 'Cancel',
                                                    type: 'primary'
                                                }).then(ok => { if (ok) document.getElementById('restore-app-{{ $app->id }}').submit(); })"
                                                class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-bold text-emerald-700 bg-emerald-50 hover:bg-emerald-100 border border-emerald-200 rounded-xl transition cursor-pointer shadow-2xs">
                                                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                                                </svg>
                                                Restore
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-5 py-16 text-center">
                                        <div class="w-16 h-16 rounded-3xl bg-slate-50 border border-slate-200 flex items-center justify-center mx-auto text-slate-400 mb-3 shadow-xs">
                                            <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                        </div>
                                        <h3 class="text-sm font-bold text-slate-800">Recycle Bin is empty</h3>
                                        <p class="text-xs text-slate-400 mt-1 max-w-sm mx-auto">
                                            No soft-deleted feasibility applications found. All active reports are intact.
                                        </p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if ($trashedApplications->hasPages())
                    <div class="px-5 py-4 border-t border-slate-100 bg-slate-50/50">
                        {{ $trashedApplications->links() }}
                    </div>
                @endif
            </x-card>
        @else
            <!-- Trashed Schools Table -->
            <x-card noPadding="true">
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm text-slate-600">
                        <thead class="bg-slate-50 text-xs uppercase font-semibold text-slate-500 border-b border-slate-100">
                            <tr>
                                <th class="px-5 py-3.5">School Name</th>
                                <th class="px-5 py-3.5">Location</th>
                                <th class="px-5 py-3.5">Management</th>
                                <th class="px-5 py-3.5">Deleted At</th>
                                <th class="px-5 py-3.5 text-right">Action</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @forelse ($trashedSchools as $sch)
                                <tr class="hover:bg-slate-50/60 transition">
                                    <td class="px-5 py-4 font-semibold text-slate-900">
                                        {{ $sch->name }}
                                    </td>
                                    <td class="px-5 py-4 text-xs text-slate-700">
                                        {{ $sch->city }}, {{ $sch->state }}
                                    </td>
                                    <td class="px-5 py-4 text-xs text-slate-500">
                                        {{ $sch->management_organization ?? '—' }}
                                    </td>
                                    <td class="px-5 py-4 text-xs text-slate-600">
                                        {{ $sch->deleted_at->format('d M Y, h:i A') }}
                                    </td>
                                    <td class="px-5 py-4 text-right">
                                        <form method="POST" action="{{ route('recycle_bin.restore_school', $sch->id) }}" class="inline-block" id="restore-sch-{{ $sch->id }}">
                                            @csrf
                                            <button type="button"
                                                onclick="window.customConfirm('Are you sure you want to restore school \'{{ $sch->name }}\'?', {
                                                    title: 'Restore School',
                                                    subtext: 'This school will be restored back to the active schools directory.',
                                                    confirmText: 'Yes, Restore School',
                                                    cancelText: 'Cancel',
                                                    type: 'primary'
                                                }).then(ok => { if (ok) document.getElementById('restore-sch-{{ $sch->id }}').submit(); })"
                                                class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-bold text-emerald-700 bg-emerald-50 hover:bg-emerald-100 border border-emerald-200 rounded-xl transition cursor-pointer">
                                                Restore
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-5 py-12 text-center text-slate-400">
                                        No soft-deleted schools found in the recycle bin.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if ($trashedSchools->hasPages())
                    <div class="px-5 py-4 border-t border-slate-100 bg-slate-50/50">
                        {{ $trashedSchools->links() }}
                    </div>
                @endif
            </x-card>
        @endif
    </div>
</x-app-layout>
