<x-layouts.app>
    {{-- Banner de Exemplo --}}
    <x-spire::banner type="warning" id="demo-banner">
        ⚠️ Estamos com instabilidade no sistema de pedidos. Nossa equipe está trabalhando para restabelecer o serviço.
    </x-spire::banner>

    <div class="container mx-auto px-6 py-12">
        <h1 class="text-4xl font-bold mb-2 bg-linear-to-r from-blue-600 to-purple-600 bg-clip-text text-transparent">
            🚀 Spire UI 2025 - Demo
        </h1>
        <p class="text-gray-600 dark:text-gray-400 mb-8">Biblioteca TypeScript leve (~19KB gzip) para substituir
            Alpine.js</p>

        {{-- Tabs de demonstração --}}
        <x-spire::tabs id="demo-tabs">
            <x-slot:tabs>
                <x-spire::tab name="buttons" :active="true">Botões</x-spire::tab>
                <x-spire::tab name="inputs">Inputs</x-spire::tab>
                <x-spire::tab name="toasts">Toasts</x-spire::tab>
                <x-spire::tab name="modal">Modal</x-spire::tab>
                <x-spire::tab name="select">Select</x-spire::tab>
                <x-spire::tab name="multiselect">MultiSelect</x-spire::tab>
                <x-spire::tab name="drawer">Drawer</x-spire::tab>
                <x-spire::tab name="progress">Progress</x-spire::tab>
                <x-spire::tab name="stepper">Stepper</x-spire::tab>
                <x-spire::tab name="forms">Forms</x-spire::tab>
                <x-spire::tab name="pickers">Pickers</x-spire::tab>
                <x-spire::tab name="interface">Interface</x-spire::tab>
                <x-spire::tab name="utilities">Utilities</x-spire::tab>
                <x-spire::tab name="sidebar">Sidebar</x-spire::tab>
                <x-spire::tab name="extras">Extras</x-spire::tab>
            </x-slot:tabs>

            {{-- Panel: Buttons --}}
            <x-spire::tab-panel name="buttons" :active="true">
                <div class="grid gap-8 md:grid-cols-2">
                    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl p-8">
                        <h2 class="text-xl font-bold mb-6">Botões com Loading</h2>
                        <div class="space-y-4">
                            <x-spire::button id="save">Salvar Dados</x-spire::button>
                            <x-spire::button id="send" class="from-green-500 to-teal-500">Enviar Email</x-spire::button>
                            <x-spire::button id="delete" class="from-red-500 to-red-600">Excluir</x-spire::button>
                        </div>
                    </div>
                    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl p-8">
                        <h2 class="text-xl font-bold mb-6">Eventos Customizados</h2>
                        <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">
                            Abra o console para ver os eventos sendo disparados:
                        </p>
                        <ul class="text-sm text-gray-500 dark:text-gray-400 space-y-1 font-mono">
                            <li>• button:loading</li>
                            <li>• button:success</li>
                            <li>• button:error</li>
                            <li>• button:reset</li>
                        </ul>
                    </div>
                </div>
            </x-spire::tab-panel>

            {{-- Panel: Inputs --}}
            <x-spire::tab-panel name="inputs">
                <div class="grid gap-8 lg:grid-cols-2">
                    {{-- Basic Inputs --}}
                    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl p-8">
                        <h2 class="text-xl font-bold mb-6">📝 Inputs Básicos</h2>
                        <div class="space-y-6">
                            {{-- With Label and Icon --}}
                            <x-spire::input label="Name" placeholder="Your name" hint="Your full name"
                                icon='<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>' />

                            {{-- Right Icon --}}
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">Right
                                    icon</label>
                                <x-spire::input placeholder="CA, Street 1"
                                    icon-right='<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>' />
                            </div>

                            {{-- Clearable --}}
                            <div>
                                <label
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">Clearable</label>
                                <x-spire::input placeholder="Clearable field" :clearable="true" value="Type something..." />
                            </div>

                            {{-- Prefix & Suffix --}}
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">Prefix
                                    & Suffix</label>
                                <x-spire::input prefix="www" suffix=".com" placeholder="" />
                            </div>

                            {{-- Inline Label --}}
                            <x-spire::input label="Hey, inline..." :inline="true" placeholder="" />
                        </div>
                    </div>

                    {{-- States --}}
                    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl p-8">
                        <h2 class="text-xl font-bold mb-6">🔒 Estados</h2>
                        <div class="space-y-6">
                            {{-- Disabled --}}
                            <x-spire::input label="Disabled" placeholder="It is disabled" :disabled="true" />

                            {{-- Read Only --}}
                            <x-spire::input label="Read only" value="Read only" :readonly="true" />

                            {{-- Error --}}
                            <x-spire::input label="With error" placeholder="Invalid input" error="This field is required" />

                            {{-- Required --}}
                            <x-spire::input label="Required field" placeholder="This is required" :required="true" />
                        </div>
                    </div>

                    {{-- Password --}}
                    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl p-8">
                        <h2 class="text-xl font-bold mb-6">🔐 Password</h2>
                        <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">
                            All above attributes will work with the password component.
                        </p>
                        <div class="space-y-6">
                            {{-- Toggle Left --}}
                            <x-spire::input label="Toggle" :password="true" password-toggle="left" :clearable="true"
                                value="secret123" hint="It toggles visibility" />

                            {{-- Toggle Right --}}
                            <x-spire::input label="Right toggle" :password="true" password-toggle="right"
                                value="secret123" />

                            {{-- Custom Icon --}}
                            <x-spire::input label="Custom icons" :password="true" password-toggle="left" value="secret123"
                                icon='<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>' />

                            {{-- Without Toggle (inline label) --}}
                            <x-spire::input label="Without toggle" :inline="true" type="password" value="secret123" />
                        </div>
                    </div>

                    {{-- Money --}}
                    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl p-8">
                        <h2 class="text-xl font-bold mb-6">💰 Money</h2>
                        <div class="space-y-6">
                            {{-- Default USD --}}
                            <x-spire::input label="Default money" :money="true" currency="USD" locale="en-US"
                                prefix="USD" value="123456.78" />

                            {{-- Custom BRL --}}
                            <x-spire::input label="Custom money" :money="true" currency="BRL" locale="pt-BR"
                                prefix="R$" value="123456.78" />
                        </div>
                    </div>

                    {{-- Help/Popover --}}
                    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl p-8">
                        <h2 class="text-xl font-bold mb-6">❓ Popover / Help</h2>
                        <div class="space-y-6">
                            {{-- Question Icon --}}
                            <x-spire::input label="Name" help="This is a helpful tooltip that appears on hover"
                                help-icon="question" placeholder="" />

                            {{-- Info Icon --}}
                            <x-spire::input label="Name" help="Additional information about this field"
                                help-icon="info" placeholder="" />
                        </div>
                    </div>

                    {{-- Slots --}}
                    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl p-8">
                        <h2 class="text-xl font-bold mb-6">🧩 Slots</h2>
                        <div class="space-y-6">
                            {{-- Prepend a select --}}
                            <div>
                                <label
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">Prepend a
                                    select</label>
                                <x-spire::input placeholder="">
                                    <x-slot:prepend>
                                        <div class="flex items-center gap-1 text-sm">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                            </svg>
                                            <select class="bg-transparent border-none text-sm focus:ring-0 pr-6 -mr-2">
                                                <option>Vernic</option>
                                                <option>Admin</option>
                                                <option>User</option>
                                            </select>
                                        </div>
                                    </x-slot:prepend>
                                </x-spire::input>
                            </div>

                            {{-- Append a button --}}
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">Append
                                    a button</label>
                                <x-spire::input placeholder="">
                                    <x-slot:append>
                                        <button
                                            class="px-4 py-2.5 bg-indigo-500 text-white font-medium rounded-r-lg hover:bg-indigo-600 transition-colors">
                                            I am a button
                                        </button>
                                    </x-slot:append>
                                </x-spire::input>
                            </div>
                        </div>
                    </div>

                    {{-- Sizes --}}
                    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl p-8">
                        <h2 class="text-xl font-bold mb-6">📐 Sizes</h2>
                        <div class="space-y-6">
                            <x-spire::input label="Small" size="sm" placeholder="Small input" />
                            <x-spire::input label="Medium (default)" size="md" placeholder="Medium input" />
                            <x-spire::input label="Large" size="lg" placeholder="Large input" />
                        </div>
                    </div>
                </div>
            </x-spire::tab-panel>

            {{-- Panel: Toasts --}}
            <x-spire::tab-panel name="toasts">
                <div class="grid gap-8 md:grid-cols-2">
                    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl p-8">
                        <h2 class="text-xl font-bold mb-6">Tipos de Toast</h2>
                        <div class="grid grid-cols-2 gap-4">
                            <x-spire::button id="toast-success" class="from-green-500 to-green-600">Success</x-spire::button>
                            <x-spire::button id="toast-error" class="from-red-500 to-red-600">Error</x-spire::button>
                            <x-spire::button id="toast-info" class="from-blue-500 to-blue-600">Info</x-spire::button>
                            <x-spire::button id="toast-warning"
                                class="from-yellow-500 to-orange-500">Warning</x-spire::button>
                        </div>
                    </div>
                    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl p-8">
                        <h2 class="text-xl font-bold mb-6">Sistema de Fila (máx 3)</h2>
                        <div class="space-y-4">
                            <x-spire::button id="toast-queue" class="from-purple-500 to-pink-500">Disparar 5
                                Toasts</x-spire::button>
                            <x-spire::button id="toast-clear" class="from-gray-500 to-gray-600">Limpar Todos</x-spire::button>
                        </div>
                    </div>
                </div>
            </x-spire::tab-panel>

            {{-- Panel: Modal & Confirm --}}
            <x-spire::tab-panel name="modal">
                <div class="grid gap-8 md:grid-cols-2">
                    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl p-8">
                        <h2 class="text-xl font-bold mb-6">Modal Completo</h2>
                        <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">
                            Modal com trap de foco, ESC para fechar, e scroll lock.
                        </p>
                        <x-spire::button id="open-modal">Abrir Modal</x-spire::button>
                    </div>
                    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl p-8">
                        <h2 class="text-xl font-bold mb-6">Confirm Dialog</h2>
                        <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">
                            Retorna uma Promise com true/false.
                        </p>
                        <x-spire::button id="open-confirm" class="from-red-500 to-red-600">Excluir Item</x-spire::button>
                    </div>
                </div>
            </x-spire::tab-panel>

            {{-- Panel: Select --}}
            <x-spire::tab-panel name="select">
                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl p-8 max-w-lg">
                    <h2 class="text-xl font-bold mb-6">Custom Select</h2>
                    <div class="space-y-6">
                        <div>
                            <label class="block text-sm font-medium mb-2">País</label>
                            <x-spire::select id="country-select" name="country" placeholder="Selecione um país"
                                :options="[
                                    ['value' => 'br', 'label' => '🇧🇷 Brasil'],
                                    ['value' => 'us', 'label' => '🇺🇸 Estados Unidos'],
                                    ['value' => 'pt', 'label' => '🇵🇹 Portugal'],
                                    ['value' => 'es', 'label' => '🇪🇸 Espanha'],
                                    ['value' => 'fr', 'label' => '🇫🇷 França'],
                                ]" />
                        </div>
                        <div>
                            <label class="block text-sm font-medium mb-2">Status</label>
                            <x-spire::select id="status-select" name="status" value="active" :options="[
                                ['value' => 'active', 'label' => '✅ Ativo'],
                                ['value' => 'pending', 'label' => '⏳ Pendente'],
                                ['value' => 'inactive', 'label' => '❌ Inativo'],
                            ]" />
                        </div>
                        <x-spire::button id="get-select-values">Ver Valores Selecionados</x-spire::button>
                    </div>
                </div>
            </x-spire::tab-panel>

            {{-- Panel: MultiSelect --}}
            <x-spire::tab-panel name="multiselect">
                <div class="grid gap-8 md:grid-cols-2">
                    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl p-8">
                        <h2 class="text-xl font-bold mb-6">MultiSelect Básico</h2>
                        <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">
                            Selecione múltiplos itens com busca, selecionar todos e limpar.
                        </p>
                        <div class="space-y-4">
                            <label class="block text-sm font-medium">Tecnologias que você usa:</label>
                            <x-spire::multiselect id="tech-multiselect" name="technologies"
                                placeholder="Selecione as tecnologias..." :options="[
                                    ['value' => 'laravel', 'label' => 'Laravel'],
                                    ['value' => 'vue', 'label' => 'Vue.js'],
                                    ['value' => 'react', 'label' => 'React'],
                                    ['value' => 'angular', 'label' => 'Angular'],
                                    ['value' => 'svelte', 'label' => 'Svelte'],
                                    ['value' => 'tailwind', 'label' => 'Tailwind CSS'],
                                    ['value' => 'typescript', 'label' => 'TypeScript'],
                                    ['value' => 'node', 'label' => 'Node.js'],
                                    ['value' => 'python', 'label' => 'Python'],
                                    ['value' => 'php', 'label' => 'PHP'],
                                ]" :value="['laravel', 'tailwind']" />
                            <x-spire::button id="get-multiselect-values">Ver Tecnologias Selecionadas</x-spire::button>
                        </div>
                    </div>

                    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl p-8">
                        <h2 class="text-xl font-bold mb-6">MultiSelect com Limite</h2>
                        <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">
                            Máximo de 3 itens selecionáveis.
                        </p>
                        <div class="space-y-4">
                            <label class="block text-sm font-medium">Cores favoritas (máx 3):</label>
                            <x-spire::multiselect id="colors-multiselect" name="colors"
                                placeholder="Escolha até 3 cores..." :maxItems="3" :options="[
                                    ['value' => 'red', 'label' => '🔴 Vermelho'],
                                    ['value' => 'blue', 'label' => '🔵 Azul'],
                                    ['value' => 'green', 'label' => '🟢 Verde'],
                                    ['value' => 'yellow', 'label' => '🟡 Amarelo'],
                                    ['value' => 'purple', 'label' => '🟣 Roxo'],
                                    ['value' => 'orange', 'label' => '🟠 Laranja'],
                                    ['value' => 'pink', 'label' => '💗 Rosa'],
                                    ['value' => 'black', 'label' => '⚫ Preto'],
                                ]" />
                        </div>
                    </div>

                    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl p-8">
                        <h2 class="text-xl font-bold mb-6">Controle Programático</h2>
                        <div class="space-y-4">
                            <label class="block text-sm font-medium">Frutas:</label>
                            <x-spire::multiselect id="fruits-multiselect" name="fruits"
                                placeholder="Selecione frutas..." :showSelectAll="true" :showClear="true"
                                :options="[
                                    ['value' => 'apple', 'label' => '🍎 Maçã'],
                                    ['value' => 'banana', 'label' => '🍌 Banana'],
                                    ['value' => 'orange', 'label' => '🍊 Laranja'],
                                    ['value' => 'grape', 'label' => '🍇 Uva'],
                                    ['value' => 'strawberry', 'label' => '🍓 Morango'],
                                    ['value' => 'watermelon', 'label' => '🍉 Melancia'],
                                ]" />
                            <div class="flex flex-wrap gap-2">
                                <x-spire::button id="add-fruit" class="from-green-500 to-green-600">+ Adicionar
                                    Maçã</x-spire::button>
                                <x-spire::button id="remove-fruit" class="from-red-500 to-red-600">- Remover
                                    Banana</x-spire::button>
                                <x-spire::button id="clear-fruits" class="from-gray-500 to-gray-600">Limpar
                                    Tudo</x-spire::button>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl p-8">
                        <h2 class="text-xl font-bold mb-6">Eventos do MultiSelect</h2>
                        <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">
                            Abra o console para ver os eventos:
                        </p>
                        <ul class="text-sm text-gray-500 dark:text-gray-400 space-y-1 font-mono">
                            <li>• multiselect:change</li>
                            <li>• multiselect:added</li>
                            <li>• multiselect:removed</li>
                            <li>• multiselect:cleared</li>
                            <li>• multiselect:select-all</li>
                            <li>• multiselect:max-reached</li>
                            <li>• multiselect:opened</li>
                            <li>• multiselect:closed</li>
                        </ul>
                    </div>
                </div>
            </x-spire::tab-panel>

            {{-- Panel: Drawer --}}
            <x-spire::tab-panel name="drawer">
                <div class="grid gap-8 md:grid-cols-2">
                    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl p-8">
                        <h2 class="text-xl font-bold mb-6">Drawer / Sidebar</h2>
                        <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">
                            Painel deslizante lateral para menus mobile e sidebars.
                        </p>
                        <div class="flex flex-wrap gap-4">
                            <x-spire::button id="open-drawer-left">← Abrir Esquerda</x-spire::button>
                            <x-spire::button id="open-drawer-right" class="from-purple-500 to-pink-500">Abrir Direita
                                →</x-spire::button>
                        </div>
                    </div>
                    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl p-8">
                        <h2 class="text-xl font-bold mb-6">Características</h2>
                        <ul class="text-sm text-gray-500 dark:text-gray-400 space-y-2">
                            <li>✅ Posições: left, right, top, bottom</li>
                            <li>✅ Overlay com blur</li>
                            <li>✅ ESC para fechar</li>
                            <li>✅ Animação de slide</li>
                            <li>✅ Scroll lock no body</li>
                            <li>✅ Focus trap</li>
                        </ul>
                    </div>
                </div>
            </x-spire::tab-panel>

            {{-- Panel: Progress --}}
            <x-spire::tab-panel name="progress">
                <div class="grid gap-8 md:grid-cols-2">
                    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl p-8">
                        <h2 class="text-xl font-bold mb-6">Barras de Progresso</h2>
                        <div class="space-y-6">
                            <x-spire::progress id="progress-1" :value="25" color="blue">
                                <x-slot:label>Download</x-slot:label>
                            </x-spire::progress>

                            <x-spire::progress id="progress-2" :value="60" color="green">
                                <x-slot:label>Upload</x-slot:label>
                            </x-spire::progress>

                            <x-spire::progress id="progress-3" :value="85" color="gradient">
                                <x-slot:label>Processando</x-slot:label>
                            </x-spire::progress>
                        </div>

                        <div class="mt-6 flex flex-wrap gap-2">
                            <x-spire::button id="progress-increment"
                                class="from-green-500 to-green-600">+10%</x-spire::button>
                            <x-spire::button id="progress-decrement" class="from-red-500 to-red-600">-10%</x-spire::button>
                            <x-spire::button id="progress-complete"
                                class="from-purple-500 to-pink-500">Completar</x-spire::button>
                            <x-spire::button id="progress-reset" class="from-gray-500 to-gray-600">Reset</x-spire::button>
                        </div>
                    </div>

                    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl p-8">
                        <h2 class="text-xl font-bold mb-6">Skeleton Loaders</h2>
                        <div class="space-y-6">
                            <div>
                                <p class="text-sm font-medium mb-2">Card Skeleton:</p>
                                <x-spire::skeleton type="card" :count="1" />
                            </div>
                            <div>
                                <p class="text-sm font-medium mb-2">Lines Skeleton:</p>
                                <x-spire::skeleton type="line" :count="3" />
                            </div>
                            <div>
                                <p class="text-sm font-medium mb-2">Avatar Skeleton:</p>
                                <x-spire::skeleton type="avatar" :count="4" />
                            </div>
                        </div>
                    </div>
                </div>
            </x-spire::tab-panel>

            {{-- Panel: Stepper --}}
            <x-spire::tab-panel name="stepper">
                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl p-8">
                    <h2 class="text-xl font-bold mb-8">Formulário Multi-Etapas</h2>

                    <x-spire::stepper id="checkout-stepper" :steps="[
                        ['title' => 'Dados Pessoais', 'description' => 'Nome e email'],
                        ['title' => 'Endereço', 'description' => 'Onde entregar'],
                        ['title' => 'Pagamento', 'description' => 'Como pagar'],
                        ['title' => 'Confirmação', 'description' => 'Revise o pedido'],
                    ]" :initialStep="1" :showNavigation="true">
                        <x-spire::step-panel :step="1">
                            <div class="max-w-md space-y-4">
                                <h3 class="text-lg font-semibold mb-4">Seus Dados</h3>
                                <x-spire::input id="step-name" placeholder="Nome completo" />
                                <x-spire::input id="step-email" type="email" placeholder="seu@email.com" />
                            </div>
                        </x-spire::step-panel>

                        <x-spire::step-panel :step="2">
                            <div class="max-w-md space-y-4">
                                <h3 class="text-lg font-semibold mb-4">Endereço de Entrega</h3>
                                <x-spire::input id="step-cep" placeholder="CEP" />
                                <x-spire::input id="step-street" placeholder="Rua e número" />
                                <x-spire::input id="step-city" placeholder="Cidade" />
                            </div>
                        </x-spire::step-panel>

                        <x-spire::step-panel :step="3">
                            <div class="max-w-md space-y-4">
                                <h3 class="text-lg font-semibold mb-4">Forma de Pagamento</h3>
                                <x-spire::select id="step-payment" placeholder="Selecione..." :options="[
                                    ['value' => 'credit', 'label' => '💳 Cartão de Crédito'],
                                    ['value' => 'pix', 'label' => '📱 PIX'],
                                    ['value' => 'boleto', 'label' => '📄 Boleto'],
                                ]" />
                            </div>
                        </x-spire::step-panel>

                        <x-spire::step-panel :step="4">
                            <div class="max-w-md">
                                <h3 class="text-lg font-semibold mb-4">Confirme seu Pedido</h3>
                                <div
                                    class="p-4 bg-green-50 dark:bg-green-900/30 rounded-xl text-green-700 dark:text-green-300">
                                    <p>✅ Tudo pronto! Clique em "Próximo" para finalizar.</p>
                                </div>
                            </div>
                        </x-spire::step-panel>
                    </x-spire::stepper>
                </div>
            </x-spire::tab-panel>

            {{-- Panel: Forms --}}
            <x-spire::tab-panel name="forms">
                <div class="grid gap-8 md:grid-cols-2">
                    {{-- Form Validator --}}
                    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl p-8">
                        <h2 class="text-xl font-bold mb-6">📝 Form Validator</h2>
                        <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">
                            Validação declarativa com 16 regras.
                        </p>
                        <form data-v="form" id="demo-form" class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium mb-1">Nome*</label>
                                <input type="text" name="name" data-validate="required|min:3"
                                    class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg dark:bg-gray-900"
                                    placeholder="Mínimo 3 caracteres">
                            </div>
                            <div>
                                <label class="block text-sm font-medium mb-1">Email*</label>
                                <input type="email" name="email" data-validate="required|email"
                                    class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg dark:bg-gray-900"
                                    placeholder="seu@email.com">
                            </div>
                            <div>
                                <label class="block text-sm font-medium mb-1">CPF</label>
                                <input type="text" name="cpf" id="cpf-input" data-validate="cpf"
                                    class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg dark:bg-gray-900"
                                    placeholder="000.000.000-00">
                            </div>
                            <div>
                                <label class="block text-sm font-medium mb-1">Idade</label>
                                <input type="number" name="age" data-validate="numeric|minValue:18|maxValue:120"
                                    class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg dark:bg-gray-900"
                                    placeholder="Entre 18 e 120">
                            </div>
                            <x-spire::button type="submit">Validar Formulário</x-spire::button>
                        </form>
                    </div>

                    {{-- File Upload --}}
                    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl p-8">
                        <h2 class="text-xl font-bold mb-6">📤 File Upload</h2>
                        <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">
                            Drag & drop com preview de imagens.
                        </p>
                        <div data-v="upload" id="demo-upload" data-accept="image/*" data-max-size="5242880"
                            data-multiple="true"
                            class="border-2 border-dashed border-gray-300 dark:border-gray-600 rounded-xl p-8 text-center cursor-pointer hover:border-blue-500 transition-colors">
                            <div class="text-gray-500 dark:text-gray-400 pointer-events-none">
                                <svg class="w-12 h-12 mx-auto mb-4" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                                </svg>
                                <p>Arraste arquivos aqui ou clique para selecionar</p>
                                <p class="text-xs mt-2">Imagens até 5MB</p>
                            </div>
                            <div data-preview class="grid grid-cols-3 gap-3 mt-4"></div>
                        </div>
                    </div>

                    {{-- Range Slider --}}
                    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl p-8">
                        <h2 class="text-xl font-bold mb-6">🎚️ Range Slider</h2>
                        <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">
                            Slider customizável com touch support.
                        </p>
                        <div class="space-y-6">
                            <div>
                                <label class="block text-sm font-medium mb-2">Volume: <span
                                        id="volume-value">50</span>%</label>
                                <div data-v="range" id="volume-slider" data-min="0" data-max="100"
                                    data-value="50" data-step="1"></div>
                            </div>
                            <div>
                                <label class="block text-sm font-medium mb-2">Preço: R$ <span
                                        id="price-value">500</span></label>
                                <div data-v="range" id="price-slider" data-min="100" data-max="1000"
                                    data-value="500" data-step="50" data-color="#10b981"></div>
                            </div>
                        </div>
                    </div>

                    {{-- Input Masks --}}
                    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl p-8">
                        <h2 class="text-xl font-bold mb-6">🎭 Input Masks</h2>
                        <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">
                            Máscaras brasileiras prontas.
                        </p>
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium mb-1">Telefone</label>
                                <input type="text" id="mask-phone"
                                    class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg dark:bg-gray-900"
                                    placeholder="(00) 00000-0000">
                            </div>
                            <div>
                                <label class="block text-sm font-medium mb-1">CNPJ</label>
                                <input type="text" id="mask-cnpj"
                                    class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg dark:bg-gray-900"
                                    placeholder="00.000.000/0000-00">
                            </div>
                            <div>
                                <label class="block text-sm font-medium mb-1">CEP</label>
                                <input type="text" id="mask-cep"
                                    class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg dark:bg-gray-900"
                                    placeholder="00000-000">
                            </div>
                            <div>
                                <label class="block text-sm font-medium mb-1">Valor (R$)</label>
                                <input type="text" id="mask-money"
                                    class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg dark:bg-gray-900"
                                    placeholder="R$ 0,00">
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Checkboxes --}}
                <div class="mt-8">
                    <h2 class="text-xl font-bold mb-6">☑️ Checkboxes</h2>
                    <div class="grid gap-8 md:grid-cols-2">
                        {{-- Checkbox Individual --}}
                        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl p-8">
                            <h3 class="text-lg font-semibold mb-4">Checkbox Individual</h3>
                            <div class="space-y-4">
                                <x-spire::checkbox label="Receber newsletter" />
                                <x-spire::checkbox label="Aceito os termos de uso" required />
                                <x-spire::checkbox label="Concordo com a política de privacidade" checked />
                                <x-spire::checkbox label="Opção desabilitada" disabled />
                                <x-spire::checkbox label="Estado indeterminado" indeterminate />
                            </div>
                        </div>

                        {{-- Checkbox Group --}}
                        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl p-8">
                            <h3 class="text-lg font-semibold mb-4">Grupo de Checkboxes</h3>
                            <div class="space-y-4">
                                <x-spire::checkbox-group label="Habilidades Técnicas" name="skills[]">
                                    <x-spire::checkbox value="php" label="PHP" />
                                    <x-spire::checkbox value="javascript" label="JavaScript" checked />
                                    <x-spire::checkbox value="python" label="Python" />
                                    <x-spire::checkbox value="java" label="Java" checked />
                                </x-spire::checkbox-group>

                                <x-spire::checkbox-group
                                    label="Preferências"
                                    name="preferences[]"
                                    :options="['notificacoes' => 'Notificações por email', 'marketing' => 'Materiais de marketing', 'updates' => 'Atualizações do produto']"
                                    :value="['notificacoes']"
                                />
                            </div>
                        </div>

                        {{-- Tamanhos --}}
                        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl p-8">
                            <h3 class="text-lg font-semibold mb-4">Tamanhos</h3>
                            <div class="space-y-3">
                                <x-spire::checkbox size="sm" label="Checkbox pequeno" />
                                <x-spire::checkbox size="md" label="Checkbox médio" />
                                <x-spire::checkbox size="lg" label="Checkbox grande" />
                            </div>
                        </div>

                        {{-- Estados --}}
                        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl p-8">
                            <h3 class="text-lg font-semibold mb-4">Estados</h3>
                            <div class="space-y-3">
                                <x-spire::checkbox label="Estado normal" />
                                <x-spire::checkbox label="Estado válido" variant="success" checked />
                                <x-spire::checkbox label="Estado com erro" variant="error" />
                                <x-spire::checkbox label="Estado desabilitado" disabled checked />
                            </div>
                        </div>
                    </div>
                </div>
            </x-spire::tab-panel>

            {{-- Panel: Pickers --}}
            <x-spire::tab-panel name="pickers">
                <div class="grid gap-8 md:grid-cols-2">
                    {{-- Date Picker --}}
                    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl p-8">
                        <h2 class="text-xl font-bold mb-6">📅 Date Picker</h2>
                        <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">
                            Seletor de data com formato brasileiro.
                        </p>
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium mb-1">Data de Nascimento</label>
                                <div data-v="datepicker" id="birth-date" data-format="dd/mm/yyyy"
                                    data-placeholder="DD/MM/AAAA"></div>
                            </div>
                            <div>
                                <label class="block text-sm font-medium mb-1">Data do Evento</label>
                                <div data-v="datepicker" id="event-date" data-format="dd/mm/yyyy"
                                    data-min-date="01/01/2025" data-max-date="31/12/2025"
                                    data-placeholder="Selecione uma data de 2025"></div>
                            </div>
                        </div>
                    </div>

                    {{-- Date Range Picker --}}
                    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl p-8">
                        <h2 class="text-xl font-bold mb-6">📆 Date Range Picker</h2>
                        <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">
                            Seletor de período com presets e dois calendários.
                        </p>
                        <div class="space-y-4">
                            <x-spire::date-range-picker label="Período de Reserva" start-placeholder="Check-in"
                                end-placeholder="Check-out" hint="Selecione as datas de entrada e saída" />

                            <x-spire::date-range-picker label="Período do Relatório" start-value="01/12/2025"
                                end-value="31/12/2025" />
                        </div>
                    </div>

                    {{-- Color Picker --}}
                    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl p-8">
                        <h2 class="text-xl font-bold mb-6">🎨 Color Picker</h2>
                        <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">
                            Seletor de cores com presets.
                        </p>
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium mb-1">Cor Principal</label>
                                <div data-v="colorpicker" id="main-color" data-value="#3b82f6"></div>
                            </div>
                            <div>
                                <label class="block text-sm font-medium mb-1">Cor de Fundo</label>
                                <div data-v="colorpicker" id="bg-color" data-value="#f3f4f6"
                                    data-presets='["#ffffff", "#f3f4f6", "#e5e7eb", "#1f2937", "#111827"]'></div>
                            </div>
                            <div id="color-preview"
                                class="h-20 rounded-xl border-2 border-gray-200 dark:border-gray-700 transition-colors"
                                style="background: #3b82f6"></div>
                        </div>
                    </div>

                    {{-- Combobox/Autocomplete --}}
                    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl p-8">
                        <h2 class="text-xl font-bold mb-6">🔍 Combobox / Autocomplete</h2>
                        <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">
                            Busca e seleção com autocomplete.
                        </p>
                        <div class="space-y-4">
                            <x-spire::combobox label="Selecione um país" placeholder="Buscar país..." :options="[
                                ['value' => 'br', 'label' => 'Brasil', 'description' => 'América do Sul'],
                                ['value' => 'us', 'label' => 'Estados Unidos', 'description' => 'América do Norte'],
                                ['value' => 'uk', 'label' => 'Reino Unido', 'description' => 'Europa'],
                                ['value' => 'jp', 'label' => 'Japão', 'description' => 'Ásia'],
                                ['value' => 'de', 'label' => 'Alemanha', 'description' => 'Europa'],
                                ['value' => 'fr', 'label' => 'França', 'description' => 'Europa'],
                                ['value' => 'pt', 'label' => 'Portugal', 'description' => 'Europa'],
                            ]"
                                hint="Digite para filtrar" />

                            <x-spire::combobox label="Múltipla seleção" placeholder="Selecione tecnologias..."
                                :multiple="true" :options="[
                                    ['value' => 'php', 'label' => 'PHP'],
                                    ['value' => 'js', 'label' => 'JavaScript'],
                                    ['value' => 'ts', 'label' => 'TypeScript'],
                                    ['value' => 'py', 'label' => 'Python'],
                                    ['value' => 'go', 'label' => 'Go'],
                                    ['value' => 'rust', 'label' => 'Rust'],
                                ]" />
                        </div>
                    </div>

                    {{-- Tag Input --}}
                    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl p-8">
                        <h2 class="text-xl font-bold mb-6">🏷️ Tag Input</h2>
                        <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">
                            Input para múltiplas tags.
                        </p>
                        <div class="space-y-4">
                            <x-spire::tag-input label="Tags" placeholder="Digite e pressione Enter" :value="['Laravel', 'Vue.js', 'Tailwind']"
                                hint="Pressione Enter ou vírgula para adicionar" />

                            <x-spire::tag-input label="Com sugestões" :suggestions="['React', 'Vue', 'Angular', 'Svelte', 'Next.js', 'Nuxt', 'Remix']"
                                placeholder="Digite para ver sugestões..." />

                            <x-spire::tag-input label="Máximo 5 tags (estilo pill)" variant="pill" :max="5"
                                :value="['Tag 1', 'Tag 2']" />
                        </div>
                    </div>

                    {{-- Mini Charts --}}
                    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl p-8">
                        <h2 class="text-xl font-bold mb-6">📊 Mini Charts</h2>
                        <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">
                            Gráficos inline em SVG puro.
                        </p>
                        <div class="space-y-6">
                            {{-- Line --}}
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-sm font-medium">Vendas</p>
                                    <p class="text-2xl font-bold">R$ 12.450</p>
                                </div>
                                <x-spire::mini-chart :data="[10, 25, 15, 30, 22, 40, 35, 50, 45, 60]" color="blue" :show-dots="true" />
                            </div>

                            {{-- Area --}}
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-sm font-medium">Visitas</p>
                                    <p class="text-2xl font-bold">8.234</p>
                                </div>
                                <x-spire::mini-chart type="area" :data="[20, 35, 25, 45, 30, 55, 40, 60, 50, 70]" color="green" />
                            </div>

                            {{-- Bar --}}
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-sm font-medium">Conversões</p>
                                    <p class="text-2xl font-bold">4.5%</p>
                                </div>
                                <x-spire::mini-chart type="bar" :data="[30, 45, 25, 60, 40, 55, 70]" color="purple"
                                    :height="50" />
                            </div>

                            {{-- Sparkline with labels --}}
                            <div>
                                <p class="text-sm font-medium mb-2">Últimos 7 dias</p>
                                <x-spire::mini-chart :data="[100, 120, 90, 150, 130, 170, 160]" :labels="['Seg', 'Ter', 'Qua', 'Qui', 'Sex', 'Sáb', 'Dom']" color="orange"
                                    :show-dots="true" :show-labels="true" :width="280" :height="80" />
                            </div>
                        </div>
                    </div>

                    {{-- Command Palette --}}
                    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl p-8 md:col-span-2">
                        <h2 class="text-xl font-bold mb-6">⌘ Command Palette</h2>
                        <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">
                            Busca global estilo ⌘K / Ctrl+K. Pressione <kbd
                                class="px-2 py-1 bg-gray-200 dark:bg-gray-700 rounded text-xs">Ctrl+K</kbd> para abrir.
                        </p>
                        <div class="flex gap-4">
                            <x-spire::button id="open-command" class="from-purple-500 to-pink-500">Abrir Command
                                Palette</x-spire::button>
                        </div>
                        <div class="mt-4 p-4 bg-gray-50 dark:bg-gray-900 rounded-xl">
                            <p class="text-sm font-medium mb-2">Comandos disponíveis:</p>
                            <ul class="text-xs text-gray-500 dark:text-gray-400 space-y-1">
                                <li>• <kbd class="px-1 bg-gray-200 dark:bg-gray-700 rounded">Alternar Tema</kbd> - Muda
                                    entre claro/escuro</li>
                                <li>• <kbd class="px-1 bg-gray-200 dark:bg-gray-700 rounded">Novo Projeto</kbd> - Cria
                                    um novo projeto</li>
                                <li>• <kbd class="px-1 bg-gray-200 dark:bg-gray-700 rounded">Configurações</kbd> - Abre
                                    configurações</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </x-spire::tab-panel>

            {{-- Panel: Interface --}}
            <x-spire::tab-panel name="interface">
                <div class="grid gap-8 md:grid-cols-2 lg:grid-cols-3">
                    {{-- Dropdown --}}
                    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl p-8">
                        <h2 class="text-xl font-bold mb-6">📋 Dropdown</h2>
                        <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">
                            Menus dropdown com posicionamento automático.
                        </p>
                        <div class="space-y-4">
                            <x-spire::dropdown trigger="Ações">
                                <x-spire::dropdown-item>Editar</x-spire::dropdown-item>
                                <x-spire::dropdown-item>Duplicar</x-spire::dropdown-item>
                                <x-spire::dropdown-divider />
                                <x-spire::dropdown-item :danger="true">Excluir</x-spire::dropdown-item>
                            </x-spire::dropdown>

                            <x-spire::dropdown trigger="Conta" position="bottom-end" width="w-56">
                                <x-spire::dropdown-divider label="Logado como" />
                                <div class="px-4 py-2 text-sm text-gray-900 dark:text-gray-100">João Silva</div>
                                <x-spire::dropdown-divider />
                                <x-spire::dropdown-item :active="true">Dashboard</x-spire::dropdown-item>
                                <x-spire::dropdown-item>Perfil</x-spire::dropdown-item>
                                <x-spire::dropdown-item>Configurações</x-spire::dropdown-item>
                                <x-spire::dropdown-divider />
                                <x-spire::dropdown-item :danger="true">Sair</x-spire::dropdown-item>
                            </x-spire::dropdown>
                        </div>
                    </div>

                    {{-- Tooltip --}}
                    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl p-8">
                        <h2 class="text-xl font-bold mb-6">💡 Tooltip</h2>
                        <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">
                            Dicas contextuais ao passar o mouse.
                        </p>
                        <div class="flex flex-wrap gap-4 justify-center">
                            <x-spire::tooltip content="Topo" position="top">
                                <x-spire::button variant="outline" size="sm">Topo</x-spire::button>
                            </x-spire::tooltip>
                            <x-spire::tooltip content="Baixo" position="bottom">
                                <x-spire::button variant="outline" size="sm">Baixo</x-spire::button>
                            </x-spire::tooltip>
                            <x-spire::tooltip content="Esquerda" position="left">
                                <x-spire::button variant="outline" size="sm">Esquerda</x-spire::button>
                            </x-spire::tooltip>
                            <x-spire::tooltip content="Direita" position="right">
                                <x-spire::button variant="outline" size="sm">Direita</x-spire::button>
                            </x-spire::tooltip>
                        </div>
                        <div class="mt-4 text-center">
                            <x-spire::tooltip content="Tooltip com delay personalizado" :delay="500">
                                <x-spire::button variant="ghost" size="sm">Com delay</x-spire::button>
                            </x-spire::tooltip>
                        </div>
                    </div>

                    {{-- Badge --}}
                    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl p-8">
                        <h2 class="text-xl font-bold mb-6">🏷️ Badge</h2>
                        <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">
                            Indicadores visuais para status e contadores.
                        </p>
                        <div class="space-y-4">
                            <div class="flex flex-wrap gap-2">
                                <x-spire::badge>Default</x-spire::badge>
                                <x-spire::badge variant="primary">Primary</x-spire::badge>
                                <x-spire::badge variant="success">Success</x-spire::badge>
                                <x-spire::badge variant="warning">Warning</x-spire::badge>
                                <x-spire::badge variant="danger">Danger</x-spire::badge>
                                <x-spire::badge variant="info">Info</x-spire::badge>
                            </div>
                            <div class="flex flex-wrap gap-2">
                                <x-spire::badge variant="success" :dot="true">Online</x-spire::badge>
                                <x-spire::badge variant="danger" :dot="true">Offline</x-spire::badge>
                                <x-spire::badge variant="warning" :dot="true">Aguardando</x-spire::badge>
                            </div>
                            <div class="flex flex-wrap gap-2">
                                <x-spire::badge variant="primary" size="sm">Small</x-spire::badge>
                                <x-spire::badge variant="primary" size="md">Medium</x-spire::badge>
                                <x-spire::badge variant="primary" size="lg">Large</x-spire::badge>
                            </div>
                        </div>
                    </div>

                    {{-- Skeleton --}}
                    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl p-8">
                        <h2 class="text-xl font-bold mb-6">💀 Skeleton</h2>
                        <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">
                            Placeholders animados para estados de carregamento.
                        </p>
                        <div class="space-y-4">
                            <div>
                                <label class="text-xs text-gray-500 dark:text-gray-400 mb-2 block">Card</label>
                                <x-spire::skeleton type="card" :count="1" />
                            </div>
                            <div>
                                <label class="text-xs text-gray-500 dark:text-gray-400 mb-2 block">Linhas</label>
                                <x-spire::skeleton type="line" :count="3" />
                            </div>
                            <div>
                                <label class="text-xs text-gray-500 dark:text-gray-400 mb-2 block">Avatares</label>
                                <x-spire::skeleton type="avatar" :count="4" />
                            </div>
                        </div>
                    </div>

                    {{-- Icon --}}
                    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl p-8">
                        <h2 class="text-xl font-bold mb-6">🎨 Icon</h2>
                        <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">
                            Ícones Heroicon com fácil integração.
                        </p>
                        <div class="space-y-4">
                            <div class="grid grid-cols-4 gap-3">
                                <x-spire::icon name="menu" class="text-gray-600 dark:text-gray-400" />
                                <x-spire::icon name="x" class="text-gray-600 dark:text-gray-400" />
                                <x-spire::icon name="search" class="text-gray-600 dark:text-gray-400" />
                                <x-spire::icon name="filter" class="text-gray-600 dark:text-gray-400" />
                                <x-spire::icon name="settings" class="text-gray-600 dark:text-gray-400" />
                                <x-spire::icon name="chevron-down" class="text-gray-600 dark:text-gray-400" />
                                <x-spire::icon name="chevron-up" class="text-gray-600 dark:text-gray-400" />
                                <x-spire::icon name="user" class="text-gray-600 dark:text-gray-400" />
                            </div>
                            <div class="flex gap-2">
                                <x-spire::icon name="heart" variant="solid" class="text-red-500" />
                                <x-spire::icon name="star" variant="solid" class="text-yellow-500" />
                                <x-spire::icon name="check-circle" variant="solid" class="text-green-500" />
                            </div>
                        </div>
                    </div>

                    {{-- Form Group --}}
                    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl p-8">
                        <h2 class="text-xl font-bold mb-6">📋 Form Group</h2>
                        <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">
                            Agrupamento estruturado para campos de formulário.
                        </p>
                        <div class="space-y-6">
                            <x-spire::form-group label="Nome Completo" hint="Seu nome completo">
                                <x-spire::input placeholder="Digite seu nome" />
                            </x-spire::form-group>

                            <x-spire::form-group label="Email" error="Email inválido" :required="true">
                                <x-spire::input type="email" placeholder="seu@email.com" />
                            </x-spire::form-group>

                            <x-spire::form-group label="Mensagem" help="Digite sua mensagem">
                                <x-spire::textarea placeholder="Sua mensagem aqui..." rows="3" />
                            </x-spire::form-group>
                        </div>
                    </div>
                </div>
            </x-spire::tab-panel>

            {{-- Panel: Utilities --}}
            <x-spire::tab-panel name="utilities">
                <div class="grid gap-8 md:grid-cols-2 lg:grid-cols-3">
                    {{-- Debounce/Throttle --}}
                    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl p-8">
                        <h2 class="text-xl font-bold mb-6">⏱️ Debounce & Throttle</h2>
                        <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">
                            Controle de frequência de execução.
                        </p>
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium mb-1">Debounce (300ms)</label>
                                <input type="text" id="debounce-input"
                                    class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg dark:bg-gray-900"
                                    placeholder="Digite algo...">
                                <p class="text-xs text-gray-500 mt-1">Resultado: <span id="debounce-result">-</span>
                                </p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium mb-1">Throttle (1s)</label>
                                <x-spire::button id="throttle-btn">Clique rápido!</x-spire::button>
                                <p class="text-xs text-gray-500 mt-1">Cliques processados: <span
                                        id="throttle-count">0</span></p>
                            </div>
                        </div>
                    </div>

                    {{-- Event Bus --}}
                    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl p-8">
                        <h2 class="text-xl font-bold mb-6">📡 Event Bus</h2>
                        <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">
                            Comunicação global entre componentes.
                        </p>
                        <div class="space-y-4">
                            <x-spire::button id="emit-event" class="from-green-500 to-green-600">Emitir
                                Evento</x-spire::button>
                            <div id="event-log"
                                class="p-3 bg-gray-50 dark:bg-gray-900 rounded-lg text-xs font-mono h-24 overflow-y-auto">
                                <p class="text-gray-400">Aguardando eventos...</p>
                            </div>
                        </div>
                    </div>

                    {{-- HTTP Client --}}
                    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl p-8">
                        <h2 class="text-xl font-bold mb-6">🌐 HTTP Client</h2>
                        <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">
                            Requisições com CSRF automático.
                        </p>
                        <div class="space-y-4">
                            <x-spire::button id="http-get" class="from-blue-500 to-blue-600">GET Request</x-spire::button>
                            <div id="http-result"
                                class="p-3 bg-gray-50 dark:bg-gray-900 rounded-lg text-xs font-mono h-24 overflow-y-auto">
                                <p class="text-gray-400">Resultado aparecerá aqui...</p>
                            </div>
                        </div>
                    </div>

                    {{-- Currency --}}
                    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl p-8">
                        <h2 class="text-xl font-bold mb-6">💰 Currency</h2>
                        <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">
                            Formatação de moedas.
                        </p>
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium mb-1">Valor</label>
                                <input type="number" id="currency-input"
                                    class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg dark:bg-gray-900"
                                    value="1234.56">
                            </div>
                            <div class="grid grid-cols-2 gap-2 text-sm">
                                <div class="p-2 bg-gray-50 dark:bg-gray-900 rounded">
                                    <span class="text-gray-500">BRL:</span>
                                    <span id="currency-brl" class="font-mono">R$ 1.234,56</span>
                                </div>
                                <div class="p-2 bg-gray-50 dark:bg-gray-900 rounded">
                                    <span class="text-gray-500">USD:</span>
                                    <span id="currency-usd" class="font-mono">$1,234.56</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Performance --}}
                    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl p-8">
                        <h2 class="text-xl font-bold mb-6">⚡ Performance</h2>
                        <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">
                            Monitoramento de performance.
                        </p>
                        <div class="space-y-4">
                            <x-spire::button id="perf-test">Testar Performance</x-spire::button>
                            <div id="perf-result"
                                class="p-3 bg-gray-50 dark:bg-gray-900 rounded-lg text-xs font-mono">
                                <p class="text-gray-400">Clique para medir...</p>
                            </div>
                        </div>
                    </div>

                    {{-- Accessibility --}}
                    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl p-8">
                        <h2 class="text-xl font-bold mb-6">♿ Acessibilidade</h2>
                        <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">
                            Ferramentas A11y.
                        </p>
                        <div class="space-y-4">
                            <x-spire::button id="a11y-announce" class="from-purple-500 to-pink-500">Anunciar para
                                SR</x-spire::button>
                            <p class="text-xs text-gray-500">
                                Usuários de leitores de tela ouvirão o anúncio.
                            </p>
                        </div>
                    </div>
                </div>
            </x-spire::tab-panel>

            {{-- Panel: Sidebar --}}
            <x-spire::tab-panel name="sidebar">
                {{-- Full Width Demo Container --}}
                <div class="space-y-6">
                    {{-- Header --}}
                    <div class="flex items-center justify-between">
                        <div>
                            <h2 class="text-xl font-bold">📁 Sidebar + Navbar Integradas</h2>
                            <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">
                                Navbar com 4 áreas e logo que aparece quando sidebar está recolhida. Clique no botão ‹‹
                                para recolher.
                            </p>
                        </div>
                    </div>

                    {{-- Full Width Demo --}}
                    <div
                        class="relative border border-gray-200 dark:border-gray-700 rounded-xl h-[600px] bg-gray-50 dark:bg-gray-900">
                        {{-- Estilos para sidebar-collapsed --}}
                        <style>
                            #demo-sidebar.sidebar-collapsed {
                                width: 4rem !important;
                            }

                            #demo-sidebar.sidebar-collapsed .sidebar-item-text {
                                display: none;
                            }

                            #demo-sidebar.sidebar-collapsed .sidebar-item-arrow {
                                display: none;
                            }

                            #demo-sidebar.sidebar-collapsed [data-submenu] {
                                display: none !important;
                            }

                            #demo-sidebar.sidebar-collapsed .toggle-icon {
                                transform: rotate(180deg);
                            }

                            #demo-sidebar.sidebar-collapsed .sidebar-logo-full {
                                display: none;
                            }

                            #demo-sidebar.sidebar-collapsed .sidebar-header {
                                justify-content: center;
                            }

                            #demo-sidebar.sidebar-collapsed .sidebar-toggle-btn {
                                margin: 0;
                            }

                            #demo-sidebar.sidebar-collapsed .sidebar-item {
                                justify-content: center;
                            }

                            /* Tooltips inline - escondidos sempre (usamos JS) */
                            .sidebar-tooltip {
                                display: none;
                            }

                            /* Navbar ajustes quando sidebar collapsed */
                            #demo-sidebar.sidebar-collapsed~#demo-navbar {
                                left: 4rem !important;
                            }

                            #demo-sidebar.sidebar-collapsed~#demo-navbar [data-navbar-logo] {
                                opacity: 1 !important;
                                width: auto !important;
                                overflow: visible !important;
                            }

                            #demo-sidebar.sidebar-collapsed~#demo-content {
                                left: 4rem !important;
                            }
                        </style>

                        {{-- Sidebar --}}
                        <aside id="demo-sidebar" data-v="sidebar" data-persist="demo"
                            class="sidebar absolute inset-y-0 left-0 flex flex-col z-20
                                       bg-white dark:bg-gray-800 border-r border-gray-200 dark:border-gray-700
                                       transition-all duration-300 ease-in-out w-56">
                            {{-- Sidebar Header with Logo --}}
                            <div
                                class="sidebar-header flex items-center justify-between h-12 px-2 border-b border-gray-200 dark:border-gray-700 shrink-0">
                                <div
                                    class="flex items-center gap-2 overflow-hidden sidebar-logo-full transition-all duration-300">
                                    <div
                                        class="w-8 h-8 bg-linear-to-br from-blue-500 to-purple-600 rounded-lg flex items-center justify-center text-white font-bold text-xs shrink-0">
                                        S
                                    </div>
                                    <span
                                        class="sidebar-item-text font-semibold text-gray-800 dark:text-gray-200 whitespace-nowrap text-sm">
                                        SPIRE
                                    </span>
                                </div>
                                <button type="button" data-sidebar-toggle
                                    class="sidebar-toggle-btn w-8 h-8 flex items-center justify-center rounded-lg text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
                                    <svg class="toggle-icon w-4 h-4 transition-transform duration-300" fill="none"
                                        stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M11 19l-7-7 7-7m8 14l-7-7 7-7" />
                                    </svg>
                                </button>
                            </div>

                            {{-- Navigation --}}
                            <nav class="flex-1 overflow-y-auto py-2 px-2">
                                <ul class="space-y-1">
                                    <li data-sidebar-item="dashboard" class="relative group">
                                        <a href="#"
                                            class="sidebar-item flex items-center w-full px-2 py-1.5 rounded-lg bg-blue-50 dark:bg-blue-900/30 text-blue-700 dark:text-blue-400 transition-all duration-200">
                                            <span class="shrink-0 w-5 h-5">
                                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                                                </svg>
                                            </span>
                                            <span
                                                class="sidebar-item-text ml-2 text-sm whitespace-nowrap transition-all duration-300">Dashboard</span>
                                        </a>
                                        <div class="sidebar-tooltip">Dashboard</div>
                                    </li>
                                    <li data-sidebar-item="products" class="relative group">
                                        <button type="button" data-submenu-trigger
                                            class="sidebar-item flex items-center w-full px-2 py-1.5 rounded-lg text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 transition-all duration-200">
                                            <span class="shrink-0 w-5 h-5 text-gray-500">
                                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                                                </svg>
                                            </span>
                                            <span
                                                class="sidebar-item-text ml-2 text-sm whitespace-nowrap transition-all duration-300">Produtos</span>
                                            <svg data-arrow
                                                class="sidebar-item-arrow ml-auto w-3 h-3 text-gray-400 transition-transform duration-300"
                                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M9 5l7 7-7 7" />
                                            </svg>
                                        </button>
                                        <div class="sidebar-tooltip">Produtos</div>
                                        <ul data-submenu
                                            class="hidden mt-1 ml-4 pl-2 border-l-2 border-gray-200 dark:border-gray-600 space-y-0.5">
                                            <li><a href="#"
                                                    class="block px-2 py-1 text-xs text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-200 rounded hover:bg-gray-100 dark:hover:bg-gray-700">Todos</a>
                                            </li>
                                            <li><a href="#"
                                                    class="block px-2 py-1 text-xs text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-200 rounded hover:bg-gray-100 dark:hover:bg-gray-700">Categorias</a>
                                            </li>
                                        </ul>
                                    </li>
                                    <li data-sidebar-item="users" class="relative group">
                                        <a href="#"
                                            class="sidebar-item flex items-center w-full px-2 py-1.5 rounded-lg text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 transition-all duration-200">
                                            <span class="shrink-0 w-5 h-5 text-gray-500">
                                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                                                </svg>
                                            </span>
                                            <span
                                                class="sidebar-item-text ml-2 text-sm whitespace-nowrap transition-all duration-300">Usuários</span>
                                        </a>
                                        <div class="sidebar-tooltip">Usuários</div>
                                    </li>
                                </ul>
                            </nav>
                        </aside>

                        {{-- Tooltip container (fora da sidebar para não ser afetado por overflow) --}}
                        <div id="sidebar-tooltip-container"
                            class="hidden fixed z-[9999] px-3 py-1.5 text-xs font-medium text-gray-700 dark:text-gray-200 bg-gray-100 dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-md shadow-sm whitespace-nowrap pointer-events-none">
                        </div>

                        <script>
                            (function() {
                                const sidebar = document.getElementById('demo-sidebar');
                                const tooltip = document.getElementById('sidebar-tooltip-container');
                                const items = sidebar.querySelectorAll('[data-sidebar-item]');

                                items.forEach(item => {
                                    const text = item.querySelector('.sidebar-tooltip')?.textContent ||
                                        item.querySelector('.sidebar-item-text')?.textContent || '';

                                    item.addEventListener('mouseenter', (e) => {
                                        if (!sidebar.classList.contains('sidebar-collapsed')) return;

                                        const rect = item.getBoundingClientRect();
                                        tooltip.textContent = text;
                                        tooltip.classList.remove('hidden');
                                        tooltip.style.left = (rect.right + 12) + 'px';
                                        tooltip.style.top = (rect.top + rect.height / 2) + 'px';
                                        tooltip.style.transform = 'translateY(-50%)';
                                    });

                                    item.addEventListener('mouseleave', () => {
                                        tooltip.classList.add('hidden');
                                    });
                                });
                            })();
                        </script>

                        {{-- Navbar --}}
                        <header id="demo-navbar" data-v="navbar" data-sidebar="demo-sidebar"
                            class="absolute top-0 right-0 left-56 h-12 z-10
                                       bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700
                                       transition-all duration-300 ease-in-out">
                            <div class="h-full flex items-center justify-between px-3">
                                {{-- Área 1: Logo (aparece quando sidebar collapsed) --}}
                                <div class="flex items-center">
                                    <div data-navbar-logo
                                        class="flex items-center gap-2 transition-all duration-300 opacity-0 w-0 overflow-hidden">
                                        <div
                                            class="w-7 h-7 bg-linear-to-br from-blue-500 to-purple-600 rounded-lg flex items-center justify-center text-white font-bold text-xs shrink-0">
                                            S
                                        </div>
                                        <span
                                            class="font-semibold text-gray-800 dark:text-gray-200 whitespace-nowrap text-sm">
                                            SPIRE
                                        </span>
                                    </div>
                                </div>

                                {{-- Área 2: Centro (Logo do parceiro) --}}
                                <div class="flex items-center justify-center">
                                    <div class="flex items-center gap-2">
                                        <span class="text-lg font-bold text-gray-700 dark:text-gray-300">G<span
                                                class="text-blue-600">i</span>BR</span>
                                        <span class="text-[10px] text-gray-500 leading-tight">COMPONENTES<br>DA
                                            AMAZÔNIA</span>
                                    </div>
                                </div>

                                {{-- Área 3: SAC --}}
                                <div
                                    class="hidden sm:flex items-center gap-1.5 text-gray-600 dark:text-gray-400 text-xs">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                                    </svg>
                                    <span class="font-medium">SAC 0800 387 8220</span>
                                </div>

                                {{-- Área 4: Ações --}}
                                <div class="flex items-center gap-2">
                                    <button
                                        class="p-1.5 rounded-lg text-gray-500 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                        </svg>
                                    </button>
                                    <button
                                        class="relative p-1.5 rounded-lg text-gray-500 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                                        </svg>
                                        <span
                                            class="absolute -top-0.5 -right-0.5 w-4 h-4 bg-red-500 text-white text-[10px] font-bold rounded-full flex items-center justify-center">6</span>
                                    </button>
                                    <div
                                        class="w-8 h-8 rounded-full bg-blue-100 dark:bg-blue-900 flex items-center justify-center">
                                        <svg class="w-5 h-5 text-blue-600 dark:text-blue-400" fill="none"
                                            stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                        </svg>
                                    </div>
                                </div>
                            </div>
                        </header>

                        {{-- Content area --}}
                        <div class="absolute inset-0 top-12 left-56 transition-all duration-300 p-4 flex items-center justify-center"
                            id="demo-content">
                            <div class="text-center">
                                <div
                                    class="w-12 h-12 mx-auto mb-3 rounded-full bg-gray-200 dark:bg-gray-700 flex items-center justify-center">
                                    <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M4 5a1 1 0 011-1h14a1 1 0 011 1v2a1 1 0 01-1 1H5a1 1 0 01-1-1V5zM4 13a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H5a1 1 0 01-1-1v-6zM16 13a1 1 0 011-1h2a1 1 0 011 1v6a1 1 0 01-1 1h-2a1 1 0 01-1-1v-6z" />
                                    </svg>
                                </div>
                                <p class="text-gray-500 dark:text-gray-400 text-xs">Área de conteúdo</p>
                                <p class="text-gray-400 dark:text-gray-500 text-[10px] mt-1">Toggle para recolher
                                    sidebar</p>
                            </div>
                        </div>
                    </div>

                    {{-- Features, API & Events in horizontal layout --}}
                    <div class="grid gap-6 lg:grid-cols-3">
                        {{-- Features --}}
                        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl p-6">
                            <h2 class="text-lg font-bold mb-4">✨ Recursos</h2>
                            <ul class="space-y-2">
                                <li class="flex items-start gap-2">
                                    <span class="text-green-500 mt-0.5">✓</span>
                                    <span class="text-gray-600 dark:text-gray-400 text-sm"><strong>Navbar
                                            Integrada:</strong> Logo aparece na navbar quando sidebar recolhe</span>
                                </li>
                                <li class="flex items-start gap-2">
                                    <span class="text-green-500 mt-0.5">✓</span>
                                    <span class="text-gray-600 dark:text-gray-400 text-sm"><strong>4 Áreas
                                            Distintas:</strong> Logo, Centro, SAC, Ações</span>
                                </li>
                                <li class="flex items-start gap-2">
                                    <span class="text-green-500 mt-0.5">✓</span>
                                    <span class="text-gray-600 dark:text-gray-400 text-sm"><strong>Modo
                                            Recolhido:</strong> Toggle suave entre largura total e só ícones</span>
                                </li>
                                <li class="flex items-start gap-2">
                                    <span class="text-green-500 mt-0.5">✓</span>
                                    <span class="text-gray-600 dark:text-gray-400 text-sm"><strong>Submenus
                                            Multinível:</strong> Animação deslizante</span>
                                </li>
                                <li class="flex items-start gap-2">
                                    <span class="text-green-500 mt-0.5">✓</span>
                                    <span
                                        class="text-gray-600 dark:text-gray-400 text-sm"><strong>Persistência:</strong>
                                        Estado salvo no localStorage</span>
                                </li>
                            </ul>
                        </div>

                        {{-- API --}}
                        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl p-6">
                            <h2 class="text-lg font-bold mb-4">🔧 API JavaScript</h2>
                            <div class="bg-gray-900 rounded-xl p-4 text-xs font-mono overflow-x-auto">
                                <pre class="text-gray-300"><code><span class="text-purple-400">const</span> <span class="text-blue-300">sidebar</span> = <span class="text-yellow-300">SpireUI</span>.<span class="text-green-300">get</span>(<span class="text-orange-300">el</span>);

<span class="text-gray-500">// Toggle</span>
<span class="text-blue-300">sidebar</span>.<span class="text-green-300">toggle</span>();
<span class="text-blue-300">sidebar</span>.<span class="text-green-300">collapse</span>();
<span class="text-blue-300">sidebar</span>.<span class="text-green-300">expand</span>();

<span class="text-gray-500">// Submenus</span>
<span class="text-blue-300">sidebar</span>.<span class="text-green-300">openSubmenu</span>(<span class="text-orange-300">'id'</span>);
<span class="text-blue-300">sidebar</span>.<span class="text-green-300">closeAllSubmenus</span>();

<span class="text-gray-500">// Estado</span>
<span class="text-blue-300">sidebar</span>.<span class="text-green-300">isCollapsed</span>();</code></pre>
                            </div>
                        </div>

                        {{-- Events --}}
                        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl p-6">
                            <h2 class="text-lg font-bold mb-4">📡 Eventos</h2>
                            <ul class="space-y-2 text-xs font-mono">
                                <li class="flex items-center gap-2">
                                    <span
                                        class="px-2 py-0.5 bg-blue-100 dark:bg-blue-900 text-blue-700 dark:text-blue-300 rounded">sidebar:collapse</span>
                                    <span class="text-gray-500">→ Recolhida</span>
                                </li>
                                <li class="flex items-center gap-2">
                                    <span
                                        class="px-2 py-0.5 bg-blue-100 dark:bg-blue-900 text-blue-700 dark:text-blue-300 rounded">sidebar:expand</span>
                                    <span class="text-gray-500">→ Expandida</span>
                                </li>
                                <li class="flex items-center gap-2">
                                    <span
                                        class="px-2 py-0.5 bg-green-100 dark:bg-green-900 text-green-700 dark:text-green-300 rounded">sidebar:submenu-open</span>
                                    <span class="text-gray-500">→ Submenu aberto</span>
                                </li>
                                <li class="flex items-center gap-2">
                                    <span
                                        class="px-2 py-0.5 bg-orange-100 dark:bg-orange-900 text-orange-700 dark:text-orange-300 rounded">sidebar:submenu-close</span>
                                    <span class="text-gray-500">→ Submenu fechado</span>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </x-spire::tab-panel>

            {{-- Panel: Extras --}}
            <x-spire::tab-panel name="extras">
                <div class="grid gap-8 md:grid-cols-2 lg:grid-cols-3">
                    {{-- Clipboard --}}
                    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl p-8">
                        <h2 class="text-xl font-bold mb-6">📋 Clipboard</h2>
                        <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">
                            Copiar para área de transferência com feedback visual.
                        </p>
                        <div class="space-y-4">
                            <div class="flex gap-2">
                                <input id="copy-input" type="text" value="npm install spire-ui"
                                    class="flex-1 px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-gray-50 dark:bg-gray-900 text-sm font-mono"
                                    readonly>
                                <x-spire::clipboard target="#copy-input" />
                            </div>
                            <x-spire::clipboard text="Texto copiado via data-attribute!">
                                Copiar Texto Fixo
                            </x-spire::clipboard>
                        </div>
                    </div>

                    {{-- Context Menu --}}
                    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl p-8">
                        <h2 class="text-xl font-bold mb-6">📋 Context Menu</h2>
                        <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">
                            Menu de contexto (clique direito) personalizável.
                        </p>
                        <x-spire::context-menu id="demo-context" :items="[
                            ['id' => 'edit', 'label' => 'Editar', 'icon' => '✏️', 'shortcut' => 'Ctrl+E'],
                            ['id' => 'copy', 'label' => 'Copiar', 'icon' => '📋', 'shortcut' => 'Ctrl+C'],
                            ['id' => 'paste', 'label' => 'Colar', 'icon' => '📄', 'shortcut' => 'Ctrl+V'],
                            ['id' => 'divider1', 'divider' => true],
                            ['id' => 'share', 'label' => 'Compartilhar', 'icon' => '🔗'],
                            ['id' => 'download', 'label' => 'Download', 'icon' => '⬇️'],
                            ['id' => 'divider2', 'divider' => true],
                            [
                                'id' => 'delete',
                                'label' => 'Excluir',
                                'icon' => '🗑️',
                                'danger' => true,
                                'shortcut' => 'Del',
                            ],
                        ]"
                            class="p-8 border-2 border-dashed border-gray-300 dark:border-gray-600 rounded-xl text-center cursor-context-menu hover:border-blue-400 hover:bg-blue-50 dark:hover:bg-blue-900/20 transition-colors">
                            <p class="text-gray-500 dark:text-gray-400 font-medium">
                                👆 Clique com botão direito aqui
                            </p>
                            <p class="text-xs text-gray-400 mt-1">ou pressione a tecla de menu</p>
                        </x-spire::context-menu>
                    </div>

                    {{-- Breadcrumbs --}}
                    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl p-8">
                        <h2 class="text-xl font-bold mb-6">🥖 Breadcrumbs</h2>
                        <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">
                            Navegação com trilha de migalhas.
                        </p>
                        <div class="space-y-6">
                            {{-- Chevron (default) --}}
                            <div>
                                <label class="text-xs text-gray-500 dark:text-gray-400 mb-2 block">Chevron
                                    (padrão)</label>
                                <x-spire::breadcrumbs :items="[
                                    ['label' => 'Home', 'url' => '#'],
                                    ['label' => 'Produtos', 'url' => '#'],
                                    ['label' => 'Eletrônicos', 'url' => '#'],
                                    ['label' => 'Smartphones'],
                                ]" />
                            </div>

                            {{-- Slash --}}
                            <div>
                                <label class="text-xs text-gray-500 dark:text-gray-400 mb-2 block">Slash</label>
                                <x-spire::breadcrumbs separator="slash" :items="[
                                    ['label' => 'Dashboard', 'url' => '#'],
                                    ['label' => 'Usuários', 'url' => '#'],
                                    ['label' => 'Editar'],
                                ]" />
                            </div>

                            {{-- Arrow with Home Icon --}}
                            <div>
                                <label class="text-xs text-gray-500 dark:text-gray-400 mb-2 block">Arrow + Ícone
                                    Home</label>
                                <x-spire::breadcrumbs separator="arrow" :showHome="true" homeUrl="#"
                                    :items="[
                                        ['label' => 'Blog', 'url' => '#'],
                                        ['label' => 'Tecnologia', 'url' => '#'],
                                        ['label' => 'Artigo Interessante'],
                                    ]" />
                            </div>

                            {{-- Dot with Icons --}}
                            <div>
                                <label class="text-xs text-gray-500 dark:text-gray-400 mb-2 block">Dot + Ícones
                                    customizados</label>
                                <x-spire::breadcrumbs separator="dot" :items="[
                                    ['label' => 'Início', 'url' => '#', 'icon' => '🏠'],
                                    ['label' => 'Configurações', 'url' => '#', 'icon' => '⚙️'],
                                    ['label' => 'Perfil', 'url' => '#', 'icon' => '👤'],
                                    ['label' => 'Segurança', 'icon' => '🔒'],
                                ]" />
                            </div>

                            {{-- Truncated --}}
                            <div>
                                <label class="text-xs text-gray-500 dark:text-gray-400 mb-2 block">Truncado (caminhos
                                    longos)</label>
                                <x-spire::breadcrumbs :truncate="true" :items="[
                                    ['label' => 'Raiz', 'url' => '#'],
                                    ['label' => 'Pasta 1', 'url' => '#'],
                                    ['label' => 'Pasta 2', 'url' => '#'],
                                    ['label' => 'Pasta 3', 'url' => '#'],
                                    ['label' => 'Pasta 4', 'url' => '#'],
                                    ['label' => 'Arquivo Final'],
                                ]" />
                            </div>
                        </div>
                    </div>

                    {{-- Badge --}}
                    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl p-8">
                        <h2 class="text-xl font-bold mb-6">🏷️ Badge</h2>
                        <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">
                            Etiquetas e indicadores de status.
                        </p>
                        <div class="space-y-4">
                            {{-- Variantes --}}
                            <div>
                                <label class="text-xs text-gray-500 dark:text-gray-400 mb-2 block">Variantes</label>
                                <div class="flex flex-wrap gap-2">
                                    <x-spire::badge>Default</x-spire::badge>
                                    <x-spire::badge variant="primary">Primary</x-spire::badge>
                                    <x-spire::badge variant="success">Success</x-spire::badge>
                                    <x-spire::badge variant="warning">Warning</x-spire::badge>
                                    <x-spire::badge variant="danger">Danger</x-spire::badge>
                                    <x-spire::badge variant="info">Info</x-spire::badge>
                                </div>
                            </div>

                            {{-- Com Dot --}}
                            <div>
                                <label class="text-xs text-gray-500 dark:text-gray-400 mb-2 block">Com Dot</label>
                                <div class="flex flex-wrap gap-2">
                                    <x-spire::badge variant="success" :dot="true">Online</x-spire::badge>
                                    <x-spire::badge variant="danger" :dot="true">Offline</x-spire::badge>
                                    <x-spire::badge variant="warning" :dot="true">Aguardando</x-spire::badge>
                                </div>
                            </div>

                            {{-- Tamanhos --}}
                            <div>
                                <label class="text-xs text-gray-500 dark:text-gray-400 mb-2 block">Tamanhos</label>
                                <div class="flex flex-wrap items-center gap-2">
                                    <x-spire::badge variant="primary" size="sm">Small</x-spire::badge>
                                    <x-spire::badge variant="primary" size="md">Medium</x-spire::badge>
                                    <x-spire::badge variant="primary" size="lg">Large</x-spire::badge>
                                </div>
                            </div>

                            {{-- Removíveis e Pulse --}}
                            <div>
                                <label class="text-xs text-gray-500 dark:text-gray-400 mb-2 block">Removíveis e
                                    Pulse</label>
                                <div class="flex flex-wrap gap-2">
                                    <x-spire::badge variant="info" :removable="true">Removível</x-spire::badge>
                                    <x-spire::badge variant="danger" :pulse="true">Novo!</x-spire::badge>
                                    <x-spire::badge variant="success" :dot="true"
                                        :removable="true">Tag</x-spire::badge>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Card --}}
                    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl p-8">
                        <h2 class="text-xl font-bold mb-6">🃏 Card</h2>
                        <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">
                            Cards com header, badges e actions.
                        </p>
                        <div class="space-y-6">
                            {{-- Card Completo (estilo da imagem) --}}
                            <x-spire::card title="Dados do Reparo" :border="true" shadow="sm">
                                <x-slot:badges>
                                    <x-spire::badge variant="danger">Aguardando Peças</x-spire::badge>
                                </x-slot:badges>
                                <x-slot:actions>
                                    <x-spire::icon-button variant="filled" color="primary" title="Editar">
                                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                        </svg>
                                    </x-spire::icon-button>
                                    <x-spire::icon-button variant="filled" title="Histórico">
                                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                    </x-spire::icon-button>
                                    <x-spire::icon-button variant="filled" title="Salvar">
                                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4" />
                                        </svg>
                                    </x-spire::icon-button>
                                </x-slot:actions>
                                <div class="text-gray-600 dark:text-gray-400">
                                    Conteúdo do card com informações do reparo...
                                </div>
                            </x-spire::card>

                            {{-- Card com Subtítulo --}}
                            <x-spire::card title="Pedido #12345" subtitle="Criado em 03/12/2025" :border="true"
                                shadow="sm">
                                <x-slot:badges>
                                    <x-spire::badge variant="success" :dot="true">Pago</x-spire::badge>
                                    <x-spire::badge variant="info">Enviado</x-spire::badge>
                                </x-slot:badges>
                                <div class="text-gray-600 dark:text-gray-400">
                                    Detalhes do pedido...
                                </div>
                                <x-slot:footer>
                                    <div class="flex justify-between items-center">
                                        <span class="text-sm text-gray-500">Total: R$ 1.299,00</span>
                                        <x-spire::button size="sm">Ver Detalhes</x-spire::button>
                                    </div>
                                </x-slot:footer>
                            </x-spire::card>

                            {{-- Card Simples com Hover --}}
                            <x-spire::card :hover="true" :border="true" shadow="sm">
                                <div class="text-center">
                                    <div class="text-4xl mb-2">📦</div>
                                    <h4 class="font-semibold text-gray-900 dark:text-white">Card com Hover</h4>
                                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Passe o mouse para ver o
                                        efeito</p>
                                </div>
                            </x-spire::card>
                        </div>
                    </div>

                    {{-- Collapse --}}
                    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl p-8">
                        <h2 class="text-xl font-bold mb-6">📁 Collapse</h2>
                        <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">
                            Painéis colapsáveis independentes.
                        </p>
                        <div class="space-y-4">
                            {{-- Default --}}
                            <x-spire::collapse id="collapse-1" title="Informações Gerais" :open="true">
                                <p class="text-gray-600 dark:text-gray-400">
                                    Este é o conteúdo do primeiro collapse. Pode conter qualquer HTML,
                                    incluindo formulários, tabelas, imagens, etc.
                                </p>
                            </x-spire::collapse>

                            {{-- Bordered --}}
                            <x-spire::collapse id="collapse-2" title="Detalhes Técnicos" variant="bordered">
                                <div class="space-y-2 text-gray-600 dark:text-gray-400">
                                    <p><strong>Versão:</strong> 2.0.0</p>
                                    <p><strong>Última atualização:</strong> 03/12/2025</p>
                                    <p><strong>Licença:</strong> MIT</p>
                                </div>
                            </x-spire::collapse>

                            {{-- Ghost --}}
                            <x-spire::collapse id="collapse-3" title="Ver mais opções" variant="ghost"
                                iconPosition="left">
                                <div class="flex gap-2">
                                    <x-spire::badge variant="primary">Opção 1</x-spire::badge>
                                    <x-spire::badge variant="success">Opção 2</x-spire::badge>
                                    <x-spire::badge variant="warning">Opção 3</x-spire::badge>
                                </div>
                            </x-spire::collapse>

                            {{-- Com Trigger customizado --}}
                            <x-spire::collapse id="collapse-4" variant="bordered">
                                <x-slot:trigger>
                                    <div class="flex items-center gap-2">
                                        <span class="text-2xl">🔧</span>
                                        <div>
                                            <div class="font-medium">Configurações Avançadas</div>
                                            <div class="text-sm text-gray-500 dark:text-gray-400">Clique para expandir
                                            </div>
                                        </div>
                                    </div>
                                </x-slot:trigger>
                                <div class="space-y-3">
                                    <label class="flex items-center gap-2">
                                        <input type="checkbox" class="rounded">
                                        <span class="text-gray-600 dark:text-gray-400">Habilitar modo debug</span>
                                    </label>
                                    <label class="flex items-center gap-2">
                                        <input type="checkbox" class="rounded" checked>
                                        <span class="text-gray-600 dark:text-gray-400">Notificações por email</span>
                                    </label>
                                </div>
                            </x-spire::collapse>

                            {{-- Controles via JS --}}
                            <div class="flex gap-2 pt-2">
                                <x-spire::button size="sm" onclick="SpireUI.collapse('collapse-1').open()">
                                    Abrir #1
                                </x-spire::button>
                                <x-spire::button size="sm" class="from-gray-500 to-gray-600"
                                    onclick="SpireUI.collapse('collapse-1').close()">
                                    Fechar #1
                                </x-spire::button>
                                <x-spire::button size="sm" class="from-purple-500 to-pink-500"
                                    onclick="SpireUI.collapse('collapse-2').toggle()">
                                    Toggle #2
                                </x-spire::button>
                            </div>
                        </div>
                    </div>

                    {{-- Avatar --}}
                    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl p-8">
                        <h2 class="text-xl font-bold mb-6">👤 Avatar</h2>
                        <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">
                            Avatares com imagem, iniciais ou placeholder.
                        </p>
                        <div class="space-y-6">
                            {{-- Tamanhos --}}
                            <div>
                                <label class="text-xs text-gray-500 dark:text-gray-400 mb-3 block">Tamanhos</label>
                                <div class="flex items-end gap-3">
                                    <x-spire::avatar size="xs" name="Ana" />
                                    <x-spire::avatar size="sm" name="Bruno Costa" />
                                    <x-spire::avatar size="md" name="Carlos" />
                                    <x-spire::avatar size="lg" name="Diana Lima" />
                                    <x-spire::avatar size="xl" name="Eduardo" />
                                    <x-spire::avatar size="2xl" name="Fernanda Silva" />
                                </div>
                            </div>

                            {{-- Com imagem --}}
                            <div>
                                <label class="text-xs text-gray-500 dark:text-gray-400 mb-3 block">Com Imagem</label>
                                <div class="flex items-center gap-3">
                                    <x-spire::avatar src="https://i.pravatar.cc/150?img=1" alt="User 1"
                                        size="lg" />
                                    <x-spire::avatar src="https://i.pravatar.cc/150?img=2" alt="User 2"
                                        size="lg" />
                                    <x-spire::avatar src="https://i.pravatar.cc/150?img=3" alt="User 3"
                                        size="lg" />
                                </div>
                            </div>

                            {{-- Status --}}
                            <div>
                                <label class="text-xs text-gray-500 dark:text-gray-400 mb-3 block">Com Status</label>
                                <div class="flex items-center gap-4">
                                    <x-spire::avatar name="Online User" status="online" size="lg" />
                                    <x-spire::avatar name="Away User" status="away" size="lg" />
                                    <x-spire::avatar name="Busy User" status="busy" size="lg" />
                                    <x-spire::avatar name="Offline User" status="offline" size="lg" />
                                </div>
                            </div>

                            {{-- Com Ring --}}
                            <div>
                                <label class="text-xs text-gray-500 dark:text-gray-400 mb-3 block">Com Anel</label>
                                <div class="flex items-center gap-4">
                                    <x-spire::avatar name="Primary" :ring="true" ringColor="primary"
                                        size="lg" />
                                    <x-spire::avatar name="Success" :ring="true" ringColor="success"
                                        size="lg" />
                                    <x-spire::avatar name="Warning" :ring="true" ringColor="warning"
                                        size="lg" />
                                    <x-spire::avatar name="Danger" :ring="true" ringColor="danger"
                                        size="lg" />
                                </div>
                            </div>

                            {{-- Placeholders e Rounded --}}
                            <div>
                                <label class="text-xs text-gray-500 dark:text-gray-400 mb-3 block">Placeholder e
                                    Bordas</label>
                                <div class="flex items-center gap-4">
                                    <x-spire::avatar size="lg" />
                                    <x-spire::avatar size="lg" rounded="lg" />
                                    <x-spire::avatar size="lg" rounded="md" />
                                    <x-spire::avatar size="lg" rounded="none" />
                                </div>
                            </div>

                            {{-- Grupo de Avatares --}}
                            <div>
                                <label class="text-xs text-gray-500 dark:text-gray-400 mb-3 block">Grupo de
                                    Avatares</label>
                                <x-spire::avatar-group>
                                    <x-spire::avatar src="https://i.pravatar.cc/150?img=10" :group="true" />
                                    <x-spire::avatar src="https://i.pravatar.cc/150?img=11" :group="true" />
                                    <x-spire::avatar src="https://i.pravatar.cc/150?img=12" :group="true" />
                                    <x-spire::avatar src="https://i.pravatar.cc/150?img=13" :group="true" />
                                    <x-spire::avatar name="+5" :group="true"
                                        class="bg-gray-200 dark:bg-gray-700" />
                                </x-spire::avatar-group>
                            </div>
                        </div>
                    </div>

                    {{-- Alert --}}
                    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl p-8">
                        <h2 class="text-xl font-bold mb-6">🚨 Alert</h2>
                        <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">
                            Alertas e notificações inline.
                        </p>
                        <div class="space-y-4">
                            {{-- Tipos básicos (soft) --}}
                            <x-spire::alert type="info">
                                Esta é uma mensagem informativa para o usuário.
                            </x-spire::alert>

                            <x-spire::alert type="success" title="Sucesso!">
                                Sua operação foi concluída com sucesso.
                            </x-spire::alert>

                            <x-spire::alert type="warning" title="Atenção">
                                Verifique os dados antes de continuar.
                            </x-spire::alert>

                            <x-spire::alert type="danger" :dismissible="true">
                                Ocorreu um erro ao processar sua solicitação.
                            </x-spire::alert>

                            {{-- Variante Solid --}}
                            <div class="pt-2">
                                <label class="text-xs text-gray-500 dark:text-gray-400 mb-2 block">Solid</label>
                                <x-spire::alert type="info" variant="solid" title="Novidade!">
                                    Confira as novas funcionalidades disponíveis.
                                </x-spire::alert>
                            </div>

                            {{-- Variante Outline --}}
                            <div class="pt-2">
                                <label class="text-xs text-gray-500 dark:text-gray-400 mb-2 block">Outline</label>
                                <x-spire::alert type="success" variant="outline">
                                    Dados salvos com sucesso!
                                </x-spire::alert>
                            </div>

                            {{-- Variante Minimal --}}
                            <div class="pt-2">
                                <label class="text-xs text-gray-500 dark:text-gray-400 mb-2 block">Minimal</label>
                                <x-spire::alert type="warning" variant="minimal">
                                    Sua sessão expira em 5 minutos.
                                </x-spire::alert>
                            </div>

                            {{-- Com Actions --}}
                            <div class="pt-2">
                                <label class="text-xs text-gray-500 dark:text-gray-400 mb-2 block">Com Ações</label>
                                <x-spire::alert type="info" title="Atualização Disponível">
                                    Uma nova versão está disponível para download.
                                    <x-slot:actions>
                                        <x-spire::button size="sm">Atualizar Agora</x-spire::button>
                                        <button class="text-sm font-medium hover:underline">Depois</button>
                                    </x-slot:actions>
                                </x-spire::alert>
                            </div>
                        </div>
                    </div>

                    {{-- Banner --}}
                    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl p-8">
                        <h2 class="text-xl font-bold mb-6">📢 Banner</h2>
                        <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">
                            Alertas de topo de página para anúncios importantes.
                        </p>
                        <div class="space-y-4">
                            {{-- Info --}}
                            <x-spire::banner type="info" :fixed="false" id="banner-info">
                                📌 Este é um banner informativo para anúncios gerais.
                            </x-spire::banner>

                            {{-- Success --}}
                            <x-spire::banner type="success" :fixed="false" id="banner-success">
                                ✅ Sistema atualizado com sucesso! Confira as novidades.
                            </x-spire::banner>

                            {{-- Warning --}}
                            <x-spire::banner type="warning" :fixed="false" id="banner-warning">
                                ⚠️ Manutenção programada para hoje às 23h.
                            </x-spire::banner>

                            {{-- Danger --}}
                            <x-spire::banner type="danger" :fixed="false" id="banner-danger">
                                🚨 Sistema de pagamentos temporariamente indisponível.
                            </x-spire::banner>

                            {{-- Announcement com Action --}}
                            <x-spire::banner type="announcement" :fixed="false" id="banner-promo">
                                🎉 Black Friday! 50% de desconto em todos os planos.
                                <x-slot:action>
                                    <a href="#"
                                        class="inline-flex items-center gap-1 px-3 py-1 bg-white/20 hover:bg-white/30 rounded-lg text-sm font-medium transition-colors">
                                        Ver Ofertas
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 5l7 7-7 7" />
                                        </svg>
                                    </a>
                                </x-slot:action>
                            </x-spire::banner>

                            {{-- Nota sobre o banner fixo --}}
                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-4">
                                💡 O banner amarelo no topo da página é um exemplo de banner fixo
                                (<code>:fixed="true"</code>).
                            </p>
                        </div>
                    </div>

                    {{-- Header --}}
                    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl p-8">
                        <h2 class="text-xl font-bold mb-6">📋 Header</h2>
                        <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">
                            Cabeçalho de página ou seção com ações.
                        </p>
                        <div class="space-y-6 -mx-8">
                            {{-- Header simples (como na imagem) --}}
                            <x-spire::header title="Ordem de Serviço">
                                <div class="flex items-center gap-3">
                                    {{-- Search --}}
                                    <div class="relative">
                                        <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400"
                                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                        </svg>
                                        <input type="text" placeholder="Localizar..."
                                            class="pl-9 pr-8 py-2 w-48 border border-gray-300 dark:border-gray-600 rounded-lg text-sm bg-white dark:bg-gray-700 focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                        <button
                                            class="absolute right-2 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                            </svg>
                                        </button>
                                    </div>
                                    {{-- Filter Button --}}
                                    <button
                                        class="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                                        </svg>
                                        Filtros
                                        <x-spire::badge variant="primary" size="sm"
                                            class="bg-white/20 text-white">0</x-spire::badge>
                                    </button>
                                    {{-- Export Button --}}
                                    <x-spire::icon-button variant="outline" title="Exportar CSV">
                                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                        </svg>
                                    </x-spire::icon-button>
                                </div>
                            </x-spire::header>

                            {{-- Header com subtítulo e ícone --}}
                            <x-spire::header title="Dashboard" subtitle="Visão geral do sistema" icon="📊">
                                <x-spire::button size="sm">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                                    </svg>
                                    Atualizar
                                </x-spire::button>
                            </x-spire::header>

                            {{-- Header com badges --}}
                            <x-spire::header title="Pedidos" icon="🛒">
                                <div class="flex items-center gap-2">
                                    <x-spire::badge variant="warning" :dot="true">5 Pendentes</x-spire::badge>
                                    <x-spire::badge variant="success" :dot="true">12 Concluídos</x-spire::badge>
                                </div>
                            </x-spire::header>

                            {{-- Header sem borda --}}
                            <x-spire::header title="Configurações" subtitle="Ajuste as preferências do sistema"
                                :border="false">
                                <x-spire::icon-button variant="ghost" title="Ajuda">
                                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                </x-spire::icon-button>
                            </x-spire::header>
                        </div>
                    </div>

                    {{-- Pin --}}
                    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl p-8">
                        <h2 class="text-xl font-bold mb-6">📍 Pin</h2>
                        <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">
                            Indicadores e marcadores de notificação.
                        </p>
                        <div class="space-y-6">
                            {{-- Cores --}}
                            <div>
                                <label class="text-xs text-gray-500 dark:text-gray-400 mb-3 block">Cores</label>
                                <div class="flex items-center gap-4">
                                    <x-spire::pin color="primary">1</x-spire::pin>
                                    <x-spire::pin color="success">2</x-spire::pin>
                                    <x-spire::pin color="warning">3</x-spire::pin>
                                    <x-spire::pin color="danger">4</x-spire::pin>
                                    <x-spire::pin color="info">5</x-spire::pin>
                                    <x-spire::pin color="gray">6</x-spire::pin>
                                </div>
                            </div>

                            {{-- Tamanhos --}}
                            <div>
                                <label class="text-xs text-gray-500 dark:text-gray-400 mb-3 block">Tamanhos</label>
                                <div class="flex items-center gap-4">
                                    <x-spire::pin size="xs">1</x-spire::pin>
                                    <x-spire::pin size="sm">2</x-spire::pin>
                                    <x-spire::pin size="md">3</x-spire::pin>
                                    <x-spire::pin size="lg">4</x-spire::pin>
                                    <x-spire::pin size="xl">5</x-spire::pin>
                                </div>
                            </div>

                            {{-- Dots --}}
                            <div>
                                <label class="text-xs text-gray-500 dark:text-gray-400 mb-3 block">Dots
                                    (pontos)</label>
                                <div class="flex items-center gap-4">
                                    <x-spire::pin :dot="true" color="success" size="sm" />
                                    <x-spire::pin :dot="true" color="warning" size="md" />
                                    <x-spire::pin :dot="true" color="danger" size="lg" />
                                </div>
                            </div>

                            {{-- Contadores --}}
                            <div>
                                <label class="text-xs text-gray-500 dark:text-gray-400 mb-3 block">Contadores</label>
                                <div class="flex items-center gap-4">
                                    <x-spire::pin :count="3" color="danger" />
                                    <x-spire::pin :count="12" color="primary" />
                                    <x-spire::pin :count="99" color="success" />
                                    <x-spire::pin :count="150" color="warning" />
                                </div>
                            </div>

                            {{-- Animações --}}
                            <div>
                                <label class="text-xs text-gray-500 dark:text-gray-400 mb-3 block">Animações</label>
                                <div class="flex items-center gap-6">
                                    <x-spire::pin :pulse="true" color="danger">!</x-spire::pin>
                                    <x-spire::pin :ping="true" color="success" :dot="true"
                                        size="md" />
                                    <x-spire::pin :ping="true" color="primary">3</x-spire::pin>
                                </div>
                            </div>

                            {{-- Em elementos --}}
                            <div>
                                <label class="text-xs text-gray-500 dark:text-gray-400 mb-3 block">Posicionado em
                                    elementos</label>
                                <div class="flex items-center gap-6">
                                    <div class="relative">
                                        <x-spire::icon-button variant="outline">
                                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    stroke-width="2"
                                                    d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                                            </svg>
                                        </x-spire::icon-button>
                                        <x-spire::pin position="top-right" color="danger" :count="5"
                                            size="sm" />
                                    </div>
                                    <div class="relative">
                                        <x-spire::avatar name="João" size="lg" />
                                        <x-spire::pin position="bottom-right" color="success" :dot="true"
                                            size="md" />
                                    </div>
                                    <div class="relative">
                                        <x-spire::icon-button variant="filled">
                                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    stroke-width="2"
                                                    d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                            </svg>
                                        </x-spire::icon-button>
                                        <x-spire::pin position="top-right" color="danger" :ping="true"
                                            :count="12" size="sm" />
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Rating --}}
                    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl p-8">
                        <h2 class="text-xl font-bold mb-6">⭐ Rating</h2>
                        <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">
                            Sistema de avaliação com estrelas.
                        </p>
                        <div class="space-y-6">
                            {{-- Básico interativo --}}
                            <div>
                                <label class="text-xs text-gray-500 dark:text-gray-400 mb-3 block">Interativo (clique
                                    para avaliar)</label>
                                <x-spire::rating id="rating-demo" :value="3" :showValue="true"
                                    data-v="rating" />
                            </div>

                            {{-- Readonly --}}
                            <div>
                                <label class="text-xs text-gray-500 dark:text-gray-400 mb-3 block">Somente
                                    leitura</label>
                                <x-spire::rating :value="4" :readonly="true" :showValue="true"
                                    showCount="(128 avaliações)" />
                            </div>

                            {{-- Meio estrela --}}
                            <div>
                                <label class="text-xs text-gray-500 dark:text-gray-400 mb-3 block">Meio
                                    estrela</label>
                                <x-spire::rating id="rating-half" :value="3.5" :half="true"
                                    :showValue="true" data-v="rating" />
                            </div>

                            {{-- Tamanhos --}}
                            <div>
                                <label class="text-xs text-gray-500 dark:text-gray-400 mb-3 block">Tamanhos</label>
                                <div class="flex flex-col gap-3">
                                    <x-spire::rating :value="4" :readonly="true" size="sm" />
                                    <x-spire::rating :value="4" :readonly="true" size="md" />
                                    <x-spire::rating :value="4" :readonly="true" size="lg" />
                                    <x-spire::rating :value="4" :readonly="true" size="xl" />
                                </div>
                            </div>

                            {{-- Cores --}}
                            <div>
                                <label class="text-xs text-gray-500 dark:text-gray-400 mb-3 block">Cores</label>
                                <div class="flex flex-col gap-3">
                                    <x-spire::rating :value="4" :readonly="true" color="yellow" />
                                    <x-spire::rating :value="4" :readonly="true" color="red" />
                                    <x-spire::rating :value="4" :readonly="true" color="blue" />
                                    <x-spire::rating :value="4" :readonly="true" color="green" />
                                    <x-spire::rating :value="4" :readonly="true" color="purple" />
                                </div>
                            </div>

                            {{-- Ícones diferentes --}}
                            <div>
                                <label class="text-xs text-gray-500 dark:text-gray-400 mb-3 block">Ícones
                                    diferentes</label>
                                <div class="flex flex-col gap-3">
                                    <x-spire::rating :value="4" :readonly="true" icon="star" />
                                    <x-spire::rating :value="4" :readonly="true" icon="heart"
                                        color="red" />
                                    <x-spire::rating :value="4" :readonly="true" icon="thumb"
                                        color="blue" />
                                </div>
                            </div>

                            {{-- Com formulário --}}
                            <div>
                                <label class="text-xs text-gray-500 dark:text-gray-400 mb-3 block">Em
                                    formulário</label>
                                <form class="flex items-center gap-4">
                                    <x-spire::rating id="rating-form" name="rating" :value="0"
                                        :showValue="true" data-v="rating" />
                                    <x-spire::button size="sm" type="button"
                                        onclick="SpireUI.toast.success('Avaliação: ' + document.querySelector('[name=rating]').value + ' estrelas')">
                                        Enviar
                                    </x-spire::button>
                                </form>
                            </div>
                        </div>
                    </div>

                    {{-- Icons --}}
                    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl p-8">
                        <h2 class="text-xl font-bold mb-6">🎨 Icons</h2>
                        <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">
                            Sistema de ícones SVG Sprite (zero requests HTTP).
                        </p>
                        <div class="space-y-6">
                            {{-- Interface --}}
                            <div>
                                <label class="text-xs text-gray-500 dark:text-gray-400 mb-3 block">Interface</label>
                                <div class="flex flex-wrap items-center gap-4">
                                    <x-spire::icon name="menu" class="text-gray-600 dark:text-gray-400" />
                                    <x-spire::icon name="x" class="text-gray-600 dark:text-gray-400" />
                                    <x-spire::icon name="search" class="text-gray-600 dark:text-gray-400" />
                                    <x-spire::icon name="filter" class="text-gray-600 dark:text-gray-400" />
                                    <x-spire::icon name="settings" class="text-gray-600 dark:text-gray-400" />
                                    <x-spire::icon name="chevron-down" class="text-gray-600 dark:text-gray-400" />
                                    <x-spire::icon name="chevron-up" class="text-gray-600 dark:text-gray-400" />
                                    <x-spire::icon name="chevron-left" class="text-gray-600 dark:text-gray-400" />
                                    <x-spire::icon name="chevron-right" class="text-gray-600 dark:text-gray-400" />
                                    <x-spire::icon name="check" class="text-gray-600 dark:text-gray-400" />
                                    <x-spire::icon name="plus" class="text-gray-600 dark:text-gray-400" />
                                    <x-spire::icon name="minus" class="text-gray-600 dark:text-gray-400" />
                                </div>
                            </div>

                            {{-- Ações --}}
                            <div>
                                <label class="text-xs text-gray-500 dark:text-gray-400 mb-3 block">Ações</label>
                                <div class="flex flex-wrap items-center gap-4">
                                    <x-spire::icon name="edit" class="text-blue-500" />
                                    <x-spire::icon name="trash" class="text-red-500" />
                                    <x-spire::icon name="copy" class="text-gray-600 dark:text-gray-400" />
                                    <x-spire::icon name="download" class="text-green-500" />
                                    <x-spire::icon name="upload" class="text-purple-500" />
                                    <x-spire::icon name="refresh" class="text-cyan-500" />
                                    <x-spire::icon name="save" class="text-blue-500" />
                                    <x-spire::icon name="share" class="text-indigo-500" />
                                    <x-spire::icon name="printer" class="text-gray-600 dark:text-gray-400" />
                                </div>
                            </div>

                            {{-- Status --}}
                            <div>
                                <label class="text-xs text-gray-500 dark:text-gray-400 mb-3 block">Status</label>
                                <div class="flex flex-wrap items-center gap-4">
                                    <x-spire::icon name="info" class="text-blue-500" />
                                    <x-spire::icon name="success" class="text-green-500" />
                                    <x-spire::icon name="warning" class="text-yellow-500" />
                                    <x-spire::icon name="error" class="text-red-500" />
                                </div>
                            </div>

                            {{-- Tamanhos --}}
                            <div>
                                <label class="text-xs text-gray-500 dark:text-gray-400 mb-3 block">Tamanhos</label>
                                <div class="flex flex-wrap items-end gap-4">
                                    <x-spire::icon name="star" size="xs" class="text-yellow-500" />
                                    <x-spire::icon name="star" size="sm" class="text-yellow-500" />
                                    <x-spire::icon name="star" size="md" class="text-yellow-500" />
                                    <x-spire::icon name="star" size="lg" class="text-yellow-500" />
                                    <x-spire::icon name="star" size="xl" class="text-yellow-500" />
                                    <x-spire::icon name="star" size="2xl" class="text-yellow-500" />
                                </div>
                            </div>

                            {{-- Uso em botões --}}
                            <div>
                                <label class="text-xs text-gray-500 dark:text-gray-400 mb-3 block">Em Botões</label>
                                <div class="flex flex-wrap items-center gap-3">
                                    <x-spire::button size="sm">
                                        <x-spire::icon name="plus" size="sm" class="mr-1" /> Adicionar
                                    </x-spire::button>
                                    <x-spire::button size="sm" class="from-red-500 to-red-600">
                                        <x-spire::icon name="trash" size="sm" class="mr-1" /> Excluir
                                    </x-spire::button>
                                    <x-spire::icon-button variant="outline" title="Editar">
                                        <x-spire::icon name="edit" />
                                    </x-spire::icon-button>
                                    <x-spire::icon-button variant="filled" color="danger" title="Excluir">
                                        <x-spire::icon name="trash" />
                                    </x-spire::icon-button>
                                </div>
                            </div>

                            {{-- Mais ícones --}}
                            <div>
                                <label class="text-xs text-gray-500 dark:text-gray-400 mb-3 block">Outros
                                    Ícones</label>
                                <div class="flex flex-wrap items-center gap-4">
                                    <x-spire::icon name="user" class="text-gray-600 dark:text-gray-400" />
                                    <x-spire::icon name="users" class="text-gray-600 dark:text-gray-400" />
                                    <x-spire::icon name="bell" class="text-gray-600 dark:text-gray-400" />
                                    <x-spire::icon name="mail" class="text-gray-600 dark:text-gray-400" />
                                    <x-spire::icon name="chat" class="text-gray-600 dark:text-gray-400" />
                                    <x-spire::icon name="calendar" class="text-gray-600 dark:text-gray-400" />
                                    <x-spire::icon name="clock" class="text-gray-600 dark:text-gray-400" />
                                    <x-spire::icon name="home" class="text-gray-600 dark:text-gray-400" />
                                    <x-spire::icon name="heart" class="text-red-500" />
                                    <x-spire::icon name="heart-solid" class="text-red-500" />
                                    <x-spire::icon name="star" class="text-yellow-500" />
                                    <x-spire::icon name="star-solid" class="text-yellow-500" />
                                    <x-spire::icon name="eye" class="text-gray-600 dark:text-gray-400" />
                                    <x-spire::icon name="eye-off" class="text-gray-600 dark:text-gray-400" />
                                    <x-spire::icon name="lock" class="text-gray-600 dark:text-gray-400" />
                                    <x-spire::icon name="unlock" class="text-gray-600 dark:text-gray-400" />
                                    <x-spire::icon name="link" class="text-gray-600 dark:text-gray-400" />
                                    <x-spire::icon name="external-link" class="text-gray-600 dark:text-gray-400" />
                                    <x-spire::icon name="folder" class="text-yellow-500" />
                                    <x-spire::icon name="document" class="text-gray-600 dark:text-gray-400" />
                                    <x-spire::icon name="image" class="text-gray-600 dark:text-gray-400" />
                                    <x-spire::icon name="camera" class="text-gray-600 dark:text-gray-400" />
                                    <x-spire::icon name="cart" class="text-gray-600 dark:text-gray-400" />
                                    <x-spire::icon name="credit-card" class="text-gray-600 dark:text-gray-400" />
                                    <x-spire::icon name="chart" class="text-gray-600 dark:text-gray-400" />
                                    <x-spire::icon name="globe" class="text-gray-600 dark:text-gray-400" />
                                    <x-spire::icon name="qrcode" class="text-gray-600 dark:text-gray-400" />
                                    <x-spire::icon name="logout" class="text-gray-600 dark:text-gray-400" />
                                    <x-spire::icon name="help" class="text-gray-600 dark:text-gray-400" />
                                </div>
                            </div>

                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-2">
                                💡 50+ ícones SVG otimizados. Zero requests HTTP. ~5KB total (vs ~300KB Font Awesome).
                            </p>
                        </div>
                    </div>

                    {{-- Carousel --}}
                    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl p-8 md:col-span-2">
                        <h2 class="text-xl font-bold mb-6">🎠 Carousel</h2>
                        <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">
                            Carrossel de imagens com navegação, indicadores e autoplay.
                        </p>
                        <div class="grid md:grid-cols-2 gap-6">
                            {{-- Carousel básico --}}
                            <div>
                                <label class="text-xs text-gray-500 dark:text-gray-400 mb-3 block">Básico com
                                    navegação</label>
                                <x-spire::carousel id="demo-carousel-1" :images="[
                                    ['src' => 'https://picsum.photos/800/450?random=1', 'alt' => 'Imagem 1'],
                                    ['src' => 'https://picsum.photos/800/450?random=2', 'alt' => 'Imagem 2'],
                                    ['src' => 'https://picsum.photos/800/450?random=3', 'alt' => 'Imagem 3'],
                                    ['src' => 'https://picsum.photos/800/450?random=4', 'alt' => 'Imagem 4'],
                                ]" />
                            </div>

                            {{-- Carousel com autoplay --}}
                            <div>
                                <label class="text-xs text-gray-500 dark:text-gray-400 mb-3 block">Autoplay
                                    (5s)</label>
                                <x-spire::carousel id="demo-carousel-2" :autoplay="true" :interval="5000"
                                    :images="[
                                        ['src' => 'https://picsum.photos/800/450?random=5', 'alt' => 'Imagem 5'],
                                        ['src' => 'https://picsum.photos/800/450?random=6', 'alt' => 'Imagem 6'],
                                        ['src' => 'https://picsum.photos/800/450?random=7', 'alt' => 'Imagem 7'],
                                    ]" />
                            </div>

                            {{-- Carousel com captions --}}
                            <div>
                                <label class="text-xs text-gray-500 dark:text-gray-400 mb-3 block">Com
                                    legendas</label>
                                <x-spire::carousel id="demo-carousel-3" :overlay="true" :captions="true"
                                    :images="[
                                        [
                                            'src' => 'https://picsum.photos/800/450?random=8',
                                            'alt' => 'Natureza',
                                            'caption' => '🌿 Natureza exuberante',
                                        ],
                                        [
                                            'src' => 'https://picsum.photos/800/450?random=9',
                                            'alt' => 'Cidade',
                                            'caption' => '🏙️ Paisagem urbana',
                                        ],
                                        [
                                            'src' => 'https://picsum.photos/800/450?random=10',
                                            'alt' => 'Montanha',
                                            'caption' => '⛰️ Montanhas majestosas',
                                        ],
                                    ]" />
                            </div>

                            {{-- Carousel com thumbnails --}}
                            <div>
                                <label class="text-xs text-gray-500 dark:text-gray-400 mb-3 block">Com
                                    miniaturas</label>
                                <x-spire::carousel id="demo-carousel-4" :showIndicators="false" :thumbnails="true"
                                    :images="[
                                        ['src' => 'https://picsum.photos/800/450?random=11', 'alt' => 'Slide 1'],
                                        ['src' => 'https://picsum.photos/800/450?random=12', 'alt' => 'Slide 2'],
                                        ['src' => 'https://picsum.photos/800/450?random=13', 'alt' => 'Slide 3'],
                                        ['src' => 'https://picsum.photos/800/450?random=14', 'alt' => 'Slide 4'],
                                    ]" />
                            </div>
                        </div>

                        {{-- Controle via JavaScript --}}
                        <div class="mt-6 p-4 bg-gray-50 dark:bg-gray-900 rounded-xl">
                            <label class="text-xs text-gray-500 dark:text-gray-400 mb-3 block">Controle via
                                JavaScript</label>
                            <div class="flex flex-wrap items-center gap-3">
                                <x-spire::button size="sm" onclick="SpireUI.carousel('demo-carousel-1').prev()">
                                    ⬅️ Anterior
                                </x-spire::button>
                                <x-spire::button size="sm" onclick="SpireUI.carousel('demo-carousel-1').next()">
                                    Próximo ➡️
                                </x-spire::button>
                                <x-spire::button size="sm" class="from-green-500 to-green-600"
                                    onclick="SpireUI.carousel('demo-carousel-1').goto(0)">
                                    Ir para 1
                                </x-spire::button>
                                <x-spire::button size="sm" class="from-purple-500 to-purple-600"
                                    onclick="SpireUI.carousel('demo-carousel-1').goto(2)">
                                    Ir para 3
                                </x-spire::button>
                            </div>
                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-2">
                                API: <code class="text-pink-500">SpireUI.carousel('id').next()</code>,
                                <code class="text-pink-500">.prev()</code>,
                                <code class="text-pink-500">.goto(index)</code>,
                                <code class="text-pink-500">.play()</code>,
                                <code class="text-pink-500">.pause()</code>
                            </p>
                        </div>
                    </div>

                    {{-- Timeline --}}
                    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl p-8">
                        <h2 class="text-xl font-bold mb-6">📅 Timeline</h2>
                        <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">
                            Histórico de eventos com diferentes layouts.
                        </p>
                        <div class="space-y-6">
                            {{-- Timeline padrão --}}
                            <div>
                                <label class="text-xs text-gray-500 dark:text-gray-400 mb-3 block">Padrão</label>
                                <x-spire::timeline :items="[
                                    [
                                        'title' => 'Pedido criado',
                                        'description' => 'Pedido #12345 foi criado',
                                        'date' => '10:30',
                                        'icon' => 'cart',
                                        'color' => 'info',
                                    ],
                                    [
                                        'title' => 'Pagamento confirmado',
                                        'description' => 'Pagamento via PIX aprovado',
                                        'date' => '10:35',
                                        'icon' => 'check',
                                        'color' => 'success',
                                    ],
                                    [
                                        'title' => 'Em preparação',
                                        'description' => 'Seu pedido está sendo preparado',
                                        'date' => '11:00',
                                        'icon' => 'clock',
                                        'color' => 'warning',
                                    ],
                                ]" />
                            </div>

                            {{-- Timeline compacto --}}
                            <div>
                                <label class="text-xs text-gray-500 dark:text-gray-400 mb-3 block">Compacto</label>
                                <x-spire::timeline variant="compact" :items="[
                                    ['title' => 'Login realizado', 'date' => '2 min atrás', 'color' => 'success'],
                                    ['title' => 'Perfil atualizado', 'date' => '1 hora atrás', 'color' => 'info'],
                                    ['title' => 'Senha alterada', 'date' => 'Ontem', 'color' => 'warning'],
                                    ['title' => 'Conta criada', 'date' => '1 semana atrás', 'color' => 'primary'],
                                ]" />
                            </div>
                        </div>
                    </div>

                    {{-- Stats Cards --}}
                    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl p-8 md:col-span-2">
                        <h2 class="text-xl font-bold mb-6">📊 Stats Card</h2>
                        <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">
                            Métricas com tendências e sparklines.
                        </p>
                        <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-4">
                            {{-- Default --}}
                            <x-spire::stats-card title="Receita Total" value="R$ 45.231" change="+12.5%"
                                changeLabel="vs mês passado" icon="chart" iconColor="success" />

                            {{-- Com tendência negativa --}}
                            <x-spire::stats-card title="Pedidos" value="1.234" change="-3.2%"
                                changeLabel="vs semana passada" icon="cart" iconColor="danger" />

                            {{-- Gradient --}}
                            <x-spire::stats-card variant="gradient" color="purple" title="Usuários Ativos"
                                value="8.549" change="+23%" icon="users" />

                            {{-- Com sparkline --}}
                            <x-spire::stats-card title="Conversão" value="4.28%" change="+0.8%" icon="chart"
                                iconColor="info" :chart="[2.1, 2.4, 2.2, 2.8, 3.1, 2.9, 3.5, 3.8, 4.0, 4.28]" />
                        </div>

                        {{-- Minimal variant --}}
                        <div class="mt-6 grid sm:grid-cols-3 gap-4 p-4 bg-gray-50 dark:bg-gray-900 rounded-xl">
                            <x-spire::stats-card variant="minimal" title="Visitas" value="12.4K" change="+5%"
                                icon="eye" iconColor="primary" />
                            <x-spire::stats-card variant="minimal" title="Downloads" value="3.2K" change="+18%"
                                icon="download" iconColor="success" />
                            <x-spire::stats-card variant="minimal" title="Avaliação" value="4.9" icon="star"
                                iconColor="warning" />
                        </div>
                    </div>

                    {{-- Notification Bell --}}
                    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl p-8">
                        <h2 class="text-xl font-bold mb-6">🔔 Notification Bell</h2>
                        <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">
                            Sino de notificações com dropdown.
                        </p>
                        <div class="flex flex-wrap items-center gap-6">
                            {{-- Sem notificações --}}
                            <div class="text-center">
                                <x-spire::notification-bell :count="0" />
                                <span class="text-xs text-gray-500 mt-2 block">Vazio</span>
                            </div>

                            {{-- Com notificações --}}
                            <div class="text-center">
                                <x-spire::notification-bell :count="5" :notifications="[
                                    [
                                        'title' => 'Novo pedido recebido',
                                        'description' => 'Pedido #12345 de R$ 150,00',
                                        'time' => '2 min atrás',
                                        'icon' => 'cart',
                                        'iconColor' => 'green',
                                    ],
                                    [
                                        'title' => 'Pagamento confirmado',
                                        'time' => '5 min atrás',
                                        'icon' => 'check',
                                        'iconColor' => 'blue',
                                        'read' => true,
                                    ],
                                    [
                                        'title' => 'Novo comentário',
                                        'description' => 'João comentou no seu post',
                                        'time' => '1 hora atrás',
                                        'icon' => 'chat',
                                        'iconColor' => 'purple',
                                    ],
                                ]" />
                                <span class="text-xs text-gray-500 mt-2 block">Com lista</span>
                            </div>

                            {{-- Com pulse --}}
                            <div class="text-center">
                                <x-spire::notification-bell :count="99" :pulse="true" />
                                <span class="text-xs text-gray-500 mt-2 block">Pulse</span>
                            </div>

                            {{-- Tamanhos --}}
                            <div class="text-center">
                                <div class="flex items-end gap-3">
                                    <x-spire::notification-bell :count="3" size="sm" :showDropdown="false" />
                                    <x-spire::notification-bell :count="3" size="md" :showDropdown="false" />
                                    <x-spire::notification-bell :count="3" size="lg" :showDropdown="false" />
                                </div>
                                <span class="text-xs text-gray-500 mt-2 block">Tamanhos</span>
                            </div>
                        </div>
                    </div>

                    {{-- Empty State --}}
                    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl p-8">
                        <h2 class="text-xl font-bold mb-6">🎯 Empty State</h2>
                        <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">
                            Estados vazios para quando não há dados.
                        </p>
                        <div class="space-y-6">
                            {{-- Default --}}
                            <x-spire::empty-state icon="inbox" title="Nenhuma mensagem"
                                description="Você não tem mensagens na caixa de entrada."
                                actionLabel="Escrever mensagem" secondaryLabel="Ver arquivadas" />

                            {{-- Card variant --}}
                            <x-spire::empty-state variant="card" icon="cart" title="Carrinho vazio"
                                description="Adicione produtos ao seu carrinho para continuar comprando."
                                actionLabel="Ver produtos" />
                        </div>
                    </div>

                    {{-- Pricing Cards --}}
                    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl p-8 md:col-span-2">
                        <h2 class="text-xl font-bold mb-6">💳 Pricing Card</h2>
                        <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">
                            Cards de preços para planos e assinaturas.
                        </p>
                        <div class="grid sm:grid-cols-3 gap-6">
                            {{-- Basic --}}
                            <x-spire::pricing-card name="Starter" description="Para começar" price="0"
                                period="/mês" ctaLabel="Começar grátis" :features="[
                                    'Até 3 projetos',
                                    '1GB de armazenamento',
                                    'Suporte por email',
                                    ['label' => 'API Access', 'included' => false],
                                    ['label' => 'Integrações', 'included' => false],
                                ]" />

                            {{-- Pro (Popular) --}}
                            <x-spire::pricing-card variant="gradient" color="primary" name="Pro"
                                description="Para profissionais" price="49" originalPrice="79"
                                discount="-38% OFF" period="/mês" :popular="true" ctaLabel="Assinar Pro"
                                :features="[
                                    ['label' => 'Projetos ilimitados', 'highlight' => true],
                                    '50GB de armazenamento',
                                    'Suporte prioritário',
                                    'API Access',
                                    'Todas as integrações',
                                ]" />

                            {{-- Enterprise --}}
                            <x-spire::pricing-card name="Enterprise" description="Para grandes times" price="199"
                                period="/mês" color="purple" :highlighted="true" ctaLabel="Falar com vendas"
                                :features="[
                                    ['label' => 'Tudo do Pro', 'highlight' => true],
                                    'Armazenamento ilimitado',
                                    'Suporte 24/7 dedicado',
                                    'SSO / SAML',
                                    'SLA garantido',
                                ]" />
                        </div>
                    </div>

                    {{-- Swap --}}
                    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl p-8">
                        <h2 class="text-xl font-bold mb-6">🔄 Swap</h2>
                        <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">
                            Alterna entre dois estados com animações.
                        </p>
                        <div class="space-y-6">
                            {{-- Hamburger / X --}}
                            <div>
                                <label class="text-xs text-gray-500 dark:text-gray-400 mb-3 block">Menu
                                    Hamburger</label>
                                <x-spire::swap animation="rotate" class="btn btn-ghost p-2">
                                    <x-slot:on>
                                        <svg class="w-8 h-8" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M6 18L18 6M6 6l12 12" />
                                        </svg>
                                    </x-slot:on>
                                    <x-slot:off>
                                        <svg class="w-8 h-8" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M4 6h16M4 12h16M4 18h16" />
                                        </svg>
                                    </x-slot:off>
                                </x-spire::swap>
                            </div>

                            {{-- Volume On/Off --}}
                            <div>
                                <label class="text-xs text-gray-500 dark:text-gray-400 mb-3 block">Volume
                                    (zoom)</label>
                                <x-spire::swap animation="zoom" :active="true"
                                    class="p-2 rounded-full hover:bg-gray-100 dark:hover:bg-gray-700">
                                    <x-slot:on>
                                        <svg class="w-8 h-8 text-primary-500" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M15.536 8.464a5 5 0 010 7.072m2.828-9.9a9 9 0 010 12.728M5.586 15H4a1 1 0 01-1-1v-4a1 1 0 011-1h1.586l4.707-4.707C10.923 3.663 12 4.109 12 5v14c0 .891-1.077 1.337-1.707.707L5.586 15z" />
                                        </svg>
                                    </x-slot:on>
                                    <x-slot:off>
                                        <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M5.586 15H4a1 1 0 01-1-1v-4a1 1 0 011-1h1.586l4.707-4.707C10.923 3.663 12 4.109 12 5v14c0 .891-1.077 1.337-1.707.707L5.586 15z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M17 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2" />
                                        </svg>
                                    </x-slot:off>
                                </x-spire::swap>
                            </div>

                            {{-- Sun/Moon Theme --}}
                            <div>
                                <label class="text-xs text-gray-500 dark:text-gray-400 mb-3 block">Tema (fade)</label>
                                <x-spire::swap animation="fade" class="p-2 rounded-lg bg-gray-100 dark:bg-gray-700">
                                    <x-slot:on>
                                        <svg class="w-8 h-8 text-yellow-500" fill="currentColor"
                                            viewBox="0 0 20 20">
                                            <path fill-rule="evenodd"
                                                d="M10 2a1 1 0 011 1v1a1 1 0 11-2 0V3a1 1 0 011-1zm4 8a4 4 0 11-8 0 4 4 0 018 0zm-.464 4.95l.707.707a1 1 0 001.414-1.414l-.707-.707a1 1 0 00-1.414 1.414zm2.12-10.607a1 1 0 010 1.414l-.706.707a1 1 0 11-1.414-1.414l.707-.707a1 1 0 011.414 0zM17 11a1 1 0 100-2h-1a1 1 0 100 2h1zm-7 4a1 1 0 011 1v1a1 1 0 11-2 0v-1a1 1 0 011-1zM5.05 6.464A1 1 0 106.465 5.05l-.708-.707a1 1 0 00-1.414 1.414l.707.707zm1.414 8.486l-.707.707a1 1 0 01-1.414-1.414l.707-.707a1 1 0 011.414 1.414zM4 11a1 1 0 100-2H3a1 1 0 000 2h1z"
                                                clip-rule="evenodd" />
                                        </svg>
                                    </x-slot:on>
                                    <x-slot:off>
                                        <svg class="w-8 h-8 text-indigo-500" fill="currentColor"
                                            viewBox="0 0 20 20">
                                            <path
                                                d="M17.293 13.293A8 8 0 016.707 2.707a8.001 8.001 0 1010.586 10.586z" />
                                        </svg>
                                    </x-slot:off>
                                </x-spire::swap>
                            </div>

                            {{-- Heart Like --}}
                            <div>
                                <label class="text-xs text-gray-500 dark:text-gray-400 mb-3 block">Like
                                    (slide)</label>
                                <x-spire::swap animation="slide" class="p-2">
                                    <x-slot:on>
                                        <svg class="w-8 h-8 text-red-500" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd"
                                                d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z"
                                                clip-rule="evenodd" />
                                        </svg>
                                    </x-slot:on>
                                    <x-slot:off>
                                        <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                                        </svg>
                                    </x-slot:off>
                                </x-spire::swap>
                            </div>

                            {{-- Emoji Happy/Sad --}}
                            <div>
                                <label class="text-xs text-gray-500 dark:text-gray-400 mb-3 block">Emoji
                                    (rotate)</label>
                                <x-spire::swap animation="rotate"
                                    class="text-4xl p-2 cursor-pointer hover:scale-110 transition-transform">
                                    <x-slot:on>😊</x-slot:on>
                                    <x-slot:off>😢</x-slot:off>
                                </x-spire::swap>
                            </div>

                            {{-- Text Swap - Cor padrão --}}
                            <div>
                                <label class="text-xs text-gray-500 dark:text-gray-400 mb-3 block">Texto (cor
                                    padrão)</label>
                                <x-spire::swap :text="true"
                                    class="inline-block px-4 py-2 bg-gray-100 dark:bg-gray-700 rounded-lg font-medium hover:bg-gray-200 dark:hover:bg-gray-600 transition-colors">
                                    <x-slot:on>
                                        <span class="flex items-center gap-2">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    stroke-width="2" d="M5 13l4 4L19 7" />
                                            </svg>
                                            Ativado
                                        </span>
                                    </x-slot:on>
                                    <x-slot:off>
                                        <span class="flex items-center gap-2">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                            </svg>
                                            Desativado
                                        </span>
                                    </x-slot:off>
                                </x-spire::swap>
                            </div>

                            {{-- Text Swap - Cor customizada --}}
                            <div>
                                <label class="text-xs text-gray-500 dark:text-gray-400 mb-3 block">Texto
                                    (color="text-white")</label>
                                <x-spire::swap :text="true" color="text-white"
                                    class="inline-block px-4 py-2 bg-blue-500 rounded-lg font-medium hover:bg-blue-600 transition-colors">
                                    <x-slot:on>
                                        <span class="flex items-center gap-2">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    stroke-width="2" d="M5 13l4 4L19 7" />
                                            </svg>
                                            Seguindo
                                        </span>
                                    </x-slot:on>
                                    <x-slot:off>
                                        <span class="flex items-center gap-2">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    stroke-width="2" d="M12 4v16m8-8H4" />
                                            </svg>
                                            Seguir
                                        </span>
                                    </x-slot:off>
                                </x-spire::swap>
                            </div>
                        </div>
                    </div>

                    {{-- Dropdown --}}
                    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl p-8">
                        <h2 class="text-xl font-bold mb-6">📋 Dropdown</h2>
                        <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">
                            Menu dropdown com várias opções de posicionamento e estilo.
                        </p>
                        <div class="flex flex-wrap gap-4 items-start">
                            {{-- Basic --}}
                            <x-spire::dropdown trigger="Options">
                                <x-spire::dropdown-item
                                    icon='<svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>'>
                                    Profile
                                </x-spire::dropdown-item>
                                <x-spire::dropdown-item
                                    icon='<svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>'>
                                    Settings
                                </x-spire::dropdown-item>
                                <x-spire::dropdown-divider />
                                <x-spire::dropdown-item :danger="true"
                                    icon='<svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>'>
                                    Logout
                                </x-spire::dropdown-item>
                            </x-spire::dropdown>

                            {{-- With Header --}}
                            <x-spire::dropdown trigger="Account" position="bottom-end" width="w-56">
                                <x-spire::dropdown-divider label="Signed in as" />
                                <div class="px-4 py-2 text-sm text-gray-900 dark:text-white font-medium">
                                    user@example.com
                                </div>
                                <x-spire::dropdown-divider />
                                <x-spire::dropdown-item :active="true">Dashboard</x-spire::dropdown-item>
                                <x-spire::dropdown-item>Your Profile</x-spire::dropdown-item>
                                <x-spire::dropdown-item>Settings</x-spire::dropdown-item>
                                <x-spire::dropdown-divider />
                                <x-spire::dropdown-item :danger="true">Sign out</x-spire::dropdown-item>
                            </x-spire::dropdown>

                            {{-- Hover --}}
                            <x-spire::dropdown trigger="Hover me" :hover="true">
                                <x-spire::dropdown-item>Opens on hover</x-spire::dropdown-item>
                                <x-spire::dropdown-item>No click needed</x-spire::dropdown-item>
                                <x-spire::dropdown-item>Just hover!</x-spire::dropdown-item>
                            </x-spire::dropdown>

                            {{-- Custom Trigger --}}
                            <x-spire::dropdown position="bottom-end">
                                <x-slot:triggerSlot>
                                    <div
                                        class="w-10 h-10 rounded-full bg-linear-to-r from-purple-500 to-pink-500 flex items-center justify-center text-white font-bold cursor-pointer hover:scale-105 transition-transform">
                                        JD
                                    </div>
                                </x-slot:triggerSlot>
                                <x-spire::dropdown-divider label="John Doe" />
                                <x-spire::dropdown-item href="#profile">View Profile</x-spire::dropdown-item>
                                <x-spire::dropdown-item href="#settings">Settings</x-spire::dropdown-item>
                                <x-spire::dropdown-divider />
                                <x-spire::dropdown-item :disabled="true">Admin Panel</x-spire::dropdown-item>
                                <x-spire::dropdown-item :danger="true">Logout</x-spire::dropdown-item>
                            </x-spire::dropdown>
                        </div>
                    </div>

                    {{-- Popover --}}
                    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl p-8">
                        <h2 class="text-xl font-bold mb-6">💬 Popover</h2>
                        <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">
                            Conteúdo rico em popup posicionável.
                        </p>
                        <div class="flex flex-wrap gap-4">
                            <x-spire::popover position="bottom" trigger="Info">
                                <x-slot:title>Informação</x-slot:title>
                                Este é um popover com conteúdo rico. Pode conter qualquer HTML!
                            </x-spire::popover>

                            <x-spire::popover position="right">
                                <x-slot:triggerSlot>
                                    <x-spire::button class="from-purple-500 to-pink-500">Menu</x-spire::button>
                                </x-slot:triggerSlot>
                                <div class="space-y-2">
                                    <a href="#"
                                        class="block px-2 py-1 hover:bg-gray-100 dark:hover:bg-gray-700 rounded">Perfil</a>
                                    <a href="#"
                                        class="block px-2 py-1 hover:bg-gray-100 dark:hover:bg-gray-700 rounded">Configurações</a>
                                    <a href="#"
                                        class="block px-2 py-1 hover:bg-gray-100 dark:hover:bg-gray-700 rounded text-red-500">Sair</a>
                                </div>
                            </x-spire::popover>
                        </div>
                    </div>

                    {{-- Tooltips --}}
                    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl p-8">
                        <h2 class="text-xl font-bold mb-6">💡 Tooltips</h2>
                        <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">
                            Dicas em diferentes posições.
                        </p>
                        <div class="flex flex-wrap gap-3">
                            <x-spire::tooltip content="Topo" position="top">
                                <x-spire::button>↑</x-spire::button>
                            </x-spire::tooltip>
                            <x-spire::tooltip content="Baixo" position="bottom">
                                <x-spire::button class="from-green-500 to-green-600">↓</x-spire::button>
                            </x-spire::tooltip>
                            <x-spire::tooltip content="Esquerda" position="left">
                                <x-spire::button class="from-yellow-500 to-orange-500">←</x-spire::button>
                            </x-spire::tooltip>
                            <x-spire::tooltip content="Direita" position="right">
                                <x-spire::button class="from-purple-500 to-pink-500">→</x-spire::button>
                            </x-spire::tooltip>
                        </div>
                    </div>

                    {{-- Dynamic Tabs Demo --}}
                    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl p-8 md:col-span-2 lg:col-span-3">
                        <h2 class="text-xl font-bold mb-6">📑 Tabs Dinâmicas</h2>
                        <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">
                            Tabs com suporte a desabilitar, ocultar e adicionar/remover dinamicamente.
                        </p>

                        {{-- Demo Tabs --}}
                        <x-spire::tabs id="dynamic-tabs" class="mb-6">
                            <x-slot:tabs>
                                <x-spire::tab name="tab1" :active="true">Tab 1</x-spire::tab>
                                <x-spire::tab name="tab2">Tab 2</x-spire::tab>
                                <x-spire::tab name="tab3">Tab 3</x-spire::tab>
                                <x-spire::tab name="tab4" :disabled="true">Desabilitada</x-spire::tab>
                            </x-slot:tabs>

                            <x-spire::tab-panel name="tab1" :active="true">
                                <p class="text-gray-600 dark:text-gray-400">Conteúdo da Tab 1</p>
                            </x-spire::tab-panel>
                            <x-spire::tab-panel name="tab2">
                                <p class="text-gray-600 dark:text-gray-400">Conteúdo da Tab 2</p>
                            </x-spire::tab-panel>
                            <x-spire::tab-panel name="tab3">
                                <p class="text-gray-600 dark:text-gray-400">Conteúdo da Tab 3</p>
                            </x-spire::tab-panel>
                            <x-spire::tab-panel name="tab4">
                                <p class="text-gray-600 dark:text-gray-400">Conteúdo da Tab Desabilitada</p>
                            </x-spire::tab-panel>
                        </x-spire::tabs>

                        {{-- Controls --}}
                        <div class="space-y-3">
                            <p class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wide">
                                Gerenciamento</p>
                            <div class="flex flex-wrap gap-2">
                                <x-spire::button id="tabs-disable-2" class="from-yellow-500 to-orange-500">Desabilitar
                                    Tab 2</x-spire::button>
                                <x-spire::button id="tabs-enable-2" class="from-green-500 to-green-600">Habilitar Tab
                                    2</x-spire::button>
                                <x-spire::button id="tabs-hide-3" class="from-gray-500 to-gray-600">Ocultar Tab
                                    3</x-spire::button>
                                <x-spire::button id="tabs-show-3" class="from-blue-500 to-blue-600">Mostrar Tab
                                    3</x-spire::button>
                                <x-spire::button id="tabs-add" class="from-purple-500 to-pink-500">+
                                    Adicionar</x-spire::button>
                                <x-spire::button id="tabs-remove" class="from-red-500 to-red-600">-
                                    Remover</x-spire::button>
                            </div>

                            <p
                                class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wide pt-2">
                                Destaques (Validação)</p>
                            <div class="flex flex-wrap gap-2">
                                <x-spire::button id="tabs-error" class="from-red-500 to-red-600">🔴 Erro Tab
                                    1</x-spire::button>
                                <x-spire::button id="tabs-warning" class="from-amber-500 to-orange-500">🟠 Warning Tab
                                    2</x-spire::button>
                                <x-spire::button id="tabs-success" class="from-green-500 to-green-600">🟢 Sucesso Tab
                                    3</x-spire::button>
                                <x-spire::button id="tabs-pulse" class="from-pink-500 to-rose-500">💓
                                    Pulsante</x-spire::button>
                                <x-spire::button id="tabs-badge" class="from-indigo-500 to-purple-500">🔢 Com
                                    Badge</x-spire::button>
                                <x-spire::button id="tabs-clear" class="from-gray-500 to-gray-600">✖ Limpar
                                    Todos</x-spire::button>
                            </div>
                        </div>
                    </div>

                    {{-- Tab Variants --}}
                    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl p-8 md:col-span-2 lg:col-span-3">
                        <h2 class="text-xl font-bold mb-6">🎨 Estilos de Tabs</h2>
                        <p class="text-sm text-gray-600 dark:text-gray-400 mb-6">
                            5 variantes visuais disponíveis: underline, pills, boxed, minimal e segmented.
                        </p>

                        <div class="grid gap-8 lg:grid-cols-2">
                            {{-- Underline (default) --}}
                            <div>
                                <p class="text-xs font-medium text-gray-500 uppercase mb-3">Underline (padrão)</p>
                                <x-spire::tabs id="tabs-underline" variant="underline">
                                    <x-slot:tabs>
                                        <x-spire::tab name="under1" variant="underline"
                                            :active="true">Dados</x-spire::tab>
                                        <x-spire::tab name="under2" variant="underline">Produto</x-spire::tab>
                                        <x-spire::tab name="under3" variant="underline">Reparo</x-spire::tab>
                                    </x-slot:tabs>
                                    <x-spire::tab-panel name="under1" :active="true">
                                        <p class="text-gray-500 text-sm">Conteúdo Dados</p>
                                    </x-spire::tab-panel>
                                    <x-spire::tab-panel name="under2">
                                        <p class="text-gray-500 text-sm">Conteúdo Produto</p>
                                    </x-spire::tab-panel>
                                    <x-spire::tab-panel name="under3">
                                        <p class="text-gray-500 text-sm">Conteúdo Reparo</p>
                                    </x-spire::tab-panel>
                                </x-spire::tabs>
                            </div>

                            {{-- Pills --}}
                            <div>
                                <p class="text-xs font-medium text-gray-500 uppercase mb-3">Pills</p>
                                <x-spire::tabs id="tabs-pills" variant="pills">
                                    <x-slot:tabs>
                                        <x-spire::tab name="pill1" variant="pills"
                                            :active="true">Dados</x-spire::tab>
                                        <x-spire::tab name="pill2" variant="pills">Produto</x-spire::tab>
                                        <x-spire::tab name="pill3" variant="pills">Reparo</x-spire::tab>
                                    </x-slot:tabs>
                                    <x-spire::tab-panel name="pill1" :active="true">
                                        <p class="text-gray-500 text-sm">Conteúdo Dados</p>
                                    </x-spire::tab-panel>
                                    <x-spire::tab-panel name="pill2">
                                        <p class="text-gray-500 text-sm">Conteúdo Produto</p>
                                    </x-spire::tab-panel>
                                    <x-spire::tab-panel name="pill3">
                                        <p class="text-gray-500 text-sm">Conteúdo Reparo</p>
                                    </x-spire::tab-panel>
                                </x-spire::tabs>
                            </div>

                            {{-- Boxed --}}
                            <div>
                                <p class="text-xs font-medium text-gray-500 uppercase mb-3">Boxed</p>
                                <x-spire::tabs id="tabs-boxed" variant="boxed">
                                    <x-slot:tabs>
                                        <x-spire::tab name="box1" variant="boxed"
                                            :active="true">Dados</x-spire::tab>
                                        <x-spire::tab name="box2" variant="boxed">Produto</x-spire::tab>
                                        <x-spire::tab name="box3" variant="boxed">Reparo</x-spire::tab>
                                    </x-slot:tabs>
                                    <x-spire::tab-panel name="box1" :active="true">
                                        <p class="text-gray-500 text-sm">Conteúdo Dados</p>
                                    </x-spire::tab-panel>
                                    <x-spire::tab-panel name="box2">
                                        <p class="text-gray-500 text-sm">Conteúdo Produto</p>
                                    </x-spire::tab-panel>
                                    <x-spire::tab-panel name="box3">
                                        <p class="text-gray-500 text-sm">Conteúdo Reparo</p>
                                    </x-spire::tab-panel>
                                </x-spire::tabs>
                            </div>

                            {{-- Minimal --}}
                            <div>
                                <p class="text-xs font-medium text-gray-500 uppercase mb-3">Minimal</p>
                                <x-spire::tabs id="tabs-minimal" variant="minimal">
                                    <x-slot:tabs>
                                        <x-spire::tab name="min1" variant="minimal"
                                            :active="true">Dados</x-spire::tab>
                                        <x-spire::tab name="min2" variant="minimal">Produto</x-spire::tab>
                                        <x-spire::tab name="min3" variant="minimal">Reparo</x-spire::tab>
                                    </x-slot:tabs>
                                    <x-spire::tab-panel name="min1" :active="true">
                                        <p class="text-gray-500 text-sm">Conteúdo Dados</p>
                                    </x-spire::tab-panel>
                                    <x-spire::tab-panel name="min2">
                                        <p class="text-gray-500 text-sm">Conteúdo Produto</p>
                                    </x-spire::tab-panel>
                                    <x-spire::tab-panel name="min3">
                                        <p class="text-gray-500 text-sm">Conteúdo Reparo</p>
                                    </x-spire::tab-panel>
                                </x-spire::tabs>
                            </div>

                            {{-- Segmented --}}
                            <div class="lg:col-span-2">
                                <p class="text-xs font-medium text-gray-500 uppercase mb-3">Segmented</p>
                                <x-spire::tabs id="tabs-segmented" variant="segmented">
                                    <x-slot:tabs>
                                        <x-spire::tab name="seg1" variant="segmented"
                                            :active="true">Dados</x-spire::tab>
                                        <x-spire::tab name="seg2" variant="segmented">Consumidor</x-spire::tab>
                                        <x-spire::tab name="seg3" variant="segmented">Produto</x-spire::tab>
                                        <x-spire::tab name="seg4" variant="segmented">Reparo</x-spire::tab>
                                        <x-spire::tab name="seg5" variant="segmented">Evidências</x-spire::tab>
                                    </x-slot:tabs>
                                    <x-spire::tab-panel name="seg1" :active="true">
                                        <p class="text-gray-500 text-sm">Conteúdo Dados</p>
                                    </x-spire::tab-panel>
                                    <x-spire::tab-panel name="seg2">
                                        <p class="text-gray-500 text-sm">Conteúdo Consumidor</p>
                                    </x-spire::tab-panel>
                                    <x-spire::tab-panel name="seg3">
                                        <p class="text-gray-500 text-sm">Conteúdo Produto</p>
                                    </x-spire::tab-panel>
                                    <x-spire::tab-panel name="seg4">
                                        <p class="text-gray-500 text-sm">Conteúdo Reparo</p>
                                    </x-spire::tab-panel>
                                    <x-spire::tab-panel name="seg5">
                                        <p class="text-gray-500 text-sm">Conteúdo Evidências</p>
                                    </x-spire::tab-panel>
                                </x-spire::tabs>
                            </div>
                        </div>
                    </div>

                    {{-- Accordion --}}
                    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl p-8 md:col-span-2 lg:col-span-3">
                        <h2 class="text-xl font-bold mb-6">📚 Accordion / FAQ</h2>
                        <x-spire::accordion id="faq-accordion" :multiple="false">
                            <x-spire::accordion-item name="item1" title="O que é Spire UI?" :open="true">
                                Spire UI é uma biblioteca JavaScript/TypeScript leve (~19KB gzip) que oferece 25
                                componentes
                                interativos e 9 utilities para projetos Laravel, substituindo Alpine.js com melhor
                                performance e tipagem.
                            </x-spire::accordion-item>
                            <x-spire::accordion-item name="item2" title="Quantos componentes estão incluídos?">
                                São 25 componentes: Button, Input, Modal, Dropdown, Table, Tabs, Accordion, Tooltip,
                                Select, MultiSelect, Drawer, Popover, Progress, Skeleton, Clipboard, Stepper,
                                FormValidator,
                                DatePicker, ColorPicker, RangeSlider, FileUpload, CommandPalette, VirtualScroll,
                                LazyLoad e InfiniteScroll.
                                Além de 9 utilities: debounce, throttle, events, http, currency, mask, perf, a11y e
                                onError.
                            </x-spire::accordion-item>
                            <x-spire::accordion-item name="item3" title="Preciso de Livewire ou Alpine.js?">
                                Não! Spire UI é 100% independente e não requer nenhuma outra biblioteca JavaScript.
                            </x-spire::accordion-item>
                        </x-spire::accordion>
                    </div>

                    {{-- Keyboard Shortcuts --}}
                    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl p-8 md:col-span-2 lg:col-span-3">
                        <h2 class="text-xl font-bold mb-6">⌨️ Atalhos de Teclado</h2>
                        <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">
                            Sistema de atalhos globais registráveis.
                        </p>
                        <div class="grid gap-4 md:grid-cols-3">
                            <div class="p-4 bg-gray-50 dark:bg-gray-900 rounded-xl">
                                <kbd
                                    class="px-2 py-1 bg-gray-200 dark:bg-gray-700 rounded text-sm font-mono">Ctrl+K</kbd>
                                <span class="ml-2 text-sm">Alternar tema</span>
                            </div>
                            <div class="p-4 bg-gray-50 dark:bg-gray-900 rounded-xl">
                                <kbd
                                    class="px-2 py-1 bg-gray-200 dark:bg-gray-700 rounded text-sm font-mono">Escape</kbd>
                                <span class="ml-2 text-sm">Fechar modais/drawers</span>
                            </div>
                            <div class="p-4 bg-gray-50 dark:bg-gray-900 rounded-xl">
                                <kbd
                                    class="px-2 py-1 bg-gray-200 dark:bg-gray-700 rounded text-sm font-mono">Tab</kbd>
                                <span class="ml-2 text-sm">Navegação focável</span>
                            </div>
                        </div>
                    </div>
                </div>
            </x-spire::tab-panel>
        </x-spire::tabs>

        <!-- Drawers -->
        <x-spire::drawer id="drawer-left" position="left" title="Menu">
            <nav class="space-y-2">
                <a href="#"
                    class="flex items-center gap-3 px-4 py-3 rounded-xl hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
                    <span>🏠</span> Home
                </a>
                <a href="#"
                    class="flex items-center gap-3 px-4 py-3 rounded-xl hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
                    <span>📦</span> Produtos
                </a>
                <a href="#"
                    class="flex items-center gap-3 px-4 py-3 rounded-xl hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
                    <span>📞</span> Contato
                </a>
                <a href="#"
                    class="flex items-center gap-3 px-4 py-3 rounded-xl hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
                    <span>⚙️</span> Configurações
                </a>
            </nav>
        </x-spire::drawer>

        <x-spire::drawer id="drawer-right" position="right" title="Carrinho" width="max-w-md">
            <div class="space-y-4">
                <div class="flex gap-4 p-4 bg-gray-50 dark:bg-gray-900 rounded-xl">
                    <div class="w-16 h-16 bg-gray-200 dark:bg-gray-700 rounded-lg"></div>
                    <div class="flex-1">
                        <h4 class="font-medium">Produto 1</h4>
                        <p class="text-sm text-gray-500">R$ 99,90</p>
                    </div>
                </div>
                <div class="flex gap-4 p-4 bg-gray-50 dark:bg-gray-900 rounded-xl">
                    <div class="w-16 h-16 bg-gray-200 dark:bg-gray-700 rounded-lg"></div>
                    <div class="flex-1">
                        <h4 class="font-medium">Produto 2</h4>
                        <p class="text-sm text-gray-500">R$ 149,90</p>
                    </div>
                </div>
            </div>
            <x-slot:footer>
                <div class="flex justify-between items-center">
                    <span class="text-lg font-bold">Total: R$ 249,80</span>
                    <x-spire::button class="from-green-500 to-green-600">Finalizar</x-spire::button>
                </div>
            </x-slot:footer>
        </x-spire::drawer>

        <!-- Modal de exemplo -->
        <x-spire::modal id="demo-modal">
            <p class="text-gray-600 dark:text-gray-300 mb-6">
                Este modal demonstra todas as funcionalidades de acessibilidade:
            </p>
            <ul class="text-sm text-gray-500 dark:text-gray-400 mb-6 space-y-2">
                <li>✅ Pressione <kbd class="px-2 py-1 bg-gray-200 dark:bg-gray-700 rounded">ESC</kbd> para fechar</li>
                <li>✅ Clique fora do modal para fechar</li>
                <li>✅ Trap de foco (Tab navega apenas dentro do modal)</li>
                <li>✅ Scroll da página bloqueado</li>
                <li>✅ Foco restaurado ao fechar</li>
            </ul>
            <div class="flex gap-4">
                <x-spire::button data-close>Fechar</x-spire::button>
                <x-spire::button id="confirm-action" class="from-green-500 to-green-600">Confirmar</x-spire::button>
            </div>
        </x-spire::modal>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            // ========== BUTTONS ==========
            document.getElementById('save')?.addEventListener('click', function() {
                this.$button.loading(true);
                setTimeout(() => {
                    this.$button.success();
                    SpireUI.toast.success('Dados salvos com sucesso!');
                }, 2000);
            });

            document.getElementById('send')?.addEventListener('click', function() {
                this.$button.loading(true);
                setTimeout(() => {
                    this.$button.success('Enviado!');
                    SpireUI.toast.success('Email enviado!');
                }, 1500);
            });

            document.getElementById('delete')?.addEventListener('click', async function() {
                const confirmed = await SpireUI.confirm({
                    title: 'Excluir registro?',
                    message: 'Esta ação não pode ser desfeita. Deseja continuar?',
                    confirmText: 'Sim, excluir',
                    cancelText: 'Cancelar',
                    confirmClass: 'bg-linear-to-r from-red-500 to-red-600'
                });

                if (confirmed) {
                    this.$button.loading(true);
                    setTimeout(() => {
                        this.$button.error('Excluído!');
                        SpireUI.toast.success('Registro excluído!');
                    }, 1000);
                }
            });

            // Escutar eventos customizados
            document.getElementById('save')?.addEventListener('button:loading', (e) => {
                console.log('Evento: button:loading', e.detail);
            });
            document.getElementById('save')?.addEventListener('button:success', (e) => {
                console.log('Evento: button:success', e.detail);
            });

            // ========== TOASTS ==========
            document.getElementById('toast-success')?.addEventListener('click', () => {
                SpireUI.toast.success('Operação realizada com sucesso! 🎉');
            });
            document.getElementById('toast-error')?.addEventListener('click', () => {
                SpireUI.toast.error('Ops! Algo deu errado 😕');
            });
            document.getElementById('toast-info')?.addEventListener('click', () => {
                SpireUI.toast.info('Você sabia? Este é um toast informativo 💡');
            });
            document.getElementById('toast-warning')?.addEventListener('click', () => {
                SpireUI.toast.warning('Atenção! Verifique os dados ⚠️');
            });
            document.getElementById('toast-queue')?.addEventListener('click', () => {
                SpireUI.toast.info('Toast 1 - Primeiro da fila');
                SpireUI.toast.success('Toast 2 - Segundo da fila');
                SpireUI.toast.warning('Toast 3 - Terceiro da fila');
                SpireUI.toast.error('Toast 4 - Quarto (aguardando)');
                SpireUI.toast.info('Toast 5 - Quinto (aguardando)');
            });
            document.getElementById('toast-clear')?.addEventListener('click', () => {
                SpireUI.toast.clear();
            });

            // ========== MODAL ==========
            document.getElementById('open-modal')?.addEventListener('click', () => {
                document.getElementById('demo-modal')?.$modal?.open();
            });
            document.getElementById('confirm-action')?.addEventListener('click', () => {
                document.getElementById('demo-modal')?.$modal?.close();
                SpireUI.toast.success('Ação confirmada!');
            });

            // ========== CONFIRM ==========
            document.getElementById('open-confirm')?.addEventListener('click', async () => {
                const confirmed = await SpireUI.confirm({
                    title: 'Confirmar Exclusão',
                    message: 'Você tem certeza que deseja excluir este item? Esta ação não pode ser desfeita.',
                    confirmText: 'Excluir',
                    cancelText: 'Cancelar',
                    confirmClass: 'bg-linear-to-r from-red-500 to-red-600'
                });

                if (confirmed) {
                    SpireUI.toast.success('Item excluído com sucesso!');
                } else {
                    SpireUI.toast.info('Ação cancelada');
                }
            });

            // ========== SELECT ==========
            document.getElementById('get-select-values')?.addEventListener('click', () => {
                const country = document.getElementById('country-select')?.$select?.value();
                const status = document.getElementById('status-select')?.$select?.value();
                SpireUI.toast.info(`País: ${country || 'não selecionado'}, Status: ${status}`);
            });

            // Escutar evento de mudança do select
            document.getElementById('country-select')?.addEventListener('select:change', (e) => {
                console.log('País selecionado:', e.detail.value);
            });

            // ========== MULTISELECT ==========
            document.getElementById('get-multiselect-values')?.addEventListener('click', () => {
                const techs = document.getElementById('tech-multiselect')?.$multiselect?.value() || [];
                if (techs.length === 0) {
                    SpireUI.toast.warning('Nenhuma tecnologia selecionada!');
                } else {
                    SpireUI.toast.success(`Tecnologias: ${techs.join(', ')}`);
                }
            });

            // Controle programático
            document.getElementById('add-fruit')?.addEventListener('click', () => {
                document.getElementById('fruits-multiselect')?.$multiselect?.add('apple');
                SpireUI.toast.success('Maçã adicionada!');
            });

            document.getElementById('remove-fruit')?.addEventListener('click', () => {
                document.getElementById('fruits-multiselect')?.$multiselect?.remove('banana');
                SpireUI.toast.info('Banana removida!');
            });

            document.getElementById('clear-fruits')?.addEventListener('click', () => {
                document.getElementById('fruits-multiselect')?.$multiselect?.clear();
                SpireUI.toast.info('Lista limpa!');
            });

            // Eventos do MultiSelect
            ['tech-multiselect', 'colors-multiselect', 'fruits-multiselect'].forEach(id => {
                const el = document.getElementById(id);

                el.addEventListener('multiselect:change', (e) => {
                    console.log(`[${id}] multiselect:change`, e.detail.values);
                });

                el.addEventListener('multiselect:added', (e) => {
                    console.log(`[${id}] multiselect:added`, e.detail.value);
                });

                el.addEventListener('multiselect:removed', (e) => {
                    console.log(`[${id}] multiselect:removed`, e.detail.value);
                });

                el.addEventListener('multiselect:max-reached', (e) => {
                    SpireUI.toast.warning(`Máximo de ${e.detail.max} itens atingido!`);
                });
            });

            // ========== TABS ==========
            document.getElementById('demo-tabs')?.addEventListener('tabs:changed', (e) => {
                console.log('Tab alterada:', e.detail.tab);
            });

            // ========== CONTEXT MENU ==========
            document.getElementById('demo-context')?.addEventListener('contextmenu:select', (e) => {
                const item = e.detail.item;
                switch (item.id) {
                    case 'edit':
                        SpireUI.toast.info('Editando...');
                        break;
                    case 'copy':
                        SpireUI.toast.success('Copiado para área de transferência!');
                        break;
                    case 'paste':
                        SpireUI.toast.info('Colado!');
                        break;
                    case 'share':
                        SpireUI.toast.info('Compartilhando...');
                        break;
                    case 'download':
                        SpireUI.toast.success('Download iniciado!');
                        break;
                    case 'delete':
                        SpireUI.toast.error('Item excluído!');
                        break;
                }
            });

            // ========== ACCORDION ==========
            document.getElementById('faq-accordion')?.addEventListener('accordion:toggled', (e) => {
                console.log('Accordion toggled:', e.detail);
            });

            // ========== DRAWER ==========
            // Botões para abrir drawers
            document.getElementById('open-drawer-left')?.addEventListener('click', () => {
                document.getElementById('drawer-left')?.$drawer?.open();
            });
            document.getElementById('open-drawer-right')?.addEventListener('click', () => {
                document.getElementById('drawer-right')?.$drawer?.open();
            });
            document.getElementById('open-drawer-top')?.addEventListener('click', () => {
                document.getElementById('drawer-top')?.$drawer?.open();
            });
            document.getElementById('open-drawer-bottom')?.addEventListener('click', () => {
                document.getElementById('drawer-bottom')?.$drawer?.open();
            });

            // Eventos do drawer
            ['drawer-left', 'drawer-right', 'drawer-top', 'drawer-bottom'].forEach(id => {
                const el = document.getElementById(id);
                if (!el) return;
                el.addEventListener('drawer:opened', () => console.log(`${id} aberto`));
                el.addEventListener('drawer:closed', () => console.log(`${id} fechado`));
            });

            // Drawer de carrinho
            document.getElementById('view-cart')?.addEventListener('click', () => {
                document.getElementById('cart-drawer')?.$drawer?.open();
            });

            // ========== PROGRESS ==========
            // Progress control buttons
            const progress1 = document.getElementById('progress-1');

            document.getElementById('progress-increment')?.addEventListener('click', () => {
                if (progress1?.$progress) {
                    progress1.$progress.increment(10);
                    SpireUI.toast.info(`Progresso: ${progress1.$progress.value()}%`);
                }
            });

            document.getElementById('progress-decrement')?.addEventListener('click', () => {
                if (progress1?.$progress) {
                    progress1.$progress.decrement(10);
                    SpireUI.toast.info(`Progresso: ${progress1.$progress.value()}%`);
                }
            });

            document.getElementById('progress-complete')?.addEventListener('click', () => {
                if (progress1?.$progress) {
                    progress1.$progress.complete();
                    SpireUI.toast.success('Progresso completo! 🎉');
                }
            });

            document.getElementById('progress-reset')?.addEventListener('click', () => {
                if (progress1?.$progress) {
                    progress1.$progress.reset();
                    SpireUI.toast.info('Progresso resetado');
                }
            });

            // Progresso dinâmico
            let progressValue = 0;
            document.getElementById('animate-progress')?.addEventListener('click', () => {
                const progressEl = document.getElementById('dynamic-progress');
                if (!progressEl) return;

                progressValue = 0;
                progressEl.$progress.value(0);

                const interval = setInterval(() => {
                    progressValue += 10;
                    progressEl.$progress.value(progressValue);

                    if (progressValue >= 100) {
                        clearInterval(interval);
                        SpireUI.toast.success('Upload concluído! 🎉');
                    }
                }, 300);
            });

            // ========== STEPPER ==========
            const stepperEl = document.getElementById('demo-stepper');
            if (stepperEl) {
                stepperEl.addEventListener('stepper:step-changed', (e) => {
                    console.log('Passo alterado:', e.detail);
                });

                stepperEl.addEventListener('stepper:completed', () => {
                    SpireUI.toast.success('Cadastro concluído! 🎉');
                });

                // Botões de navegação do stepper
                document.getElementById('stepper-prev')?.addEventListener('click', () => {
                    stepperEl.$stepper.prev();
                });

                document.getElementById('stepper-next')?.addEventListener('click', () => {
                    stepperEl.$stepper.next();
                });

                document.getElementById('stepper-finish')?.addEventListener('click', () => {
                    stepperEl.$stepper.complete();
                });
            }

            // ========== CLIPBOARD ==========
            // Eventos do clipboard
            document.querySelectorAll('[id^="clipboard-"]').forEach(el => {
                el.addEventListener('clipboard:copied', (e) => {
                    SpireUI.toast.success(`Copiado: ${e.detail.text.substring(0, 50)}...`);
                });
                el.addEventListener('clipboard:error', (e) => {
                    SpireUI.toast.error('Erro ao copiar: ' + e.detail.error);
                });
            });

            // ========== KEYBOARD SHORTCUTS ==========
            // Registrar atalhos globais de demonstração
            SpireUI.shortcuts.register('ctrl+shift+n', () => {
                SpireUI.toast.info('Atalho Ctrl+Shift+N pressionado!');
            });

            SpireUI.shortcuts.register('ctrl+shift+s', () => {
                document.getElementById('save')?.click();
            });

            SpireUI.shortcuts.register('escape', () => {
                // Fecha drawers abertos
                ['drawer-left', 'drawer-right', 'drawer-top', 'drawer-bottom', 'cart-drawer'].forEach(
                id => {
                    const el = document.getElementById(id);
                    if (el?.$drawer?.isOpen()) el.$drawer.close();
                });
            });

            // ========== FORMS TAB ==========
            // Form Validator
            document.getElementById('demo-form')?.addEventListener('form:valid', () => {
                SpireUI.toast.success('Formulário válido! ✅');
            });
            document.getElementById('demo-form')?.addEventListener('form:invalid', (e) => {
                SpireUI.toast.error(`${Object.keys(e.detail.errors).length} erro(s) encontrado(s)`);
            });

            // Input Masks
            const maskPhone = document.getElementById('mask-phone');
            const maskCnpj = document.getElementById('mask-cnpj');
            const maskCep = document.getElementById('mask-cep');
            const maskMoney = document.getElementById('mask-money');
            const maskCpf = document.getElementById('cpf-input');

            if (maskPhone) SpireUI.mask.apply(maskPhone, 'phone');
            if (maskCnpj) SpireUI.mask.apply(maskCnpj, 'cnpj');
            if (maskCep) SpireUI.mask.apply(maskCep, 'cep');
            if (maskMoney) SpireUI.mask.apply(maskMoney, 'money');
            if (maskCpf) SpireUI.mask.apply(maskCpf, 'cpf');

            // Range Slider events
            document.getElementById('volume-slider')?.addEventListener('range:change', (e) => {
                document.getElementById('volume-value').textContent = e.detail.value;
            });
            document.getElementById('price-slider')?.addEventListener('range:change', (e) => {
                document.getElementById('price-value').textContent = e.detail.value;
            });

            // File Upload events
            document.getElementById('demo-upload')?.addEventListener('upload:files-added', (e) => {
                SpireUI.toast.success(`${e.detail.files.length} arquivo(s) adicionado(s)`);
            });

            // Checkbox events
            document.querySelectorAll('[data-v="checkbox"]').forEach(checkbox => {
                checkbox.addEventListener('change', (e) => {
                    const label = e.target.closest('[data-checkbox-container]')?.querySelector('label')?.textContent?.trim();
                    const isChecked = e.target.checked;
                    console.log(`Checkbox "${label}" ${isChecked ? 'marcado' : 'desmarcado'}`);
                });
            });

            // ========== PICKERS TAB ==========
            // DatePicker events
            document.getElementById('birth-date')?.addEventListener('datepicker:change', (e) => {
                SpireUI.toast.info(`Data selecionada: ${e.detail.formatted}`);
            });

            // ColorPicker events - update both color pickers to reflect on preview
            document.getElementById('main-color')?.addEventListener('colorpicker:change', (e) => {
                document.getElementById('color-preview').style.background = e.detail.color;
            });
            document.getElementById('bg-color')?.addEventListener('colorpicker:change', (e) => {
                document.getElementById('color-preview').style.borderColor = e.detail.color;
            });

            // Command Palette
            document.getElementById('open-command')?.addEventListener('click', () => {
                SpireUI.command.open();
            });

            // Register commands
            SpireUI.command.register({
                id: 'toggle-theme',
                title: 'Alternar Tema',
                description: 'Muda entre tema claro e escuro',
                icon: '🌙',
                category: 'Aparência',
                handler: () => {
                    document.documentElement.classList.toggle('dark');
                    SpireUI.toast.success('Tema alterado!');
                }
            });
            SpireUI.command.register({
                id: 'new-project',
                title: 'Novo Projeto',
                description: 'Criar um novo projeto',
                icon: '📁',
                category: 'Projetos',
                handler: () => SpireUI.toast.info('Criando novo projeto...')
            });
            SpireUI.command.register({
                id: 'settings',
                title: 'Configurações',
                description: 'Abrir configurações',
                icon: '⚙️',
                category: 'Sistema',
                handler: () => SpireUI.toast.info('Abrindo configurações...')
            });

            // ========== UTILITIES TAB ==========
            // Debounce demo
            const debouncedSearch = SpireUI.debounce((value) => {
                document.getElementById('debounce-result').textContent = value || '-';
            }, 300);

            document.getElementById('debounce-input')?.addEventListener('input', (e) => {
                debouncedSearch(e.target.value);
            });

            // Throttle demo
            let throttleCount = 0;
            const throttledClick = SpireUI.throttle(() => {
                throttleCount++;
                document.getElementById('throttle-count').textContent = throttleCount;
            }, 1000);

            document.getElementById('throttle-btn')?.addEventListener('click', throttledClick);

            // Event Bus demo
            SpireUI.events.on('demo:event', (data) => {
                const log = document.getElementById('event-log');
                if (log) {
                    const p = document.createElement('p');
                    p.textContent = `[${new Date().toLocaleTimeString()}] ${data.message}`;
                    log.appendChild(p);
                    log.scrollTop = log.scrollHeight;
                }
            });

            document.getElementById('emit-event')?.addEventListener('click', () => {
                SpireUI.events.emit('demo:event', {
                    message: 'Evento emitido!'
                });
            });

            // HTTP Client demo
            document.getElementById('http-get')?.addEventListener('click', async () => {
                const result = document.getElementById('http-result');
                result.innerHTML = '<p class="text-blue-500">Carregando...</p>';

                try {
                    // Using a public API for demo
                    const data = await SpireUI.http.get('https://jsonplaceholder.typicode.com/posts/1');
                    result.innerHTML = `<pre>${JSON.stringify(data, null, 2)}</pre>`;
                } catch (err) {
                    result.innerHTML = `<p class="text-red-500">Erro: ${err.message}</p>`;
                }
            });

            // Currency demo
            document.getElementById('currency-input')?.addEventListener('input', (e) => {
                const value = parseFloat(e.target.value) || 0;
                document.getElementById('currency-brl').textContent = SpireUI.currency.format(value);
                document.getElementById('currency-usd').textContent = SpireUI.currency.format(value, {
                    locale: 'en-US',
                    currency: 'USD'
                });
            });

            // Performance demo
            document.getElementById('perf-test')?.addEventListener('click', () => {
                SpireUI.perf.mark('test-start');

                // Simulate some work
                let sum = 0;
                for (let i = 0; i < 1000000; i++) {
                    sum += Math.sqrt(i);
                }

                SpireUI.perf.mark('test-end');
                const duration = SpireUI.perf.measure('test-duration', 'test-start', 'test-end');

                document.getElementById('perf-result').innerHTML = `
                    <p class="text-green-500">✓ Cálculo concluído</p>
                    <p>Tempo: <strong>${duration.toFixed(2)}ms</strong></p>
                    <p class="text-gray-400">1M iterações sqrt()</p>
                `;
            });

            // A11y demo
            document.getElementById('a11y-announce')?.addEventListener('click', () => {
                SpireUI.a11y.announce('Esta é uma mensagem para leitores de tela!', 'polite');
                SpireUI.toast.info('Anúncio enviado para screen readers');
            });

            // Ctrl+K to open command palette
            SpireUI.shortcuts.register('ctrl+k', () => {
                SpireUI.command.open();
            });

            // ========== DYNAMIC TABS DEMO ==========
            let tabCounter = 5;
            const dynamicTabs = document.getElementById('dynamic-tabs');

            // Disable Tab 2
            document.getElementById('tabs-disable-2')?.addEventListener('click', () => {
                dynamicTabs?.$tabs?.disable('tab2');
                SpireUI.toast.warning('Tab 2 desabilitada');
            });

            // Enable Tab 2
            document.getElementById('tabs-enable-2')?.addEventListener('click', () => {
                dynamicTabs?.$tabs?.enable('tab2');
                SpireUI.toast.success('Tab 2 habilitada');
            });

            // Hide Tab 3
            document.getElementById('tabs-hide-3')?.addEventListener('click', () => {
                dynamicTabs?.$tabs?.hide('tab3');
                SpireUI.toast.info('Tab 3 ocultada');
            });

            // Show Tab 3
            document.getElementById('tabs-show-3')?.addEventListener('click', () => {
                dynamicTabs?.$tabs?.unhide('tab3');
                SpireUI.toast.success('Tab 3 visível');
            });

            // Add new tab
            document.getElementById('tabs-add')?.addEventListener('click', () => {
                const tabName = `tab${tabCounter}`;
                dynamicTabs?.$tabs?.add({
                    name: tabName,
                    label: `Tab ${tabCounter}`,
                    content: `<p class="text-gray-600 dark:text-gray-400">Conteúdo da Tab ${tabCounter} (criada dinamicamente)</p>`
                });
                SpireUI.toast.success(`Tab ${tabCounter} adicionada`);
                tabCounter++;
            });

            // Remove last tab
            document.getElementById('tabs-remove')?.addEventListener('click', () => {
                const tabs = dynamicTabs?.$tabs?.list();
                if (tabs && tabs.length > 1) {
                    const lastTab = tabs[tabs.length - 1];
                    dynamicTabs?.$tabs?.remove(lastTab.name);
                    SpireUI.toast.info(`Tab "${lastTab.label}" removida`);
                } else {
                    SpireUI.toast.warning('Deve haver ao menos uma tab');
                }
            });

            // ========== TABS HIGHLIGHT (Validação) ==========
            // Highlight com erro
            document.getElementById('tabs-error')?.addEventListener('click', () => {
                dynamicTabs?.$tabs?.highlight('tab1', {
                    type: 'error',
                    badge: 3
                });
                SpireUI.toast.error('Tab 1 marcada com erro (3 campos inválidos)');
            });

            // Highlight com warning
            document.getElementById('tabs-warning')?.addEventListener('click', () => {
                dynamicTabs?.$tabs?.highlight('tab2', {
                    type: 'warning',
                    badge: 1
                });
                SpireUI.toast.warning('Tab 2 marcada com aviso');
            });

            // Highlight com sucesso
            document.getElementById('tabs-success')?.addEventListener('click', () => {
                dynamicTabs?.$tabs?.highlight('tab3', {
                    type: 'success'
                });
                SpireUI.toast.success('Tab 3 marcada como válida');
            });

            // Highlight pulsante (chamar atenção)
            document.getElementById('tabs-pulse')?.addEventListener('click', () => {
                dynamicTabs?.$tabs?.highlight('tab1', {
                    type: 'error',
                    pulse: true,
                    badge: '!'
                });
                SpireUI.toast.error('Tab 1 pulsando - atenção necessária!');
            });

            // Highlight com badge numérico
            document.getElementById('tabs-badge')?.addEventListener('click', () => {
                dynamicTabs?.$tabs?.highlight('tab1', {
                    type: 'error',
                    badge: 5
                });
                dynamicTabs?.$tabs?.highlight('tab2', {
                    type: 'warning',
                    badge: 2
                });
                dynamicTabs?.$tabs?.highlight('tab3', {
                    type: 'info',
                    badge: 1
                });
                SpireUI.toast.info('Múltiplas tabs com badges de erro');
            });

            // Limpar todos os highlights
            document.getElementById('tabs-clear')?.addEventListener('click', () => {
                dynamicTabs?.$tabs?.clearAllHighlights();
                SpireUI.toast.success('Todos os destaques removidos');
            });

            console.log('🚀 Spire UI 2025 Demo carregado!');
            console.log(
                'Atalhos: Ctrl+K (command palette), Ctrl+Shift+N (toast), Ctrl+Shift+S (salvar), Esc (fechar)');

            // ========== INTERFACE TAB ==========
            // Dropdown interactions
            document.getElementById('dropdown-toggle')?.addEventListener('click', () => {
                const dropdown = document.getElementById('demo-dropdown');
                if (dropdown?.$dropdown?.isOpen()) {
                    dropdown.$dropdown.close();
                } else {
                    dropdown.$dropdown.open();
                }
            });

            document.getElementById('demo-dropdown')?.addEventListener('dropdown:opened', () => {
                console.log('Dropdown aberto');
            });

            document.getElementById('demo-dropdown')?.addEventListener('dropdown:closed', () => {
                console.log('Dropdown fechado');
            });

            document.getElementById('demo-dropdown')?.addEventListener('dropdown:selected', (e) => {
                SpireUI.toast.info(`Selecionado: ${e.detail.item.label}`);
            });

            // Tooltip demo - mostrar/ocultar programaticamente
            document.getElementById('show-tooltip')?.addEventListener('click', () => {
                const tooltip = document.getElementById('demo-tooltip');
                tooltip?.$tooltip?.show();
                setTimeout(() => tooltip?.$tooltip?.hide(), 2000);
            });

            // Badge animations
            document.getElementById('animate-badge')?.addEventListener('click', () => {
                const badge = document.getElementById('animated-badge');
                badge.classList.add('animate-pulse');
                setTimeout(() => badge.classList.remove('animate-pulse'), 2000);
                SpireUI.toast.info('Badge animado!');
            });

            // Skeleton loading simulation
            document.getElementById('load-skeleton')?.addEventListener('click', () => {
                const skeleton = document.getElementById('demo-skeleton');
                skeleton.classList.remove('hidden');
                setTimeout(() => {
                    skeleton.classList.add('hidden');
                    SpireUI.toast.success('Conteúdo carregado!');
                }, 3000);
            });

            // Icon interactions
            document.getElementById('icon-heart')?.addEventListener('click', function() {
                this.classList.toggle('text-red-500');
                this.classList.toggle('text-gray-400');
                SpireUI.toast.info(this.classList.contains('text-red-500') ? '❤️ Curtido!' : '💔 Descurtido');
            });

            document.getElementById('icon-star')?.addEventListener('click', function() {
                const isFilled = this.querySelector('svg').classList.contains('fill-current');
                if (isFilled) {
                    this.querySelector('svg').classList.remove('fill-current');
                    this.querySelector('svg').classList.add('stroke-current');
                } else {
                    this.querySelector('svg').classList.add('fill-current');
                    this.querySelector('svg').classList.remove('stroke-current');
                }
                SpireUI.toast.info(isFilled ? '⭐ Desfavoritado' : '⭐ Favoritado');
            });

            // Form Group validation demo
            document.getElementById('validate-form-group')?.addEventListener('click', () => {
                const emailInput = document.getElementById('email-input');
                const passwordInput = document.getElementById('password-input');

                let hasErrors = false;

                // Validate email
                if (!emailInput.value || !emailInput.value.includes('@')) {
                    emailInput.closest('[x-data]').__x.$data.error = 'Email inválido';
                    hasErrors = true;
                } else {
                    emailInput.closest('[x-data]').__x.$data.error = null;
                }

                // Validate password
                if (!passwordInput.value || passwordInput.value.length < 6) {
                    passwordInput.closest('[x-data]').__x.$data.error = 'Senha deve ter pelo menos 6 caracteres';
                    hasErrors = true;
                } else {
                    passwordInput.closest('[x-data]').__x.$data.error = null;
                }

                if (hasErrors) {
                    SpireUI.toast.error('Corrija os erros no formulário');
                } else {
                    SpireUI.toast.success('Formulário válido!');
                }
            });

            // Form Group clear errors
            document.getElementById('clear-form-errors')?.addEventListener('click', () => {
                document.getElementById('email-input').closest('[x-data]').__x.$data.error = null;
                document.getElementById('password-input').closest('[x-data]').__x.$data.error = null;
                document.getElementById('email-input').value = '';
                document.getElementById('password-input').value = '';
                SpireUI.toast.info('Erros limpos');
            });
        });
    </script>
</x-layouts.app>
