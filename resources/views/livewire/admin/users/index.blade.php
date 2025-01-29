@use('App\Models\User')
@use('Illuminate\Support\Str')
<div>
    <x-header title="Users" separator/>

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

            <x-dropdown label="Filters" class="btn-outline">
                <x-slot:trigger>
                    <x-button icon="o-funnel" class="btn-primary"/>
                </x-slot:trigger>

                <x-menu-item @click.stop="">

                    <x-select
                        class="select-sm"
                        label="Permissions"
                        :options="$permissions_to_search"
                        option-label="key"
                        placeholder="All"
                        wire:model.live="search_permissions"/>
                </x-menu-item>

                <x-menu-item @click.stop="">
                    <x-toggle label="Deleted" wire:model.live="showDeleted"/>
                </x-menu-item>
            </x-dropdown>
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

        @scope('cell_permissions', $user)
        @foreach($user->permissions as $permission)
            <x-badge :value="Str::ucfirst($permission->key)" class="badge-info"/>
        @endforeach
        @endscope

        @scope('cell_created_at', $user)
        {{ $user->created_at->format('d/m/Y') }}
        @endscope

        @php
            /** @var User $user */
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

    {{ $this->items->links(data: ['scrollTo' => false]) }}

    <livewire:admin.users.delete/>
    <livewire:admin.users.restore/>
    <livewire:admin.users.show/>
    <livewire:admin.users.impersonate/>
</div>
