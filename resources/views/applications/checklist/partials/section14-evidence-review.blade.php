<div x-show="currentTab === '13'" x-cloak class="space-y-5" x-data="evidenceReviewSection()">
    <!-- Section Header & Overview -->
    <div class="bg-white rounded-2xl border border-slate-200/90 shadow-sm p-4 sm:p-6">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 border-b border-slate-100 pb-4">
            <div>
                <div class="flex items-center gap-2">
                    <span class="w-6 h-6 rounded-full bg-indigo-600 text-white font-black text-xs flex items-center justify-center">13</span>
                    <h2 class="text-sm sm:text-base font-bold text-slate-900 uppercase tracking-wide">
                        13. Evidence Photos & Remarks Review
                    </h2>
                </div>
                <p class="text-xs text-slate-500 mt-1">
                    Review all qualitative remarks, field observations, and optionally attach supporting evidence photos for each point.
                </p>
            </div>
            <div class="flex items-center gap-2 self-start sm:self-auto">
                <span class="text-xs font-semibold px-3 py-1 rounded-full border bg-indigo-50 border-indigo-200 text-indigo-700">
                    <span x-text="stats.photosAttached">{{ $evidenceSummary['photos_attached'] }}</span> Photos Attached
                </span>
            </div>
        </div>

        <!-- Evidence Summary Metric Counters -->
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 sm:gap-4 mt-4">
            <!-- Total Remarks -->
            <div class="bg-slate-50 border border-slate-200/80 rounded-xl p-3.5 flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-indigo-100 text-indigo-700 flex items-center justify-center shrink-0">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z" />
                    </svg>
                </div>
                <div>
                    <span class="block text-[11px] font-medium text-slate-500 uppercase tracking-wider">Total Remarks</span>
                    <span class="text-lg font-bold text-slate-900" x-text="stats.totalRemarks">{{ $evidenceSummary['total_remarks'] }}</span>
                </div>
            </div>

            <!-- Photos Attached -->
            <div class="bg-slate-50 border border-slate-200/80 rounded-xl p-3.5 flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-emerald-100 text-emerald-700 flex items-center justify-center shrink-0">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                </div>
                <div>
                    <span class="block text-[11px] font-medium text-slate-500 uppercase tracking-wider">Photos Attached</span>
                    <span class="text-lg font-bold text-emerald-700" x-text="stats.photosAttached">{{ $evidenceSummary['photos_attached'] }}</span>
                </div>
            </div>

            <!-- Without Photos -->
            <div class="bg-slate-50 border border-slate-200/80 rounded-xl p-3.5 flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-slate-200 text-slate-600 flex items-center justify-center shrink-0">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" />
                    </svg>
                </div>
                <div>
                    <span class="block text-[11px] font-medium text-slate-500 uppercase tracking-wider">Without Photo</span>
                    <span class="text-lg font-bold text-slate-700" x-text="stats.missingPhotos">{{ $evidenceSummary['missing_photos'] }}</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Filter Pills & List of Remarked Items -->
    <div class="bg-white rounded-2xl border border-slate-200/90 shadow-sm p-4 sm:p-6 space-y-4">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 border-b border-slate-100 pb-3">
            <div class="flex items-center gap-2">
                <button type="button" @click="filter = 'all'"
                        :class="filter === 'all' ? 'bg-indigo-600 text-white shadow-xs' : 'bg-slate-100 text-slate-700 hover:bg-slate-200'"
                        class="px-3 py-1.5 rounded-lg text-xs font-semibold transition cursor-pointer">
                    All Remarked Items (<span x-text="items.length">{{ count($evidenceSummary['items']) }}</span>)
                </button>
                <button type="button" @click="filter = 'attached'"
                        :class="filter === 'attached' ? 'bg-emerald-600 text-white shadow-xs' : 'bg-emerald-50 text-emerald-800 hover:bg-emerald-100 border border-emerald-200'"
                        class="px-3 py-1.5 rounded-lg text-xs font-semibold transition cursor-pointer">
                    Photo Attached (<span x-text="items.filter(i => i.has_photo).length">{{ $evidenceSummary['photos_attached'] }}</span>)
                </button>
                <button type="button" @click="filter = 'missing'"
                        :class="filter === 'missing' ? 'bg-indigo-600 text-white shadow-xs font-semibold' : 'bg-slate-100 text-slate-700 hover:bg-slate-200 border border-slate-200'"
                        class="px-3 py-1.5 rounded-lg text-xs font-semibold transition cursor-pointer">
                    Without Photo (<span x-text="items.filter(i => i.missing_photo).length">{{ $evidenceSummary['missing_photos'] }}</span>)
                </button>
            </div>
            <span class="text-[11px] text-slate-400 italic">Click on any photo thumbnail to zoom</span>
        </div>

        <!-- Empty State -->
        <template x-if="filteredItems().length === 0">
            <div class="text-center py-10 px-4 bg-slate-50/60 rounded-xl border border-dashed border-slate-200">
                <div class="w-12 h-12 mx-auto rounded-full bg-slate-100 text-slate-400 flex items-center justify-center mb-3">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                </div>
                <h4 class="text-xs font-bold text-slate-700">No Remarks Matching Current Filter</h4>
                <p class="text-[11px] text-slate-500 max-w-md mx-auto mt-1">
                    When remarks are typed in Sections 1 through 13, each item will automatically appear in this review gallery where you can attach supporting photos.
                </p>
            </div>
        </template>

        <!-- Remark Items Cards -->
        <div class="space-y-3">
            <template x-for="(item, idx) in filteredItems()" :key="item.field_name + '_' + idx">
                <div class="border rounded-xl p-3.5 transition hover:shadow-xs bg-white border-slate-200">
                    <div class="flex flex-col sm:flex-row sm:items-start justify-between gap-3">
                        <!-- Left: Section Title, Point, Remark -->
                        <div class="space-y-1.5 flex-1">
                            <div class="flex items-center flex-wrap gap-2">
                                <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-indigo-100 text-indigo-800"
                                      x-text="item.section_title"></span>
                                <button type="button" @click="currentTab = String(item.section_number)"
                                        class="text-[10px] text-indigo-600 hover:text-indigo-800 font-semibold underline cursor-pointer">
                                    View in Section <span x-text="item.section_number"></span> &rarr;
                                </button>
                                <template x-if="item.has_photo">
                                    <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-emerald-100 text-emerald-800 flex items-center gap-1">
                                        <svg class="w-3 h-3 text-emerald-600" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                                        </svg>
                                        Photo Attached
                                    </span>
                                </template>
                                <template x-if="!item.has_photo">
                                    <span class="px-2 py-0.5 rounded text-[10px] font-medium bg-slate-100 text-slate-600">
                                        No Photo Attached (Optional)
                                    </span>
                                </template>
                            </div>

                            <h4 class="text-xs font-bold text-slate-800 leading-snug" x-text="item.item_label"></h4>

                            <div class="bg-slate-50 border border-slate-200 rounded-lg p-2.5 text-xs text-slate-700">
                                <span class="font-semibold text-slate-500 text-[10px] uppercase block mb-0.5">Recorded Remark:</span>
                                <p class="italic text-slate-800 whitespace-pre-line" x-text="item.remarks || '(No text remark entered)'"></p>
                            </div>
                        </div>

                        <!-- Right: Photo Thumbnail & Direct Inline Action -->
                        <div class="sm:w-64 shrink-0 flex flex-col items-start sm:items-end justify-center gap-2 pt-1">
                            <!-- Has photo -->
                            <template x-if="item.has_photo">
                                <div class="flex items-center gap-2">
                                    <div class="relative group cursor-pointer" @click="openLightbox(item.photo_url, item.item_label)">
                                        <img :src="item.photo_url" class="w-16 h-16 object-cover rounded-xl border-2 border-emerald-400 shadow-sm group-hover:scale-105 transition" alt="Photo Evidence">
                                        <div class="absolute inset-0 bg-black/30 rounded-xl opacity-0 group-hover:opacity-100 flex items-center justify-center text-white text-[10px] font-bold transition">
                                            Zoom &plus;
                                        </div>
                                    </div>
                                    <div class="space-y-1 text-right">
                                        <button type="button" @click="openPhotoSourcePicker(item)"
                                                class="block text-[11px] font-semibold text-indigo-600 hover:text-indigo-800 underline cursor-pointer">
                                            Replace Photo
                                        </button>
                                        <button type="button" @click="removeReviewPhoto(item)"
                                                class="block text-[11px] font-semibold text-rose-600 hover:text-rose-800 underline cursor-pointer">
                                            Remove
                                        </button>
                                    </div>
                                </div>
                            </template>

                            <!-- No photo: Quick inline upload button -->
                            <template x-if="!item.has_photo">
                                <div class="w-full text-center">
                                    <button type="button" @click="openPhotoSourcePicker(item)"
                                            class="w-full px-3 py-2 rounded-xl text-xs font-semibold text-indigo-700 bg-indigo-50 hover:bg-indigo-100 border border-indigo-200 transition shadow-xs flex items-center justify-center gap-1.5 cursor-pointer">
                                        <svg class="w-4 h-4 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z" />
                                        </svg>
                                        <span>Attach Evidence Photo</span>
                                    </button>
                                </div>
                            </template>
                        </div>
                    </div>
                </div>
            </template>
        </div>

        <!-- Hidden Inputs for Direct Camera and Photo Gallery Uploads -->
        <input type="file" x-ref="cameraFileInput" @change="handleReviewFileSelected($event)" accept="image/jpeg,image/png,image/jpg,image/webp,image/heic" capture="environment" class="hidden">
        <input type="file" x-ref="galleryFileInput" @change="handleReviewFileSelected($event)" accept="image/jpeg,image/png,image/jpg,image/webp,image/heic" class="hidden">

        <!-- Photo Source Picker Modal (Camera vs Gallery) -->
        <div x-show="pickerModalOpen" x-cloak
             class="fixed inset-0 z-[100] flex items-center justify-center p-4 sm:p-6 overflow-y-auto"
             role="dialog" aria-modal="true" @keydown.escape.window="pickerModalOpen = false">

            <!-- Backdrop -->
            <div x-show="pickerModalOpen"
                 x-transition:enter="ease-out duration-300"
                 x-transition:enter-start="opacity-0"
                 x-transition:enter-end="opacity-100"
                 x-transition:leave="ease-in duration-200"
                 x-transition:leave-start="opacity-100"
                 x-transition:leave-end="opacity-0"
                 class="fixed inset-0 bg-slate-950/60 backdrop-blur-xs transition-opacity"
                 @click="pickerModalOpen = false"></div>

            <!-- Modal Panel -->
            <div x-show="pickerModalOpen"
                 x-transition:enter="ease-out duration-300"
                 x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                 x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave="ease-in duration-200"
                 x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                 class="relative w-full max-w-md bg-white rounded-3xl shadow-2xl border border-slate-200/90 overflow-hidden transform transition-all z-10"
                 @click.stop>

                <!-- Modal Header -->
                <div class="p-5 sm:p-6 border-b border-slate-100 bg-slate-50/50 flex items-start justify-between gap-3">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-2xl bg-indigo-50 border border-indigo-100 text-indigo-600 flex items-center justify-center shrink-0 shadow-xs">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-sm sm:text-base font-bold text-slate-900">Attach Evidence Photo</h3>
                            <p class="text-xs text-slate-500 mt-0.5 line-clamp-1" x-text="activeUploadItem ? activeUploadItem.item_label : 'Select photo source'"></p>
                        </div>
                    </div>
                    <button type="button" @click="pickerModalOpen = false"
                            class="p-1.5 rounded-xl text-slate-400 hover:text-slate-600 hover:bg-slate-100 transition cursor-pointer">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <!-- Uploading Indicator -->
                <div x-show="isUploading" class="p-8 text-center space-y-3">
                    <div class="inline-flex items-center justify-center w-12 h-12 rounded-full bg-indigo-50 text-indigo-600">
                        <svg class="w-6 h-6 animate-spin" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                    </div>
                    <p class="text-xs font-bold text-slate-800">Uploading photo evidence...</p>
                    <p class="text-[11px] text-slate-500">Optimizing and storing field evidence on secure server.</p>
                </div>

                <!-- Modal Options (When not uploading) -->
                <div x-show="!isUploading" class="p-5 sm:p-6 space-y-3">
                    <!-- Option 1: Take Photo using Camera -->
                    <button type="button" @click="takePhotoWithCamera()"
                            class="w-full text-left p-4 rounded-2xl border-2 border-indigo-100 hover:border-indigo-500 bg-gradient-to-r from-indigo-50/50 to-white hover:from-indigo-50 hover:to-indigo-50/30 transition shadow-xs group cursor-pointer flex items-center justify-between gap-3">
                        <div class="flex items-center gap-3.5">
                            <div class="w-12 h-12 rounded-2xl bg-indigo-600 text-white flex items-center justify-center shrink-0 shadow-md shadow-indigo-600/20 group-hover:scale-105 transition">
                                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                            </div>
                            <div>
                                <div class="flex items-center gap-2">
                                    <h4 class="text-xs sm:text-sm font-bold text-slate-900 group-hover:text-indigo-600 transition">Take Photo using Camera</h4>
                                    <span class="px-1.5 py-0.5 rounded text-[10px] font-bold bg-emerald-100 text-emerald-800">Live Camera</span>
                                </div>
                                <p class="text-[11px] text-slate-500 mt-0.5">Open device camera to capture field evidence</p>
                            </div>
                        </div>
                        <svg class="w-5 h-5 text-indigo-400 group-hover:text-indigo-600 group-hover:translate-x-0.5 transition shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                        </svg>
                    </button>

                    <!-- Option 2: Select from Gallery / Photos -->
                    <button type="button" @click="selectFromGallery()"
                            class="w-full text-left p-4 rounded-2xl border-2 border-slate-200 hover:border-indigo-500 bg-white hover:bg-indigo-50/20 transition shadow-xs group cursor-pointer flex items-center justify-between gap-3">
                        <div class="flex items-center gap-3.5">
                            <div class="w-12 h-12 rounded-2xl bg-slate-100 text-slate-700 group-hover:bg-indigo-100 group-hover:text-indigo-700 flex items-center justify-center shrink-0 transition">
                                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                            </div>
                            <div>
                                <div class="flex items-center gap-2">
                                    <h4 class="text-xs sm:text-sm font-bold text-slate-900 group-hover:text-indigo-600 transition">Select from Gallery / Photos</h4>
                                    <span class="px-1.5 py-0.5 rounded text-[10px] font-medium bg-slate-100 text-slate-600">Saved Files</span>
                                </div>
                                <p class="text-[11px] text-slate-500 mt-0.5">Browse photo album or upload from device</p>
                            </div>
                        </div>
                        <svg class="w-5 h-5 text-slate-400 group-hover:text-indigo-600 group-hover:translate-x-0.5 transition shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                        </svg>
                    </button>
                </div>

                <!-- Modal Footer -->
                <div class="p-3.5 bg-slate-50 border-t border-slate-100 flex items-center justify-end">
                    <button type="button" @click="pickerModalOpen = false"
                            class="px-4 py-2 text-xs font-semibold text-slate-600 hover:text-slate-800 rounded-xl hover:bg-slate-200/60 transition cursor-pointer">
                        Cancel
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Bottom Action Navigation -->
    <div class="px-4 sm:px-6 py-3.5 bg-slate-50/90 rounded-2xl border border-slate-200/90 flex items-center justify-between gap-3">
        <button type="button" @click="saveAndGoPrev()"
                class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl text-slate-700 bg-white border border-slate-300 hover:bg-slate-50 font-semibold text-xs transition shadow-xs cursor-pointer">
            <span>&larr; Save & Prev: 12. Teachers</span>
        </button>
        <div class="flex items-center gap-2">
            <button type="submit" @click="document.getElementById('formActionInput').value = 'save_draft'"
                    class="hidden sm:inline-flex items-center gap-1.5 px-3 py-2 text-xs font-semibold text-slate-700 bg-white border border-slate-300 rounded-xl hover:bg-slate-50 transition shadow-xs">
                Save Draft
            </button>
            <button type="button" @click="saveAndGoNext()"
                    class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl bg-indigo-600 text-white hover:bg-indigo-500 font-bold text-xs transition shadow-xs cursor-pointer">
                <span>Save & Next: 14. Final Assessment Remarks</span>
                <span>&rarr;</span>
            </button>
        </div>
    </div>
</div>
