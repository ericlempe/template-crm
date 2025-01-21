<div class="flex flex-col space-y-2 mb-3 px-3">
    <x-select class="select-sm" icon="o-user" :options="$this->users" wire:model="selectedUser" placeholder="Select and user"/>
    <x-button label="Login" class="btn-primary btn-sm" wire:click="login" />
</div>
