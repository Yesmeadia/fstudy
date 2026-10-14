<div x-data="globalConfirmModal()"
     x-on:show-confirm.window="openConfirm($event.detail)"
     x-show="isOpen"
     x-cloak
     class="fixed inset-0 z-[100] flex items-center justify-center p-4 sm:p-6 overflow-y-auto"
     role="dialog"
     aria-modal="true"
     x-on:keydown.escape.window="cancel()">

    <!-- Backdrop with Blur -->
    <div x-show="isOpen"
         x-transition:enter="ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 bg-slate-950/60 backdrop-blur-xs transition-opacity"
         @click="cancel()"></div>

    <!-- Modal Card -->
    <div x-show="isOpen"
         x-transition:enter="ease-out duration-300"
         x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
         x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
         x-transition:leave="ease-in duration-200"
         x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
         x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
         class="relative w-full max-w-lg bg-white rounded-3xl shadow-2xl border border-slate-200/90 overflow-hidden transform transition-all z-10"
         @click.stop>

        <!-- Top Close Button -->
        <button type="button" @click="cancel()"
                class="absolute top-4 right-4 text-slate-400 hover:text-slate-600 p-1.5 rounded-xl hover:bg-slate-100 transition cursor-pointer">
            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>

        <!-- Content Area -->
        <div class="p-6 sm:p-7 space-y-4">
            <div class="flex items-start gap-4">
                <!-- Dynamic Themed Icon Badge -->
                <div :class="{
                    'bg-emerald-50 text-emerald-600 border-emerald-200': type === 'success',
                    'bg-rose-50 text-rose-600 border-rose-200': type === 'danger',
                    'bg-amber-50 text-amber-600 border-amber-200': type === 'warning',
                    'bg-indigo-50 text-indigo-600 border-indigo-200': type === 'primary' || !type
                }" class="w-12 h-12 rounded-2xl border flex items-center justify-center shrink-0 shadow-xs">
                    <!-- Success / Submit icon -->
                    <template x-if="type === 'success'">
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </template>
                    <!-- Danger icon -->
                    <template x-if="type === 'danger'">
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                        </svg>
                    </template>
                    <!-- Warning icon -->
                    <template x-if="type === 'warning'">
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </template>
                    <!-- Primary / Info icon -->
                    <template x-if="type === 'primary' || !type">
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </template>
                </div>

                <div class="space-y-1.5 flex-1 pr-4">
                    <h3 class="text-base sm:text-lg font-bold text-slate-900 tracking-tight" x-text="title"></h3>
                    <p class="text-xs sm:text-sm font-semibold text-slate-800 leading-snug" x-text="message"></p>
                    <p x-show="subtext" class="text-xs text-slate-500 leading-relaxed mt-1" x-text="subtext"></p>
                </div>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="bg-slate-50/90 px-6 py-3.5 sm:py-4 border-t border-slate-100 flex items-center justify-end gap-2.5">
            <button type="button" @click="cancel()"
                    class="px-4 py-2 text-xs font-semibold text-slate-700 bg-white border border-slate-300 rounded-xl hover:bg-slate-50 transition shadow-xs cursor-pointer"
                    x-text="cancelText"></button>
            <button type="button" @click="confirmAction()"
                    :class="{
                        'bg-emerald-600 hover:bg-emerald-500 text-white shadow-emerald-600/20': type === 'success',
                        'bg-rose-600 hover:bg-rose-500 text-white shadow-rose-600/20': type === 'danger',
                        'bg-amber-600 hover:bg-amber-500 text-white shadow-amber-600/20': type === 'warning',
                        'bg-indigo-600 hover:bg-indigo-500 text-white shadow-indigo-600/20': type === 'primary' || !type
                    }"
                    class="px-5 py-2 text-xs font-bold rounded-xl shadow-sm transition flex items-center gap-2 cursor-pointer">
                <span x-text="confirmText"></span>
            </button>
        </div>
    </div>
</div>

<script>
    function globalConfirmModal() {
        return {
            isOpen: false,
            title: 'Confirm Action',
            message: '',
            subtext: '',
            confirmText: 'Confirm',
            cancelText: 'Cancel',
            type: 'primary',
            resolveCallback: null,

            openConfirm(detail) {
                this.title = detail.title || 'Confirm Action';
                this.message = detail.message || '';
                this.subtext = detail.subtext || '';
                this.confirmText = detail.confirmText || 'Confirm';
                this.cancelText = detail.cancelText || 'Cancel';
                this.type = detail.type || 'primary';
                this.resolveCallback = detail.resolveCallback || null;
                this.isOpen = true;
            },

            confirmAction() {
                this.isOpen = false;
                if (typeof this.resolveCallback === 'function') {
                    this.resolveCallback(true);
                }
            },

            cancel() {
                this.isOpen = false;
                if (typeof this.resolveCallback === 'function') {
                    this.resolveCallback(false);
                }
            }
        };
    }

    // Global helper callable from anywhere: await window.customConfirm('Are you sure...?')
    window.customConfirm = function(message, options = {}) {
        return new Promise((resolve) => {
            window.dispatchEvent(new CustomEvent('show-confirm', {
                detail: {
                    message: message,
                    title: options.title || 'Confirm Action',
                    subtext: options.subtext || '',
                    confirmText: options.confirmText || 'Yes, Proceed',
                    cancelText: options.cancelText || 'Cancel',
                    type: options.type || 'primary',
                    resolveCallback: resolve
                }
            }));
        });
    };
</script>
