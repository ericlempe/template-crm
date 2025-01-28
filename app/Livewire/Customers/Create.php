<?php

namespace App\Livewire\Customers;

use App\Models\Customer;
use Illuminate\View\View;
use Livewire\Attributes\{On, Rule};
use Livewire\Component;

class Create extends Component
{
    #[Rule(['required', 'min:3', 'max:255'])]
    public ?string $name = '';

    #[Rule(['required', 'email', 'unique:App\Models\Customer,email'])]
    public ?string $email = '';

    public ?string $phone = null;

    public bool $modal = false;

    public function render(): View
    {
        return view('livewire.customers.create');
    }

    public function save(): void
    {
        $this->validate();

        Customer::create([
            'type'  => 'customer',
            'name'  => $this->name,
            'email' => $this->email,
            'phone' => $this->phone,
        ]);

        $this->redirectRoute('customers');
    }

    #[On('customer::create')]
    public function open()
    {
        $this->resetErrorBag();
        $this->modal = true;
    }
}
