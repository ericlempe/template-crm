<?php

namespace App\Livewire\Opportunities;

use App\Models\Opportunity;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\View\View;
use Livewire\Attributes\Computed;
use Livewire\Component;

/**
 * @property-read Collection|Opportunity[] $opportunities
 */
class Board extends Component
{
    public function render(): view
    {
        return view('livewire.opportunities.board');
    }

    #[Computed]
    public function opportunities(): Collection
    {
        return Opportunity::query()
            ->orderBy('status')
            ->get();
    }
}
