<x-app-layout>
    <x-slot name="title">Feasibility Wizard — Step {{ $step }} of 12 — {{ $application->application_number }}</x-slot>

    <div class="space-y-6">
        <!-- Wizard Navigation & Header -->
        <div class="bg-white rounded-2xl border border-slate-200/80 p-6 shadow-xs">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <div class="flex items-center gap-2">
                        <span class="font-mono text-xs font-bold text-indigo-600 bg-indigo-50 px-2 py-0.5 rounded border border-indigo-100">
                            {{ $application->application_number }}
                        </span>
                        <x-badge :variant="$application->status->color()">
                            {{ $application->status->label() }}
                        </x-badge>
                    </div>
                    <h1 class="text-xl font-bold text-slate-900 mt-1.5">{{ $school->name }}</h1>
                    <p class="text-xs text-slate-500">Managed by {{ $school->management_organization }} &bull; {{ $school->city }}, {{ $school->state }}</p>
                </div>

                <div class="flex items-center gap-2">
                    <a href="{{ route('applications.show', $application) }}" class="px-3.5 py-2 text-xs font-semibold text-slate-700 bg-slate-100 hover:bg-slate-200 rounded-lg transition">
                        View Summary
                    </a>
                </div>
            </div>

            <!-- Stepper Indicators (1 to 12) -->
            <div class="mt-6 pt-4 border-t border-slate-100">
                <div class="grid grid-cols-2 sm:grid-cols-4 lg:grid-cols-12 gap-1.5 text-center text-xs">
                    @php
                        $stepTitles = [
                            1 => 'Basic Info',
                            2 => 'Management',
                            3 => 'Location & Land',
                            4 => 'Demographics',
                            5 => 'Nearby Schools',
                            6 => 'Projections',
                            7 => 'Infrastructure',
                            8 => 'Staffing',
                            9 => 'Financials',
                            10 => 'Documents',
                            11 => 'Declaration',
                            12 => 'Submit',
                        ];
                    @endphp

                    @foreach ($stepTitles as $sNum => $sName)
                        <a href="{{ route('applications.wizard.step', [$application, $sNum]) }}"
                           class="p-2 rounded-lg border transition {{ $sNum === $step ? 'bg-indigo-600 text-white font-bold border-indigo-600 shadow-xs' : ($sNum < $application->current_step ? 'bg-indigo-50 text-indigo-700 border-indigo-100 font-semibold' : 'bg-slate-50 text-slate-400 border-slate-100') }}">
                            <div class="text-[10px] uppercase tracking-wider opacity-80">Step {{ $sNum }}</div>
                            <div class="truncate mt-0.5">{{ $sName }}</div>
                        </a>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Step Content Form -->
        @if ($step === 9)
            <!-- STEP 9: Financial Feasibility — rendered outside outer form to support nested sub-forms -->
            <div class="space-y-6 text-xs">
                <!-- CAPEX Card -->
                <x-card title="Step 9A: Capital Expenditure (CAPEX)" subtitle="Land, infrastructure, laboratories, and fleet investments">
                    <!-- Add CAPEX Form -->
                    <form method="POST" action="{{ route('applications.wizard.save', [$application, 9]) }}" class="bg-slate-50 p-4 rounded-xl border border-slate-200 mb-5 space-y-3">
                        @csrf
                        <input type="hidden" name="action" value="add_item">
                        <h5 class="font-bold text-slate-900 text-xs">Add Capital Expense Item</h5>
                        <div class="grid grid-cols-1 sm:grid-cols-4 gap-2">
                            <div>
                                <label class="block text-[10px] font-semibold text-slate-500 mb-1 uppercase tracking-wide">Category</label>
                                <select name="capex_category" class="w-full text-xs rounded-lg border-slate-300 focus:border-indigo-500 focus:ring-indigo-500">
                                    <option value="building">School Building</option>
                                    <option value="land">Land &amp; Lease</option>
                                    <option value="furniture">Furniture</option>
                                    <option value="laboratory">Science Labs</option>
                                    <option value="computer_equipment">ICT &amp; Computers</option>
                                    <option value="buses">School Buses</option>
                                    <option value="other">Other Infrastructure</option>
                                </select>
                            </div>
                            <div class="sm:col-span-2">
                                <label class="block text-[10px] font-semibold text-slate-500 mb-1 uppercase tracking-wide">Item Description <span class="text-rose-500">*</span></label>
                                <input type="text" name="capex_name" placeholder="e.g. Main School Block Construction"
                                       class="w-full text-xs rounded-lg border-slate-300 focus:border-indigo-500 focus:ring-indigo-500">
                            </div>
                            <div>
                                <label class="block text-[10px] font-semibold text-slate-500 mb-1 uppercase tracking-wide">Amount (INR) <span class="text-rose-500">*</span></label>
                                <input type="number" name="capex_amount" placeholder="0" min="0"
                                       class="w-full text-xs rounded-lg border-slate-300 focus:border-indigo-500 focus:ring-indigo-500">
                            </div>
                        </div>
                        <div class="flex justify-end pt-1">
                            <button type="submit"
                                    class="inline-flex items-center gap-2 px-4 py-2 text-xs font-semibold text-white bg-indigo-600 hover:bg-indigo-500 rounded-lg shadow-sm transition">
                                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                </svg>
                                Add Item
                            </button>
                        </div>
                    </form>

                    <!-- CAPEX Table -->
                    <div class="overflow-x-auto">
                        <table class="w-full text-left">
                            <thead class="bg-slate-50 font-semibold text-slate-500">
                                <tr>
                                    <th class="p-2">Category</th>
                                    <th class="p-2">Item Name</th>
                                    <th class="p-2 text-right">Amount (INR)</th>
                                    <th class="p-2 text-right">Action</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                @forelse ($capitalExpenses as $cx)
                                    <tr>
                                        <td class="p-2 capitalize font-medium text-slate-700">{{ $cx->category }}</td>
                                        <td class="p-2 font-semibold text-slate-900">{{ $cx->item_name }}</td>
                                        <td class="p-2 text-right font-mono">{{ number_format($cx->amount) }}</td>
                                        <td class="p-2 text-right">
                                            <form method="POST" action="{{ route('applications.wizard.delete_capex', [$application, $cx]) }}">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-rose-600 font-semibold">Remove</button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="p-4 text-center text-slate-400">No capital expenses added yet.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </x-card>

                <!-- Revenue Projections Card -->
                <x-card title="Step 9B: Annual Tuition &amp; Fee Revenues" subtitle="Projected fee schedules across grade categories">
                    <!-- Add Revenue Form -->
                    <form method="POST" action="{{ route('applications.wizard.save', [$application, 9]) }}" class="bg-slate-50 p-4 rounded-xl border border-slate-200 mb-5 space-y-3">
                        @csrf
                        <input type="hidden" name="action" value="add_item">
                        <h5 class="font-bold text-slate-900 text-xs">Add Revenue Stream</h5>
                        <div class="grid grid-cols-1 sm:grid-cols-4 gap-2">
                            <div>
                                <label class="block text-[10px] font-semibold text-slate-500 mb-1 uppercase tracking-wide">Fee Type <span class="text-rose-500">*</span></label>
                                <input type="text" name="revenue_type" placeholder="e.g. Tuition Fee" class="w-full text-xs rounded-lg border-slate-300 focus:border-indigo-500 focus:ring-indigo-500">
                            </div>
                            <div>
                                <label class="block text-[10px] font-semibold text-slate-500 mb-1 uppercase tracking-wide">Fee / Student (INR)</label>
                                <input type="number" name="fee_per_student" placeholder="0" class="w-full text-xs rounded-lg border-slate-300 focus:border-indigo-500 focus:ring-indigo-500">
                            </div>
                            <div>
                                <label class="block text-[10px] font-semibold text-slate-500 mb-1 uppercase tracking-wide">Year 1 Total (INR)</label>
                                <input type="number" name="rev_year_1" placeholder="0" class="w-full text-xs rounded-lg border-slate-300 focus:border-indigo-500 focus:ring-indigo-500">
                            </div>
                            <div>
                                <label class="block text-[10px] font-semibold text-slate-500 mb-1 uppercase tracking-wide">Year 2 Total (INR)</label>
                                <input type="number" name="rev_year_2" placeholder="0" class="w-full text-xs rounded-lg border-slate-300 focus:border-indigo-500 focus:ring-indigo-500">
                            </div>
                        </div>
                        <div class="flex justify-end pt-1">
                            <button type="submit"
                                    class="inline-flex items-center gap-2 px-4 py-2 text-xs font-semibold text-white bg-emerald-600 hover:bg-emerald-500 rounded-lg shadow-sm transition">
                                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                </svg>
                                Add Revenue
                            </button>
                        </div>
                    </form>

                    <!-- Revenue Table -->
                    <div class="overflow-x-auto">
                        <table class="w-full text-left">
                            <thead class="bg-slate-50 font-semibold text-slate-500">
                                <tr>
                                    <th class="p-2">Fee Type</th>
                                    <th class="p-2 text-right">Per Student</th>
                                    <th class="p-2 text-right">Year 1 Total (INR)</th>
                                    <th class="p-2 text-right">Action</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                @forelse ($revenueProjections as $rev)
                                    <tr>
                                        <td class="p-2 font-semibold text-slate-900">{{ $rev->fee_type }}</td>
                                        <td class="p-2 text-right font-mono">{{ number_format($rev->fee_per_student) }}</td>
                                        <td class="p-2 text-right font-bold text-emerald-600 font-mono">{{ number_format($rev->year_1) }}</td>
                                        <td class="p-2 text-right">
                                            <form method="POST" action="{{ route('applications.wizard.delete_revenue', [$application, $rev]) }}">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-rose-600 font-semibold">Remove</button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="p-4 text-center text-slate-400">No revenue streams added yet.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </x-card>
            </div>

            <!-- Step 9 Navigation -->
            <form method="POST" action="{{ route('applications.wizard.save', [$application, 9]) }}">
                @csrf
                <div class="flex items-center justify-between pt-4 border-t border-slate-200">
                    <a href="{{ route('applications.wizard.step', [$application, 8]) }}" class="px-4 py-2 text-xs font-semibold text-slate-700 bg-white border border-slate-300 rounded-lg hover:bg-slate-50 transition">
                        &larr; Previous Step
                    </a>
                    <div class="flex items-center gap-3">
                        <button type="submit" name="action" value="save_draft" class="px-4 py-2 text-xs font-semibold text-slate-700 bg-slate-100 hover:bg-slate-200 rounded-lg transition">
                            Save Draft &amp; Continue Later
                        </button>
                        <button type="submit" class="px-5 py-2 text-xs font-semibold text-white bg-indigo-600 hover:bg-indigo-500 rounded-lg shadow-sm transition">
                            Save &amp; Proceed to Step 10 &rarr;
                        </button>
                    </div>
                </div>
            </form>
        @elseif ($step === 10)
            <!-- STEP 10: Document Uploads — rendered outside outer form to allow nested upload forms -->
            <x-card title="Step 10: Mandatory Document Uploads" subtitle="Upload required deeds, certificates, and compliance NOCs">
                <div class="space-y-4 text-xs">
                    <div class="divide-y divide-slate-100">
                        @foreach ($documentRequirements as $req)
                            @php $doc = $documents->get($req->id); @endphp
                            <div class="py-4 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                                <div>
                                    <div class="font-semibold text-slate-900">{{ $req->title }}</div>
                                    <p class="text-[11px] text-slate-500">{{ $req->description }}</p>
                                    @if ($req->is_mandatory)
                                        <span class="text-[10px] font-bold text-rose-600 uppercase">Mandatory</span>
                                    @endif
                                </div>
                                <div class="shrink-0">
                                    @if ($doc)
                                        <div class="flex items-center gap-3">
                                            <x-badge variant="emerald" size="sm">Uploaded ({{ $doc->formatted_size }})</x-badge>
                                            <a href="{{ route('documents.download', $doc) }}" class="text-indigo-600 font-semibold hover:underline">Download</a>
                                            <form method="POST" action="{{ route('documents.destroy', $doc) }}">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-rose-500 hover:text-rose-700 font-semibold text-[11px]">Remove</button>
                                            </form>
                                        </div>
                                    @else
                                        <form method="POST"
                                              action="{{ route('documents.upload', $application) }}"
                                              enctype="multipart/form-data"
                                              class="flex items-center gap-2">
                                            @csrf
                                            <input type="hidden" name="requirement_id" value="{{ $req->id }}">
                                            <label class="cursor-pointer inline-flex items-center gap-2 px-3 py-1.5 text-[11px] font-semibold text-white bg-indigo-600 hover:bg-indigo-500 rounded-lg shadow-sm transition">
                                                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" />
                                                </svg>
                                                Choose File
                                                <input type="file"
                                                       name="document_file"
                                                       accept=".pdf,.jpg,.jpeg,.png,.docx,.xlsx"
                                                       class="sr-only"
                                                       onchange="this.closest('form').submit()">
                                            </label>
                                        </form>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </x-card>

            <!-- Step 10 Navigation -->
            <form method="POST" action="{{ route('applications.wizard.save', [$application, 10]) }}">
                @csrf
                <div class="flex items-center justify-between pt-4 border-t border-slate-200">
                    <a href="{{ route('applications.wizard.step', [$application, 9]) }}" class="px-4 py-2 text-xs font-semibold text-slate-700 bg-white border border-slate-300 rounded-lg hover:bg-slate-50 transition">
                        &larr; Previous Step
                    </a>
                    <div class="flex items-center gap-3">
                        <button type="submit" name="action" value="save_draft" class="px-4 py-2 text-xs font-semibold text-slate-700 bg-slate-100 hover:bg-slate-200 rounded-lg transition">
                            Save Draft &amp; Continue Later
                        </button>
                        <button type="submit" class="px-5 py-2 text-xs font-semibold text-white bg-indigo-600 hover:bg-indigo-500 rounded-lg shadow-sm transition">
                            Proceed to Step 11 &rarr;
                        </button>
                    </div>
                </div>
            </form>
        @else
        <form method="POST" action="{{ route('applications.wizard.save', [$application, $step]) }}" enctype="multipart/form-data" class="space-y-6">
            @csrf

            <!-- STEP 1: Basic Information -->
            @if ($step === 1)
                <x-card title="Step 1: School Identity & General Information" subtitle="Specify institutional names, proposed board, medium, and academic level">
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-xs">
                        <div class="sm:col-span-2">
                            <label class="block font-medium text-slate-700 mb-1">Official School Name <span class="text-rose-500">*</span></label>
                            <input type="text" name="name" value="{{ old('name', $school->name) }}" required class="w-full text-xs rounded-lg border-slate-300 focus:ring-indigo-500">
                        </div>
                        <div>
                            <label class="block font-medium text-slate-700 mb-1">Proposed Name (if change)</label>
                            <input type="text" name="proposed_name" value="{{ old('proposed_name', $school->proposed_name) }}" class="w-full text-xs rounded-lg border-slate-300">
                        </div>
                        <div>
                            <label class="block font-medium text-slate-700 mb-1">Affiliation Board <span class="text-rose-500">*</span></label>
                            <select name="board" required class="w-full text-xs rounded-lg border-slate-300">
                                <option value="cbse" {{ old('board', $school->board?->value) === 'cbse' ? 'selected' : '' }}>CBSE</option>
                                <option value="icse" {{ old('board', $school->board?->value) === 'icse' ? 'selected' : '' }}>ICSE / CISCE</option>
                                <option value="state_board" {{ old('board', $school->board?->value) === 'state_board' ? 'selected' : '' }}>State Board</option>
                                <option value="ib" {{ old('board', $school->board?->value) === 'ib' ? 'selected' : '' }}>IB (International)</option>
                                <option value="cambridge" {{ old('board', $school->board?->value) === 'cambridge' ? 'selected' : '' }}>Cambridge International</option>
                            </select>
                        </div>
                        <div>
                            <label class="block font-medium text-slate-700 mb-1">Medium of Instruction <span class="text-rose-500">*</span></label>
                            <input type="text" name="medium" value="{{ old('medium', $school->medium) }}" required class="w-full text-xs rounded-lg border-slate-300">
                        </div>
                        <div>
                            <label class="block font-medium text-slate-700 mb-1">School Type <span class="text-rose-500">*</span></label>
                            <select name="school_type" required class="w-full text-xs rounded-lg border-slate-300">
                                <option value="k12" {{ old('school_type', $school->school_type?->value) === 'k12' ? 'selected' : '' }}>Composite K-12</option>
                                <option value="senior_secondary" {{ old('school_type', $school->school_type?->value) === 'senior_secondary' ? 'selected' : '' }}>Senior Secondary (to 12th)</option>
                                <option value="secondary" {{ old('school_type', $school->school_type?->value) === 'secondary' ? 'selected' : '' }}>Secondary (to 10th)</option>
                                <option value="middle" {{ old('school_type', $school->school_type?->value) === 'middle' ? 'selected' : '' }}>Middle (to 8th)</option>
                                <option value="primary" {{ old('school_type', $school->school_type?->value) === 'primary' ? 'selected' : '' }}>Primary (to 5th)</option>
                            </select>
                        </div>
                        <div>
                            <label class="block font-medium text-slate-700 mb-1">Target Opening Academic Year</label>
                            <input type="number" name="established_year" value="{{ old('established_year', $school->established_year ?? date('Y')) }}" class="w-full text-xs rounded-lg border-slate-300">
                        </div>
                    </div>
                </x-card>
            @endif

            <!-- STEP 2: Management Details -->
            @if ($step === 2)
                <x-card title="Step 2: Management Entity & Governing Trust" subtitle="Legal registration of the sponsoring society or section 8 company">
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-xs">
                        <div>
                            <label class="block font-medium text-slate-700 mb-1">Managing Organization / Trust Name <span class="text-rose-500">*</span></label>
                            <input type="text" name="management_organization" value="{{ old('management_organization', $school->management_organization) }}" required class="w-full text-xs rounded-lg border-slate-300">
                        </div>
                        <div>
                            <label class="block font-medium text-slate-700 mb-1">Legal Structure <span class="text-rose-500">*</span></label>
                            <select name="trust_society_type" required class="w-full text-xs rounded-lg border-slate-300">
                                <option value="Trust">Registered Public Charitable Trust</option>
                                <option value="Society">Registered Educational Society</option>
                                <option value="Section 8 Company">Section 8 Non-Profit Company</option>
                            </select>
                        </div>
                        <div>
                            <label class="block font-medium text-slate-700 mb-1">Registration Number</label>
                            <input type="text" name="registration_number" value="{{ old('registration_number', $school->registration_number) }}" class="w-full text-xs rounded-lg border-slate-300">
                        </div>
                        <div>
                            <label class="block font-medium text-slate-700 mb-1">Registration Date</label>
                            <input type="date" name="registration_date" value="{{ old('registration_date', $school->registration_date?->format('Y-m-d')) }}" class="w-full text-xs rounded-lg border-slate-300">
                        </div>
                        <div>
                            <label class="block font-medium text-slate-700 mb-1">Applicant / Authorised Contact Name <span class="text-rose-500">*</span></label>
                            <input type="text" name="applicant_name" value="{{ old('applicant_name', $school->applicant_name) }}" required class="w-full text-xs rounded-lg border-slate-300">
                        </div>
                        <div>
                            <label class="block font-medium text-slate-700 mb-1">Applicant Email <span class="text-rose-500">*</span></label>
                            <input type="email" name="applicant_email" value="{{ old('applicant_email', $school->applicant_email) }}" required class="w-full text-xs rounded-lg border-slate-300">
                        </div>
                        <div>
                            <label class="block font-medium text-slate-700 mb-1">Applicant Phone <span class="text-rose-500">*</span></label>
                            <input type="text" name="applicant_phone" value="{{ old('applicant_phone', $school->applicant_phone) }}" required class="w-full text-xs rounded-lg border-slate-300">
                        </div>
                        <div>
                            <label class="block font-medium text-slate-700 mb-1">Trust / School Website</label>
                            <input type="url" name="website" value="{{ old('website', $school->website) }}" class="w-full text-xs rounded-lg border-slate-300" placeholder="https://">
                        </div>
                    </div>
                </x-card>
            @endif

            <!-- STEP 3: Location & Land -->
            @if ($step === 3)
                <div class="space-y-6">
                    <x-card title="Step 3A: Geographic Location & Approach" subtitle="Precise site details, GPS coordinates, and road connectivity">
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-xs">
                            <div class="sm:col-span-2">
                                <label class="block font-medium text-slate-700 mb-1">Campus Street Address <span class="text-rose-500">*</span></label>
                                <textarea name="address" rows="2" required class="w-full text-xs rounded-lg border-slate-300">{{ old('address', $location?->address ?? $school->address) }}</textarea>
                            </div>
                            <div>
                                <label class="block font-medium text-slate-700 mb-1">Village / Town / Sector <span class="text-rose-500">*</span></label>
                                <input type="text" name="village_town" value="{{ old('village_town', $location?->village_town ?? $school->city) }}" required class="w-full text-xs rounded-lg border-slate-300">
                            </div>
                            <div>
                                <label class="block font-medium text-slate-700 mb-1">Taluk / Tehsil</label>
                                <input type="text" name="taluk_tehsil" value="{{ old('taluk_tehsil', $location?->taluk_tehsil) }}" class="w-full text-xs rounded-lg border-slate-300">
                            </div>
                            <div>
                                <label class="block font-medium text-slate-700 mb-1">District <span class="text-rose-500">*</span></label>
                                <input type="text" name="district" value="{{ old('district', $location?->district ?? $school->district) }}" required class="w-full text-xs rounded-lg border-slate-300">
                            </div>
                            <div>
                                <label class="block font-medium text-slate-700 mb-1">State <span class="text-rose-500">*</span></label>
                                <input type="text" name="state" value="{{ old('state', $location?->state ?? $school->state) }}" required class="w-full text-xs rounded-lg border-slate-300">
                            </div>
                            <div>
                                <label class="block font-medium text-slate-700 mb-1">PIN Code <span class="text-rose-500">*</span></label>
                                <input type="text" name="pincode" value="{{ old('pincode', $location?->pincode ?? $school->pincode) }}" required class="w-full text-xs rounded-lg border-slate-300">
                            </div>
                            <div>
                                <label class="block font-medium text-slate-700 mb-1">GPS Latitude</label>
                                <input type="number" step="0.0000001" name="latitude" value="{{ old('latitude', $location?->latitude ?? $school->latitude) }}" class="w-full text-xs rounded-lg border-slate-300" placeholder="e.g. 28.5123000">
                            </div>
                            <div>
                                <label class="block font-medium text-slate-700 mb-1">GPS Longitude</label>
                                <input type="number" step="0.0000001" name="longitude" value="{{ old('longitude', $location?->longitude ?? $school->longitude) }}" class="w-full text-xs rounded-lg border-slate-300" placeholder="e.g. 77.3689000">
                            </div>
                            <div>
                                <label class="block font-medium text-slate-700 mb-1">Nearest Major Road / Highway</label>
                                <input type="text" name="nearest_major_road" value="{{ old('nearest_major_road', $location?->nearest_major_road) }}" class="w-full text-xs rounded-lg border-slate-300">
                            </div>
                            <div>
                                <label class="block font-medium text-slate-700 mb-1">Distance from Main Road (km)</label>
                                <input type="number" step="0.01" name="distance_from_main_road_km" value="{{ old('distance_from_main_road_km', $location?->distance_from_main_road_km) }}" class="w-full text-xs rounded-lg border-slate-300">
                            </div>
                            <div class="sm:col-span-2">
                                <label class="block font-medium text-slate-700 mb-1">Approach Road Description</label>
                                <input type="text" name="accessibility_description" value="{{ old('accessibility_description', $location?->accessibility_description) }}" class="w-full text-xs rounded-lg border-slate-300" placeholder="e.g. 18m wide all-weather pucca road with central lighting">
                            </div>
                        </div>
                    </x-card>

                    <x-card title="Step 3B: Land Specifications & Title Ownership" subtitle="Area measurements, tenure, and zoning compliance">
                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 text-xs">
                            <div>
                                <label class="block font-medium text-slate-700 mb-1">Total Land Area <span class="text-rose-500">*</span></label>
                                <input type="number" step="0.01" name="total_land_area" value="{{ old('total_land_area', $land?->total_land_area ?? 3.5) }}" required class="w-full text-xs rounded-lg border-slate-300">
                            </div>
                            <div>
                                <label class="block font-medium text-slate-700 mb-1">Unit of Measure <span class="text-rose-500">*</span></label>
                                <select name="area_unit" required class="w-full text-xs rounded-lg border-slate-300">
                                    <option value="Acres" {{ old('area_unit', $land?->area_unit) === 'Acres' ? 'selected' : '' }}>Acres</option>
                                    <option value="Hectares" {{ old('area_unit', $land?->area_unit) === 'Hectares' ? 'selected' : '' }}>Hectares</option>
                                    <option value="Sq. Meters" {{ old('area_unit', $land?->area_unit) === 'Sq. Meters' ? 'selected' : '' }}>Sq. Meters</option>
                                    <option value="Sq. Feet" {{ old('area_unit', $land?->area_unit) === 'Sq. Feet' ? 'selected' : '' }}>Sq. Feet</option>
                                </select>
                            </div>
                            <div>
                                <label class="block font-medium text-slate-700 mb-1">Ownership / Tenure <span class="text-rose-500">*</span></label>
                                <select name="ownership_type" required class="w-full text-xs rounded-lg border-slate-300">
                                    <option value="owned" {{ old('ownership_type', $land?->ownership_type?->value) === 'owned' ? 'selected' : '' }}>Freehold / Owned</option>
                                    <option value="leased" {{ old('ownership_type', $land?->ownership_type?->value) === 'leased' ? 'selected' : '' }}>Registered Long-Term Lease (30+ yrs)</option>
                                    <option value="trust_owned" {{ old('ownership_type', $land?->ownership_type?->value) === 'trust_owned' ? 'selected' : '' }}>Trust / Society Owned</option>
                                    <option value="rented" {{ old('ownership_type', $land?->ownership_type?->value) === 'rented' ? 'selected' : '' }}>Rented</option>
                                </select>
                            </div>
                            <div>
                                <label class="block font-medium text-slate-700 mb-1">Survey / Khasra Number</label>
                                <input type="text" name="survey_number" value="{{ old('survey_number', $land?->survey_number) }}" class="w-full text-xs rounded-lg border-slate-300">
                            </div>
                            <div class="sm:col-span-2">
                                <label class="block font-medium text-slate-700 mb-1">Registration Deed Particulars</label>
                                <input type="text" name="land_registration_details" value="{{ old('land_registration_details', $land?->land_registration_details) }}" class="w-full text-xs rounded-lg border-slate-300" placeholder="e.g. Sub-Registrar Book 1, Vol 402, Reg No. 1290">
                            </div>
                            <div>
                                <label class="block font-medium text-slate-700 mb-1">Building Built-Up Area (sq.ft)</label>
                                <input type="number" step="0.01" name="building_area" value="{{ old('building_area', $land?->building_area) }}" class="w-full text-xs rounded-lg border-slate-300">
                            </div>
                            <div>
                                <label class="block font-medium text-slate-700 mb-1">Playground Area (sq.ft)</label>
                                <input type="number" step="0.01" name="playground_area" value="{{ old('playground_area', $land?->playground_area) }}" class="w-full text-xs rounded-lg border-slate-300">
                            </div>
                            <div>
                                <label class="block font-medium text-slate-700 mb-1">Open / Green Space (sq.ft)</label>
                                <input type="number" step="0.01" name="open_area" value="{{ old('open_area', $land?->open_area) }}" class="w-full text-xs rounded-lg border-slate-300">
                            </div>
                        </div>
                    </x-card>
                </div>
            @endif

            <!-- STEP 4: Demographic Analysis -->
            @if ($step === 4)
                <x-card title="Step 4: Catchment Demographics & Student Feasibility" subtitle="Population density within concentric radii, age distribution, and capture formulas">
                    <div class="space-y-6 text-xs">
                        <div class="grid grid-cols-1 sm:grid-cols-4 gap-4">
                            <div>
                                <label class="block font-medium text-slate-700 mb-1">Population within 1 km <span class="text-rose-500">*</span></label>
                                <input type="number" name="population_1km" value="{{ old('population_1km', $demographic?->population_1km ?? 15000) }}" required class="w-full text-xs rounded-lg border-slate-300">
                            </div>
                            <div>
                                <label class="block font-medium text-slate-700 mb-1">Population within 3 km <span class="text-rose-500">*</span></label>
                                <input type="number" name="population_3km" value="{{ old('population_3km', $demographic?->population_3km ?? 65000) }}" required class="w-full text-xs rounded-lg border-slate-300">
                            </div>
                            <div>
                                <label class="block font-medium text-slate-700 mb-1">Population within 5 km <span class="text-rose-500">*</span></label>
                                <input type="number" name="population_5km" value="{{ old('population_5km', $demographic?->population_5km ?? 140000) }}" required class="w-full text-xs rounded-lg border-slate-300">
                            </div>
                            <div>
                                <label class="block font-medium text-slate-700 mb-1">Total Catchment Households <span class="text-rose-500">*</span></label>
                                <input type="number" name="number_of_households" value="{{ old('number_of_households', $demographic?->number_of_households ?? 32000) }}" required class="w-full text-xs rounded-lg border-slate-300">
                            </div>
                        </div>

                        <div class="border-t pt-4">
                            <h4 class="font-bold text-slate-900 mb-3 uppercase text-[11px]">School-Age Children Demographics</h4>
                            <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
                                <div>
                                    <label class="block font-medium text-slate-700 mb-1">Pre-Primary (3–5 yrs) <span class="text-rose-500">*</span></label>
                                    <input type="number" name="children_age_3_5" value="{{ old('children_age_3_5', $demographic?->children_age_3_5 ?? 3500) }}" required class="w-full text-xs rounded-lg border-slate-300">
                                </div>
                                <div>
                                    <label class="block font-medium text-slate-700 mb-1">Primary (6–10 yrs) <span class="text-rose-500">*</span></label>
                                    <input type="number" name="children_age_6_10" value="{{ old('children_age_6_10', $demographic?->children_age_6_10 ?? 6800) }}" required class="w-full text-xs rounded-lg border-slate-300">
                                </div>
                                <div>
                                    <label class="block font-medium text-slate-700 mb-1">Middle (11–14 yrs) <span class="text-rose-500">*</span></label>
                                    <input type="number" name="children_age_11_14" value="{{ old('children_age_11_14', $demographic?->children_age_11_14 ?? 6100) }}" required class="w-full text-xs rounded-lg border-slate-300">
                                </div>
                                <div>
                                    <label class="block font-medium text-slate-700 mb-1">Secondary (15–18 yrs) <span class="text-rose-500">*</span></label>
                                    <input type="number" name="children_age_15_18" value="{{ old('children_age_15_18', $demographic?->children_age_15_18 ?? 5400) }}" required class="w-full text-xs rounded-lg border-slate-300">
                                </div>
                            </div>
                        </div>

                        <div class="border-t pt-4 grid grid-cols-1 sm:grid-cols-4 gap-4">
                            <div>
                                <label class="block font-medium text-slate-700 mb-1">Annual Pop Growth % <span class="text-rose-500">*</span></label>
                                <input type="number" step="0.01" name="population_growth_rate" value="{{ old('population_growth_rate', $demographic?->population_growth_rate ?? 3.5) }}" required class="w-full text-xs rounded-lg border-slate-300">
                            </div>
                            <div>
                                <label class="block font-medium text-slate-700 mb-1">Expected Capture Rate % <span class="text-rose-500">*</span></label>
                                <input type="number" step="0.1" name="expected_capture_rate" value="{{ old('expected_capture_rate', $demographic?->expected_capture_rate ?? 5.5) }}" required class="w-full text-xs rounded-lg border-slate-300">
                            </div>
                            <div>
                                <label class="block font-medium text-slate-700 mb-1">Development Pace</label>
                                <select name="residential_development_trend" class="w-full text-xs rounded-lg border-slate-300">
                                    <option value="rapid">Rapid Urbanization</option>
                                    <option value="steady">Steady Growth</option>
                                    <option value="emerging">Emerging Suburb</option>
                                    <option value="stagnant">Mature / Stagnant</option>
                                </select>
                            </div>
                            <div>
                                <label class="block font-medium text-slate-700 mb-1">Economic Catchment Profile</label>
                                <select name="economic_profile" class="w-full text-xs rounded-lg border-slate-300">
                                    <option value="upper_middle">Upper Middle Class</option>
                                    <option value="high_income">High Income</option>
                                    <option value="middle_income">Middle Income</option>
                                    <option value="mixed">Mixed Income</option>
                                </select>
                            </div>
                            <input type="hidden" name="employment_profile" value="corporate">
                        </div>

                        <div class="bg-indigo-50/80 p-3 rounded-xl border border-indigo-100 flex items-center justify-between">
                            <div>
                                <span class="font-bold text-indigo-900 block">Current Calculated Potential Enrolment:</span>
                                <span class="text-[11px] text-indigo-700">Formula: (Total Eligible School-Age Population &times; Expected Capture Rate)</span>
                            </div>
                            <span class="text-2xl font-bold font-mono text-indigo-900">
                                {{ number_format($demographic?->potential_students_calculated ?? 0) }} Students
                            </span>
                        </div>
                    </div>
                </x-card>
            @endif

            <!-- STEP 5: Nearby Schools -->
            @if ($step === 5)
                <div class="space-y-6">
                    <x-card title="Step 5: Nearby Competing Schools & Market Analysis" subtitle="Record competitor strengths, distances, boards, and fee structures">
                        <!-- Add Competitor Input Form -->
                        <div class="bg-slate-50 p-4 rounded-xl border border-slate-200 mb-6 text-xs space-y-3">
                            <h4 class="font-bold text-slate-900">Add Nearby School</h4>
                            <div class="grid grid-cols-1 sm:grid-cols-4 gap-3">
                                <div class="sm:col-span-2">
                                    <label class="block font-medium text-slate-700 mb-1">School Name</label>
                                    <input type="text" name="nearby_school_name" placeholder="e.g. St. Xavier International" class="w-full text-xs rounded border-slate-300">
                                </div>
                                <div>
                                    <label class="block font-medium text-slate-700 mb-1">Distance (km)</label>
                                    <input type="number" step="0.1" name="distance_km" placeholder="e.g. 2.5" class="w-full text-xs rounded border-slate-300">
                                </div>
                                <div>
                                    <label class="block font-medium text-slate-700 mb-1">Affiliation Board</label>
                                    <input type="text" name="board" value="CBSE" class="w-full text-xs rounded border-slate-300">
                                </div>
                                <div>
                                    <label class="block font-medium text-slate-700 mb-1">Medium</label>
                                    <input type="text" name="medium" value="English" class="w-full text-xs rounded border-slate-300">
                                </div>
                                <div>
                                    <label class="block font-medium text-slate-700 mb-1">Grades Offered</label>
                                    <input type="text" name="classes_offered" value="Pre-KG to 12" class="w-full text-xs rounded border-slate-300">
                                </div>
                                <div>
                                    <label class="block font-medium text-slate-700 mb-1">Approx Student Strength</label>
                                    <input type="number" name="student_strength" value="2000" class="w-full text-xs rounded border-slate-300">
                                </div>
                                <div>
                                    <label class="block font-medium text-slate-700 mb-1">Annual Fee (INR)</label>
                                    <input type="number" name="fee_structure_annual" value="120000" class="w-full text-xs rounded border-slate-300">
                                </div>
                                <input type="hidden" name="competition_level" value="medium">
                            </div>
                        </div>

                        <!-- Existing Competitors Table -->
                        <div class="overflow-x-auto">
                            <table class="w-full text-left text-xs">
                                <thead class="bg-slate-50 font-semibold text-slate-500 border-b">
                                    <tr>
                                        <th class="p-2.5">School Name</th>
                                        <th class="p-2.5">Distance</th>
                                        <th class="p-2.5">Board</th>
                                        <th class="p-2.5">Strength</th>
                                        <th class="p-2.5">Annual Fee</th>
                                        <th class="p-2.5 text-right">Action</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-100">
                                    @forelse ($nearbySchools as $ns)
                                        <tr>
                                            <td class="p-2.5 font-semibold text-slate-900">{{ $ns->school_name }}</td>
                                            <td class="p-2.5">{{ $ns->distance_km }} km</td>
                                            <td class="p-2.5">{{ $ns->board }}</td>
                                            <td class="p-2.5 font-mono">{{ number_format($ns->student_strength) }}</td>
                                            <td class="p-2.5 font-mono">INR {{ number_format($ns->fee_structure_annual) }}</td>
                                            <td class="p-2.5 text-right">
                                                <form method="POST" action="{{ route('applications.wizard.delete_nearby', [$application, $ns]) }}">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="text-rose-600 hover:text-rose-800 font-semibold">Remove</button>
                                                </form>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6" class="p-4 text-center text-slate-400">No competitor schools recorded yet.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </x-card>
                </div>
            @endif

            <!-- STEP 6: Student Projections -->
            @if ($step === 6)
                <x-card title="Step 6: Class-wise 5-Year Enrolment Projections" subtitle="Map projected admissions across grade levels from Kindergarten through Senior Secondary">
                    <div class="overflow-x-auto text-xs">
                        <table class="w-full text-left">
                            <thead class="bg-slate-50 font-semibold text-slate-500 border-b">
                                <tr>
                                    <th class="p-2">Grade Level</th>
                                    <th class="p-2">Potential</th>
                                    <th class="p-2 bg-indigo-50/60 font-bold text-indigo-700">Year 1</th>
                                    <th class="p-2">Year 2</th>
                                    <th class="p-2">Year 3</th>
                                    <th class="p-2">Year 4</th>
                                    <th class="p-2">Year 5</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                @php
                                    $defaultClasses = app(\App\Services\Feasibility\StudentProjectionService::class)->getDefaultClasses();
                                @endphp
                                @foreach ($defaultClasses as $idx => $cls)
                                    @php
                                        $existing = $projections->firstWhere('class_name', $cls['name']);
                                    @endphp
                                    <tr>
                                        <td class="p-2 font-semibold text-slate-900">
                                            {{ $cls['name'] }}
                                            <input type="hidden" name="projections[{{ $idx }}][class_name]" value="{{ $cls['name'] }}">
                                        </td>
                                        <td class="p-2">
                                            <input type="number" name="projections[{{ $idx }}][current_potential]" value="{{ old("projections.{$idx}.current_potential", $existing?->current_potential ?? 50) }}" class="w-20 text-xs rounded border-slate-300">
                                        </td>
                                        <td class="p-2 bg-indigo-50/30">
                                            <input type="number" name="projections[{{ $idx }}][year_1]" value="{{ old("projections.{$idx}.year_1", $existing?->year_1_projection ?? 35) }}" class="w-20 text-xs font-bold text-indigo-700 rounded border-slate-300">
                                        </td>
                                        <td class="p-2">
                                            <input type="number" name="projections[{{ $idx }}][year_2]" value="{{ old("projections.{$idx}.year_2", $existing?->year_2_projection ?? 45) }}" class="w-20 text-xs rounded border-slate-300">
                                        </td>
                                        <td class="p-2">
                                            <input type="number" name="projections[{{ $idx }}][year_3]" value="{{ old("projections.{$idx}.year_3", $existing?->year_3_projection ?? 60) }}" class="w-20 text-xs rounded border-slate-300">
                                        </td>
                                        <td class="p-2">
                                            <input type="number" name="projections[{{ $idx }}][year_4]" value="{{ old("projections.{$idx}.year_4", $existing?->year_4_projection ?? 75) }}" class="w-20 text-xs rounded border-slate-300">
                                        </td>
                                        <td class="p-2">
                                            <input type="number" name="projections[{{ $idx }}][year_5]" value="{{ old("projections.{$idx}.year_5", $existing?->year_5_projection ?? 90) }}" class="w-20 text-xs rounded border-slate-300">
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </x-card>
            @endif

            <!-- STEP 7: Infrastructure Checklist -->
            @if ($step === 7)
                <x-card title="Step 7: Physical Infrastructure Readiness & Checklist" subtitle="Select readiness status (Available, Planned, Not Available)">
                    <div class="space-y-6 text-xs">
                        @php $counter = 0; @endphp
                        @foreach ($infrastructureCategories as $cat)
                            <div class="border rounded-xl p-4 bg-slate-50/50">
                                <h4 class="font-bold text-slate-900 border-b pb-2 mb-3 uppercase text-[11px]">{{ $cat->name }}</h4>
                                <div class="space-y-2">
                                    @foreach ($cat->items as $item)
                                        @php
                                            $counter++;
                                            $existingAss = $item->assessments->first();
                                        @endphp
                                        <div class="grid grid-cols-1 sm:grid-cols-4 items-center gap-2 bg-white p-2.5 rounded border border-slate-200">
                                            <div class="sm:col-span-2">
                                                <span class="font-semibold text-slate-900">{{ $item->name }}</span>
                                                <input type="hidden" name="items[{{ $counter }}][item_id]" value="{{ $item->id }}">
                                            </div>
                                            <div>
                                                <select name="items[{{ $counter }}][status]" class="w-full text-xs rounded border-slate-300">
                                                    <option value="available" {{ ($existingAss?->status ?? 'available') === 'available' ? 'selected' : '' }}>Available</option>
                                                    <option value="planned" {{ ($existingAss?->status) === 'planned' ? 'selected' : '' }}>Planned (Under Prep)</option>
                                                    <option value="not_available" {{ ($existingAss?->status) === 'not_available' ? 'selected' : '' }}>Not Available</option>
                                                    <option value="not_applicable" {{ ($existingAss?->status) === 'not_applicable' ? 'selected' : '' }}>Not Applicable</option>
                                                </select>
                                            </div>
                                            <div>
                                                <input type="number" name="items[{{ $counter }}][quantity]" value="{{ $existingAss?->quantity ?? 1 }}" placeholder="Quantity" class="w-full text-xs rounded border-slate-300">
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endforeach
                    </div>
                </x-card>
            @endif

            <!-- STEP 8: Staffing Plan -->
            @if ($step === 8)
                <x-card title="Step 8: Faculty & Operational Staffing Plan" subtitle="Configure human resource headcounts, positions, and average salaries">
                    <div class="overflow-x-auto text-xs">
                        <table class="w-full text-left">
                            <thead class="bg-slate-50 font-semibold text-slate-500 border-b">
                                <tr>
                                    <th class="p-2.5">Designation</th>
                                    <th class="p-2.5">Required Quantity</th>
                                    <th class="p-2.5">Existing Staff</th>
                                    <th class="p-2.5">Avg Monthly Salary (INR)</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                @foreach ($staffingPositions as $idx => $pos)
                                    @php $existingSt = $staffingAssessments->get($pos->id); @endphp
                                    <tr>
                                        <td class="p-2.5 font-semibold text-slate-900">
                                            {{ $pos->title }}
                                            <input type="hidden" name="staffing[{{ $idx }}][position_id]" value="{{ $pos->id }}">
                                        </td>
                                        <td class="p-2.5">
                                            <input type="number" name="staffing[{{ $idx }}][required_qty]" value="{{ old("staffing.{$idx}.required_qty", $existingSt?->required_qty ?? 1) }}" class="w-20 text-xs rounded border-slate-300">
                                        </td>
                                        <td class="p-2.5">
                                            <input type="number" name="staffing[{{ $idx }}][existing_qty]" value="{{ old("staffing.{$idx}.existing_qty", $existingSt?->existing_qty ?? 0) }}" class="w-20 text-xs rounded border-slate-300">
                                        </td>
                                        <td class="p-2.5">
                                            <input type="number" name="staffing[{{ $idx }}][monthly_salary]" value="{{ old("staffing.{$idx}.monthly_salary", $existingSt?->monthly_salary ?? $pos->default_monthly_salary) }}" class="w-32 text-xs rounded border-slate-300">
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </x-card>
      
            @endif



            <!-- STEP 11: Declaration -->
            @if ($step === 11)
                <x-card title="Step 11: Applicant Legal Declaration" subtitle="Solemn affirmation of facts, compliance with statutory norms, and submission consent">
                    <div class="space-y-6 text-xs text-slate-700">
                        <div class="bg-slate-50 p-4 rounded-xl border border-slate-200 leading-relaxed space-y-2">
                            <p class="font-bold text-slate-900">Affirmation of Statutory Feasibility Disclosures:</p>
                            <p>
                                I hereby solemnly declare and affirm that all statements, facts, figures, financial budgets, and demographic metrics submitted in this Feasibility Study Application are true, accurate, and correct to the best of my knowledge and belief.
                            </p>
                            <p>
                                The land parcel proposed for the school campus has an unencumbered title, conforming strictly to Board affiliation guidelines. The sponsoring Trust/Society has the requisite financial capability to service all projected capital and operational expenditures.
                            </p>
                        </div>

                        <div class="space-y-4">
                            <label class="flex items-start gap-3">
                                <input type="checkbox" name="declaration_accepted" value="1" {{ old('declaration_accepted', $application->declaration_accepted) ? 'checked' : '' }} required class="mt-0.5 rounded border-slate-300 text-indigo-600">
                                <span class="font-semibold text-slate-900">
                                    I accept and solemnly affirm all contents of this Feasibility Study Report. <span class="text-rose-500">*</span>
                                </span>
                            </label>

                            <div class="max-w-md">
                                <label class="block font-medium text-slate-700 mb-1">Authorised Signatory Name <span class="text-rose-500">*</span></label>
                                <input type="text" name="declaration_signed_by" value="{{ old('declaration_signed_by', $application->declaration_signed_by ?? auth()->user()->name) }}" required class="w-full text-xs rounded border-slate-300">
                            </div>
                        </div>
                    </div>
                </x-card>
            @endif

            <!-- STEP 12: Review & Submit -->
            @if ($step === 12)
                <x-card title="Step 12: Final Review & Submission" subtitle="Review complete disclosure, check mandatory requirements, and lock application">
                    <div class="space-y-6 text-xs">
                        @if (!empty($missingDocuments))
                            <x-alert type="warning" title="Missing Mandatory Documents:">
                                <p>You must upload the following mandatory documents before you can submit:</p>
                                <ul class="list-disc pl-5 mt-1 space-y-0.5 font-medium">
                                    @foreach ($missingDocuments as $mDoc)
                                        <li>{{ $mDoc->title }}</li>
                                    @endforeach
                                </ul>
                            </x-alert>
                        @endif

                        <div class="bg-slate-50 p-4 rounded-xl border border-slate-200">
                            <h4 class="font-bold text-slate-900 mb-3 text-sm">Application Summary Checklist</h4>
                            <dl class="grid grid-cols-2 sm:grid-cols-4 gap-4">
                                <div>
                                    <dt class="text-slate-400">School Name</dt>
                                    <dd class="font-bold text-slate-900 mt-0.5">{{ $school->name }}</dd>
                                </div>
                                <div>
                                    <dt class="text-slate-400">Target Board</dt>
                                    <dd class="font-bold text-slate-900 mt-0.5 uppercase">{{ $school->board?->value ?? 'CBSE' }}</dd>
                                </div>
                                <div>
                                    <dt class="text-slate-400">Land Area</dt>
                                    <dd class="font-bold text-slate-900 mt-0.5">{{ $land?->total_land_area ?? 0 }} {{ $land?->area_unit ?? 'Acres' }}</dd>
                                </div>
                                <div>
                                    <dt class="text-slate-400">Calculated Capture</dt>
                                    <dd class="font-bold text-slate-900 mt-0.5">{{ number_format($demographic?->potential_students_calculated ?? 0) }} Students</dd>
                                </div>
                            </dl>
                        </div>

                        <div class="p-4 bg-indigo-50/50 rounded-xl border border-indigo-100 flex items-center justify-between">
                            <div>
                                <h4 class="font-bold text-indigo-950">Ready to Submit?</h4>
                                <p class="text-indigo-700 text-[11px] mt-0.5">Upon submission, your application will advance to Preliminary Scrutiny and Document Verification by Feasibility Officers.</p>
                            </div>
                            <button type="submit" formaction="{{ route('applications.wizard.submit', $application) }}" formmethod="POST" class="px-6 py-2.5 bg-emerald-600 hover:bg-emerald-500 text-white font-bold rounded-lg shadow-sm transition">
                                Submit Application Now &rarr;
                            </button>
                        </div>
                    </div>
                </x-card>
            @endif

            <!-- Wizard Bottom Action Navigation -->
            <div class="flex items-center justify-between pt-4 border-t border-slate-200">
                <div>
                    @if ($step > 1)
                        <a href="{{ route('applications.wizard.step', [$application, $step - 1]) }}" class="px-4 py-2 text-xs font-semibold text-slate-700 bg-white border border-slate-300 rounded-lg hover:bg-slate-50 transition">
                            &larr; Previous Step
                        </a>
                    @endif
                </div>

                <div class="flex items-center gap-3">
                    <button type="submit" name="action" value="save_draft" class="px-4 py-2 text-xs font-semibold text-slate-700 bg-slate-100 hover:bg-slate-200 rounded-lg transition">
                        Save Draft & Continue Later
                    </button>

                    @if ($step < 12)
                        <button type="submit" class="px-5 py-2 text-xs font-semibold text-white bg-indigo-600 hover:bg-indigo-500 rounded-lg shadow-sm transition">
                            Save & Proceed to Step {{ $step + 1 }} &rarr;
                        </button>
                    @endif
                </div>
            </div>
        </form>
        @endif
    </div>
</x-app-layout>
