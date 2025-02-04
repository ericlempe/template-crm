<div>
    <x-drawer wire:model="modal" class="w-11/12 lg:w-1/3" title="Create Customer" right>
        <x-form wire:submit="save" id="form-opportunity-update">
            <x-choices
                label="Customer"
                wire:model="form.customer_id"
                :options="$form->customers"
                placeholder="Search a customer"
                no-result-text="No customer found"
                debounce="300ms"
                min-chars="3"
                single
                searchable
            />
            <x-input label="Title" placeholder="Enter title" wire:model="form.title"/>
            <x-select
                label="Status"
                :options="$this->status"
                option-value="id"
                option-label="name"
                placeholder="Select a status"
                wire:model="form.status"
            />
            <x-input label="Amount" placeholder="Enter amount" wire:model="form.amount" prefix="$" money/>
            <x-slot:actions>
                <x-button label="Close" @click="$wire.modal = false"/>
                <x-button label="Save" class="btn-primary" type="submit" spinner="save" form="form-opportunity-update"/>
            </x-slot:actions>
        </x-form>
    </x-drawer>
</div>
