<?php

namespace App\Livewire\Opportunities;

use App\Models\Opportunity;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Collection as SupportCollection;
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

    public function handleStatusOrder(array $data): void
    {
        $order = $this->getItemsInOrder($data);
        $cases = $this->getSqlGroupCases($order);
        $this->updateOpportunityOrder($cases, $order);
    }

    private function updateOpportunityOrder(string $cases, SupportCollection $collection): void
    {
        $ids = $collection->pluck('ids')->flatten()->join(',');
        DB::transaction(function () use ($cases, $ids) {
            DB::table('opportunities')
                ->whereRaw("id IN ($ids)")
                ->update([
                    'status' => DB::raw("CASE $cases END"),
                ]);

            DB::table('opportunities')->update(['sort_order' => DB::raw("field(id, $ids)")]);
        });
    }

    private function getItemsInOrder(array $data): SupportCollection
    {
        $order = collect();

        collect($data)->each(function ($group) use ($order) {
            $order->push((object) [
                'group' => $group['value'],
                'ids'   => collect($group['items'])->map(fn ($item) => $item['value'])->values(),
            ]);
        });

        return $order;
    }

    public function getSqlGroupCases(SupportCollection $collection): string
    {
        $cases = '';

        foreach (['open', 'won', 'lost'] as $group) {
            $groupData = $collection->firstWhere('group', $group);

            if (count($groupData->ids) > 0) {
                $cases .= "WHEN id IN ({$groupData->ids->join(',')}) THEN '{$group}' ";
            }
        }

        return $cases;
    }

}
