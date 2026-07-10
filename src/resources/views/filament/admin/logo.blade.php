@php
    $brandName = \App\Support\BrandAsset::brandName();
    $logoUrl = \App\Support\BrandAsset::logoUrl();
@endphp

<div class="flex items-center gap-3">
    @if ($logoUrl)
        <img
            src="{{ $logoUrl }}"
            alt="{{ $brandName }}"
            class="h-10 max-w-[160px] object-contain"
        >
    @else
        <div class="flex h-10 w-10 items-center justify-center rounded-2xl bg-primary-600 text-sm font-black text-white shadow-lg shadow-primary-600/20">
            {{ mb_substr($brandName, 0, 1) }}
        </div>

        <div class="leading-tight">
            <div class="text-lg font-black tracking-tight text-gray-950 dark:text-white">
                {{ $brandName }}
            </div>

            <div class="text-xs font-bold uppercase tracking-wide text-gray-500 dark:text-gray-400">
                Admin Panel
            </div>
        </div>
    @endif
</div>
