@props([
    'status',
    'items'
])

<div>
    <x-header :title="ucfirst($status)" :subtitle="'Total ' . $items->count()  . ' opportunities'" size="text-xl"
              separator progress-indicator/>
    <div class="space-y-2 p-2" wire:sortable-group.item-group="{{ $status }}">
        @empty($items)
            <div wire:key="opportunity-null"
                 class="h-10 border-dashed border-gray-400 border-2 shadow rounded w-full flex items-center justify-center opacity-40">
                Empty List
            </div>
        @endempty
        @foreach($items as $item)
            <x-card
                class="hover:opacity-60 cursor-grab"
                wire:key="item-{{ $item->id }}"
                wire:sortable-group.item="{{ $item->id }}"
                wire:sortable.handle
            >
                {{ $item->title }}
            </x-card>
        @endforeach
    </div>
</div>
