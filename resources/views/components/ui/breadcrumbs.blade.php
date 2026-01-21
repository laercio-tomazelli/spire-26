@props([
    'items' => [],
    'separator' => 'chevron', // chevron, slash, arrow, dot
    'home' => null, // URL for home icon
])

@php
    $separators = [
        'chevron' => '<svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>',
        'slash' => '<span class="text-gray-400 mx-1">/</span>',
        'arrow' => '<span class="text-gray-400 mx-1">→</span>',
        'dot' => '<span class="text-gray-400 mx-1">•</span>',
    ];
    $sep = $separators[$separator] ?? $separators['chevron'];
@endphp

<nav {{ $attributes->merge(['class' => 'flex']) }} aria-label="Breadcrumb">
    <ol class="flex items-center flex-wrap gap-1" role="list">
        @if($home)
            <li class="flex items-center">
                <a 
                    href="{{ $home }}" 
                    class="text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200 transition-colors"
                    aria-label="Home"
                >
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                    </svg>
                </a>
            </li>
            @if(count($items) > 0)
                <li class="flex items-center" aria-hidden="true">
                    {!! $sep !!}
                </li>
            @endif
        @endif

        @foreach($items as $index => $item)
            @php
                $isLast = $index === count($items) - 1;
                $href = $item['href'] ?? $item['url'] ?? null;
                $label = $item['label'] ?? $item['name'] ?? $item;
                $icon = $item['icon'] ?? null;
            @endphp
            
            <li class="flex items-center">
                @if($isLast)
                    <span 
                        class="text-sm font-medium text-gray-900 dark:text-white flex items-center gap-1.5"
                        aria-current="page"
                    >
                        @if($icon)
                            <span>{!! $icon !!}</span>
                        @endif
                        {{ $label }}
                    </span>
                @else
                    <a 
                        href="{{ $href ?? '#' }}" 
                        class="text-sm text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200 transition-colors flex items-center gap-1.5"
                    >
                        @if($icon)
                            <span>{!! $icon !!}</span>
                        @endif
                        {{ $label }}
                    </a>
                @endif
            </li>
            
            @if(!$isLast)
                <li class="flex items-center" aria-hidden="true">
                    {!! $sep !!}
                </li>
            @endif
        @endforeach
    </ol>
</nav>
