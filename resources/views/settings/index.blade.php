<x-app-layout>
    <x-slot name="title">System Settings & Evaluation Weights — Portal</x-slot>

    <!-- Full-Width Page Canvas -->
    <div class="w-full space-y-6">
        <div>
            <h1 class="text-2xl font-bold text-slate-900 tracking-tight">Portal Settings & Evaluation Weights</h1>
            <p class="text-sm text-slate-500 mt-1">Configure institutional identity, scoring thresholds, and dynamically tune evaluation weights.</p>
        </div>

        <form method="POST" action="{{ route('settings.update') }}" class="space-y-6 text-xs">
            @csrf

            <!-- Organization Profile -->
            <x-card title="1. Organization & Branding Details">
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div class="sm:col-span-2">
                        <label class="block font-medium text-slate-700 mb-1">Organization / Directorate Name</label>
                        <input type="text" name="org_name" value="{{ \App\Models\Setting::get('org_name') }}" class="w-full text-xs rounded border-slate-300">
                    </div>
                    <div class="sm:col-span-2">
                        <label class="block font-medium text-slate-700 mb-1">Official Address</label>
                        <input type="text" name="org_address" value="{{ \App\Models\Setting::get('org_address') }}" class="w-full text-xs rounded border-slate-300">
                    </div>
                    <div>
                        <label class="block font-medium text-slate-700 mb-1">Official Contact Information</label>
                        <input type="text" name="org_contact" value="{{ \App\Models\Setting::get('org_contact') }}" class="w-full text-xs rounded border-slate-300">
                    </div>
                    <div>
                        <label class="block font-medium text-slate-700 mb-1">Current Academic Session</label>
                        <input type="text" name="academic_year" value="{{ \App\Models\Setting::get('academic_year', '2026-2027') }}" class="w-full text-xs rounded border-slate-300">
                    </div>
                </div>
            </x-card>

            <!-- Feasibility Scoring Thresholds -->
            <x-card title="2. Feasibility Rating Thresholds (%)" subtitle="Minimum overall weighted score percentage required for each feasibility grade">
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <div>
                        <label class="block font-medium text-emerald-700 mb-1">Highly Feasible Threshold (&ge; %)</label>
                        <input type="number" step="0.5" name="score_threshold_highly_feasible" value="{{ \App\Models\Setting::get('score_threshold_highly_feasible', 80.0) }}" class="w-full text-xs rounded border-slate-300">
                    </div>
                    <div>
                        <label class="block font-medium text-blue-700 mb-1">Feasible Threshold (&ge; %)</label>
                        <input type="number" step="0.5" name="score_threshold_feasible" value="{{ \App\Models\Setting::get('score_threshold_feasible', 65.0) }}" class="w-full text-xs rounded border-slate-300">
                    </div>
                    <div>
                        <label class="block font-medium text-amber-700 mb-1">Conditionally Feasible (&ge; %)</label>
                        <input type="number" step="0.5" name="score_threshold_conditional" value="{{ \App\Models\Setting::get('score_threshold_conditional', 50.0) }}" class="w-full text-xs rounded border-slate-300">
                    </div>
                </div>
                <p class="text-[11px] text-slate-400 mt-2">Scores below the conditional threshold (default &lt; 50%) are automatically classified as <strong>Not Feasible</strong>.</p>
            </x-card>

            <!-- Dynamic Criteria Weights -->
            <x-card title="3. Configurable Feasibility Criteria Weights" subtitle="Adjust the weighting percentages across dimensions (must sum to 100%)">
                <div class="space-y-3">
                    @foreach ($criteria as $crit)
                        <div class="flex items-center justify-between p-3 bg-slate-50 rounded-lg border border-slate-200">
                            <div>
                                <span class="font-bold text-slate-900">{{ $crit->name }}</span>
                                <span class="text-[11px] text-slate-400 block">{{ $crit->subcriteria->count() }} subcriteria components</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <input type="number" step="0.5" min="0" max="100"
                                       name="criteria_weights[{{ $crit->id }}]"
                                       value="{{ $crit->weight_percentage }}"
                                       class="w-20 text-xs font-bold text-indigo-700 rounded border-slate-300 text-right">
                                <span class="text-slate-500 font-semibold">%</span>
                            </div>
                        </div>
                    @endforeach
                </div>
            </x-card>

            <!-- Statutory Data Retention Schedules (DPDP Act 2023) -->
            <x-card title="4. Statutory Data Retention Schedules & Expiry" subtitle="Define statutory preservation periods and automated expiry for archived records">
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <div>
                        <label class="block font-medium text-slate-700 mb-1">Completed Studies Retention (Years)</label>
                        <input type="number" min="1" max="25" name="data_retention_years_completed" value="{{ \App\Models\Setting::get('data_retention_years_completed', 7) }}" class="w-full text-xs rounded border-slate-300">
                        <span class="text-[10px] text-slate-400 mt-1 block">Preserved for statutory trust limitation period.</span>
                    </div>
                    <div>
                        <label class="block font-medium text-slate-700 mb-1">Abandoned Draft Expiry (Days)</label>
                        <input type="number" min="30" max="365" name="data_retention_days_draft" value="{{ \App\Models\Setting::get('data_retention_days_draft', 180) }}" class="w-full text-xs rounded border-slate-300">
                        <span class="text-[10px] text-slate-400 mt-1 block">Unsubmitted drafts purged automatically after inactivity.</span>
                    </div>
                    <div>
                        <label class="block font-medium text-slate-700 mb-1">Immutable Audit Trail Retention (Days)</label>
                        <input type="number" min="90" max="1825" name="data_retention_days_audit_logs" value="{{ \App\Models\Setting::get('data_retention_days_audit_logs', 730) }}" class="w-full text-xs rounded border-slate-300">
                        <span class="text-[10px] text-slate-400 mt-1 block">Security & login event preservation duration (2 years).</span>
                    </div>
                </div>
            </x-card>

            <!-- Data Protection & Grievance Redressal Contacts -->
            <x-card title="5. Data Protection Officer & Grievance Redressal" subtitle="Statutory contact details published on public notices and reports">
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <div>
                        <label class="block font-medium text-slate-700 mb-1">Designated Grievance Officer</label>
                        <input type="text" name="dpo_name" value="{{ \App\Models\Setting::get('dpo_name', 'National Secretariat Grievance Officer') }}" class="w-full text-xs rounded border-slate-300">
                    </div>
                    <div>
                        <label class="block font-medium text-slate-700 mb-1">Grievance Officer Email</label>
                        <input type="email" name="dpo_email" value="{{ \App\Models\Setting::get('dpo_email', 'compliance@yesindiafoundation.com') }}" class="w-full text-xs rounded border-slate-300">
                    </div>
                    <div>
                        <label class="block font-medium text-slate-700 mb-1">Statutory Redressal Phone</label>
                        <input type="text" name="dpo_phone" value="{{ \App\Models\Setting::get('dpo_phone', '+91 989 566 2111') }}" class="w-full text-xs rounded border-slate-300">
                    </div>
                </div>
            </x-card>

            <div class="flex items-center justify-end">
                <button type="submit" class="px-6 py-2.5 bg-indigo-600 hover:bg-indigo-500 text-white font-bold rounded-lg shadow-sm transition">
                    Save System Settings & Weights
                </button>
            </div>
        </form>

        <!-- SUB-PROCESSOR / VENDOR REGISTER (OUTSIDE MAIN FORM) -->
        <x-card title="6. Authorized Third-Party Sub-Processor & Vendor Register" subtitle="Transparent inventory of all external services processing institutional data">
            <div class="overflow-x-auto text-xs">
                <table class="w-full text-left">
                    <thead class="bg-slate-50 font-semibold text-slate-600 border-b border-slate-200">
                        <tr>
                            <th class="p-3">Vendor / Processor</th>
                            <th class="p-3">Service Role</th>
                            <th class="p-3">Data Processed</th>
                            <th class="p-3">Jurisdiction</th>
                            <th class="p-3 text-right">Security Safeguards</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        <tr class="hover:bg-slate-50/50">
                            <td class="p-3 font-bold text-slate-900">Google LLC</td>
                            <td class="p-3 text-slate-600">reCAPTCHA v2 Bot Protection</td>
                            <td class="p-3 text-slate-500 font-mono text-[11px]">User verification token & IP</td>
                            <td class="p-3 text-slate-600">USA / Global Cloud</td>
                            <td class="p-3 text-right"><span class="px-2 py-0.5 rounded text-[10px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">SOC 2 / ISO 27001</span></td>
                        </tr>
                        <tr class="hover:bg-slate-50/50">
                            <td class="p-3 font-bold text-slate-900">OpenStreetMap Nominatim</td>
                            <td class="p-3 text-slate-600">Reverse Geocoding</td>
                            <td class="p-3 text-slate-500 font-mono text-[11px]">GPS coordinates only</td>
                            <td class="p-3 text-slate-600">UK / Germany</td>
                            <td class="p-3 text-right"><span class="px-2 py-0.5 rounded text-[10px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">TLS 1.3 / Proxied</span></td>
                        </tr>
                        <tr class="hover:bg-slate-50/50">
                            <td class="p-3 font-bold text-slate-900">Google LLC (Google Maps)</td>
                            <td class="p-3 text-slate-600">Satellite Aerial Tiles</td>
                            <td class="p-3 text-slate-500 font-mono text-[11px]">Map zoom & tile coordinates</td>
                            <td class="p-3 text-slate-600">USA / Global</td>
                            <td class="p-3 text-right"><span class="px-2 py-0.5 rounded text-[10px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">ISO 27001 / FedRAMP</span></td>
                        </tr>
                        <tr class="hover:bg-slate-50/50">
                            <td class="p-3 font-bold text-slate-900">BigDataCloud Pty Ltd</td>
                            <td class="p-3 text-slate-600">Secondary Geocode Fallback</td>
                            <td class="p-3 text-slate-500 font-mono text-[11px]">GPS coordinates only</td>
                            <td class="p-3 text-slate-600">Australia</td>
                            <td class="p-3 text-right"><span class="px-2 py-0.5 rounded text-[10px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">TLS 1.3 / Anonymized</span></td>
                        </tr>
                        <tr class="hover:bg-slate-50/50">
                            <td class="p-3 font-bold text-slate-900">Local Isolated Private Vault</td>
                            <td class="p-3 text-slate-600">Document Storage</td>
                            <td class="p-3 text-slate-500 font-mono text-[11px]">Statutory deeds & balance sheets</td>
                            <td class="p-3 text-slate-600">India (Domestic Host)</td>
                            <td class="p-3 text-right"><span class="px-2 py-0.5 rounded text-[10px] font-bold bg-indigo-50 text-indigo-700 border border-indigo-200">UUID Hashed / Isolated Disk</span></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </x-card>

        <!-- SECURITY INCIDENT & BREACH RESPONSE PROTOCOL (OPERATIONAL) -->
        <x-card title="7. Security Incident & Data Breach Response Protocol" subtitle="Operational incident management and statutory CERT-In / DPDP Act compliance">
            <div class="space-y-4 text-xs" x-data="{ openIncidentModal: false }">
                <div class="p-4 rounded-2xl bg-amber-50/80 border border-amber-200/80 text-amber-950 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                    <div class="space-y-1">
                        <div class="flex items-center gap-2">
                            <span class="w-2 h-2 rounded-full bg-amber-500 animate-ping"></span>
                            <span class="font-bold text-sm">Statutory Breach Response SLA Matrix</span>
                        </div>
                        <p class="text-slate-600 leading-relaxed text-[11px]">
                            Pursuant to CERT-In Cyber Security Directions (6-Hour Notice) and the Digital Personal Data Protection Act 2023 (72-Hour Notice), any confirmed or suspected compromise of personal/institutional records must be logged immediately.
                        </p>
                    </div>

                    <div class="flex items-center gap-2 shrink-0">
                        <form method="POST" action="{{ route('settings.purge') }}" onsubmit="return confirm('Execute automated statutory data retention purge now?');">
                            @csrf
                            <button type="submit" class="px-3.5 py-2 rounded-xl bg-white border border-slate-300 text-slate-700 font-semibold hover:bg-slate-50 shadow-2xs transition">
                                Run Retention Purge Now
                            </button>
                        </form>
                        <button type="button" @click="openIncidentModal = true" class="px-4 py-2 rounded-xl bg-rose-600 hover:bg-rose-500 text-white font-bold shadow-xs transition flex items-center gap-1.5">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                            </svg>
                            <span>Record Security Incident</span>
                        </button>
                    </div>
                </div>

                <!-- Record Incident Modal -->
                <div x-show="openIncidentModal" style="display: none;" class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
                    <div class="flex items-center justify-center min-h-screen px-4 text-center">
                        <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-xs transition-opacity" @click="openIncidentModal = false"></div>
                        <div class="relative bg-white rounded-3xl p-6 sm:p-8 max-w-lg w-full border border-slate-200 shadow-xl text-left space-y-4">
                            <h4 class="text-base font-bold text-slate-900 tracking-tight flex items-center gap-2">
                                <span class="w-2.5 h-2.5 rounded-full bg-rose-600"></span>
                                <span>Record Security Incident in Immutable Audit Trail</span>
                            </h4>
                            <p class="text-xs text-slate-500">
                                This will record an official breach investigation dossier entry with automated statutory deadline markers.
                            </p>
                            <form method="POST" action="{{ route('settings.incident') }}" class="space-y-3.5">
                                @csrf
                                <div>
                                    <label class="block font-medium text-slate-700 mb-1">Incident Headline / Summary</label>
                                    <input type="text" name="incident_title" required placeholder="e.g. Unauthorized credential probe or token anomaly" class="w-full text-xs rounded-xl border-slate-300 py-2 px-3">
                                </div>
                                <div class="grid grid-cols-2 gap-3">
                                    <div>
                                        <label class="block font-medium text-slate-700 mb-1">Severity Level</label>
                                        <select name="severity" required class="w-full text-xs rounded-xl border-slate-300 py-2 px-3">
                                            <option value="low">Low (Routine Anomaly)</option>
                                            <option value="medium">Medium (Targeted Probe)</option>
                                            <option value="high">High (Access Breach Attempt)</option>
                                            <option value="critical">Critical (Confirmed Data Compromise)</option>
                                        </select>
                                    </div>
                                    <div>
                                        <label class="block font-medium text-slate-700 mb-1">Affected Systems</label>
                                        <input type="text" name="affected_systems" required placeholder="e.g. Document Vault, Auth API" class="w-full text-xs rounded-xl border-slate-300 py-2 px-3">
                                    </div>
                                </div>
                                <div>
                                    <label class="block font-medium text-slate-700 mb-1">Incident Investigation Details</label>
                                    <textarea name="incident_details" rows="3" required placeholder="Describe technical observations, timestamps, and impacted accounts..." class="w-full text-xs rounded-xl border-slate-300 py-2 px-3"></textarea>
                                </div>
                                <div>
                                    <label class="block font-medium text-slate-700 mb-1">Immediate Containment Measures</label>
                                    <textarea name="containment_actions" rows="2" placeholder="e.g. Session revoked, IP blacklisted, credentials rotated..." class="w-full text-xs rounded-xl border-slate-300 py-2 px-3"></textarea>
                                </div>
                                <div class="flex items-center justify-end gap-2 pt-2 border-t border-slate-100">
                                    <button type="button" @click="openIncidentModal = false" class="px-4 py-2 rounded-xl text-xs font-semibold text-slate-600 hover:bg-slate-100 transition">
                                        Cancel
                                    </button>
                                    <button type="submit" class="px-5 py-2 rounded-xl text-xs font-bold text-white bg-rose-600 hover:bg-rose-500 shadow-xs transition">
                                        Submit Incident Dossier
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </x-card>
    </div>
</x-app-layout>
