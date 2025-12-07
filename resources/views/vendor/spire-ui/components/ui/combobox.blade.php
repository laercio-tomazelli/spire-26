@props([
    'id' => null,
    'name' => null,
    'label' => null,
    'placeholder' => 'Buscar...',
    'hint' => null,
    'options' => [], // Array of ['value' => '', 'label' => '', 'description' => '']
    'value' => null,
    'multiple' => false,
    'searchable' => true,
    'clearable' => true,
    'disabled' => false,
    'required' => false,
    'error' => null,
    'minChars' => 1, // Minimum characters to start searching
    'noResults' => 'Nenhum resultado encontrado',
    'loading' => 'Carregando...',
    'remote' => null, // URL for remote search
])

@php
    $comboId = $id ?? 'combo-' . uniqid();
    $optionsJson = json_encode($options);
@endphp

<div 
    class="w-full"
    data-v="combobox"
    data-combobox-id="{{ $comboId }}"
    data-options='{{ $optionsJson }}'
    data-min-chars="{{ $minChars }}"
    data-no-results="{{ $noResults }}"
    data-loading-text="{{ $loading }}"
    @if($remote) data-remote="{{ $remote }}" @endif
    @if($multiple) data-multiple @endif
    @if($value) data-value="{{ is_array($value) ? implode(',', $value) : $value }}" @endif
>
    @if($label)
        <label for="{{ $comboId }}" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">
            {{ $label }}
            @if($required)
                <span class="text-red-500">*</span>
            @endif
        </label>
    @endif
    
    <div class="relative">
        {{-- Selected Tags (for multiple) --}}
        @if($multiple)
        <div class="flex flex-wrap gap-1 mb-1 empty:mb-0" data-selected-tags></div>
        @endif
        
        {{-- Input Container --}}
        <div class="relative">
            <input 
                type="text"
                id="{{ $comboId }}"
                @if($name) name="{{ $name }}" @endif
                placeholder="{{ $placeholder }}"
                @disabled($disabled)
                @readonly(!$searchable)
                autocomplete="off"
                class="w-full px-4 py-2.5 pr-10 border rounded-lg bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 placeholder-gray-400 dark:placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all
                       {{ $error ? 'border-red-500' : 'border-gray-300 dark:border-gray-600' }}
                       {{ $disabled ? 'bg-gray-100 dark:bg-gray-700 cursor-not-allowed opacity-60' : '' }}"
                data-combobox-input
            />
            
            {{-- Icons --}}
            <div class="absolute inset-y-0 right-0 flex items-center pr-2 gap-1">
                {{-- Clear --}}
                @if($clearable)
                <button 
                    type="button"
                    class="p-1 text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 hidden"
                    data-combobox-clear
                >
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
                @endif
                
                {{-- Loading Spinner --}}
                <div class="hidden" data-combobox-loading>
                    <svg class="w-4 h-4 animate-spin text-gray-400" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"/>
                    </svg>
                </div>
                
                {{-- Dropdown Arrow --}}
                <button 
                    type="button"
                    class="p-1 text-gray-400"
                    data-combobox-toggle
                >
                    <svg class="w-4 h-4 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24" data-combobox-arrow>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                    </svg>
                </button>
            </div>
        </div>
        
        {{-- Dropdown --}}
        <div 
            class="absolute z-50 w-full mt-1 bg-white dark:bg-gray-800 rounded-lg shadow-lg border border-gray-200 dark:border-gray-700 max-h-60 overflow-auto hidden"
            data-combobox-dropdown
        >
            <div class="py-1" data-combobox-options>
                {{-- Options will be rendered here --}}
            </div>
        </div>
        
        {{-- Hidden input for form submission --}}
        <input type="hidden" data-combobox-value @if($name) name="{{ $name }}_value" @endif />
    </div>
    
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
</div>

@pushOnce('scripts')
<script>
document.querySelectorAll('[data-v="combobox"]').forEach(container => {
    const input = container.querySelector('[data-combobox-input]');
    const dropdown = container.querySelector('[data-combobox-dropdown]');
    const optionsContainer = container.querySelector('[data-combobox-options]');
    const clearBtn = container.querySelector('[data-combobox-clear]');
    const toggleBtn = container.querySelector('[data-combobox-toggle]');
    const arrow = container.querySelector('[data-combobox-arrow]');
    const loadingEl = container.querySelector('[data-combobox-loading]');
    const hiddenInput = container.querySelector('[data-combobox-value]');
    const tagsContainer = container.querySelector('[data-selected-tags]');
    
    let options = JSON.parse(container.dataset.options || '[]');
    const minChars = parseInt(container.dataset.minChars) || 1;
    const noResultsText = container.dataset.noResults;
    const loadingText = container.dataset.loadingText;
    const remoteUrl = container.dataset.remote;
    const isMultiple = container.hasAttribute('data-multiple');
    
    let selectedValues = [];
    let highlightedIndex = -1;
    let isOpen = false;
    
    // Initialize with value
    const initialValue = container.dataset.value;
    if (initialValue) {
        selectedValues = initialValue.split(',');
        updateDisplay();
    }
    
    function open() {
        isOpen = true;
        dropdown.classList.remove('hidden');
        arrow.style.transform = 'rotate(180deg)';
        renderOptions(options);
    }
    
    function close() {
        isOpen = false;
        dropdown.classList.add('hidden');
        arrow.style.transform = '';
        highlightedIndex = -1;
    }
    
    function renderOptions(opts, loading = false) {
        if (loading) {
            optionsContainer.innerHTML = `<div class="px-4 py-2 text-sm text-gray-500">${loadingText}</div>`;
            return;
        }
        
        if (opts.length === 0) {
            optionsContainer.innerHTML = `<div class="px-4 py-2 text-sm text-gray-500">${noResultsText}</div>`;
            return;
        }
        
        optionsContainer.innerHTML = opts.map((opt, i) => {
            const isSelected = selectedValues.includes(String(opt.value));
            return `
                <div 
                    class="px-4 py-2 cursor-pointer transition-colors ${isSelected ? 'bg-blue-50 dark:bg-blue-900/30' : 'hover:bg-gray-100 dark:hover:bg-gray-700'} ${highlightedIndex === i ? 'bg-gray-100 dark:bg-gray-700' : ''}"
                    data-value="${opt.value}"
                    data-index="${i}"
                >
                    <div class="flex items-center justify-between">
                        <div>
                            <div class="text-sm font-medium text-gray-900 dark:text-white">${opt.label}</div>
                            ${opt.description ? `<div class="text-xs text-gray-500 dark:text-gray-400">${opt.description}</div>` : ''}
                        </div>
                        ${isSelected ? '<svg class="w-4 h-4 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>' : ''}
                    </div>
                </div>
            `;
        }).join('');
        
        // Attach click listeners
        optionsContainer.querySelectorAll('[data-value]').forEach(el => {
            el.addEventListener('click', () => selectOption(el.dataset.value));
        });
    }
    
    function selectOption(value) {
        const opt = options.find(o => String(o.value) === String(value));
        if (!opt) return;
        
        if (isMultiple) {
            const idx = selectedValues.indexOf(String(value));
            if (idx > -1) {
                selectedValues.splice(idx, 1);
            } else {
                selectedValues.push(String(value));
            }
            input.value = '';
        } else {
            selectedValues = [String(value)];
            input.value = opt.label;
            close();
        }
        
        updateDisplay();
        hiddenInput.value = selectedValues.join(',');
        container.dispatchEvent(new CustomEvent('combobox:change', { 
            detail: { values: selectedValues, options: selectedValues.map(v => options.find(o => String(o.value) === v)) }
        }));
    }
    
    function updateDisplay() {
        if (isMultiple && tagsContainer) {
            tagsContainer.innerHTML = selectedValues.map(v => {
                const opt = options.find(o => String(o.value) === v);
                return opt ? `
                    <span class="inline-flex items-center gap-1 px-2 py-0.5 bg-blue-100 dark:bg-blue-900/50 text-blue-700 dark:text-blue-300 text-sm rounded-md">
                        ${opt.label}
                        <button type="button" class="hover:text-blue-900 dark:hover:text-blue-100" data-remove="${v}">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                        </button>
                    </span>
                ` : '';
            }).join('');
            
            tagsContainer.querySelectorAll('[data-remove]').forEach(btn => {
                btn.addEventListener('click', (e) => {
                    e.stopPropagation();
                    const idx = selectedValues.indexOf(btn.dataset.remove);
                    if (idx > -1) selectedValues.splice(idx, 1);
                    updateDisplay();
                    hiddenInput.value = selectedValues.join(',');
                });
            });
        }
        
        if (clearBtn) {
            clearBtn.classList.toggle('hidden', selectedValues.length === 0);
        }
        
        renderOptions(options);
    }
    
    function filterOptions(query) {
        if (!query || query.length < minChars) return options;
        const q = query.toLowerCase();
        return options.filter(opt => 
            opt.label.toLowerCase().includes(q) || 
            (opt.description && opt.description.toLowerCase().includes(q))
        );
    }
    
    async function searchRemote(query) {
        if (!remoteUrl) return;
        loadingEl?.classList.remove('hidden');
        renderOptions([], true);
        
        try {
            const res = await fetch(`${remoteUrl}?q=${encodeURIComponent(query)}`);
            const data = await res.json();
            options = data;
            renderOptions(data);
        } catch (e) {
            renderOptions([]);
        } finally {
            loadingEl?.classList.add('hidden');
        }
    }
    
    // Events
    input.addEventListener('focus', () => {
        if (!isOpen) open();
    });
    
    input.addEventListener('input', () => {
        const query = input.value;
        if (remoteUrl) {
            searchRemote(query);
        } else {
            const filtered = filterOptions(query);
            renderOptions(filtered);
        }
        if (!isOpen) open();
    });
    
    toggleBtn?.addEventListener('click', () => {
        if (isOpen) close(); else open();
    });
    
    clearBtn?.addEventListener('click', () => {
        selectedValues = [];
        input.value = '';
        hiddenInput.value = '';
        updateDisplay();
        container.dispatchEvent(new CustomEvent('combobox:clear'));
    });
    
    // Keyboard navigation
    input.addEventListener('keydown', (e) => {
        const opts = optionsContainer.querySelectorAll('[data-value]');
        
        if (e.key === 'ArrowDown') {
            e.preventDefault();
            highlightedIndex = Math.min(highlightedIndex + 1, opts.length - 1);
            renderOptions(filterOptions(input.value));
        } else if (e.key === 'ArrowUp') {
            e.preventDefault();
            highlightedIndex = Math.max(highlightedIndex - 1, 0);
            renderOptions(filterOptions(input.value));
        } else if (e.key === 'Enter' && highlightedIndex >= 0) {
            e.preventDefault();
            const opt = opts[highlightedIndex];
            if (opt) selectOption(opt.dataset.value);
        } else if (e.key === 'Escape') {
            close();
        }
    });
    
    // Close on outside click
    document.addEventListener('click', (e) => {
        if (!container.contains(e.target)) close();
    });
});
</script>
@endPushOnce
