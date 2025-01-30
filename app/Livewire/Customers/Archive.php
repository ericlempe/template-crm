<?php

namespace App\Livewire\Customers;

use App\Models\Customer;
use Illuminate\View\View;
use Livewire\Attributes\{On, Rule};
use Livewire\Component;
use Mary\Traits\Toast;

class Archive extends Component
{
    use Toast;

    public ?Customer $customer = null;

    #[Rule(['accepted'])]
    public bool $confirmedArchiving = false;

    public bool $modal = false;

    public function render(): View
    {
        return view('livewire.customers.archive');
    }

    public function archive(): void
    {
        $this->validate();

        $this->customer->delete();

        $this->reset('confirmedArchiving', 'modal');
        $this->success('Customer archived successfully');
        $this->dispatch('customer::archived');
    }

    #[On('customer::archive')]
    public function openConfimation(int $id)
    {
        $this->customer = Customer::findOrFail($id);
        $this->modal    = true;
    }

    public function confirmArchiving()
    {
        $this->confirmedArchiving = true;
        $this->archive();
    }
}
