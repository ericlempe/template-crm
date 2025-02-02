<?php

namespace App\Livewire\Opportunities;

use App\Models\Opportunity;
use App\Support\Table\Header;
use App\Traits\Livewire\HasTable;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Livewire\Attributes\On;
use Livewire\{Component, WithPagination};

class Index extends Component
{
    use HasTable;
    use WithPagination;

    public bool $show_archived = false;

    #[On('opportunity::reload')]
    public function render(): View
    {
        return view('livewire.opportunities.index');
    }

    public function query(): Builder
    {
        return Opportunity::query()->when(
            $this->show_archived,
            fn (Builder $q) => $q->onlyTrashed()
        );
    }

    public function searchColumns(): array
    {
        return ['title'];
    }

    public function tableHeaders(): array
    {
        return [
            Header::make('id', '#'),
            Header::make('title', 'Title'),
            Header::make('status', 'Status'),
            Header::make('amount', 'Amount'),
            Header::make('created_at', 'Created at'),
        ];
    }
}
