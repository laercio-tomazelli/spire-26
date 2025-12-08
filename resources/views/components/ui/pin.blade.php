@props([
    'color' => 'primary', // primary, success, warning, danger, info, gray
    'size' => 'md', // xs, sm, md, lg, xl
    'pulse' => false, // animação de pulse
    'outline' => false, // apenas contorno
    'position' => null, // top-right, top-left, bottom-right, bottom-left (para posicionar sobre outro elemento)
    'count' => null, // número para exibir (transforma em badge de contagem)
    'dot' => false, // apenas um ponto pequeno
    'ping' => false, // animação de ping (notificação)
])

@php
    $colors = [
        'primary' => $outline ? 'border-2 border-blue-500 text-blue-500' : 'bg-blue-500 text-white',
        'success' => $outline ? 'border-2 border-green-500 text-green-500' : 'bg-green-500 text-white',
        'warning' => $outline ? 'border-2 border-yellow-500 text-yellow-500' : 'bg-yellow-500 text-white',
        'danger' => $outline ? 'border-2 border-red-500 text-red-500' : 'bg-red-500 text-white',
        'info' => $outline ? 'border-2 border-cyan-500 text-cyan-500' : 'bg-cyan-500 text-white',
        'gray' => $outline ? 'border-2 border-gray-400 text-gray-400' : 'bg-gray-400 text-white',
    ];

    $sizes = [
        'xs' => $dot ? 'w-1.5 h-1.5' : 'w-4 h-4 text-[10px]',
        'sm' => $dot ? 'w-2 h-2' : 'w-5 h-5 text-xs',
        'md' => $dot ? 'w-2.5 h-2.5' : 'w-6 h-6 text-xs',
        'lg' => $dot ? 'w-3 h-3' : 'w-7 h-7 text-sm',
        'xl' => $dot ? 'w-4 h-4' : 'w-8 h-8 text-base',
    ];

    $positions = [
        'top-right' => 'absolute -top-1 -right-1',
        'top-left' => 'absolute -top-1 -left-1',
        'bottom-right' => 'absolute -bottom-1 -right-1',
        'bottom-left' => 'absolute -bottom-1 -left-1',
    ];

    $pingColors = [
        'primary' => 'bg-blue-400',
        'success' => 'bg-green-400',
        'warning' => 'bg-yellow-400',
        'danger' => 'bg-red-400',
        'info' => 'bg-cyan-400',
        'gray' => 'bg-gray-300',
    ];

    $colorClass = $colors[$color] ?? $colors['primary'];
    $sizeClass = $sizes[$size] ?? $sizes['md'];
    $positionClass = $position ? ($positions[$position] ?? '') : '';
    $pulseClass = $pulse ? 'animate-pulse' : '';
    $pingColorClass = $pingColors[$color] ?? $pingColors['primary'];
    
    // Se tem count, ajusta o tamanho para caber o número
    if ($count !== null && $count > 99) {
        $sizeClass = 'min-w-6 h-6 px-1.5 text-xs';
    } elseif ($count !== null && $count > 9) {
        $sizeClass = 'min-w-5 h-5 px-1 text-xs';
    }
@endphp

@if($position)
    {{-- Versão posicionada (wrapper relativo necessário no pai) --}}
    <span class="{{ $positionClass }} z-10">
        @if($ping)
            <span class="absolute inline-flex h-full w-full rounded-full {{ $pingColorClass }} opacity-75 animate-ping"></span>
        @endif
        <span {{ $attributes->merge(['class' => "relative inline-flex items-center justify-center rounded-full font-medium {$colorClass} {$sizeClass} {$pulseClass}"]) }}>
            @if(!$dot)
                {{ $count ?? $slot }}
            @endif
        </span>
    </span>
@else
    {{-- Versão inline --}}
    <span class="relative inline-flex">
        @if($ping)
            <span class="absolute inline-flex h-full w-full rounded-full {{ $pingColorClass }} opacity-75 animate-ping"></span>
        @endif
        <span {{ $attributes->merge(['class' => "relative inline-flex items-center justify-center rounded-full font-medium {$colorClass} {$sizeClass} {$pulseClass}"]) }}>
            @if(!$dot)
                {{ $count ?? $slot }}
            @endif
        </span>
    </span>
@endif
