@props([
    'title' => null,
    'subtitle' => null,
    'icon' => null, // emoji ou slot
    'border' => true, // linha inferior
    'sticky' => false, // fixo no topo
    'padding' => 'md', // sm, md, lg
    'background' => true, // fundo branco
])

@php
    $paddings = [
        'sm' => 'px-4 py-3',
        'md' => 'px-6 py-4',
        'lg' => 'px-8 py-5',
    ];

    $paddingClass = $paddings[$padding] ?? $paddings['md'];
    $borderClass = $border ? 'border-b border-gray-200 dark:border-gray-700' : '';
    $stickyClass = $sticky ? 'sticky top-0 z-40' : '';
    $bgClass = $background ? 'bg-white dark:bg-gray-800' : '';
@endphp

<header {{ $attributes->merge(['class' => "{$bgClass} {$paddingClass} {$borderClass} {$stickyClass}"]) }}>
    <div class="flex items-center justify-between gap-4">
        {{-- Left: Icon + Title --}}
        <div class="flex items-center gap-3 min-w-0">
            {{-- Icon --}}
            @if($icon)
                <span class="text-2xl shrink-0">{{ $icon }}</span>
            @elseif(isset($iconSlot))
                <div class="shrink-0">
                    {{ $iconSlot }}
                </div>
            @endif

            {{-- Title & Subtitle --}}
            <div class="min-w-0">
                @if($title)
                    <h1 class="text-lg font-semibold text-gray-900 dark:text-white truncate">
                        {{ $title }}
                    </h1>
                @elseif(isset($titleSlot))
                    {{ $titleSlot }}
                @endif

                @if($subtitle)
                    <p class="text-sm text-gray-500 dark:text-gray-400 truncate">
                        {{ $subtitle }}
                    </p>
                @elseif(isset($subtitleSlot))
                    {{ $subtitleSlot }}
                @endif
            </div>
        </div>

        {{-- Right: Actions --}}
        @if($slot->isNotEmpty() || isset($actions))
            <div class="flex items-center gap-3 shrink-0">
                @isset($actions)
                    {{ $actions }}
                @else
                    {{ $slot }}
                @endisset
            </div>
        @endif
    </div>

    {{-- Extra content below (tabs, filters, etc) --}}
    @isset($below)
        <div class="mt-4">
            {{ $below }}
        </div>
    @endisset
</header>
