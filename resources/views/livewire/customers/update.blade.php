<div>
    <x-drawer wire:model="modal" class="w-11/12 lg:w-1/3" title="Create Customer" right>
        <x-form wire:submit="save">
            <x-input label="Name" placeholder="Enter name" wire:model="customer.name"/>
            <x-input label="Email" placeholder="Enter email" wire:model="customer.email"/>
            <x-input label="Phone" placeholder="Enter phone" wire:model="customer.phone"/>

            <x-slot:actions>
                <x-button label="Close" @click="$wire.modal = false"/>
                <x-button label="Save" class="btn-primary" type="submit" spinner="save"/>
            </x-slot:actions>
        </x-form>
    </x-drawer>
</div>
