<x-app-layout>
    <x-slot name="title">{{ $user->name }} — User Profile</x-slot>

    <!-- Full-Width Page Canvas -->
    <div class="w-full space-y-6">

        {{-- Top Navigation & Action Header --}}
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div class="flex items-center gap-3">
                <a href="{{ route('users.index') }}"
                   class="flex items-center justify-center w-9 h-9 rounded-xl bg-white hover:bg-slate-100 text-slate-600 border border-slate-200/80 shadow-xs transition"
                   title="Back to Users Directory">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                </a>
                <div>
                    <div class="flex items-center gap-2">
                        <a href="{{ route('users.index') }}" class="text-xs font-semibold text-slate-400 hover:text-slate-600 transition">User Directory</a>
                        <span class="text-slate-300 text-xs">/</span>
                        <span class="text-xs font-semibold text-slate-700">Profile Details</span>
                    </div>
                    <h1 class="text-2xl font-extrabold text-slate-900 tracking-tight">User Profile</h1>
                </div>
            </div>

            <div class="flex items-center gap-2.5">
                <a href="{{ route('users.index') }}"
                   class="inline-flex items-center gap-2 px-3.5 py-2 text-xs font-semibold text-slate-700 bg-white hover:bg-slate-50 rounded-xl border border-slate-200/90 shadow-xs transition">
                    <svg class="w-3.5 h-3.5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16" />
                    </svg>
                    <span>All Users</span>
                </a>

                <a href="{{ route('users.edit', $user) }}"
                   class="inline-flex items-center gap-2 px-4 py-2 text-xs font-semibold text-indigo-700 bg-indigo-50 hover:bg-indigo-100 rounded-xl border border-indigo-200/80 shadow-xs transition">
                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                    </svg>
                    <span>Edit User</span>
                </a>

                @if ($user->id !== auth()->id())
                    <form method="POST" action="{{ route('users.destroy', $user) }}"
                          onsubmit="return confirm('Permanently delete {{ addslashes($user->name) }}? This cannot be undone.')">
                        @csrf
                        @method('DELETE')
                        <button type="submit"
                                class="inline-flex items-center gap-2 px-3.5 py-2 text-xs font-semibold text-rose-600 bg-rose-50 hover:bg-rose-100 rounded-xl border border-rose-200/80 shadow-xs transition">
                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                            </svg>
                            <span>Delete</span>
                        </button>
                    </form>
                @endif
            </div>
        </div>

        {{-- Executive Profile Header Card --}}
        <div class="w-full bg-white rounded-3xl p-6 sm:p-8 border border-slate-200/90 shadow-sm">
            <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-6">
                <div class="flex flex-col sm:flex-row items-start sm:items-center gap-5 sm:gap-6">
                    {{-- Avatar --}}
                    <div class="w-20 h-20 sm:w-24 sm:h-24 rounded-3xl overflow-hidden border-4 border-slate-100 shadow-sm flex items-center justify-center bg-slate-100 shrink-0">
                        @if ($user->avatar)
                            <img src="{{ asset('storage/' . $user->avatar) }}" alt="{{ $user->name }}" class="w-full h-full object-cover">
                        @else
                            <div class="w-full h-full bg-gradient-to-tr from-indigo-600 to-violet-500 text-white flex items-center justify-center font-extrabold text-3xl sm:text-4xl shadow-inner">
                                {{ strtoupper(substr($user->name, 0, 1)) }}
                            </div>
                        @endif
                    </div>

                    {{-- Name, Roles & Contact Info --}}
                    <div class="space-y-1.5 min-w-0">
                        <div class="flex items-center gap-2.5 flex-wrap">
                            <h2 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight">{{ $user->name }}</h2>

                            @forelse ($user->roles as $role)
                                @if ($role->name === 'Super Admin')
                                    <span class="inline-flex items-center gap-1.5 px-3.5 py-1 rounded-full text-xs font-black tracking-wide uppercase border border-purple-200 bg-purple-50 text-purple-700 shadow-2xs">
                                        <svg class="w-3.5 h-3.5 text-purple-600 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                                        </svg>
                                        {{ $role->name }}
                                    </span>
                                @elseif ($role->name === 'Inspector')
                                    <span class="inline-flex items-center gap-1.5 px-3.5 py-1 rounded-full text-xs font-black tracking-wide uppercase border border-indigo-200 bg-indigo-50 text-indigo-700 shadow-2xs">
                                        <svg class="w-3.5 h-3.5 text-indigo-600 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                                        </svg>
                                        {{ $role->name }}
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold tracking-wide uppercase border border-slate-200 bg-slate-50 text-slate-700 shadow-2xs">
                                        {{ $role->name }}
                                    </span>
                                @endif
                            @empty
                                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-amber-50 text-amber-700 border border-amber-200">
                                    <svg class="w-3.5 h-3.5 text-amber-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                    </svg>
                                    No Role Assigned
                                </span>
                            @endforelse

                            @if ($user->two_factor_enabled)
                                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold text-emerald-700 bg-emerald-50 border border-emerald-300 uppercase tracking-wider">
                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                                    2FA Protected
                                </span>
                            @endif
                        </div>

                        <p class="text-xs sm:text-sm text-slate-500 flex items-center gap-2 flex-wrap">
                            <span class="font-medium text-slate-700">{{ $user->email }}</span>
                            @if ($user->phone)
                                <span>&bull;</span>
                                <span>{{ $user->phone }}</span>
                            @endif
                            @if ($user->designation)
                                <span>&bull;</span>
                                <span class="text-indigo-600 font-medium">{{ $user->designation }}</span>
                            @endif
                        </p>
                    </div>
                </div>

                {{-- Metrics Summary Badges --}}
                <div class="grid grid-cols-2 sm:grid-cols-3 gap-3 shrink-0 pt-2 lg:pt-0">
                    <div class="px-4 py-2.5 rounded-2xl bg-slate-50 border border-slate-200/80 text-center">
                        <p class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Assigned</p>
                        <p class="text-lg font-black text-slate-900">{{ $user->assigned_applications_count ?? 0 }}</p>
                    </div>
                    <div class="px-4 py-2.5 rounded-2xl bg-slate-50 border border-slate-200/80 text-center">
                        <p class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Field Visits</p>
                        <p class="text-lg font-black text-slate-900">{{ $user->field_visits_count ?? 0 }}</p>
                    </div>
                    <div class="col-span-2 sm:col-span-1 px-4 py-2.5 rounded-2xl bg-slate-50 border border-slate-200/80 text-center">
                        <p class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Member Since</p>
                        <p class="text-xs font-bold text-slate-800 mt-1 font-mono">{{ $user->created_at->format('M Y') }}</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Main 2-Column Responsive Dashboard Layout --}}
        <div class="w-full grid grid-cols-1 lg:grid-cols-12 gap-6">

            {{-- Primary Left Column (8 cols) --}}
            <div class="lg:col-span-8 space-y-6">

                {{-- Card 1: Role Privileges & Assigned System Permissions --}}
                <div class="bg-white rounded-3xl p-6 sm:p-7 border border-slate-200/80 shadow-xs space-y-5">
                    <div class="flex items-center justify-between border-b border-slate-100 pb-4">
                        <div class="flex items-center gap-3">
                            <div class="w-9 h-9 rounded-xl bg-violet-50 text-violet-600 flex items-center justify-center font-bold">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-sm font-bold text-slate-900">Role Privileges & Access Rights</h3>
                                <p class="text-xs text-slate-500">Security permissions associated with this user's assigned role.</p>
                            </div>
                        </div>

                        <span class="px-2.5 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider bg-slate-100 text-slate-700">
                            {{ $user->getAllPermissions()->count() }} Active Rights
                        </span>
                    </div>

                    <div class="space-y-4">
                        <div class="flex flex-wrap items-center gap-2">
                            @forelse ($user->roles as $role)
                                <div class="p-4 rounded-2xl bg-indigo-50/50 border border-indigo-100 w-full space-y-1">
                                    <div class="flex items-center justify-between">
                                        <h4 class="font-bold text-xs uppercase tracking-wider text-indigo-900">{{ $role->name }}</h4>
                                        <span class="text-[11px] font-semibold text-indigo-600">Assigned Role</span>
                                    </div>
                                    <div class="text-xs text-indigo-900/90 space-y-2 mt-1">
                                        @if ($role->name === 'Super Admin')
                                            <p class="text-indigo-800/80">Full administrative authority across user management, system audit logs, evaluations, and workflow configurations.</p>
                                            <div class="flex items-center gap-2 pt-1 flex-wrap">
                                                <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-md text-[11px] font-bold bg-emerald-100 text-emerald-800 border border-emerald-200">
                                                    <svg class="w-3 h-3 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                                                    </svg>
                                                    Final Decision Authority
                                                </span>
                                                <span class="text-[11px] text-slate-500">Authorized to approve, conditionally approve, return, or reject feasibility applications.</span>
                                            </div>
                                        @elseif ($role->name === 'Inspector')
                                            <p class="text-indigo-800/80">Authorized to conduct school feasibility evaluations, field visits, verification reviews, and submit evaluation assessments.</p>
                                            <div class="flex items-center gap-2 pt-1 flex-wrap">
                                                <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-md text-[11px] font-bold bg-amber-100 text-amber-900 border border-amber-200">
                                                    <svg class="w-3 h-3 text-amber-700" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                                    </svg>
                                                    No Final Acceptance / Rejection Authority
                                                </span>
                                                <span class="text-[11px] text-slate-500">Evaluation & recommendation only. No authorization to accept or reject applications; final authority is strictly reserved for Super Admin / Management.</span>
                                            </div>
                                        @else
                                            <p class="text-indigo-800/80">Role assigned for feasibility portal operations.</p>
                                        @endif
                                    </div>
                                </div>
                            @empty
                                <div class="p-4 rounded-2xl bg-slate-50 border border-slate-200 text-slate-500 text-xs w-full">
                                    No roles currently assigned to this user.
                                </div>
                            @endforelse
                        </div>

                        {{-- Granular Permissions Grid --}}
                        <div class="pt-2">
                            <p class="text-[11px] font-bold uppercase tracking-wider text-slate-400 mb-3">Effective Granted Permissions</p>
                            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-2">
                                @forelse ($user->getAllPermissions() as $perm)
                                    <div class="flex items-center gap-2 px-3 py-2 rounded-xl bg-slate-50 border border-slate-200/80 text-[11px] font-mono text-slate-700">
                                        <span class="w-1.5 h-1.5 rounded-full bg-indigo-500 shrink-0"></span>
                                        <span class="truncate">{{ $perm->name }}</span>
                                    </div>
                                @empty
                                    <div class="col-span-3 p-3 text-center text-slate-400 text-xs italic">
                                        No specific granular permissions attached.
                                    </div>
                                @endforelse
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Card 3: Assigned Feasibility Applications --}}
                <div class="bg-white rounded-3xl p-6 sm:p-7 border border-slate-200/80 shadow-xs space-y-5">
                    <div class="flex items-center justify-between border-b border-slate-100 pb-4">
                        <div class="flex items-center gap-3">
                            <div class="w-9 h-9 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center font-bold">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-sm font-bold text-slate-900">Assigned Feasibility Applications</h3>
                                <p class="text-xs text-slate-500">School feasibility assessments assigned to this officer.</p>
                            </div>
                        </div>

                        <span class="px-2.5 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider bg-slate-100 text-slate-700">
                            {{ $user->assignedApplications->count() }} Records
                        </span>
                    </div>

                    @if ($user->assignedApplications->isNotEmpty())
                        <div class="overflow-x-auto">
                            <table class="w-full text-left text-xs">
                                <thead class="bg-slate-50 font-semibold text-slate-500 border-b border-slate-100">
                                    <tr>
                                        <th class="p-3 rounded-l-xl">App #</th>
                                        <th class="p-3">School Name</th>
                                        <th class="p-3">Location</th>
                                        <th class="p-3">Status</th>
                                        <th class="p-3 text-right rounded-r-xl">Action</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-100">
                                    @foreach ($user->assignedApplications as $app)
                                        <tr class="hover:bg-slate-50/60 transition">
                                            <td class="p-3 font-mono font-bold text-slate-900">
                                                {{ $app->application_number }}
                                            </td>
                                            <td class="p-3 font-semibold text-slate-800">
                                                {{ $app->school?->name ?? '—' }}
                                            </td>
                                            <td class="p-3 text-slate-500">
                                                {{ $app->school?->district ? $app->school->district . ', ' . $app->school->state : '—' }}
                                            </td>
                                            <td class="p-3">
                                                @if (method_exists($app->status, 'color'))
                                                    <x-badge :variant="$app->status->color()">
                                                        {{ $app->status->label() }}
                                                    </x-badge>
                                                @else
                                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold bg-slate-100 text-slate-700">
                                                        {{ $app->status }}
                                                    </span>
                                                @endif
                                            </td>
                                            <td class="p-3 text-right">
                                                <a href="{{ route('applications.show', $app) }}"
                                                   class="inline-flex items-center gap-1 text-xs font-semibold text-indigo-600 hover:text-indigo-800 transition">
                                                    <span>View</span>
                                                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                                    </svg>
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="py-8 text-center rounded-2xl bg-slate-50/70 border border-dashed border-slate-200">
                            <svg class="w-8 h-8 text-slate-300 mx-auto mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                      d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                            </svg>
                            <p class="text-xs font-medium text-slate-600">No Feasibility Cases Assigned</p>
                            <p class="text-[11px] text-slate-400 mt-0.5">Currently there are no active feasibility applications assigned to this user.</p>
                        </div>
                    @endif
                </div>

            </div>

            {{-- Secondary Right Column (4 cols) --}}
            <div class="lg:col-span-4 space-y-6">

                {{-- Card 1: Account Security & Protection --}}
                <div class="bg-white rounded-3xl p-6 border border-slate-200/80 shadow-xs space-y-5">
                    <div class="border-b border-slate-100 pb-3.5">
                        <h3 class="text-sm font-bold text-slate-900">Security & Authentication</h3>
                        <p class="text-xs text-slate-500">Security safeguards enforced on this profile.</p>
                    </div>

                    <div class="space-y-3.5 text-xs">
                        {{-- 2FA Status --}}
                        <div class="flex items-center justify-between p-3 rounded-2xl bg-slate-50 border border-slate-200/70">
                            <div class="flex items-center gap-2.5">
                                <div class="w-7 h-7 rounded-lg {{ $user->two_factor_enabled ? 'bg-emerald-100 text-emerald-600' : 'bg-slate-200 text-slate-500' }} flex items-center justify-center">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                              d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                    </svg>
                                </div>
                                <div>
                                    <p class="font-semibold text-slate-800">Two-Factor Auth</p>
                                    <p class="text-[10px] text-slate-400">TOTP Authenticator</p>
                                </div>
                            </div>
                            <div>
                                @if ($user->two_factor_enabled)
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                        Enabled
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-medium bg-slate-100 text-slate-500">
                                        Disabled
                                    </span>
                                @endif
                            </div>
                        </div>

                        {{-- Account Status --}}
                        <div class="flex items-center justify-between p-3 rounded-2xl bg-slate-50 border border-slate-200/70">
                            <div class="flex items-center gap-2.5">
                                <div class="w-7 h-7 rounded-lg bg-emerald-100 text-emerald-600 flex items-center justify-center">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                    </svg>
                                </div>
                                <div>
                                    <p class="font-semibold text-slate-800">Account Status</p>
                                    <p class="text-[10px] text-slate-400">Portal Access</p>
                                </div>
                            </div>
                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                Active
                            </span>
                        </div>

                        {{-- Password Protection --}}
                        <div class="flex items-center justify-between p-3 rounded-2xl bg-slate-50 border border-slate-200/70">
                            <div class="flex items-center gap-2.5">
                                <div class="w-7 h-7 rounded-lg bg-indigo-100 text-indigo-600 flex items-center justify-center">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                              d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z" />
                                    </svg>
                                </div>
                                <div>
                                    <p class="font-semibold text-slate-800">Password Encryption</p>
                                    <p class="text-[10px] text-slate-400">Bcrypt / Argon2id</p>
                                </div>
                            </div>
                            <span class="text-[11px] font-semibold text-slate-600">Enforced</span>
                        </div>
                    </div>
                </div>

                {{-- Card 2: Session & Access Telemetry --}}
                <div class="bg-white rounded-3xl p-6 border border-slate-200/80 shadow-xs space-y-4">
                    <div class="border-b border-slate-100 pb-3.5">
                        <h3 class="text-sm font-bold text-slate-900">Session & Access Telemetry</h3>
                        <p class="text-xs text-slate-500">Historical timestamps and login endpoints.</p>
                    </div>

                    <div class="space-y-3 text-xs">
                        <div class="p-3 rounded-2xl bg-slate-50 border border-slate-200/70">
                            <p class="text-[10px] font-bold uppercase tracking-wider text-slate-400 mb-1">Last Portal Sign-In</p>
                            @if ($user->last_login_at)
                                <p class="text-xs font-bold text-slate-800">{{ $user->last_login_at->format('d M Y, h:i A') }}</p>
                                <p class="text-[11px] text-indigo-600 font-medium mt-0.5">{{ $user->last_login_at->diffForHumans() }}</p>
                            @else
                                <p class="text-xs text-slate-400 italic">Never signed in yet</p>
                            @endif
                        </div>

                        <div class="p-3 rounded-2xl bg-slate-50 border border-slate-200/70">
                            <p class="text-[10px] font-bold uppercase tracking-wider text-slate-400 mb-1">Last Authenticated IP</p>
                            <p class="text-xs font-mono font-semibold text-slate-800">
                                {{ $user->last_login_ip ?: '127.0.0.1' }}
                            </p>
                        </div>

                        <div class="p-3 rounded-2xl bg-slate-50 border border-slate-200/70">
                            <p class="text-[10px] font-bold uppercase tracking-wider text-slate-400 mb-1">Account Creation Date</p>
                            <p class="text-xs font-medium text-slate-700">{{ $user->created_at->format('d M Y, h:i A') }}</p>
                            <p class="text-[11px] text-slate-400 mt-0.5">({{ $user->created_at->diffForHumans() }})</p>
                        </div>
                    </div>
                </div>

                {{-- Card 3: Recent Activity Trail --}}
                <div class="bg-white rounded-3xl p-6 border border-slate-200/80 shadow-xs space-y-4">
                    <div class="flex items-center justify-between border-b border-slate-100 pb-3.5">
                        <div>
                            <h3 class="text-sm font-bold text-slate-900">Recent User Operations</h3>
                            <p class="text-xs text-slate-500">Security and audit log events.</p>
                        </div>
                        <a href="{{ route('audit_logs.index') }}" class="text-[11px] font-semibold text-indigo-600 hover:text-indigo-800 transition">
                            All Logs
                        </a>
                    </div>

                    <div class="space-y-2.5 text-xs">
                        @forelse ($recentLogs as $log)
                            <div class="p-3 rounded-2xl bg-slate-50 border border-slate-200/70 space-y-1">
                                <div class="flex items-center justify-between">
                                    <span class="font-bold text-slate-800 capitalize">
                                        {{ str_replace('_', ' ', $log->action) }}
                                    </span>
                                    <span class="text-[10px] text-slate-400 font-mono">
                                        {{ $log->created_at->diffForHumans() }}
                                    </span>
                                </div>
                                <div class="flex items-center justify-between text-[11px] text-slate-500">
                                    <span>IP: <strong class="font-mono text-slate-700">{{ $log->ip_address ?: '127.0.0.1' }}</strong></span>
                                    <span class="font-mono">{{ $log->created_at->format('d M, H:i') }}</span>
                                </div>
                            </div>
                        @empty
                            <div class="py-5 text-center text-slate-400 text-xs italic">
                                No recent audit events recorded for this user.
                            </div>
                        @endforelse
                    </div>
                </div>

            </div>

        </div>

    </div>
</x-app-layout>
