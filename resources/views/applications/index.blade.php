<x-app-layout>
    <x-slot name="title">Feasibility Applications — School Feasibility Study Portal</x-slot>

    <div class="space-y-6">
        <!-- Header -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold text-slate-900 tracking-tight">Feasibility Study Applications</h1>
                <p class="text-sm text-slate-500 mt-1">Review, monitor, and advance school feasibility proposals through
                    evaluation stages.</p>
            </div>
            <div class="flex items-center gap-3">
                @if (auth()->user()->isSuperAdmin())
                    @php $trashedCount = \App\Models\FeasibilityApplication::onlyTrashed()->count(); @endphp
                    <a href="{{ route('recycle_bin.index') }}"
                        class="inline-flex items-center gap-2 px-3 py-2 text-xs font-semibold text-rose-700 bg-white border border-rose-200 rounded-lg hover:bg-rose-50 transition shadow-xs">
                        <svg class="w-4 h-4 text-rose-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                        </svg>
                        Recycle Bin
                        @if ($trashedCount > 0)
                            <span class="px-1.5 py-0.5 bg-rose-100 text-rose-800 text-[10px] font-bold rounded-full">
                                {{ $trashedCount }}
                            </span>
                        @endif
                    </a>
                @endif

                <a href="{{ route('reports.export.csv') }}"
                    class="inline-flex items-center gap-2 px-3 py-2 text-xs font-semibold text-slate-700 bg-white border border-slate-300 rounded-lg hover:bg-slate-50 transition shadow-xs">
                    <svg class="w-4 h-4 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    Export CSV
                </a>

                <a href="{{ route('applications.create') }}"
                    class="inline-flex items-center gap-2 px-4 py-2 text-xs font-semibold text-white bg-indigo-600 hover:bg-indigo-500 rounded-lg shadow-sm transition">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    New Application
                </a>
            </div>
        </div>

        <!-- Filter Bar -->
        <div class="bg-white rounded-xl border border-slate-200/80 p-4 shadow-xs">
            <form method="GET" action="{{ route('applications.index') }}" class="grid grid-cols-1 sm:grid-cols-5 gap-3">
                <div class="sm:col-span-2">
                    <input type="text" name="search" value="{{ request('search') }}"
                        placeholder="Search by Application #, school name, or district..."
                        class="w-full text-xs rounded-lg border-slate-300 focus:border-indigo-500 focus:ring-indigo-500">
                </div>

                <div>
                    <select name="status"
                        class="w-full text-xs rounded-lg border-slate-300 focus:border-indigo-500 focus:ring-indigo-500">
                        <option value="">All Statuses</option>
                        @foreach (\App\Enums\ApplicationStatus::cases() as $st)
                            <option value="{{ $st->value }}" {{ request('status') === $st->value ? 'selected' : '' }}>
                                {{ $st->label() }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <select name="assigned_to"
                        class="w-full text-xs rounded-lg border-slate-300 focus:border-indigo-500 focus:ring-indigo-500">
                        <option value="">All Assigned Officers</option>
                        @foreach ($officers as $officer)
                            <option value="{{ $officer->id }}" {{ request('assigned_to') == $officer->id ? 'selected' : '' }}>
                                {{ $officer->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="flex items-center gap-2">
                    <button type="submit"
                        class="w-full py-2 px-3 text-xs font-semibold text-white bg-slate-800 hover:bg-slate-700 rounded-lg transition">
                        Filter
                    </button>
                    @if (request()->hasAny(['search', 'status', 'assigned_to', 'state']))
                        <a href="{{ route('applications.index') }}"
                            class="py-2 px-3 text-xs font-semibold text-slate-600 bg-slate-100 hover:bg-slate-200 rounded-lg transition text-center">
                            Reset
                        </a>
                    @endif
                </div>
            </form>
        </div>

        <!-- Application Cards / Table -->
        <x-card noPadding="true">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm text-slate-600">
                    <thead class="bg-slate-50 text-xs uppercase font-semibold text-slate-500 border-b border-slate-100">
                        <tr>
                            <th class="px-5 py-3.5">Application #</th>
                            <th class="px-5 py-3.5">School Name</th>
                            <th class="px-5 py-3.5">Location</th>
                            <th class="px-5 py-3.5">Status</th>
                            <th class="px-5 py-3.5">Score & Rating</th>
                            <th class="px-5 py-3.5">Lead Inspector</th>
                            <th class="px-5 py-3.5 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse ($applications as $app)
                            <tr class="hover:bg-slate-50/60 transition">
                                <td class="px-5 py-4">
                                    <a href="{{ route('applications.show', $app) }}"
                                        class="font-mono text-xs font-bold text-indigo-600 hover:text-indigo-800 block">
                                        {{ $app->application_number }}
                                    </a>
                                    <span class="text-[11px] text-slate-400">{{ $app->created_at->format('d M Y') }}</span>
                                </td>
                                <td class="px-5 py-4">
                                    <div class="font-semibold text-slate-900 leading-snug">{{ $app->school?->name ?? 'Unknown School' }}</div>
                                    <div class="text-xs text-slate-500">{{ $app->school?->management_organization ?? '—' }}</div>
                                </td>
                                <td class="px-5 py-4">
                                    <div class="text-slate-900">{{ $app->school?->city ?? '—' }}{{ $app->school?->district ? ', '.$app->school->district : '' }}</div>
                                    <div class="text-xs text-slate-400">{{ $app->school?->state ?? '' }}</div>
                                </td>
                                <td class="px-5 py-4">
                                    <x-badge :variant="$app->status->color()">
                                        {{ $app->status->label() }}
                                    </x-badge>
                                </td>
                                <td class="px-5 py-4">
                                    @if ($app->overall_score)
                                        <div class="font-bold text-slate-900">{{ $app->overall_score }}%</div>
                                        <div class="text-[11px] text-slate-500 capitalize">
                                            {{ str_replace('_', ' ', (string) $app->feasibility_rating) }}</div>
                                    @else
                                        <span class="text-xs text-slate-400 font-medium">Incomplete</span>
                                    @endif
                                </td>
                                <td class="px-5 py-4">
                                    <span class="text-xs font-medium text-slate-700">
                                        {{ $app->assignedOfficer?->name ?? 'Unassigned' }}
                                    </span>
                                </td>
                                <td class="px-5 py-4 text-right">
                                    <div class="flex items-center justify-end gap-1.5 flex-wrap">
                                        @if ($app->canBeEdited() || auth()->user()->isSuperAdmin())
                                            <a href="{{ route('applications.checklist.edit', $app) }}"
                                                class="text-xs font-semibold text-indigo-700 bg-indigo-50 hover:bg-indigo-100 px-2.5 py-1.5 rounded-md transition">
                                                Checklist
                                            </a>
                                        @endif
                                        <a href="{{ route('applications.show', $app) }}"
                                            class="text-xs font-semibold text-slate-700 bg-slate-100 hover:bg-slate-200 px-2.5 py-1.5 rounded-md transition">
                                            View
                                        </a>
                                        @if ($app->overall_score)
                                            <a href="{{ route('reports.application.pdf', $app) }}" title="Download PDF Report"
                                                class="text-xs font-semibold text-rose-700 bg-rose-50 hover:bg-rose-100 px-2 py-1.5 rounded-md transition">
                                                PDF
                                            </a>
                                        @endif

                                        @if (auth()->user()->isSuperAdmin())
                                            @if ($app->isArchived())
                                                <form method="POST" action="{{ route('applications.unarchive', $app) }}" class="inline" id="unarchive-app-{{ $app->id }}">
                                                    @csrf
                                                    <button type="button"
                                                        title="Restore from Archive"
                                                        onclick="window.customConfirm('Do you want to unarchive application #{{ $app->application_number }} and restore it to active status?', {
                                                            title: 'Unarchive Application',
                                                            confirmText: 'Yes, Unarchive',
                                                            cancelText: 'Cancel',
                                                            type: 'primary'
                                                        }).then(ok => { if (ok) document.getElementById('unarchive-app-{{ $app->id }}').submit(); })"
                                                        class="text-xs font-semibold text-amber-700 bg-amber-50 hover:bg-amber-100 px-2 py-1.5 rounded-md transition cursor-pointer">
                                                        Unarchive
                                                    </button>
                                                </form>
                                            @else
                                                <form method="POST" action="{{ route('applications.archive', $app) }}" class="inline" id="archive-app-{{ $app->id }}">
                                                    @csrf
                                                    <button type="button"
                                                        title="Archive this application"
                                                        onclick="window.customConfirm('Are you sure you want to archive application #{{ $app->application_number }}?', {
                                                            title: 'Archive Application',
                                                            subtext: 'Archived applications remain preserved and can be viewed or unarchived at any time.',
                                                            confirmText: 'Yes, Archive',
                                                            cancelText: 'Cancel',
                                                            type: 'warning'
                                                        }).then(ok => { if (ok) document.getElementById('archive-app-{{ $app->id }}').submit(); })"
                                                        class="text-xs font-semibold text-slate-600 bg-slate-100 hover:bg-slate-200 px-2 py-1.5 rounded-md transition cursor-pointer">
                                                        Archive
                                                    </button>
                                                </form>
                                            @endif

                                            <form method="POST" action="{{ route('applications.destroy', $app) }}" class="inline" id="delete-app-{{ $app->id }}">
                                                @csrf
                                                @method('DELETE')
                                                <button type="button"
                                                    title="Move to Recycle Bin (Soft Delete)"
                                                    onclick="window.customConfirm('Are you sure you want to move application #{{ $app->application_number }} to the Recycle Bin?', {
                                                        title: 'Move to Recycle Bin',
                                                        subtext: 'This application will be safely soft-deleted and can be restored from the Recycle Bin at any time.',
                                                        confirmText: 'Yes, Move to Trash',
                                                        cancelText: 'Cancel',
                                                        type: 'danger'
                                                    }).then(ok => { if (ok) document.getElementById('delete-app-{{ $app->id }}').submit(); })"
                                                    class="text-xs font-semibold text-rose-700 bg-rose-50 hover:bg-rose-100 px-2 py-1.5 rounded-md transition cursor-pointer">
                                                    Delete
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-5 py-12 text-center text-slate-400">
                                    No feasibility applications found matching your criteria.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if ($applications->hasPages())
                <div class="px-5 py-4 border-t border-slate-100 bg-slate-50/50">
                    {{ $applications->links() }}
                </div>
            @endif
        </x-card>
    </div>
</x-app-layout>