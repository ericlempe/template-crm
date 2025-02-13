<?php

namespace App\Livewire\Customers\Tasks;

use App\Actions\DataSort;
use App\Models\{Customer, Task};
use Illuminate\Database\Eloquent\{Builder, Collection};
use Illuminate\View\View;
use Livewire\Attributes\{Computed, On};
use Livewire\Component;

class Index extends Component
{
    public Customer $customer;

    #[On('task::reload')]
    public function render(): view
    {
        return view('livewire.customers.tasks.index');
    }

    #[Computed]
    public function doneTasks(): Collection
    {
        return $this->customer->tasks()->done()->orderBy('sort_order')->get();
    }

    #[Computed]
    public function notDoneTasks(): Collection
    {
        return $this->customer->tasks()->notDone()->orderBy('sort_order')->get();
    }

    public function updateTaskOrder($data): void
    {
        (new DataSort($data, 'tasks', 'value'))->handle();
    }

    public function toggleCheck(int $id, string $status): void
    {
        Task::where('id', $id)
            ->when(
                $status === 'done',
                fn (Builder $q) => $q->update(['done_at' => now()]),
                fn (Builder $q) => $q->update(['done_at' => null])
            );
    }
}
