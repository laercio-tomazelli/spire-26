@props([
    'id' => null,
    'target' => null,
    'text' => null,
    'successMessage' => 'Copiado!',
    'errorMessage' => 'Erro ao copiar'
])

<button 
    {{ $attributes->merge([
        'type' => 'button',
        'class' => 'inline-flex items-center gap-2 px-3 py-2 text-sm font-medium text-gray-700 dark:text-gray-200 bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 rounded-lg transition-colors focus:outline-none focus:ring-2 focus:ring-blue-500'
    ]) }}
    data-v="clipboard"
    @if($id) id="{{ $id }}" @endif
    @if($target) data-clipboard-target="{{ $target }}" @endif
    @if($text) data-clipboard-text="{{ $text }}" @endif
    data-success-message="{{ $successMessage }}"
    data-error-message="{{ $errorMessage }}"
>
    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/>
    </svg>
    @if($slot->isNotEmpty())
        {{ $slot }}
    @else
        Copiar
    @endif
</button>
