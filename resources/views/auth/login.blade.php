<x-guest-layout>
    <x-slot name="title">Sign In — YES INDIA FOUNDATION Feasibility Portal</x-slot>

    <div class="space-y-6">
        <!-- Header -->
        <div class="text-center">
            <h2 class="text-2xl sm:text-3xl font-bold text-white tracking-tight">Authentication</h2>
            <p class="text-xs sm:text-sm text-slate-400 mt-1.5">Sign in to access your feasibility studies and
                evaluations.</p>
        </div>

        <!-- Session Status -->
        <x-auth-session-status class="text-xs" :status="session('status')" />

        <form method="POST" action="{{ route('login') }}" class="space-y-4 text-xs">
            @csrf

            <!-- Email Address -->
            <div>
                <label for="email" class="block font-semibold text-slate-300 mb-1.5">Email Address</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-500">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207" />
                        </svg>
                    </div>
                    <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus
                        autocomplete="username" placeholder="user@yesindiafoundation.com"
                        class="w-full pl-10 pr-3.5 py-2.5 text-xs rounded-xl border border-slate-700/80 bg-slate-900/70 text-white placeholder-slate-500 focus:ring-2 focus:ring-indigo-500/30 focus:border-indigo-500 transition shadow-inner">
                </div>
                <x-input-error :messages="$errors->get('email')" class="mt-1" />
            </div>

            <!-- Password -->
            <div>
                <div class="flex items-center justify-between mb-1.5">
                    <label for="password" class="block font-semibold text-slate-300">Password</label>
                    @if (Route::has('password.request'))
                        <a href="{{ route('password.request') }}"
                            class="text-[11px] font-semibold text-indigo-400 hover:text-indigo-300 transition">
                            Forgot Password?
                        </a>
                    @endif
                </div>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-500">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                        </svg>
                    </div>
                    <input id="password" type="password" name="password" required autocomplete="current-password"
                        placeholder="••••••••••••"
                        class="w-full pl-10 pr-3.5 py-2.5 text-xs rounded-xl border border-slate-700/80 bg-slate-900/70 text-white placeholder-slate-500 focus:ring-2 focus:ring-indigo-500/30 focus:border-indigo-500 transition shadow-inner">
                </div>
                <x-input-error :messages="$errors->get('password')" class="mt-1" />
            </div>

            <!-- Remember Me -->
            <div class="flex items-center justify-between py-1">
                <label for="remember_me" class="inline-flex items-center cursor-pointer">
                    <input id="remember_me" type="checkbox"
                        class="rounded border-slate-700 bg-slate-900 text-indigo-600 shadow-xs focus:ring-indigo-500 focus:ring-offset-slate-900"
                        name="remember">
                    <span class="ms-2 text-[11px] text-slate-300 font-medium">Keep me signed in for 30 days</span>
                </label>
            </div>

            <!-- Google reCAPTCHA v2 -->
            <x-recaptcha theme="dark" />

            <!-- Submit Button -->
            <button type="submit"
                class="w-full py-3 px-4 text-xs font-bold text-white bg-gradient-to-r from-indigo-600 to-indigo-700 hover:from-indigo-500 hover:to-indigo-600 rounded-xl shadow-lg shadow-indigo-600/25 transition flex items-center justify-center gap-2 cursor-pointer">
                <span>Sign In to Dashboard</span>
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M14 5l7 7m0 0l-7 7m7-7H3" />
                </svg>
            </button>
        </form>
    </div>
</x-guest-layout>