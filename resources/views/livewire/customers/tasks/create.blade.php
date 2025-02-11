<form class="pt-1 pb-3 flex items-start gap-2" wire:submit="save">

    <div class="w-full">
        <x-input
            wire:model="title"
            class="input-xs input-ghost"
            placeholder="{{ __('Write down you new task...') }}"
        />
    </div>
    <div>
        <x-button
            class="btn-xs btn-ghost"
            label="{{ __('Save') }}"
            type="submit"
            spinner="save"
        />
    </div>
</form>
