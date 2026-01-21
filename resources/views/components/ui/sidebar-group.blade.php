@props([
    'label' => '',
])

<li class="pt-4 first:pt-0">
    {{-- Group Label --}}
    @if($label)
        <div class="sidebar-group-label px-3 mb-2 text-xs font-semibold uppercase tracking-wider text-gray-400 dark:text-gray-500 transition-all duration-300 sidebar-collapsed:opacity-0 sidebar-collapsed:h-0 sidebar-collapsed:overflow-hidden">
            {{ $label }}
        </div>
    @endif

    {{-- Group Items --}}
    <ul class="space-y-1">
        {{ $slot }}
    </ul>
</li>
