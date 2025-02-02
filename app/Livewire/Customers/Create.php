<?php

namespace App\Livewire\Customers;

use Illuminate\View\View;
use Livewire\Attributes\{On};
use Livewire\Component;
use Mary\Traits\Toast;

class Create extends Component
{
    use Toast;

    public Form $form;

    public bool $modal = false;

    public function render(): View
    {
        return view('livewire.customers.create');
    }

    public function save(): void
    {
        $this->form->create();
        $this->success('Customer created successfully');
        $this->reset('modal');
        $this->dispatch('customer::reload');
    }

    #[On('customer::create')]
    public function open()
    {
        $this->resetErrorBag();
        $this->modal = true;
    }
}
