<x-app-layout>
    <x-slot name="title">Create User — School Feasibility Portal</x-slot>

    <!-- Full-Width Page Canvas -->
    <div class="w-full space-y-6">
        {{-- Page Header --}}
        <div class="flex items-center gap-3">
            <a href="{{ route('users.index') }}"
                class="flex items-center justify-center w-8 h-8 rounded-lg bg-slate-100 hover:bg-slate-200 text-slate-600 transition">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                </svg>
            </a>
            <div>
                <h1 class="text-2xl font-bold text-slate-900 tracking-tight">Create New User</h1>
                <p class="text-sm text-slate-500 mt-0.5">Add a portal user and assign them a role.</p>
            </div>
        </div>

        {{-- Form Card --}}
        <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden">
            <div class="px-6 py-4 border-b border-slate-100 bg-slate-50/60 flex items-center gap-3">
                <div class="w-9 h-9 rounded-xl bg-indigo-100 flex items-center justify-center">
                    <svg class="w-5 h-5 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
                    </svg>
                </div>
                <div>
                    <p class="text-sm font-semibold text-slate-800">User Details</p>
                    <p class="text-xs text-slate-500">All fields marked * are required.</p>
                </div>
            </div>

            <form method="POST" action="{{ route('users.store') }}" class="p-6 space-y-5">
                @csrf

                {{-- Name & Email --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                    <div>
                        <label for="name" class="block text-xs font-semibold text-slate-700 mb-1.5">Full Name <span
                                class="text-rose-500">*</span></label>
                        <input type="text" id="name" name="name" value="{{ old('name') }}" required
                            placeholder="Full Name"
                            class="w-full text-sm rounded-lg border-slate-300 focus:border-indigo-500 focus:ring-indigo-500 @error('name') border-rose-400 @enderror">
                        @error('name')
                            <p class="text-xs text-rose-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="email" class="block text-xs font-semibold text-slate-700 mb-1.5">Email Address <span
                                class="text-rose-500">*</span></label>
                        <input type="email" id="email" name="email" value="{{ old('email') }}" required
                            placeholder="user@example.com"
                            class="w-full text-sm rounded-lg border-slate-300 focus:border-indigo-500 focus:ring-indigo-500 @error('email') border-rose-400 @enderror">
                        @error('email')
                            <p class="text-xs text-rose-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                {{-- Phone & Designation --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                    <div>
                        <label for="phone" class="block text-xs font-semibold text-slate-700 mb-1.5">Phone
                            Number</label>
                        <input type="text" id="phone" name="phone" value="{{ old('phone') }}"
                            placeholder="+91 98765 43210"
                            class="w-full text-sm rounded-lg border-slate-300 focus:border-indigo-500 focus:ring-indigo-500 @error('phone') border-rose-400 @enderror">
                        @error('phone')
                            <p class="text-xs text-rose-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="designation" class="block text-xs font-semibold text-slate-700 mb-1.5">Designation /
                            Title</label>
                        <input type="text" id="designation" name="designation" value="{{ old('designation') }}"
                            placeholder="Designation"
                            class="w-full text-sm rounded-lg border-slate-300 focus:border-indigo-500 focus:ring-indigo-500 @error('designation') border-rose-400 @enderror">
                        @error('designation')
                            <p class="text-xs text-rose-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                {{-- Role --}}
                <div class="space-y-3">
                    <label for="role" class="block text-xs font-semibold text-slate-700">Assign Role <span
                            class="text-rose-500">*</span></label>
                    <select id="role" name="role" required
                        class="w-full text-sm rounded-xl border-slate-300 focus:border-indigo-500 focus:ring-indigo-500 py-2.5 @error('role') border-rose-400 @enderror">
                        <option value="">— Select a role —</option>
                        @foreach ($roles as $role)
                            <option value="{{ $role->name }}" {{ old('role') === $role->name ? 'selected' : '' }}>
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

                {{-- Password --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                    <div>
                        <label for="password" class="block text-xs font-semibold text-slate-700 mb-1.5">Password <span
                                class="text-rose-500">*</span></label>
                        <input type="password" id="password" name="password" required placeholder="Min. 8 characters"
                            class="w-full text-sm rounded-lg border-slate-300 focus:border-indigo-500 focus:ring-indigo-500 @error('password') border-rose-400 @enderror">
                        @error('password')
                            <p class="text-xs text-rose-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="password_confirmation"
                            class="block text-xs font-semibold text-slate-700 mb-1.5">Confirm Password <span
                                class="text-rose-500">*</span></label>
                        <input type="password" id="password_confirmation" name="password_confirmation" required
                            placeholder="Repeat password"
                            class="w-full text-sm rounded-lg border-slate-300 focus:border-indigo-500 focus:ring-indigo-500">
                    </div>
                </div>

                {{-- Actions --}}
                <div class="pt-2 flex items-center justify-end gap-3 border-t border-slate-100">
                    <a href="{{ route('users.index') }}"
                        class="px-4 py-2 text-xs font-semibold text-slate-600 bg-slate-100 hover:bg-slate-200 rounded-lg transition">
                        Cancel
                    </a>
                    <button type="submit"
                        class="inline-flex items-center gap-2 px-5 py-2 text-xs font-semibold text-white bg-indigo-600 hover:bg-indigo-500 rounded-lg shadow-sm transition">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        Create User
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>