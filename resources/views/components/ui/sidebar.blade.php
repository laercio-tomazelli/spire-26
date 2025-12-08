@props([
    'id' => null,
    'persist' => null,
    'collapsed' => false,
    'logo' => null,
    'logoCollapsed' => null,
    'header' => null,
    'footer' => null,
])

@php
    $sidebarId = $id ?? 'sidebar-' . uniqid();
@endphp

{{-- Mobile Hamburger Button (shown outside sidebar) --}}
<button 
    type="button"
    data-sidebar-mobile-trigger="{{ $sidebarId }}"
    class="lg:hidden fixed top-4 left-4 z-50 p-2 rounded-lg bg-white dark:bg-gray-800 shadow-lg border border-gray-200 dark:border-gray-700"
    aria-label="Abrir menu"
>
    <svg class="w-6 h-6 text-gray-600 dark:text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
    </svg>
</button>

{{-- Sidebar Container --}}
<aside 
    id="{{ $sidebarId }}"
    data-v="sidebar"
    @if($persist) data-persist="{{ $persist }}" @endif
    {{ $attributes->merge([
        'class' => 'sidebar group/sidebar fixed inset-y-0 left-0 z-40 flex flex-col
                    bg-white dark:bg-gray-900 border-r border-gray-200 dark:border-gray-800
                    transition-all duration-300 ease-in-out
                    w-64 sidebar-collapsed:w-20
                    -translate-x-full lg:translate-x-0
                    sidebar-mobile-open:translate-x-0'
    ]) }}
>
    {{-- Overlay for mobile --}}
    <div 
        data-sidebar-overlay
        class="hidden fixed inset-0 bg-black/50 backdrop-blur-sm z-[-1] lg:hidden transition-opacity duration-300 opacity-0"
    ></div>

    {{-- Header / Logo --}}
    <div class="flex items-center justify-between h-16 px-4 border-b border-gray-200 dark:border-gray-800 shrink-0">
        {{-- Logo --}}
        <div class="flex items-center gap-3 overflow-hidden">
            @if($logo)
                <div class="sidebar-logo shrink-0 transition-all duration-300">
                    {{ $logo }}
                </div>
            @endif
            
            @if($logoCollapsed)
                <div class="sidebar-logo-collapsed hidden shrink-0">
                    {{ $logoCollapsed }}
                </div>
            @endif

            @if($header)
                <div class="sidebar-header-content whitespace-nowrap transition-all duration-300 opacity-100 sidebar-collapsed:opacity-0 sidebar-collapsed:w-0">
                    {{ $header }}
                </div>
            @endif
        </div>

        {{-- Toggle Button --}}
        <button 
            type="button"
            data-sidebar-toggle
            class="hidden lg:flex p-2 rounded-lg text-gray-500 hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors"
            aria-label="Recolher menu"
        >
            <svg class="w-5 h-5 transition-transform duration-300 sidebar-collapsed:rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 19l-7-7 7-7m8 14l-7-7 7-7"/>
            </svg>
        </button>

        {{-- Close button for mobile --}}
        <button 
            type="button"
            data-sidebar-toggle
            class="lg:hidden p-2 rounded-lg text-gray-500 hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors"
            aria-label="Fechar menu"
        >
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
            </svg>
        </button>
    </div>

    {{-- Navigation --}}
    <nav class="flex-1 overflow-y-auto overflow-x-hidden py-4 px-3">
        <ul class="space-y-1">
            {{ $slot }}
        </ul>
    </nav>

    {{-- Footer --}}
    @if($footer)
        <div class="shrink-0 border-t border-gray-200 dark:border-gray-800 p-4">
            {{ $footer }}
        </div>
    @endif
</aside>

{{-- Spacer for content (desktop only) --}}
<div class="hidden lg:block shrink-0 transition-all duration-300 w-64 sidebar-collapsed:w-20" data-sidebar-spacer="{{ $sidebarId }}"></div>

@pushOnce('scripts')
<script>
// Mobile trigger handler
document.querySelectorAll('[data-sidebar-mobile-trigger]').forEach(btn => {
    btn.addEventListener('click', () => {
        const sidebarId = btn.dataset.sidebarMobileTrigger;
        const sidebar = document.getElementById(sidebarId);
        if (sidebar) {
            const instance = SpireUI.get(sidebar);
            instance?.toggleMobile();
        }
    });
});

// Sync spacer with sidebar collapsed state
document.querySelectorAll('[data-v="sidebar"]').forEach(sidebar => {
    const spacer = document.querySelector(`[data-sidebar-spacer="${sidebar.id}"]`);
    if (spacer) {
        const observer = new MutationObserver(() => {
            spacer.classList.toggle('w-20', sidebar.classList.contains('sidebar-collapsed'));
            spacer.classList.toggle('w-64', !sidebar.classList.contains('sidebar-collapsed'));
        });
        observer.observe(sidebar, { attributes: true, attributeFilter: ['class'] });
    }
});
</script>
@endPushOnce

@pushOnce('styles')
<style>
/* Sidebar collapsed state variants */
.sidebar-collapsed .sidebar-logo { display: none; }
.sidebar-collapsed .sidebar-logo-collapsed { display: block !important; }
.sidebar-collapsed .sidebar-header-content { opacity: 0; width: 0; }
.sidebar-collapsed .sidebar-item-text { opacity: 0; width: 0; overflow: hidden; }
.sidebar-collapsed .sidebar-item-arrow { opacity: 0; }
.sidebar-collapsed [data-submenu] { display: none !important; }

/* Tooltip on collapsed items */
.sidebar-collapsed .sidebar-item:hover .sidebar-tooltip {
    display: block;
    opacity: 1;
}
</style>
@endPushOnce
