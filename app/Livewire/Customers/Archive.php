<?php

namespace App\Livewire\Customers;

use App\Models\Customer;
use Illuminate\View\View;
use Livewire\Attributes\{On};
use Livewire\Component;
use Mary\Traits\Toast;

class Archive extends Component
{
    use Toast;

    public ?Customer $customer = null;

    public bool $modal = false;

    public function render(): View
    {
        return view('livewire.customers.archive');
    }

    public function archive(): void
    {
        $this->customer->delete();

        $this->reset('modal');
        $this->success('Customer archived successfully');
        $this->dispatch('customer::reload');
    }

    #[On('customer::archive')]
    public function confirmAction(int $id)
    {
        $this->customer = Customer::findOrFail($id);
        $this->modal    = true;
    }
}
