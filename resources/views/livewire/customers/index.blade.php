<div x-data="{ showFilters: false, toggleFilters() { this.showFilters = !this.showFilters } }">
    <x-header title="Customers" separator/>

    <div x-show="showFilters" class="flex mb-14 items-center space-x-4">
    </div>

    <div class="mb-4 flex justify-between">
        <x-select :options="$this->filterPerPage" wire:model.live="perPage"/>
        <div class="flex space-x-4">
            <x-input
                icon="o-magnifying-glass"
                placeholder="Search.."
                wire:model.live.debounce="search"
                clearable
            />
            <x-button @click="toggleFilters()" label="Filters" icon="o-funnel" class="btn-primary"/>
        </div>
    </div>

    <x-table :headers="$this->headers" :rows="$this->customers" :sort-by="$sortBy" with-pagination>

        @scope('cell_created_at', $customer)
        {{ $customer->created_at->format('d/m/Y') }}
        @endscope

        @php
            /** @var \App\Models\Customer $customer */
        @endphp
        @scope('actions', $customer)

        <div class="flex space-x-1">
        </div>
        @endscope
    </x-table>
</div>
