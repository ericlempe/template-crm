<?php

namespace App\Livewire\Opportunities;

use App\Models\Opportunity;
use Illuminate\View\View;
use Livewire\Attributes\On;
use Livewire\Component;
use Mary\Traits\Toast;

class Archive extends Component
{
    use Toast;

    public ?Opportunity $opportunity = null;

    public bool $modal = false;

    public function render(): view
    {
        return view('livewire.opportunities.archive');
    }

    public function archive(): void
    {
        $this->opportunity->delete();

        $this->reset('modal');
        $this->success('Opportunity archived successfully');
        $this->dispatch('opportunity::reload');
    }

    #[On('opportunity::archive')]
    public function confirmAction(int $id)
    {
        $this->opportunity = Opportunity::findOrFail($id);
        $this->modal       = true;
    }
}
