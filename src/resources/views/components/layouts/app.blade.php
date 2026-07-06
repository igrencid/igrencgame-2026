@props([
    'title' => null,
])

@include('layouts.public', [
    'title' => $title,
    'slot' => $slot,
])
