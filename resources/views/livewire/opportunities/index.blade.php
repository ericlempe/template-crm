<div>

    <x-header title="Opportunities" separator>
        <x-slot:actions>
            <x-button icon="o-plus" class="btn-primary" @click="$dispatch('opportunity::create')"/>
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
        @scope('header_id', $header)
        <x-table.th :$header name="id"/>
        @endscope

        @scope('header_title', $header)
        <x-table.th :$header name="title"/>
        @endscope

        @scope('header_status', $header)
        <x-table.th :$header name="status"/>
        @endscope

        @scope('header_amount', $header)
        <x-table.th :$header name="amount"/>
        @endscope

        @scope('header_created_at', $header)
        <x-table.th :$header name="created_at"/>
        @endscope

        @scope('cell_created_at', $opportunity)
        {{ $opportunity->created_at->format('d/m/Y') }}
        @endscope

        @scope('actions', $opportunity)

        <div class="flex space-x-1">
            <x-button
                id="btn-update-opportunity-{{ $opportunity->id }}"
                wire:key="btn-update-opportunity-{{ $opportunity->id }}"
                icon="o-pencil"
                @click="$dispatch('opportunity::update', { id: '{{ $opportunity->id }}' })"
                class="btn-sm btn-ghost"
                tooltip="{{ __('Edit Opportunity') }}"
                spinner
            />

            @unless($opportunity->trashed())
                <x-button
                    id="btn-archive-opportunity-{{ $opportunity->id }}"
                    wire:key="btn-archive-opportunity-{{ $opportunity->id }}"
                    icon="o-archive-box"
                    @click="$dispatch('opportunity::archive', { id: '{{ $opportunity->id }}' })"
                    class="btn-sm btn-ghost"
                    tooltip="{{ __('Archive') }}"
                    spinner
                />
            @else
                <x-button
                    id="btn-restore-opportunity-{{ $opportunity->id }}"
                    wire:key="btn-restore-opportunity-{{ $opportunity->id }}"
                    icon="o-arrow-path"
                    @click="$dispatch('opportunity::restore', { id: '{{ $opportunity->id }}' })"
                    class="btn-sm btn-ghost"
                    tooltip="{{ __('Restore') }}"
                    spinner
                />
            @endunless
        </div>
        @endscope
    </x-table>

    {{ $this->items->links(data: ['scrollTo' => false]) }}

    <livewire:opportunities.create/>
    <livewire:opportunities.update/>
    <livewire:opportunities.archive/>
    <livewire:opportunities.restore/>
</div>
