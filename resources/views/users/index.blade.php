<x-app-layout>
    <x-slot name="title">User Management — School Feasibility Portal</x-slot>

    <div class="space-y-6">
        {{-- Page Header --}}
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <div class="flex items-center gap-2 mb-1">
                    <span class="px-2.5 py-0.5 rounded-full text-[10px] font-black uppercase tracking-wider bg-slate-100 text-slate-700 border border-slate-200">
                        Access Control
                    </span>
                    <span class="text-xs text-slate-400 font-semibold uppercase tracking-wider">YES India Foundation</span>
                </div>
                <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight">User Management</h1>
                <p class="text-xs sm:text-sm text-slate-500 mt-1">
                    Manage administrative privileges and field inspection assignments across portal staff.
                </p>
            </div>
            <a href="{{ route('users.create') }}"
               class="inline-flex items-center justify-center gap-2 px-4 py-2.5 text-xs font-bold text-white bg-indigo-600 hover:bg-indigo-500 rounded-xl shadow-sm transition active:scale-95">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4" />
                </svg>
                <span>Create New User</span>
            </a>
        </div>

        {{-- Role Overview & Quick Filter Cards --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            {{-- All Users Card --}}
            <a href="{{ route('users.index') }}"
               class="bg-white rounded-2xl border p-5 transition shadow-xs flex items-center justify-between gap-3 group {{ !request('role') ? 'border-indigo-600 ring-2 ring-indigo-600/10 bg-indigo-50/20' : 'border-slate-200/80 hover:border-slate-300' }}">
                <div class="space-y-1.5 min-w-0">
                    <p class="text-[11px] font-bold uppercase tracking-wider text-slate-500">All Portal Users</p>
                    <p class="text-2xl font-black text-slate-900 tracking-tight">{{ $totalUsersCount ?? $users->total() }}</p>
                    <span class="text-[11px] font-semibold flex items-center gap-1 {{ !request('role') ? 'text-indigo-600' : 'text-slate-400 group-hover:text-indigo-600' }}">
                        {{ !request('role') ? '● Active Filter' : 'Filter All Users →' }}
                    </span>
                </div>
                <div class="w-12 h-12 rounded-2xl bg-slate-100 group-hover:bg-indigo-50 text-slate-600 group-hover:text-indigo-600 flex items-center justify-center transition shrink-0">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                    </svg>
                </div>
            </a>

            {{-- Super Admin Card --}}
            <a href="{{ route('users.index', ['role' => 'Super Admin']) }}"
               class="bg-white rounded-2xl border p-5 transition shadow-xs flex items-center justify-between gap-3 group {{ request('role') === 'Super Admin' ? 'border-purple-600 ring-2 ring-purple-600/10 bg-purple-50/30' : 'border-slate-200/80 hover:border-purple-300' }}">
                <div class="space-y-1.5 min-w-0">
                    <div class="flex items-center gap-1.5">
                        <span class="w-2 h-2 rounded-full bg-purple-600 shrink-0"></span>
                        <p class="text-[11px] font-bold uppercase tracking-wider text-purple-700 truncate">Super Admins</p>
                    </div>
                    <p class="text-2xl font-black text-slate-900 tracking-tight">{{ $superAdminCount ?? 0 }}</p>
                    <span class="text-[11px] font-semibold flex items-center gap-1 {{ request('role') === 'Super Admin' ? 'text-purple-700' : 'text-purple-600 group-hover:underline' }}">
                        {{ request('role') === 'Super Admin' ? '● Active Filter' : 'Full system authority →' }}
                    </span>
                </div>
                <div class="w-12 h-12 rounded-2xl bg-purple-50 group-hover:bg-purple-100 text-purple-700 flex items-center justify-center transition shrink-0">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                    </svg>
                </div>
            </a>

            {{-- Inspector Card --}}
            <a href="{{ route('users.index', ['role' => 'Inspector']) }}"
               class="bg-white rounded-2xl border p-5 transition shadow-xs flex items-center justify-between gap-3 group {{ request('role') === 'Inspector' ? 'border-indigo-600 ring-2 ring-indigo-600/10 bg-indigo-50/30' : 'border-slate-200/80 hover:border-indigo-300' }}">
                <div class="space-y-1.5 min-w-0">
                    <div class="flex items-center gap-1.5">
                        <span class="w-2 h-2 rounded-full bg-indigo-600 shrink-0"></span>
                        <p class="text-[11px] font-bold uppercase tracking-wider text-indigo-700 truncate">Inspectors</p>
                    </div>
                    <p class="text-2xl font-black text-slate-900 tracking-tight">{{ $inspectorCount ?? 0 }}</p>
                    <span class="text-[11px] font-semibold flex items-center gap-1 {{ request('role') === 'Inspector' ? 'text-indigo-700' : 'text-indigo-600 group-hover:underline' }}">
                        {{ request('role') === 'Inspector' ? '● Active Filter' : 'Audit & inspection evaluators →' }}
                    </span>
                </div>
                <div class="w-12 h-12 rounded-2xl bg-indigo-50 group-hover:bg-indigo-100 text-indigo-700 flex items-center justify-center transition shrink-0">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                    </svg>
                </div>
            </a>

            {{-- Unassigned Card --}}
            <a href="{{ route('users.index', ['role' => 'unassigned']) }}"
               class="bg-white rounded-2xl border p-5 transition shadow-xs flex items-center justify-between gap-3 group {{ request('role') === 'unassigned' ? 'border-amber-600 ring-2 ring-amber-600/10 bg-amber-50/30' : 'border-slate-200/80 hover:border-amber-300' }}">
                <div class="space-y-1.5 min-w-0">
                    <div class="flex items-center gap-1.5">
                        <span class="w-2 h-2 rounded-full {{ ($unassignedCount ?? 0) > 0 ? 'bg-amber-500 animate-pulse' : 'bg-slate-300' }} shrink-0"></span>
                        <p class="text-[11px] font-bold uppercase tracking-wider text-amber-700 truncate">Unassigned</p>
                    </div>
                    <p class="text-2xl font-black text-slate-900 tracking-tight">{{ $unassignedCount ?? 0 }}</p>
                    <span class="text-[11px] font-semibold flex items-center gap-1 {{ request('role') === 'unassigned' ? 'text-amber-700' : 'text-amber-600 group-hover:underline' }}">
                        {{ request('role') === 'unassigned' ? '● Active Filter' : 'Needs role assignment →' }}
                    </span>
                </div>
                <div class="w-12 h-12 rounded-2xl bg-amber-50 group-hover:bg-amber-100 text-amber-700 flex items-center justify-center transition shrink-0">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                </div>
            </a>
        </div>

        {{-- Filters Bar --}}
        <div class="bg-white rounded-2xl border border-slate-200/80 p-4 shadow-xs">
            <form method="GET" action="{{ route('users.index') }}" class="grid grid-cols-1 sm:grid-cols-12 gap-3 items-center">
                <div class="sm:col-span-6 lg:col-span-5">
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                        </div>
                        <input type="text" name="search" value="{{ request('search') }}"
                               placeholder="Search user name, email, or designation..."
                               class="w-full text-xs rounded-xl border-slate-300 pl-9 focus:border-indigo-500 focus:ring-indigo-500 py-2">
                    </div>
                </div>

                <div class="sm:col-span-4 lg:col-span-4">
                    <select name="role" class="w-full text-xs rounded-xl border-slate-300 focus:border-indigo-500 focus:ring-indigo-500 py-2">
                        <option value="">All Roles (Super Admin &amp; Inspector)</option>
                        @foreach ($roles as $roleName)
                            <option value="{{ $roleName }}" {{ request('role') === $roleName ? 'selected' : '' }}>
                                {{ $roleName }}
                            </option>
                        @endforeach
                        <option value="unassigned" {{ request('role') === 'unassigned' ? 'selected' : '' }}>
                            Unassigned (No Role)
                        </option>
                    </select>
                </div>

                <div class="sm:col-span-2 lg:col-span-3 flex items-center gap-2">
                    <button type="submit"
                            class="flex-1 py-2 px-4 text-xs font-bold text-white bg-slate-900 hover:bg-slate-800 rounded-xl transition shadow-xs text-center cursor-pointer">
                        Filter
                    </button>
                    @if (request()->hasAny(['search', 'role']))
                        <a href="{{ route('users.index') }}"
                           class="py-2 px-3 text-xs font-semibold text-slate-600 bg-slate-100 hover:bg-slate-200 rounded-xl transition text-center"
                           title="Clear Filters">
                            Reset
                        </a>
                    @endif
                </div>
            </form>
        </div>

        {{-- Users Table --}}
        <div class="bg-white rounded-2xl border border-slate-200/90 shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm text-slate-600">
                    <thead class="bg-slate-50 text-[11px] uppercase font-bold text-slate-500 border-b border-slate-100 tracking-wider">
                        <tr>
                            <th class="px-5 py-3.5">User</th>
                            <th class="px-5 py-3.5">Assigned Role</th>
                            <th class="px-5 py-3.5">Designation</th>
                            <th class="px-5 py-3.5">Last Login</th>
                            <th class="px-5 py-3.5">Joined</th>
                            <th class="px-5 py-3.5 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse ($users as $user)
                            <tr class="hover:bg-slate-50/70 transition">
                                {{-- User Column --}}
                                <td class="px-5 py-4">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 rounded-2xl shrink-0 overflow-hidden border border-slate-200 flex items-center justify-center text-xs font-bold bg-slate-100 shadow-2xs">
                                            @if ($user->avatar)
                                                <img src="{{ asset('storage/' . $user->avatar) }}" alt="{{ $user->name }}" class="w-full h-full object-cover">
                                            @else
                                                @if ($user->isSuperAdmin() || $user->roles->contains('name', 'Super Admin'))
                                                    <div class="w-full h-full bg-gradient-to-tr from-purple-700 to-indigo-600 text-white flex items-center justify-center font-black">
                                                        {{ strtoupper(substr($user->name, 0, 1)) }}
                                                    </div>
                                                @elseif ($user->isInspector() || $user->roles->contains('name', 'Inspector'))
                                                    <div class="w-full h-full bg-gradient-to-tr from-indigo-600 to-sky-500 text-white flex items-center justify-center font-black">
                                                        {{ strtoupper(substr($user->name, 0, 1)) }}
                                                    </div>
                                                @else
                                                    <div class="w-full h-full bg-gradient-to-tr from-slate-600 to-slate-400 text-white flex items-center justify-center font-black">
                                                        {{ strtoupper(substr($user->name, 0, 1)) }}
                                                    </div>
                                                @endif
                                            @endif
                                        </div>
                                        <div class="min-w-0">
                                            <div class="flex items-center gap-2 flex-wrap">
                                                <a href="{{ route('users.show', $user) }}"
                                                   class="font-bold text-slate-900 hover:text-indigo-600 block leading-tight text-sm">
                                                    {{ $user->name }}
                                                </a>
                                                @if ($user->id === auth()->id())
                                                    <span class="px-1.5 py-0.5 rounded text-[9px] font-black uppercase tracking-wider bg-slate-100 text-slate-600 border border-slate-200">
                                                        You
                                                    </span>
                                                @endif
                                                @if ($user->isSuperAdmin() || $user->roles->contains('name', 'Super Admin'))
                                                    <span class="px-1.5 py-0.5 rounded text-[9px] font-black uppercase tracking-wider bg-purple-100 text-purple-800" title="Super Administrator">
                                                        Admin
                                                    </span>
                                                @elseif ($user->isInspector() || $user->roles->contains('name', 'Inspector'))
                                                    <span class="px-1.5 py-0.5 rounded text-[9px] font-black uppercase tracking-wider bg-indigo-100 text-indigo-800" title="Inspector">
                                                        Inspector
                                                    </span>
                                                @endif
                                            </div>
                                            <span class="text-xs text-slate-400 font-medium block truncate">{{ $user->email }}</span>
                                        </div>
                                    </div>
                                </td>

                                {{-- Role Column --}}
                                <td class="px-5 py-4">
                                    @php
                                        $userRoles = $user->roles;
                                    @endphp
                                    @if ($user->isSuperAdmin() || $userRoles->contains('name', 'Super Admin'))
                                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-black uppercase tracking-wider bg-purple-50 text-purple-700 border border-purple-200 shadow-2xs">
                                            <svg class="w-3.5 h-3.5 text-purple-600 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                                            </svg>
                                            <span>Super Admin</span>
                                        </span>
                                    @elseif ($user->isInspector() || $userRoles->contains('name', 'Inspector'))
                                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-black uppercase tracking-wider bg-indigo-50 text-indigo-700 border border-indigo-200 shadow-2xs">
                                            <svg class="w-3.5 h-3.5 text-indigo-600 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                                            </svg>
                                            <span>Inspector</span>
                                        </span>
                                    @elseif ($userRoles->isNotEmpty())
                                        @foreach ($userRoles as $role)
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-wider bg-slate-100 text-slate-700 border border-slate-200">
                                                {{ $role->name }}
                                            </span>
                                        @endforeach
                                    @else
                                        <div class="inline-flex items-center gap-1.5">
                                            <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[11px] font-bold uppercase tracking-wider bg-amber-50 text-amber-700 border border-amber-200">
                                                <svg class="w-3.5 h-3.5 text-amber-500 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                                </svg>
                                                No Role
                                            </span>
                                            <a href="{{ route('users.edit', $user) }}" class="text-[11px] text-indigo-600 font-bold hover:underline" title="Assign Role to user">
                                                + Assign
                                            </a>
                                        </div>
                                    @endif
                                </td>

                                {{-- Designation Column --}}
                                <td class="px-5 py-4 text-slate-700 text-xs font-medium">
                                    {{ $user->designation ?? '—' }}
                                </td>

                                {{-- Last Login Column --}}
                                <td class="px-5 py-4">
                                    @if ($user->last_login_at)
                                        <span class="text-xs text-slate-600 font-medium">{{ $user->last_login_at->diffForHumans() }}</span>
                                    @else
                                        <span class="text-xs text-slate-400 italic">Never</span>
                                    @endif
                                </td>

                                {{-- Joined Column --}}
                                <td class="px-5 py-4 text-xs text-slate-500 font-medium">
                                    {{ $user->created_at->format('d M Y') }}
                                </td>

                                {{-- Actions Column --}}
                                <td class="px-5 py-4 text-right">
                                    <div class="flex items-center justify-end gap-1.5">
                                        <a href="{{ route('users.show', $user) }}"
                                           class="text-xs font-semibold text-slate-700 bg-slate-100 hover:bg-slate-200 px-2.5 py-1.5 rounded-lg transition"
                                           title="View Profile">
                                            View
                                        </a>
                                        <a href="{{ route('users.edit', $user) }}"
                                           class="text-xs font-semibold text-indigo-700 bg-indigo-50 hover:bg-indigo-100 px-2.5 py-1.5 rounded-lg transition"
                                           title="Edit User &amp; Role">
                                            Edit
                                        </a>
                                        @if ($user->id !== auth()->id())
                                            <form method="POST" action="{{ route('users.destroy', $user) }}"
                                                  onsubmit="return confirm('Are you sure you want to delete {{ addslashes($user->name) }}? This action cannot be undone.')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                        class="text-xs font-semibold text-rose-700 bg-rose-50 hover:bg-rose-100 px-2.5 py-1.5 rounded-lg transition cursor-pointer"
                                                        title="Delete User">
                                                    Delete
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-5 py-14 text-center">
                                    <div class="max-w-sm mx-auto space-y-2">
                                        <div class="w-12 h-12 rounded-2xl bg-slate-100 text-slate-400 flex items-center justify-center mx-auto">
                                            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                                            </svg>
                                        </div>
                                        <p class="text-sm font-bold text-slate-800">No users found</p>
                                        <p class="text-xs text-slate-500">
                                            No users matched your current filter criteria.
                                        </p>
                                        <div class="pt-2">
                                            <a href="{{ route('users.index') }}" class="text-xs font-bold text-indigo-600 hover:underline">
                                                Clear all filters
                                            </a>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if ($users->hasPages())
                <div class="px-5 py-4 border-t border-slate-100 bg-slate-50/50">
                    {{ $users->links() }}
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
