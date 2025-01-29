<?php

namespace App\Livewire\Admin\Users;

use App\Enums\Can;
use App\Models\{Permission, User};
use App\Support\Table\Header;
use App\Traits\Livewire\HasTable;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\{Builder, Collection};
use Livewire\{Attributes\On, Component, WithPagination};

class Index extends Component
{
    use HasTable;
    use WithPagination;

    public ?int $search_permissions = null;

    public bool $showDeleted = false;

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
        return User::query()
            ->with('permissions')
            ->when(
                $this->search_permissions,
                fn (Builder $q) => $q->whereHas('permissions', function (Builder $query) {
                    $query->where('id', $this->search_permissions);
                })
            )
            ->when(
                $this->showDeleted,
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
