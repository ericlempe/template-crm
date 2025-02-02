<?php

namespace App\Livewire\Customers;

use App\Models\Customer;
use Illuminate\View\View;
use Livewire\Attributes\{On};
use Livewire\Component;
use Mary\Traits\Toast;

class Update extends Component
{
    use Toast;

    public Form $form;

    public bool $modal = false;

    public function render(): View
    {
        return view('livewire.customers.update');
    }

    public function save(): void
    {
        $this->form->update();
        $this->success('Customer updated successfully');
        $this->reset('modal');
        $this->dispatch('customer::reload');
    }

    #[On('customer::update')]
    public function load(int $id)
    {
        $customer = Customer::find($id);
        $this->form->setCustomer($customer);
        $this->resetErrorBag();
        $this->modal = true;
    }
}
