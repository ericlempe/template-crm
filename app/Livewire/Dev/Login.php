<?php

namespace App\Livewire\Dev;

use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\View\View;
use Livewire\Attributes\Computed;
use Livewire\Component;

class Login extends Component
{
    public ?int $selectedUser = null;

    public function render(): view
    {
        return view('livewire.dev.login');
    }

    public function login(): void
    {
        auth()->loginUsingId($this->selectedUser);
        $this->redirect(route('dashboard'));
    }

    #[Computed]
    public function users(): Collection
    {
        return User::all();
    }
}
