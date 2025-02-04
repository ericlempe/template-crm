<?php

namespace App\Livewire\Opportunities;

use Illuminate\View\View;
use Livewire\Attributes\{Computed, On};
use Livewire\Component;
use Mary\Traits\Toast;

class Create extends Component
{
    use Toast;

    public Form $form;

    public bool $modal = false;

    public function render(): view
    {
        return view('livewire.opportunities.create');
    }

    public function save(): void
    {
        $this->form->create();
        $this->success('Opportunity created successfully');
        $this->reset('modal');
        $this->dispatch('opportunity::reload');
    }

    #[On('opportunity::create')]
    public function open()
    {
        $this->resetErrorBag();
        $this->form->searchCostumers();
        $this->modal = true;
    }

    #[Computed('status')]
    public function status(): array
    {
        return [
            ['id' => 'open', 'name' => 'Open'],
            ['id' => 'won', 'name' => 'Won'],
            ['id' => 'lost', 'name' => 'Lost'],
        ];
    }

    public function search(string $value = ''): void
    {
        $this->form->searchCostumers($value);
    }
}
