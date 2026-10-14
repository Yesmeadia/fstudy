<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-slate-900">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $title ?? 'YES INDIA FOUNDATION — School Feasibility Portal' }}</title>

    <!-- Google Font Figtree -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800&display=swap" rel="stylesheet" />

    <!-- Scripts & Tailwind CSS -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Google reCAPTCHA v2 -->
    @if (config('services.recaptcha.site_key'))
        <script src="https://www.google.com/recaptcha/api.js" async defer></script>
    @endif

    <style>
        @media (max-width: 1023px) {
            .auth-hero-sidebar {
                display: none !important;
            }
            .auth-mobile-header {
                display: block !important;
            }
            .auth-mobile-footer {
                display: block !important;
            }
            .auth-right-panel {
                display: flex !important;
                flex-direction: column !important;
                justify-content: space-between !important;
                align-items: center !important;
            }
        }
        @media (min-width: 1024px) {
            .auth-hero-sidebar {
                display: flex !important;
            }
            .auth-mobile-header {
                display: none !important;
            }
            .auth-mobile-footer {
                display: none !important;
            }
            .auth-right-panel {
                display: flex !important;
                flex-direction: column !important;
                justify-content: center !important;
                align-items: center !important;
            }
            .auth-form-slot {
                margin-top: auto !important;
                margin-bottom: auto !important;
            }
        }
    </style>
</head>

<body class="min-h-screen font-sans antialiased text-slate-100 bg-slate-950">

    <div class="min-h-screen flex flex-col lg:flex-row">
        
        <!-- Left Hero / Brand Canvas (Hidden on mobile & tablet, visible on lg screens) -->
        <div
            class="auth-hero-sidebar lg:w-1/2 relative flex-col justify-between p-8 lg:p-14 bg-gradient-to-br from-slate-950 via-indigo-950 to-slate-900 text-white border-r border-slate-800/80 overflow-hidden shrink-0">
            <!-- Decorative Glow Circles -->
            <div class="absolute -top-24 -left-24 w-96 h-96 rounded-full bg-indigo-600/15 blur-3xl pointer-events-none">
            </div>
            <div
                class="absolute -bottom-24 -right-24 w-96 h-96 rounded-full bg-blue-600/15 blur-3xl pointer-events-none">
            </div>

            <!-- Top Header Logo -->
            <div class="relative z-10">
                <a href="/" class="inline-flex items-center gap-4">
                    <img src="{{ asset('logow.png') }}" alt="YES INDIA FOUNDATION" class="h-12 w-auto object-contain">
                </a>
            </div>

            <!-- Middle Narrative -->
            <div class="relative z-10 my-auto py-8 max-w-lg space-y-6">
                <div
                    class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-indigo-500/10 text-indigo-300 border border-indigo-500/20 text-xs font-semibold">
                    <span class="w-2 h-2 rounded-full bg-indigo-400 animate-pulse"></span>
                    Institutional Feasibility & Accreditation Portal
                </div>

                <h1 class="text-3xl sm:text-4xl font-extrabold tracking-tight text-white leading-tight">
                    Empowering Quality Educational Infrastructure Across India
                </h1>

                <p class="text-sm text-slate-300 leading-relaxed">
                    A comprehensive, multi-criteria assessment portal for establishing, expanding, and evaluating
                    schools. Engineered with demographic formulas, financial viability modelling, and governing board
                    approval workflows.
                </p>

                <!-- Key Metrics Badges -->
                <div class="grid grid-cols-3 gap-3 pt-2">
                    <div class="bg-white/5 border border-white/10 rounded-xl p-3">
                        <span class="block text-xl font-bold text-amber-400">13-Section</span>
                        <span class="text-[11px] text-slate-400 uppercase tracking-wider">Feasibility Audit</span>
                    </div>
                    <div class="bg-white/5 border border-white/10 rounded-xl p-3">
                        <span class="block text-xl font-bold text-indigo-400">100%</span>
                        <span class="text-[11px] text-slate-400 uppercase tracking-wider">Statutory Audit</span>
                    </div>
                    <div class="bg-white/5 border border-white/10 rounded-xl p-3">
                        <span class="block text-xl font-bold text-emerald-400">A4 PDF</span>
                        <span class="text-[11px] text-slate-400 uppercase tracking-wider">Executive Reports</span>
                    </div>
                </div>
            </div>

            <!-- Footer Legal & Security Badges -->
            <div
                class="relative z-10 pt-6 border-t border-slate-800/80 text-xs text-slate-400 flex flex-col sm:flex-row items-center justify-between gap-3">
                <span class="text-slate-500">&copy; {{ date('Y') }} YES INDIA FOUNDATION. All Rights Reserved.</span>
                <div class="flex items-center gap-3 text-[11px]">
                    <a href="{{ route('privacy.policy') }}" class="text-slate-400 hover:text-cyan-400 transition underline underline-offset-4">
                        Privacy Policy
                    </a>
                    <span class="text-slate-600">&bull;</span>
                    <a href="{{ route('terms.conditions') }}" class="text-slate-400 hover:text-cyan-400 transition underline underline-offset-4">
                        Terms & Conditions
                    </a>
                </div>
            </div>
        </div>

        <!-- Right Authentication Panel -->
        <div class="auth-right-panel w-full lg:w-1/2 flex flex-col justify-between lg:justify-center items-center p-6 sm:p-10 lg:p-14 min-h-screen bg-slate-900/60 backdrop-blur-md">
            <!-- Mobile Brand Header (Visible ONLY on Mobile & Tablet) -->
            <div class="auth-mobile-header w-full max-w-md mx-auto pt-2 pb-2 text-center">
                <a href="/" class="inline-flex items-center justify-center">
                    <img src="{{ asset('logow.png') }}" alt="YES INDIA FOUNDATION" class="h-10 w-auto object-contain mx-auto">
                </a>
                <div class="mt-2">
                    <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full bg-indigo-500/10 text-indigo-300 border border-indigo-500/20 text-[10px] font-semibold tracking-wide uppercase">
                        <span class="w-1.5 h-1.5 rounded-full bg-indigo-400 animate-pulse"></span>
                        YES INDIA FOUNDATION &bull; Feasibility Portal
                    </span>
                </div>
            </div>

            <!-- Centered Form Slot (Compatible with all auth views) -->
            <div class="auth-form-slot w-full max-w-md mx-auto my-auto py-4">
                {{ $slot }}
            </div>

            <!-- Mobile Footer (Visible ONLY on Mobile & Tablet) -->
            <div class="auth-mobile-footer w-full max-w-md mx-auto pt-4 pb-2 border-t border-slate-800/80 text-[11px] text-slate-400 text-center space-y-1.5">
                <div class="flex items-center justify-center gap-3">
                    <a href="{{ route('privacy.policy') }}" class="hover:text-cyan-400 transition underline underline-offset-2">
                        Privacy Policy
                    </a>
                    <span class="text-slate-600">&bull;</span>
                    <a href="{{ route('terms.conditions') }}" class="hover:text-cyan-400 transition underline underline-offset-2">
                        Terms & Conditions
                    </a>
                </div>
                <p class="text-[10px] text-slate-500">&copy; {{ date('Y') }} YES INDIA FOUNDATION. All Rights Reserved.</p>
            </div>
        </div>

    </div>

</body>

</html>