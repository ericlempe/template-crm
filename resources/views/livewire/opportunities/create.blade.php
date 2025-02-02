<div>
    <x-drawer wire:model="modal" class="w-11/12 lg:w-1/3" title="Create Customer" right>
        <x-form wire:submit="save" id="form-opportunity-create">
            <x-input label="Title" placeholder="Enter title" wire:model="form.title"/>
            <x-select
                label="Status"
                :options="[[ 'id' => 'open', 'name' => 'Open' ], [ 'id' => 'won', 'name' => 'Won'], ['id' => 'lost', 'name' => 'Lost']]"
                option-value="id"
                option-label="name"
                placeholder="Select a status"
                wire:model="form.status"
            />
            <x-input label="Amount" placeholder="Enter amount" wire:model="form.amount"/>
            <x-slot:actions>
                <x-button label="Close" @click="$wire.modal = false"/>
                <x-button label="Save" class="btn-primary" type="submit" spinner="save" form="form-opportunity-create"/>
            </x-slot:actions>
        </x-form>
    </x-drawer>
</div>
