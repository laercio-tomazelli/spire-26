@props([
    'striped' => false,
    'hoverable' => true,
    'bordered' => false,
])

@php
    $classes = 'w-full text-sm text-left text-gray-700 dark:text-gray-300';
@endphp

<div
    class="fi-ta-ctn overflow-hidden rounded-xl bg-white shadow-sm ring-1 ring-gray-950/5 dark:bg-gray-900 dark:ring-white/10">
    {{ $header ?? '' }}

    <div class="fi-ta-content overflow-x-auto">
        <table {{ $attributes->merge(['class' => $classes]) }} data-striped="{{ $striped ? 'true' : 'false' }}"
            data-hoverable="{{ $hoverable ? 'true' : 'false' }}">
            {{ $slot }}
        </table>
    </div>

    {{ $footer ?? '' }}
</div>
