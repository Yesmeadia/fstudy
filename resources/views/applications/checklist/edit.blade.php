<x-app-layout>
    <x-slot name="title">Takeover Feasibility Checklist — {{ $application->application_number }}</x-slot>

    <!-- Full-Width Page Canvas -->
    <div class="w-full space-y-5 sm:space-y-6 pb-28 sm:pb-16" x-data="checklistApp()">
        <!-- Header Banner -->
        <div class="bg-white rounded-2xl border border-slate-200/90 shadow-sm p-4 sm:p-6">
            <div
                class="flex flex-col lg:flex-row lg:items-center justify-between gap-4 border-b border-slate-100 pb-4 sm:pb-5">
                <div class="space-y-1.5">
                    <div class="flex items-center flex-wrap gap-2">
                        <span
                            class="px-2.5 py-0.5 rounded-full text-[10px] font-extrabold uppercase tracking-wider bg-indigo-50 text-indigo-700 border border-indigo-200">
                            YES INDIA FOUNDATION
                        </span>
                        <span class="text-xs font-semibold text-slate-500 uppercase tracking-wider">
                            {{ $application->application_number }}
                        </span>
                        <span
                            class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold {{ $application->status->badgeClass() }}">
                            {{ $application->status->label() }}
                        </span>
                    </div>
                    <h1 class="text-lg sm:text-2xl font-extrabold text-slate-900 tracking-tight leading-tight">
                        RUNNING SCHOOL TAKEOVER / FEASIBILITY ASSESSMENT CHECKLIST
                    </h1>
                    <p class="text-xs text-slate-500 italic">
                        Pre-Takeover Inspection, Feasibility & Transition Control Format
                    </p>
                </div>

                <div class="flex items-center gap-2 sm:gap-3 w-full sm:w-auto">
                    <a href="{{ route('applications.show', $application) }}"
                        class="flex-1 sm:flex-initial text-center px-3.5 py-2 text-xs font-semibold text-slate-700 bg-white border border-slate-300 rounded-xl hover:bg-slate-50 transition shadow-xs">
                        &larr; Overview
                    </a>
                    <button type="button" @click="saveDraft()"
                        class="flex-1 sm:flex-initial justify-center px-4 py-2 text-xs font-bold text-white bg-indigo-600 hover:bg-indigo-500 rounded-xl shadow-sm transition flex items-center gap-2 cursor-pointer">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4" />
                        </svg>
                        <span>Save Draft</span>
                    </button>
                </div>
            </div>

            <!-- Progress Meter -->
            <div
                class="mt-3.5 sm:mt-4 flex flex-col sm:flex-row sm:items-center justify-between gap-2.5 text-xs text-slate-600">
                <div class="flex items-center flex-wrap gap-2.5 sm:gap-4">
                    <span>Answered: <strong
                            class="text-slate-900">{{ $complianceSummary['yes'] + $complianceSummary['no'] }}</strong> /
                        {{ $complianceSummary['total'] }} items</span>
                    <span class="text-slate-300 hidden sm:inline">&bull;</span>
                    <span class="text-emerald-600 font-semibold">Yes: {{ $complianceSummary['yes'] }}</span>
                    <span class="text-slate-300 hidden sm:inline">&bull;</span>
                    <span class="text-rose-600 font-semibold">No: {{ $complianceSummary['no'] }}</span>
                    <span class="text-slate-300 hidden sm:inline">&bull;</span>
                    <span class="inline-flex items-center gap-1 font-semibold text-slate-700">
                        <svg class="w-3.5 h-3.5 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" />
                        </svg>
                        Evidence Photos: <strong
                            class="text-indigo-700">{{ $evidenceSummary['photos_attached'] }}</strong>
                    </span>
                </div>
                <div class="flex items-center gap-2.5">
                    <div class="w-32 sm:w-36 h-2 bg-slate-100 rounded-full overflow-hidden border border-slate-200">
                        <div class="h-full bg-indigo-600 transition-all duration-300"
                            style="width: {{ $application->completion_percentage }}%"></div>
                    </div>
                    <span class="font-bold text-slate-800 text-[11px]">{{ $application->completion_percentage }}%</span>
                </div>
            </div>
        </div>

        <!-- Main Form -->
        <form x-ref="mainForm" method="POST" action="{{ route('applications.checklist.update', $application) }}"
            @submit="syncCurrentSection()"
            enctype="multipart/form-data" class="space-y-5 sm:space-y-6">
            @csrf
            <input type="hidden" name="current_section" :value="currentTab">
            <input type="hidden" name="action" id="formActionInput" value="save_draft">

            <!-- Mobile Section Dropdown Selector (Optimized for field inspectors on phone) -->
            <div class="sm:hidden bg-white p-3.5 rounded-2xl border border-slate-200/90 shadow-xs space-y-1.5">
                <label
                    class="flex items-center justify-between text-[11px] font-bold text-slate-500 uppercase tracking-wider">
                    <span>Jump to Section</span>
                    <span class="text-indigo-600" x-text="`Section ${currentTab} of 15`"></span>
                </label>
                <div class="relative">
                    <select x-model="currentTab"
                        class="w-full text-xs font-bold text-slate-800 rounded-xl border-slate-300 py-2.5 pl-3 pr-8 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 bg-slate-50/50 shadow-inner">
                        <option value="0">00. Primary Inspection Details</option>
                        <option value="1">01. Legal Identity & Recognition (7)</option>
                        <option value="2">02. Rooms; Dimensions & Capacity</option>
                        <option value="3">03. Physical Infrastructure (17)</option>
                        <option value="4">04. Students Enrolment Statement</option>
                        <option value="5">05. Student Population (3)</option>
                        <option value="6">06. Curriculum (12)</option>
                        <option value="7">07. Staff, HR & Liabilities (3)</option>
                        <option value="8">08. Financials – Income & Expense (7)</option>
                        <option value="9">09. Branding (6)</option>
                        <option value="10">10. Primary Quality</option>
                        <option value="11">11. Secondary Quality (8)</option>
                        <option value="12">12. Teachers (7)</option>
                        <option value="13">13. Evidence Photos ({{ $evidenceSummary['total_remarks'] }})</option>
                        <option value="14">14. Remarks</option>
                        <option value="15">15. Submit</option>
                    </select>
                </div>
            </div>

            <!-- Desktop Section Navigation Pills -->
            <div
                class="hidden sm:flex bg-white rounded-2xl border border-slate-200/90 p-2 shadow-xs items-center gap-1.5 overflow-x-auto text-xs font-semibold">
                <button type="button" @click="currentTab = '0'"
                    :class="currentTab === '0' ? 'bg-indigo-600 text-white shadow-xs' : 'text-slate-600 hover:bg-slate-100'"
                    class="px-3 py-2 rounded-xl shrink-0 transition cursor-pointer flex items-center gap-1.5">
                    <span>0. Primary Details</span>
                    @if(empty($checklist->inspection_dates))
                        <span class="w-1.5 h-1.5 rounded-full bg-amber-400 inline-block animate-pulse"></span>
                    @endif
                </button>
                <button type="button" @click="currentTab = '1'"
                    :class="currentTab === '1' ? 'bg-indigo-600 text-white shadow-xs' : 'text-slate-600 hover:bg-slate-100'"
                    class="px-3 py-2 rounded-xl shrink-0 transition cursor-pointer">
                    1. Legal (7)
                </button>
                <button type="button" @click="currentTab = '2'"
                    :class="currentTab === '2' ? 'bg-indigo-600 text-white shadow-xs' : 'text-slate-600 hover:bg-slate-100'"
                    class="px-3 py-2 rounded-xl shrink-0 transition cursor-pointer">
                    2. Rooms
                </button>
                <button type="button" @click="currentTab = '3'"
                    :class="currentTab === '3' ? 'bg-indigo-600 text-white shadow-xs' : 'text-slate-600 hover:bg-slate-100'"
                    class="px-3 py-2 rounded-xl shrink-0 transition cursor-pointer">
                    3. Infrastructure (17)
                </button>
                <button type="button" @click="currentTab = '4'"
                    :class="currentTab === '4' ? 'bg-indigo-600 text-white shadow-xs' : 'text-slate-600 hover:bg-slate-100'"
                    class="px-3 py-2 rounded-xl shrink-0 transition cursor-pointer">
                    4. Enrolment
                </button>
                <button type="button" @click="currentTab = '5'"
                    :class="currentTab === '5' ? 'bg-indigo-600 text-white shadow-xs' : 'text-slate-600 hover:bg-slate-100'"
                    class="px-3 py-2 rounded-xl shrink-0 transition cursor-pointer">
                    5. Population (3)
                </button>
                <button type="button" @click="currentTab = '6'"
                    :class="currentTab === '6' ? 'bg-indigo-600 text-white shadow-xs' : 'text-slate-600 hover:bg-slate-100'"
                    class="px-3 py-2 rounded-xl shrink-0 transition cursor-pointer">
                    6. Curriculum (12)
                </button>
                <button type="button" @click="currentTab = '7'"
                    :class="currentTab === '7' ? 'bg-indigo-600 text-white shadow-xs' : 'text-slate-600 hover:bg-slate-100'"
                    class="px-3 py-2 rounded-xl shrink-0 transition cursor-pointer">
                    7. Staff (3)
                </button>
                <button type="button" @click="currentTab = '8'"
                    :class="currentTab === '8' ? 'bg-indigo-600 text-white shadow-xs' : 'text-slate-600 hover:bg-slate-100'"
                    class="px-3 py-2 rounded-xl shrink-0 transition cursor-pointer">
                    8. Financials (7)
                </button>
                <button type="button" @click="currentTab = '9'"
                    :class="currentTab === '9' ? 'bg-indigo-600 text-white shadow-xs' : 'text-slate-600 hover:bg-slate-100'"
                    class="px-3 py-2 rounded-xl shrink-0 transition cursor-pointer">
                    9. Branding (6)
                </button>
                <button type="button" @click="currentTab = '10'"
                    :class="currentTab === '10' ? 'bg-indigo-600 text-white shadow-xs' : 'text-slate-600 hover:bg-slate-100'"
                    class="px-3 py-2 rounded-xl shrink-0 transition cursor-pointer">
                    10. Primary Quality
                </button>
                <button type="button" @click="currentTab = '11'"
                    :class="currentTab === '11' ? 'bg-indigo-600 text-white shadow-xs' : 'text-slate-600 hover:bg-slate-100'"
                    class="px-3 py-2 rounded-xl shrink-0 transition cursor-pointer">
                    11. Secondary Quality (8)
                </button>
                <button type="button" @click="currentTab = '12'"
                    :class="currentTab === '12' ? 'bg-indigo-600 text-white shadow-xs' : 'text-slate-600 hover:bg-slate-100'"
                    class="px-3 py-2 rounded-xl shrink-0 transition cursor-pointer">
                    12. Teachers (7)
                </button>
                <button type="button" @click="currentTab = '13'"
                    :class="currentTab === '13' ? 'bg-indigo-600 text-white shadow-xs' : 'text-slate-600 hover:bg-slate-100'"
                    class="px-3 py-2 rounded-xl shrink-0 transition cursor-pointer">
                    13. Evidence Photos
                </button>
                <button type="button" @click="currentTab = '14'"
                    :class="currentTab === '14' ? 'bg-indigo-600 text-white shadow-xs' : 'text-slate-600 hover:bg-slate-100'"
                    class="px-3 py-2 rounded-xl shrink-0 transition cursor-pointer">
                    14. Remarks
                </button>
                <button type="button" @click="currentTab = '15'"
                    :class="currentTab === '15' ? 'bg-indigo-600 text-white shadow-xs' : 'text-slate-600 hover:bg-slate-100'"
                    class="px-3 py-2 rounded-xl shrink-0 transition cursor-pointer">
                    15. Submit
                </button>
            </div>

            <!-- SECTION 0: PRIMARY DETAILS & INSPECTION CONTEXT -->
            <div x-show="currentTab === '0'" x-cloak
                class="bg-white rounded-2xl border border-slate-200/90 shadow-sm p-4 sm:p-6 space-y-4 sm:space-y-5">
                <div
                    class="border-b border-slate-100 pb-3 flex flex-col sm:flex-row sm:items-center justify-between gap-2">
                    <div>
                        <div class="flex items-center gap-2">
                            <span
                                class="w-6 h-6 rounded-full bg-indigo-600 text-white font-black text-xs flex items-center justify-center">0</span>
                            <h2 class="text-xs sm:text-sm font-bold text-slate-900 uppercase tracking-wide">
                                0. Primary Details &amp; Inspection Context
                            </h2>
                        </div>
                        <p class="text-[11px] sm:text-xs text-slate-500 mt-1">
                            Preliminary inspection dossier: official institution identity, campus location, governing
                            management, audit schedule, and evaluation team.
                        </p>
                    </div>
                    <span
                        class="self-start sm:self-auto text-xs font-semibold px-2.5 py-1 rounded-md bg-amber-50 text-amber-800 border border-amber-200">
                        Compulsory Inspection Context
                    </span>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3 sm:gap-4 text-xs">
                    <div>
                        <label class="block font-semibold text-slate-700 mb-1">
                            Proposed Institution / School <span class="text-rose-500">*</span>
                        </label>
                        <input type="text" name="institution_name"
                            value="{{ old('institution_name', $checklist->institution_name ?? $school->name) }}"
                            required
                            class="w-full text-xs rounded-xl border-slate-300 focus:border-indigo-500 focus:ring-indigo-500 py-2 px-3">
                    </div>

                    <div>
                        <label class="block font-semibold text-slate-700 mb-1">
                            Location <span class="text-rose-500">*</span>
                        </label>
                        <input type="text" name="location"
                            value="{{ old('location', $checklist->location ?? ($school->city . ', ' . $school->state)) }}"
                            required
                            class="w-full text-xs rounded-xl border-slate-300 focus:border-indigo-500 focus:ring-indigo-500 py-2 px-3">
                    </div>

                    <div>
                        <label class="block font-semibold text-slate-700 mb-1">
                            Present Owner / Management <span class="text-rose-500">*</span>
                        </label>
                        <input type="text" name="present_management"
                            value="{{ old('present_management', $checklist->present_management ?? $school->management_organization) }}"
                            required
                            class="w-full text-xs rounded-xl border-slate-300 focus:border-indigo-500 focus:ring-indigo-500 py-2 px-3">
                    </div>

                    <div>
                        <label class="block font-semibold text-slate-700 mb-1">
                            Date(s) of Feasibility Inspection <span class="text-rose-500">*</span>
                        </label>
                        <input type="text" name="inspection_dates" placeholder="e.g. 04–05 September 2026"
                            value="{{ old('inspection_dates', $checklist->inspection_dates) }}" required
                            class="w-full text-xs rounded-xl border-slate-300 focus:border-indigo-500 focus:ring-indigo-500 py-2 px-3">
                    </div>

                    <div class="sm:col-span-2">
                        <label class="block font-semibold text-slate-700 mb-1">
                            Team Members of Inspection <span class="text-rose-500">*</span>
                        </label>
                        <input type="text" name="inspection_team"
                            placeholder="Lead Inspector name &amp; accompanying evaluation team"
                            value="{{ old('inspection_team', $checklist->inspection_team ?? ($application->assignedOfficer?->name ?? auth()->user()->name)) }}"
                            required
                            class="w-full text-xs rounded-xl border-slate-300 focus:border-indigo-500 focus:ring-indigo-500 py-2 px-3">
                    </div>
                </div>

                <div
                    class="px-4 sm:px-6 py-3.5 bg-slate-50/90 rounded-xl border border-slate-100 flex items-center justify-between gap-3 mt-4">
                    <button type="submit" @click="document.getElementById('formActionInput').value = 'save_draft'"
                        class="inline-flex items-center gap-1.5 px-3.5 py-2 text-xs font-semibold text-slate-700 bg-white border border-slate-300 rounded-xl hover:bg-slate-50 transition shadow-xs">
                        <svg class="w-3.5 h-3.5 text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4" />
                        </svg>
                        <span>Save Draft</span>
                    </button>
                    <button type="button" @click="saveAndGoNext()"
                        class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl bg-indigo-600 text-white hover:bg-indigo-500 font-bold text-xs transition shadow-xs cursor-pointer">
                        <span>Save &amp; Next: 01. Legal Identity &amp; Statutory Recognition</span>
                        <span>&rarr;</span>
                    </button>
                </div>
            </div>

            <!-- SECTION 1: LEGAL IDENTITY -->
            <div x-show="currentTab === '1'" x-cloak
                class="bg-white rounded-2xl border border-slate-200/90 shadow-sm overflow-hidden">
                <div
                    class="px-4 sm:px-6 py-3.5 sm:py-4 border-b border-slate-100 bg-slate-50/70 flex flex-col sm:flex-row sm:items-center justify-between gap-2">
                    <div>
                        <h2 class="text-sm font-bold text-slate-900 uppercase tracking-wide">1. Legal Identity,
                            Ownership & Statutory Recognition</h2>
                        <p class="text-xs text-slate-500">Verify original registration, land deeds, affiliation
                            certificates, and UDISE codes.</p>
                    </div>
                    <span
                        class="self-start sm:self-auto text-xs font-semibold px-2.5 py-1 rounded-md bg-indigo-50 text-indigo-700">7
                        Points</span>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs border-collapse min-w-[680px]">
                        <thead class="bg-slate-900 text-white uppercase font-bold tracking-wider">
                            <tr>
                                <th class="w-12 px-4 py-3 text-center border-r border-slate-800">No.</th>
                                <th class="w-2/5 max-w-sm px-4 py-3 border-r border-slate-800">Verification / Point to
                                    Check</th>
                                <th class="w-16 px-3 py-3 text-center border-r border-slate-800">Yes</th>
                                <th class="w-16 px-3 py-3 text-center border-r border-slate-800">No</th>
                                <th class="w-2/5 min-w-[280px] px-4 py-3">Remarks / Action Required</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-200">
                            @foreach ($checklist->section1_legal ?? [] as $index => $item)
                                <tr class="hover:bg-slate-50/80 transition">
                                    <td
                                        class="px-4 py-3 text-center font-bold text-slate-700 bg-slate-50 border-r border-slate-200">
                                        {{ $item['no'] }}
                                        <input type="hidden" name="section1_legal[{{ $index }}][no]"
                                            value="{{ $item['no'] }}">
                                        <input type="hidden" name="section1_legal[{{ $index }}][point]"
                                            value="{{ $item['point'] }}">
                                    </td>
                                    <td
                                        class="px-4 py-3 text-slate-800 font-medium leading-relaxed border-r border-slate-200">
                                        {{ $item['point'] }}
                                    </td>
                                    <td class="p-1 text-center border-r border-slate-200">
                                        <label
                                            class="flex items-center justify-center w-full min-h-[38px] cursor-pointer hover:bg-emerald-50 rounded transition">
                                            <input type="radio" name="section1_legal[{{ $index }}][compliance]" value="yes"
                                                {{ ($item['compliance'] ?? '') === 'yes' ? 'checked' : '' }}
                                                class="compliance-radio w-4.5 h-4.5 text-emerald-600 focus:ring-2 focus:ring-emerald-500 focus:ring-offset-1 cursor-pointer">
                                        </label>
                                    </td>
                                    <td class="p-1 text-center border-r border-slate-200">
                                        <label
                                            class="flex items-center justify-center w-full min-h-[38px] cursor-pointer hover:bg-rose-50 rounded transition">
                                            <input type="radio" name="section1_legal[{{ $index }}][compliance]" value="no"
                                                {{ ($item['compliance'] ?? '') === 'no' ? 'checked' : '' }}
                                                class="compliance-radio w-4.5 h-4.5 text-rose-600 focus:ring-2 focus:ring-rose-500 focus:ring-offset-1 cursor-pointer">
                                        </label>
                                    </td>
                                    <td class="p-1.5">
                                        <input type="text" name="section1_legal[{{ $index }}][remarks]"
                                            value="{{ old("section1_legal.{$index}.remarks", $item['remarks'] ?? '') }}"
                                            placeholder="Remarks"
                                            class="w-full text-xs rounded-lg border-slate-200 focus:border-indigo-500 focus:ring-indigo-500 bg-white py-1.5 px-2">
                                        <input type="hidden" name="section1_legal[{{ $index }}][evidence_photo]"
                                            value="{{ $item['evidence_photo'] ?? '' }}">
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div
                    class="px-4 sm:px-6 py-3.5 bg-slate-50/90 border-t border-slate-100 flex items-center justify-between gap-3">
                    <button type="button" @click="saveAndGoPrev()"
                        class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl text-slate-700 bg-white border border-slate-300 hover:bg-slate-50 font-semibold text-xs transition shadow-xs cursor-pointer">
                        <span>&larr; Save & Prev: 0. Primary Details</span>
                    </button>
                    <div class="flex items-center gap-2">
                        <button type="submit" @click="document.getElementById('formActionInput').value = 'save_draft'"
                            class="hidden sm:inline-flex items-center gap-1.5 px-3 py-2 text-xs font-semibold text-slate-700 bg-white border border-slate-300 rounded-xl hover:bg-slate-50 transition shadow-xs">
                            Save Draft
                        </button>
                        <button type="button" @click="saveAndGoNext()"
                            class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl bg-indigo-600 text-white hover:bg-indigo-500 font-bold text-xs transition shadow-xs cursor-pointer">
                            <span>Save & Next: 02. Rooms; Dimensions & Capacity</span>
                            <span>&rarr;</span>
                        </button>
                    </div>
                </div>
            </div>

            <!-- SECTION 2: ROOMS; DIMENSIONS & CAPACITY (Dynamic Table) -->
            <div x-show="currentTab === '2'" x-cloak
                class="bg-white rounded-2xl border border-slate-200/90 shadow-sm p-4 sm:p-6 space-y-4">
                <div
                    class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 border-b border-slate-100 pb-3 sm:pb-4">
                    <div>
                        <h2 class="text-sm font-bold text-slate-900 uppercase tracking-wide">2. ROOMS; DIMENSIONS &
                            CAPACITY</h2>
                        <p class="text-xs text-slate-500">Record classroom dimensions, office space, specialised rooms,
                            current utilisation, and furniture status.</p>
                    </div>
                    <button type="button" @click="addRoomRow()"
                        class="inline-flex items-center justify-center gap-1.5 px-3.5 py-2 text-xs font-bold text-indigo-700 bg-indigo-50 hover:bg-indigo-100 border border-indigo-200 rounded-xl transition cursor-pointer self-start sm:self-auto">
                        + Add Room Row
                    </button>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs border-collapse min-w-[780px]">
                        <thead class="bg-slate-900 text-white uppercase font-bold tracking-wider">
                            <tr>
                                <th class="px-3 py-3">TYPE (classroom/office/hall)</th>
                                <th class="w-24 px-3 py-3 text-center">Number of Rooms</th>
                                <th class="w-28 px-3 py-3">SIZE In feet</th>
                                <th class="px-3 py-3">CURRENT USE</th>
                                <th class="px-3 py-3">FURNITURE</th>
                                <th class="w-2/5 min-w-[260px] px-3 py-3">Remarks (ventilation, lighting)</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-200">
                            <template x-for="(room, index) in rooms" :key="index">
                                <tr class="hover:bg-slate-50 transition">
                                    <td class="p-2">
                                        <input type="text" :name="`section3_rooms[${index}][type]`" x-model="room.type"
                                            placeholder="e.g. Standard Classroom"
                                            class="w-full text-xs rounded-lg border-slate-300 focus:border-indigo-500 py-1.5">
                                    </td>
                                    <td class="p-2">
                                        <input type="number" :name="`section3_rooms[${index}][count]`"
                                            x-model="room.count" placeholder="Qty"
                                            class="w-full text-xs text-center rounded-lg border-slate-300 focus:border-indigo-500 py-1.5">
                                    </td>
                                    <td class="p-2">
                                        <input type="text" :name="`section3_rooms[${index}][size_feet]`"
                                            x-model="room.size_feet" placeholder="e.g. 20 x 25 ft"
                                            class="w-full text-xs rounded-lg border-slate-300 focus:border-indigo-500 py-1.5">
                                    </td>
                                    <td class="p-2">
                                        <input type="text" :name="`section3_rooms[${index}][current_use]`"
                                            x-model="room.current_use" placeholder="e.g. Grade 5 Class"
                                            class="w-full text-xs rounded-lg border-slate-300 focus:border-indigo-500 py-1.5">
                                    </td>
                                    <td class="p-2">
                                        <input type="text" :name="`section3_rooms[${index}][furniture]`"
                                            x-model="room.furniture" placeholder="e.g. 20 dual desks, board"
                                            class="w-full text-xs rounded-lg border-slate-300 focus:border-indigo-500 py-1.5">
                                    </td>
                                    <td class="p-2">
                                        <input type="text" :name="`section3_rooms[${index}][remarks]`"
                                            x-model="room.remarks" placeholder="Remarks"
                                            class="w-full text-xs rounded-lg border-slate-300 focus:border-indigo-500 py-1.5 px-2.5">
                                        <input type="hidden" :name="`section3_rooms[${index}][evidence_photo]`"
                                            :value="room.evidence_photo || ''">
                                    </td>
                                </tr>
                            </template>
                        </tbody>
                    </table>
                </div>

                <div class="pt-3 border-t border-slate-100 flex items-center justify-between gap-3">
                    <button type="button" @click="saveAndGoPrev()"
                        class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl text-slate-700 bg-white border border-slate-300 hover:bg-slate-50 font-semibold text-xs transition shadow-xs cursor-pointer">
                        <span>&larr; Save & Prev: 01. Legal</span>
                    </button>
                    <div class="flex items-center gap-2">
                        <button type="submit" @click="document.getElementById('formActionInput').value = 'save_draft'"
                            class="hidden sm:inline-flex items-center gap-1.5 px-3 py-2 text-xs font-semibold text-slate-700 bg-white border border-slate-300 rounded-xl hover:bg-slate-50 transition shadow-xs">
                            Save Draft
                        </button>
                        <button type="button" @click="saveAndGoNext()"
                            class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl bg-indigo-600 text-white hover:bg-indigo-500 font-bold text-xs transition shadow-xs cursor-pointer">
                            <span>Save & Next: 03. Infrastructure (17)</span>
                            <span>&rarr;</span>
                        </button>
                    </div>
                </div>
            </div>

            <!-- SECTION 3: PHYSICAL INFRASTRUCTURE -->
            <div x-show="currentTab === '3'" x-cloak
                class="bg-white rounded-2xl border border-slate-200/90 shadow-sm overflow-hidden">
                <div
                    class="px-4 sm:px-6 py-3.5 sm:py-4 border-b border-slate-100 bg-slate-50/70 flex flex-col sm:flex-row sm:items-center justify-between gap-2">
                    <div>
                        <h2 class="text-sm font-bold text-slate-900 uppercase tracking-wide">3. Physical Infrastructure
                            & Campus Condition</h2>
                        <p class="text-xs text-slate-500">Inspect campus buildings, labs, digital classrooms, utilities,
                            washrooms, transport, security.</p>
                    </div>
                    <span
                        class="self-start sm:self-auto text-xs font-semibold px-2.5 py-1 rounded-md bg-indigo-50 text-indigo-700">17
                        Points</span>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs border-collapse min-w-[680px]">
                        <thead class="bg-slate-900 text-white uppercase font-bold tracking-wider">
                            <tr>
                                <th class="w-12 px-4 py-3 text-center border-r border-slate-800">No.</th>
                                <th class="w-2/5 max-w-sm px-4 py-3 border-r border-slate-800">Verification / Point to
                                    Check</th>
                                <th class="w-16 px-3 py-3 text-center border-r border-slate-800">Yes</th>
                                <th class="w-16 px-3 py-3 text-center border-r border-slate-800">No</th>
                                <th class="w-2/5 min-w-[280px] px-4 py-3">Remarks / Action Required</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-200">
                            @foreach ($checklist->section2_infrastructure ?? [] as $index => $item)
                                <tr class="hover:bg-slate-50/80 transition">
                                    <td
                                        class="px-4 py-3 text-center font-bold text-slate-700 bg-slate-50 border-r border-slate-200">
                                        {{ $item['no'] }}
                                        <input type="hidden" name="section2_infrastructure[{{ $index }}][no]"
                                            value="{{ $item['no'] }}">
                                        <input type="hidden" name="section2_infrastructure[{{ $index }}][point]"
                                            value="{{ $item['point'] }}">
                                    </td>
                                    <td
                                        class="px-4 py-3 text-slate-800 font-medium leading-relaxed border-r border-slate-200">
                                        {{ $item['point'] }}
                                    </td>
                                    <td class="p-1 text-center border-r border-slate-200">
                                        <label
                                            class="flex items-center justify-center w-full min-h-[38px] cursor-pointer hover:bg-emerald-50 rounded transition">
                                            <input type="radio" name="section2_infrastructure[{{ $index }}][compliance]"
                                                value="yes" {{ ($item['compliance'] ?? '') === 'yes' ? 'checked' : '' }}
                                                class="compliance-radio w-4.5 h-4.5 text-emerald-600 focus:ring-2 focus:ring-emerald-500 focus:ring-offset-1 cursor-pointer">
                                        </label>
                                    </td>
                                    <td class="p-1 text-center border-r border-slate-200">
                                        <label
                                            class="flex items-center justify-center w-full min-h-[38px] cursor-pointer hover:bg-rose-50 rounded transition">
                                            <input type="radio" name="section2_infrastructure[{{ $index }}][compliance]"
                                                value="no" {{ ($item['compliance'] ?? '') === 'no' ? 'checked' : '' }}
                                                class="compliance-radio w-4.5 h-4.5 text-rose-600 focus:ring-2 focus:ring-rose-500 focus:ring-offset-1 cursor-pointer">
                                        </label>
                                    </td>
                                    <td class="p-1.5">
                                        <input type="text" name="section2_infrastructure[{{ $index }}][remarks]"
                                            value="{{ old("section2_infrastructure.{$index}.remarks", $item['remarks'] ?? '') }}"
                                            placeholder="Remarks"
                                            class="w-full text-xs rounded-lg border-slate-200 focus:border-indigo-500 focus:ring-indigo-500 bg-white py-1.5 px-2">
                                        <input type="hidden" name="section2_infrastructure[{{ $index }}][evidence_photo]"
                                            value="{{ $item['evidence_photo'] ?? '' }}">
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div
                    class="px-4 sm:px-6 py-3.5 bg-slate-50/90 border-t border-slate-100 flex items-center justify-between gap-3">
                    <button type="button" @click="saveAndGoPrev()"
                        class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl text-slate-700 bg-white border border-slate-300 hover:bg-slate-50 font-semibold text-xs transition shadow-xs cursor-pointer">
                        <span>&larr; Save & Prev: 02. ROOMS; DIMENSIONS & CAPACITY</span>
                    </button>
                    <div class="flex items-center gap-2">
                        <button type="submit" @click="document.getElementById('formActionInput').value = 'save_draft'"
                            class="hidden sm:inline-flex items-center gap-1.5 px-3 py-2 text-xs font-semibold text-slate-700 bg-white border border-slate-300 rounded-xl hover:bg-slate-50 transition shadow-xs">
                            Save Draft
                        </button>
                        <button type="button" @click="saveAndGoNext()"
                            class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl bg-indigo-600 text-white hover:bg-indigo-500 font-bold text-xs transition shadow-xs cursor-pointer">
                            <span>Save & Next: 04. Enrolment</span>
                            <span>&rarr;</span>
                        </button>
                    </div>
                </div>
            </div>

            <!-- SECTION 4: STUDENTS ENROLMENT STATEMENT (Dynamic Table) -->
            <div x-show="currentTab === '4'" x-cloak
                class="bg-white rounded-2xl border border-slate-200/90 shadow-sm p-4 sm:p-6 space-y-4">
                <div
                    class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 border-b border-slate-100 pb-3 sm:pb-4">
                    <div>
                        <h2 class="text-sm font-bold text-slate-900 uppercase tracking-wide">4. Students Enrolment
                            Statement</h2>
                        <p class="text-xs text-slate-500">Record current student strength breakdown by class and
                            division with gender distribution and previous year count.</p>
                    </div>
                    <button type="button" @click="addEnrolmentRow()"
                        class="inline-flex items-center justify-center gap-1.5 px-3.5 py-2 text-xs font-bold text-indigo-700 bg-indigo-50 hover:bg-indigo-100 border border-indigo-200 rounded-xl transition cursor-pointer self-start sm:self-auto">
                        + Add Class / Division
                    </button>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs border-collapse min-w-[720px]">
                        <thead class="bg-slate-900 text-white uppercase font-bold tracking-wider">
                            <tr>
                                <th class="px-4 py-3">CLASS & DIVISION</th>
                                <th class="w-20 sm:w-24 px-2 sm:px-3 py-3 text-center">BOYS</th>
                                <th class="w-20 sm:w-24 px-2 sm:px-3 py-3 text-center">GIRLS</th>
                                <th class="w-20 sm:w-24 px-2 sm:px-3 py-3 text-center bg-slate-800">TOTAL</th>
                                <th class="w-28 sm:w-32 px-2 sm:px-3 py-3 text-center">PREV YEAR TOTAL</th>
                                <th class="w-28 sm:w-32 px-2 sm:px-3 py-3 text-center bg-slate-800">TREND / STATUS</th>
                                <th class="w-12 px-2 py-3 text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-200">
                            <template x-for="(enrol, index) in enrolments" :key="index">
                                <tr class="hover:bg-slate-50 transition">
                                    <td class="p-2 font-medium text-slate-800">
                                        <input type="text" :name="`section5_enrolment[${index}][class_division]`"
                                            x-model="enrol.class_division" placeholder="e.g. Class 1-A"
                                            class="w-full text-xs rounded-lg border-slate-300 focus:border-indigo-500 py-1.5">
                                    </td>
                                    <td class="p-2">
                                        <input type="number" :name="`section5_enrolment[${index}][boys]`"
                                            x-model.number="enrol.boys" @input="calcEnrolmentTotal(enrol)"
                                            placeholder="0"
                                            class="w-full text-xs text-center rounded-lg border-slate-300 focus:border-indigo-500 py-1.5">
                                    </td>
                                    <td class="p-2">
                                        <input type="number" :name="`section5_enrolment[${index}][girls]`"
                                            x-model.number="enrol.girls" @input="calcEnrolmentTotal(enrol)"
                                            placeholder="0"
                                            class="w-full text-xs text-center rounded-lg border-slate-300 focus:border-indigo-500 py-1.5">
                                    </td>
                                    <td class="p-2 bg-slate-50">
                                        <input type="text" :name="`section5_enrolment[${index}][total]`"
                                            x-model="enrol.total" readonly
                                            class="w-full text-xs text-center font-bold text-indigo-700 bg-indigo-50/50 rounded-lg border-indigo-200 py-1.5">
                                    </td>
                                    <td class="p-2">
                                        <input type="number" :name="`section5_enrolment[${index}][last_year_total]`"
                                            x-model.number="enrol.last_year_total" @input="calcEnrolmentTotal(enrol)"
                                            placeholder="0"
                                            class="w-full text-xs text-center rounded-lg border-slate-300 focus:border-indigo-500 py-1.5">
                                    </td>
                                    <td class="p-2 text-center bg-slate-50/60">
                                        <input type="hidden" :name="`section5_enrolment[${index}][trend]`"
                                            :value="calcEnrolmentTrend(enrol)">
                                        <span
                                            class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold tracking-wide"
                                            :class="{
                                                'bg-emerald-50 text-emerald-700 border border-emerald-200': calcEnrolmentTrend(enrol) === 'Increased',
                                                'bg-rose-50 text-rose-700 border border-rose-200': calcEnrolmentTrend(enrol) === 'Decreased',
                                                'bg-slate-100 text-slate-600 border border-slate-200': calcEnrolmentTrend(enrol) === 'Stayed same'
                                            }" x-text="calcEnrolmentTrend(enrol)">
                                        </span>
                                    </td>
                                    <td class="p-2 text-center">
                                        <button type="button" @click="removeEnrolmentRow(index)"
                                            class="text-rose-500 hover:text-rose-700 p-1.5 rounded-lg hover:bg-rose-50 cursor-pointer"
                                            title="Delete Row">
                                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                        </button>
                                    </td>
                                </tr>
                            </template>
                        </tbody>
                        <tfoot class="bg-slate-100 font-bold border-t-2 border-slate-300">
                            <tr>
                                <td class="px-4 py-2.5 text-slate-800">CUMULATIVE TOTALS</td>
                                <td class="px-3 py-2.5 text-center text-slate-800" x-text="totalBoys()"></td>
                                <td class="px-3 py-2.5 text-center text-slate-800" x-text="totalGirls()"></td>
                                <td class="px-3 py-2.5 text-center text-indigo-700 bg-indigo-100"
                                    x-text="grandTotalStudents()"></td>
                                <td class="px-3 py-2.5 text-center text-slate-800" x-text="totalLastYear()"></td>
                                <td class="px-3 py-2.5 text-center text-slate-800" x-text="calcOverallTrend()"></td>
                                <td></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>

                <div class="pt-3 border-t border-slate-100 flex items-center justify-between gap-3">
                    <button type="button" @click="saveAndGoPrev()"
                        class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl text-slate-700 bg-white border border-slate-300 hover:bg-slate-50 font-semibold text-xs transition shadow-xs cursor-pointer">
                        <span>&larr; Save & Prev: 03. Infrastructure (17)</span>
                    </button>
                    <div class="flex items-center gap-2">
                        <button type="submit" @click="document.getElementById('formActionInput').value = 'save_draft'"
                            class="hidden sm:inline-flex items-center gap-1.5 px-3 py-2 text-xs font-semibold text-slate-700 bg-white border border-slate-300 rounded-xl hover:bg-slate-50 transition shadow-xs">
                            Save Draft
                        </button>
                        <button type="button" @click="saveAndGoNext()"
                            class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl bg-indigo-600 text-white hover:bg-indigo-500 font-bold text-xs transition shadow-xs cursor-pointer">
                            <span>Save & Next: 05. Population</span>
                            <span>&rarr;</span>
                        </button>
                    </div>
                </div>
            </div>

            <!-- SECTION 5: STUDENT POPULATION & ADMISSIONS -->
            <div x-show="currentTab === '5'" x-cloak
                class="bg-white rounded-2xl border border-slate-200/90 shadow-sm overflow-hidden">
                <div
                    class="px-4 sm:px-6 py-3.5 sm:py-4 border-b border-slate-100 bg-slate-50/70 flex flex-col sm:flex-row sm:items-center justify-between gap-2">
                    <div>
                        <h2 class="text-sm font-bold text-slate-900 uppercase tracking-wide">5. Student Population &
                            Admissions</h2>
                        <p class="text-xs text-slate-500">Verify class rolls, admission registers, and
                            teacher-to-student ratios.</p>
                    </div>
                    <span
                        class="self-start sm:self-auto text-xs font-semibold px-2.5 py-1 rounded-md bg-indigo-50 text-indigo-700">3
                        Points</span>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs border-collapse min-w-[680px]">
                        <thead class="bg-slate-900 text-white uppercase font-bold tracking-wider">
                            <tr>
                                <th class="w-12 px-4 py-3 text-center border-r border-slate-800">No.</th>
                                <th class="w-2/5 max-w-sm px-4 py-3 border-r border-slate-800">Verification / Point to
                                    Check</th>
                                <th class="w-16 px-3 py-3 text-center border-r border-slate-800">Yes</th>
                                <th class="w-16 px-3 py-3 text-center border-r border-slate-800">No</th>
                                <th class="w-2/5 min-w-[280px] px-4 py-3">Remarks / Action Required</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-200">
                            @foreach ($checklist->section4_students ?? [] as $index => $item)
                                <tr class="hover:bg-slate-50/80 transition">
                                    <td
                                        class="px-4 py-3 text-center font-bold text-slate-700 bg-slate-50 border-r border-slate-200">
                                        {{ $item['no'] }}
                                        <input type="hidden" name="section4_students[{{ $index }}][no]"
                                            value="{{ $item['no'] }}">
                                        <input type="hidden" name="section4_students[{{ $index }}][point]"
                                            value="{{ $item['point'] }}">
                                    </td>
                                    <td
                                        class="px-4 py-3 text-slate-800 font-medium leading-relaxed border-r border-slate-200">
                                        {{ $item['point'] }}
                                    </td>
                                    <td class="p-1 text-center border-r border-slate-200">
                                        <label
                                            class="flex items-center justify-center w-full min-h-[38px] cursor-pointer hover:bg-emerald-50 rounded transition">
                                            <input type="radio" name="section4_students[{{ $index }}][compliance]"
                                                value="yes" {{ ($item['compliance'] ?? '') === 'yes' ? 'checked' : '' }}
                                                class="compliance-radio w-4.5 h-4.5 text-emerald-600 focus:ring-2 focus:ring-emerald-500 focus:ring-offset-1 cursor-pointer">
                                        </label>
                                    </td>
                                    <td class="p-1 text-center border-r border-slate-200">
                                        <label
                                            class="flex items-center justify-center w-full min-h-[38px] cursor-pointer hover:bg-rose-50 rounded transition">
                                            <input type="radio" name="section4_students[{{ $index }}][compliance]"
                                                value="no" {{ ($item['compliance'] ?? '') === 'no' ? 'checked' : '' }}
                                                class="compliance-radio w-4.5 h-4.5 text-rose-600 focus:ring-2 focus:ring-rose-500 focus:ring-offset-1 cursor-pointer">
                                        </label>
                                    </td>
                                    <td class="p-1.5">
                                        <input type="text" name="section4_students[{{ $index }}][remarks]"
                                            value="{{ old("section4_students.{$index}.remarks", $item['remarks'] ?? '') }}"
                                            placeholder="Remarks"
                                            class="w-full text-xs rounded-lg border-slate-200 focus:border-indigo-500 focus:ring-indigo-500 bg-white py-1.5 px-2">
                                        <input type="hidden" name="section4_students[{{ $index }}][evidence_photo]"
                                            value="{{ $item['evidence_photo'] ?? '' }}">
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div
                    class="px-4 sm:px-6 py-3.5 bg-slate-50/90 border-t border-slate-100 flex items-center justify-between gap-3">
                    <button type="button" @click="saveAndGoPrev()"
                        class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl text-slate-700 bg-white border border-slate-300 hover:bg-slate-50 font-semibold text-xs transition shadow-xs cursor-pointer">
                        <span>&larr; Save & Prev: 04. Enrolment</span>
                    </button>
                    <div class="flex items-center gap-2">
                        <button type="submit" @click="document.getElementById('formActionInput').value = 'save_draft'"
                            class="hidden sm:inline-flex items-center gap-1.5 px-3 py-2 text-xs font-semibold text-slate-700 bg-white border border-slate-300 rounded-xl hover:bg-slate-50 transition shadow-xs">
                            Save Draft
                        </button>
                        <button type="button" @click="saveAndGoNext()"
                            class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl bg-indigo-600 text-white hover:bg-indigo-500 font-bold text-xs transition shadow-xs cursor-pointer">
                            <span>Save & Next: 06. Curriculum</span>
                            <span>&rarr;</span>
                        </button>
                    </div>
                </div>
            </div>

            <!-- SECTION 6: CURRICULUM -->
            <div x-show="currentTab === '6'" x-cloak
                class="bg-white rounded-2xl border border-slate-200/90 shadow-sm overflow-hidden">
                <div
                    class="px-4 sm:px-6 py-3.5 sm:py-4 border-b border-slate-100 bg-slate-50/70 flex flex-col sm:flex-row sm:items-center justify-between gap-2">
                    <div>
                        <h2 class="text-xs sm:text-sm font-bold text-slate-900 uppercase tracking-wide">6. Curriculum
                        </h2>
                        <p class="text-[11px] sm:text-xs text-slate-500">Lesson planning, examination systems, remedial
                            classes, board exam trends, co-curriculars.</p>
                    </div>
                    <span
                        class="text-[11px] sm:text-xs font-semibold px-2.5 py-1 rounded-md bg-indigo-50 text-indigo-700 self-start sm:self-auto">12
                        Verification Points</span>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs border-collapse min-w-[680px]">
                        <thead class="bg-slate-900 text-white uppercase font-bold tracking-wider">
                            <tr>
                                <th class="w-12 px-4 py-3 text-center border-r border-slate-800">No.</th>
                                <th class="w-2/5 max-w-sm px-4 py-3 border-r border-slate-800">Verification / Point to
                                    Check</th>
                                <th class="w-16 px-3 py-3 text-center border-r border-slate-800">Yes</th>
                                <th class="w-16 px-3 py-3 text-center border-r border-slate-800">No</th>
                                <th class="w-2/5 min-w-[280px] px-4 py-3">Remarks / Action Required</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-200">
                            @foreach ($checklist->section6_academic ?? [] as $index => $item)
                                <tr class="hover:bg-slate-50/80 transition">
                                    <td
                                        class="px-4 py-3 text-center font-bold text-slate-700 bg-slate-50 border-r border-slate-200">
                                        {{ $item['no'] }}
                                        <input type="hidden" name="section6_academic[{{ $index }}][no]"
                                            value="{{ $item['no'] }}">
                                        <input type="hidden" name="section6_academic[{{ $index }}][point]"
                                            value="{{ $item['point'] }}">
                                    </td>
                                    <td
                                        class="px-4 py-3 text-slate-800 font-medium leading-relaxed border-r border-slate-200">
                                        {{ $item['point'] }}
                                    </td>
                                    <td class="p-1 text-center border-r border-slate-200">
                                        <label
                                            class="flex items-center justify-center w-full min-h-[38px] cursor-pointer hover:bg-emerald-50 rounded transition">
                                            <input type="radio" name="section6_academic[{{ $index }}][compliance]"
                                                value="yes" {{ ($item['compliance'] ?? '') === 'yes' ? 'checked' : '' }}
                                                class="compliance-radio w-4.5 h-4.5 text-emerald-600 focus:ring-2 focus:ring-emerald-500 focus:ring-offset-1 cursor-pointer">
                                        </label>
                                    </td>
                                    <td class="p-1 text-center border-r border-slate-200">
                                        <label
                                            class="flex items-center justify-center w-full min-h-[38px] cursor-pointer hover:bg-rose-50 rounded transition">
                                            <input type="radio" name="section6_academic[{{ $index }}][compliance]"
                                                value="no" {{ ($item['compliance'] ?? '') === 'no' ? 'checked' : '' }}
                                                class="compliance-radio w-4.5 h-4.5 text-rose-600 focus:ring-2 focus:ring-rose-500 focus:ring-offset-1 cursor-pointer">
                                        </label>
                                    </td>
                                    <td class="p-1.5">
                                        <input type="text" name="section6_academic[{{ $index }}][remarks]"
                                            value="{{ old("section6_academic.{$index}.remarks", $item['remarks'] ?? '') }}"
                                            placeholder="Remarks"
                                            class="w-full text-xs rounded-lg border-slate-200 focus:border-indigo-500 focus:ring-indigo-500 bg-white py-1.5 px-2">
                                        <input type="hidden" name="section6_academic[{{ $index }}][evidence_photo]"
                                            value="{{ $item['evidence_photo'] ?? '' }}">
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div
                    class="px-4 sm:px-6 py-3.5 bg-slate-50/90 border-t border-slate-100 flex items-center justify-between gap-3">
                    <button type="button" @click="saveAndGoPrev()"
                        class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl text-slate-700 bg-white border border-slate-300 hover:bg-slate-50 font-semibold text-xs transition shadow-xs cursor-pointer">
                        <span>&larr; Save & Prev: 05. Population</span>
                    </button>
                    <div class="flex items-center gap-2">
                        <button type="submit" @click="document.getElementById('formActionInput').value = 'save_draft'"
                            class="hidden sm:inline-flex items-center gap-1.5 px-3 py-2 text-xs font-semibold text-slate-700 bg-white border border-slate-300 rounded-xl hover:bg-slate-50 transition shadow-xs">
                            Save Draft
                        </button>
                        <button type="button" @click="saveAndGoNext()"
                            class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl bg-indigo-600 text-white hover:bg-indigo-500 font-bold text-xs transition shadow-xs cursor-pointer">
                            <span>Save & Next: 07. Staff & HR</span>
                            <span>&rarr;</span>
                        </button>
                    </div>
                </div>
            </div>

            <!-- SECTION 7: STAFF, HR & EMPLOYMENT LIABILITIES -->
            <div x-show="currentTab === '7'" x-cloak
                class="bg-white rounded-2xl border border-slate-200/90 shadow-sm overflow-hidden">
                <div
                    class="px-4 sm:px-6 py-3.5 sm:py-4 border-b border-slate-100 bg-slate-50/70 flex flex-col sm:flex-row sm:items-center justify-between gap-2">
                    <div>
                        <h2 class="text-xs sm:text-sm font-bold text-slate-900 uppercase tracking-wide">7. Staff, HR &
                            Employment Liabilities</h2>
                        <p class="text-[11px] sm:text-xs text-slate-500">Staff muster roll, salaries, qualifications,
                            aided vs unaided status, attendance, agreements.</p>
                    </div>
                    <span
                        class="text-[11px] sm:text-xs font-semibold px-2.5 py-1 rounded-md bg-indigo-50 text-indigo-700 self-start sm:self-auto">3
                        Verification Points</span>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs border-collapse min-w-[680px]">
                        <thead class="bg-slate-900 text-white uppercase font-bold tracking-wider">
                            <tr>
                                <th class="w-12 px-4 py-3 text-center border-r border-slate-800">No.</th>
                                <th class="w-2/5 max-w-sm px-4 py-3 border-r border-slate-800">Verification / Point to
                                    Check</th>
                                <th class="w-16 px-3 py-3 text-center border-r border-slate-800">Yes</th>
                                <th class="w-16 px-3 py-3 text-center border-r border-slate-800">No</th>
                                <th class="w-2/5 min-w-[280px] px-4 py-3">Remarks / Action Required</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-200">
                            @foreach ($checklist->section7_staff ?? [] as $index => $item)
                                <tr class="hover:bg-slate-50/80 transition">
                                    <td
                                        class="px-4 py-3 text-center font-bold text-slate-700 bg-slate-50 border-r border-slate-200">
                                        {{ $item['no'] }}
                                        <input type="hidden" name="section7_staff[{{ $index }}][no]"
                                            value="{{ $item['no'] }}">
                                        <input type="hidden" name="section7_staff[{{ $index }}][point]"
                                            value="{{ $item['point'] }}">
                                    </td>
                                    <td
                                        class="px-4 py-3 text-slate-800 font-medium leading-relaxed border-r border-slate-200">
                                        {{ $item['point'] }}
                                    </td>
                                    <td class="p-1 text-center border-r border-slate-200">
                                        <label
                                            class="flex items-center justify-center w-full min-h-[38px] cursor-pointer hover:bg-emerald-50 rounded transition">
                                            <input type="radio" name="section7_staff[{{ $index }}][compliance]" value="yes"
                                                {{ ($item['compliance'] ?? '') === 'yes' ? 'checked' : '' }}
                                                class="compliance-radio w-4.5 h-4.5 text-emerald-600 focus:ring-2 focus:ring-emerald-500 focus:ring-offset-1 cursor-pointer">
                                        </label>
                                    </td>
                                    <td class="p-1 text-center border-r border-slate-200">
                                        <label
                                            class="flex items-center justify-center w-full min-h-[38px] cursor-pointer hover:bg-rose-50 rounded transition">
                                            <input type="radio" name="section7_staff[{{ $index }}][compliance]" value="no"
                                                {{ ($item['compliance'] ?? '') === 'no' ? 'checked' : '' }}
                                                class="compliance-radio w-4.5 h-4.5 text-rose-600 focus:ring-2 focus:ring-rose-500 focus:ring-offset-1 cursor-pointer">
                                        </label>
                                    </td>
                                    <td class="p-1.5">
                                        <input type="text" name="section7_staff[{{ $index }}][remarks]"
                                            value="{{ old("section7_staff.{$index}.remarks", $item['remarks'] ?? '') }}"
                                            placeholder="Remarks"
                                            class="w-full text-xs rounded-lg border-slate-200 focus:border-indigo-500 focus:ring-indigo-500 bg-white py-1.5 px-2">
                                        <input type="hidden" name="section7_staff[{{ $index }}][evidence_photo]"
                                            value="{{ $item['evidence_photo'] ?? '' }}">
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div
                    class="px-4 sm:px-6 py-3.5 bg-slate-50/90 border-t border-slate-100 flex items-center justify-between gap-3">
                    <button type="button" @click="saveAndGoPrev()"
                        class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl text-slate-700 bg-white border border-slate-300 hover:bg-slate-50 font-semibold text-xs transition shadow-xs cursor-pointer">
                        <span>&larr; Save & Prev: 06. Curriculum</span>
                    </button>
                    <div class="flex items-center gap-2">
                        <button type="submit" @click="document.getElementById('formActionInput').value = 'save_draft'"
                            class="hidden sm:inline-flex items-center gap-1.5 px-3 py-2 text-xs font-semibold text-slate-700 bg-white border border-slate-300 rounded-xl hover:bg-slate-50 transition shadow-xs">
                            Save Draft
                        </button>
                        <button type="button" @click="saveAndGoNext()"
                            class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl bg-indigo-600 text-white hover:bg-indigo-500 font-bold text-xs transition shadow-xs cursor-pointer">
                            <span>Save & Next: 08. Financials</span>
                            <span>&rarr;</span>
                        </button>
                    </div>
                </div>
            </div>

            <!-- SECTION 8: FINANCIAL ASSESSMENT – INCOME & EXPENDITURE -->
            <div x-show="currentTab === '8'" x-cloak
                class="bg-white rounded-2xl border border-slate-200/90 shadow-sm overflow-hidden">
                <div
                    class="px-4 sm:px-6 py-3.5 sm:py-4 border-b border-slate-100 bg-slate-50/70 flex flex-col sm:flex-row sm:items-center justify-between gap-2">
                    <div>
                        <h2 class="text-xs sm:text-sm font-bold text-slate-900 uppercase tracking-wide">8. Financial
                            Assessment – Income & Expenditure</h2>
                        <p class="text-[11px] sm:text-xs text-slate-500">Audited statements, fee structures, bank
                            reconciliations, concessions, liabilities, loans.</p>
                    </div>
                    <span
                        class="text-[11px] sm:text-xs font-semibold px-2.5 py-1 rounded-md bg-indigo-50 text-indigo-700 self-start sm:self-auto">7
                        Verification Points</span>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs border-collapse min-w-[680px]">
                        <thead class="bg-slate-900 text-white uppercase font-bold tracking-wider">
                            <tr>
                                <th class="w-12 px-4 py-3 text-center border-r border-slate-800">No.</th>
                                <th class="w-2/5 max-w-sm px-4 py-3 border-r border-slate-800">Verification / Point to
                                    Check</th>
                                <th class="w-16 px-3 py-3 text-center border-r border-slate-800">Yes</th>
                                <th class="w-16 px-3 py-3 text-center border-r border-slate-800">No</th>
                                <th class="w-2/5 min-w-[280px] px-4 py-3">Remarks / Action Required</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-200">
                            @foreach ($checklist->section8_financial ?? [] as $index => $item)
                                <tr class="hover:bg-slate-50/80 transition">
                                    <td
                                        class="px-4 py-3 text-center font-bold text-slate-700 bg-slate-50 border-r border-slate-200">
                                        {{ $item['no'] }}
                                        <input type="hidden" name="section8_financial[{{ $index }}][no]"
                                            value="{{ $item['no'] }}">
                                        <input type="hidden" name="section8_financial[{{ $index }}][point]"
                                            value="{{ $item['point'] }}">
                                    </td>
                                    <td
                                        class="px-4 py-3 text-slate-800 font-medium leading-relaxed border-r border-slate-200">
                                        {{ $item['point'] }}
                                    </td>
                                    <td class="p-1 text-center border-r border-slate-200">
                                        <label
                                            class="flex items-center justify-center w-full min-h-[38px] cursor-pointer hover:bg-emerald-50 rounded transition">
                                            <input type="radio" name="section8_financial[{{ $index }}][compliance]"
                                                value="yes" {{ ($item['compliance'] ?? '') === 'yes' ? 'checked' : '' }}
                                                class="compliance-radio w-4.5 h-4.5 text-emerald-600 focus:ring-2 focus:ring-emerald-500 focus:ring-offset-1 cursor-pointer">
                                        </label>
                                    </td>
                                    <td class="p-1 text-center border-r border-slate-200">
                                        <label
                                            class="flex items-center justify-center w-full min-h-[38px] cursor-pointer hover:bg-rose-50 rounded transition">
                                            <input type="radio" name="section8_financial[{{ $index }}][compliance]"
                                                value="no" {{ ($item['compliance'] ?? '') === 'no' ? 'checked' : '' }}
                                                class="compliance-radio w-4.5 h-4.5 text-rose-600 focus:ring-2 focus:ring-rose-500 focus:ring-offset-1 cursor-pointer">
                                        </label>
                                    </td>
                                    <td class="p-1.5">
                                        <input type="text" name="section8_financial[{{ $index }}][remarks]"
                                            value="{{ old("section8_financial.{$index}.remarks", $item['remarks'] ?? '') }}"
                                            placeholder="Remarks"
                                            class="w-full text-xs rounded-lg border-slate-200 focus:border-indigo-500 focus:ring-indigo-500 bg-white py-1.5 px-2">
                                        <input type="hidden" name="section8_financial[{{ $index }}][evidence_photo]"
                                            value="{{ $item['evidence_photo'] ?? '' }}">
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div
                    class="px-4 sm:px-6 py-3.5 bg-slate-50/90 border-t border-slate-100 flex items-center justify-between gap-3">
                    <button type="button" @click="saveAndGoPrev()"
                        class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl text-slate-700 bg-white border border-slate-300 hover:bg-slate-50 font-semibold text-xs transition shadow-xs cursor-pointer">
                        <span>&larr; Save & Prev: 07. Staff & HR</span>
                    </button>
                    <div class="flex items-center gap-2">
                        <button type="submit" @click="document.getElementById('formActionInput').value = 'save_draft'"
                            class="hidden sm:inline-flex items-center gap-1.5 px-3 py-2 text-xs font-semibold text-slate-700 bg-white border border-slate-300 rounded-xl hover:bg-slate-50 transition shadow-xs">
                            Save Draft
                        </button>
                        <button type="button" @click="saveAndGoNext()"
                            class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl bg-indigo-600 text-white hover:bg-indigo-500 font-bold text-xs transition shadow-xs cursor-pointer">
                            <span>Save & Next: 09. Branding</span>
                            <span>&rarr;</span>
                        </button>
                    </div>
                </div>
            </div>

            <!-- SECTION 9: BRANDING -->
            <div x-show="currentTab === '9'" x-cloak
                class="bg-white rounded-2xl border border-slate-200/90 shadow-sm overflow-hidden">
                <div
                    class="px-4 sm:px-6 py-3.5 sm:py-4 border-b border-slate-100 bg-slate-50/70 flex flex-col sm:flex-row sm:items-center justify-between gap-2">
                    <div>
                        <h2 class="text-xs sm:text-sm font-bold text-slate-900 uppercase tracking-wide">9. Branding</h2>
                        <p class="text-[11px] sm:text-xs text-slate-500">Local reputation, NGO/industry partnerships,
                            social media review, website, minutes of meeting.</p>
                    </div>
                    <span
                        class="text-[11px] sm:text-xs font-semibold px-2.5 py-1 rounded-md bg-indigo-50 text-indigo-700 self-start sm:self-auto">6
                        Verification Points</span>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs border-collapse min-w-[680px]">
                        <thead class="bg-slate-900 text-white uppercase font-bold tracking-wider">
                            <tr>
                                <th class="w-12 px-4 py-3 text-center border-r border-slate-800">No.</th>
                                <th class="w-2/5 max-w-sm px-4 py-3 border-r border-slate-800">Verification / Point to
                                    Check</th>
                                <th class="w-16 px-3 py-3 text-center border-r border-slate-800">Yes</th>
                                <th class="w-16 px-3 py-3 text-center border-r border-slate-800">No</th>
                                <th class="w-2/5 min-w-[280px] px-4 py-3">Remarks / Action Required</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-200">
                            @foreach ($checklist->section9_brand ?? [] as $index => $item)
                                <tr class="hover:bg-slate-50/80 transition">
                                    <td
                                        class="px-4 py-3 text-center font-bold text-slate-700 bg-slate-50 border-r border-slate-200">
                                        {{ $item['no'] }}
                                        <input type="hidden" name="section9_brand[{{ $index }}][no]"
                                            value="{{ $item['no'] }}">
                                        <input type="hidden" name="section9_brand[{{ $index }}][point]"
                                            value="{{ $item['point'] }}">
                                    </td>
                                    <td
                                        class="px-4 py-3 text-slate-800 font-medium leading-relaxed border-r border-slate-200">
                                        {{ $item['point'] }}
                                    </td>
                                    <td class="p-1 text-center border-r border-slate-200">
                                        <label
                                            class="flex items-center justify-center w-full min-h-[38px] cursor-pointer hover:bg-emerald-50 rounded transition">
                                            <input type="radio" name="section9_brand[{{ $index }}][compliance]" value="yes"
                                                {{ ($item['compliance'] ?? '') === 'yes' ? 'checked' : '' }}
                                                class="compliance-radio w-4.5 h-4.5 text-emerald-600 focus:ring-2 focus:ring-emerald-500 focus:ring-offset-1 cursor-pointer">
                                        </label>
                                    </td>
                                    <td class="p-1 text-center border-r border-slate-200">
                                        <label
                                            class="flex items-center justify-center w-full min-h-[38px] cursor-pointer hover:bg-rose-50 rounded transition">
                                            <input type="radio" name="section9_brand[{{ $index }}][compliance]" value="no"
                                                {{ ($item['compliance'] ?? '') === 'no' ? 'checked' : '' }}
                                                class="compliance-radio w-4.5 h-4.5 text-rose-600 focus:ring-2 focus:ring-rose-500 focus:ring-offset-1 cursor-pointer">
                                        </label>
                                    </td>
                                    <td class="p-1.5">
                                        <input type="text" name="section9_brand[{{ $index }}][remarks]"
                                            value="{{ old("section9_brand.{$index}.remarks", $item['remarks'] ?? '') }}"
                                            placeholder="Remarks"
                                            class="w-full text-xs rounded-lg border-slate-200 focus:border-indigo-500 focus:ring-indigo-500 bg-white py-1.5 px-2">
                                        <input type="hidden" name="section9_brand[{{ $index }}][evidence_photo]"
                                            value="{{ $item['evidence_photo'] ?? '' }}">
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div
                    class="px-4 sm:px-6 py-3.5 bg-slate-50/90 border-t border-slate-100 flex items-center justify-between gap-3">
                    <button type="button" @click="saveAndGoPrev()"
                        class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl text-slate-700 bg-white border border-slate-300 hover:bg-slate-50 font-semibold text-xs transition shadow-xs cursor-pointer">
                        <span>&larr; Save & Prev: 08. Financials</span>
                    </button>
                    <div class="flex items-center gap-2">
                        <button type="submit" @click="document.getElementById('formActionInput').value = 'save_draft'"
                            class="hidden sm:inline-flex items-center gap-1.5 px-3 py-2 text-xs font-semibold text-slate-700 bg-white border border-slate-300 rounded-xl hover:bg-slate-50 transition shadow-xs">
                            Save Draft
                        </button>
                        <button type="button" @click="saveAndGoNext()"
                            class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl bg-indigo-600 text-white hover:bg-indigo-500 font-bold text-xs transition shadow-xs cursor-pointer">
                            <span>Save & Next: 10. Primary Quality</span>
                            <span>&rarr;</span>
                        </button>
                    </div>
                </div>
            </div>

            <!-- SECTION 10: PRIMARY QUALITY -->
            <div x-show="currentTab === '10'" x-cloak
                class="bg-white rounded-2xl border border-slate-200/90 shadow-sm p-4 sm:p-6 space-y-4">
                <div class="border-b border-slate-100 pb-3">
                    <h2 class="text-xs sm:text-sm font-bold text-slate-900 uppercase tracking-wide">10. Primary Quality
                    </h2>
                    <p class="text-[11px] sm:text-xs text-slate-500">Select 5 classes from the school's active enrolment
                        in the heading below, then evaluate foundational competencies with markings and evidence
                        remarks.</p>
                </div>

                @php
                    $savedEnrolmentClasses = collect($checklist->section5_enrolment ?? [])
                        ->pluck('class_division')
                        ->map(fn($v) => trim((string) $v))
                        ->filter()
                        ->unique()
                        ->values()
                        ->all();

                    // Strictly use saved class names; fallback to default enrolment classes only if none exist yet
                    $savedClasses = !empty($savedEnrolmentClasses)
                        ? $savedEnrolmentClasses
                        : collect(\App\Models\FeasibilityChecklist::defaultTemplate()['section5_enrolment'] ?? [])
                            ->pluck('class_division')
                            ->map(fn($v) => trim((string) $v))
                            ->filter()
                            ->unique()
                            ->values()
                            ->all();

                    $sec10 = $checklist->section10_primary_assessment;
                    $sec10Classes = $sec10['classes'] ?? [];
                    while (count($sec10Classes) < 5) {
                        $idx = count($sec10Classes);
                        $sec10Classes[] = $savedClasses[$idx] ?? ("Class " . ($idx + 1));
                    }
                    $sec10Classes = array_slice($sec10Classes, 0, 5);

                    $sec10Rows = $sec10['rows'] ?? [
                        ['particular' => 'Basic reading', 'c1' => '', 'c2' => '', 'c3' => '', 'c4' => '', 'c5' => '', 'remarks' => ''],
                        ['particular' => 'Basic writing', 'c1' => '', 'c2' => '', 'c3' => '', 'c4' => '', 'c5' => '', 'remarks' => ''],
                        ['particular' => 'Basic mathematical operations', 'c1' => '', 'c2' => '', 'c3' => '', 'c4' => '', 'c5' => '', 'remarks' => ''],
                        ['particular' => 'Can speak confidently', 'c1' => '', 'c2' => '', 'c3' => '', 'c4' => '', 'c5' => '', 'remarks' => ''],
                        ['particular' => 'Can listen attentively', 'c1' => '', 'c2' => '', 'c3' => '', 'c4' => '', 'c5' => '', 'remarks' => ''],
                    ];
                @endphp

                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs border-collapse min-w-[780px]">
                        <thead class="bg-slate-900 text-white uppercase font-bold tracking-wider">
                            <tr>
                                <th rowspan="2" class="w-64 px-4 py-3 border-r border-slate-800 align-middle">
                                    PARTICULARS</th>
                                <th colspan="5"
                                    class="px-3 py-2 text-center border-r border-slate-800 bg-slate-800 tracking-widest text-xs font-black">
                                    CLASS</th>
                                <th rowspan="2" class="px-4 py-3 border-slate-800 align-middle">REMARKS / EVIDENCE /
                                    OBSERVATIONS</th>
                            </tr>
                            <tr class="bg-slate-800">
                                @for ($c = 0; $c < 5; $c++)
                                    <th class="p-1.5 border-r border-slate-700 text-center w-28">
                                        <select name="section10_primary_assessment[classes][{{ $c }}]"
                                            class="w-full text-[11px] font-bold text-slate-800 rounded-lg border-slate-300 focus:border-indigo-500 focus:ring-indigo-500 py-1 px-1.5 bg-white shadow-xs">
                                            <option value="">-- Class {{ $c + 1 }} --</option>
                                            @foreach ($savedClasses as $cOpt)
                                                <option value="{{ $cOpt }}" {{ ($sec10Classes[$c] ?? '') === $cOpt ? 'selected' : '' }}>
                                                    {{ $cOpt }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </th>
                                @endfor
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-200 bg-white font-sans">
                            @foreach ($sec10Rows as $index => $item)
                                <tr class="hover:bg-slate-50 transition">
                                    <td
                                        class="px-4 py-3 font-semibold text-slate-800 border-r border-slate-200 bg-slate-50/60">
                                        <input type="hidden"
                                            name="section10_primary_assessment[rows][{{ $index }}][particular]"
                                            value="{{ $item['particular'] ?? '' }}">
                                        <div class="flex items-center gap-2">
                                            <span
                                                class="w-5 h-5 rounded-full bg-slate-200 text-slate-700 font-bold text-[10px] flex items-center justify-center shrink-0">{{ $index + 1 }}</span>
                                            <span>{{ $item['particular'] ?? '' }}</span>
                                        </div>
                                    </td>
                                    @for ($c = 1; $c <= 5; $c++)
                                        <td class="p-1.5 text-center border-r border-slate-200 w-28">
                                            <input type="text"
                                                name="section10_primary_assessment[rows][{{ $index }}][c{{ $c }}]"
                                                value="{{ $item['c' . $c] ?? '' }}" placeholder="Grade/Score"
                                                class="w-full text-center text-xs font-semibold rounded-lg border-slate-300 focus:border-indigo-500 focus:ring-indigo-500 py-1.5">
                                        </td>
                                    @endfor
                                    <td class="p-1.5">
                                        <input type="text" name="section10_primary_assessment[rows][{{ $index }}][remarks]"
                                            value="{{ $item['remarks'] ?? '' }}" placeholder="Remarks"
                                            class="w-full text-xs rounded-lg border-slate-300 focus:border-indigo-500 focus:ring-indigo-500 py-1.5">
                                        <input type="hidden"
                                            name="section10_primary_assessment[rows][{{ $index }}][evidence_photo]"
                                            value="{{ $item['evidence_photo'] ?? '' }}">
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div
                    class="px-4 sm:px-6 py-3.5 bg-slate-50/90 border-t border-slate-100 flex items-center justify-between gap-3">
                    <button type="button" @click="saveAndGoPrev()"
                        class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl text-slate-700 bg-white border border-slate-300 hover:bg-slate-50 font-semibold text-xs transition shadow-xs cursor-pointer">
                        <span>&larr; Save & Prev: 09. Branding</span>
                    </button>
                    <div class="flex items-center gap-2">
                        <button type="submit" @click="document.getElementById('formActionInput').value = 'save_draft'"
                            class="hidden sm:inline-flex items-center gap-1.5 px-3 py-2 text-xs font-semibold text-slate-700 bg-white border border-slate-300 rounded-xl hover:bg-slate-50 transition shadow-xs">
                            Save Draft
                        </button>
                        <button type="button" @click="saveAndGoNext()"
                            class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl bg-indigo-600 text-white hover:bg-indigo-500 font-bold text-xs transition shadow-xs cursor-pointer">
                            <span>Save & Next: 11. Secondary Quality</span>
                            <span>&rarr;</span>
                        </button>
                    </div>
                </div>
            </div>

            <!-- SECTION 11: SECONDARY QUALITY -->
            <div x-show="currentTab === '11'" x-cloak
                class="bg-white rounded-2xl border border-slate-200/90 shadow-sm p-4 sm:p-6 space-y-4">
                <div class="border-b border-slate-100 pb-3">
                    <h2 class="text-xs sm:text-sm font-bold text-slate-900 uppercase tracking-wide">11. Secondary
                        Quality</h2>
                    <p class="text-[11px] sm:text-xs text-slate-500">Sample and evaluate student competency across
                        Middle & Secondary classes.</p>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs border-collapse min-w-[780px]">
                        <thead class="bg-slate-900 text-white uppercase font-bold tracking-wider">
                            <tr>
                                <th rowspan="2" class="w-64 px-4 py-3 border-r border-slate-800 align-middle">
                                    PARTICULARS</th>
                                <th colspan="5"
                                    class="px-3 py-2 text-center border-r border-slate-800 bg-slate-800 tracking-widest text-xs font-black">
                                    CLASS</th>
                                <th rowspan="2" class="px-4 py-3 border-slate-800 align-middle">REMARKS / EVIDENCE /
                                    OBSERVATIONS</th>
                            </tr>
                            <tr class="bg-slate-800 text-center font-bold text-slate-200 text-xs">
                                <th class="p-1.5 border-r border-slate-700 text-center w-28">Class 6</th>
                                <th class="p-1.5 border-r border-slate-700 text-center w-28">Class 7</th>
                                <th class="p-1.5 border-r border-slate-700 text-center w-28">Class 8</th>
                                <th class="p-1.5 border-r border-slate-700 text-center w-28">Class 9</th>
                                <th class="p-1.5 border-r border-slate-700 text-center w-28">Class 10</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-200 bg-white font-sans">
                            @php
                                $sec11Items = !empty($checklist->section11_secondary_assessment)
                                    ? $checklist->section11_secondary_assessment
                                    : \App\Models\FeasibilityChecklist::defaultTemplate()['section11_secondary_assessment'];
                            @endphp
                            @foreach ($sec11Items as $index => $item)
                                <tr class="hover:bg-slate-50 transition">
                                    <td
                                        class="px-4 py-3 font-semibold text-slate-800 border-r border-slate-200 bg-slate-50/60">
                                        <input type="hidden" name="section11_secondary_assessment[{{ $index }}][particular]"
                                            value="{{ $item['particular'] ?? '' }}">
                                        <div class="flex items-center gap-2">
                                            <span
                                                class="w-5 h-5 rounded-full bg-slate-200 text-slate-700 font-bold text-[10px] flex items-center justify-center shrink-0">{{ $index + 1 }}</span>
                                            <span>{{ $item['particular'] ?? '' }}</span>
                                        </div>
                                    </td>
                                    @for ($c = 1; $c <= 5; $c++)
                                        <td class="p-1.5 text-center border-r border-slate-200 w-28">
                                            <input type="text" name="section11_secondary_assessment[{{ $index }}][c{{ $c }}]"
                                                value="{{ $item['c' . $c] ?? '' }}" placeholder="Grade/Score"
                                                class="w-full text-center text-xs font-semibold rounded-lg border-slate-300 focus:border-indigo-500 focus:ring-indigo-500 py-1.5">
                                        </td>
                                    @endfor
                                    <td class="p-1.5">
                                        <input type="text" name="section11_secondary_assessment[{{ $index }}][remarks]"
                                            value="{{ $item['remarks'] ?? '' }}" placeholder="Remarks"
                                            class="w-full text-xs rounded-lg border-slate-300 focus:border-indigo-500 focus:ring-indigo-500 py-1.5">
                                        <input type="hidden"
                                            name="section11_secondary_assessment[{{ $index }}][evidence_photo]"
                                            value="{{ $item['evidence_photo'] ?? '' }}">
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div
                    class="px-4 sm:px-6 py-3.5 bg-slate-50/90 border-t border-slate-100 flex items-center justify-between gap-3">
                    <button type="button" @click="saveAndGoPrev()"
                        class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl text-slate-700 bg-white border border-slate-300 hover:bg-slate-50 font-semibold text-xs transition shadow-xs cursor-pointer">
                        <span>&larr; Save & Prev: 10. Primary Quality</span>
                    </button>
                    <div class="flex items-center gap-2">
                        <button type="submit" @click="document.getElementById('formActionInput').value = 'save_draft'"
                            class="hidden sm:inline-flex items-center gap-1.5 px-3 py-2 text-xs font-semibold text-slate-700 bg-white border border-slate-300 rounded-xl hover:bg-slate-50 transition shadow-xs">
                            Save Draft
                        </button>
                        <button type="button" @click="saveAndGoNext()"
                            class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl bg-indigo-600 text-white hover:bg-indigo-500 font-bold text-xs transition shadow-xs cursor-pointer">
                            <span>Save & Next: 12. Teachers' Assessment</span>
                            <span>&rarr;</span>
                        </button>
                    </div>
                </div>
            </div>

            <!-- SECTION 12: TEACHERS' ASSESSMENT -->
            <div x-show="currentTab === '12'" x-cloak
                class="bg-white rounded-2xl border border-slate-200/90 shadow-sm overflow-hidden">
                <div
                    class="px-4 sm:px-6 py-3.5 sm:py-4 border-b border-slate-100 bg-slate-50/70 flex flex-col sm:flex-row sm:items-center justify-between gap-2">
                    <div>
                        <h2 class="text-xs sm:text-sm font-bold text-slate-900 uppercase tracking-wide">12. Teachers’
                            Assessment</h2>
                        <p class="text-[11px] sm:text-xs text-slate-500">Evaluation matrix for teaching faculty across
                            subject mastery, documentation, and training.</p>
                    </div>
                    <span
                        class="text-[11px] sm:text-xs font-semibold px-2.5 py-1 rounded-md bg-indigo-50 text-indigo-700 self-start sm:self-auto">7
                        Competency Criteria</span>
                </div>

                @php
                    $sec12 = $checklist->section12_teachers_assessment;
                    $sec12Teachers = $sec12['teachers'] ?? ['Teacher 1', 'Teacher 2', 'Teacher 3', 'Teacher 4', 'Teacher 5', 'Teacher 6', 'Teacher 7'];
                    while (count($sec12Teachers) < 7) {
                        $idx = count($sec12Teachers) + 1;
                        $sec12Teachers[] = "Teacher {$idx}";
                    }
                    $sec12Teachers = array_slice($sec12Teachers, 0, 7);

                    $sec12Rows = $sec12['rows'] ?? [
                        ['particular' => 'English proficiency', 't1' => '', 't2' => '', 't3' => '', 't4' => '', 't5' => '', 't6' => '', 't7' => '', 'remarks' => ''],
                        ['particular' => 'Subject Quality', 't1' => '', 't2' => '', 't3' => '', 't4' => '', 't5' => '', 't6' => '', 't7' => '', 'remarks' => ''],
                        ['particular' => 'Documentation professionalism', 't1' => '', 't2' => '', 't3' => '', 't4' => '', 't5' => '', 't6' => '', 't7' => '', 'remarks' => ''],
                        ['particular' => 'Professional exposure', 't1' => '', 't2' => '', 't3' => '', 't4' => '', 't5' => '', 't6' => '', 't7' => '', 'remarks' => ''],
                        ['particular' => 'Certified qualities', 't1' => '', 't2' => '', 't3' => '', 't4' => '', 't5' => '', 't6' => '', 't7' => '', 'remarks' => ''],
                        ['particular' => 'Uncertified and social qualities', 't1' => '', 't2' => '', 't3' => '', 't4' => '', 't5' => '', 't6' => '', 't7' => '', 'remarks' => ''],
                        ['particular' => 'Trainings provided in the school', 't1' => '', 't2' => '', 't3' => '', 't4' => '', 't5' => '', 't6' => '', 't7' => '', 'remarks' => ''],
                    ];
                @endphp

                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs border-collapse min-w-[880px]">
                        <thead class="bg-slate-900 text-white uppercase font-bold tracking-wider">
                            <tr>
                                <th rowspan="2" class="w-64 px-4 py-3 border-r border-slate-800 align-middle">
                                    PARTICULARS</th>
                                <th colspan="7"
                                    class="px-3 py-2 text-center border-r border-slate-800 bg-slate-800 tracking-widest text-xs font-black">
                                    TEACHER NAME</th>
                                <th rowspan="2" class="px-4 py-3 border-slate-800 align-middle">REMARKS / EVIDENCE</th>
                            </tr>
                            <tr class="bg-slate-800">
                                @for ($t = 0; $t < 7; $t++)
                                    <th class="p-1.5 border-r border-slate-700 text-center w-28">
                                        <input type="text" name="section12_teachers_assessment[teachers][{{ $t }}]"
                                            value="{{ $sec12Teachers[$t] ?? '' }}" placeholder="Teacher {{ $t + 1 }}"
                                            class="w-full text-[11px] font-bold text-slate-800 rounded-lg border-slate-300 focus:border-indigo-500 focus:ring-indigo-500 py-1 px-1.5 bg-white shadow-xs text-center">
                                    </th>
                                @endfor
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-200 bg-white font-sans">
                            @foreach ($sec12Rows as $index => $item)
                                <tr class="hover:bg-slate-50 transition">
                                    <td
                                        class="px-4 py-3 font-semibold text-slate-800 border-r border-slate-200 bg-slate-50/60">
                                        <input type="hidden"
                                            name="section12_teachers_assessment[rows][{{ $index }}][particular]"
                                            value="{{ $item['particular'] ?? '' }}">
                                        <div class="flex items-center gap-2">
                                            <span
                                                class="w-5 h-5 rounded-full bg-slate-200 text-slate-700 font-bold text-[10px] flex items-center justify-center shrink-0">{{ $index + 1 }}</span>
                                            <span>{{ $item['particular'] ?? '' }}</span>
                                        </div>
                                    </td>
                                    @for ($t = 1; $t <= 7; $t++)
                                        <td class="p-1.5 text-center border-r border-slate-200 w-28">
                                            <input type="text"
                                                name="section12_teachers_assessment[rows][{{ $index }}][t{{ $t }}]"
                                                value="{{ $item['t' . $t] ?? '' }}" placeholder="A/B/C"
                                                class="w-full text-center text-xs font-semibold rounded-lg border-slate-300 focus:border-indigo-500 focus:ring-indigo-500 py-1.5">
                                        </td>
                                    @endfor
                                    <td class="p-1.5">
                                        <input type="text" name="section12_teachers_assessment[rows][{{ $index }}][remarks]"
                                            value="{{ $item['remarks'] ?? '' }}" placeholder="Remarks"
                                            class="w-full text-xs rounded-lg border-slate-300 focus:border-indigo-500 focus:ring-indigo-500 py-1.5">
                                        <input type="hidden"
                                            name="section12_teachers_assessment[rows][{{ $index }}][evidence_photo]"
                                            value="{{ $item['evidence_photo'] ?? '' }}">
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div
                    class="px-4 sm:px-6 py-3.5 bg-slate-50/90 border-t border-slate-100 flex items-center justify-between gap-3">
                    <button type="button" @click="saveAndGoPrev()"
                        class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl text-slate-700 bg-white border border-slate-300 hover:bg-slate-50 font-semibold text-xs transition shadow-xs cursor-pointer">
                        <span>&larr; Save & Prev: 11. Secondary Quality</span>
                    </button>
                    <div class="flex items-center gap-2">
                        <button type="submit" @click="document.getElementById('formActionInput').value = 'save_draft'"
                            class="hidden sm:inline-flex items-center gap-1.5 px-3 py-2 text-xs font-semibold text-slate-700 bg-white border border-slate-300 rounded-xl hover:bg-slate-50 transition shadow-xs">
                            Save Draft
                        </button>
                        <button type="button" @click="saveAndGoNext()"
                            class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl bg-indigo-600 text-white hover:bg-indigo-500 font-bold text-xs transition shadow-xs cursor-pointer">
                            <span>Save & Next: 13. Evidence Photos</span>
                            <span>&rarr;</span>
                        </button>
                    </div>
                </div>
            </div>

            <!-- SECTION 13: EVIDENCE PHOTOS & REMARKS REVIEW -->
            @include('applications.checklist.partials.section14-evidence-review')

            <!-- SECTION 14: FINAL ASSESSMENT REMARKS -->
            <div x-show="currentTab === '14'" x-cloak
                class="bg-white rounded-2xl border border-slate-200/90 shadow-sm p-4 sm:p-6 space-y-4 sm:space-y-5">
                <div class="border-b border-slate-100 pb-3">
                    <h2 class="text-xs sm:text-sm font-bold text-slate-900 uppercase tracking-wide">14. Final Assessment
                        Remarks</h2>
                    <p class="text-[11px] sm:text-xs text-slate-500">Record category-wise findings and feasibility
                        observations across all institutional dimensions.</p>
                </div>

                <div class="space-y-4 text-xs">
                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Academic:</label>
                        <textarea name="section13_final_remarks[academic]" rows="3"
                            placeholder="Academic curriculum, teaching quality, student outcomes, affiliation conformity..."
                            class="w-full text-xs rounded-xl border-slate-300 focus:border-indigo-500 focus:ring-indigo-500">{{ old('section13_final_remarks.academic', $checklist->section13_final_remarks['academic'] ?? '') }}</textarea>
                        <input type="hidden" name="section13_final_remarks[photos][academic]"
                            value="{{ $checklist->section13_final_remarks['photos']['academic'] ?? ($checklist->section13_final_remarks['academic_photo'] ?? ($checklist->section13_final_remarks['academic_evidence_photo'] ?? '')) }}">
                    </div>

                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Financial:</label>
                        <textarea name="section13_final_remarks[financial]" rows="3"
                            placeholder="Fee realization, loan commitments, statutory dues, monthly accounts status..."
                            class="w-full text-xs rounded-xl border-slate-300 focus:border-indigo-500 focus:ring-indigo-500">{{ old('section13_final_remarks.financial', $checklist->section13_final_remarks['financial'] ?? '') }}</textarea>
                        <input type="hidden" name="section13_final_remarks[photos][financial]"
                            value="{{ $checklist->section13_final_remarks['photos']['financial'] ?? ($checklist->section13_final_remarks['financial_photo'] ?? ($checklist->section13_final_remarks['financial_evidence_photo'] ?? '')) }}">
                    </div>

                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Infrastructural:</label>
                        <textarea name="section13_final_remarks[infrastructural]" rows="3"
                            placeholder="Building stability, classrooms, labs, play area, boundary wall, expansion capacity..."
                            class="w-full text-xs rounded-xl border-slate-300 focus:border-indigo-500 focus:ring-indigo-500">{{ old('section13_final_remarks.infrastructural', $checklist->section13_final_remarks['infrastructural'] ?? '') }}</textarea>
                        <input type="hidden" name="section13_final_remarks[photos][infrastructural]"
                            value="{{ $checklist->section13_final_remarks['photos']['infrastructural'] ?? ($checklist->section13_final_remarks['infrastructural_photo'] ?? ($checklist->section13_final_remarks['infrastructural_evidence_photo'] ?? '')) }}">
                    </div>

                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Legal:</label>
                        <textarea name="section13_final_remarks[legal]" rows="3"
                            placeholder="Title deeds, lease term, trust deed, litigation status, statutory NOCs..."
                            class="w-full text-xs rounded-xl border-slate-300 focus:border-indigo-500 focus:ring-indigo-500">{{ old('section13_final_remarks.legal', $checklist->section13_final_remarks['legal'] ?? '') }}</textarea>
                        <input type="hidden" name="section13_final_remarks[photos][legal]"
                            value="{{ $checklist->section13_final_remarks['photos']['legal'] ?? ($checklist->section13_final_remarks['legal_photo'] ?? ($checklist->section13_final_remarks['legal_evidence_photo'] ?? '')) }}">
                    </div>

                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Administration Staff:</label>
                        <textarea name="section13_final_remarks[administration_staff]" rows="3"
                            placeholder="Administrative leadership, front desk, accounts staff, HR agreements, record maintenance..."
                            class="w-full text-xs rounded-xl border-slate-300 focus:border-indigo-500 focus:ring-indigo-500">{{ old('section13_final_remarks.administration_staff', $checklist->section13_final_remarks['administration_staff'] ?? '') }}</textarea>
                        <input type="hidden" name="section13_final_remarks[photos][administration_staff]"
                            value="{{ $checklist->section13_final_remarks['photos']['administration_staff'] ?? ($checklist->section13_final_remarks['administration_staff_photo'] ?? ($checklist->section13_final_remarks['administration_staff_evidence_photo'] ?? '')) }}">
                    </div>

                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Teaching Staff:</label>
                        <textarea name="section13_final_remarks[teaching_staff]" rows="3"
                            placeholder="Teacher retention, qualifications, salary scale, appointment agreements, willingness to transition..."
                            class="w-full text-xs rounded-xl border-slate-300 focus:border-indigo-500 focus:ring-indigo-500">{{ old('section13_final_remarks.teaching_staff', $checklist->section13_final_remarks['teaching_staff'] ?? '') }}</textarea>
                        <input type="hidden" name="section13_final_remarks[photos][teaching_staff]"
                            value="{{ $checklist->section13_final_remarks['photos']['teaching_staff'] ?? ($checklist->section13_final_remarks['teaching_staff_photo'] ?? ($checklist->section13_final_remarks['teaching_staff_evidence_photo'] ?? '')) }}">
                    </div>

                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Safety:</label>
                        <textarea name="section13_final_remarks[safety]" rows="3"
                            placeholder="Fire NOC, structural safety, CCTV coverage, student transport security, child protection compliance..."
                            class="w-full text-xs rounded-xl border-slate-300 focus:border-indigo-500 focus:ring-indigo-500">{{ old('section13_final_remarks.safety', $checklist->section13_final_remarks['safety'] ?? '') }}</textarea>
                        <input type="hidden" name="section13_final_remarks[photos][safety]"
                            value="{{ $checklist->section13_final_remarks['photos']['safety'] ?? ($checklist->section13_final_remarks['safety_photo'] ?? ($checklist->section13_final_remarks['safety_evidence_photo'] ?? '')) }}">
                    </div>

                    <div>
                        <label class="block font-bold text-slate-700 mb-1">SIGNED BY;</label>
                        <input type="text" name="section13_final_remarks[signed_by]"
                            value="{{ old('section13_final_remarks.signed_by', $checklist->section13_final_remarks['signed_by'] ?? ($checklist->inspector_name ?? auth()->user()->name)) }}"
                            placeholder="Inspector Name & Designation"
                            class="w-full text-xs rounded-xl border-slate-300 focus:border-indigo-500 focus:ring-indigo-500">
                    </div>
                </div>

                <div
                    class="px-4 sm:px-6 py-3.5 bg-slate-50/90 border-t border-slate-100 flex items-center justify-between gap-3">
                    <button type="button" @click="saveAndGoPrev()"
                        class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl text-slate-700 bg-white border border-slate-300 hover:bg-slate-50 font-semibold text-xs transition shadow-xs cursor-pointer">
                        <span>&larr; Save & Prev: 13. Evidence Photos</span>
                    </button>
                    <div class="flex items-center gap-2">
                        <button type="button" @click="saveDraft()"
                            class="hidden sm:inline-flex items-center gap-1.5 px-3 py-2 text-xs font-semibold text-slate-700 bg-white border border-slate-300 rounded-xl hover:bg-slate-50 transition shadow-xs cursor-pointer">
                            Save Draft
                        </button>
                        <button type="button" @click="saveAndGoNext()"
                            class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl bg-indigo-600 text-white hover:bg-indigo-500 font-bold text-xs transition shadow-xs cursor-pointer">
                            <span>Save & Next: 15. Sign-off & Submit</span>
                            <span>&rarr;</span>
                        </button>
                    </div>
                </div>
            </div>

            <!-- SECTION 15: SIGN-OFF & SUBMIT -->
            <div x-show="currentTab === '15'" x-cloak
                class="bg-white rounded-2xl border border-slate-200/90 shadow-sm p-4 sm:p-6 space-y-4 sm:space-y-5">
                <div class="border-b border-slate-100 pb-3">
                    <h2 class="text-xs sm:text-sm font-bold text-slate-900 uppercase tracking-wide">15. Inspector
                        Recommendation & Submission</h2>
                    <p class="text-[11px] sm:text-xs text-slate-500">Provide overall feasibility executive conclusion
                        and submit the completed checklist to the Super Admin.</p>
                </div>

                <!-- Supplementary Evidence Documentation (Optional) -->
                @if($evidenceSummary['total_remarks'] > 0)
                    <div
                        class="bg-slate-50 border border-slate-200 rounded-xl p-3.5 flex flex-col sm:flex-row sm:items-center justify-between gap-3 text-slate-700 text-xs">
                        <div class="flex items-center gap-3">
                            <div
                                class="w-8 h-8 rounded-lg bg-indigo-50 border border-indigo-100 flex items-center justify-center shrink-0 text-indigo-600">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                            </div>
                            <div>
                                <p class="font-bold text-slate-800">Supplementary Evidence Photos (Optional)</p>
                                <p class="text-[11px] text-slate-500">
                                    {{ $evidenceSummary['photos_attached'] }} of {{ $evidenceSummary['total_remarks'] }}
                                    remarked items have photo documentation attached. You can review or attach additional
                                    photos in Section 13.
                                </p>
                            </div>
                        </div>
                        <button type="button" @click="currentTab = '13'"
                            class="shrink-0 px-3 py-1.5 rounded-lg text-xs font-semibold text-indigo-700 bg-white hover:bg-indigo-50 border border-indigo-200 transition shadow-2xs cursor-pointer">
                            View Section 13 &rarr;
                        </button>
                    </div>
                @endif

                <div class="space-y-4 text-xs">
                    <div>
                        <label class="block font-semibold text-slate-700 mb-1.5">Executive Feasibility Summary &
                            Takeover Viability Remarks</label>
                        <textarea name="overall_summary" rows="4"
                            placeholder="Provide key observations regarding legal, infrastructural, financial liabilities, and operational feasibility for takeover..."
                            class="w-full text-xs rounded-xl border-slate-300 focus:border-indigo-500 focus:ring-indigo-500">{{ old('overall_summary', $checklist->overall_summary) }}</textarea>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block font-semibold text-slate-700 mb-1.5">Overall Takeover Recommendation
                                <span class="text-rose-500">*</span></label>
                            <select name="overall_recommendation"
                                class="w-full text-xs rounded-xl border-slate-300 focus:border-indigo-500 focus:ring-indigo-500">
                                <option value="recommended" {{ ($checklist->overall_recommendation ?? '') === 'recommended' ? 'selected' : '' }}>
                                    Recommended for Takeover
                                </option>
                                <option value="conditionally_recommended" {{ ($checklist->overall_recommendation ?? '') === 'conditionally_recommended' ? 'selected' : '' }}>
                                    Conditionally Recommended (Subject to rectifications)
                                </option>
                                <option value="not_recommended" {{ ($checklist->overall_recommendation ?? '') === 'not_recommended' ? 'selected' : '' }}>
                                    Not Recommended (High liability / unviable)
                                </option>
                            </select>
                        </div>

                        <div>
                            <label class="block font-semibold text-slate-700 mb-1.5">Inspector Signature / Name <span
                                    class="text-rose-500">*</span></label>
                            <input type="text" name="inspector_name"
                                value="{{ old('inspector_name', $checklist->inspector_name ?? auth()->user()->name) }}"
                                required
                                class="w-full text-xs rounded-xl border-slate-300 focus:border-indigo-500 focus:ring-indigo-500">
                        </div>
                    </div>

                    <div class="bg-amber-50 border border-amber-200 rounded-xl p-4 text-amber-900 space-y-1">
                        <p class="font-bold flex items-center gap-1.5">
                            <svg class="w-4 h-4 text-amber-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                            </svg>
                            Submission Notice:
                        </p>
                        <p class="text-[11px] text-amber-800 leading-relaxed">
                            Submitting this report locks the inspection checklist and sends it directly to the
                            <strong>Super Admin</strong> for final decision and feasibility review. You can also save as
                            draft at any time before submitting.
                        </p>
                    </div>
                </div>

                <div
                    class="px-4 sm:px-6 py-3.5 bg-slate-50/90 border-t border-slate-100 flex items-center justify-between gap-3">
                    <button type="button" @click="saveAndGoPrev()"
                        class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl text-slate-700 bg-white border border-slate-300 hover:bg-slate-50 font-semibold text-xs transition shadow-xs cursor-pointer">
                        <span>&larr; Save & Prev: 14. Remarks</span>
                    </button>
                    <div class="flex items-center gap-2">
                        <button type="submit" @click="document.getElementById('formActionInput').value = 'save_draft'"
                            class="hidden sm:inline-flex items-center gap-1.5 px-3 py-2 text-xs font-semibold text-slate-700 bg-white border border-slate-300 rounded-xl hover:bg-slate-50 transition shadow-xs">
                            Save Draft
                        </button>
                        <button type="button" @click="submitChecklist()"
                            class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl bg-emerald-600 text-white hover:bg-emerald-500 font-bold text-xs transition shadow-xs cursor-pointer">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <span>Submit Report to Super Admin</span>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Footer Action Bar -->
            <div
                class="bg-white rounded-2xl border border-slate-200/90 p-4 sm:p-5 shadow-sm flex flex-col sm:flex-row sm:items-center justify-between gap-3 sm:gap-4">
                <div class="text-xs text-slate-500 flex items-center gap-2">
                    <span class="inline-block w-2 h-2 rounded-full bg-emerald-500"></span>
                    <span>All changes saved securely to database</span>
                </div>

                <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-2.5 sm:gap-3 w-full sm:w-auto">
                    <button type="submit" @click="document.getElementById('formActionInput').value = 'save_draft'"
                        class="w-full sm:w-auto px-4 py-2.5 text-xs font-semibold text-slate-700 bg-white border border-slate-300 rounded-xl hover:bg-slate-50 transition shadow-xs text-center">
                        Save as Draft
                    </button>

                    <button type="button" @click="saveAndGoNext()"
                        class="w-full sm:w-auto px-4 py-2.5 text-xs font-semibold text-indigo-700 bg-indigo-50 border border-indigo-200 rounded-xl hover:bg-indigo-100 transition shadow-xs text-center">
                        Save & Next Section &rarr;
                    </button>

                    <button type="button" @click="submitChecklist()"
                        class="w-full sm:w-auto px-5 py-2.5 text-xs font-bold text-white bg-emerald-600 hover:bg-emerald-500 rounded-xl shadow-sm transition flex items-center justify-center gap-2 text-center">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        Submit Report to Super Admin
                    </button>
                </div>
            </div>

            <!-- Sticky Mobile Bottom Quick-Action Bar (Smartphones only) -->
            <div
                class="sm:hidden fixed bottom-0 left-0 right-0 z-40 bg-white/95 backdrop-blur-md border-t border-slate-200/90 px-3 py-2.5 shadow-lg flex items-center justify-between gap-2">
                <button type="submit" @click="document.getElementById('formActionInput').value = 'save_draft'"
                    class="flex-1 py-2 text-xs font-semibold text-slate-700 bg-slate-100 hover:bg-slate-200 rounded-xl transition text-center border border-slate-200">
                    Save Draft
                </button>
                <button type="button" @click="saveAndGoNext()"
                    class="flex-1 py-2 text-xs font-bold text-white bg-indigo-600 hover:bg-indigo-500 rounded-xl transition shadow-xs text-center">
                    Save & Next &rarr;
                </button>
            </div>

            <!-- Photo Lightbox Modal -->
            @include('applications.checklist.partials.photo-lightbox')
        </form>
    </div>

    <!-- Alpine Component Logic -->
    <script>
        // Global Keyboard Shortcut handler for compliance radio buttons (Item 4)
        document.addEventListener('keydown', function (e) {
            const active = document.activeElement;
            if (active && (active.classList.contains('compliance-radio') || (active.type === 'radio' && active.name && active.name.includes('[compliance]')))) {
                if (e.key === 'Enter') {
                    e.preventDefault();
                    active.checked = true;
                    active.dispatchEvent(new Event('change', { bubbles: true }));
                } else if (e.key === 'y' || e.key === 'Y') {
                    e.preventDefault();
                    const row = active.closest('tr');
                    if (row) {
                        const r = row.querySelector('input[type="radio"][value="yes"]');
                        if (r) {
                            r.checked = true;
                            r.focus();
                            r.dispatchEvent(new Event('change', { bubbles: true }));
                        }
                    }
                } else if (e.key === 'n' || e.key === 'N') {
                    e.preventDefault();
                    const row = active.closest('tr');
                    if (row) {
                        const r = row.querySelector('input[type="radio"][value="no"]');
                        if (r) {
                            r.checked = true;
                            r.focus();
                            r.dispatchEvent(new Event('change', { bubbles: true }));
                        }
                    }
                }
            }
        });

        function evidenceItem(config) {
            return {
                photo: config.initialPhoto || '',
                photoUrl: config.initialUrl || '',
                remark: config.initialRemark || '',
                label: config.label || 'Evidence Photo',
                isUploading: false,

                hasRemarkWithoutPhoto() {
                    return (this.remark || '').trim().length > 0 && !this.photo;
                },

                openFileDialog() {
                    this.$refs.filePicker.click();
                },

                async uploadFile(event) {
                    const file = event.target.files[0];
                    if (!file) return;

                    if (!file.type.match('image.*')) {
                        alert('Please select an image file (JPG, PNG, WEBP, HEIC).');
                        return;
                    }

                    if (file.size > 10 * 1024 * 1024) {
                        alert('Image size exceeds 10MB limit.');
                        return;
                    }

                    this.isUploading = true;

                    const formData = new FormData();
                    formData.append('photo', file);
                    formData.append('_token', '{{ csrf_token() }}');

                    try {
                        const response = await fetch(config.uploadUrl, {
                            method: 'POST',
                            body: formData,
                            headers: {
                                'Accept': 'application/json',
                                'X-Requested-With': 'XMLHttpRequest'
                            }
                        });

                        const data = await response.json();
                        if (response.ok && data.success) {
                            this.photo = data.path;
                            this.photoUrl = data.url;

                            window.dispatchEvent(new CustomEvent('evidence-photo-updated', {
                                detail: {
                                    fieldName: config.photoName,
                                    path: data.path,
                                    url: data.url,
                                    remark: this.remark
                                }
                            }));
                        } else {
                            alert(data.message || 'Failed to upload photo.');
                        }
                    } catch (err) {
                        alert('Network error while uploading photo.');
                    } finally {
                        this.isUploading = false;
                        event.target.value = '';
                    }
                },

                removePhoto() {
                    this.photo = '';
                    this.photoUrl = '';
                    window.dispatchEvent(new CustomEvent('evidence-photo-updated', {
                        detail: {
                            fieldName: config.photoName,
                            path: '',
                            url: '',
                            remark: this.remark
                        }
                    }));
                }
            };
        }

        function evidenceReviewSection() {
            return {
                filter: 'all',
                items: @json($evidenceSummary['items'] ?? []),
                stats: {
                    totalRemarks: {{ $evidenceSummary['total_remarks'] }},
                    photosAttached: {{ $evidenceSummary['photos_attached'] }},
                    missingPhotos: {{ $evidenceSummary['missing_photos'] }}
                },
                activeUploadItem: null,
                pickerModalOpen: false,
                isUploading: false,

                init() {
                    window.addEventListener('evidence-photo-updated', (e) => {
                        const detail = e.detail;
                        const found = this.items.find(i => i.field_name === detail.fieldName);
                        if (found) {
                            found.evidence_photo = detail.path;
                            found.photo_url = detail.url;
                            found.has_photo = !!detail.path;
                            found.missing_photo = !!found.remarks && !detail.path;
                        }
                        this.recalcStats();
                    });
                },

                filteredItems() {
                    if (this.filter === 'missing') {
                        return this.items.filter(i => i.missing_photo);
                    }
                    if (this.filter === 'attached') {
                        return this.items.filter(i => i.has_photo);
                    }
                    return this.items;
                },

                recalcStats() {
                    let total = 0;
                    let attached = 0;
                    let missing = 0;
                    this.items.forEach(i => {
                        if (i.remarks && i.remarks.trim().length > 0) total++;
                        if (i.has_photo) attached++;
                        if (i.missing_photo) missing++;
                    });
                    this.stats.totalRemarks = total;
                    this.stats.photosAttached = attached;
                    this.stats.missingPhotos = missing;
                },

                openPhotoSourcePicker(item) {
                    this.activeUploadItem = item;
                    this.pickerModalOpen = true;
                },

                triggerReviewUpload(item) {
                    this.openPhotoSourcePicker(item);
                },

                takePhotoWithCamera() {
                    this.pickerModalOpen = false;
                    this.$nextTick(() => {
                        if (this.$refs.cameraFileInput) {
                            this.$refs.cameraFileInput.click();
                        }
                    });
                },

                selectFromGallery() {
                    this.pickerModalOpen = false;
                    this.$nextTick(() => {
                        if (this.$refs.galleryFileInput) {
                            this.$refs.galleryFileInput.click();
                        }
                    });
                },

                async handleReviewFileSelected(e) {
                    const file = e.target.files[0];
                    if (!file || !this.activeUploadItem) return;

                    this.isUploading = true;
                    const formData = new FormData();
                    formData.append('photo', file);
                    formData.append('_token', '{{ csrf_token() }}');

                    try {
                        const res = await fetch('{{ route('applications.checklist.upload_photo', $application) }}', {
                            method: 'POST',
                            body: formData,
                            headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' }
                        });
                        const data = await res.json();
                        if (res.ok && data.success) {
                            this.activeUploadItem.evidence_photo = data.path;
                            this.activeUploadItem.photo_url = data.url;
                            this.activeUploadItem.has_photo = true;
                            this.activeUploadItem.missing_photo = false;
                            this.recalcStats();

                            // Sync into hidden input in the form if present
                            const input = document.querySelector(`input[name="${this.activeUploadItem.field_name}"]`);
                            if (input) {
                                input.value = data.path;
                            }
                        } else {
                            alert(data.message || 'Failed to upload photo.');
                        }
                    } catch (err) {
                        alert('Network error while uploading photo.');
                    } finally {
                        this.isUploading = false;
                        e.target.value = '';
                        this.activeUploadItem = null;
                    }
                },

                removeReviewPhoto(item) {
                    item.evidence_photo = null;
                    item.photo_url = null;
                    item.has_photo = false;
                    item.missing_photo = !!item.remarks;
                    this.recalcStats();

                    const input = document.querySelector(`input[name="${item.field_name}"]`);
                    if (input) {
                        input.value = '';
                    }
                }
            };
        }

        function checklistApp() {
            return {
                currentTab: '{{ $activeTab ?? (empty($checklist->inspection_dates) ? "0" : "1") }}',
                rooms: @json($checklist->section3_rooms ?? []),
                enrolments: @json($checklist->section5_enrolment ?? []).map(r => {
                    const b = parseInt(r.boys) || 0;
                    const g = parseInt(r.girls) || 0;
                    const tot = (parseInt(r.total) || (b + g));
                    const prev = parseInt(r.last_year_total) || 0;
                    let trend = r.trend || '';
                    if (!trend && (tot > 0 || prev > 0)) {
                        trend = tot > prev ? 'Increased' : (tot < prev ? 'Decreased' : 'Stayed same');
                    }
                    return { ...r, boys: b, girls: g, total: tot, last_year_total: prev, trend: trend };
                }),
                lightboxOpen: false,
                lightboxImage: '',
                lightboxCaption: '',

                init() {
                    if (!this.rooms || this.rooms.length === 0) {
                        this.addRoomRow();
                    }
                    if (!this.enrolments || this.enrolments.length === 0) {
                        this.addEnrolmentRow();
                    }
                },

                openLightbox(url, caption) {
                    if (!url) return;
                    this.lightboxImage = url;
                    this.lightboxCaption = caption || 'Evidence Photo';
                    this.lightboxOpen = true;
                },

                getRoomPhotoUrl(room) {
                    if (room.evidence_photo_url) return room.evidence_photo_url;
                    if (room.evidence_photo) return '{{ asset('storage') }}/' + room.evidence_photo;
                    return '';
                },

                uploadRoomPhoto(index) {
                    const input = document.createElement('input');
                    input.type = 'file';
                    input.accept = 'image/jpeg,image/png,image/jpg,image/webp,image/heic';
                    input.onchange = async (e) => {
                        const file = e.target.files[0];
                        if (!file) return;
                        const formData = new FormData();
                        formData.append('photo', file);
                        formData.append('_token', '{{ csrf_token() }}');
                        try {
                            const res = await fetch('{{ route('applications.checklist.upload_photo', $application) }}', {
                                method: 'POST',
                                body: formData,
                                headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' }
                            });
                            const data = await res.json();
                            if (data.success) {
                                this.rooms[index].evidence_photo = data.path;
                                this.rooms[index].evidence_photo_url = data.url;
                            } else {
                                alert(data.message || 'Failed to upload photo.');
                            }
                        } catch (err) {
                            alert('Error uploading photo.');
                        }
                    };
                    input.click();
                },

                addRoomRow() {
                    this.rooms.push({
                        type: '',
                        count: '',
                        size_feet: '',
                        current_use: '',
                        furniture: '',
                        remarks: '',
                        evidence_photo: ''
                    });
                },

                removeRoomRow(index) {
                    if (this.rooms.length > 1) {
                        this.rooms.splice(index, 1);
                    }
                },

                addEnrolmentRow() {
                    this.enrolments.push({
                        class_division: '',
                        boys: 0,
                        girls: 0,
                        total: 0,
                        last_year_total: 0,
                        trend: ''
                    });
                },

                removeEnrolmentRow(index) {
                    if (this.enrolments.length > 1) {
                        this.enrolments.splice(index, 1);
                    }
                },

                calcEnrolmentTotal(row) {
                    const b = parseInt(row.boys) || 0;
                    const g = parseInt(row.girls) || 0;
                    row.total = b + g;
                    const prev = parseInt(row.last_year_total) || 0;
                    if (row.total === 0 && prev === 0) {
                        row.trend = '';
                    } else if (row.total > prev) {
                        row.trend = 'Increased';
                    } else if (row.total < prev) {
                        row.trend = 'Decreased';
                    } else {
                        row.trend = 'Stayed same';
                    }
                },

                calcEnrolmentTrend(row) {
                    const b = parseInt(row.boys) || 0;
                    const g = parseInt(row.girls) || 0;
                    const tot = (parseInt(row.total) || (b + g));
                    const prev = parseInt(row.last_year_total) || 0;
                    if (tot === 0 && prev === 0) {
                        return '—';
                    }
                    if (tot > prev) return 'Increased';
                    if (tot < prev) return 'Decreased';
                    return 'Stayed same';
                },

                totalBoys() {
                    return this.enrolments.reduce((sum, r) => sum + (parseInt(r.boys) || 0), 0);
                },

                totalGirls() {
                    return this.enrolments.reduce((sum, r) => sum + (parseInt(r.girls) || 0), 0);
                },

                grandTotalStudents() {
                    return this.totalBoys() + this.totalGirls();
                },

                totalLastYear() {
                    return this.enrolments.reduce((sum, r) => sum + (parseInt(r.last_year_total) || 0), 0);
                },

                overallTrend() {
                    const curr = this.grandTotalStudents();
                    const prev = this.totalLastYear();
                    if (curr === 0 && prev === 0) return '—';
                    if (curr > prev) return 'Increased';
                    if (curr < prev) return 'Decreased';
                    return 'Stayed same';
                },

                calcOverallTrend() {
                    return this.overallTrend();
                },

                getAvailableClasses() {
                    const fromEnrolment = (this.enrolments || []).map(e => (e.class_division || '').trim()).filter(Boolean);
                    const standard = ['Class 1', 'Class 2', 'Class 3', 'Class 4', 'Class 5'];
                    return Array.from(new Set([...fromEnrolment, ...standard]));
                },

                syncCurrentSection() {
                    const currentSectionInput = document.querySelector('input[name="current_section"]');
                    if (currentSectionInput) {
                        currentSectionInput.value = this.currentTab;
                    }
                },

                saveDraft() {
                    this.syncCurrentSection();
                    document.getElementById('formActionInput').value = 'save_draft';
                    this.$refs.mainForm.submit();
                },

                saveAndGoNext() {
                    this.syncCurrentSection();
                    document.getElementById('formActionInput').value = 'save_and_next';
                    this.$refs.mainForm.submit();
                },

                saveAndGoPrev() {
                    this.syncCurrentSection();
                    document.getElementById('formActionInput').value = 'save_and_prev';
                    this.$refs.mainForm.submit();
                },

                async submitChecklist() {
                    const inspectionDateInput = document.querySelector('input[name="inspection_dates"]');
                    if (inspectionDateInput && !inspectionDateInput.value.trim()) {
                        alert('Date(s) of Feasibility Inspection is required before submitting the report.');
                        this.currentTab = '0';
                        inspectionDateInput.focus();
                        return;
                    }

                    const confirmed = await window.customConfirm(
                        'Are you sure you want to submit this completed inspection report to the Super Admin?',
                        {
                            title: 'Submit Inspection Report',
                            subtext: 'Your findings, evaluations, and compliance ratings will be submitted to the Super Admin for formal review and takeover approval.',
                            confirmText: 'Yes, Submit to Super Admin',
                            cancelText: 'Cancel & Review Again',
                            type: 'success'
                        }
                    );

                    if (confirmed) {
                        document.getElementById('formActionInput').value = 'submit';
                        this.$refs.mainForm.submit();
                    }
                }
            };
        }
    </script>
</x-app-layout>