<?php

namespace App\Livewire\Opportunities;

use App\Models\Opportunity;
use Illuminate\View\View;
use Livewire\Attributes\On;
use Livewire\Component;
use Mary\Traits\Toast;

class Update extends Component
{
    use Toast;

    public Form $form;

    public bool $modal = false;

    public function render(): view
    {
        return view('livewire.opportunities.update');
    }

    public function save(): void
    {
        $this->form->update();
        $this->success('Opportunity updated successfully');
        $this->reset('modal');
        $this->dispatch('opportunity::reload');
    }

    #[On('opportunity::update')]
    public function load(int $id)
    {
        $opportunity = Opportunity::find($id);
        $this->form->setOpportunity($opportunity);
        $this->resetErrorBag();
        $this->modal = true;
    }
}
