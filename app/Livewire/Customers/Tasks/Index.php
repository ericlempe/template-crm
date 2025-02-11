<?php

namespace App\Livewire\Customers\Tasks;

use App\Models\{Customer};
use Illuminate\Database\Eloquent\Collection;
use Illuminate\View\View;
use Livewire\Attributes\Computed;
use Livewire\Component;

class Index extends Component
{
    public Customer $customer;

    public Collection $tasks;

    public function mount()
    {
        $this->tasks = $this->customer->tasks()->get();
    }

    public function render(): view
    {
        return view('livewire.customers.tasks.index');
    }

    #[Computed]
    public function doneTasks()
    {
        return $this->tasks->whereNotNull('done_at');
    }

    #[Computed]
    public function notDoneTasks()
    {
        return $this->tasks->whereNull('done_at');
    }
}
