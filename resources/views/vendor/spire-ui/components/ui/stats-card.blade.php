@props([
    'title' => '',
    'value' => '',
    'change' => null,
    'changeType' => null, // up, down, neutral
    'changeLabel' => '',
    'icon' => null,
    'iconColor' => 'primary',
    'chart' => null, // mini sparkline data
    'variant' => 'default', // default, gradient, minimal
    'color' => 'primary',
])

@php
    $iconColorClasses = [
        'primary' => 'bg-primary-100 dark:bg-primary-900/30 text-primary-600 dark:text-primary-400',
        'success' => 'bg-green-100 dark:bg-green-900/30 text-green-600 dark:text-green-400',
        'warning' => 'bg-yellow-100 dark:bg-yellow-900/30 text-yellow-600 dark:text-yellow-400',
        'danger' => 'bg-red-100 dark:bg-red-900/30 text-red-600 dark:text-red-400',
        'info' => 'bg-blue-100 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400',
        'gray' => 'bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-400',
    ][$iconColor] ?? 'bg-primary-100 dark:bg-primary-900/30 text-primary-600 dark:text-primary-400';

    $gradientColors = [
        'primary' => 'from-primary-500 to-primary-600',
        'success' => 'from-green-500 to-green-600',
        'warning' => 'from-yellow-500 to-yellow-600',
        'danger' => 'from-red-500 to-red-600',
        'info' => 'from-blue-500 to-blue-600',
        'purple' => 'from-purple-500 to-purple-600',
        'pink' => 'from-pink-500 to-pink-600',
    ][$color] ?? 'from-primary-500 to-primary-600';

    // Auto-detect change type if not provided
    if ($changeType === null && $change !== null) {
        $changeValue = floatval(str_replace(['%', '+', '-', ','], ['', '', '', '.'], $change));
        $changeType = $changeValue > 0 ? 'up' : ($changeValue < 0 ? 'down' : 'neutral');
    }

    $changeColors = [
        'up' => 'text-green-600 dark:text-green-400',
        'down' => 'text-red-600 dark:text-red-400',
        'neutral' => 'text-gray-600 dark:text-gray-400',
    ][$changeType ?? 'neutral'];

    $changeBgColors = [
        'up' => 'bg-green-100 dark:bg-green-900/30',
        'down' => 'bg-red-100 dark:bg-red-900/30',
        'neutral' => 'bg-gray-100 dark:bg-gray-700',
    ][$changeType ?? 'neutral'];
@endphp

@if($variant === 'gradient')
    {{-- Gradient Variant --}}
    <div {{ $attributes->merge(['class' => "relative overflow-hidden rounded-2xl bg-gradient-to-br {$gradientColors} p-6 text-white shadow-xl"]) }}>
        {{-- Background Pattern --}}
        <div class="absolute inset-0 opacity-10">
            <svg class="h-full w-full" viewBox="0 0 100 100" preserveAspectRatio="none">
                <defs>
                    <pattern id="grid" width="10" height="10" patternUnits="userSpaceOnUse">
                        <path d="M 10 0 L 0 0 0 10" fill="none" stroke="white" stroke-width="0.5"/>
                    </pattern>
                </defs>
                <rect width="100" height="100" fill="url(#grid)"/>
            </svg>
        </div>

        <div class="relative">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-sm font-medium text-white/80">{{ $title }}</p>
                    <p class="mt-2 text-3xl font-bold">{{ $value }}</p>
                </div>
                @if($icon)
                    <div class="rounded-xl bg-white/20 p-3">
                        <x-ui.icon :name="$icon" size="lg" />
                    </div>
                @endif
            </div>

            @if($change !== null)
                <div class="mt-4 flex items-center gap-2">
                    <span class="flex items-center gap-1 rounded-full bg-white/20 px-2 py-0.5 text-sm font-medium">
                        @if($changeType === 'up')
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18"/></svg>
                        @elseif($changeType === 'down')
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"/></svg>
                        @endif
                        {{ $change }}
                    </span>
                    @if($changeLabel)
                        <span class="text-sm text-white/70">{{ $changeLabel }}</span>
                    @endif
                </div>
            @endif
        </div>
    </div>

@elseif($variant === 'minimal')
    {{-- Minimal Variant --}}
    <div {{ $attributes->merge(['class' => 'flex items-center gap-4 p-4']) }}>
        @if($icon)
            <div class="rounded-xl {{ $iconColorClasses }} p-3">
                <x-ui.icon :name="$icon" size="lg" />
            </div>
        @endif
        <div>
            <p class="text-sm text-gray-500 dark:text-gray-400">{{ $title }}</p>
            <div class="flex items-baseline gap-2">
                <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $value }}</p>
                @if($change !== null)
                    <span class="flex items-center text-sm font-medium {{ $changeColors }}">
                        @if($changeType === 'up')
                            <svg class="w-3 h-3 mr-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18"/></svg>
                        @elseif($changeType === 'down')
                            <svg class="w-3 h-3 mr-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"/></svg>
                        @endif
                        {{ $change }}
                    </span>
                @endif
            </div>
        </div>
    </div>

@else
    {{-- Default Variant --}}
    <div {{ $attributes->merge(['class' => 'bg-white dark:bg-gray-800 rounded-2xl p-6 shadow-lg border border-gray-100 dark:border-gray-700']) }}>
        <div class="flex items-start justify-between">
            <div class="flex-1">
                <p class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ $title }}</p>
                <p class="mt-2 text-3xl font-bold text-gray-900 dark:text-white">{{ $value }}</p>
                
                @if($change !== null)
                    <div class="mt-3 flex items-center gap-2">
                        <span class="inline-flex items-center gap-1 rounded-full {{ $changeBgColors }} px-2.5 py-0.5 text-sm font-medium {{ $changeColors }}">
                            @if($changeType === 'up')
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18"/></svg>
                            @elseif($changeType === 'down')
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"/></svg>
                            @endif
                            {{ $change }}
                        </span>
                        @if($changeLabel)
                            <span class="text-sm text-gray-500 dark:text-gray-400">{{ $changeLabel }}</span>
                        @endif
                    </div>
                @endif
            </div>

            @if($icon)
                <div class="rounded-xl {{ $iconColorClasses }} p-3">
                    <x-ui.icon :name="$icon" size="lg" />
                </div>
            @endif
        </div>

        {{-- Mini Chart (Sparkline) --}}
        @if($chart)
            <div class="mt-4 h-12">
                <svg class="w-full h-full" viewBox="0 0 100 40" preserveAspectRatio="none">
                    @php
                        $points = is_array($chart) ? $chart : explode(',', $chart);
                        $max = max($points);
                        $min = min($points);
                        $range = $max - $min ?: 1;
                        $pathData = '';
                        foreach ($points as $i => $point) {
                            $x = ($i / (count($points) - 1)) * 100;
                            $y = 40 - (($point - $min) / $range) * 35;
                            $pathData .= ($i === 0 ? 'M' : 'L') . " $x $y ";
                        }
                    @endphp
                    <path 
                        d="{{ $pathData }}" 
                        fill="none" 
                        stroke="currentColor" 
                        stroke-width="2"
                        class="{{ $changeType === 'up' ? 'text-green-500' : ($changeType === 'down' ? 'text-red-500' : 'text-gray-400') }}"
                    />
                </svg>
            </div>
        @endif

        {{ $slot }}
    </div>
@endif
