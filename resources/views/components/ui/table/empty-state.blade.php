@props([
    'title' => 'No records found',
    'description' => 'Try adjusting your search or filter criteria.',
    'icon' => null,
])

<tr>
    <td colspan="100" class="px-6 py-12">
        <div class="fi-ta-empty-state flex flex-col items-center justify-center text-center">
            @if($icon)
                <div class="mb-4 text-gray-400 dark:text-gray-500">
                    {!! $icon !!}
                </div>
            @else
                <div class="mb-4 rounded-full bg-gray-100 p-3 dark:bg-gray-800">
                    <svg class="h-6 w-6 text-gray-400 dark:text-gray-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M20.25 7.5l-.625 10.632a2.25 2.25 0 01-2.247 2.118H6.622a2.25 2.25 0 01-2.247-2.118L3.75 7.5m6 4.125l2.25 2.25m0 0l2.25 2.25M12 13.875l2.25-2.25M12 13.875l-2.25 2.25M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125z" />
                    </svg>
                </div>
            @endif

            <h3 class="text-base font-semibold text-gray-950 dark:text-white">
                {{ $title }}
            </h3>

            @if($description)
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                    {{ $description }}
                </p>
            @endif

            @isset($action)
                <div class="mt-4">
                    {{ $action }}
                </div>
            @endisset
        </div>
    </td>
</tr>
