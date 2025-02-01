<?php

namespace App\Livewire\Customers;

use App\Models\Customer;
use Illuminate\View\View;
use Livewire\Attributes\{On};
use Livewire\Component;
use Mary\Traits\Toast;

class Restore extends Component
{
    use Toast;

    public ?Customer $customer = null;

    public bool $modal = false;

    public function render(): view
    {
        return view('livewire.customers.restore');
    }

    public function restore(): void
    {
        $this->customer->restore();

        $this->reset('modal');
        $this->success('Customer restored successfully');
        $this->dispatch('customer::reload');
    }

    #[On('customer::restore')]
    public function confirmAction(int $id): void
    {
        $this->customer = Customer::withTrashed()->find($id);
        $this->modal    = true;
    }
}
