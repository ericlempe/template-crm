<?php

namespace App\Livewire\Customers\Tasks;

use App\Models\{Customer};
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;
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

    public function updateTaskOrder($data)
    {
        $orders = collect($data)->pluck('value')->join(',');
        DB::table('tasks')->update(['sort_order' => DB::raw("FIELD(id, $orders)")]);
    }
}
