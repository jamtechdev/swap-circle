@props([
    'href' => url('/'),
    'label' => config('app.name', 'Swap Circle'),
    'logo' => null,
    'icon' => null,
    'variant' => 'sidebar',
    'tone' => 'dark',
])

@php
    $src = $logo ?? ($icon ?? \App\Support\BrandMark::iconUrl());
@endphp

<a
    href="{{ $href }}"
    {{ $attributes->merge(['class' => 'brand-mark brand-mark--' . $variant . ' brand-mark--' . $tone]) }}
    aria-label="{{ $label }}"
>
    <img
        src="{{ $src }}"
        alt=""
        class="brand-mark__img"
        loading="eager"
        decoding="async"
    >
</a>
