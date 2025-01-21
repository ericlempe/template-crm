<?php

namespace App\Livewire\Auth;

use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Event;
use Illuminate\View\View;
use Livewire\Attributes\Rule;
use Livewire\Component;

class Register extends Component
{
    #[Rule('required')]
    public ?string $name = null;

    #[Rule(['required', 'email', 'unique:App\Models\User,email'])]
    public ?string $email = null;

    #[Rule(['required', 'min:6', 'max:8', 'confirmed'])]
    public ?string $password = null;

    #[Rule('required')]
    public ?string $password_confirmation = null;

    public function submit(): void
    {
        $this->validate();

        $user = User::query()->create([
            'name'     => $this->name,
            'email'    => $this->email,
            'password' => $this->password,
        ]);

        auth()->login($user);

        Event::dispatch(new Registered($user));

        $this->redirect(route('auth.email-validation'));
    }

    public function render(): View
    {
        return view('livewire.auth.register')
            ->title('Sign up to your account')
            ->layout('components.layouts.guest');
    }
}
