<x-guest-layout>
    <x-slot name="title">Two-Factor Authentication — YES INDIA FOUNDATION</x-slot>

    <div class="space-y-6" x-data="{ useRecovery: false }">
        <!-- Header -->
        <div class="text-center">
            <h2 class="text-2xl sm:text-3xl font-bold text-white tracking-tight">Two-Factor Verification</h2>
            <p class="text-xs sm:text-sm text-slate-400 mt-1.5 max-w-sm mx-auto leading-relaxed" x-show="!useRecovery">
                Please enter the 6-digit dynamic authentication token from your <strong>Authenticator App</strong>
                (Google Authenticator, Microsoft Authenticator, Authy).
            </p>
            <p class="text-xs sm:text-sm text-slate-400 mt-1.5 max-w-sm mx-auto leading-relaxed" x-show="useRecovery"
                style="display: none;">
                Please enter one of your 8 emergency backup recovery codes (e.g. <strong>ABCD-1234</strong>) to access
                your account.
            </p>
        </div>

        @if ($demoCode)
            <!-- Demo Helper Banner -->
            <div
                class="p-3 rounded-xl bg-amber-500/10 border border-amber-500/20 text-xs text-amber-200 flex items-center justify-between">
                <div>
                    <span class="font-bold block">Test Authentication Token:</span>
                    <span class="text-[11px] text-amber-300/80">Authenticator app code or active OTP:</span>
                </div>
                <button type="button" @click="document.getElementById('code').value = '{{ $demoCode }}'"
                    class="font-mono text-sm font-extrabold text-amber-300 bg-amber-500/20 hover:bg-amber-500/30 border border-amber-500/30 px-3 py-1 rounded-lg transition">
                    {{ $demoCode }}
                </button>
            </div>
        @endif

        <form method="POST" action="{{ route('two-factor.verify') }}" class="space-y-4 text-xs">
            @csrf

            <!-- 6-Digit Code Input -->
            <div x-show="!useRecovery">
                <label for="code" class="block font-semibold text-slate-300 mb-1.5 text-center">6-Digit Authenticator
                    Code</label>
                <input id="code" type="text" name="code" value="{{ $demoCode ?? '' }}" autofocus inputmode="numeric"
                    maxlength="6" placeholder="000000"
                    class="w-full text-center tracking-widest text-2xl font-mono font-bold py-3 rounded-xl border border-slate-700/80 bg-slate-900/70 text-white placeholder-slate-600 focus:ring-2 focus:ring-indigo-500/30 focus:border-indigo-500 transition shadow-inner">
                <x-input-error :messages="$errors->get('code')" class="mt-1.5 text-center" />
            </div>

            <!-- Emergency Recovery Code Input -->
            <div x-show="useRecovery" style="display: none;">
                <label for="recovery_code" class="block font-semibold text-slate-300 mb-1.5 text-center">Emergency
                    Recovery Code</label>
                <input id="recovery_code" type="text" name="recovery_code" placeholder="XXXX-XXXX"
                    @input="if (useRecovery) document.getElementById('code').value = $event.target.value"
                    class="w-full text-center tracking-wider text-lg font-mono font-bold py-3 rounded-xl border border-slate-700/80 bg-slate-900/70 text-white placeholder-slate-600 focus:ring-2 focus:ring-cyan-500/30 focus:border-cyan-500 transition shadow-inner uppercase">
                <p class="text-[11px] text-slate-400 mt-1 text-center">This single-use recovery code will be consumed
                    upon successful sign-in.</p>
            </div>

            <!-- Submit Button -->
            <button type="submit"
                class="w-full py-3 px-4 text-xs font-bold text-white bg-gradient-to-r from-indigo-600 to-indigo-700 hover:from-indigo-500 hover:to-indigo-600 rounded-xl shadow-lg shadow-indigo-600/25 transition flex items-center justify-center gap-2">
                <span>Confirm & Sign In</span>
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </button>
        </form>

        <!-- Toggle between Authenticator Code and Backup Code -->
        <div class="text-center">
            <button type="button"
                @click="useRecovery = !useRecovery; if(useRecovery) { document.getElementById('code').value = ''; setTimeout(() => document.getElementById('recovery_code').focus(), 50); } else { setTimeout(() => document.getElementById('code').focus(), 50); }"
                class="text-xs font-semibold text-indigo-400 hover:text-indigo-300 transition">
                <span x-show="!useRecovery">Lost your device? Use an emergency recovery backup code &rarr;</span>
                <span x-show="useRecovery" style="display: none;">&larr; Use Authenticator app 6-digit code
                    instead</span>
            </button>
        </div>

        <div class="pt-4 border-t border-slate-800 text-center">
            <a href="{{ route('login') }}" class="text-xs font-semibold text-slate-400 hover:text-slate-200 transition">
                &larr; Cancel and return to Login
            </a>
        </div>
    </div>
</x-guest-layout>