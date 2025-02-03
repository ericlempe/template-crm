<div>

    <x-header title="Customers" separator>
        <x-slot:actions>
            <x-button icon="o-plus" class="btn-primary" @click="$dispatch('customer::create')"/>
        </x-slot:actions>
    </x-header>

    <div class="mb-4 flex justify-between">
        <x-select :options="$this->filterPerPage" wire:model.live="perPage"/>
        <div class="flex space-x-4">
            <x-input
                icon="o-magnifying-glass"
                placeholder="Search.."
                wire:model.live.debounce="search"
                clearable
            />

            <x-dropdown label="Filters" class="btn-outline">

                <x-slot:trigger>
                    <x-button icon="o-funnel" class="btn-primary"/>
                </x-slot:trigger>

                <x-menu-item @click.stop="">
                    <x-toggle label="Archived" wire:model.live="show_archived"/>
                </x-menu-item>
            </x-dropdown>
        </div>
    </div>

    <x-table :headers="$this->headers" :rows="$this->items">
        <x-slot:empty>
            <x-icon name="o-face-frown" label="No records found"/>
        </x-slot:empty>
        @scope('header_id', $header)
        <x-table.th :$header name="id"/>
        @endscope

        @scope('header_name', $header)
        <x-table.th :$header name="name"/>
        @endscope

        @scope('header_email', $header)
        <x-table.th :$header name="email"/>
        @endscope

        @scope('header_created_at', $header)
        <x-table.th :$header name="created_at"/>
        @endscope

        @scope('cell_created_at', $customer)
        {{ $customer->created_at->format('d/m/Y') }}
        @endscope

        @scope('actions', $customer)

        <div class="flex space-x-1">
            <x-button
                id="btn-update-customer-{{ $customer->id }}"
                wire:key="btn-update-customer-{{ $customer->id }}"
                icon="o-pencil"
                @click="$dispatch('customer::update', { id: '{{ $customer->id }}' })"
                class="btn-sm btn-ghost"
                tooltip="{{ __('Edit Customer') }}"
                spinner
            />

            @unless($customer->trashed())
                <x-button
                    id="btn-archive-customer-{{ $customer->id }}"
                    wire:key="btn-archive-customer-{{ $customer->id }}"
                    icon="o-archive-box"
                    @click="$dispatch('customer::archive', { id: '{{ $customer->id }}' })"
                    class="btn-sm btn-ghost"
                    tooltip="{{ __('Archive') }}"
                    spinner
                />
            @else
                <x-button
                    id="btn-restore-customer-{{ $customer->id }}"
                    wire:key="btn-restore-customer-{{ $customer->id }}"
                    icon="o-arrow-path"
                    @click="$dispatch('customer::restore', { id: '{{ $customer->id }}' })"
                    class="btn-sm btn-ghost"
                    tooltip="{{ __('Restore') }}"
                    spinner
                />
            @endunless
        </div>
        @endscope
    </x-table>

    {{ $this->items->links(data: ['scrollTo' => false]) }}

    <livewire:customers.create/>
    <livewire:customers.update/>
    <livewire:customers.archive/>
    <livewire:customers.restore/>
</div>
