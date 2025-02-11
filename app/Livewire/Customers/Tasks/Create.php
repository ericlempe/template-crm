<?php

namespace App\Livewire\Customers\Tasks;

use App\Models\Customer;
use Illuminate\View\View;
use Livewire\Attributes\Validate;
use Livewire\Component;
use Mary\Traits\Toast;

class Create extends Component
{
    use Toast;

    public Customer $customer;

    #[Validate(['required', 'string', 'max:255'])]
    public ?string $title = null;

    public function save(): void
    {
        $this->validate();

        $this->customer->tasks()->create([
            'title' => $this->title,
        ]);

        $this->success('Task created successfully');
        $this->reset('title');
        $this->dispatch('task::reload');
    }

    public function render(): view
    {
        return view('livewire.customers.tasks.create');
    }
}
