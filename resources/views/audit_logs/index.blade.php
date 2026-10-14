<x-app-layout>
    <x-slot name="title">System Activity & Audit Trails — YES INDIA FOUNDATION</x-slot>

    <div class="space-y-6" x-data="{ selectedLog: null, showModal: false }">
        <!-- Header -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <div class="flex items-center gap-2.5">
                    <h1 class="text-2xl font-bold text-slate-900 tracking-tight">Activity & Audit Logs</h1>
                </div>
                <p class="text-sm text-slate-500 mt-1">
                    Immutable security log of authentication events, user operations, status workflows, and system
                    mutations for YES INDIA FOUNDATION.
                </p>
            </div>
            <div class="flex items-center gap-2">
                <a href="{{ route('audit_logs.index') }}"
                    class="px-3.5 py-2 text-xs font-semibold text-slate-700 bg-white border border-slate-300 rounded-lg hover:bg-slate-50 transition shadow-xs">
                    Refresh Logs
                </a>
            </div>
        </div>

        <!-- Super Admin KPI Metrics -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            <x-stat-card label="Activity Events Today" :value="$stats['total_today']" subtext="All recorded mutations"
                color="blue">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </x-stat-card>

            <x-stat-card label="User Logins Today" :value="$stats['logins_today']"
                subtext="Security sessions initialized" color="emerald">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1" />
                </svg>
            </x-stat-card>

            <x-stat-card label="Workflow Status Changes" :value="$stats['status_changes']"
                subtext="Feasibility application transitions" color="purple">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </x-stat-card>

            <x-stat-card label="Total Historical Logs" :value="number_format($stats['total_all_time'])"
                subtext="Permanent audit record" color="slate">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                </svg>
            </x-stat-card>
        </div>

        <!-- Filter Controls -->
        <div class="bg-white rounded-xl border border-slate-200/80 p-4 shadow-xs">
            <form method="GET" action="{{ route('audit_logs.index') }}" class="grid grid-cols-1 sm:grid-cols-5 gap-3">
                <div class="sm:col-span-2">
                    <input type="text" name="search" value="{{ request('search') }}"
                        placeholder="Search action, IP, model, or user name..."
                        class="w-full text-xs rounded-lg border-slate-300 focus:border-indigo-500 focus:ring-indigo-500">
                </div>

                <div>
                    <select name="action"
                        class="w-full text-xs rounded-lg border-slate-300 focus:border-indigo-500 focus:ring-indigo-500">
                        <option value="">All Action Types</option>
                        <option value="user_login" {{ request('action') === 'user_login' ? 'selected' : '' }}>User Login
                        </option>
                        <option value="user_logout" {{ request('action') === 'user_logout' ? 'selected' : '' }}>User
                            Logout</option>
                        <option value="status_changed" {{ request('action') === 'status_changed' ? 'selected' : '' }}>
                            Status Changed</option>
                        <option value="officer_assigned" {{ request('action') === 'officer_assigned' ? 'selected' : '' }}>
                            Officer Assigned</option>
                        <option value="document_verified" {{ request('action') === 'document_verified' ? 'selected' : '' }}>Document Verified</option>
                        <option value="management_decision" {{ request('action') === 'management_decision' ? 'selected' : '' }}>Management Decision</option>
                        <option value="report_downloaded" {{ request('action') === 'report_downloaded' ? 'selected' : '' }}>Report Downloaded</option>
                        <option value="csv_exported" {{ request('action') === 'csv_exported' ? 'selected' : '' }}>CSV
                            Exported</option>
                        <option value="settings_updated" {{ request('action') === 'settings_updated' ? 'selected' : '' }}>
                            Settings Updated</option>
                    </select>
                </div>

                <div>
                    <select name="user_id"
                        class="w-full text-xs rounded-lg border-slate-300 focus:border-indigo-500 focus:ring-indigo-500">
                        <option value="">All Performing Users</option>
                        @foreach ($users as $u)
                            <option value="{{ $u->id }}" {{ request('user_id') == $u->id ? 'selected' : '' }}>
                                {{ $u->name }} ({{ $u->email }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="flex items-center gap-2">
                    <button type="submit"
                        class="w-full py-2 px-3 text-xs font-semibold text-white bg-slate-800 hover:bg-slate-700 rounded-lg transition">
                        Filter Logs
                    </button>
                    @if (request()->hasAny(['search', 'action', 'user_id', 'date_from', 'date_to']))
                        <a href="{{ route('audit_logs.index') }}"
                            class="py-2 px-3 text-xs font-semibold text-slate-600 bg-slate-100 hover:bg-slate-200 rounded-lg transition text-center">
                            Reset
                        </a>
                    @endif
                </div>
            </form>
        </div>

        <!-- Logs Table -->
        <x-card noPadding="true">
            <div class="overflow-x-auto text-xs">
                <table class="w-full text-left">
                    <thead class="bg-slate-50 font-semibold text-slate-500 border-b border-slate-100">
                        <tr>
                            <th class="px-4 py-3.5">Timestamp</th>
                            <th class="px-4 py-3.5">User</th>
                            <th class="px-4 py-3.5">Action Event</th>
                            <th class="px-4 py-3.5">Target Entity</th>
                            <th class="px-4 py-3.5">IP Address & Agent</th>
                            <th class="px-4 py-3.5 text-right">Details</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse ($logs as $log)
                            <tr class="hover:bg-slate-50/60 transition">
                                <td class="px-4 py-3 whitespace-nowrap">
                                    <span
                                        class="font-mono text-slate-700 block">{{ $log->created_at->format('d M Y, H:i:s') }}</span>
                                    <span class="text-[10px] text-slate-400">{{ $log->created_at->diffForHumans() }}</span>
                                </td>
                                <td class="px-4 py-3">
                                    <div class="flex items-center gap-2.5">
                                        <div
                                            class="w-7 h-7 rounded-full bg-slate-100 text-slate-700 flex items-center justify-center font-bold text-[11px] shrink-0 border border-slate-200">
                                            {{ strtoupper(substr($log->user?->name ?? 'S', 0, 1)) }}
                                        </div>
                                        <div>
                                            <div class="font-semibold text-slate-900">
                                                {{ $log->user?->name ?? 'System Automated' }}
                                            </div>
                                            <div class="text-[11px] text-slate-400">{{ $log->user?->email ??
                                                'system@internal' }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-4 py-3">
                                    @php
                                        $badgeColor = match ($log->action) {
                                            'user_login' => 'emerald',
                                            'user_logout' => 'slate',
                                            'status_changed' => 'indigo',
                                            'management_decision' => 'purple',
                                            'document_verified' => 'blue',
                                            default => 'amber',
                                        };
                                    @endphp
                                    <x-badge :variant="$badgeColor" size="sm">
                                        {{ str_replace('_', ' ', $log->action) }}
                                    </x-badge>
                                </td>
                                <td class="px-4 py-3">
                                    <span
                                        class="font-mono text-[11px] text-slate-700 bg-slate-100 px-2 py-0.5 rounded border border-slate-200">
                                        {{ class_basename($log->model_type ?? 'N/A') }} #{{ $log->model_id ?? '-' }}
                                    </span>
                                </td>
                                <td class="px-4 py-3 text-slate-500 font-mono text-[11px]">
                                    <div>{{ $log->ip_address ?? '127.0.0.1' }}</div>
                                    <div class="text-[10px] text-slate-400 truncate max-w-xs"
                                        title="{{ $log->user_agent }}">
                                        {{ $log->user_agent ?? 'Internal' }}
                                    </div>
                                </td>
                                <td class="px-4 py-3 text-right">
                                    <button type="button" @click="selectedLog = @js($log); showModal = true"
                                        class="px-2.5 py-1 text-xs font-semibold text-indigo-600 bg-indigo-50 hover:bg-indigo-100 rounded-md transition">
                                        Inspect
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-4 py-12 text-center text-slate-400">
                                    No audit log events match your current filter parameters.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if ($logs->hasPages())
                <div class="px-4 py-3.5 border-t border-slate-100 bg-slate-50/50">
                    {{ $logs->links() }}
                </div>
            @endif
        </x-card>

        <!-- JSON Diff Details Modal -->
        <div x-show="showModal" x-cloak
            class="fixed inset-0 z-50 overflow-y-auto bg-slate-900/60 backdrop-blur-xs flex items-center justify-center p-4">
            <div @click.away="showModal = false"
                class="bg-white rounded-2xl max-w-2xl w-full p-6 shadow-2xl border border-slate-200 text-xs space-y-4">
                <div class="flex items-center justify-between border-b pb-3">
                    <div>
                        <h3 class="text-base font-bold text-slate-900">Audit Record Inspector</h3>
                        <p class="text-[11px] text-slate-500"
                            x-text="'Log ID #' + (selectedLog?.id || '') + ' • Event: ' + (selectedLog?.action || '')">
                        </p>
                    </div>
                    <button @click="showModal = false"
                        class="text-slate-400 hover:text-slate-600 p-1.5 rounded-lg hover:bg-slate-100 transition">
                        <x-icon name="x" class="w-4 h-4" />
                    </button>
                </div>

                <div class="grid grid-cols-2 gap-4 bg-slate-50 p-3 rounded-lg text-slate-700">
                    <div>
                        <span class="text-slate-400 font-medium">Actor:</span>
                        <span class="font-bold block" x-text="selectedLog?.user?.name || 'System'"></span>
                    </div>
                    <div>
                        <span class="text-slate-400 font-medium">Timestamp:</span>
                        <span class="font-mono block" x-text="selectedLog?.created_at || ''"></span>
                    </div>
                    <div>
                        <span class="text-slate-400 font-medium">IP Address:</span>
                        <span class="font-mono block" x-text="selectedLog?.ip_address || '127.0.0.1'"></span>
                    </div>
                    <div>
                        <span class="text-slate-400 font-medium">Entity:</span>
                        <span class="font-mono block"
                            x-text="(selectedLog?.model_type || '') + ' #' + (selectedLog?.model_id || '')"></span>
                    </div>
                </div>

                <!-- Old Values -->
                <template x-if="selectedLog?.old_values">
                    <div>
                        <span class="font-bold text-slate-700 block mb-1 text-[11px] uppercase tracking-wider">Previous
                            State (Old Values):</span>
                        <pre class="bg-slate-900 text-slate-200 p-3 rounded-lg overflow-x-auto font-mono text-[11px]"
                            x-text="JSON.stringify(selectedLog.old_values, null, 2)"></pre>
                    </div>
                </template>

                <!-- New Values -->
                <template x-if="selectedLog?.new_values">
                    <div>
                        <span class="font-bold text-slate-700 block mb-1 text-[11px] uppercase tracking-wider">Mutated
                            State (New Values):</span>
                        <pre class="bg-slate-900 text-emerald-300 p-3 rounded-lg overflow-x-auto font-mono text-[11px]"
                            x-text="JSON.stringify(selectedLog.new_values, null, 2)"></pre>
                    </div>
                </template>

                <div class="flex justify-end pt-2 border-t">
                    <button type="button" @click="showModal = false"
                        class="px-4 py-2 bg-slate-800 text-white font-semibold rounded-lg hover:bg-slate-700">
                        Close
                    </button>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>