<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-slate-950 text-slate-100">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Privacy Policy — YES INDIA FOUNDATION Institutional Feasibility Portal</title>

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
                <a href="{{ route('terms.conditions') }}" class="text-slate-400 hover:text-white transition">
                    Terms & Conditions
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
                        Official Policy Document
                    </span>
                    <span class="text-xs text-slate-400 font-medium">&bull; YES INDIA FOUNDATION</span>
                    <span class="text-xs text-slate-400 font-medium">&bull; Effective Session: 2026-2027</span>
                </div>
                <h1 class="text-3xl sm:text-4xl font-extrabold text-white tracking-tight">
                    Privacy & Data Governance Policy
                </h1>
                <p class="text-sm text-slate-300 leading-relaxed max-w-2xl">
                    This Privacy Policy outlines how the YES INDIA FOUNDATION Institutional Feasibility & Takeover
                    Assessment Portal collects, safeguards, and processes institutional, financial, and operational
                    information.
                </p>
                <div class="pt-2 text-[11px] text-slate-400 font-mono">
                    Last Revised: September 2026 &bull; Secretariat Document Ref: YIF/DDP/POL-2026/01
                </div>
            </div>

            <!-- Content Card -->
            <div
                class="bg-slate-900/70 rounded-3xl p-8 sm:p-12 border border-slate-800/80 shadow-lg space-y-10 text-slate-300 text-sm leading-relaxed">

                <!-- Section 1: Statutory Framework & Purpose -->
                <section class="space-y-3">
                    <h2 class="text-lg font-bold text-white tracking-tight flex items-center gap-2.5 text-indigo-300">
                        <span class="w-7 h-7 rounded-lg bg-indigo-600/30 border border-indigo-500/40 text-indigo-300 flex items-center justify-center font-bold text-xs">1</span>
                        <span>Statutory Framework & Purpose Specification</span>
                    </h2>
                    <p>
                        YES INDIA FOUNDATION ("the Foundation", "Data Fiduciary") operates this Institutional Feasibility & Takeover Assessment Portal in adherence to the <strong>Digital Personal Data Protection (DPDP) Act, 2023</strong>, the <strong>Information Technology Act, 2000</strong> (and applicable intermediary guidelines), and CERT-In cyber incident directives.
                    </p>
                    <p>
                        This portal is dedicated strictly to evaluating prospective schools, conducting infrastructural risk reviews, assessing academic readiness, and calculating economic viability for institutional transition, takeover, or management support. Personal data processed through this platform is collected solely for specified institutional verification purposes.
                    </p>
                </section>

                <!-- Section 2: Data Minimisation & Categories Collected -->
                <section class="space-y-3">
                    <h2 class="text-lg font-bold text-white tracking-tight flex items-center gap-2.5 text-indigo-300">
                        <span class="w-7 h-7 rounded-lg bg-indigo-600/30 border border-indigo-500/40 text-indigo-300 flex items-center justify-center font-bold text-xs">2</span>
                        <span>Data Minimisation & Categories of Information</span>
                    </h2>
                    <p>
                        Under our strict <strong>Data Minimisation</strong> policy, we collect only the minimal information strictly necessary to execute feasibility audits. We do not gather extraneous consumer metadata, student biometric records, or third-party marketing telemetry.
                    </p>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 pt-2">
                        <div class="p-4 rounded-xl bg-slate-950/60 border border-slate-800 space-y-1.5">
                            <h3 class="text-xs font-bold text-indigo-300 uppercase tracking-wider">Institutional Data</h3>
                            <p class="text-xs text-slate-300">School registration deeds, board affiliations (CBSE/ICSE/State), land ownership documents, fire safety NOCs, classroom and playground acreage dimensions.</p>
                        </div>
                        <div class="p-4 rounded-xl bg-slate-950/60 border border-slate-800 space-y-1.5">
                            <h3 class="text-xs font-bold text-indigo-300 uppercase tracking-wider">Aggregated Academic Demographics</h3>
                            <p class="text-xs text-slate-300">Aggregated grade-wise enrolment rosters, staff-to-student ratios, dropout rates, and fee tier distributions (no individual student personal profiles).</p>
                        </div>
                        <div class="p-4 rounded-xl bg-slate-950/60 border border-slate-800 space-y-1.5">
                            <h3 class="text-xs font-bold text-indigo-300 uppercase tracking-wider">Aggregated Financial & Staff Headcount</h3>
                            <p class="text-xs text-slate-300">Audited 3-year balance sheets, statutory PF/ESI compliance indicators, operational expenditure budgets, and aggregated faculty salary outlays.</p>
                        </div>
                        <div class="p-4 rounded-xl bg-slate-950/60 border border-slate-800 space-y-1.5">
                            <h3 class="text-xs font-bold text-indigo-300 uppercase tracking-wider">Evaluator & User Account Data</h3>
                            <p class="text-xs text-slate-300">Name, institutional email, phone number, designated role, and IP address collected for multi-factor authentication and security auditing.</p>
                        </div>
                    </div>
                </section>

                <!-- Section 3: Multi-Tenant School Isolation & RBAC -->
                <section class="space-y-3">
                    <h2 class="text-lg font-bold text-white tracking-tight flex items-center gap-2.5 text-indigo-300">
                        <span class="w-7 h-7 rounded-lg bg-indigo-600/30 border border-indigo-500/40 text-indigo-300 flex items-center justify-center font-bold text-xs">3</span>
                        <span>Role-Based Access Control (RBAC) & School Isolation</span>
                    </h2>
                    <p>
                        Institutional confidentiality is preserved through a defense-in-depth architecture:
                    </p>
                    <ul class="list-disc pl-6 space-y-1.5 text-xs text-slate-300">
                        <li><strong>Cryptographic School/Organization Isolation:</strong> Institutional school records, financial models, and feasibility scores are segregated at the database query layer. Users cannot view, query, or edit institutions outside their verified institutional domain.</li>
                        <li><strong>Granular Role Gating:</strong> Permissions are strictly enforced across Super Administrators, Lead Field Inspectors, and School Applicants. Action capabilities (such as report approvals or application status promotion) require explicit privileges.</li>
                        <li><strong>Child Record Scoping:</strong> Associated financial ledger entries, capital expenditure proposals, and competitor benchmarking schools are locked to their parent application ID, preventing parameter tampering.</li>
                    </ul>
                </section>

                <!-- Section 4: Secure Storage, HTTPS & Encryption -->
                <section class="space-y-3">
                    <h2 class="text-lg font-bold text-white tracking-tight flex items-center gap-2.5 text-indigo-300">
                        <span class="w-7 h-7 rounded-lg bg-indigo-600/30 border border-indigo-500/40 text-indigo-300 flex items-center justify-center font-bold text-xs">4</span>
                        <span>Secure Document Storage & HTTPS Transport</span>
                    </h2>
                    <ul class="list-disc pl-6 space-y-1.5 text-xs text-slate-300">
                        <li><strong>Mandatory HTTPS/TLS 1.3:</strong> All communications between portal users and server endpoints are encrypted in transit with strict HSTS policies. Plaintext HTTP traffic is rejected or automatically upgraded.</li>
                        <li><strong>Non-Public Private Storage Disk:</strong> Statutory certificates, land deeds, and audit attachments are deposited on isolated, non-web-accessible storage disks outside the public root. Direct file URL enumeration is blocked.</li>
                        <li><strong>Cryptographic Randomization & Integrity:</strong> File uploads receive unguessable UUID identifiers, MIME-type binary validation, and SHA-256 integrity checksums to protect against tampering.</li>
                        <li><strong>Gated Stream Streaming:</strong> Uploaded documents can only be retrieved through authenticated controller endpoints enforcing school ownership and inspector authorization.</li>
                    </ul>
                </section>

                <!-- Section 5: Immutable Audit Trails -->
                <section class="space-y-3">
                    <h2 class="text-lg font-bold text-white tracking-tight flex items-center gap-2.5 text-indigo-300">
                        <span class="w-7 h-7 rounded-lg bg-indigo-600/30 border border-indigo-500/40 text-indigo-300 flex items-center justify-center font-bold text-xs">5</span>
                        <span>Immutable Audit Logs</span>
                    </h2>
                    <p>
                        In compliance with ISO 27001 and DPDP guidelines, the portal maintains append-only, tamper-evident audit logs capturing:
                    </p>
                    <ul class="list-disc pl-6 space-y-1 text-xs text-slate-300">
                        <li>Authentication events (successful logins, logouts, failed password attempts, IP addresses, and user agents).</li>
                        <li>Data lifecycle events (application creation, step progress saves, evaluation score updates, and board decisions).</li>
                        <li>Document operations (file uploads, verified removals, and inspector downloads).</li>
                        <li>Data Subject requests (DSAR personal data dossier exports and account erasure requests).</li>
                    </ul>
                </section>

                <!-- Section 6: Data Retention & Automated Purge Schedules -->
                <section class="space-y-3">
                    <h2 class="text-lg font-bold text-white tracking-tight flex items-center gap-2.5 text-indigo-300">
                        <span class="w-7 h-7 rounded-lg bg-indigo-600/30 border border-indigo-500/40 text-indigo-300 flex items-center justify-center font-bold text-xs">6</span>
                        <span>Data Retention Schedules & Automated Purging</span>
                    </h2>
                    <p>
                        Personal and institutional data is retained only for periods justified by statutory obligations, educational governance standards, and audit requirements:
                    </p>
                    <div class="overflow-x-auto">
                        <table class="w-full text-xs text-left text-slate-300 border border-slate-800 rounded-xl overflow-hidden">
                            <thead class="bg-slate-950 text-indigo-300 uppercase font-semibold text-[10px] tracking-wider">
                                <tr>
                                    <th class="py-2.5 px-4">Data Category</th>
                                    <th class="py-2.5 px-4">Retention Period</th>
                                    <th class="py-2.5 px-4">Purge / Archival Action</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-800/80 bg-slate-950/40">
                                <tr>
                                    <td class="py-2.5 px-4 font-semibold text-white">Completed & Approved Feasibility Studies</td>
                                    <td class="py-2.5 px-4">7 Years (2,555 Days)</td>
                                    <td class="py-2.5 px-4">Archived for statutory board governance; documents sanitized upon expiry</td>
                                </tr>
                                <tr>
                                    <td class="py-2.5 px-4 font-semibold text-white">Incomplete / Abandoned Draft Applications</td>
                                    <td class="py-2.5 px-4">180 Days</td>
                                    <td class="py-2.5 px-4">Automated deletion of drafts and associated temporary documents via daily cron</td>
                                </tr>
                                <tr>
                                    <td class="py-2.5 px-4 font-semibold text-white">Security & Audit Trails</td>
                                    <td class="py-2.5 px-4">365 Days</td>
                                    <td class="py-2.5 px-4">Retained for incident analysis and CERT-In compliance; aggregated thereafter</td>
                                </tr>
                                <tr>
                                    <td class="py-2.5 px-4 font-semibold text-white">Temporary Session & Upload Tokens</td>
                                    <td class="py-2.5 px-4">24 Hours</td>
                                    <td class="py-2.5 px-4">Immediate system purge upon session expiration</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </section>

                <!-- Section 7: Data Subject Rights (DSAR) & Deletion Workflow -->
                <section class="space-y-3">
                    <h2 class="text-lg font-bold text-white tracking-tight flex items-center gap-2.5 text-indigo-300">
                        <span class="w-7 h-7 rounded-lg bg-indigo-600/30 border border-indigo-500/40 text-indigo-300 flex items-center justify-center font-bold text-xs">7</span>
                        <span>Data Rights Workflow (Access, Portability & Deletion)</span>
                    </h2>
                    <p>
                        Under the DPDP Act 2023, registered institutional users and data principals hold verifiable rights regarding their personal data:
                    </p>
                    <ul class="list-disc pl-6 space-y-1.5 text-xs text-slate-300">
                        <li><strong>Right to Access & Portability:</strong> Users can download an authenticated, machine-readable JSON dossier containing all personal profiles, school links, and audit histories directly from their <em>Account Profile &rarr; Data Rights & Privacy Controls</em>.</li>
                        <li><strong>Right to Correction:</strong> Users can rectify inaccurate profile attributes or update institutional credentials through their account dashboard.</li>
                        <li><strong>Right to Erasure:</strong> Users can trigger password-verified account deletion. Upon confirmation, personal identifiers and session tokens are permanently scrubbed. Anonymised audit records are retained solely where required by legal and financial statute.</li>
                        <li><strong>Right to Grievance Redressal:</strong> Grievance requests are processed by our Data Protection Officer within 30 days of receipt.</li>
                    </ul>
                </section>

                <!-- Section 8: Sub-Processor & Third-Party Vendor Register -->
                <section class="space-y-3">
                    <h2 class="text-lg font-bold text-white tracking-tight flex items-center gap-2.5 text-indigo-300">
                        <span class="w-7 h-7 rounded-lg bg-indigo-600/30 border border-indigo-500/40 text-indigo-300 flex items-center justify-center font-bold text-xs">8</span>
                        <span>Sub-Processor & Third-Party Vendor Register</span>
                    </h2>
                    <p>
                        The Foundation contracts with selected third-party data processors bound by stringent Data Processing Addendums (DPAs) guaranteeing equivalent data protection benchmarks:
                    </p>
                    <div class="overflow-x-auto">
                        <table class="w-full text-xs text-left text-slate-300 border border-slate-800 rounded-xl overflow-hidden">
                            <thead class="bg-slate-950 text-indigo-300 uppercase font-semibold text-[10px] tracking-wider">
                                <tr>
                                    <th class="py-2.5 px-4">Vendor / Processor</th>
                                    <th class="py-2.5 px-4">Processing Function</th>
                                    <th class="py-2.5 px-4">Data Transferred</th>
                                    <th class="py-2.5 px-4">Location / Standard</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-800/80 bg-slate-950/40">
                                <tr>
                                    <td class="py-2.5 px-4 font-semibold text-white">Google Maps Platform</td>
                                    <td class="py-2.5 px-4">Satellite imagery & geospatial coordinates</td>
                                    <td class="py-2.5 px-4">Latitude/Longitude for campus perimeter review</td>
                                    <td class="py-2.5 px-4">India / Global (Encrypted API)</td>
                                </tr>
                                <tr>
                                    <td class="py-2.5 px-4 font-semibold text-white">Cloud Infrastructure Provider</td>
                                    <td class="py-2.5 px-4">Hardened VPS hosting & database operations</td>
                                    <td class="py-2.5 px-4">Encrypted portal records & application databases</td>
                                    <td class="py-2.5 px-4">India Region (ISO 27001 / SOC 2)</td>
                                </tr>
                                <tr>
                                    <td class="py-2.5 px-4 font-semibold text-white">Transactional Mail Provider</td>
                                    <td class="py-2.5 px-4">Account verification & security alerts</td>
                                    <td class="py-2.5 px-4">User email address and login confirmation links</td>
                                    <td class="py-2.5 px-4">TLS 1.3 / DPA Encrypted</td>
                                </tr>
                                <tr>
                                    <td class="py-2.5 px-4 font-semibold text-white">Local Encrypted Document Store</td>
                                    <td class="py-2.5 px-4">Private document repository</td>
                                    <td class="py-2.5 px-4">Institutional deeds, safety certificates, balance sheets</td>
                                    <td class="py-2.5 px-4">On-premise / Restricted Non-public Disk</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </section>

                <!-- Section 9: Breach Response & Incident Notification Protocol -->
                <section class="space-y-3">
                    <h2 class="text-lg font-bold text-white tracking-tight flex items-center gap-2.5 text-indigo-300">
                        <span class="w-7 h-7 rounded-lg bg-indigo-600/30 border border-indigo-500/40 text-indigo-300 flex items-center justify-center font-bold text-xs">9</span>
                        <span>Security Incident Response & Breach Notification Protocol</span>
                    </h2>
                    <p>
                        The Foundation enforces a structured Security Incident and Breach Response Protocol in compliance with CERT-In directions and the DPDP Act 2023:
                    </p>
                    <ul class="list-disc pl-6 space-y-1.5 text-xs text-slate-300">
                        <li><strong>Incident Detection & Containment:</strong> Anomalous access patterns, rate-limit triggers, and document access failures are logged automatically. Administrators can formally initiate an incident lockdown from the Portal Security Console.</li>
                        <li><strong>Statutory Notification Timelines:</strong>
                            <ul class="list-circle pl-6 pt-1 space-y-1 text-slate-400">
                                <li><strong>CERT-In Mandatory Reporting:</strong> Cybersecurity incidents are reported to the Indian Computer Emergency Response Team (CERT-In) within <strong>6 hours</strong> of noticing the incident.</li>
                                <li><strong>Data Protection Board & Data Principals:</strong> Any confirmed breach compromising personal data is notified to the Data Protection Board of India and affected data principals without unreasonable delay (targeted within <strong>72 hours</strong>), detailing the nature of the breach, affected categories, and mitigating steps taken.</li>
                            </ul>
                        </li>
                        <li><strong>Remediation & Forensic Archival:</strong> All actions taken during an incident are sealed into the immutable audit register for regulatory evaluation.</li>
                    </ul>
                </section>

                <!-- Section 10: Contact Information & Grievance Officer -->
                <section class="space-y-3">
                    <h2 class="text-lg font-bold text-white tracking-tight flex items-center gap-2.5 text-indigo-300">
                        <span class="w-7 h-7 rounded-lg bg-indigo-600/30 border border-indigo-500/40 text-indigo-300 flex items-center justify-center font-bold text-xs">10</span>
                        <span>Data Protection Officer & Grievance Redressal</span>
                    </h2>
                    <p>
                        To exercise statutory data rights, report privacy concerns, or submit a data subject inquiry, contact our designated Data Protection & Grievance Redressal Officer:
                    </p>
                    <div class="p-6 rounded-2xl bg-slate-950/60 border border-slate-800 text-xs space-y-2 text-slate-300">
                        <div class="flex items-center justify-between border-b border-slate-800 pb-3">
                            <div>
                                <p class="font-bold text-white text-sm">Grievance Redressal & Data Protection Officer</p>
                                <p class="text-indigo-400 text-xs font-semibold">Institutional Feasibility Directorate &bull; YES INDIA FOUNDATION</p>
                            </div>
                            <span class="px-2.5 py-1 rounded-full text-[10px] font-bold bg-emerald-500/20 text-emerald-400 border border-emerald-500/30">DPDP Redressal Desk</span>
                        </div>
                        <div class="pt-1 space-y-1">
                            <p><strong>Physical Address:</strong> RKP.V/720-A 12, Hanna Tower, Opp. BUS Stand Ramanatukara, Kozhikode - 673633, Kerala, India</p>
                            <p><strong>Official Email:</strong> <a href="mailto:info@yesindiafoundation.com" class="text-indigo-400 hover:underline">info@yesindiafoundation.com</a> / <a href="mailto:dpo@yesindiafoundation.com" class="text-indigo-400 hover:underline">dpo@yesindiafoundation.com</a></p>
                            <p><strong>Direct Desk Helpline:</strong> +91 989 566 2111</p>
                            <p><strong>Statutory Response Turnaround:</strong> Initial acknowledgment within 48 hours; substantive resolution within 30 days.</p>
                        </div>
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
                <a href="{{ route('privacy.policy') }}"
                    class="text-indigo-400 font-semibold underline underline-offset-4">Privacy Policy</a>
                <a href="{{ route('terms.conditions') }}" class="hover:text-white transition">Terms & Conditions</a>
                <a href="{{ route('login') }}" class="hover:text-white transition">Portal Sign In</a>
            </div>
        </div>
    </footer>

</body>

</html>