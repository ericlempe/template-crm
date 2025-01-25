<?php

namespace App\Livewire\Customers;

use App\Models\Customer;
use App\Traits\Livewire\HasTable;
use Illuminate\Contracts\View\View;
use Illuminate\Pagination\LengthAwarePaginator;
use Livewire\Attributes\Computed;
use Livewire\{Attributes\On, Component, WithPagination};

/**
 * @property-read LengthAwarePaginator|Customer[] $customers
 * @property-read  array $headers
 */
class Index extends Component
{
    use HasTable;
    use WithPagination;

    #[On('customer::deleted')]
    #[On('customer::restored')]
    public function render(): View
    {
        return view('livewire.customers.index');
    }

    #[Computed]
    public function customers(): LengthAwarePaginator
    {
        return Customer::query()
            ->search($this->search, ['name', 'email'])
            ->orderBy($this->sortBy['column'], $this->sortBy['direction'])
            ->paginate($this->perPage);
    }

    #[Computed]
    protected function headers(): array
    {
        return [
            ['key' => 'id', 'label' => '#'],
            ['key' => 'name', 'label' => 'Name'],
            ['key' => 'email', 'label' => 'Email'],
            ['key' => 'phone', 'label' => 'Phone'],
            ['key' => 'created_at', 'label' => 'Created at'],
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
