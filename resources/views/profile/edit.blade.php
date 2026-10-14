<x-app-layout>
    <x-slot name="title">My Profile & Assigned Roles — YES INDIA FOUNDATION</x-slot>

    <!-- Full-Width Container -->
    <div class="w-full space-y-6" x-data="{ activeSection: 'profile' }">
        
        <!-- Executive White Profile Header Card -->
        <div class="w-full bg-white rounded-3xl p-6 sm:p-8 border border-slate-200/90 shadow-sm">
            <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-6">
                <!-- User Profile & Assigned Roles -->
                <div class="flex items-center gap-5 sm:gap-6">
                    <!-- Rounded Avatar -->
                    <div class="w-20 h-20 sm:w-24 sm:h-24 rounded-3xl overflow-hidden border-4 border-slate-100 shadow-sm shrink-0 bg-slate-100">
                        @if ($user->avatar)
                            <img src="{{ asset('storage/' . $user->avatar) }}" alt="{{ $user->name }}" class="w-full h-full object-cover">
                        @else
                            <div class="w-full h-full bg-gradient-to-tr from-amber-100 via-amber-200 to-amber-300 text-amber-900 flex items-center justify-center font-extrabold text-3xl">
                                {{ strtoupper(substr($user->name, 0, 1)) }}
                            </div>
                        @endif
                    </div>

                    <div class="space-y-1.5 min-w-0">
                        <!-- Name & Assigned Roles Badges -->
                        <div class="flex items-center gap-3 flex-wrap">
                            <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight">{{ $user->name }}</h1>
                            
                            <!-- Assigned Roles -->
                            <div class="flex items-center gap-2 flex-wrap">
                                @forelse ($roles as $role)
                                    <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-xs font-extrabold tracking-wider uppercase border border-cyan-500 text-cyan-700 bg-cyan-50 shadow-2xs">
                                        <span class="w-1.5 h-1.5 rounded-full bg-cyan-500"></span>
                                        {{ $role->name }}
                                    </span>
                                @empty
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-extrabold tracking-wider uppercase border border-slate-300 text-slate-600 bg-slate-100">
                                        BOARD ADMIN
                                    </span>
                                @endforelse

                                @if ($user->two_factor_enabled)
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold text-emerald-700 bg-emerald-50 border border-emerald-300 uppercase tracking-wider">
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                                        2FA Protected
                                    </span>
                                @endif
                            </div>
                        </div>

                        <!-- User Meta Details -->
                        <div class="flex items-center gap-4 text-xs sm:text-sm text-slate-600 flex-wrap">
                            <span class="font-semibold text-slate-800">{{ $user->email }}</span>
                            @if ($user->phone)
                                <span>&bull; <span class="text-slate-500">{{ $user->phone }}</span></span>
                            @endif
                            @if ($user->designation)
                                <span>&bull; <span class="text-slate-500">{{ $user->designation }}</span></span>
                            @endif
                        </div>

                        <!-- Organization & System Footprint -->
                        <div class="flex items-center gap-4 text-xs text-slate-400 pt-1 flex-wrap">
                            <span>Organization: <strong class="text-slate-800">YES INDIA FOUNDATION</strong></span>
                            <span>Last Sign-In: <strong class="text-slate-700 font-mono">{{ $user->last_login_at?->format('d M Y, h:i A') ?? 'Initial Session' }}</strong></span>
                            <span>IP Address: <strong class="text-slate-700 font-mono">{{ $user->last_login_ip ?? '127.0.0.1' }}</strong></span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Full-Width Navigation Tabs -->
            <div class="mt-8 pt-5 border-t border-slate-100 flex items-center gap-2 overflow-x-auto text-xs font-semibold">
                <button @click="activeSection = 'profile'"
                        :class="activeSection === 'profile' ? 'bg-slate-900 text-white shadow-xs' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100'"
                        class="px-4 py-2.5 rounded-xl transition">
                    Profile Details
                </button>
                <button @click="activeSection = 'roles'"
                        :class="activeSection === 'roles' ? 'bg-slate-900 text-white shadow-xs' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100'"
                        class="px-4 py-2.5 rounded-xl transition flex items-center gap-2">
                    <span>Assigned Roles</span>
                </button>
                <button @click="activeSection = 'security'"
                        :class="activeSection === 'security' ? 'bg-slate-900 text-white shadow-xs' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100'"
                        class="px-4 py-2.5 rounded-xl transition">
                    Password & Security
                </button>
                <button @click="activeSection = 'two_factor'"
                        :class="activeSection === 'two_factor' ? 'bg-slate-900 text-white shadow-xs' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100'"
                        class="px-4 py-2.5 rounded-xl transition flex items-center gap-1.5">
                    <span>Two-Factor Authentication</span>
                    @if ($user->two_factor_enabled)
                        <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                    @endif
                </button>
                <button @click="activeSection = 'activity'"
                        :class="activeSection === 'activity' ? 'bg-slate-900 text-white shadow-xs' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100'"
                        class="px-4 py-2.5 rounded-xl transition">
                    Session Audit Trail
                </button>
                <button @click="activeSection = 'privacy'"
                        :class="activeSection === 'privacy' ? 'bg-slate-900 text-white shadow-xs' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100'"
                        class="px-4 py-2.5 rounded-xl transition flex items-center gap-1.5">
                    <span>Data Rights & Privacy</span>
                </button>
            </div>
        </div>

        <!-- SECTION 1: PROFILE DETAILS (FULL WIDTH BOX) -->
        <div x-show="activeSection === 'profile'" class="w-full">
            <div class="w-full bg-white rounded-3xl border border-slate-200/90 p-6 sm:p-8 shadow-xs">

                <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data" class="w-full space-y-6 text-xs">
                    @csrf
                    @method('PATCH')

                    <!-- Profile Photo Upload Box (Max 1 MB) -->
                    <div class="w-full p-4 sm:p-5 rounded-2xl border border-slate-200 bg-slate-50/70 flex flex-col sm:flex-row items-center gap-5"
                         x-data="{ photoName: null, photoPreview: null }">
                        <div class="relative w-18 h-18 sm:w-20 sm:h-20 rounded-2xl overflow-hidden border-2 border-slate-200 bg-white shrink-0 shadow-2xs">
                            <!-- Current Photo / Initials -->
                            <div x-show="!photoPreview" class="w-full h-full">
                                @if ($user->avatar)
                                    <img src="{{ asset('storage/' . $user->avatar) }}" alt="{{ $user->name }}" class="w-full h-full object-cover">
                                @else
                                    <div class="w-full h-full bg-gradient-to-tr from-amber-100 to-amber-200 text-amber-900 flex items-center justify-center font-bold text-2xl">
                                        {{ strtoupper(substr($user->name, 0, 1)) }}
                                    </div>
                                @endif
                            </div>
                            <!-- Live Selected Photo Preview -->
                            <div x-show="photoPreview" class="w-full h-full" style="display: none;">
                                <span class="block w-full h-full bg-cover bg-no-repeat bg-center"
                                      :style="'background-image: url(\'' + photoPreview + '\');'"></span>
                            </div>
                        </div>

                        <div class="flex-1 text-center sm:text-left space-y-1">
                            <label class="block font-bold text-slate-900 text-xs">Institutional Profile Photo</label>
                            <p class="text-[11px] text-slate-500">
                                Upload your personal profile portrait (JPG, PNG, or WEBP). Maximum allowed size: <span class="font-bold text-slate-700">1 MB (1024 KB)</span>.
                            </p>
                            
                            <input type="file" name="avatar" id="avatar_input" accept="image/jpeg,image/png,image/webp" class="hidden"
                                   @change="
                                       const file = $event.target.files[0];
                                       if (file) {
                                           photoName = file.name;
                                           const reader = new FileReader();
                                           reader.onload = (e) => { photoPreview = e.target.result; };
                                           reader.readAsDataURL(file);
                                       }
                                   ">

                            <div class="pt-1.5 flex flex-wrap items-center justify-center sm:justify-start gap-3">
                                <button type="button" @click="document.getElementById('avatar_input').click()"
                                        class="px-3.5 py-1.5 rounded-xl text-xs font-semibold text-slate-800 bg-white border border-slate-300 hover:bg-slate-100 hover:border-slate-400 shadow-2xs transition">
                                    Browse & Select Photo
                                </button>
                                <span x-show="photoName" x-text="photoName" class="text-[11px] font-mono text-cyan-700 truncate max-w-xs"></span>
                            </div>
                            @error('avatar') <span class="text-rose-500 text-[11px] block mt-1 font-semibold">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div class="w-full grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                        <div>
                            <label class="block font-semibold text-slate-700 mb-1">Full Legal Name <span class="text-rose-500">*</span></label>
                            <input type="text" name="name" value="{{ old('name', $user->name) }}" required
                                   class="w-full text-xs rounded-xl border-slate-300 focus:ring-2 focus:ring-cyan-500 focus:border-cyan-500 py-3 px-3.5 shadow-2xs">
                            @error('name') <span class="text-rose-500 text-[11px] mt-1">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block font-semibold text-slate-700 mb-1">Registered Account Email <span class="text-rose-500">*</span></label>
                            <input type="email" name="email" value="{{ old('email', $user->email) }}" required
                                   class="w-full text-xs rounded-xl border-slate-300 focus:ring-2 focus:ring-cyan-500 focus:border-cyan-500 py-3 px-3.5 shadow-2xs">
                            @error('email') <span class="text-rose-500 text-[11px] mt-1">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block font-semibold text-slate-700 mb-1">Official Mobile Phone</label>
                            <input type="text" name="phone" value="{{ old('phone', $user->phone) }}" placeholder="+91 98765 43210"
                                   class="w-full text-xs rounded-xl border-slate-300 focus:ring-2 focus:ring-cyan-500 focus:border-cyan-500 py-3 px-3.5 shadow-2xs">
                            @error('phone') <span class="text-rose-500 text-[11px] mt-1">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block font-semibold text-slate-700 mb-1">Designation / Role Title</label>
                            <input type="text" name="designation" value="{{ old('designation', $user->designation) }}" placeholder="e.g. Lead Feasibility Evaluator"
                                   class="w-full text-xs rounded-xl border-slate-300 focus:ring-2 focus:ring-cyan-500 focus:border-cyan-500 py-3 px-3.5 shadow-2xs">
                            @error('designation') <span class="text-rose-500 text-[11px] mt-1">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div class="w-full flex items-center justify-end pt-5 border-t border-slate-100">
                        <button type="submit"
                                class="px-7 py-3 text-xs font-bold text-white bg-slate-900 hover:bg-slate-800 rounded-xl shadow-sm transition flex items-center gap-2">
                            <span>Save Profile Changes</span>
                            <svg class="w-4 h-4 text-cyan-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- SECTION 2: ASSIGNED ROLES (FULL WIDTH BOX) -->
        <div x-show="activeSection === 'roles'" class="w-full space-y-6">
            <div class="w-full bg-white rounded-3xl border border-slate-200/90 p-6 sm:p-8 shadow-xs">

                <!-- Single Authorised System Privileges Grid -->
                <div class="w-full mt-8 pt-6 border-t border-slate-100">
                    <div class="flex items-center justify-between mb-4">
                        <h4 class="font-bold text-xs uppercase tracking-wider text-slate-700">
                            Active Institutional Privileges ({{ $permissions->count() }}):
                        </h4>
                        <span class="text-[11px] text-slate-400">All unique capabilities granted to your account</span>
                    </div>

                    <div class="w-full grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-2.5">
                        @forelse ($permissions as $perm)
                            <div class="flex items-center gap-2.5 p-3 rounded-xl bg-slate-50 border border-slate-200/80 text-xs font-mono text-slate-700 hover:bg-slate-100/80 transition">
                                <span class="w-2 h-2 rounded-full bg-cyan-500 shrink-0"></span>
                                <span class="truncate font-semibold">{{ $perm->name }}</span>
                            </div>
                        @empty
                            <div class="col-span-4 p-4 text-center text-slate-400 text-xs">
                                No specific permissions assigned.
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>

        <!-- SECTION 3: PASSWORD & CREDENTIALS (FULL WIDTH BOX) -->
        <div x-show="activeSection === 'security'" class="w-full">
            <div class="w-full bg-white rounded-3xl border border-slate-200/90 p-6 sm:p-8 shadow-xs">
                <div class="border-b border-slate-100 pb-4 mb-6">
                    <h3 class="text-base font-bold text-slate-900 tracking-tight">Security Credentials & Password Management</h3>
                    <p class="text-xs text-slate-500 mt-0.5">Maintain institutional cybersecurity by regularly updating your passphrase.</p>
                </div>

                <form method="POST" action="{{ route('password.update') }}" class="w-full space-y-6 text-xs">
                    @csrf
                    @method('PUT')

                    <div class="w-full grid grid-cols-1 sm:grid-cols-3 gap-6">
                        <div>
                            <label class="block font-semibold text-slate-700 mb-1">Current Password <span class="text-rose-500">*</span></label>
                            <input type="password" name="current_password" required
                                   class="w-full text-xs rounded-xl border-slate-300 focus:ring-2 focus:ring-cyan-500 focus:border-cyan-500 py-3 px-3.5 shadow-2xs">
                            @error('current_password', 'updatePassword') <span class="text-rose-500 text-[11px] mt-1">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block font-semibold text-slate-700 mb-1">New Password <span class="text-rose-500">*</span></label>
                            <input type="password" name="password" required
                                   class="w-full text-xs rounded-xl border-slate-300 focus:ring-2 focus:ring-cyan-500 focus:border-cyan-500 py-3 px-3.5 shadow-2xs">
                            @error('password', 'updatePassword') <span class="text-rose-500 text-[11px] mt-1">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block font-semibold text-slate-700 mb-1">Confirm New Password <span class="text-rose-500">*</span></label>
                            <input type="password" name="password_confirmation" required
                                   class="w-full text-xs rounded-xl border-slate-300 focus:ring-2 focus:ring-cyan-500 focus:border-cyan-500 py-3 px-3.5 shadow-2xs">
                        </div>
                    </div>

                    <div class="w-full flex items-center justify-end pt-5 border-t border-slate-100">
                        <button type="submit"
                                class="px-7 py-3 text-xs font-bold text-white bg-slate-900 hover:bg-slate-800 rounded-xl shadow-sm transition">
                            Update Account Password
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- SECTION 4: TWO-FACTOR AUTHENTICATION (FULL WIDTH BOX) -->
        <div x-show="activeSection === 'two_factor'" class="w-full"
             x-data="{
                 showQr: {{ $user->two_factor_enabled ? 'false' : 'true' }},
                 copiedCodes: false,
                 copiedSecret: false,
                 copyAllCodes() {
                     const codes = Array.from(this.$refs.codesContainer.querySelectorAll('span')).map(el => el.textContent.trim()).join('\n');
                     const text = '2FA Emergency Backup Codes for {{ $user->email }}:\n\n' + codes;
                     navigator.clipboard.writeText(text);
                     this.copiedCodes = true;
                     setTimeout(() => { this.copiedCodes = false; }, 2000);
                 }
             }">
            <div class="w-full bg-white rounded-3xl border border-slate-200/90 p-6 sm:p-8 shadow-xs space-y-8">
                <!-- Section Header -->
                <div class="border-b border-slate-100 pb-5 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                    <div>
                        <h3 class="text-base font-bold text-slate-900 tracking-tight">Two-Factor Authentication (2FA) & Authenticator App</h3>
                        <p class="text-xs text-slate-500 mt-0.5">Protect your institutional portal access using TOTP Authenticator Apps (Google Authenticator, Microsoft Authenticator, Authy).</p>
                    </div>
                    <div>
                        @if ($user->two_factor_enabled)
                            <span class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full text-xs font-extrabold uppercase tracking-wider bg-emerald-50 text-emerald-700 border border-emerald-300 shadow-2xs">
                                <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                                2FA Protection Active
                            </span>
                        @else
                            <span class="inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-full text-xs font-bold uppercase tracking-wider bg-amber-50 text-amber-800 border border-amber-300">
                                Setup Incomplete
                            </span>
                        @endif
                    </div>
                </div>

                @if ($user->two_factor_enabled)
                    <!-- Active Status Banner -->
                    <div class="p-5 rounded-2xl bg-emerald-50/70 border border-emerald-200 flex flex-col md:flex-row items-start md:items-center justify-between gap-4">
                        <div class="space-y-1">
                            <h4 class="font-bold text-emerald-950 text-sm flex items-center gap-2">
                                <svg class="w-5 h-5 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                                </svg>
                                Authenticator App Paired & Active
                            </h4>
                            <p class="text-xs text-emerald-800">
                                Your account is fortified. Each sign-in requires entering a dynamic 6-digit TOTP code from your paired authenticator app or an emergency recovery backup code.
                            </p>
                            <p class="text-[11px] text-emerald-700 font-mono">
                                Confirmed on: {{ $user->two_factor_confirmed_at?->format('d M Y, h:i A') ?? 'Active' }}
                            </p>
                        </div>

                        <div class="flex items-center gap-3 shrink-0">
                            <button type="button" @click="showQr = !showQr"
                                    class="px-4 py-2 rounded-xl text-xs font-semibold text-emerald-800 bg-white border border-emerald-300 hover:bg-emerald-100/60 shadow-2xs transition">
                                <span x-text="showQr ? 'Hide QR & Secret Key' : 'View QR & Secret Key'"></span>
                            </button>

                            <form method="POST" action="{{ route('profile.two-factor') }}" x-data="{ confirmingDisable: false }">
                                @csrf
                                <input type="hidden" name="enable" value="0">
                                
                                <button x-show="!confirmingDisable" type="button" @click="confirmingDisable = true"
                                        class="px-4 py-2 rounded-xl text-xs font-bold text-rose-700 bg-white border border-rose-300 hover:bg-rose-50 shadow-2xs transition">
                                    Disable 2FA
                                </button>

                                <div x-show="confirmingDisable" style="display: none;" class="flex items-center gap-2">
                                    <span class="text-[11px] font-bold text-rose-800">Confirm disable?</span>
                                    <button type="submit"
                                            class="px-3 py-1.5 rounded-lg text-xs font-bold text-white bg-rose-600 hover:bg-rose-700 shadow-2xs transition">
                                        Yes, Disable
                                    </button>
                                    <button type="button" @click="confirmingDisable = false"
                                            class="px-2 py-1.5 rounded-lg text-xs font-medium text-slate-600 hover:bg-slate-100 transition">
                                        Cancel
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                @endif

                <!-- Setup Step 1: QR Code & Secret Key Box -->
                <div x-show="showQr" class="w-full grid grid-cols-1 lg:grid-cols-12 gap-8 pt-2">
                    <!-- Left: QR Code Display -->
                    <div class="lg:col-span-5 flex flex-col items-center justify-center p-6 rounded-2xl bg-slate-50 border border-slate-200 text-center space-y-4">
                        <div class="text-left w-full">
                            <span class="text-[10px] font-bold text-cyan-700 uppercase tracking-wider bg-cyan-100 px-2 py-0.5 rounded">Step 1</span>
                            <h4 class="font-bold text-slate-900 text-sm mt-1">Scan with Authenticator App</h4>
                            <p class="text-[11px] text-slate-500">Scan this QR code in Google Authenticator, Microsoft Authenticator, or Authy:</p>
                        </div>

                        <!-- ISO/IEC 18004 Standard QR Code (High Precision) -->
                        <div class="p-4 bg-white rounded-2xl border border-slate-200 shadow-sm flex items-center justify-center">
                            <img src="{{ $qrDataUri }}" alt="Two-Factor Authenticator QR Code"
                                 class="w-48 h-48 sm:w-52 sm:h-52 block object-contain select-none"
                                 loading="eager">
                        </div>

                        <div class="flex items-center gap-1.5 text-[11px] text-emerald-700 bg-emerald-50 px-3 py-1.5 rounded-xl border border-emerald-200">
                            <svg class="w-4 h-4 text-emerald-600 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <span>ISO-standard QR code readable by all camera scanners</span>
                        </div>
                    </div>

                    <!-- Right: Manual Entry Key & Confirmation Form -->
                    <div class="lg:col-span-7 space-y-6 flex flex-col justify-between">
                        <!-- Manual Entry Secret Key -->
                        <div class="p-5 rounded-2xl border border-slate-200 bg-slate-50 space-y-3">
                            <div>
                                <span class="text-[10px] font-bold text-slate-500 uppercase tracking-wider">Manual Configuration Key:</span>
                                <p class="text-[11px] text-slate-500">If you cannot scan the QR code, manually type this key into your app:</p>
                            </div>

                            <div class="flex items-center gap-2">
                                <input type="text" readonly value="{{ chunk_split($secretKey, 4, ' ') }}"
                                       class="w-full font-mono text-sm font-bold bg-white border border-slate-300 rounded-xl px-3.5 py-2 text-slate-900 text-center tracking-wider select-all shadow-2xs">
                                <button type="button"
                                        @click="navigator.clipboard.writeText('{{ $secretKey }}'); copiedSecret = true; setTimeout(() => copiedSecret = false, 2000)"
                                        class="px-4 py-2 bg-slate-900 hover:bg-slate-800 text-white rounded-xl text-xs font-bold shrink-0 transition">
                                    <span x-text="copiedSecret ? 'Copied!' : 'Copy Key'"></span>
                                </button>
                            </div>
                        </div>

                        @if (!$user->two_factor_enabled)
                            <!-- Step 2: Confirm 6-Digit Code -->
                            <div class="p-5 rounded-2xl border-2 border-cyan-500/30 bg-cyan-50/40 space-y-4">
                                <div>
                                    <span class="text-[10px] font-bold text-cyan-700 uppercase tracking-wider bg-cyan-100 px-2 py-0.5 rounded">Step 2</span>
                                    <h4 class="font-bold text-slate-900 text-sm mt-1">Confirm Authentication Token</h4>
                                    <p class="text-[11px] text-slate-600">Enter the 6-digit code currently generated by your Authenticator app to finalize pairing:</p>
                                </div>

                                <form method="POST" action="{{ route('profile.two-factor.confirm') }}" class="space-y-3">
                                    @csrf
                                    <div class="flex items-center gap-3">
                                        <input type="text" name="code" required inputmode="numeric" maxlength="6" placeholder="000000"
                                               class="w-48 text-center tracking-widest text-lg font-mono font-bold py-2.5 rounded-xl border border-slate-300 focus:ring-2 focus:ring-cyan-500 focus:border-cyan-500 bg-white shadow-2xs">
                                        <button type="submit"
                                                class="flex-1 py-3 px-5 text-xs font-bold text-white bg-slate-900 hover:bg-slate-800 rounded-xl shadow-md shadow-slate-900/10 transition flex items-center justify-center gap-2">
                                            <span>Verify & Activate 2FA</span>
                                            <svg class="w-4 h-4 text-cyan-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                        </button>
                                    </div>
                                    @error('code') <span class="text-rose-500 text-[11px] block font-semibold">{{ $message }}</span> @enderror
                                    @error('totp_code') <span class="text-rose-500 text-[11px] block font-semibold">{{ $message }}</span> @enderror
                                </form>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Emergency Backup Recovery Codes Section -->
                <div class="w-full pt-6 border-t border-slate-100 space-y-4">
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                        <div>
                            <div class="flex items-center gap-2">
                                <h4 class="font-bold text-sm text-slate-900">Emergency Backup Recovery Codes</h4>
                                <span class="px-2 py-0.5 text-[10px] font-bold rounded-full bg-slate-100 text-slate-700 border border-slate-200">
                                    {{ count($recoveryCodes) }} Codes Remaining
                                </span>
                            </div>
                            <p class="text-xs text-slate-500 mt-0.5">
                                If you lose access to your authenticator app, each single-use recovery code can be used once to sign in. Store these in a safe password manager.
                            </p>
                        </div>

                        <div class="flex items-center gap-2 shrink-0">
                            <!-- Copy All Codes -->
                            <button type="button"
                                    @click="copyAllCodes()"
                                    class="px-3.5 py-2 text-xs font-semibold text-slate-700 bg-white border border-slate-300 hover:bg-slate-50 rounded-xl shadow-2xs transition flex items-center gap-1.5">
                                <svg class="w-4 h-4 text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z" />
                                </svg>
                                <span x-text="copiedCodes ? 'Codes Copied!' : 'Copy All Codes'"></span>
                            </button>

                            <!-- Download Codes -->
                            <a href="data:text/plain;charset=utf-8,{{ rawurlencode("YES INDIA FOUNDATION — 2FA Emergency Backup Codes\nAccount: {$user->email}\nGenerated: " . now()->toDateTimeString() . "\n\n" . implode("\n", $recoveryCodes)) }}"
                               download="yes_india_2fa_backup_codes.txt"
                               class="px-3.5 py-2 text-xs font-semibold text-slate-700 bg-white border border-slate-300 hover:bg-slate-50 rounded-xl shadow-2xs transition flex items-center gap-1.5">
                                <svg class="w-4 h-4 text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                                </svg>
                                <span>Download (.txt)</span>
                            </a>

                            <!-- Regenerate Codes -->
                            <form method="POST" action="{{ route('profile.two-factor.recovery-codes') }}" x-data="{ confirmingRegen: false }">
                                @csrf
                                <button x-show="!confirmingRegen" type="button" @click="confirmingRegen = true"
                                        class="px-3.5 py-2 text-xs font-semibold text-cyan-700 bg-cyan-50 hover:bg-cyan-100 border border-cyan-200 rounded-xl transition">
                                    Regenerate Codes
                                </button>
                                
                                <div x-show="confirmingRegen" style="display: none;" class="flex items-center gap-1.5">
                                    <button type="submit"
                                            class="px-3 py-1.5 rounded-lg text-xs font-bold text-white bg-cyan-700 hover:bg-cyan-800 shadow-2xs transition">
                                        Confirm
                                    </button>
                                    <button type="button" @click="confirmingRegen = false"
                                            class="px-2 py-1.5 rounded-lg text-xs font-medium text-slate-500 hover:bg-slate-100 transition">
                                        Cancel
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>

                    <!-- Backup Codes Grid (8 Codes) -->
                    <div x-ref="codesContainer" class="w-full grid grid-cols-2 sm:grid-cols-4 gap-3 p-4 rounded-2xl bg-slate-50 border border-slate-200">
                        @foreach ($recoveryCodes as $code)
                            <div class="p-2.5 rounded-xl bg-white border border-slate-200 text-center shadow-2xs">
                                <span class="font-mono text-xs font-bold text-slate-800 tracking-wider select-all">{{ $code }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        <!-- SECTION 5: SESSION AUDIT TRAIL (FULL WIDTH BOX) -->
        <div x-show="activeSection === 'activity'" class="w-full">
            <div class="w-full bg-white rounded-3xl border border-slate-200/90 p-6 sm:p-8 shadow-xs">
                <div class="border-b border-slate-100 pb-4 mb-6">
                    <h3 class="text-base font-bold text-slate-900 tracking-tight">Your Recent Session Activity</h3>
                    <p class="text-xs text-slate-500 mt-0.5">Immutable audit record of your recent portal logins and operations.</p>
                </div>

                <div class="w-full overflow-x-auto text-xs">
                    <table class="w-full text-left">
                        <thead class="bg-slate-50 font-semibold text-slate-500 border-b border-slate-100">
                            <tr>
                                <th class="p-3.5">Timestamp</th>
                                <th class="p-3.5">Action Performed</th>
                                <th class="p-3.5">IP Address</th>
                                <th class="p-3.5 text-right">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @forelse ($recentActivities as $act)
                                <tr class="hover:bg-slate-50/60 transition">
                                    <td class="p-3.5 font-mono text-slate-500">{{ $act->created_at->format('d M Y, H:i:s') }}</td>
                                    <td class="p-3.5 font-semibold text-slate-900 capitalize">{{ str_replace('_', ' ', $act->action) }}</td>
                                    <td class="p-3.5 font-mono text-slate-600">{{ $act->ip_address ?? '127.0.0.1' }}</td>
                                    <td class="p-3.5 text-right">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-cyan-50 text-cyan-700 border border-cyan-200 uppercase">
                                            Verified
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="p-8 text-center text-slate-400">No session events recorded yet.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- SECTION 6: DATA RIGHTS & PRIVACY WORKFLOW (DPDP ACT / GDPR) -->
        <div x-show="activeSection === 'privacy'" class="w-full space-y-6">
            <div class="w-full bg-white rounded-3xl border border-slate-200/90 p-6 sm:p-8 shadow-xs space-y-8">
                <div class="border-b border-slate-100 pb-4">
                    <div class="flex items-center gap-2 mb-1">
                        <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-wider bg-indigo-50 text-indigo-700 border border-indigo-200">
                            DPDP Act 2023 &bull; ISO 27001
                        </span>
                        <span class="text-xs text-slate-400">Institutional Compliance</span>
                    </div>
                    <h3 class="text-base font-bold text-slate-900 tracking-tight">Data Principal Rights & Privacy Controls</h3>
                    <p class="text-xs text-slate-500 mt-0.5">Exercise statutory data subject rights regarding your personal records, registered institutions, and audit trails.</p>
                </div>

                <!-- Feature Grid: Portability & Erasure -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <!-- Right to Portability (Download Data Dossier) -->
                    <div class="p-6 rounded-2xl bg-slate-50 border border-slate-200 space-y-4 flex flex-col justify-between">
                        <div class="space-y-2">
                            <div class="w-10 h-10 rounded-xl bg-indigo-100 text-indigo-700 flex items-center justify-center font-bold">
                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                                </svg>
                            </div>
                            <h4 class="font-bold text-slate-900 text-sm">Right to Data Portability (DSAR)</h4>
                            <p class="text-xs text-slate-600 leading-relaxed">
                                Download a machine-readable, cryptographic SHA-256 verified JSON export of all personal data held under your account, including profile metadata, registered schools, submitted feasibility applications, and immutable security audit logs.
                            </p>
                        </div>

                        <div class="pt-2">
                            <a href="{{ route('profile.data.export') }}"
                               class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-500 text-white font-bold text-xs shadow-xs transition">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                                <span>Download Personal Data Dossier (.JSON)</span>
                            </a>
                        </div>
                    </div>

                    <!-- Right to Erasure / Account Deletion -->
                    <div class="p-6 rounded-2xl bg-rose-50/50 border border-rose-200/80 space-y-4 flex flex-col justify-between" x-data="{ confirmingDeletion: false }">
                        <div class="space-y-2">
                            <div class="w-10 h-10 rounded-xl bg-rose-100 text-rose-700 flex items-center justify-center font-bold">
                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                </svg>
                            </div>
                            <h4 class="font-bold text-rose-950 text-sm">Right to Erasure (Account Deletion)</h4>
                            <p class="text-xs text-rose-800/80 leading-relaxed">
                                Request permanent deletion of your user account and profile credentials. Active statutory feasibility audit applications will be anonymized in accordance with legal preservation retention schedules.
                            </p>
                        </div>

                        <div class="pt-2">
                            <button type="button" @click="confirmingDeletion = true"
                                    class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-rose-600 hover:bg-rose-500 text-white font-bold text-xs shadow-xs transition">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                                <span>Delete Account & Personal Records</span>
                            </button>
                        </div>

                        <!-- Deletion Confirmation Modal -->
                        <div x-show="confirmingDeletion" style="display: none;" class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
                            <div class="flex items-center justify-center min-h-screen px-4 text-center">
                                <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-xs transition-opacity" @click="confirmingDeletion = false"></div>
                                <div class="relative bg-white rounded-3xl p-6 sm:p-8 max-w-md w-full border border-slate-200 shadow-xl text-left space-y-4">
                                    <h4 class="text-lg font-bold text-slate-900 tracking-tight">Confirm Account Erasure</h4>
                                    <p class="text-xs text-slate-600 leading-relaxed">
                                        Are you sure you want to permanently delete your account? This action cannot be reversed. Please re-enter your account password to confirm identity.
                                    </p>
                                    <form method="POST" action="{{ route('profile.destroy') }}" class="space-y-4">
                                        @csrf
                                        @method('DELETE')
                                        <div>
                                            <label class="block font-medium text-slate-700 mb-1 text-xs">Current Account Password</label>
                                            <input type="password" name="password" required class="w-full text-xs rounded-xl border-slate-300 py-2.5 px-3">
                                            @if ($errors->userDeletion->has('password'))
                                                <p class="text-xs text-rose-600 mt-1">{{ $errors->userDeletion->first('password') }}</p>
                                            @endif
                                        </div>
                                        <div class="flex items-center justify-end gap-2 pt-2">
                                            <button type="button" @click="confirmingDeletion = false" class="px-4 py-2 rounded-xl text-xs font-semibold text-slate-600 hover:bg-slate-100 transition">
                                                Cancel
                                            </button>
                                            <button type="submit" class="px-4 py-2 rounded-xl text-xs font-bold text-white bg-rose-600 hover:bg-rose-500 shadow-xs transition">
                                                Confirm Permanent Deletion
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Grievance Redressal & DPO Notice Card -->
                <div class="p-5 rounded-2xl bg-indigo-50/60 border border-indigo-100 flex flex-col sm:flex-row sm:items-center justify-between gap-4 text-xs text-indigo-950">
                    <div class="space-y-1">
                        <span class="font-bold block">Statutory Grievance Redressal Officer (DPDP Act 2023)</span>
                        <p class="text-slate-600 text-[11px]">National Secretariat &bull; Email: <a href="mailto:compliance@yesindiafoundation.com" class="text-indigo-600 font-semibold underline">compliance@yesindiafoundation.com</a> &bull; Response SLA: Within 48 hours.</p>
                    </div>
                    <a href="{{ route('privacy.policy') }}" target="_blank" class="px-3.5 py-2 bg-white border border-indigo-200 text-indigo-700 font-semibold rounded-xl hover:bg-indigo-50 transition shrink-0 inline-flex items-center gap-1.5">
                        <span>View Privacy Policy</span>
                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                        </svg>
                    </a>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
