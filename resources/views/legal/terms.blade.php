<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-slate-950 text-slate-100">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Terms & Conditions — YES INDIA FOUNDATION Institutional Feasibility Portal</title>

    <!-- Google Font Figtree -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800&display=swap" rel="stylesheet" />

    <!-- Scripts & Styles -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body
    class="min-h-full font-sans antialiased bg-slate-950 text-slate-200 flex flex-col justify-between selection:bg-indigo-500 selection:text-white">

    <!-- Top Public Navigation Header -->
    <header class="sticky top-0 z-50 bg-slate-900/90 backdrop-blur-md border-b border-slate-800">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-18 flex items-center justify-between">
            <a href="/" class="flex items-center gap-3">
                <img src="{{ asset('logow.png') }}" alt="YES INDIA FOUNDATION" class="h-10 w-auto object-contain">
            </a>

            <div class="flex items-center gap-4 text-xs font-semibold">
                <a href="{{ route('privacy.policy') }}" class="text-slate-400 hover:text-white transition">
                    Privacy Policy
                </a>
                @auth
                    <a href="{{ route('dashboard') }}"
                        class="px-4 py-2 rounded-xl bg-indigo-600 hover:bg-indigo-500 text-white shadow-sm transition">
                        Open Dashboard &rarr;
                    </a>
                @else
                    <a href="{{ route('login') }}"
                        class="px-4 py-2 rounded-xl bg-indigo-600 hover:bg-indigo-500 text-white shadow-sm transition">
                        Sign In to Portal &rarr;
                    </a>
                @endauth
            </div>
        </div>
    </header>

    <!-- Main Content Area -->
    <main class="flex-1 py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-4xl mx-auto space-y-8">

            <!-- Document Hero Title Card -->
            <div
                class="bg-gradient-to-br from-slate-900 via-slate-900 to-indigo-950/70 rounded-3xl p-8 sm:p-10 border border-slate-800 shadow-xl space-y-4">
                <div class="flex items-center gap-2.5 flex-wrap">
                    <span
                        class="px-2.5 py-0.5 rounded-full text-[10px] font-extrabold uppercase tracking-wider bg-indigo-500/20 text-indigo-300 border border-indigo-500/30">
                        Official Governance Protocol
                    </span>
                    <span class="text-xs text-slate-400 font-medium">&bull; YES INDIA FOUNDATION</span>
                    <span class="text-xs text-slate-400 font-medium">&bull; Academic Session: 2026-2027</span>
                </div>
                <h1 class="text-3xl sm:text-4xl font-extrabold text-white tracking-tight">
                    Terms & Conditions of Portal Governance
                </h1>
                <p class="text-sm text-slate-300 leading-relaxed max-w-2xl">
                    These Terms and Conditions govern access to and usage of the YES INDIA FOUNDATION School
                    Feasibility, Institutional Takeover & Assessment System.
                </p>
                <div class="pt-2 text-[11px] text-slate-400 font-mono">
                    Official Document Ref: YIF/DDP/TOS-2026/02 &bull; Enforced by National Governing Council
                </div>
            </div>

            <!-- Content Card -->
            <div
                class="bg-slate-900/70 rounded-3xl p-8 sm:p-12 border border-slate-800/80 shadow-lg space-y-10 text-slate-300 text-sm leading-relaxed">

                <!-- Section 1 -->
                <section class="space-y-3">
                    <h2 class="text-lg font-bold text-white tracking-tight flex items-center gap-2.5 text-indigo-300">
                        <span
                            class="w-7 h-7 rounded-lg bg-indigo-600/30 border border-indigo-500/40 text-indigo-300 flex items-center justify-center font-bold text-xs">1</span>
                        <span>Acceptance & Authority</span>
                    </h2>
                    <p>
                        By accessing, logging into, or submitting data within the YES INDIA FOUNDATION Feasibility
                        Portal, you agree to be bound by these Terms and Conditions and all applicable Indian statutory
                        laws governing educational trusts, societies, and composite school affiliations.
                    </p>
                    <p>
                        Access to this portal is restricted to authorised personnel of YES INDIA FOUNDATION and
                        designated applicant representatives of partner institutions undergoing formal takeover
                        review.
                    </p>
                </section>

                <!-- Section 2 -->
                <section class="space-y-3">
                    <h2 class="text-lg font-bold text-white tracking-tight flex items-center gap-2.5 text-indigo-300">
                        <span
                            class="w-7 h-7 rounded-lg bg-indigo-600/30 border border-indigo-500/40 text-indigo-300 flex items-center justify-center font-bold text-xs">2</span>
                        <span>Field Inspector Code of Conduct & Responsibilities</span>
                    </h2>
                    <p>
                        Designated Field Verification Officers and Lead Inspectors must strictly adhere to the
                        Foundation's Code of Conduct:
                    </p>
                    <ul class="list-disc pl-6 space-y-2 text-xs text-slate-300">
                        <li>
                            <strong class="text-white">Objective Truth in Reporting:</strong> All 13 sections of the
                            Takeover Feasibility Checklist must reflect actual physical observations on site, including
                            photographic evidence of campus facilities, certified structural safety certificates, and
                            verified enrolment rosters.
                        </li>
                        <li>
                            <strong class="text-white">Impartial Scoring:</strong> Scores assigned to academic quality,
                            infrastructure adequacy, and management viability must be supported by empirical
                            documentation and free from conflict of interest.
                        </li>
                        <li>
                            <strong class="text-white">Non-Disclosure of Institutional School Data:</strong> Inspectors
                            shall not disclose financial liabilities, student strength, or staff salary details to
                            unauthorised third parties or competing institutions.
                        </li>
                    </ul>
                </section>

                <!-- Section 3 -->
                <section class="space-y-3">
                    <h2 class="text-lg font-bold text-white tracking-tight flex items-center gap-2.5 text-indigo-300">
                        <span
                            class="w-7 h-7 rounded-lg bg-indigo-600/30 border border-indigo-500/40 text-indigo-300 flex items-center justify-center font-bold text-xs">3</span>
                        <span>Institutional Applicant Representations & Warranties</span>
                    </h2>
                    <p>
                        When submitting institutional school records, the applicant/management entity warrants that:
                    </p>
                    <ul class="list-disc pl-6 space-y-1.5 text-xs text-slate-300">
                        <li>All uploaded land deeds, lease agreements, and structural stability certificates are
                            genuine, authentic, and free from undisclosed legal encumbrances.</li>
                        <li>Reported student numbers, staff salary expenditures, and balance sheet figures reflect true
                            accounting records.</li>
                        <li>Any known disciplinary, regulatory, or judicial proceedings concerning the school or
                            managing society are disclosed in the statutory checklist section.</li>
                    </ul>
                </section>

                <!-- Section 4 -->
                <section class="space-y-3">
                    <h2 class="text-lg font-bold text-white tracking-tight flex items-center gap-2.5 text-indigo-300">
                        <span
                            class="w-7 h-7 rounded-lg bg-indigo-600/30 border border-indigo-500/40 text-indigo-300 flex items-center justify-center font-bold text-xs">4</span>
                        <span>Intellectual Property & Audit Methodology</span>
                    </h2>
                    <p>
                        All proprietary scoring formulas, the 13-section takeover checklist framework, executive PDF
                        report designs, and system algorithms are the exclusive intellectual property of YES INDIA
                        FOUNDATION. Unauthorised reproduction or commercial use outside Foundation proceedings is
                        strictly prohibited.
                    </p>
                </section>

                <!-- Section 5 -->
                <section class="space-y-3">
                    <h2 class="text-lg font-bold text-white tracking-tight flex items-center gap-2.5 text-indigo-300">
                        <span
                            class="w-7 h-7 rounded-lg bg-indigo-600/30 border border-indigo-500/40 text-indigo-300 flex items-center justify-center font-bold text-xs">5</span>
                        <span>Governing Board Approval Discretion</span>
                    </h2>
                    <p>
                        Completion of the Feasibility Checklist and generation of an inspection score does not
                        constitute an agreement or obligation by YES INDIA FOUNDATION to execute a takeover,
                        affiliation, or financial sponsorship. Final takeover approval rests entirely within the
                        sovereign discretion of the Foundation's Governing Board and National Secretariat.
                    </p>
                </section>

                <!-- Section 6 -->
                <section class="space-y-3">
                    <h2 class="text-lg font-bold text-white tracking-tight flex items-center gap-2.5 text-indigo-300">
                        <span
                            class="w-7 h-7 rounded-lg bg-indigo-600/30 border border-indigo-500/40 text-indigo-300 flex items-center justify-center font-bold text-xs">6</span>
                        <span>Governing Law & Dispute Resolution</span>
                    </h2>
                    <p>
                        These Terms and Conditions shall be governed by and construed in accordance with the laws of
                        India. Any legal dispute or proceeding arising under or in connection with this portal shall be
                        subject to the exclusive jurisdiction of the competent courts in New Delhi, India.
                    </p>
                </section>

                <!-- Section 7 -->
                <section class="space-y-3">
                    <h2 class="text-lg font-bold text-white tracking-tight flex items-center gap-2.5 text-indigo-300">
                        <span
                            class="w-7 h-7 rounded-lg bg-indigo-600/30 border border-indigo-500/40 text-indigo-300 flex items-center justify-center font-bold text-xs">7</span>
                        <span>Official Notices & Inquiries</span>
                    </h2>
                    <div
                        class="p-5 rounded-2xl bg-slate-950/60 border border-slate-800 text-xs space-y-1 text-slate-300">
                        <p class="font-bold text-white">National Secretariat, YES INDIA FOUNDATION</p>
                        <p>Directorate of Institutional Feasibility & School Assessment</p>
                        <p>RKP.V/720-A 12, Hanna Tower, Opp. BUS Stand Ramanatukara, Kozhikode -673633, Kerala</p>
                        <p class="pt-1">Email: <a href="mailto:info@yesindiafoundation.com"
                                class="text-indigo-400 hover:underline">info@yesindiafoundation.com</a> | Phone: +91 989 566 2111 | Web: <a href="https://www.yesindiafoundation.com" target="_blank" rel="noopener noreferrer" class="text-indigo-400 hover:underline">www.yesindiafoundation.com</a></p>
                    </div>
                </section>

            </div>

        </div>
    </main>

    <!-- Public Footer -->
    <footer class="bg-slate-900 border-t border-slate-800 py-8 px-4 sm:px-6 lg:px-8 text-xs text-slate-400">
        <div class="max-w-7xl mx-auto flex flex-col sm:flex-row items-center justify-between gap-4">
            <div>
                <span>&copy; {{ date('Y') }} YES INDIA FOUNDATION. All Rights Reserved.</span>
            </div>
            <div class="flex items-center gap-6">
                <a href="{{ route('privacy.policy') }}" class="hover:text-white transition">Privacy Policy</a>
                <a href="{{ route('terms.conditions') }}"
                    class="text-indigo-400 font-semibold underline underline-offset-4">Terms & Conditions</a>
                <a href="{{ route('login') }}" class="hover:text-white transition">Portal Sign In</a>
            </div>
        </div>
    </footer>

</body>

</html>