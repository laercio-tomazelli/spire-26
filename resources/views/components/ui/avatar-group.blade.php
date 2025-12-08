@props([
    'max' => null, // máximo de avatares visíveis
    'size' => 'md', // tamanho padrão dos avatares
    'spacing' => '-2', // espaçamento negativo (sobreposição)
])

@php
    $spacingClass = "space-x-{$spacing}";
    
    // Espaçamentos negativos disponíveis
    $spacings = [
        '-1' => '-space-x-1',
        '-2' => '-space-x-2',
        '-3' => '-space-x-3',
        '-4' => '-space-x-4',
    ];
    
    $spacingStyle = $spacings[$spacing] ?? $spacings['-2'];
@endphp

<div {{ $attributes->merge(['class' => "flex items-center {$spacingStyle}"]) }} data-avatar-group data-max="{{ $max }}" data-size="{{ $size }}">
    {{ $slot }}
</div>
