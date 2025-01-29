<?php

namespace App\Livewire\Customers;

use App\Models\Customer;
use Illuminate\View\View;
use Livewire\Attributes\Rule;
use Livewire\Component;

class Archive extends Component
{
    public ?Customer $customer = null;

    #[Rule(['accepted'])]
    public bool $confirmedArchiving = false;

    public function render(): View
    {
        return view('livewire.customers.archive');
    }

    public function archive(): void
    {
        $this->validate();

        $this->customer->delete();
        $this->dispatch('customer::archived');
    }
}
