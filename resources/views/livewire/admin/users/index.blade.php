<div x-data="{ showFilters: false, toggleFilters() { this.showFilters = !this.showFilters } }">
    <x-header title="Users" separator/>

    <div x-show="showFilters" class="flex mb-14 items-center space-x-4">
        <x-choices
            Label="Search by permissions"
            wire:model.live="search_permissions"
            :options="$permissions_to_search"
            option-label="key"
            search-function="filterPermissions"
            searchable
            multiple
            no-result-text="Nothing here"
        />
        <x-select label="Show deleted users" :options="$this->filterShowDeletedUsers" wire:model.live="search_trash"/>
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

    <x-table :headers="$this->headers" :rows="$this->users" :sort-by="$sortBy" with-pagination>
        @scope('cell_permissions', $user)
        @foreach($user->permissions as $permission)
            <x-badge :value="\Illuminate\Support\Str::ucfirst($permission->key)" class="badge-info"/>
        @endforeach
        @endscope

        @scope('cell_created_at', $user)
        {{ $user->created_at->format('d/m/Y') }}
        @endscope

        @php
            /** @var \App\Models\User $user */
        @endphp
        @scope('actions', $user)

        <div class="flex space-x-1">
            <x-button
                id="btn-show-user-{{ $user->id }}"
                wire:key="btn-show-user-{{ $user->id }}"
                icon="o-pencil"
                wire:click="showUser('{{ $user->id }}')"
                class="btn-sm btn-ghost"
                tooltip="{{ __('Show details') }}"
                spinner
            />

            @unless($user->trashed())
                <x-button
                    id="btn-delete-user-{{ $user->id }}"
                    wire:key="btn-delete-user-{{ $user->id }}"
                    icon="o-trash"
                    wire:click="destroy('{{ $user->id }}')"
                    class="btn-sm btn-ghost"
                    tooltip="{{ __('Delete') }}"
                    spinner
                    :disabled="$user->is(auth()->user())"
                />

                 <x-button
                    id="btn-impersonate-user-{{ $user->id }}"
                    wire:key="btn-impersonate-user-{{ $user->id }}"
                    icon="o-eye"
                    wire:click="impersonate('{{ $user->id }}')"
                    class="btn-sm btn-ghost"
                    tooltip="{{ __('Impersonate') }}"
                    spinner
                    :disabled="$user->is(auth()->user())"
                />
            @else
                <x-button
                    id="btn-restore-user-{{ $user->id }}"
                    wire:key="btn-restore-user-{{ $user->id }}"
                    icon="o-arrow-path"
                    wire:click="restore('{{ $user->id }}')"
                    class="btn-sm btn-ghost"
                    tooltip="{{ __('Restore') }}"
                    spinner
                />
            @endunless
        </div>
        @endscope
    </x-table>

    <livewire:admin.users.delete/>
    <livewire:admin.users.restore/>
    <livewire:admin.users.show/>
    <livewire:admin.users.impersonate/>
</div>
