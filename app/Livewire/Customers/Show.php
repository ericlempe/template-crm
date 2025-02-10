<?php

namespace App\Livewire\Customers;

use App\Models\Customer;
use Illuminate\View\View;
use Livewire\Component;

class Show extends Component
{
    public Customer $customer;

    public string $tab = 'opportunities';

    public function mount(): void
    {
        abort_unless(in_array($this->tab, ['opportunities', 'notes', 'tasks']), 404);
    }

    public function render(): view
    {
        return view('livewire.customers.show');
    }
}
