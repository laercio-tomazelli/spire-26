@props([
    'name' => 'Plano',
    'description' => null,
    'price' => '0',
    'currency' => 'R$',
    'period' => '/mês',
    'features' => [],
    'popular' => false,
    'highlighted' => false,
    'ctaLabel' => 'Começar agora',
    'ctaUrl' => '#',
    'variant' => 'default', // default, gradient, minimal
    'color' => 'primary',
    'discount' => null,
    'originalPrice' => null,
])

@php
    $gradientColors = [
        'primary' => 'from-primary-500 to-primary-600',
        'success' => 'from-green-500 to-green-600',
        'warning' => 'from-yellow-500 to-yellow-600',
        'danger' => 'from-red-500 to-red-600',
        'info' => 'from-blue-500 to-blue-600',
        'purple' => 'from-purple-500 to-purple-600',
        'pink' => 'from-pink-500 to-pink-600',
    ][$color] ?? 'from-primary-500 to-primary-600';

    $borderColors = [
        'primary' => 'border-primary-500',
        'success' => 'border-green-500',
        'warning' => 'border-yellow-500',
        'danger' => 'border-red-500',
        'info' => 'border-blue-500',
        'purple' => 'border-purple-500',
        'pink' => 'border-pink-500',
    ][$color] ?? 'border-primary-500';

    $buttonColors = [
        'primary' => 'from-primary-500 to-primary-600 hover:from-primary-600 hover:to-primary-700',
        'success' => 'from-green-500 to-green-600 hover:from-green-600 hover:to-green-700',
        'warning' => 'from-yellow-500 to-yellow-600 hover:from-yellow-600 hover:to-yellow-700',
        'danger' => 'from-red-500 to-red-600 hover:from-red-600 hover:to-red-700',
        'info' => 'from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700',
        'purple' => 'from-purple-500 to-purple-600 hover:from-purple-600 hover:to-purple-700',
        'pink' => 'from-pink-500 to-pink-600 hover:from-pink-600 hover:to-pink-700',
    ][$color] ?? 'from-primary-500 to-primary-600 hover:from-primary-600 hover:to-primary-700';
@endphp

@if($variant === 'gradient')
    {{-- Gradient Variant --}}
    <div {{ $attributes->merge(['class' => "relative overflow-hidden rounded-2xl bg-linear-to-br {$gradientColors} p-[2px]"]) }}>
        <div class="relative h-full rounded-[14px] bg-white dark:bg-gray-900 p-6">
            {{-- Popular Badge --}}
            @if($popular)
                <div class="absolute -top-px left-1/2 -translate-x-1/2">
                    <div class="px-3 py-1 text-xs font-bold text-white bg-linear-to-r {{ $gradientColors }} rounded-b-lg">
                        MAIS POPULAR
                    </div>
                </div>
            @endif

            {{-- Header --}}
            <div class="text-center {{ $popular ? 'pt-4' : '' }}">
                <h3 class="text-xl font-bold text-gray-900 dark:text-white">{{ $name }}</h3>
                @if($description)
                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">{{ $description }}</p>
                @endif
            </div>

            {{-- Price --}}
            <div class="mt-6 text-center">
                @if($discount)
                    <span class="inline-block px-2 py-0.5 mb-2 text-xs font-bold text-white bg-linear-to-r {{ $gradientColors }} rounded-full">
                        {{ $discount }}
                    </span>
                @endif
                <div class="flex items-baseline justify-center gap-1">
                    <span class="text-lg font-medium text-gray-500 dark:text-gray-400">{{ $currency }}</span>
                    <span class="text-5xl font-extrabold bg-linear-to-r {{ $gradientColors }} bg-clip-text text-transparent">{{ $price }}</span>
                    <span class="text-gray-500 dark:text-gray-400">{{ $period }}</span>
                </div>
                @if($originalPrice)
                    <p class="mt-1 text-sm text-gray-400 dark:text-gray-500 line-through">{{ $currency }} {{ $originalPrice }}{{ $period }}</p>
                @endif
            </div>

            {{-- Features --}}
            <ul class="mt-8 space-y-3">
                @foreach($features as $feature)
                    @php
                        $included = is_array($feature) ? ($feature['included'] ?? true) : true;
                        $label = is_array($feature) ? ($feature['label'] ?? $feature) : $feature;
                        $highlight = is_array($feature) ? ($feature['highlight'] ?? false) : false;
                    @endphp
                    <li class="flex items-start gap-3 {{ !$included ? 'opacity-50' : '' }}">
                        @if($included)
                            <svg class="w-5 h-5 shrink-0 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                        @else
                            <svg class="w-5 h-5 shrink-0 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        @endif
                        <span class="text-sm text-gray-600 dark:text-gray-400 {{ $highlight ? 'font-semibold text-gray-900 dark:text-white' : '' }}">
                            {{ $label }}
                        </span>
                    </li>
                @endforeach
            </ul>

            {{-- CTA --}}
            <div class="mt-8">
                <a href="{{ $ctaUrl }}" class="block w-full py-3 px-4 text-center font-semibold text-white bg-linear-to-r {{ $buttonColors }} rounded-xl shadow-lg hover:shadow-xl transition-all">
                    {{ $ctaLabel }}
                </a>
            </div>

            {{ $slot }}
        </div>
    </div>

@elseif($variant === 'minimal')
    {{-- Minimal Variant --}}
    <div {{ $attributes->merge(['class' => 'p-6']) }}>
        <div class="flex items-center justify-between">
            <div>
                <h3 class="font-bold text-gray-900 dark:text-white">{{ $name }}</h3>
                @if($description)
                    <p class="text-sm text-gray-500 dark:text-gray-400">{{ $description }}</p>
                @endif
            </div>
            <div class="text-right">
                <div class="flex items-baseline gap-1">
                    <span class="text-sm text-gray-500">{{ $currency }}</span>
                    <span class="text-2xl font-bold text-gray-900 dark:text-white">{{ $price }}</span>
                    <span class="text-sm text-gray-500">{{ $period }}</span>
                </div>
            </div>
        </div>
        <div class="mt-4 flex items-center gap-4">
            <a href="{{ $ctaUrl }}" class="px-4 py-2 text-sm font-medium text-white bg-linear-to-r {{ $buttonColors }} rounded-lg">
                {{ $ctaLabel }}
            </a>
            <span class="text-sm text-gray-500 dark:text-gray-400">
                {{ count($features) }} recursos incluídos
            </span>
        </div>
    </div>

@else
    {{-- Default Variant --}}
    <div {{ $attributes->merge(['class' => "relative bg-white dark:bg-gray-800 rounded-2xl shadow-xl border-2 transition-all hover:shadow-2xl " . ($highlighted ? $borderColors : 'border-gray-100 dark:border-gray-700')]) }}>
        {{-- Popular Badge --}}
        @if($popular)
            <div class="absolute -top-4 left-1/2 -translate-x-1/2">
                <span class="px-4 py-1 text-sm font-bold text-white bg-linear-to-r {{ $gradientColors }} rounded-full shadow-lg">
                    MAIS POPULAR
                </span>
            </div>
        @endif

        <div class="p-6 {{ $popular ? 'pt-8' : '' }}">
            {{-- Header --}}
            <div class="text-center">
                <h3 class="text-xl font-bold text-gray-900 dark:text-white">{{ $name }}</h3>
                @if($description)
                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">{{ $description }}</p>
                @endif
            </div>

            {{-- Price --}}
            <div class="mt-6 text-center">
                @if($discount)
                    <span class="inline-block px-2 py-0.5 mb-2 text-xs font-bold text-white bg-linear-to-r {{ $gradientColors }} rounded-full">
                        {{ $discount }}
                    </span>
                @endif
                <div class="flex items-baseline justify-center gap-1">
                    <span class="text-lg font-medium text-gray-500 dark:text-gray-400">{{ $currency }}</span>
                    <span class="text-5xl font-extrabold text-gray-900 dark:text-white">{{ $price }}</span>
                    <span class="text-gray-500 dark:text-gray-400">{{ $period }}</span>
                </div>
                @if($originalPrice)
                    <p class="mt-1 text-sm text-gray-400 dark:text-gray-500 line-through">{{ $currency }} {{ $originalPrice }}{{ $period }}</p>
                @endif
            </div>

            {{-- Features --}}
            <ul class="mt-8 space-y-3">
                @foreach($features as $feature)
                    @php
                        $included = is_array($feature) ? ($feature['included'] ?? true) : true;
                        $label = is_array($feature) ? ($feature['label'] ?? $feature) : $feature;
                        $highlight = is_array($feature) ? ($feature['highlight'] ?? false) : false;
                    @endphp
                    <li class="flex items-start gap-3 {{ !$included ? 'opacity-50' : '' }}">
                        @if($included)
                            <svg class="w-5 h-5 shrink-0 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                        @else
                            <svg class="w-5 h-5 shrink-0 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        @endif
                        <span class="text-sm text-gray-600 dark:text-gray-400 {{ $highlight ? 'font-semibold text-gray-900 dark:text-white' : '' }}">
                            {{ $label }}
                        </span>
                    </li>
                @endforeach
            </ul>

            {{-- CTA --}}
            <div class="mt-8">
                <a href="{{ $ctaUrl }}" class="block w-full py-3 px-4 text-center font-semibold text-white bg-linear-to-r {{ $buttonColors }} rounded-xl shadow-lg hover:shadow-xl transition-all">
                    {{ $ctaLabel }}
                </a>
            </div>

            {{ $slot }}
        </div>
    </div>
@endif
