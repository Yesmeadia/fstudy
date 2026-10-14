@props(['theme' => 'light', 'size' => 'normal'])

@if (config('services.recaptcha.site_key'))
    <div class="flex flex-col items-center justify-center my-3">
        <div class="g-recaptcha"
             data-sitekey="{{ config('services.recaptcha.site_key') }}"
             data-theme="{{ $theme }}"
             data-size="{{ $size }}">
        </div>
        <x-input-error :messages="$errors->get('g-recaptcha-response')" class="mt-1.5 text-center text-xs" />
    </div>
@endif
