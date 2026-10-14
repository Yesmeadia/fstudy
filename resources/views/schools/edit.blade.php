<x-app-layout>
    <x-slot name="title">Edit School Profile — {{ $school->name }}</x-slot>

    <!-- Full-Width Page Canvas -->
    <div class="w-full space-y-6">
        <!-- Top Executive Header Card -->
        <div class="w-full bg-white rounded-2xl border border-slate-200/90 shadow-xs p-6 sm:p-8">
            <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4">
                <div class="space-y-1.5">
                    <div class="flex items-center gap-2.5 flex-wrap">
                        <span class="text-xs text-slate-400 font-medium">&bull; Institutional Record</span>
                    </div>
                    <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight">
                        Edit School Profile — {{ $school->name }}
                    </h1>
                    <p class="text-xs sm:text-sm text-slate-500 max-w-3xl leading-relaxed">
                        Update legal entity details, governing society registration, and physical campus location for
                        this institution.
                    </p>
                </div>

                <div class="flex items-center gap-3 shrink-0">
                    <a href="{{ route('schools.show', $school) }}"
                        class="px-4 py-2.5 text-xs font-semibold text-slate-700 bg-white border border-slate-300 rounded-xl hover:bg-slate-50 hover:border-slate-400 transition shadow-xs flex items-center gap-2">
                        <svg class="w-4 h-4 text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                        </svg>
                        <span>Cancel & View School</span>
                    </a>
                </div>
            </div>
        </div>

        <form method="POST" action="{{ route('schools.update', $school) }}" class="space-y-6">
            @csrf
            @method('PUT')

            <!-- 1. Institutional Details -->
            <div class="w-full bg-white rounded-2xl border border-slate-200/90 shadow-xs overflow-hidden">
                <div class="px-6 py-4 border-b border-slate-100 bg-slate-50/50 flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div
                            class="w-8 h-8 rounded-lg bg-indigo-100 text-indigo-700 flex items-center justify-center font-bold text-xs">
                            01
                        </div>
                        <div>
                            <h2 class="text-sm font-bold text-slate-900">Institutional Identity & Affiliation</h2>
                            <p class="text-[11px] text-slate-500">Legal school title, recognition board, and operational
                                category</p>
                        </div>
                    </div>
                </div>

                <div class="p-6 sm:p-8">
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5 text-xs">
                        <div class="sm:col-span-2 lg:col-span-2">
                            <label class="block font-semibold text-slate-700 mb-1.5">
                                Current Legal School Name <span class="text-rose-500">*</span>
                            </label>
                            <input type="text" name="name" value="{{ old('name', $school->name) }}" required
                                class="w-full text-xs rounded-xl border-slate-300 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 py-2.5 shadow-xs">
                        </div>

                        <div class="sm:col-span-2 lg:col-span-2">
                            <label class="block font-semibold text-slate-700 mb-1.5">
                                Proposed Name <span class="text-slate-400 font-normal">(Optional)</span>
                            </label>
                            <input type="text" name="proposed_name"
                                value="{{ old('proposed_name', $school->proposed_name) }}"
                                class="w-full text-xs rounded-xl border-slate-300 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 py-2.5 shadow-xs">
                        </div>

                        <div>
                            <label class="block font-semibold text-slate-700 mb-1.5">
                                School Code / Registration ID
                            </label>
                            <input type="text" name="school_code" value="{{ old('school_code', $school->school_code) }}"
                                class="w-full text-xs rounded-xl border-slate-300 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 py-2.5 shadow-xs">
                        </div>

                        <div>
                            <label class="block font-semibold text-slate-700 mb-1.5">
                                Affiliation Board <span class="text-rose-500">*</span>
                            </label>
                            <select name="board" required
                                class="w-full text-xs rounded-xl border-slate-300 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 py-2.5 shadow-xs">
                                <option value="cbse" {{ old('board', $school->board?->value ?? $school->board) === 'cbse' ? 'selected' : '' }}>CBSE</option>
                                <option value="icse" {{ old('board', $school->board?->value ?? $school->board) === 'icse' ? 'selected' : '' }}>ICSE / CISCE</option>
                                <option value="state_board" {{ old('board', $school->board?->value ?? $school->board) === 'state_board' ? 'selected' : '' }}>State Board</option>
                                <option value="ib" {{ old('board', $school->board?->value ?? $school->board) === 'ib' ? 'selected' : '' }}>IB (International)</option>
                                <option value="cambridge" {{ old('board', $school->board?->value ?? $school->board) === 'cambridge' ? 'selected' : '' }}>Cambridge (CAIE)</option>
                                <option value="other" {{ old('board', $school->board?->value ?? $school->board) === 'other' ? 'selected' : '' }}>Other</option>
                            </select>
                        </div>

                        <div>
                            <label class="block font-semibold text-slate-700 mb-1.5">
                                School Type <span class="text-rose-500">*</span>
                            </label>
                            <select name="school_type" required
                                class="w-full text-xs rounded-xl border-slate-300 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 py-2.5 shadow-xs">
                                <option value="k12" {{ old('school_type', $school->school_type?->value ?? $school->school_type) === 'k12' ? 'selected' : '' }}>K-12 Composite School</option>
                                <option value="senior_secondary" {{ old('school_type', $school->school_type?->value ?? $school->school_type) === 'senior_secondary' ? 'selected' : '' }}>Senior Secondary
                                    (Pre-Primary to 12)</option>
                                <option value="secondary" {{ old('school_type', $school->school_type?->value ?? $school->school_type) === 'secondary' ? 'selected' : '' }}>Secondary (Pre-Primary to
                                    10)</option>
                                <option value="middle" {{ old('school_type', $school->school_type?->value ?? $school->school_type) === 'middle' ? 'selected' : '' }}>Middle School (Pre-Primary to
                                    8)</option>
                                <option value="primary" {{ old('school_type', $school->school_type?->value ?? $school->school_type) === 'primary' ? 'selected' : '' }}>Primary School (Pre-Primary
                                    to 5)</option>
                                <option value="playschool" {{ old('school_type', $school->school_type?->value ?? $school->school_type) === 'playschool' ? 'selected' : '' }}>Play School</option>
                            </select>
                        </div>

                        <div>
                            <label class="block font-semibold text-slate-700 mb-1.5">
                                Medium of Instruction <span class="text-rose-500">*</span>
                            </label>
                            <input type="text" name="medium" value="{{ old('medium', $school->medium) }}" required
                                class="w-full text-xs rounded-xl border-slate-300 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 py-2.5 shadow-xs">
                        </div>

                        <div>
                            <label class="block font-semibold text-slate-700 mb-1.5">
                                Established Year
                            </label>
                            <input type="number" name="established_year"
                                value="{{ old('established_year', $school->established_year) }}"
                                class="w-full text-xs rounded-xl border-slate-300 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 py-2.5 shadow-xs">
                        </div>
                    </div>
                </div>
            </div>

            <!-- 2. Management Details -->
            <div class="w-full bg-white rounded-2xl border border-slate-200/90 shadow-xs overflow-hidden">
                <div class="px-6 py-4 border-b border-slate-100 bg-slate-50/50 flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div
                            class="w-8 h-8 rounded-lg bg-blue-100 text-blue-700 flex items-center justify-center font-bold text-xs">
                            02
                        </div>
                        <div>
                            <h2 class="text-sm font-bold text-slate-900">Management & Legal Entity Authority</h2>
                            <p class="text-[11px] text-slate-500">Trust or society governance structure and official
                                contact person</p>
                        </div>
                    </div>
                </div>

                <div class="p-6 sm:p-8">
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5 text-xs">
                        <div class="sm:col-span-2 lg:col-span-2">
                            <label class="block font-semibold text-slate-700 mb-1.5">
                                Managing Trust / Society Name <span class="text-rose-500">*</span>
                            </label>
                            <input type="text" name="management_organization"
                                value="{{ old('management_organization', $school->management_organization) }}" required
                                class="w-full text-xs rounded-xl border-slate-300 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 py-2.5 shadow-xs">
                        </div>

                        <div class="sm:col-span-2 lg:col-span-2">
                            <label class="block font-semibold text-slate-700 mb-1.5">
                                Entity Type <span class="text-rose-500">*</span>
                            </label>
                            <input type="text" name="trust_society_type"
                                value="{{ old('trust_society_type', $school->trust_society_type) }}" required
                                class="w-full text-xs rounded-xl border-slate-300 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 py-2.5 shadow-xs">
                        </div>

                        <div>
                            <label class="block font-semibold text-slate-700 mb-1.5">
                                Registration Number
                            </label>
                            <input type="text" name="registration_number"
                                value="{{ old('registration_number', $school->registration_number) }}"
                                class="w-full text-xs rounded-xl border-slate-300 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 py-2.5 shadow-xs">
                        </div>

                        <div>
                            <label class="block font-semibold text-slate-700 mb-1.5">
                                Applicant Name <span class="text-rose-500">*</span>
                            </label>
                            <input type="text" name="applicant_name"
                                value="{{ old('applicant_name', $school->applicant_name) }}" required
                                class="w-full text-xs rounded-xl border-slate-300 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 py-2.5 shadow-xs">
                        </div>

                        <div>
                            <label class="block font-semibold text-slate-700 mb-1.5">
                                Applicant Email <span class="text-rose-500">*</span>
                            </label>
                            <input type="email" name="applicant_email"
                                value="{{ old('applicant_email', $school->applicant_email) }}" required
                                class="w-full text-xs rounded-xl border-slate-300 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 py-2.5 shadow-xs">
                        </div>

                        <div>
                            <label class="block font-semibold text-slate-700 mb-1.5">
                                Applicant Phone (10-Digit) <span class="text-rose-500">*</span>
                            </label>
                            <div class="relative flex rounded-xl shadow-xs overflow-hidden border border-slate-300 focus-within:ring-2 focus-within:ring-indigo-500/20 focus-within:border-indigo-500 bg-white">
                                <span class="inline-flex items-center px-3 bg-slate-100 border-r border-slate-300 text-slate-600 font-semibold text-xs select-none">
                                    +91
                                </span>
                                <input type="tel" id="applicant_phone" name="applicant_phone"
                                    value="{{ old('applicant_phone', preg_replace('/^\+91\s*/', '', $school->applicant_phone ?? '')) }}" required
                                    maxlength="10" minlength="10" pattern="[6-9][0-9]{9}" inputmode="numeric"
                                    oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0, 10)"
                                    class="w-full text-xs border-0 py-2.5 px-3 focus:ring-0 font-mono tracking-wider"
                                    placeholder="9876543210">
                            </div>
                            <p class="text-[10px] text-slate-400 mt-1">Enter 10-digit mobile number starting with 6, 7, 8, or 9.</p>
                            @error('applicant_phone')
                                <p class="text-[11px] text-rose-500 mt-1 font-medium">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>

            <!-- 3. Geographic Address -->
            <div class="w-full bg-white rounded-2xl border border-slate-200/90 shadow-xs overflow-hidden">
                <div class="px-6 py-4 border-b border-slate-100 bg-slate-50/50 flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div
                            class="w-8 h-8 rounded-lg bg-emerald-100 text-emerald-700 flex items-center justify-center font-bold text-xs">
                            03
                        </div>
                        <div>
                            <h2 class="text-sm font-bold text-slate-900">Physical Campus Location & Address</h2>
                            <p class="text-[11px] text-slate-500">Geographic premises address and municipality mapping
                            </p>
                        </div>
                    </div>
                    <button type="button" onclick="captureSchoolGPS()"
                        class="inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-xl bg-emerald-600 hover:bg-emerald-500 text-white font-semibold text-xs shadow-xs shadow-emerald-600/20 transition active:scale-95 cursor-pointer">
                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                        <span>Use My Current Location</span>
                    </button>
                </div>

                <div class="p-6 sm:p-8">
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5 text-xs">
                        <div class="sm:col-span-2 lg:col-span-4">
                            <label class="block font-semibold text-slate-700 mb-1.5">
                                Campus Street Address <span class="text-rose-500">*</span>
                            </label>
                            <textarea name="address" id="school_address" rows="2" required
                                class="w-full text-xs rounded-xl border-slate-300 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 p-3 shadow-xs transition-all duration-300">{{ old('address', $school->address) }}</textarea>
                        </div>

                        <div>
                            <label class="block font-semibold text-slate-700 mb-1.5">
                                City <span class="text-rose-500">*</span>
                            </label>
                            <input type="text" name="city" id="school_city" value="{{ old('city', $school->city) }}"
                                required
                                class="w-full text-xs rounded-xl border-slate-300 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 py-2.5 shadow-xs transition-all duration-300">
                        </div>

                        <div>
                            <label class="block font-semibold text-slate-700 mb-1.5">
                                District <span class="text-rose-500">*</span>
                            </label>
                            <input type="text" name="district" id="school_district"
                                value="{{ old('district', $school->district) }}" required
                                class="w-full text-xs rounded-xl border-slate-300 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 py-2.5 shadow-xs transition-all duration-300">
                        </div>

                        <div>
                            <label class="block font-semibold text-slate-700 mb-1.5">
                                State <span class="text-rose-500">*</span>
                            </label>
                            <input type="text" name="state" id="school_state" value="{{ old('state', $school->state) }}"
                                required
                                class="w-full text-xs rounded-xl border-slate-300 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 py-2.5 shadow-xs transition-all duration-300">
                        </div>

                        <div>
                            <label class="block font-semibold text-slate-700 mb-1.5">
                                PIN Code <span class="text-rose-500">*</span>
                            </label>
                            <input type="text" name="pincode" id="school_pincode"
                                value="{{ old('pincode', $school->pincode) }}" required
                                class="w-full text-xs rounded-xl border-slate-300 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 py-2.5 shadow-xs transition-all duration-300">
                        </div>

                        <!-- GPS Coordinates Divider & Precision Geolocation Block -->
                        <div class="sm:col-span-2 lg:col-span-4 pt-5 mt-2 border-t border-slate-100 space-y-4">
                            <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4">
                                <div>
                                    <div class="flex items-center gap-2">
                                        <span
                                            class="inline-flex items-center justify-center w-6 h-6 rounded-lg bg-emerald-100 text-emerald-700">
                                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24"
                                                stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                            </svg>
                                        </span>
                                        <h3 class="text-xs font-bold text-slate-900">Campus GPS Geolocation & Precision
                                            Map Pinpoint</h3>
                                        <span
                                            class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                            7-Decimal Accuracy
                                        </span>
                                    </div>
                                    <p class="text-[11px] text-slate-500 mt-1">
                                        Acquire high-precision satellite coordinates via GPS sensor, locate
                                        automatically from campus address, or drag the map pin to the exact school
                                        building.
                                    </p>
                                </div>

                                <div class="flex items-center flex-wrap gap-2 shrink-0">
                                    <!-- Primary Auto-Detect GPS Button -->
                                    <button type="button" id="btn-get-gps" onclick="captureSchoolGPS()"
                                        class="inline-flex items-center justify-center gap-2 px-4 py-2.5 rounded-xl bg-gradient-to-r from-emerald-600 to-teal-600 hover:from-emerald-500 hover:to-teal-500 text-white font-bold text-xs shadow-md shadow-emerald-600/20 hover:shadow-emerald-600/30 transition active:scale-[0.98] cursor-pointer">
                                        <svg id="gps-btn-icon" class="w-4 h-4" fill="none" viewBox="0 0 24 24"
                                            stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                        </svg>
                                        <span id="btn-get-gps-text">Use My Current Location</span>
                                    </button>

                                    <!-- Geocode Address Button -->
                                    <button type="button" id="btn-geocode-address" onclick="geocodeCampusAddress()"
                                        class="inline-flex items-center justify-center gap-1.5 px-3.5 py-2.5 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold text-xs border border-slate-300/80 transition active:scale-[0.98] cursor-pointer">
                                        <svg class="w-3.5 h-3.5 text-indigo-600" fill="none" viewBox="0 0 24 24"
                                            stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                        </svg>
                                        <span>Locate from Address</span>
                                    </button>

                                    <!-- Quick Paste Toggle Button -->
                                    <button type="button" onclick="toggleQuickPaste()"
                                        class="inline-flex items-center justify-center gap-1.5 px-3 py-2.5 rounded-xl bg-white hover:bg-slate-50 text-slate-600 font-medium text-xs border border-slate-300 transition">
                                        <svg class="w-3.5 h-3.5 text-slate-500" fill="none" viewBox="0 0 24 24"
                                            stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                        </svg>
                                        <span>Paste Link / Lat-Lng</span>
                                    </button>
                                </div>
                            </div>

                            <!-- Quick Paste Input Bar (Collapsible) -->
                            <div id="quick-paste-container"
                                class="hidden p-3 bg-slate-50 border border-slate-200 rounded-xl space-y-2">
                                <div class="flex items-center justify-between">
                                    <label class="text-[11px] font-semibold text-slate-700">Paste Coordinates or Google
                                        Maps Link</label>
                                    <button type="button" onclick="toggleQuickPaste()"
                                        class="text-[11px] text-slate-400 hover:text-slate-600">&times; Close</button>
                                </div>
                                <div class="flex gap-2">
                                    <input type="text" id="quick-paste-input"
                                        placeholder="e.g. 11.1824150, 75.8647200 or https://maps.app.goo.gl/..."
                                        class="flex-1 text-xs rounded-lg border-slate-300 py-1.5 px-3 font-mono">
                                    <button type="button" onclick="parseQuickPaste()"
                                        class="px-3 py-1.5 bg-indigo-600 text-white rounded-lg text-xs font-semibold hover:bg-indigo-500 transition">
                                        Apply Coordinates
                                    </button>
                                </div>
                            </div>

                            <!-- Live Feedback & Status Alert (Dynamic) -->
                            <div id="gps-status-container"
                                class="hidden p-3.5 rounded-xl text-xs transition-all duration-200">
                                <div class="flex items-start gap-2.5">
                                    <span id="gps-status-icon" class="shrink-0 mt-0.5"></span>
                                    <div class="flex-1">
                                        <p id="gps-status-message" class="font-medium"></p>
                                        <p id="gps-status-subtext" class="text-[11px] mt-0.5 opacity-90"></p>
                                    </div>
                                </div>
                            </div>

                            <!-- Coordinates Input Grid -->
                            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
                                <div>
                                    <label class="block font-semibold text-slate-700 mb-1.5">
                                        GPS Latitude <span class="text-slate-400 font-normal">(Decimal Degrees)</span>
                                    </label>
                                    <div class="relative">
                                        <input type="number" step="0.0000001" name="latitude" id="school_latitude"
                                            value="{{ old('latitude', $school->latitude) }}"
                                            oninput="onManualCoordinateChange()"
                                            class="w-full text-xs rounded-xl border-slate-300 focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 py-2.5 pl-3 pr-8 shadow-xs font-mono"
                                            placeholder="e.g. 11.2587531">
                                        <span
                                            class="absolute right-3 top-2.5 text-slate-400 text-[10px] font-bold">°N</span>
                                    </div>
                                </div>

                                <div>
                                    <label class="block font-semibold text-slate-700 mb-1.5">
                                        GPS Longitude <span class="text-slate-400 font-normal">(Decimal Degrees)</span>
                                    </label>
                                    <div class="relative">
                                        <input type="number" step="0.0000001" name="longitude" id="school_longitude"
                                            value="{{ old('longitude', $school->longitude) }}"
                                            oninput="onManualCoordinateChange()"
                                            class="w-full text-xs rounded-xl border-slate-300 focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 py-2.5 pl-3 pr-8 shadow-xs font-mono"
                                            placeholder="e.g. 75.7804100">
                                        <span
                                            class="absolute right-3 top-2.5 text-slate-400 text-[10px] font-bold">°E</span>
                                    </div>
                                </div>

                                <!-- Live Coordinates Display & Google Maps Quick Link -->
                                <div class="sm:col-span-2 lg:col-span-2 flex flex-col justify-end">
                                    <div id="gps-preview-card"
                                        class="p-3 bg-slate-50 border border-slate-200/80 rounded-xl flex items-center justify-between gap-3">
                                        <div class="flex items-center gap-2.5">
                                            <div
                                                class="w-8 h-8 rounded-lg bg-emerald-500/10 text-emerald-600 flex items-center justify-center shrink-0">
                                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24"
                                                    stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7" />
                                                </svg>
                                            </div>
                                            <div>
                                                <div class="text-[11px] font-semibold text-slate-700">GPS Coordinates
                                                    Status</div>
                                                <div id="gps-display-text"
                                                    class="text-xs font-mono font-bold text-slate-900">
                                                    No coordinates locked
                                                </div>
                                            </div>
                                        </div>

                                        <a id="gps-maps-link" href="#" target="_blank" rel="noopener noreferrer"
                                            class="hidden px-3 py-1.5 rounded-lg bg-indigo-50 hover:bg-indigo-100 text-indigo-700 border border-indigo-200 text-[11px] font-semibold items-center gap-1.5 transition shrink-0">
                                            <span>Open Map</span>
                                            <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                                            </svg>
                                        </a>
                                    </div>
                                </div>
                            </div>

                            <!-- Interactive Google Maps & MapCN Styled Canvas -->
                            <div id="campus-map-wrapper"
                                class="relative mt-3 rounded-2xl border border-slate-200 shadow-sm overflow-hidden bg-slate-900 group">
                                <!-- Top Professional Toolbar Strip (Google Maps & MapCN) -->
                                <div
                                    class="flex flex-wrap items-center justify-between gap-3 px-3.5 py-2.5 bg-slate-900 border-b border-slate-800 text-white">
                                    <!-- Active Map Mode -->
                                    <div class="flex items-center gap-2">
                                        <span
                                            class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg bg-emerald-500/10 text-emerald-400 text-xs font-semibold border border-emerald-500/20">
                                            <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                                            <span>Google Satellite</span>
                                        </span>
                                    </div>

                                    <!-- Quick Actions: Recenter & Fullscreen -->
                                    <div class="flex items-center gap-2">
                                        <button type="button" onclick="recenterOnMarker()" title="Recenter Pin"
                                            class="px-2.5 py-1.5 rounded-lg bg-slate-800 hover:bg-slate-700 text-slate-200 text-xs font-medium flex items-center gap-1.5 transition active:scale-95 cursor-pointer border border-slate-700">
                                            <svg class="w-3.5 h-3.5 text-emerald-400" fill="none" viewBox="0 0 24 24"
                                                stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                            </svg>
                                            <span>Recenter</span>
                                        </button>
                                        <button type="button" onclick="toggleMapFullscreen()" id="btn-map-fullscreen"
                                            title="Toggle Fullscreen Map"
                                            class="px-2.5 py-1.5 rounded-lg bg-slate-800 hover:bg-slate-700 text-slate-200 text-xs font-medium flex items-center gap-1.5 transition active:scale-95 cursor-pointer border border-slate-700">
                                            <svg class="w-3.5 h-3.5 text-indigo-400" fill="none" viewBox="0 0 24 24"
                                                stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M4 8V4m0 0h4M4 4l5 5m11-1V4m0 0h-4m4 0l-5 5M4 16v4m0 0h4m-4 0l5-5m11 5l-5-5m5 5v-4m0 4h-4" />
                                            </svg>
                                            <span id="fullscreen-btn-text">Fullscreen</span>
                                        </button>
                                    </div>
                                </div>

                                <!-- The Map Canvas Container -->
                                <div id="campus-map" style="width: 100%; min-height: 420px; height: 420px;"
                                    class="relative z-0"></div>

                                <!-- Bottom Footer Strip HUD -->
                                <div
                                    class="flex flex-wrap items-center justify-between gap-2 px-3.5 py-2 bg-slate-900/95 border-t border-slate-800 text-[11px] text-slate-400 font-mono">
                                    <div class="flex items-center gap-3">
                                        <span class="text-emerald-400 flex items-center gap-1.5 font-semibold">
                                            <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                                            <span id="active-map-provider-label">Google Satellite</span>
                                        </span>
                                        <span class="text-slate-600">|</span>
                                        <span id="map-live-coords" class="text-slate-300">No coordinates locked</span>
                                    </div>
                                    <div class="flex items-center gap-3">
                                        <span id="map-zoom-hud">Zoom: 17x</span>
                                        <span class="text-slate-600 hidden sm:inline">|</span>
                                        <span class="text-slate-400 text-[10px] hidden sm:inline">Drag pin to fine-tune
                                            exact gate coordinates</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Action Controls -->
            <div
                class="w-full bg-white rounded-2xl border border-slate-200/90 p-5 shadow-xs flex items-center justify-between gap-4">
                <a href="{{ route('schools.show', $school) }}"
                    class="px-5 py-2.5 text-xs font-semibold text-slate-700 bg-white border border-slate-300 rounded-xl hover:bg-slate-50 transition">
                    Cancel & Return
                </a>
                <button type="submit"
                    class="px-7 py-3 text-xs font-bold text-white bg-indigo-600 hover:bg-indigo-500 rounded-xl shadow-lg shadow-indigo-600/25 hover:shadow-indigo-600/35 transition flex items-center gap-2">
                    <span>Update School Profile</span>
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                </button>
            </div>
        </form>
    </div>

    @push('styles')
        <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
        <style>
            .leaflet-container {
                font-family: inherit;
                background-color: #0f172a;
            }

            .leaflet-popup-content-wrapper {
                border-radius: 0.875rem;
                box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.2), 0 8px 10px -6px rgba(0, 0, 0, 0.1);
                border: 1px solid rgba(226, 232, 240, 0.9);
                padding: 2px;
            }

            .leaflet-popup-content {
                font-size: 11px;
                line-height: 1.45;
                margin: 10px 14px;
            }

            .mapcn-custom-pin-wrapper {
                background: transparent !important;
                border: none !important;
            }

            #campus-map-wrapper.map-fullscreen-active {
                position: fixed !important;
                inset: 0 !important;
                z-index: 99999 !important;
                border-radius: 0 !important;
                margin: 0 !important;
                height: 100vh !important;
                width: 100vw !important;
                background: #0f172a !important;
            }

            #campus-map-wrapper.map-fullscreen-active #campus-map {
                height: calc(100vh - 45px) !important;
            }
        </style>
    @endpush

    @push('scripts')
        <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
        <script>
            let mapInstance = null;
            let markerInstance = null;
            let accuracyCircle = null;
            let watchId = null;
            let bestFix = null;
            let currentTileLayer = null;

            // Google Satellite Tile Provider
            const googleSatelliteProvider = {
                name: 'Google Satellite',
                url: 'https://mt1.google.com/vt/lyrs=y&x={x}&y={y}&z={z}',
                options: { maxZoom: 22, subdomains: ['mt0', 'mt1', 'mt2', 'mt3'], attribution: '&copy; Google Maps' }
            };

            function createMapcnPinIcon() {
                return L.divIcon({
                    className: 'mapcn-custom-pin-wrapper',
                    html: `
                        <div class="relative flex items-center justify-center -translate-x-1/2 -translate-y-full cursor-grab active:cursor-grabbing">
                            <!-- Pulsing radar ring (MapCN signature) -->
                            <span class="absolute w-9 h-9 rounded-full bg-emerald-500/40 animate-ping pointer-events-none" style="bottom:-4px;"></span>
                            <span class="absolute w-3.5 h-3.5 rounded-full bg-emerald-600/70 ring-2 ring-white" style="bottom:-2px;"></span>

                            <!-- Precision Pin Drop Body -->
                            <div class="relative z-10 flex items-center justify-center w-9 h-11 drop-shadow-xl transition-transform hover:scale-110 active:scale-95">
                                <svg class="w-9 h-11 text-emerald-600 fill-current filter drop-shadow-md" viewBox="0 0 24 32">
                                    <path d="M12 0C5.373 0 0 5.373 0 12c0 9.5 12 20 12 20s12-10.5 12-20c0-6.627-5.373-12-12-12zm0 17c-2.761 0-5-2.239-5-5s2.239-5 5-5 5 2.239 5 5-2.239 5-5 5z"/>
                                </svg>
                                <div class="absolute top-2.5 w-4 h-4 rounded-full bg-white flex items-center justify-center shadow-xs">
                                    <svg class="w-2.5 h-2.5 text-emerald-700" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z"/>
                                    </svg>
                                </div>
                            </div>
                        </div>
                    `,
                    iconSize: [0, 0],
                    iconAnchor: [0, 0],
                    popupAnchor: [0, -38]
                });
            }

            function recenterOnMarker() {
                if (!mapInstance) return;
                const latInput = document.getElementById('school_latitude');
                const lngInput = document.getElementById('school_longitude');
                const lat = parseFloat(latInput.value);
                const lng = parseFloat(lngInput.value);

                if (!isNaN(lat) && !isNaN(lng)) {
                    mapInstance.flyTo([lat, lng], Math.max(mapInstance.getZoom(), 17), { duration: 1.2 });
                    if (markerInstance) markerInstance.openPopup();
                } else {
                    mapInstance.flyTo([20.5937, 78.9629], 5, { duration: 1.2 });
                }
            }

            function toggleMapFullscreen() {
                const wrapper = document.getElementById('campus-map-wrapper');
                const isFull = wrapper.classList.toggle('map-fullscreen-active');

                if (isFull) {
                    document.body.style.overflow = 'hidden';
                } else {
                    document.body.style.overflow = '';
                }

                setTimeout(() => {
                    if (mapInstance) mapInstance.invalidateSize();
                }, 250);
            }

            // Close fullscreen on Escape key
            document.addEventListener('keydown', function (e) {
                if (e.key === 'Escape') {
                    const wrapper = document.getElementById('campus-map-wrapper');
                    if (wrapper && wrapper.classList.contains('map-fullscreen-active')) {
                        toggleMapFullscreen();
                    }
                }
            });

            function initCampusMap() {
                const latInput = document.getElementById('school_latitude');
                const lngInput = document.getElementById('school_longitude');
                const mapContainer = document.getElementById('campus-map');
                if (!mapContainer || typeof L === 'undefined') return;

                let initialLat = parseFloat(latInput.value);
                let initialLng = parseFloat(lngInput.value);
                let hasValidCoords = !isNaN(initialLat) && !isNaN(initialLng) && initialLat !== 0 && initialLng !== 0;

                let center = hasValidCoords ? [initialLat, initialLng] : [20.5937, 78.9629];
                let zoom = hasValidCoords ? 17 : 5;

                mapInstance = L.map('campus-map', {
                    center: center,
                    zoom: zoom,
                    zoomControl: true,
                    attributionControl: true
                });

                // Apply initial map style (defaults to Google Satellite or user preference)
                // Apply Google Satellite tile layer
                currentTileLayer = L.tileLayer(googleSatelliteProvider.url, googleSatelliteProvider.options).addTo(mapInstance);

                const updateHUD = (lat, lng) => {
                    const coordsEl = document.getElementById('map-live-coords');
                    const zoomEl = document.getElementById('map-zoom-hud');
                    if (coordsEl) {
                        coordsEl.textContent = (!isNaN(lat) && !isNaN(lng))
                            ? `${parseFloat(lat).toFixed(6)}° N, ${parseFloat(lng).toFixed(6)}° E`
                            : 'No coordinates';
                    }
                    if (zoomEl && mapInstance) zoomEl.textContent = `Zoom: ${mapInstance.getZoom()}x`;
                };

                mapInstance.on('zoomend', function () {
                    const zoomEl = document.getElementById('map-zoom-hud');
                    if (zoomEl) zoomEl.textContent = `Zoom: ${mapInstance.getZoom()}x`;
                });

                mapInstance.on('mousemove', function (e) {
                    const coordsEl = document.getElementById('map-live-coords');
                    if (coordsEl && e.latlng) {
                        coordsEl.textContent = `${e.latlng.lat.toFixed(6)}° N, ${e.latlng.lng.toFixed(6)}° E`;
                    }
                });

                if (hasValidCoords) {
                    placeOrUpdateMarker(initialLat, initialLng, false);
                    updateHUD(initialLat, initialLng);
                }

                mapInstance.on('click', function (e) {
                    const lat = parseFloat(e.latlng.lat).toFixed(7);
                    const lng = parseFloat(e.latlng.lng).toFixed(7);
                    latInput.value = lat;
                    lngInput.value = lng;
                    placeOrUpdateMarker(e.latlng.lat, e.latlng.lng, true);
                    updateGPSPreview();
                    updateHUD(lat, lng);
                    debouncedReverseGeocode(lat, lng);
                });
            }

            function placeOrUpdateMarker(lat, lng, pan = true) {
                if (!mapInstance || typeof L === 'undefined') return;

                const formattedLat = parseFloat(lat).toFixed(7);
                const formattedLng = parseFloat(lng).toFixed(7);

                const popupHtml = `
                    <div class="space-y-1.5 font-sans">
                        <div class="flex items-center gap-1.5 font-bold text-slate-900 text-xs">
                            <span class="w-2 h-2 rounded-full bg-emerald-500 animate-ping"></span>
                            <span>Campus Location</span>
                        </div>
                        <div class="font-mono text-[11px] text-slate-600 bg-slate-50 px-2 py-1 rounded border border-slate-200">
                            ${formattedLat}° N, ${formattedLng}° E
                        </div>
                        <p class="text-[10px] text-slate-500">Drag this pin to fine-tune the exact school building or gate.</p>
                        <div class="pt-1.5 border-t border-slate-100 flex items-center gap-2">
                            <a href="https://www.google.com/maps?q=${formattedLat},${formattedLng}" target="_blank" rel="noopener noreferrer"
                                class="text-[10px] text-indigo-600 hover:text-indigo-800 font-semibold inline-flex items-center gap-0.5">
                                Google Maps &nearr;
                            </a>
                            <span class="text-slate-300">|</span>
                            <a href="https://earth.google.com/web/search/${formattedLat},${formattedLng}" target="_blank" rel="noopener noreferrer"
                                class="text-[10px] text-emerald-600 hover:text-emerald-800 font-semibold inline-flex items-center gap-0.5">
                                Google Earth 3D &nearr;
                            </a>
                        </div>
                    </div>
                `;

                if (markerInstance) {
                    markerInstance.setLatLng([lat, lng]);
                } else {
                    markerInstance = L.marker([lat, lng], {
                        draggable: true,
                        icon: createMapcnPinIcon(),
                        title: "School Location Pin"
                    }).addTo(mapInstance);

                    markerInstance.on('dragend', function (e) {
                        const pos = e.target.getLatLng();
                        const fLat = parseFloat(pos.lat).toFixed(7);
                        const fLng = parseFloat(pos.lng).toFixed(7);
                        document.getElementById('school_latitude').value = fLat;
                        document.getElementById('school_longitude').value = fLng;
                        updateGPSPreview();
                        showStatusMessage('success', 'Pin Adjusted', `Campus coordinates updated to ${fLat}° N, ${fLng}° E.`);
                        if (accuracyCircle) {
                            mapInstance.removeLayer(accuracyCircle);
                            accuracyCircle = null;
                        }
                        debouncedReverseGeocode(fLat, fLng);
                    });
                }

                markerInstance.bindPopup(popupHtml);

                if (pan) {
                    mapInstance.setView([lat, lng], Math.max(mapInstance.getZoom(), 17));
                    markerInstance.openPopup();
                }

                const coordsEl = document.getElementById('map-live-coords');
                if (coordsEl) coordsEl.textContent = `${formattedLat}° N, ${formattedLng}° E`;
            }

            function onManualCoordinateChange() {
                const latInput = document.getElementById('school_latitude');
                const lngInput = document.getElementById('school_longitude');
                const lat = parseFloat(latInput.value);
                const lng = parseFloat(lngInput.value);

                updateGPSPreview();

                if (!isNaN(lat) && !isNaN(lng) && lat >= -90 && lat <= 90 && lng >= -180 && lng <= 180) {
                    placeOrUpdateMarker(lat, lng, true);
                }
            }

            function updateGPSPreview() {
                const latInput = document.getElementById('school_latitude');
                const lngInput = document.getElementById('school_longitude');
                const displayText = document.getElementById('gps-display-text');
                const mapsLink = document.getElementById('gps-maps-link');
                const previewCard = document.getElementById('gps-preview-card');

                const latVal = latInput ? latInput.value.trim() : '';
                const lngVal = lngInput ? lngInput.value.trim() : '';

                if (latVal && lngVal && !isNaN(latVal) && !isNaN(lngVal)) {
                    const formattedLat = parseFloat(latVal).toFixed(7);
                    const formattedLng = parseFloat(lngVal).toFixed(7);
                    displayText.innerHTML = `<span class="text-emerald-700 font-semibold">${formattedLat}° N, ${formattedLng}° E</span>`;
                    mapsLink.href = `https://www.google.com/maps?q=${encodeURIComponent(formattedLat)},${encodeURIComponent(formattedLng)}`;
                    mapsLink.classList.remove('hidden');
                    mapsLink.classList.add('inline-flex');
                    previewCard.classList.remove('bg-slate-50', 'border-slate-200/80');
                    previewCard.classList.add('bg-emerald-50/60', 'border-emerald-200');
                } else {
                    displayText.textContent = 'No coordinates locked';
                    mapsLink.classList.add('hidden');
                    mapsLink.classList.remove('inline-flex');
                    previewCard.classList.add('bg-slate-50', 'border-slate-200/80');
                    previewCard.classList.remove('bg-emerald-50/60', 'border-emerald-200');
                }
            }

            function showStatusMessage(type, message, subtext) {
                const statusBox = document.getElementById('gps-status-container');
                const statusMsg = document.getElementById('gps-status-message');
                const statusSub = document.getElementById('gps-status-subtext');
                const statusIcon = document.getElementById('gps-status-icon');

                statusBox.classList.remove('hidden');
                statusMsg.innerHTML = message;
                statusSub.innerHTML = subtext || '';

                if (type === 'success') {
                    statusBox.className = 'p-3.5 rounded-xl text-xs bg-emerald-50 text-emerald-900 border border-emerald-200 block';
                    statusIcon.innerHTML = `<svg class="w-4 h-4 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>`;
                } else if (type === 'loading') {
                    statusBox.className = 'p-3.5 rounded-xl text-xs bg-indigo-50 text-indigo-900 border border-indigo-200 block';
                    statusIcon.innerHTML = `<svg class="w-4 h-4 text-indigo-600 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path></svg>`;
                } else if (type === 'warning') {
                    statusBox.className = 'p-3.5 rounded-xl text-xs bg-amber-50 text-amber-900 border border-amber-200 block';
                    statusIcon.innerHTML = `<svg class="w-4 h-4 text-amber-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>`;
                } else {
                    statusBox.className = 'p-3.5 rounded-xl text-xs bg-rose-50 text-rose-800 border border-rose-200 block';
                    statusIcon.innerHTML = `<svg class="w-4 h-4 text-rose-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>`;
                }
            }

            function captureSchoolGPS() {
                const btn = document.getElementById('btn-get-gps');
                const btnText = document.getElementById('btn-get-gps-text');
                const latInput = document.getElementById('school_latitude');
                const lngInput = document.getElementById('school_longitude');

                // Smooth scroll to map area
                const mapWrapper = document.getElementById('campus-map-wrapper');
                if (mapWrapper && window.innerWidth >= 640) {
                    mapWrapper.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
                }

                if (!navigator.geolocation) {
                    showStatusMessage(
                        'warning',
                        'Browser Geolocation Unavailable',
                        'Device GPS is only accessible over HTTPS or localhost. You can click "Locate from Address" or click directly on the satellite map below to position the pin.'
                    );
                    return;
                }

                if (watchId !== null) {
                    navigator.geolocation.clearWatch(watchId);
                    watchId = null;
                }

                if (btn) {
                    btn.disabled = true;
                    btn.classList.add('opacity-75', 'cursor-wait');
                }
                if (btnText) btnText.textContent = 'Calibrating Satellite Fix...';
                showStatusMessage('loading', 'Connecting to GPS Satellites...', 'Requesting high-precision coordinate telemetry. Please allow location access if prompted.');

                bestFix = null;
                let sampleCount = 0;

                const finalizeFix = () => {
                    if (watchId !== null) {
                        navigator.geolocation.clearWatch(watchId);
                        watchId = null;
                    }
                    if (btn) {
                        btn.disabled = false;
                        btn.classList.remove('opacity-75', 'cursor-wait');
                    }
                    if (btnText) btnText.textContent = 'Update GPS Fix';

                    if (bestFix) {
                        const lat = bestFix.coords.latitude.toFixed(7);
                        const lng = bestFix.coords.longitude.toFixed(7);
                        const acc = Math.round(bestFix.coords.accuracy);

                        latInput.value = lat;
                        lngInput.value = lng;
                        updateGPSPreview();
                        placeOrUpdateMarker(bestFix.coords.latitude, bestFix.coords.longitude, true);

                        if (accuracyCircle && mapInstance) {
                            mapInstance.removeLayer(accuracyCircle);
                        }
                        if (mapInstance && typeof L !== 'undefined') {
                            accuracyCircle = L.circle([bestFix.coords.latitude, bestFix.coords.longitude], {
                                radius: acc,
                                color: '#10b981',
                                fillColor: '#10b981',
                                fillOpacity: 0.15,
                                weight: 1.5
                            }).addTo(mapInstance);
                        }

                        showStatusMessage('loading', `<strong>GPS Locked:</strong> ${lat}° N, ${lng}° E (±${acc}m)`, 'Reverse geocoding Indian postal address for PIN Code, State, District, and City...');
                        debouncedReverseGeocode(lat, lng, acc);
                    }
                };

                const timeoutTimer = setTimeout(() => {
                    if (bestFix) {
                        finalizeFix();
                    } else {
                        navigator.geolocation.getCurrentPosition(
                            (pos) => {
                                bestFix = pos;
                                finalizeFix();
                            },
                            (err) => {
                                if (btn) {
                                    btn.disabled = false;
                                    btn.classList.remove('opacity-75', 'cursor-wait');
                                }
                                if (btnText) btnText.textContent = 'Use My Current Location';
                                showStatusMessage('warning', 'GPS Signal Weak or Laptop Wi-Fi Geolocation Timed Out', 'Laptops without cellular/GPS hardware may take longer or fail. You can click <strong>"Locate from Address"</strong> or click directly anywhere on the satellite map below to position the pin.');
                            },
                            { enableHighAccuracy: false, timeout: 8000 }
                        );
                    }
                }, 7000);

                try {
                    watchId = navigator.geolocation.watchPosition(
                        function (position) {
                            sampleCount++;
                            const currentAcc = position.coords.accuracy;

                            if (!bestFix || currentAcc < bestFix.coords.accuracy) {
                                bestFix = position;
                                const lat = position.coords.latitude.toFixed(7);
                                const lng = position.coords.longitude.toFixed(7);
                                latInput.value = lat;
                                lngInput.value = lng;
                                updateGPSPreview();
                                placeOrUpdateMarker(position.coords.latitude, position.coords.longitude, false);

                                showStatusMessage('loading', `Acquiring Precision Lock... (±${Math.round(currentAcc)}m)`, `Refining coordinate convergence. Telemetry reading #${sampleCount}...`);
                            }

                            if (currentAcc <= 10) {
                                clearTimeout(timeoutTimer);
                                finalizeFix();
                            }
                        },
                        function (error) {
                            clearTimeout(timeoutTimer);
                            if (btn) {
                                btn.disabled = false;
                                btn.classList.remove('opacity-75', 'cursor-wait');
                            }
                            if (btnText) btnText.textContent = 'Use My Current Location';

                            let msg = 'Unable to acquire location.';
                            let sub = 'Please use the map pin or locate by address below.';

                            if (error.code === error.PERMISSION_DENIED) {
                                msg = 'Location permission was denied in your browser.';
                                sub = 'Click the lock/settings icon in your browser URL bar to allow Location access, or drag the marker directly onto your campus building on the map below.';
                            } else if (error.code === error.POSITION_UNAVAILABLE) {
                                msg = 'GPS satellite telemetry unavailable on this device.';
                                sub = 'Standard for laptops/desktop PCs without hardware GPS. Click "Locate from Address" or click on the map to place the campus pin.';
                            } else if (error.code === error.TIMEOUT) {
                                msg = 'GPS satellite request timed out.';
                                sub = 'Try again or click "Locate from Address" to pinpoint campus premises.';
                            }

                            showStatusMessage('warning', msg, sub);
                        },
                        {
                            enableHighAccuracy: true,
                            timeout: 10000,
                            maximumAge: 0
                        }
                    );
                } catch (e) {
                    clearTimeout(timeoutTimer);
                    if (btn) {
                        btn.disabled = false;
                        btn.classList.remove('opacity-75', 'cursor-wait');
                    }
                }
            }

            let reverseGeocodeAbortController = null;
            let reverseGeocodeDebounceTimer = null;
            let lastGeocodeSearchTimestamp = 0;

            function debouncedReverseGeocode(lat, lng, acc) {
                if (reverseGeocodeDebounceTimer) {
                    clearTimeout(reverseGeocodeDebounceTimer);
                }
                reverseGeocodeDebounceTimer = setTimeout(() => {
                    reverseGeocodeBackend(lat, lng, acc);
                }, 550);
            }

            // Backend Reverse Geocoding: Sends coordinates to backend & auto-populates Indian address fields
            function reverseGeocodeBackend(lat, lng, acc) {
                if (reverseGeocodeAbortController) {
                    reverseGeocodeAbortController.abort();
                }
                reverseGeocodeAbortController = new AbortController();

                fetch('{{ route("geocode.reverse") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    signal: reverseGeocodeAbortController.signal,
                    body: JSON.stringify({
                        latitude: parseFloat(lat),
                        longitude: parseFloat(lng)
                    })
                })
                    .then(res => {
                        if (res.status === 429) {
                            showStatusMessage('warning', 'Rate Limit Reached', 'Too many coordinate queries within a short time. Please wait a minute or enter address fields manually.');
                            return null;
                        }
                        return res.json();
                    })
                    .then(res => {
                        if (!res) return;
                        if (res && res.success && res.data) {
                            const d = res.data;
                            const pincodeEl = document.getElementById('school_pincode');
                            const stateEl = document.getElementById('school_state');
                            const districtEl = document.getElementById('school_district');
                            const cityEl = document.getElementById('school_city');
                            const addressEl = document.getElementById('school_address');

                            const applyWithHighlight = (el, val) => {
                                if (!el || !val) return;
                                el.value = val;
                                el.classList.add('ring-2', 'ring-emerald-500', 'bg-emerald-50/70');
                                setTimeout(() => {
                                    el.classList.remove('ring-2', 'ring-emerald-500', 'bg-emerald-50/70');
                                }, 3500);
                            };

                            if (d.pincode) applyWithHighlight(pincodeEl, d.pincode);
                            if (d.state) applyWithHighlight(stateEl, d.state);
                            if (d.district) applyWithHighlight(districtEl, d.district);
                            if (d.city) applyWithHighlight(cityEl, d.city);

                            if (addressEl && (!addressEl.value.trim() || addressEl.value.trim().length < 5)) {
                                if (d.address) {
                                    applyWithHighlight(addressEl, d.address);
                                } else if (d.display_name) {
                                    const cleanDisplay = d.display_name.split(',').slice(0, 3).map(s => s.trim()).join(', ');
                                    applyWithHighlight(addressEl, cleanDisplay);
                                }
                            }

                            const populatedSummary = [
                                d.pincode ? `PIN: ${d.pincode}` : null,
                                d.city || null,
                                d.district || null,
                                d.state || null
                            ].filter(Boolean).join(', ');

                            showStatusMessage(
                                'success',
                                `<strong>Location & Address Populated:</strong> ${populatedSummary}`,
                                `GPS locked at ${lat}° N, ${lng}° E (±${acc || 5}m). Form fields for PIN Code, State, District, and City/Town have been automatically populated.`
                            );
                        } else {
                            showStatusMessage(
                                'warning',
                                `<strong>Coordinates Locked:</strong> ${lat}° N, ${lng}° E`,
                                (res && res.message) ? res.message : 'Could not resolve full address details automatically. Please complete PIN Code, State, District, and City manually.'
                            );
                        }
                    })
                    .catch(err => {
                        if (err.name === 'AbortError') return;
                        console.warn('Reverse geocode error:', err);
                        showStatusMessage(
                            'warning',
                            `<strong>Coordinates Locked:</strong> ${lat}° N, ${lng}° E`,
                            'Address lookup was unavailable or timed out. Coordinates are saved; please verify PIN Code, State, District, and City manually.'
                        );
                    });
            }

            // Locate Campus from Address (via backend rate-limited proxy)
            function geocodeCampusAddress() {
                const now = Date.now();
                if (now - lastGeocodeSearchTimestamp < 2500) {
                    showStatusMessage('warning', 'Please Wait', 'Please wait a moment before running another address search.');
                    return;
                }

                const address = (document.querySelector('textarea[name="address"]')?.value || '').trim();
                const city = (document.querySelector('input[name="city"]')?.value || '').trim();
                const district = (document.querySelector('input[name="district"]')?.value || '').trim();
                const state = (document.querySelector('input[name="state"]')?.value || '').trim();
                const pincode = (document.querySelector('input[name="pincode"]')?.value || '').trim();

                const queryParts = [address, city, district, state, pincode, 'India'].filter(Boolean);
                if (queryParts.length <= 1) {
                    showStatusMessage('warning', 'Address Incomplete', 'Please fill in Campus Street Address, City, District, or State first so we can locate it on the map.');
                    return;
                }

                lastGeocodeSearchTimestamp = now;
                const btn = document.getElementById('btn-geocode-address');
                btn.disabled = true;
                btn.innerHTML = `<span>Searching Map...</span>`;
                showStatusMessage('loading', 'Locating Campus Address...', 'Searching geospatial registry for your address...');

                const query = queryParts.join(', ');

                fetch('{{ route("geocode.search") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({ query: query })
                })
                    .then(res => {
                        if (res.status === 429) {
                            throw new Error('RATE_LIMIT');
                        }
                        if (!res.ok) {
                            throw new Error('HTTP_' + res.status);
                        }
                        return res.json();
                    })
                    .then(res => {
                        btn.disabled = false;
                        btn.innerHTML = `<svg class="w-3.5 h-3.5 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg><span>Locate from Address</span>`;

                        if (res && res.success && res.data) {
                            const lat = parseFloat(res.data.latitude).toFixed(7);
                            const lng = parseFloat(res.data.longitude).toFixed(7);
                            document.getElementById('school_latitude').value = lat;
                            document.getElementById('school_longitude').value = lng;
                            updateGPSPreview();
                            placeOrUpdateMarker(res.data.latitude, res.data.longitude, true);

                            const displayName = res.data.display_name ? res.data.display_name.split(',').slice(0, 3).join(',') : `${lat}° N, ${lng}° E`;
                            showStatusMessage('success', `<strong>Campus Located:</strong> ${displayName}`, `Coordinates placed at ${lat}° N, ${lng}° E. Drag the pin directly to the exact campus gate or building.`);
                        } else {
                            showStatusMessage('warning', 'Address Not Found on Map', (res && res.message) ? res.message : 'Could not resolve exact coordinates from address. You can click on the map to place the pin directly.');
                        }
                    })
                    .catch(err => {
                        btn.disabled = false;
                        btn.innerHTML = `<svg class="w-3.5 h-3.5 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg><span>Locate from Address</span>`;

                        if (err.message === 'RATE_LIMIT') {
                            showStatusMessage('warning', 'Rate Limit Exceeded', 'You have made too many address search requests. Please wait a minute before trying again, or drag the map pin directly.');
                        } else {
                            showStatusMessage('warning', 'Geocoding Service Unavailable', 'Could not locate address automatically. Please click on the map directly to place the school marker.');
                        }
                    });
            }

            function toggleQuickPaste() {
                const container = document.getElementById('quick-paste-container');
                container.classList.toggle('hidden');
                if (!container.classList.contains('hidden')) {
                    document.getElementById('quick-paste-input').focus();
                }
            }

            function parseQuickPaste() {
                const raw = (document.getElementById('quick-paste-input').value || '').trim();
                if (!raw) return;

                let lat = null;
                let lng = null;

                const numMatch = raw.match(/(-?\d+\.\d+)[\s,]+(-?\d+\.\d+)/);
                if (numMatch) {
                    lat = parseFloat(numMatch[1]);
                    lng = parseFloat(numMatch[2]);
                } else {
                    const urlMatch = raw.match(/[@=](-?\d+\.\d+),(-?\d+\.\d+)/);
                    if (urlMatch) {
                        lat = parseFloat(urlMatch[1]);
                        lng = parseFloat(urlMatch[2]);
                    }
                }

                if (lat !== null && lng !== null && lat >= -90 && lat <= 90 && lng >= -180 && lng <= 180) {
                    const formattedLat = lat.toFixed(7);
                    const formattedLng = lng.toFixed(7);
                    document.getElementById('school_latitude').value = formattedLat;
                    document.getElementById('school_longitude').value = formattedLng;
                    updateGPSPreview();
                    placeOrUpdateMarker(lat, lng, true);
                    showStatusMessage('success', 'Coordinates Parsed Successfully', `Set to ${formattedLat}° N, ${formattedLng}° E. Pin positioned on map.`);
                    toggleQuickPaste();
                } else {
                    alert('Could not detect valid latitude and longitude from the text. Example formats: "11.182415, 75.864720" or Google Maps URL.');
                }
            }

            document.addEventListener('DOMContentLoaded', function () {
                initCampusMap();
                updateGPSPreview();
            });
        </script>
    @endpush
</x-app-layout>