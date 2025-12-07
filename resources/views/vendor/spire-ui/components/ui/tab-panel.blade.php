@props([
    'name',
    'active' => false
])

<div 
    data-panel="{{ $name }}"
    role="tabpanel"
    aria-hidden="{{ $active ? 'false' : 'true' }}"
    {{ $attributes->merge(['class' => ($active ? '' : 'hidden')]) }}
>
    {{ $slot }}
</div>
