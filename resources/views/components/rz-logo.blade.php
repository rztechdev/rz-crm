@props([
    'variant' => 'light',
    'size' => 'md',
    'showText' => true
])

@php
    $heightClass = match($size) {
        'xs' => 'h-7 sm:h-8',
        'sm' => 'h-8 sm:h-9',
        'md' => 'h-9 sm:h-11',
        'lg' => 'h-10 sm:h-12',
        default => 'h-9 sm:h-11'
    };
@endphp

<div {{ $attributes->merge(['class' => 'flex items-center gap-2']) }}>
    <img 
        src="{{ asset('images/logo_rz_teks.png') }}"
        alt="RZ Digital Creative Logo"
        class="{{ $heightClass }} w-auto object-contain brightness-0 dark:brightness-100 hover:opacity-95 transition-opacity"
    >
</div>
