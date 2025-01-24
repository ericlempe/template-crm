<?php

namespace App\Livewire\Customers;

use App\Models\Customer;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\{Builder};
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Computed;
use Livewire\{Attributes\On, Component, WithPagination};

/**
 * @property-read LengthAwarePaginator|Customer[] $customers
 * @property-read  array $headers
 */
class Index extends Component
{
    use WithPagination;

    public ?string $search = null;

    public array $sortBy = ['column' => 'name', 'direction' => 'asc'];

    public int $perPage = 15;

    public function updatedPerPage($value): void
    {
        $this->resetPage();
    }

    #[On('customer::deleted')]
    #[On('customer::restored')]
    public function render(): View
    {
        return view('livewire.customers.index');
    }

    #[Computed]
    public function customers(): LengthAwarePaginator
    {
        #$this->validate();

        return Customer::query()
            ->when(
                $this->search,
                fn (Builder $q) => $q
                    ->where(DB::raw('lower(name)'), 'like', '%' . strtolower($this->search) . '%')
                    ->orWHere(DB::raw('lower(email)'), 'like', '%' . strtolower($this->search) . '%')
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
            ['key' => 'phone', 'label' => 'Phone'],
            ['key' => 'created_at', 'label' => 'Created at'],
        ];
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

    public function destroy(int $id): void
    {
        $this->dispatch('customer::deletion', customerId: $id);
    }

    public function restore(int $id): void
    {
        $this->dispatch('customer::restoration', customerId: $id);
    }
}
