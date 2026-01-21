@props([
    'id' => null,
    'trigger' => 'Click me',
    'position' => 'bottom-start', // bottom-start, bottom-end, bottom, top-start, top-end, top, left, right
    'width' => 'w-48',
    'align' => 'left', // left, right (legacy support)
    'hover' => false,
    'disabled' => false,
    'divided' => false, // Add dividers between items
])

@php
    $dropdownId = $id ?? 'dropdown-' . uniqid();
    
    $positionClasses = match($position) {
        'bottom-start' => 'top-full left-0 mt-2',
        'bottom-end' => 'top-full right-0 mt-2',
        'bottom' => 'top-full left-1/2 -translate-x-1/2 mt-2',
        'top-start' => 'bottom-full left-0 mb-2',
        'top-end' => 'bottom-full right-0 mb-2',
        'top' => 'bottom-full left-1/2 -translate-x-1/2 mb-2',
        'left' => 'right-full top-0 mr-2',
        'right' => 'left-full top-0 ml-2',
        default => 'top-full left-0 mt-2',
    };
    
    // Legacy align support
    if ($align === 'right' && $position === 'bottom-start') {
        $positionClasses = 'top-full right-0 mt-2';
    }
@endphp

<div 
    class="relative inline-block"
    data-v="dropdown"
    data-dropdown-id="{{ $dropdownId }}"
    @if($hover) data-dropdown-hover @endif
>
    {{-- Trigger --}}
    <button
        type="button"
        class="{{ $disabled ? 'opacity-50 cursor-not-allowed' : 'cursor-pointer' }}"
        data-dropdown-trigger
        @if($disabled) disabled @endif
        aria-haspopup="true"
        aria-expanded="false"
        onclick="
            if (this.disabled) return;
            const dropdown = this.closest('[data-v=dropdown]');
            const menu = dropdown.querySelector('[data-dropdown-menu]');
            const isOpen = menu.classList.contains('opacity-100');
            
            // Close all other dropdowns
            document.querySelectorAll('[data-dropdown-menu].opacity-100').forEach(m => {
                if (m !== menu) {
                    m.classList.remove('opacity-100', 'scale-100', 'pointer-events-auto');
                    m.classList.add('opacity-0', 'scale-95', 'pointer-events-none');
                    m.closest('[data-v=dropdown]').querySelector('[data-dropdown-trigger]').setAttribute('aria-expanded', 'false');
                }
            });
            
            if (isOpen) {
                menu.classList.remove('opacity-100', 'scale-100', 'pointer-events-auto');
                menu.classList.add('opacity-0', 'scale-95', 'pointer-events-none');
                this.setAttribute('aria-expanded', 'false');
            } else {
                menu.classList.remove('opacity-0', 'scale-95', 'pointer-events-none');
                menu.classList.add('opacity-100', 'scale-100', 'pointer-events-auto');
                this.setAttribute('aria-expanded', 'true');
            }
        "
        @if($hover)
        onmouseenter="
            const menu = this.closest('[data-v=dropdown]').querySelector('[data-dropdown-menu]');
            menu.classList.remove('opacity-0', 'scale-95', 'pointer-events-none');
            menu.classList.add('opacity-100', 'scale-100', 'pointer-events-auto');
            this.setAttribute('aria-expanded', 'true');
        "
        @endif
    >
        @if(isset($triggerSlot))
            {{ $triggerSlot }}
        @else
            <span class="inline-flex items-center gap-1">
                {{ $trigger }}
                <svg class="w-4 h-4 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                </svg>
            </span>
        @endif
    </button>

    {{-- Menu --}}
    <div
        data-dropdown-menu
        class="absolute z-50 {{ $positionClasses }} {{ $width }} origin-top-left
               bg-white dark:bg-gray-800 rounded-lg shadow-lg border border-gray-200 dark:border-gray-700
               opacity-0 scale-95 pointer-events-none
               transition-all duration-200 ease-out"
        role="menu"
        @if($hover)
        onmouseenter="
            this.classList.remove('opacity-0', 'scale-95', 'pointer-events-none');
            this.classList.add('opacity-100', 'scale-100', 'pointer-events-auto');
        "
        onmouseleave="
            this.classList.remove('opacity-100', 'scale-100', 'pointer-events-auto');
            this.classList.add('opacity-0', 'scale-95', 'pointer-events-none');
            this.closest('[data-v=dropdown]').querySelector('[data-dropdown-trigger]').setAttribute('aria-expanded', 'false');
        "
        @endif
    >
        <div class="py-1 {{ $divided ? 'divide-y divide-gray-100 dark:divide-gray-700' : '' }}">
            {{ $slot }}
        </div>
    </div>
</div>

{{-- Close on click outside (once per page) --}}
@pushOnce('scripts')
<script>
document.addEventListener('click', function(e) {
    if (!e.target.closest('[data-v="dropdown"]')) {
        document.querySelectorAll('[data-dropdown-menu].opacity-100').forEach(menu => {
            menu.classList.remove('opacity-100', 'scale-100', 'pointer-events-auto');
            menu.classList.add('opacity-0', 'scale-95', 'pointer-events-none');
            menu.closest('[data-v=dropdown]').querySelector('[data-dropdown-trigger]').setAttribute('aria-expanded', 'false');
        });
    }
});

// Keyboard navigation
document.addEventListener('keydown', function(e) {
    const activeDropdown = document.querySelector('[data-dropdown-menu].opacity-100');
    if (!activeDropdown) return;
    
    if (e.key === 'Escape') {
        activeDropdown.classList.remove('opacity-100', 'scale-100', 'pointer-events-auto');
        activeDropdown.classList.add('opacity-0', 'scale-95', 'pointer-events-none');
        activeDropdown.closest('[data-v=dropdown]').querySelector('[data-dropdown-trigger]').focus();
    }
    
    if (e.key === 'ArrowDown' || e.key === 'ArrowUp') {
        e.preventDefault();
        const items = activeDropdown.querySelectorAll('[data-dropdown-item]:not([disabled])');
        const currentIndex = Array.from(items).findIndex(item => item === document.activeElement);
        let nextIndex;
        
        if (e.key === 'ArrowDown') {
            nextIndex = currentIndex < items.length - 1 ? currentIndex + 1 : 0;
        } else {
            nextIndex = currentIndex > 0 ? currentIndex - 1 : items.length - 1;
        }
        
        items[nextIndex]?.focus();
    }
});
</script>
@endPushOnce
