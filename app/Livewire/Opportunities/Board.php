<?php

namespace App\Livewire\Opportunities;

use App\Models\Opportunity;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;
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
            ->orderByRaw("field(status, 'open', 'won', 'lost')")
            ->orderBy('sort_order')
            ->get();
    }

    public function updateOpportunityOrder($data)
    {

        $order = collect();

        foreach ($data as $group) {
            $order->push(
                collect($group['items'])
                    ->map(fn ($item) => $item['value'])
                    ->join(',')
            );
        }

        $open = $order[0];
        $won  = $order[1];
        $lost = $order[2];

        $ids = $order->join(',');

        DB::table('opportunities')
            ->whereRaw("id IN ($ids)")
            ->update([
                'status' => DB::raw('
            CASE
                WHEN id IN (' . $open . ") THEN 'open'
                WHEN id IN (" . $won . ") THEN 'won'
                WHEN id IN (" . $lost . ") THEN 'lost'
            END"),
            ]);

        DB::table('opportunities')->update(['sort_order' => DB::raw("field(id, $ids)")]);
    }
}
