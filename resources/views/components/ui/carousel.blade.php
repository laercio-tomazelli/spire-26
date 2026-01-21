@props([
    'id' => null,
    'images' => [],
    'autoplay' => false,
    'interval' => 5000,
    'loop' => true,
    'pauseOnHover' => true,
    'showArrows' => true,
    'showIndicators' => true,
    'aspectRatio' => '16/9',
    'rounded' => 'lg',
    'overlay' => false,
    'captions' => false,
    'thumbnails' => false,
])

@php
    $carouselId = $id ?? 'carousel-' . uniqid();
    
    $roundedClasses = [
        'none' => 'rounded-none',
        'sm' => 'rounded-sm',
        'md' => 'rounded-md',
        'lg' => 'rounded-lg',
        'xl' => 'rounded-xl',
        '2xl' => 'rounded-2xl',
        'full' => 'rounded-full',
    ][$rounded] ?? 'rounded-lg';
@endphp

<div 
    data-carousel="{{ $carouselId }}"
    data-autoplay="{{ $autoplay ? 'true' : 'false' }}"
    data-interval="{{ $interval }}"
    data-loop="{{ $loop ? 'true' : 'false' }}"
    data-pause-on-hover="{{ $pauseOnHover ? 'true' : 'false' }}"
    {{ $attributes->merge([
        'class' => "relative group overflow-hidden {$roundedClasses}"
    ]) }}
    role="region"
    aria-roledescription="carousel"
    aria-label="Image carousel"
    tabindex="0"
>
    {{-- Slides Container --}}
    <div 
        data-carousel-track 
        class="relative w-full"
        style="aspect-ratio: {{ $aspectRatio }}"
    >
        @forelse($images as $index => $image)
            @php
                $src = is_array($image) ? ($image['src'] ?? '') : $image;
                $alt = is_array($image) ? ($image['alt'] ?? "Slide " . ($index + 1)) : "Slide " . ($index + 1);
                $caption = is_array($image) ? ($image['caption'] ?? null) : null;
            @endphp
            <div 
                data-carousel-slide
                class="absolute inset-0 transition-opacity duration-500 ease-in-out {{ $index === 0 ? 'opacity-100 z-10' : 'opacity-0 z-0' }}"
                role="group"
                aria-roledescription="slide"
                aria-label="{{ $alt }}"
                aria-hidden="{{ $index !== 0 ? 'true' : 'false' }}"
            >
                <img 
                    src="{{ $src }}" 
                    alt="{{ $alt }}"
                    class="w-full h-full object-cover"
                    loading="{{ $index === 0 ? 'eager' : 'lazy' }}"
                >
                
                {{-- Overlay --}}
                @if($overlay)
                    <div class="absolute inset-0 bg-linear-to-t from-black/60 via-transparent to-transparent"></div>
                @endif
                
                {{-- Caption --}}
                @if($captions && $caption)
                    <div class="absolute bottom-0 left-0 right-0 p-4 md:p-6">
                        <p class="text-white text-sm md:text-base font-medium drop-shadow-lg">
                            {{ $caption }}
                        </p>
                    </div>
                @endif
            </div>
        @empty
            {{-- Slot for custom slides --}}
            {{ $slot }}
        @endforelse
    </div>

    {{-- Navigation Arrows --}}
    @if($showArrows)
        {{-- Previous Button --}}
        <button
            data-carousel-prev
            type="button"
            class="absolute left-2 md:left-4 top-1/2 -translate-y-1/2 z-20 w-10 h-10 md:w-12 md:h-12 flex items-center justify-center rounded-full bg-white/80 dark:bg-gray-800/80 text-gray-800 dark:text-white shadow-lg opacity-0 group-hover:opacity-100 transition-all duration-300 hover:bg-white dark:hover:bg-gray-700 hover:scale-110 focus:outline-none focus:ring-2 focus:ring-primary-500"
            aria-label="Previous slide"
        >
            <svg class="w-5 h-5 md:w-6 md:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
        </button>

        {{-- Next Button --}}
        <button
            data-carousel-next
            type="button"
            class="absolute right-2 md:right-4 top-1/2 -translate-y-1/2 z-20 w-10 h-10 md:w-12 md:h-12 flex items-center justify-center rounded-full bg-white/80 dark:bg-gray-800/80 text-gray-800 dark:text-white shadow-lg opacity-0 group-hover:opacity-100 transition-all duration-300 hover:bg-white dark:hover:bg-gray-700 hover:scale-110 focus:outline-none focus:ring-2 focus:ring-primary-500"
            aria-label="Next slide"
        >
            <svg class="w-5 h-5 md:w-6 md:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
            </svg>
        </button>
    @endif

    {{-- Indicators --}}
    @if($showIndicators && count($images) > 1)
        <div class="absolute bottom-3 md:bottom-4 left-1/2 -translate-x-1/2 z-20 flex items-center gap-2">
            @foreach($images as $index => $image)
                <button
                    data-carousel-indicator
                    type="button"
                    class="w-2.5 h-2.5 md:w-3 md:h-3 rounded-full transition-all duration-300 hover:scale-125 focus:outline-none focus:ring-2 focus:ring-white {{ $index === 0 ? 'bg-white' : 'bg-white/50' }}"
                    aria-label="Go to slide {{ $index + 1 }}"
                    aria-current="{{ $index === 0 ? 'true' : 'false' }}"
                ></button>
            @endforeach
        </div>
    @endif

    {{-- Thumbnails --}}
    @if($thumbnails && count($images) > 1)
        <div class="absolute bottom-0 left-0 right-0 z-20 p-2 md:p-3 bg-black/40 backdrop-blur-sm">
            <div class="flex items-center justify-center gap-2 overflow-x-auto">
                @foreach($images as $index => $image)
                    @php
                        $src = is_array($image) ? ($image['src'] ?? '') : $image;
                        $alt = is_array($image) ? ($image['alt'] ?? "Thumbnail " . ($index + 1)) : "Thumbnail " . ($index + 1);
                    @endphp
                    <button
                        data-carousel-indicator
                        type="button"
                        class="shrink-0 w-12 h-12 md:w-16 md:h-16 rounded-md overflow-hidden border-2 transition-all duration-300 hover:scale-105 focus:outline-none {{ $index === 0 ? 'border-white' : 'border-transparent opacity-60 hover:opacity-100' }}"
                        aria-label="Go to slide {{ $index + 1 }}"
                    >
                        <img src="{{ $src }}" alt="{{ $alt }}" class="w-full h-full object-cover">
                    </button>
                @endforeach
            </div>
        </div>
    @endif

    {{-- Autoplay Progress (optional) --}}
    @if($autoplay)
        <div class="absolute top-0 left-0 right-0 z-20">
            <div 
                class="h-1 bg-white/30 dark:bg-white/20"
                data-carousel-progress
            >
                <div 
                    class="h-full bg-white transition-all ease-linear"
                    style="width: 0%"
                ></div>
            </div>
        </div>
    @endif
</div>
