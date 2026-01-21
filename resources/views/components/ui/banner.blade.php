@props([
    'id' => null,
    'type' => 'info', // info, success, warning, danger, announcement
    'dismissible' => true,
    'fixed' => true, // fixa no topo
    'icon' => true,
    'center' => true, // centralizar texto
    'persist' => false, // lembrar que foi fechado (localStorage)
])

@php
    $bannerId = $id ?? 'banner-' . md5($slot->toHtml());

    $types = [
        'info' => 'bg-blue-600 text-white',
        'success' => 'bg-green-600 text-white',
        'warning' => 'bg-yellow-500 text-white',
        'danger' => 'bg-red-600 text-white',
        'announcement' => 'bg-linear-to-r from-purple-600 to-pink-600 text-white',
    ];

    $icons = [
        'info' =>
            '<svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/></svg>',
        'success' =>
            '<svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>',
        'warning' =>
            '<svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>',
        'danger' =>
            '<svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/></svg>',
        'announcement' =>
            '<svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 3a1 1 0 00-1.447-.894L8.763 6H5a3 3 0 000 6h.28l1.771 5.316A1 1 0 008 18h1a1 1 0 001-1v-4.382l6.553 3.276A1 1 0 0018 15V3z" clip-rule="evenodd"/></svg>',
    ];

    $typeClass = $types[$type] ?? $types['info'];
    $iconSvg = $icons[$type] ?? $icons['info'];
    $positionClass = $fixed ? 'fixed top-0 left-0 right-0 z-[100]' : 'relative';
@endphp

<div id="{{ $bannerId }}" role="alert" {{ $attributes->merge(['class' => "{$positionClass} {$typeClass}"]) }}
    @if ($persist) data-persist="true"
        data-banner-id="{{ $bannerId }}" @endif>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between gap-4 py-3 {{ $center ? 'sm:justify-center' : '' }}">
            <div class="flex items-center gap-3 {{ $center ? 'flex-1 sm:flex-none' : 'flex-1' }}">
                {{-- Icon --}}
                @if ($icon)
                    <span class="shrink-0 opacity-90">
                        {!! $iconSvg !!}
                    </span>
                @endif

                {{-- Content --}}
                <p class="text-sm font-medium {{ $center ? 'sm:text-center' : '' }}">
                    {{ $slot }}
                </p>
            </div>

            {{-- Action Button --}}
            @isset($action)
                <div class="shrink-0">
                    {{ $action }}
                </div>
            @endisset

            {{-- Dismiss Button --}}
            @if ($dismissible)
                <button type="button"
                    class="shrink-0 p-1.5 rounded-lg transition-colors hover:bg-white/20 focus:outline-none focus:ring-2 focus:ring-white/50"
                    onclick="const banner = this.closest('[role=alert]'); if (banner.dataset.persist) { localStorage.setItem('banner-' + banner.id + '-dismissed', 'true'); } banner.remove();"
                    aria-label="Fechar">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                            d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                            clip-rule="evenodd" />
                    </svg>
                </button>
            @endif
        </div>
    </div>
</div>

@if ($fixed)
    {{-- Spacer para compensar a altura do banner fixo --}}
    <div class="h-12" id="{{ $bannerId }}-spacer"></div>
    <script>
        (function() {
            const banner = document.getElementById('{{ $bannerId }}');
            const spacer = document.getElementById('{{ $bannerId }}-spacer');
            if (!banner) {
                spacer?.remove();
                return;
            }
            // Verificar se o banner persistido foi dismissado
            if (banner.dataset.persist && localStorage.getItem('banner-{{ $bannerId }}-dismissed')) {
                banner.remove();
                spacer?.remove();
                return;
            }
            // Ajustar spacer quando banner é removido
            const observer = new MutationObserver(() => {
                if (!document.getElementById('{{ $bannerId }}')) {
                    spacer?.remove();
                    observer.disconnect();
                }
            });
            observer.observe(banner.parentNode || document.body, {
                childList: true
            });
        })();
    </script>
@endif
