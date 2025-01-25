<?php

namespace App\Traits\Livewire;

use Livewire\Attributes\Computed;

trait HasTable
{
    public ?string $search = null;

    public array $sortBy = ['column' => 'id', 'direction' => 'asc'];

    public int $perPage = 15;
    abstract protected function headers(): array;

    #[Computed]
    public function filterPerPage(): array
    {
        return [
            ['id' => 5, 'name' => '5'],
            ['id' => 15, 'name' => '15'],
            ['id' => 25, 'name' => '25'],
            ['id' => 50, 'name' => '50'],
        ];
    }

    public function updatedPerPage($value): void
    {
        $this->resetPage();
    }
}
