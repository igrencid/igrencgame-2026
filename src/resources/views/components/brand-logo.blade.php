@props([
    'variant' => 'header',
    'showText' => true,
])

@php
    $logoUrl = \App\Support\Branding::logoUrl();
    $siteName = \App\Support\Branding::name();
    $tagline = \App\Support\Branding::tagline();

    $imageClass = match ($variant) {
        'intro' => 'h-24 w-24 sm:h-28 sm:w-28',
        'footer' => 'h-11 w-11',
        default => 'h-10 w-10',
    };

    $fallbackClass = match ($variant) {
        'intro' => 'h-24 w-24 rounded-3xl text-4xl sm:h-28 sm:w-28',
        'footer' => 'h-11 w-11 rounded-xl text-xl',
        default => 'h-10 w-10 rounded-xl text-lg',
    };
@endphp

<div {{ $attributes->class(['inline-flex min-w-0 items-center gap-3']) }}>
    @if ($logoUrl)
        <img
            src="{{ $logoUrl }}"
            alt="Logo {{ $siteName }}"
            class="{{ $imageClass }} shrink-0 object-contain"
            loading="eager"
            decoding="async"
        >
    @else
        <div
            class="{{ $fallbackClass }} flex shrink-0 items-center justify-center bg-gradient-to-br from-indigo-600 to-violet-600 font-black text-white shadow-sm"
            aria-hidden="true"
        >
            {{ \Illuminate\Support\Str::upper(
                \Illuminate\Support\Str::substr($siteName, 0, 1)
            ) }}
        </div>
    @endif

    @if ($showText)
        <div class="min-w-0">
            <p class="truncate text-base font-black leading-tight">
                <span class="bg-gradient-to-r from-indigo-600 via-violet-600 to-fuchsia-500 bg-clip-text text-transparent">
                    {{ $siteName }}
                </span>
            </p>

            <p class="mt-0.5 truncate text-xs font-medium text-slate-500">
                {{ $tagline }}
            </p>
        </div>
    @endif
</div>
