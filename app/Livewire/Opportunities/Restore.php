<?php

namespace App\Livewire\Opportunities;

use App\Models\Opportunity;
use Illuminate\View\View;
use Livewire\Attributes\On;
use Livewire\Component;
use Mary\Traits\Toast;

class Restore extends Component
{
    use Toast;

    public ?Opportunity $opportunity = null;

    public bool $modal = false;

    public function render(): view
    {
        return view('livewire.opportunities.restore');
    }

    public function restore(): void
    {
        $this->opportunity->restore();

        $this->reset('modal');
        $this->success('Opportunity restored successfully');
        $this->dispatch('opportunity::reload');
    }

    #[On('opportunity::restore')]
    public function confirmAction(int $id): void
    {
        $this->opportunity = Opportunity::withTrashed()->find($id);
        $this->modal       = true;
    }
}
