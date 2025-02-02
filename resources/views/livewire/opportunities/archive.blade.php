<div>
    <x-modal
        wire:model="modal"
        class="backdrop-blur"
        persistent
    >
        <div class="flex justify-start">
            Are you sure you want to archive the opportunity {{ $opportunity?->name }}?
        </div>
        <x-slot:actions>
            <x-button label="Cancel" @click="$wire.modal = false"/>
            <x-button label="Confirm" class="btn-primary" wire:click="archive"/>
        </x-slot:actions>
    </x-modal>
</div>
