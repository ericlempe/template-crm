<?php

namespace App\Livewire\Admin\Users;

use App\Enums\Can;
use App\Models\{Permission, User};
use App\Support\Table\Header;
use App\Traits\Livewire\HasTable;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\{Builder, Collection};
use Livewire\Attributes\Computed;
use Livewire\{Attributes\On, Attributes\Rule, Component, WithPagination};

class Index extends Component
{
    use HasTable;
    use WithPagination;

    #[Rule('exists:permissions,id')]
    public array $search_permissions = [];

    public int $search_trash = 0;

    public Collection $permissions_to_search;

    public function mount(): void
    {
        $this->authorize(Can::BE_AN_ADMIN->value);
        $this->filterPermissions();
    }

    #[On('user::deleted')]
    #[On('user::restored')]
    public function render(): View
    {
        return view('livewire.admin.users.index');
    }

    public function query(): Builder
    {
        $this->validate();

        return User::query()
            ->with('permissions')
            ->when(
                $this->search_permissions,
                fn (Builder $q) => $q->whereHas('permissions', function (Builder $query) {
                    $query->whereIn('id', $this->search_permissions);
                })
            )
            ->when(
                $this->search_trash,
                fn (Builder $q) => $q->onlyTrashed()
            );
    }

    public function searchColumns(): array
    {
        return ['id', 'name', 'email'];
    }

    public function tableHeaders(): array
    {
        return [
            Header::make('id', '#'),
            Header::make('name', 'Name'),
            Header::make('email', 'Email'),
            Header::make('permissions', 'Permissions'),
            Header::make('created_at', 'Created at'),
        ];
    }

    public function filterPermissions(?string $value = null): void
    {
        $this->permissions_to_search = Permission::query()
            ->when(
                $value,
                fn (Builder $q) => $q->where('key', 'like', '%' . $value . '%')
            )
            ->orderBy('key')
            ->get();
    }

    #[Computed]
    public function filterShowDeletedUsers(): array
    {
        return [
            ['id' => 0, 'name' => 'No'],
            ['id' => 1, 'name' => 'Yes'],
        ];
    }

    public function destroy(int $id): void
    {
        $this->dispatch('user::deletion', userId: $id);
    }

    public function restore(int $id): void
    {
        $this->dispatch('user::restoration', userId: $id);
    }

    public function showUser(int $id): void
    {
        $this->dispatch('user::show', userId: $id);
    }

    public function impersonate(int $id): void
    {
        $this->dispatch('user::impersonation', userId: $id);
    }
}
