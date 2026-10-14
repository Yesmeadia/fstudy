<x-app-layout>
    <x-slot name="title">Schools Directory — School Feasibility Study Portal</x-slot>

    <div class="space-y-6">
        <!-- Page Header -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold text-slate-900 tracking-tight">Schools Directory</h1>
                <p class="text-sm text-slate-500 mt-1">Manage institutional profiles, society affiliations, and school codes.</p>
            </div>
            <a href="{{ route('schools.create') }}" class="inline-flex items-center gap-2 px-4 py-2 text-xs font-semibold text-white bg-indigo-600 hover:bg-indigo-500 rounded-lg shadow-sm transition">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Register New School
            </a>
        </div>

        <!-- Filters Form -->
        <div class="bg-white rounded-xl border border-slate-200/80 p-4 shadow-xs">
            <form method="GET" action="{{ route('schools.index') }}" class="grid grid-cols-1 sm:grid-cols-4 gap-3">
                <div class="sm:col-span-2">
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by school name, code, district, or state..." class="w-full text-xs rounded-lg border-slate-300 focus:border-indigo-500 focus:ring-indigo-500">
                </div>
                <div>
                    <select name="board" class="w-full text-xs rounded-lg border-slate-300 focus:border-indigo-500 focus:ring-indigo-500">
                        <option value="">All Affiliation Boards</option>
                        <option value="cbse" {{ request('board') === 'cbse' ? 'selected' : '' }}>CBSE</option>
                        <option value="icse" {{ request('board') === 'icse' ? 'selected' : '' }}>ICSE / CISCE</option>
                        <option value="state_board" {{ request('board') === 'state_board' ? 'selected' : '' }}>State Board</option>
                        <option value="ib" {{ request('board') === 'ib' ? 'selected' : '' }}>IB (International)</option>
                        <option value="cambridge" {{ request('board') === 'cambridge' ? 'selected' : '' }}>Cambridge (CAIE)</option>
                    </select>
                </div>
                <div class="flex items-center gap-2">
                    <button type="submit" class="w-full py-2 px-3 text-xs font-semibold text-white bg-slate-800 hover:bg-slate-700 rounded-lg transition">
                        Filter
                    </button>
                    @if (request()->hasAny(['search', 'board', 'school_type']))
                        <a href="{{ route('schools.index') }}" class="py-2 px-3 text-xs font-semibold text-slate-600 bg-slate-100 hover:bg-slate-200 rounded-lg transition text-center">
                            Reset
                        </a>
                    @endif
                </div>
            </form>
        </div>

        <!-- Schools List -->
        <x-card noPadding="true">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm text-slate-600">
                    <thead class="bg-slate-50 text-xs uppercase font-semibold text-slate-500 border-b border-slate-100">
                        <tr>
                            <th class="px-5 py-3.5">School Details</th>
                            <th class="px-5 py-3.5">Management / Trust</th>
                            <th class="px-5 py-3.5">Location</th>
                            <th class="px-5 py-3.5">Affiliation & Type</th>
                            <th class="px-5 py-3.5">Feasibility Apps</th>
                            <th class="px-5 py-3.5 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse ($schools as $school)
                            <tr class="hover:bg-slate-50/60 transition">
                                <td class="px-5 py-4">
                                    <a href="{{ route('schools.show', $school) }}" class="font-semibold text-slate-900 hover:text-indigo-600 block">
                                        {{ $school->name }}
                                    </a>
                                    <span class="text-xs font-mono text-slate-400">{{ $school->school_code }}</span>
                                </td>
                                <td class="px-5 py-4">
                                    <div class="text-slate-900 font-medium">{{ $school->management_organization }}</div>
                                    <div class="text-xs text-slate-500">{{ $school->trust_society_type }}</div>
                                </td>
                                <td class="px-5 py-4">
                                    <div class="text-slate-900">{{ $school->city }}, {{ $school->district }}</div>
                                    <div class="text-xs text-slate-500">{{ $school->state }} - {{ $school->pincode }}</div>
                                </td>
                                <td class="px-5 py-4">
                                    <div class="flex items-center gap-1.5 flex-wrap">
                                        <x-badge variant="blue" size="sm">{{ $school->board?->label() ?? 'CBSE' }}</x-badge>
                                        <x-badge variant="slate" size="sm">{{ $school->school_type?->label() ?? 'K-12' }}</x-badge>
                                    </div>
                                    <div class="text-xs text-slate-500 mt-1">Medium: {{ $school->medium }}</div>
                                </td>
                                <td class="px-5 py-4">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-indigo-50 text-indigo-700">
                                        {{ $school->applications_count }} Study Apps
                                    </span>
                                </td>
                                <td class="px-5 py-4 text-right">
                                    <div class="flex items-center justify-end gap-2">
                                        <a href="{{ route('schools.show', $school) }}" class="text-xs font-semibold text-slate-700 bg-slate-100 hover:bg-slate-200 px-2.5 py-1.5 rounded-md transition">
                                            View
                                        </a>
                                        <a href="{{ route('schools.edit', $school) }}" class="text-xs font-semibold text-indigo-700 bg-indigo-50 hover:bg-indigo-100 px-2.5 py-1.5 rounded-md transition">
                                            Edit
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-5 py-12 text-center text-slate-400">
                                    No schools registered matching your criteria.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if ($schools->hasPages())
                <div class="px-5 py-4 border-t border-slate-100 bg-slate-50/50">
                    {{ $schools->links() }}
                </div>
            @endif
        </x-card>
    </div>
</x-app-layout>
