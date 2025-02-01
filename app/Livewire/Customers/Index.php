<?php

namespace App\Livewire\Customers;

use App\Models\Customer;
use App\Support\Table\Header;
use App\Traits\Livewire\HasTable;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Livewire\{Attributes\On, Component, WithPagination};

class Index extends Component
{
    use HasTable;
    use WithPagination;

    public bool $show_archived = false;

    #[On('customer::reload')]
    public function render(): View
    {
        return view('livewire.customers.index');
    }

    public function query(): Builder
    {
        return Customer::query()->when(
            $this->show_archived,
            fn (Builder $q) => $q->onlyTrashed()
        );
    }

    public function searchColumns(): array
    {
        return ['name', 'email'];
    }

    public function tableHeaders(): array
    {
        return [
            Header::make('id', '#'),
            Header::make('name', 'Name'),
            Header::make('email', 'Email'),
            Header::make('phone', 'Phone'),
            Header::make('created_at', 'Created at'),
        ];
    }
}
