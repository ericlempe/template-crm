<?php

namespace App\Livewire\Customers;

use App\Models\Customer;
use Illuminate\Validation\Rule;
use Livewire\Form as BaseForm;

class Form extends BaseForm
{
    public ?Customer $customer = null;

    public ?string $name = '';

    public ?string $email = '';

    public ?string $phone = null;

    public function rules(): array
    {
        return [
            'name'  => ['required', 'min:3', 'max:255'],
            'email' => [
                'required',
                'email',
                Rule::unique('customers', 'email')->ignore($this->customer?->id),
            ],
            'phone' => ['nullable'],
        ];
    }

    public function create(): void
    {
        $this->validate();

        Customer::create([
            'type'  => 'customer',
            'name'  => $this->name,
            'email' => $this->email,
            'phone' => $this->phone,
        ]);
    }

    public function update(): void
    {
        $this->validate();

        $this->customer->name  = $this->name;
        $this->customer->email = $this->email;
        $this->customer->phone = $this->phone;

        $this->customer->update();

    }

    public function setCustomer(Customer $customer): void
    {
        $this->customer = $customer;
        $this->name     = $customer->name;
        $this->email    = $customer->email;
        $this->phone    = $customer->phone;
    }
}
