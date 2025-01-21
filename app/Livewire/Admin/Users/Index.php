<?php

namespace App\Livewire\Admin\Users;

use App\Enums\Can;
use App\Models\{Permission, User};
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\{Builder, Collection};
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Computed;
use Livewire\{Attributes\On, Attributes\Rule, Component, WithPagination};

/**
 * @property-read LengthAwarePaginator|User[] $users
 * @property-read  array $headers
 */
class Index extends Component
{
    use WithPagination;

    public ?string $search = null;

    #[Rule('exists:permissions,id')]
    public array $search_permissions = [];

    public int $search_trash = 0;

    public Collection $permissions_to_search;

    public array $sortBy = ['column' => 'name', 'direction' => 'asc'];

    public int $perPage = 15;

    public function mount(): void
    {
        $this->authorize(Can::BE_AN_ADMIN->value);
        $this->filterPermissions();
    }

    public function updatedPerPage($value): void
    {
        $this->resetPage();
    }

    #[On('user::deleted')]
    #[On('user::restored')]
    public function render(): View
    {
        return view('livewire.admin.users.index');
    }

    #[Computed]
    public function users(): LengthAwarePaginator
    {
        $this->validate();

        return User::query()
            ->with('permissions')
            ->when(
                $this->search,
                fn (Builder $q) => $q
                    ->where(DB::raw('lower(name)'), 'like', '%' . strtolower($this->search) . '%')
                    ->orWHere(DB::raw('lower(email)'), 'like', '%' . strtolower($this->search) . '%')
            )
            ->when(
                $this->search_permissions,
                fn (Builder $q) => $q->whereHas('permissions', function (Builder $query) {
                    $query->whereIn('id', $this->search_permissions);
                })
            )
            ->when(
                $this->search_trash,
                fn (Builder $q) => $q->onlyTrashed()
            )
            ->orderBy($this->sortBy['column'], $this->sortBy['direction'])
            ->paginate($this->perPage);
    }

    #[Computed]
    public function headers(): array
    {
        return [
            ['key' => 'id', 'label' => '#'],
            ['key' => 'name', 'label' => 'Name'],
            ['key' => 'email', 'label' => 'Email'],
            ['key' => 'created_at', 'label' => 'Created at'],
            ['key' => 'permissions', 'label' => 'Permissions', 'sortable' => false],
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
    public function filterPerPage(): array
    {
        return [
            ['id' => 5, 'name' => '5'],
            ['id' => 15, 'name' => '15'],
            ['id' => 25, 'name' => '25'],
            ['id' => 50, 'name' => '50'],
        ];
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
