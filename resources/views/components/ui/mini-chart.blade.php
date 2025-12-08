@props([
    'type' => 'line',
    'data' => [],
    'labels' => [],
    'color' => 'blue',
    'height' => 60,
    'width' => null,
    'showDots' => false,
    'showGrid' => false,
    'showLabels' => false,
    'animate' => true,
    'fill' => false,
    'strokeWidth' => 2,
])

<?php
    $chartId = 'chart-' . uniqid();
    $dataPoints = is_array($data) ? $data : explode(',', $data);
    $dataPoints = array_map('floatval', $dataPoints);
    
    if (empty($dataPoints)) {
        $dataPoints = [0];
    }
    
    $min = min($dataPoints);
    $max = max($dataPoints);
    $range = $max - $min ?: 1;
    
    $colorMap = [
        'blue' => ['stroke' => '#3b82f6', 'fill' => 'rgba(59, 130, 246, 0.1)', 'dot' => '#2563eb'],
        'green' => ['stroke' => '#22c55e', 'fill' => 'rgba(34, 197, 94, 0.1)', 'dot' => '#16a34a'],
        'red' => ['stroke' => '#ef4444', 'fill' => 'rgba(239, 68, 68, 0.1)', 'dot' => '#dc2626'],
        'purple' => ['stroke' => '#a855f7', 'fill' => 'rgba(168, 85, 247, 0.1)', 'dot' => '#9333ea'],
        'orange' => ['stroke' => '#f97316', 'fill' => 'rgba(249, 115, 22, 0.1)', 'dot' => '#ea580c'],
        'gray' => ['stroke' => '#6b7280', 'fill' => 'rgba(107, 114, 128, 0.1)', 'dot' => '#4b5563'],
        'cyan' => ['stroke' => '#06b6d4', 'fill' => 'rgba(6, 182, 212, 0.1)', 'dot' => '#0891b2'],
    ];
    
    $colors = $colorMap[$color] ?? $colorMap['blue'];
    $chartWidth = $width ?? (count($dataPoints) * 20);
    $padding = 4;
    $innerWidth = $chartWidth - ($padding * 2);
    $innerHeight = $height - ($padding * 2);
    $bottomY = $innerHeight + $padding;
    
    // Calculate points with all needed values
    $points = [];
    $stepX = count($dataPoints) > 1 ? $innerWidth / (count($dataPoints) - 1) : 0;
    $barWidth = count($dataPoints) > 0 ? ($innerWidth / count($dataPoints)) * 0.7 : 10;
    
    foreach ($dataPoints as $i => $value) {
        $x = $padding + ($i * $stepX);
        $y = $padding + $innerHeight - (($value - $min) / $range * $innerHeight);
        $points[] = [
            'x' => $x,
            'y' => $y,
            'value' => $value,
            'barX' => $x - ($barWidth / 2),
            'barHeight' => $bottomY - $y,
            'barDelay' => $i * 50,
            'dotDelay' => 500 + ($i * 50),
            'label' => $labels[$i] ?? '',
        ];
    }
    
    // Generate path
    $pathD = '';
    foreach ($points as $i => $point) {
        $cmd = $i === 0 ? 'M' : 'L';
        $pathD .= $cmd . ' ' . $point['x'] . ',' . $point['y'] . ' ';
    }
    
    // Area path (closed)
    $areaD = $pathD;
    if (!empty($points)) {
        $lastPoint = end($points);
        $firstPoint = reset($points);
        $areaD .= 'L ' . $lastPoint['x'] . ',' . $bottomY . ' L ' . $firstPoint['x'] . ',' . $bottomY . ' Z';
    }
    
    // Grid lines
    $gridLines = [];
    for ($i = 0; $i <= 4; $i++) {
        $gridLines[] = [
            'y' => $padding + ($innerHeight / 4 * $i),
            'x2' => $chartWidth - $padding,
        ];
    }
?>

<div class="inline-block" data-v="minichart" data-chart-id="{{ $chartId }}">
    <svg width="{{ $chartWidth }}" height="{{ $height }}" viewBox="0 0 {{ $chartWidth }} {{ $height }}" class="overflow-visible">
        
        @if($showGrid)
            <g class="text-gray-200 dark:text-gray-700">
                @foreach($gridLines as $line)
                    <line x1="{{ $padding }}" y1="{{ $line['y'] }}" x2="{{ $line['x2'] }}" y2="{{ $line['y'] }}" stroke="currentColor" stroke-width="0.5" stroke-dasharray="2,2"/>
                @endforeach
            </g>
        @endif
        
        @if($type === 'bar')
            @foreach($points as $point)
                <rect
                    x="{{ $point['barX'] }}"
                    y="{{ $point['y'] }}"
                    width="{{ $barWidth }}"
                    height="{{ $point['barHeight'] }}"
                    fill="{{ $colors['stroke'] }}"
                    rx="2"
                    @if($animate) class="animate-grow-up" style="animation-delay: {{ $point['barDelay'] }}ms;" @endif
                ><title>{{ $point['label'] }}: {{ $point['value'] }}</title></rect>
            @endforeach
            
        @elseif($type === 'area' || $fill)
            <path d="{{ $areaD }}" fill="{{ $colors['fill'] }}" @if($animate) class="animate-fade-in" @endif/>
            <path d="{{ $pathD }}" fill="none" stroke="{{ $colors['stroke'] }}" stroke-width="{{ $strokeWidth }}" stroke-linecap="round" stroke-linejoin="round" @if($animate) style="stroke-dasharray: 1000; stroke-dashoffset: 1000; animation: draw 1s ease forwards;" @endif/>
            
        @else
            <path d="{{ $pathD }}" fill="none" stroke="{{ $colors['stroke'] }}" stroke-width="{{ $strokeWidth }}" stroke-linecap="round" stroke-linejoin="round" @if($animate) style="stroke-dasharray: 1000; stroke-dashoffset: 1000; animation: draw 1s ease forwards;" @endif/>
        @endif
        
        @if($showDots && $type !== 'bar')
            @foreach($points as $point)
                <circle
                    cx="{{ $point['x'] }}"
                    cy="{{ $point['y'] }}"
                    r="3"
                    fill="white"
                    stroke="{{ $colors['dot'] }}"
                    stroke-width="2"
                    @if($animate) class="animate-pop" style="animation-delay: {{ $point['dotDelay'] }}ms;" @endif
                ><title>{{ $point['label'] }}: {{ $point['value'] }}</title></circle>
            @endforeach
        @endif
    </svg>
    
    @if($showLabels && count($labels) > 0)
        <div class="flex justify-between mt-1 text-xs text-gray-400" style="width: {{ $chartWidth }}px;">
            @foreach($labels as $label)
                <span>{{ $label }}</span>
            @endforeach
        </div>
    @endif
</div>

<style>
@keyframes draw { to { stroke-dashoffset: 0; } }
@keyframes grow-up { from { transform: scaleY(0); transform-origin: bottom; } to { transform: scaleY(1); } }
@keyframes pop { 0% { transform: scale(0); opacity: 0; } 80% { transform: scale(1.2); } 100% { transform: scale(1); opacity: 1; } }
.animate-draw { animation: draw 1s ease forwards; }
.animate-grow-up { animation: grow-up 0.5s ease forwards; }
.animate-pop { animation: pop 0.3s ease forwards; opacity: 0; }
.animate-fade-in { animation: fadeIn 0.5s ease forwards; }
@keyframes fadeIn { from { opacity: 0; } to { opacity: 1; } }
</style>
