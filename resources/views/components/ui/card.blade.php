@props([
    'title' => null,
    'subtitle' => null,
    'padding' => 'md', // none, sm, md, lg
    'shadow' => 'md', // none, sm, md, lg, xl
    'rounded' => 'xl', // none, sm, md, lg, xl, 2xl
    'border' => false,
    'hover' => false, // adiciona efeito hover
    'divided' => true, // linha divisória entre header e body
])

@php
    $paddings = [
        'none' => '',
        'sm' => 'p-4',
        'md' => 'p-6',
        'lg' => 'p-8',
    ];

    $headerPaddings = [
        'none' => 'px-0 py-0',
        'sm' => 'px-4 py-3',
        'md' => 'px-6 py-4',
        'lg' => 'px-8 py-5',
    ];

    $bodyPaddings = [
        'none' => '',
        'sm' => 'p-4',
        'md' => 'p-6',
        'lg' => 'p-8',
    ];

    $shadows = [
        'none' => '',
        'sm' => 'shadow-sm',
        'md' => 'shadow-md',
        'lg' => 'shadow-lg',
        'xl' => 'shadow-xl',
    ];

    $roundedStyles = [
        'none' => '',
        'sm' => 'rounded-sm',
        'md' => 'rounded-md',
        'lg' => 'rounded-lg',
        'xl' => 'rounded-xl',
        '2xl' => 'rounded-2xl',
    ];

    $hasHeader = $title || $subtitle || isset($actions) || isset($badges);
    $paddingClass = $paddings[$padding] ?? $paddings['md'];
    $headerPaddingClass = $headerPaddings[$padding] ?? $headerPaddings['md'];
    $bodyPaddingClass = $bodyPaddings[$padding] ?? $bodyPaddings['md'];
    $shadowClass = $shadows[$shadow] ?? $shadows['md'];
    $roundedClass = $roundedStyles[$rounded] ?? $roundedStyles['xl'];
    $borderClass = $border ? 'border border-gray-200 dark:border-gray-700' : '';
    $hoverClass = $hover ? 'transition-all duration-200 hover:shadow-lg hover:-translate-y-0.5' : '';
@endphp

<div {{ $attributes->merge([
    'class' => "bg-white dark:bg-gray-800 {$shadowClass} {$roundedClass} {$borderClass} {$hoverClass} overflow-hidden"
]) }}>
    {{-- Header --}}
    @if($hasHeader)
        <div class="flex items-center justify-between gap-4 {{ $headerPaddingClass }} {{ $divided ? 'border-b border-gray-100 dark:border-gray-700' : '' }}">
            {{-- Title Section --}}
            <div class="flex-1 min-w-0">
                @if($title)
                    <h3 class="text-base font-semibold text-gray-900 dark:text-white truncate">
                        {{ $title }}
                    </h3>
                @endif
                @if($subtitle)
                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-0.5 truncate">
                        {{ $subtitle }}
                    </p>
                @endif
            </div>

            {{-- Badges & Actions --}}
            <div class="flex items-center gap-3 shrink-0">
                @isset($badges)
                    <div class="flex items-center gap-2">
                        {{ $badges }}
                    </div>
                @endisset
                
                @isset($actions)
                    <div class="flex items-center gap-1">
                        {{ $actions }}
                    </div>
                @endisset
            </div>
        </div>
    @endif

    {{-- Body --}}
    @if($hasHeader)
        <div class="{{ $bodyPaddingClass }}">
            {{ $slot }}
        </div>
    @else
        <div class="{{ $paddingClass }}">
            {{ $slot }}
        </div>
    @endif

    {{-- Footer --}}
    @isset($footer)
        <div class="border-t border-gray-100 dark:border-gray-700 {{ $headerPaddingClass }} bg-gray-50 dark:bg-gray-800/50">
            {{ $footer }}
        </div>
    @endisset
</div>
