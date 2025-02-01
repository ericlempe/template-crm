<?php

namespace App\Livewire\Customers;

use App\Models\Customer;
use Illuminate\View\View;
use Livewire\Attributes\{On, Rule};
use Livewire\Component;
use Mary\Traits\Toast;

class Restore extends Component
{
    use Toast;

    #[Rule(['accepted'])]
    public bool $confirmed = false;

    public ?Customer $customer = null;

    public bool $modal = false;

    public function render(): view
    {
        return view('livewire.customers.restore');
    }

    public function restore(): void
    {
        $this->validate();
        $this->customer->restore();

        $this->reset('confirmed', 'modal');
        $this->success('Customer restored successfully');
        $this->dispatch('customer::restored');
    }

    #[On('customer::restore')]
    public function openModal(int $id): void
    {
        $this->customer = Customer::withTrashed()->find($id);
        $this->modal    = true;
    }

    public function confirmAction()
    {
        $this->confirmed = true;
        $this->restore();
    }
}
