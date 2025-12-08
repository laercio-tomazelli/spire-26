@props([
    'id' => null,
    'items' => []
])

@php
    $itemsJson = json_encode($items);
@endphp

<div 
    {{ $attributes }}
    data-v="contextmenu"
    data-context-items="{{ $itemsJson }}"
    @if($id) id="{{ $id }}" @endif
>
    {{ $slot }}
</div>
