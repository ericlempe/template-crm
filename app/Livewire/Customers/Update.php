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

    public ?Customer $customer = null;

    public bool $modal = false;

    public function render(): View
    {
        return view('livewire.customers.update');
    }

    public function rules(): array
    {
        return [
            'customer.name'  => ['required', 'min:3', 'max:255'],
            'customer.email' => ['required', 'email', 'max:255', 'unique:App\Models\Customer,email,' . $this->customer->id],
            'customer.phone' => ['nullable'],
        ];
    }

    public function save(): void
    {
        $this->validate();

        $this->customer->update();

        $this->success('Customer updated successfully');
        $this->reset('modal');
        $this->dispatch('customer::reload');
    }

    #[On('customer::update')]
    public function open()
    {
        $this->resetErrorBag();
        $this->modal = true;
    }
}
