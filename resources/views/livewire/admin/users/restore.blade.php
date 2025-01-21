<div>
    <x-modal
        wire:model="modal"
        class="backdrop-blur"
        persistent
    >
        <div class="flex justify-start">
            Are you sure you want to restore the user {{ $user?->name }}?
        </div>
        <x-slot:actions>
            <x-button label="Cancel" @click="$wire.modal = false"/>
            <x-button label="Confirm" class="btn-primary" wire:click="confirmRestoration"/>
        </x-slot:actions>
    </x-modal>
</div>
