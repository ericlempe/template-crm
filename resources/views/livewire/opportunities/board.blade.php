<div
    class="p-2 grid grid-cols-3 gap-4 h-full"
    wire:sortable-group="updateOpportunityOrder"
>
    @foreach($this->opportunities->groupBy('status') as $status => $opportunities)
        <div>
            <x-header :title="$status" :subtitle="'Total ' . $opportunities->count()  . ' opportunities'" size="text-xl"
                      separator progress-indicator/>
            <div class="space-y-2 p-2" wire:sortable-group.item-group="{{ $status }}">
                @foreach($opportunities as $opportunity)
                    <x-card
                        class="hover:opacity-60 cursor-grab"
                        wire:key="opportunity-{{ $opportunity->id }}"
                        wire:sortable-group.item="{{ $opportunity->id }}"
                        wire:sortable.handle
                    >
                        {{ $opportunity->title }}
                    </x-card>
                @endforeach
            </div>
        </div>
    @endforeach
</div>
