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
 * @property-read Collection|Opportunity[] $opens
 * @property-read Collection|Opportunity[] $wons
 * @property-read Collection|Opportunity[] $losts
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

    #[Computed]
    public function opens(): Collection
    {
        return $this->opportunities->where('status', 'open');
    }

    #[Computed]
    public function wons(): Collection
    {
        return $this->opportunities->where('status', 'won');
    }

    #[Computed]
    public function losts(): Collection
    {
        return $this->opportunities->where('status', 'lost');
    }

    public function updateOpportunityOrder($data)
    {
        $order = collect();

        collect($data)->each(function ($group) use ($order) {
            $values = collect($group['items'])->map(fn ($item) => $item['value'])->values();
            $order->push((object) [
                'group' => $group['value'],
                'ids'   => $values,
            ]);
        });

        $open = $order->firstWhere('group', 'open');
        $won  = $order->firstWhere('group', 'won');
        $lost = $order->firstWhere('group', 'lost');

        $cases = count($open->ids) > 0 ? "WHEN id IN ({$open->ids->join(',')}) THEN 'open' " : '';
        $cases .= count($won->ids) > 0 ? "WHEN id IN ({$won->ids->join(',')}) THEN 'won' " : '';
        $cases .= count($lost->ids) > 0 ? "WHEN id IN ({$lost->ids->join(',')}) THEN 'lost' " : '';

        $ids = $order->pluck('ids')->flatten()->join(',');

        DB::table('opportunities')
            ->whereRaw("id IN ($ids)")
            ->update([
                'status' => DB::raw("CASE $cases END"),
            ]);

        DB::table('opportunities')->update(['sort_order' => DB::raw("field(id, $ids)")]);
    }
}
