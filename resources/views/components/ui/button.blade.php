<button {{ $attributes->merge(['class' => 'px-6 py-3 rounded-xl font-semibold text-white bg-gradient-to-r from-blue-600 to-purple-600 hover:shadow-lg transform hover:scale-105 transition disabled:opacity-60']) }} data-v="button">
    {{ $slot }}
</button>
