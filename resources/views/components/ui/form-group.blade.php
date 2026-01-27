@props([
    'label' => null,
    'hint' => null,
    'error' => null,
    'required' => false,
    'help' => null,
    'helpIcon' => 'question', // question, info
    'size' => 'md', // sm, md, lg
    'layout' => 'vertical', // vertical, horizontal
])

@php
    $groupId = 'form-group-' . uniqid();
    $hasError = !empty($error);
    $hasHint = !empty($hint);
    $hasHelp = !empty($help);

    $sizeClasses = match ($size) {
        'sm' => 'space-y-1',
        'lg' => 'space-y-3',
        default => 'space-y-2',
    };

    $layoutClasses = match ($layout) {
        'horizontal' => 'md:flex md:items-start md:space-x-4',
        default => '',
    };
@endphp

<div class="form-group {{ $sizeClasses }} {{ $layoutClasses }}" id="{{ $groupId }}">
    @if($label)
        <div class="flex items-center space-x-2 {{ $layout === 'horizontal' ? 'md:w-1/3 md:flex-shrink-0' : '' }}">
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 {{ $required ? 'after:content-["*"] after:text-red-500 after:ml-1' : '' }}">
                {{ $label }}
            </label>

            @if($hasHelp)
                <x-spire::tooltip :content="$help" position="top">
                    <button type="button" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 focus:outline-none focus:text-gray-600 dark:focus:text-gray-300">
                        @if($helpIcon === 'info')
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        @else
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        @endif
                    </button>
                </x-spire::tooltip>
            @endif
        </div>
    @endif

    <div class="{{ $layout === 'horizontal' ? 'md:flex-1' : '' }}">
        <div class="relative">
            {{ $slot }}
        </div>

        @if($hasError)
            <p class="text-sm text-red-600 dark:text-red-400 flex items-center space-x-1">
                <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <span>{{ $error }}</span>
            </p>
        @elseif($hasHint)
            <p class="text-sm text-gray-500 dark:text-gray-400">
                {{ $hint }}
            </p>
        @endif
    </div>
</div>