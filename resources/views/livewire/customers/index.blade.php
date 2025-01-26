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

    <x-table :headers="$this->headers" :rows="$this->items">
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
    </x-table>

    {{ $this->items->links(data: ['scrollTo' => false]) }}
</div>
