<x-guest-layout>
    <x-slot name="title">Set New Password — YES INDIA FOUNDATION</x-slot>

    <div class="space-y-6">
        <div class="text-center">
            <h2 class="text-2xl sm:text-3xl font-bold text-white tracking-tight">Create New Password</h2>
            <p class="text-xs sm:text-sm text-slate-400 mt-1.5">Please enter your verified email and choose a strong new
                password.</p>
        </div>

        <form method="POST" action="{{ route('password.store') }}" class="space-y-4 text-xs">
            @csrf

            <!-- Password Reset Token -->
            <input type="hidden" name="token" value="{{ $request->route('token') }}">

            <!-- Email Address -->
            <div>
                <label for="email" class="block font-semibold text-slate-300 mb-1.5">Email Address</label>
                <input id="email" type="email" name="email" value="{{ old('email', $request->email) }}" required
                    autofocus autocomplete="username"
                    class="w-full px-3.5 py-2.5 text-xs rounded-xl border border-slate-700/80 bg-slate-900/70 text-white placeholder-slate-500 focus:ring-2 focus:ring-indigo-500/30 focus:border-indigo-500 transition shadow-inner">
                <x-input-error :messages="$errors->get('email')" class="mt-1" />
            </div>

            <!-- Password -->
            <div>
                <label for="password" class="block font-semibold text-slate-300 mb-1.5">New Password</label>
                <input id="password" type="password" name="password" required autocomplete="new-password"
                    class="w-full px-3.5 py-2.5 text-xs rounded-xl border border-slate-700/80 bg-slate-900/70 text-white placeholder-slate-500 focus:ring-2 focus:ring-indigo-500/30 focus:border-indigo-500 transition shadow-inner">
                <x-input-error :messages="$errors->get('password')" class="mt-1" />
            </div>

            <!-- Confirm Password -->
            <div>
                <label for="password_confirmation" class="block font-semibold text-slate-300 mb-1.5">Confirm New
                    Password</label>
                <input id="password_confirmation" type="password" name="password_confirmation" required
                    autocomplete="new-password"
                    class="w-full px-3.5 py-2.5 text-xs rounded-xl border border-slate-700/80 bg-slate-900/70 text-white placeholder-slate-500 focus:ring-2 focus:ring-indigo-500/30 focus:border-indigo-500 transition shadow-inner">
                <x-input-error :messages="$errors->get('password_confirmation')" class="mt-1" />
            </div>

            <!-- Google reCAPTCHA v2 -->
            <x-recaptcha theme="dark" />

            <!-- Submit Button -->
            <button type="submit"
                class="w-full py-3 px-4 text-xs font-bold text-white bg-gradient-to-r from-indigo-600 to-indigo-700 hover:from-indigo-500 hover:to-indigo-600 rounded-xl shadow-lg shadow-indigo-600/25 transition flex items-center justify-center gap-2">
                <span>Save New Password & Sign In</span>
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M14 5l7 7m0 0l-7 7m7-7H3" />
                </svg>
            </button>
        </form>
    </div>
</x-guest-layout>