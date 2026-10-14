<!-- Photo Preview Lightbox Modal -->
<div x-show="lightboxOpen"
     x-cloak
     @keydown.escape.window="lightboxOpen = false"
     class="fixed inset-0 z-50 overflow-y-auto"
     aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="flex items-center justify-center min-h-screen p-4 text-center sm:p-0">
        <!-- Backdrop -->
        <div x-show="lightboxOpen"
             x-transition:enter="ease-out duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="ease-in duration-200"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             @click="lightboxOpen = false"
             class="fixed inset-0 bg-slate-900/80 backdrop-blur-sm transition-opacity"></div>

        <!-- Modal Panel -->
        <div x-show="lightboxOpen"
             x-transition:enter="ease-out duration-300"
             x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
             x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
             x-transition:leave="ease-in duration-200"
             x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
             x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
             class="relative bg-white rounded-2xl overflow-hidden shadow-2xl transform transition-all sm:max-w-3xl sm:w-full border border-slate-200 text-left p-4 sm:p-6 space-y-3">

            <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                <div class="flex items-center gap-2">
                    <span class="p-1 rounded-lg bg-indigo-50 text-indigo-700">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                    </span>
                    <h3 class="text-xs sm:text-sm font-bold text-slate-900 truncate" x-text="lightboxCaption || 'Evidence Photo Preview'"></h3>
                </div>
                <div class="flex items-center gap-2">
                    <a :href="lightboxImage" target="_blank"
                       class="text-[11px] font-semibold text-indigo-600 hover:text-indigo-800 underline">
                        Open Full View &nearr;
                    </a>
                    <button type="button" @click="lightboxOpen = false"
                            class="p-1.5 text-slate-400 hover:text-slate-600 rounded-lg hover:bg-slate-100 transition cursor-pointer">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            </div>

            <!-- Image display -->
            <div class="bg-slate-950/5 rounded-xl overflow-hidden flex items-center justify-center min-h-[260px] max-h-[70vh]">
                <img :src="lightboxImage" alt="Evidence Preview" class="max-h-[68vh] w-auto max-w-full object-contain rounded-lg shadow-inner">
            </div>
        </div>
    </div>
</div>
