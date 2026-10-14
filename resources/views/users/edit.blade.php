<x-app-layout>
    <x-slot name="title">Edit User — School Feasibility Portal</x-slot>

    <!-- Full-Width Page Canvas -->
    <div class="w-full space-y-6">
        {{-- Page Header --}}
        <div class="flex items-center gap-3">
            <a href="{{ route('users.show', $user) }}"
               class="flex items-center justify-center w-8 h-8 rounded-lg bg-slate-100 hover:bg-slate-200 text-slate-600 transition">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                </svg>
            </a>
            <div>
                <h1 class="text-2xl font-bold text-slate-900 tracking-tight">Edit User</h1>
                <p class="text-sm text-slate-500 mt-0.5">Updating profile for <span class="font-semibold text-slate-700">{{ $user->name }}</span></p>
            </div>
        </div>

        {{-- Form Card --}}
        <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden">
            <div class="px-6 py-4 border-b border-slate-100 bg-slate-50/60 flex items-center gap-3">
                <div class="w-9 h-9 rounded-full overflow-hidden border border-slate-200 flex items-center justify-center text-sm font-bold shrink-0 bg-slate-100">
                    @if ($user->avatar)
                        <img src="{{ asset('storage/' . $user->avatar) }}" alt="{{ $user->name }}" class="w-full h-full object-cover">
                    @else
                        <div class="w-full h-full bg-gradient-to-tr from-indigo-600 to-indigo-400 text-white flex items-center justify-center">
                            {{ strtoupper(substr($user->name, 0, 1)) }}
                        </div>
                    @endif
                </div>
                <div>
                    <p class="text-sm font-semibold text-slate-800">{{ $user->name }}</p>
                    <p class="text-xs text-slate-400">{{ $user->email }}</p>
                </div>
            </div>

            <form method="POST" action="{{ route('users.update', $user) }}" class="p-6 space-y-5">
                @csrf
                @method('PATCH')

                {{-- Name & Email --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                    <div>
                        <label for="name" class="block text-xs font-semibold text-slate-700 mb-1.5">Full Name <span class="text-rose-500">*</span></label>
                        <input type="text" id="name" name="name" value="{{ old('name', $user->name) }}" required
                               class="w-full text-sm rounded-lg border-slate-300 focus:border-indigo-500 focus:ring-indigo-500 @error('name') border-rose-400 @enderror">
                        @error('name')
                            <p class="text-xs text-rose-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="email" class="block text-xs font-semibold text-slate-700 mb-1.5">Email Address <span class="text-rose-500">*</span></label>
                        <input type="email" id="email" name="email" value="{{ old('email', $user->email) }}" required
                               class="w-full text-sm rounded-lg border-slate-300 focus:border-indigo-500 focus:ring-indigo-500 @error('email') border-rose-400 @enderror">
                        @error('email')
                            <p class="text-xs text-rose-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                {{-- Phone & Designation --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                    <div>
                        <label for="phone" class="block text-xs font-semibold text-slate-700 mb-1.5">Phone Number</label>
                        <input type="text" id="phone" name="phone" value="{{ old('phone', $user->phone) }}"
                               placeholder="+91 98765 43210"
                               class="w-full text-sm rounded-lg border-slate-300 focus:border-indigo-500 focus:ring-indigo-500 @error('phone') border-rose-400 @enderror">
                        @error('phone')
                            <p class="text-xs text-rose-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="designation" class="block text-xs font-semibold text-slate-700 mb-1.5">Designation / Title</label>
                        <input type="text" id="designation" name="designation" value="{{ old('designation', $user->designation) }}"
                               placeholder="e.g. District Education Officer"
                               class="w-full text-sm rounded-lg border-slate-300 focus:border-indigo-500 focus:ring-indigo-500 @error('designation') border-rose-400 @enderror">
                        @error('designation')
                            <p class="text-xs text-rose-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                {{-- Role --}}
                <div class="space-y-3">
                    <label for="role" class="block text-xs font-semibold text-slate-700">Assigned Role <span class="text-rose-500">*</span></label>
                    <select id="role" name="role" required
                            class="w-full text-sm rounded-xl border-slate-300 focus:border-indigo-500 focus:ring-indigo-500 py-2.5 @error('role') border-rose-400 @enderror">
                        <option value="">— Select a role —</option>
                        @foreach ($roles as $role)
                            <option value="{{ $role->name }}"
                                {{ old('role', $user->roles->first()?->name) === $role->name ? 'selected' : '' }}>
                                {{ $role->name }} {{ $role->name === 'Super Admin' ? '— (Full System Authority)' : '— (Field Inspection & Audit Evaluator)' }}
                            </option>
                        @endforeach
                    </select>
                    @error('role')
                        <p class="text-xs text-rose-500 mt-1">{{ $message }}</p>
                    @enderror

                    {{-- Role Explanations --}}
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 pt-1 text-xs">
                        <div class="p-3.5 rounded-xl border border-purple-100 bg-purple-50/50 space-y-1">
                            <div class="flex items-center gap-1.5 font-bold text-purple-900">
                                <svg class="w-3.5 h-3.5 text-purple-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                                </svg>
                                <span>Super Admin</span>
                            </div>
                            <p class="text-[11px] text-purple-800/80 leading-relaxed">
                                Complete administrative authority. Can review, approve, or reject takeover feasibility applications, assign inspectors, and manage users.
                            </p>
                        </div>
                        <div class="p-3.5 rounded-xl border border-indigo-100 bg-indigo-50/50 space-y-1">
                            <div class="flex items-center gap-1.5 font-bold text-indigo-900">
                                <svg class="w-3.5 h-3.5 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                                </svg>
                                <span>Inspector</span>
                            </div>
                            <p class="text-[11px] text-indigo-800/80 leading-relaxed">
                                Field inspection &amp; audit evaluator. Can record campus feasibility checklists, capture evidence photos, and submit reports for review.
                            </p>
                        </div>
                    </div>
                </div>

                {{-- Password (optional on edit) --}}
                <div class="rounded-xl border border-amber-100 bg-amber-50 p-4 space-y-4">
                    <p class="text-xs font-semibold text-amber-700 flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                        </svg>
                        Reset Password — Leave blank to keep the current password
                    </p>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label for="password" class="block text-xs font-semibold text-slate-700 mb-1.5">New Password</label>
                            <input type="password" id="password" name="password"
                                   placeholder="Min. 8 characters"
                                   class="w-full text-sm rounded-lg border-slate-300 focus:border-indigo-500 focus:ring-indigo-500 @error('password') border-rose-400 @enderror">
                            @error('password')
                                <p class="text-xs text-rose-500 mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="password_confirmation" class="block text-xs font-semibold text-slate-700 mb-1.5">Confirm New Password</label>
                            <input type="password" id="password_confirmation" name="password_confirmation"
                                   placeholder="Repeat new password"
                                   class="w-full text-sm rounded-lg border-slate-300 focus:border-indigo-500 focus:ring-indigo-500">
                        </div>
                    </div>
                </div>

                {{-- Actions --}}
                <div class="pt-2 flex items-center justify-between border-t border-slate-100">
                    @if ($user->id !== auth()->id())
                        <form method="POST" action="{{ route('users.destroy', $user) }}"
                              onsubmit="return confirm('Delete {{ addslashes($user->name) }}? This cannot be undone.')">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                    class="inline-flex items-center gap-2 px-4 py-2 text-xs font-semibold text-rose-600 bg-rose-50 hover:bg-rose-100 rounded-lg transition">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                </svg>
                                Delete User
                            </button>
                        </form>
                    @else
                        <div></div>
                    @endif

                    <div class="flex items-center gap-3">
                        <a href="{{ route('users.show', $user) }}"
                           class="px-4 py-2 text-xs font-semibold text-slate-600 bg-slate-100 hover:bg-slate-200 rounded-lg transition">
                            Cancel
                        </a>
                        <button type="submit"
                                class="inline-flex items-center gap-2 px-5 py-2 text-xs font-semibold text-white bg-indigo-600 hover:bg-indigo-500 rounded-lg shadow-sm transition">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                            Save Changes
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
