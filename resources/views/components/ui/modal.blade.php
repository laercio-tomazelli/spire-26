<div {{ $attributes->merge(['class' => 'hidden fixed inset-0 z-50 flex items-center justify-center']) }} data-v="modal">
  <div class="absolute inset-0 bg-black/70" data-close></div>
  <div class="relative bg-white dark:bg-gray-900 rounded-2xl shadow-2xl max-w-2xl w-full mx-6 p-8">
    <div class="flex justify-between items-center mb-6">
      <h3 data-title class="text-2xl font-bold"></h3>
      <button data-close class="text-4xl text-gray-400 hover:text-gray-600">&times;</button>
    </div>
    <div data-body>{{ $slot }}</div>
  </div>
</div>
