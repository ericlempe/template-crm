<?php

namespace App\Livewire\Auth;

use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Event;
use Illuminate\View\View;
use Livewire\Attributes\Validate;
use Livewire\Component;

class Register extends Component
{
    #[Validate('required')]
    public ?string $name = null;

    #[Validate(['required', 'email', 'unique:App\Models\User,email'])]
    public ?string $email = null;

    #[Validate(['required', 'min:6', 'max:8', 'confirmed'])]
    public ?string $password = null;

    #[Validate('required')]
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
