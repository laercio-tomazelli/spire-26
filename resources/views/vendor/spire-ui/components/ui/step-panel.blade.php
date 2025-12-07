@props([
    'step' => 1
])

<div 
    {{ $attributes->merge(['class' => 'hidden']) }}
    data-step-panel="{{ $step }}"
    role="tabpanel"
    aria-hidden="true"
>
    {{ $slot }}
</div>
