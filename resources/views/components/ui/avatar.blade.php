@props([
    'src' => null,
    'alt' => '',
    'name' => null, // Para gerar iniciais
    'size' => 'md', // xs, sm, md, lg, xl, 2xl
    'rounded' => 'full', // full, lg, md, sm, none
    'status' => null, // online, offline, away, busy
    'statusPosition' => 'bottom-right', // top-right, top-left, bottom-right, bottom-left
    'ring' => false, // anel colorido ao redor
    'ringColor' => 'primary', // primary, success, warning, danger, gray
    'placeholder' => true, // mostrar ícone placeholder se sem imagem/nome
    'group' => false, // se faz parte de um grupo (adiciona borda)
])

@php
    $sizes = [
        'xs' => 'w-6 h-6 text-xs',
        'sm' => 'w-8 h-8 text-sm',
        'md' => 'w-10 h-10 text-sm',
        'lg' => 'w-12 h-12 text-base',
        'xl' => 'w-16 h-16 text-lg',
        '2xl' => 'w-20 h-20 text-xl',
    ];

    $statusSizes = [
        'xs' => 'w-1.5 h-1.5',
        'sm' => 'w-2 h-2',
        'md' => 'w-2.5 h-2.5',
        'lg' => 'w-3 h-3',
        'xl' => 'w-4 h-4',
        '2xl' => 'w-5 h-5',
    ];

    $roundedStyles = [
        'full' => 'rounded-full',
        'lg' => 'rounded-lg',
        'md' => 'rounded-md',
        'sm' => 'rounded',
        'none' => '',
    ];

    $statusColors = [
        'online' => 'bg-green-500',
        'offline' => 'bg-gray-400',
        'away' => 'bg-yellow-500',
        'busy' => 'bg-red-500',
    ];

    $statusPositions = [
        'top-right' => 'top-0 right-0',
        'top-left' => 'top-0 left-0',
        'bottom-right' => 'bottom-0 right-0',
        'bottom-left' => 'bottom-0 left-0',
    ];

    $ringColors = [
        'primary' => 'ring-blue-500',
        'success' => 'ring-green-500',
        'warning' => 'ring-yellow-500',
        'danger' => 'ring-red-500',
        'gray' => 'ring-gray-300 dark:ring-gray-600',
    ];

    // Cores de fundo para iniciais (baseado no nome)
    $bgColors = [
        'bg-blue-500', 'bg-green-500', 'bg-yellow-500', 'bg-red-500',
        'bg-purple-500', 'bg-pink-500', 'bg-indigo-500', 'bg-cyan-500',
        'bg-orange-500', 'bg-teal-500'
    ];

    $sizeClass = $sizes[$size] ?? $sizes['md'];
    $roundedClass = $roundedStyles[$rounded] ?? $roundedStyles['full'];
    $statusSizeClass = $statusSizes[$size] ?? $statusSizes['md'];
    $statusColorClass = $statusColors[$status] ?? '';
    $statusPositionClass = $statusPositions[$statusPosition] ?? $statusPositions['bottom-right'];
    $ringClass = $ring ? 'ring-2 ring-offset-2 dark:ring-offset-gray-800 ' . ($ringColors[$ringColor] ?? $ringColors['primary']) : '';
    $groupClass = $group ? 'ring-2 ring-white dark:ring-gray-800' : '';

    // Gerar iniciais do nome
    $initials = '';
    if ($name) {
        $words = explode(' ', trim($name));
        if (count($words) >= 2) {
            $initials = strtoupper(substr($words[0], 0, 1) . substr(end($words), 0, 1));
        } else {
            $initials = strtoupper(substr($words[0], 0, 2));
        }
    }

    // Cor de fundo baseada no nome (consistente)
    $bgColorIndex = $name ? (array_sum(array_map('ord', str_split($name))) % count($bgColors)) : 0;
    $bgColor = $bgColors[$bgColorIndex];
@endphp

<div {{ $attributes->merge(['class' => "relative inline-flex shrink-0 {$sizeClass}"]) }}>
    @if($src)
        {{-- Imagem --}}
        <img 
            src="{{ $src }}" 
            alt="{{ $alt ?: $name }}"
            class="w-full h-full object-cover {{ $roundedClass }} {{ $ringClass }} {{ $groupClass }}"
            onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';"
        >
        {{-- Fallback para iniciais se imagem falhar --}}
        <div 
            class="w-full h-full items-center justify-center font-medium text-white {{ $bgColor }} {{ $roundedClass }} {{ $ringClass }} {{ $groupClass }}"
            style="display: none;"
        >
            @if($initials)
                {{ $initials }}
            @elseif($placeholder)
                <svg class="w-1/2 h-1/2" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/>
                </svg>
            @endif
        </div>
    @elseif($initials)
        {{-- Iniciais --}}
        <div class="w-full h-full flex items-center justify-center font-medium text-white {{ $bgColor }} {{ $roundedClass }} {{ $ringClass }} {{ $groupClass }}">
            {{ $initials }}
        </div>
    @elseif($placeholder)
        {{-- Placeholder --}}
        <div class="w-full h-full flex items-center justify-center bg-gray-200 dark:bg-gray-700 text-gray-500 dark:text-gray-400 {{ $roundedClass }} {{ $ringClass }} {{ $groupClass }}">
            <svg class="w-1/2 h-1/2" fill="currentColor" viewBox="0 0 24 24">
                <path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/>
            </svg>
        </div>
    @else
        {{-- Slot customizado --}}
        <div class="w-full h-full flex items-center justify-center {{ $roundedClass }} {{ $ringClass }} {{ $groupClass }}">
            {{ $slot }}
        </div>
    @endif

    {{-- Status indicator --}}
    @if($status)
        <span class="absolute {{ $statusPositionClass }} block {{ $statusSizeClass }} {{ $statusColorClass }} {{ $rounded === 'full' ? 'rounded-full' : 'rounded' }} ring-2 ring-white dark:ring-gray-800"></span>
    @endif
</div>
