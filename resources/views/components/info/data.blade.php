@props([
    'label'
])

<div class="flex flex-col space-y-1">
    <p class="text-gray-500">{{ $label }}</p>
    <p class="text-white opacity-80">{{ $slot }}</p>
</div>
