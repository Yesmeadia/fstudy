@props([
    'name',
    'photoName',
    'remark' => '',
    'photo' => '',
    'placeholder' => 'Remarks',
    'label' => 'Evidence Photo',
    'isTextarea' => false,
    'rows' => 3,
])

@php
    $photoUrl = !empty($photo) ? asset('storage/' . $photo) : '';
@endphp

<div x-data="evidenceItem({
    initialPhoto: @js($photo ?? ''),
    initialUrl: @js($photoUrl),
    initialRemark: @js($remark ?? ''),
    label: @js($label),
    uploadUrl: '{{ route('applications.checklist.upload_photo', $application) }}'
})" class="space-y-1.5 w-full">
    <div class="relative">
        @if($isTextarea)
            <textarea name="{{ $name }}"
                      x-model="remark"
                      rows="{{ $rows }}"
                      placeholder="{{ $placeholder }}"
                      class="w-full text-xs rounded-xl border-slate-300 focus:border-indigo-500 focus:ring-indigo-500 py-2 px-3 pr-8 transition"></textarea>
        @else
            <input type="text"
                   name="{{ $name }}"
                   x-model="remark"
                   placeholder="{{ $placeholder }}"
                   class="w-full text-xs rounded-lg border-slate-300 focus:border-indigo-500 focus:ring-indigo-500 py-1.5 px-2.5 pr-8 transition">
        @endif

        <template x-if="hasRemarkWithoutPhoto()">
            <span class="absolute right-2.5 top-2.5 flex h-2.5 w-2.5" title="Evidence photo required for remarks">
                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-amber-400 opacity-75"></span>
                <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-amber-500"></span>
            </span>
        </template>
    </div>

    <!-- Hidden input saving the uploaded photo path into the form -->
    <input type="hidden" name="{{ $photoName }}" :value="photo">

    <!-- Evidence action toolbar -->
    <div class="flex items-center justify-between gap-2 text-[11px] pt-0.5">
        <!-- Photo is attached -->
        <template x-if="photo">
            <div class="inline-flex items-center gap-1.5 bg-emerald-50 border border-emerald-200 text-emerald-800 px-2 py-0.5 rounded-md shadow-2xs">
                <button type="button" @click="openLightbox(photoUrl, label)" class="relative group cursor-pointer">
                    <img :src="photoUrl" class="w-6 h-6 object-cover rounded border border-emerald-300 group-hover:scale-105 transition" alt="Evidence thumbnail">
                </button>
                <span class="font-semibold text-[10px] text-emerald-700 flex items-center gap-1">
                    <svg class="w-3 h-3 text-emerald-600 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                    </svg>
                    Photo attached
                </span>
                <button type="button" @click="openFileDialog()" class="text-indigo-600 hover:text-indigo-800 text-[10px] font-medium underline ml-0.5 cursor-pointer">Change</button>
                <button type="button" @click="removePhoto()" class="text-rose-500 hover:text-rose-700 text-xs font-bold ml-0.5 cursor-pointer" title="Remove Photo">&times;</button>
            </div>
        </template>

        <!-- No photo attached yet -->
        <template x-if="!photo">
            <div class="flex items-center flex-wrap gap-1.5">
                <button type="button" @click="openFileDialog()"
                        :class="hasRemarkWithoutPhoto() ? 'bg-amber-500 hover:bg-amber-600 text-white shadow-xs font-bold border-amber-600' : 'bg-slate-50 hover:bg-slate-100 text-slate-700 border-slate-300 font-medium'"
                        class="inline-flex items-center gap-1 px-2 py-0.5 rounded border text-[10px] transition cursor-pointer">
                    <svg class="w-3.5 h-3.5" :class="hasRemarkWithoutPhoto() ? 'text-white' : 'text-indigo-600'" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                    <span x-text="hasRemarkWithoutPhoto() ? 'Attach Required Photo' : 'Attach Photo'"></span>
                </button>

                <template x-if="hasRemarkWithoutPhoto()">
                    <span class="text-[10px] font-bold text-amber-700 bg-amber-50 border border-amber-200 px-1.5 py-0.5 rounded flex items-center gap-1">
                        <svg class="w-3 h-3 text-amber-600 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                        </svg>
                        Photo Required
                    </span>
                </template>
            </div>
        </template>

        <!-- Hidden input for file picker -->
        <input type="file" x-ref="filePicker" @change="uploadFile($event)" accept="image/jpeg,image/png,image/jpg,image/webp,image/heic" class="hidden">

        <!-- Uploading progress spinner -->
        <template x-if="isUploading">
            <span class="inline-flex items-center gap-1 text-[10px] text-indigo-600 font-semibold animate-pulse">
                <svg class="animate-spin h-3 w-3 text-indigo-600" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path>
                </svg>
                Uploading photo...
            </span>
        </template>
    </div>
</div>
