<x-guest-layout>
    <x-slot name="title">Reset Password — YES INDIA FOUNDATION</x-slot>

    <div class="space-y-6">
        <div class="text-center">
            <h2 class="text-2xl sm:text-3xl font-bold text-white tracking-tight">Forgot Password ?</h2>
            <p class="text-xs sm:text-sm text-slate-400 mt-1.5 leading-relaxed max-w-sm mx-auto">
                Enter your institutional email address and we will send you a secure password reset link to re-establish
                your credentials.
            </p>
        </div>

        <!-- Session Status -->
        <x-auth-session-status class="text-xs" :status="session('status')" />

        <form method="POST" action="{{ route('password.email') }}" class="space-y-4 text-xs">
            @csrf

            <!-- Email Address -->
            <div>
                <label for="email" class="block font-semibold text-slate-300 mb-1.5">Registered Account Email</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-500">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207" />
                        </svg>
                    </div>
                    <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus
                        class="w-full pl-10 pr-3.5 py-2.5 text-xs rounded-xl border border-slate-700/80 bg-slate-900/70 text-white placeholder-slate-500 focus:ring-2 focus:ring-indigo-500/30 focus:border-indigo-500 transition shadow-inner"
                        placeholder="name@yesindiafoundation.com">
                </div>
                <x-input-error :messages="$errors->get('email')" class="mt-1" />
            </div>

            <!-- Google reCAPTCHA v2 -->
            <x-recaptcha theme="dark" />

            <!-- Submit Button -->
            <button type="submit"
                class="w-full py-3 px-4 text-xs font-bold text-white bg-gradient-to-r from-indigo-600 to-indigo-700 hover:from-indigo-500 hover:to-indigo-600 rounded-xl shadow-lg shadow-indigo-600/25 transition flex items-center justify-center gap-2">
                <span>Email Password Reset Link</span>
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M14 5l7 7m0 0l-7 7m7-7H3" />
                </svg>
            </button>
        </form>

        <div class="pt-4 border-t border-slate-800 text-center">
            <a href="{{ route('login') }}"
                class="text-xs font-semibold text-indigo-400 hover:text-indigo-300 transition">
                &larr; Back to Portal Sign In
            </a>
        </div>
    </div>
</x-guest-layout>