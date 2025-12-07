@props([
    'id' => null,
    'active' => false,
    'disabled' => false,
    'text' => false,
    'color' => 'text-gray-700 dark:text-gray-200', // Cor padrão do texto (customizável)
])

@php
    $swapId = $id ?? 'swap-' . uniqid();
@endphp

@if($text)
{{-- Versão para texto/botões - simples com display block/none --}}
<label 
    data-swap="{{ $swapId }}"
    data-swap-active="{{ $active ? 'true' : 'false' }}"
    data-swap-text="true"
    {{ $attributes->merge([
        'class' => 'cursor-pointer select-none ' . ($disabled ? 'opacity-50 cursor-not-allowed pointer-events-none' : '')
    ]) }}
>
    <input 
        type="checkbox" 
        class="sr-only peer"
        @checked($active)
        @disabled($disabled)
        data-swap-input
        onchange="
            const l = this.closest('[data-swap]');
            const on = l.querySelector('[data-swap-on]');
            const off = l.querySelector('[data-swap-off]');
            l.dataset.swapActive = this.checked ? 'true' : 'false';
            on.style.display = this.checked ? 'inline-flex' : 'none';
            off.style.display = this.checked ? 'none' : 'inline-flex';
        "
    >
    
    {{-- ON state --}}
    <span data-swap-on class="items-center {{ $color }}" style="display: {{ $active ? 'inline-flex' : 'none' }};">{{ $on ?? '' }}</span>
    
    {{-- OFF state --}}
    <span data-swap-off class="items-center {{ $color }}" style="display: {{ $active ? 'none' : 'inline-flex' }};">{{ $off ?? '' }}</span>
</label>

@else
{{-- Versão para ícones (absolute positioning) --}}
<label 
    data-swap="{{ $swapId }}"
    data-swap-active="{{ $active ? 'true' : 'false' }}"
    {{ $attributes->merge([
        'class' => 'relative inline-flex cursor-pointer select-none ' . ($disabled ? 'opacity-50 cursor-not-allowed pointer-events-none' : '')
    ]) }}
>
    <input 
        type="checkbox" 
        class="sr-only peer"
        @checked($active)
        @disabled($disabled)
        data-swap-input
        onchange="
            const l = this.closest('[data-swap]');
            const on = l.querySelector('[data-swap-on]');
            const off = l.querySelector('[data-swap-off]');
            l.dataset.swapActive = this.checked ? 'true' : 'false';
            if (this.checked) {
                on.style.cssText = 'opacity: 1; transform: scale(1) rotate(0deg);';
                off.style.cssText = 'opacity: 0; transform: scale(0.75) rotate(-45deg);';
            } else {
                on.style.cssText = 'opacity: 0; transform: scale(0.75) rotate(45deg);';
                off.style.cssText = 'opacity: 1; transform: scale(1) rotate(0deg);';
            }
        "
    >
    
    {{-- Container for swappable content --}}
    <span class="relative inline-flex items-center justify-center">
        {{-- ON state (shown when active) --}}
        <span 
            data-swap-on
            class="absolute inset-0 flex items-center justify-center transition-all ease-in-out duration-300"
            style="{{ $active ? 'opacity: 1; transform: scale(1) rotate(0deg);' : 'opacity: 0; transform: scale(0.75) rotate(45deg);' }}"
        >
            {{ $on ?? '' }}
        </span>
        
        {{-- OFF state (shown when inactive) --}}
        <span 
            data-swap-off
            class="flex items-center justify-center transition-all ease-in-out duration-300"
            style="{{ $active ? 'opacity: 0; transform: scale(0.75) rotate(-45deg);' : 'opacity: 1; transform: scale(1) rotate(0deg);' }}"
        >
            {{ $off ?? '' }}
        </span>
    </span>
</label>
@endif
