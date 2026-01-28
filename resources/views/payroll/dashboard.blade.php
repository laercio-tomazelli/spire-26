<x-layouts.app>
    <div class="min-h-screen bg-gray-50 dark:bg-gray-900">

        <div
            class="h-16 max-[80%] bg-white dark:bg-gray-800 mx-auto rounded-2xl flex flex-grid items-center justify-center gap-4 shadow-xl p-8 mt-8">
            <x-ui.dropdown trigger="Home">
                <x-slot name="triggerSlot">
                    <span
                        class="inline-flex items-center gap-2 px-3 py-2 text-sm font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-green-800 rounded-lg transition-colors">
                        <x-ui.icon name="home" size="md" class="w-5 h-5 text-current" />
                        Home
                        <x-ui.icon name="chevron-down" size="sm" class="w-4 h-4 text-current" />
                    </span>
                </x-slot>
                <x-ui.dropdown-item href="#">Dashboard</x-ui.dropdown-item>
                <x-ui.dropdown-item href="#">Settings</x-ui.dropdown-item>
                <x-ui.dropdown-item href="#">Profile</x-ui.dropdown-item>
            </x-ui.dropdown>

            <x-ui.dropdown trigger="Home">
                <x-slot name="triggerSlot">
                    <span
                        class="inline-flex items-center gap-2 px-3 py-2 text-sm font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-green-800 rounded-lg transition-colors">
                        <x-ui.icon name="wrench" size="md" class="w-5 h-5 text-current" />
                        Ordem de Serviço
                        <x-ui.icon name="chevron-down" size="sm" class="w-4 h-4 text-current" />
                    </span>
                </x-slot>
                <x-ui.dropdown-item href="#">Dashboard</x-ui.dropdown-item>
                <x-ui.dropdown-item href="#">Settings</x-ui.dropdown-item>
                <x-ui.dropdown-item href="#">Profile</x-ui.dropdown-item>
            </x-ui.dropdown>

            <x-ui.dropdown trigger="Home">
                <x-slot name="triggerSlot">
                    <span
                        class="inline-flex items-center gap-2 px-3 py-2 text-sm font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-green-800 rounded-lg transition-colors">
                        <x-ui.icon name="home" size="md" class="w-5 h-5 text-current" />
                        Home
                        <x-ui.icon name="chevron-down" size="sm" class="w-4 h-4 text-current" />
                    </span>
                </x-slot>
                <x-ui.dropdown-item href="#">Dashboard</x-ui.dropdown-item>
                <x-ui.dropdown-item href="#">Settings</x-ui.dropdown-item>
                <x-ui.dropdown-item href="#">Profile</x-ui.dropdown-item>
            </x-ui.dropdown>

            <x-ui.dropdown trigger="Home">
                <x-slot name="triggerSlot">
                    <span
                        class="inline-flex items-center gap-2 px-3 py-2 text-sm font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-green-800 rounded-lg transition-colors">
                        <x-ui.icon name="wrench" size="md" class="w-5 h-5 text-current" />
                        Ordem de Serviço
                        <x-ui.icon name="chevron-down" size="sm" class="w-4 h-4 text-current" />
                    </span>
                </x-slot>
                <x-ui.dropdown-item href="#">Dashboard</x-ui.dropdown-item>
                <x-ui.dropdown-item href="#">Settings</x-ui.dropdown-item>
                <x-ui.dropdown-item href="#">Profile</x-ui.dropdown-item>
            </x-ui.dropdown>

            <x-ui.dropdown trigger="Home">
                <x-slot name="triggerSlot">
                    <span
                        class="inline-flex items-center gap-2 px-3 py-2 text-sm font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-green-800 rounded-lg transition-colors">
                        <x-ui.icon name="home" size="md" class="w-5 h-5 text-current" />
                        Home
                        <x-ui.icon name="chevron-down" size="sm" class="w-4 h-4 text-current" />
                    </span>
                </x-slot>
                <x-ui.dropdown-item href="#">Dashboard</x-ui.dropdown-item>
                <x-ui.dropdown-item href="#">Settings</x-ui.dropdown-item>
                <x-ui.dropdown-item href="#">Profile</x-ui.dropdown-item>
            </x-ui.dropdown>

            <x-ui.dropdown trigger="Home">
                <x-slot name="triggerSlot">
                    <span
                        class="inline-flex items-center gap-2 px-3 py-2 text-sm font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-green-800 rounded-lg transition-colors">
                        <x-ui.icon name="wrench" size="md" class="w-5 h-5 text-current" />
                        Ordem de Serviço
                        <x-ui.icon name="chevron-down" size="sm" class="w-4 h-4 text-current" />
                    </span>
                </x-slot>
                <x-ui.dropdown-item href="#">Dashboard</x-ui.dropdown-item>
                <x-ui.dropdown-item href="#">Settings</x-ui.dropdown-item>
                <x-ui.dropdown-item href="#">Profile</x-ui.dropdown-item>
            </x-ui.dropdown>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl p-8 my-8">
            <h2 class="text-xl font-bold mb-6">📋 Dropdown</h2>
            <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">
                Menu dropdown com várias opções de posicionamento e estilo.
            </p>
            <div class="flex flex-wrap gap-4 items-start">
                {{-- Basic --}}
                <x-spire::dropdown>
                    <x-slot name="triggerSlot">
                        <span
                            class="inline-flex items-center gap-2 px-3 py-2 text-sm font-medium text-gray-700 dark:text-gray-300 hover:bg-yellow-200 dark:hover:bg-yellow-800 rounded-lg transition-colors">
                            Options
                            <x-ui.icon name="chevron-down" size="sm" class="w-4 h-4 text-current" />
                        </span>
                    </x-slot>
                    <x-spire::dropdown-item class="hover:bg-green-200"
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
                <x-spire::dropdown>


                    <x-slot name="triggerSlot">
                        <span
                            class="inline-flex items-center gap-2 px-3 py-2 text-sm font-medium text-gray-700 dark:text-gray-300 hover:bg-yellow-200 dark:hover:bg-yellow-800 rounded-lg transition-colors">
                            Account
                            <x-ui.icon name="chevron-down" size="sm" class="w-4 h-4 text-current" />
                        </span>
                    </x-slot>

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
                <x-spire::dropdown :hover="true">
                    <x-slot name="triggerSlot">
                        <span
                            class="inline-flex items-center gap-2 px-3 py-2 text-sm font-medium text-gray-700 dark:text-gray-300 hover:bg-yellow-200 dark:hover:bg-yellow-800 rounded-lg transition-colors">
                            Hover me
                            <x-ui.icon name="chevron-down" size="sm" class="w-4 h-4 text-current" />
                        </span>
                    </x-slot>
                    <x-spire::dropdown-item>Opens on hover</x-spire::dropdown-item>
                    <x-spire::dropdown-item>No click needed</x-spire::dropdown-item>
                    <x-spire::dropdown-item>Just hover!</x-spire::dropdown-item>
                </x-spire::dropdown>

                {{-- Custom Trigger --}}
                <x-spire::dropdown>
                    <x-slot name="triggerSlot">
                        <span
                            class="inline-flex items-center gap-2 px-3 py-2 text-sm font-medium text-gray-700 dark:text-gray-300 hover:bg-yellow-200 dark:hover:bg-yellow-800 rounded-lg transition-colors">
                            JS
                            <x-ui.icon name="chevron-down" size="sm" class="w-4 h-4 text-current" />
                        </span>
                    </x-slot>
                    <x-spire::dropdown-divider label="John Doe" />
                    <x-spire::dropdown-item href="#profile">View Profile</x-spire::dropdown-item>
                    <x-spire::dropdown-item href="#settings">Settings</x-spire::dropdown-item>
                    <x-spire::dropdown-divider />
                    <x-spire::dropdown-item :disabled="true">Admin Panel</x-spire::dropdown-item>
                    <x-spire::dropdown-item :danger="true">Logout</x-spire::dropdown-item>
                </x-spire::dropdown>
            </div>
        </div>

        <div class="max-w-sm mx-auto mt-10 p-6 bg-white rounded-lg shadow-lg">
            <h1 class="text-2xl font-bold mb-4 text-sky-600">Paper Effect Card</h1>
            <p class="text-gray-600">
                This element uses a white background and a large box shadow to simulate a
                piece of paper.
            </p>
        </div>

    </div>
</x-layouts.app>
