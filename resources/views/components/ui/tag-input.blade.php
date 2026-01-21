@props([
    'id' => null,
    'name' => null,
    'label' => null,
    'placeholder' => 'Digite e pressione Enter',
    'hint' => null,
    'value' => [], // Array of tags
    'suggestions' => [], // Suggestions for autocomplete
    'max' => null, // Maximum number of tags
    'maxLength' => null, // Maximum length per tag
    'allowDuplicates' => false,
    'disabled' => false,
    'required' => false,
    'error' => null,
    'variant' => 'default', // default, pill, outline
])

@php
    $tagInputId = $id ?? 'taginput-' . uniqid();
    $initialTags = is_array($value) ? $value : explode(',', $value);
    $initialTags = array_filter($initialTags);
    
    $tagClasses = match($variant) {
        'pill' => 'px-3 py-1 bg-blue-500 text-white rounded-full text-sm',
        'outline' => 'px-2 py-0.5 border border-blue-500 text-blue-600 dark:text-blue-400 rounded text-sm',
        default => 'px-2 py-0.5 bg-blue-100 dark:bg-blue-900/50 text-blue-700 dark:text-blue-300 rounded text-sm',
    };
@endphp

<div 
    class="w-full"
    data-v="taginput"
    data-taginput-id="{{ $tagInputId }}"
    data-suggestions='{{ json_encode($suggestions) }}'
    @if($max) data-max="{{ $max }}" @endif
    @if($maxLength) data-max-length="{{ $maxLength }}" @endif
    @if($allowDuplicates) data-allow-duplicates @endif
    data-tag-class="{{ $tagClasses }}"
>
    @if($label)
        <label for="{{ $tagInputId }}" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">
            {{ $label }}
            @if($required)
                <span class="text-red-500">*</span>
            @endif
        </label>
    @endif
    
    <div 
        class="flex flex-wrap gap-2 p-2 border rounded-lg bg-white dark:bg-gray-800 focus-within:ring-2 focus-within:ring-blue-500 focus-within:border-blue-500 transition-all min-h-[46px]
               {{ $error ? 'border-red-500' : 'border-gray-300 dark:border-gray-600' }}
               {{ $disabled ? 'bg-gray-100 dark:bg-gray-700 opacity-60' : '' }}"
        data-taginput-container
    >
        {{-- Tags --}}
        <div class="flex flex-wrap gap-1.5" data-tags-container>
            @foreach($initialTags as $tag)
                <span class="inline-flex items-center gap-1 {{ $tagClasses }}" data-tag="{{ $tag }}">
                    {{ $tag }}
                    @unless($disabled)
                    <button type="button" class="hover:opacity-70" data-remove-tag="{{ $tag }}">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                    @endunless
                </span>
            @endforeach
        </div>
        
        {{-- Input --}}
        <input 
            type="text"
            id="{{ $tagInputId }}"
            placeholder="{{ $placeholder }}"
            @disabled($disabled)
            autocomplete="off"
            class="flex-1 min-w-[150px] outline-none bg-transparent text-gray-900 dark:text-gray-100 placeholder-gray-400 text-sm"
            data-taginput-input
        />
    </div>
    
    {{-- Suggestions Dropdown --}}
    @if(count($suggestions) > 0)
    <div 
        class="relative"
    >
        <div 
            class="absolute z-50 w-full mt-1 bg-white dark:bg-gray-800 rounded-lg shadow-lg border border-gray-200 dark:border-gray-700 max-h-40 overflow-auto hidden"
            data-taginput-suggestions
        >
            @foreach($suggestions as $suggestion)
                <div 
                    class="px-3 py-2 text-sm cursor-pointer hover:bg-gray-100 dark:hover:bg-gray-700 text-gray-700 dark:text-gray-300"
                    data-suggestion="{{ $suggestion }}"
                >
                    {{ $suggestion }}
                </div>
            @endforeach
        </div>
    </div>
    @endif
    
    {{-- Hidden input for form submission --}}
    <input type="hidden" @if($name) name="{{ $name }}" @endif data-taginput-value value="{{ implode(',', $initialTags) }}" />
    
    @if($hint && !$error)
        <p class="mt-1.5 text-sm text-gray-500 dark:text-gray-400">{{ $hint }}</p>
    @endif
    
    @if($error)
        <p class="mt-1.5 text-sm text-red-500 flex items-center gap-1">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            {{ $error }}
        </p>
    @endif
    
    @if($max)
        <p class="mt-1 text-xs text-gray-400" data-taginput-counter>
            <span data-count>{{ count($initialTags) }}</span>/{{ $max }} tags
        </p>
    @endif
</div>

@pushOnce('scripts')
<script>
document.querySelectorAll('[data-v="taginput"]').forEach(container => {
    const input = container.querySelector('[data-taginput-input]');
    const tagsContainer = container.querySelector('[data-tags-container]');
    const suggestionsEl = container.querySelector('[data-taginput-suggestions]');
    const hiddenInput = container.querySelector('[data-taginput-value]');
    const counterEl = container.querySelector('[data-count]');
    
    const suggestions = JSON.parse(container.dataset.suggestions || '[]');
    const max = container.dataset.max ? parseInt(container.dataset.max) : null;
    const maxLength = container.dataset.maxLength ? parseInt(container.dataset.maxLength) : null;
    const allowDuplicates = container.hasAttribute('data-allow-duplicates');
    const tagClass = container.dataset.tagClass;
    
    let tags = hiddenInput.value ? hiddenInput.value.split(',').filter(Boolean) : [];
    
    function updateHiddenInput() {
        hiddenInput.value = tags.join(',');
        if (counterEl) counterEl.textContent = tags.length;
        container.dispatchEvent(new CustomEvent('taginput:change', { detail: { tags } }));
    }
    
    function addTag(value) {
        const tag = value.trim();
        if (!tag) return;
        if (max && tags.length >= max) return;
        if (maxLength && tag.length > maxLength) return;
        if (!allowDuplicates && tags.includes(tag)) return;
        
        tags.push(tag);
        
        const tagEl = document.createElement('span');
        tagEl.className = `inline-flex items-center gap-1 ${tagClass}`;
        tagEl.dataset.tag = tag;
        tagEl.innerHTML = `
            ${tag}
            <button type="button" class="hover:opacity-70" data-remove-tag="${tag}">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        `;
        
        tagEl.querySelector('[data-remove-tag]').addEventListener('click', () => removeTag(tag));
        tagsContainer.appendChild(tagEl);
        
        input.value = '';
        updateHiddenInput();
        hideSuggestions();
    }
    
    function removeTag(value) {
        const idx = tags.indexOf(value);
        if (idx > -1) {
            tags.splice(idx, 1);
            tagsContainer.querySelector(`[data-tag="${value}"]`)?.remove();
            updateHiddenInput();
        }
    }
    
    function showSuggestions(filter = '') {
        if (!suggestionsEl) return;
        
        const filtered = suggestions.filter(s => 
            s.toLowerCase().includes(filter.toLowerCase()) && 
            (allowDuplicates || !tags.includes(s))
        );
        
        if (filtered.length === 0) {
            hideSuggestions();
            return;
        }
        
        suggestionsEl.innerHTML = filtered.map(s => `
            <div class="px-3 py-2 text-sm cursor-pointer hover:bg-gray-100 dark:hover:bg-gray-700 text-gray-700 dark:text-gray-300" data-suggestion="${s}">
                ${s}
            </div>
        `).join('');
        
        suggestionsEl.querySelectorAll('[data-suggestion]').forEach(el => {
            el.addEventListener('click', () => addTag(el.dataset.suggestion));
        });
        
        suggestionsEl.classList.remove('hidden');
    }
    
    function hideSuggestions() {
        suggestionsEl?.classList.add('hidden');
    }
    
    // Attach remove listeners to initial tags
    tagsContainer.querySelectorAll('[data-remove-tag]').forEach(btn => {
        btn.addEventListener('click', () => removeTag(btn.dataset.removeTag));
    });
    
    // Events
    input.addEventListener('keydown', (e) => {
        if (e.key === 'Enter' || e.key === ',') {
            e.preventDefault();
            addTag(input.value);
        } else if (e.key === 'Backspace' && !input.value && tags.length > 0) {
            removeTag(tags[tags.length - 1]);
        } else if (e.key === 'Escape') {
            hideSuggestions();
        }
    });
    
    input.addEventListener('input', () => {
        if (suggestions.length > 0) {
            showSuggestions(input.value);
        }
    });
    
    input.addEventListener('focus', () => {
        if (suggestions.length > 0 && input.value) {
            showSuggestions(input.value);
        }
    });
    
    document.addEventListener('click', (e) => {
        if (!container.contains(e.target)) {
            hideSuggestions();
        }
    });
});
</script>
@endPushOnce
