<?php

namespace App\Livewire\Auth;

use Illuminate\Support\Facades\{Auth, RateLimiter};
use Illuminate\Support\Str;
use Illuminate\View\View;
use Livewire\Attributes\Validate;
use Livewire\Component;

class Login extends Component
{
    #[Validate('required')]
    public ?string $email = null;

    #[Validate('required')]
    public ?string $password = null;

    public function render(): View
    {
        return view('livewire.auth.login')
            ->title('Sign in to your account')
            ->layout('components.layouts.guest');
    }

    public function login(): void
    {
        $this->validate();

        if ($this->ensureIsNotRateLimiting()) {
            return;
        }

        if (!Auth::attempt(['email' => $this->email, 'password' => $this->password])) {

            RateLimiter::hit($this->throttleKey());

            $this->addError('invalidCredentials', __('auth.failed'));

            return;
        }

        $this->redirect(route('dashboard'));
    }

    private function throttleKey(): string
    {
        return Str::lower($this->email) . '|' . request()->ip;
    }

    private function ensureIsNotRateLimiting(): bool
    {
        if (RateLimiter::tooManyAttempts($this->throttleKey(), 5)) {
            $this->addError('rateLimiter', __('auth.throttle', [
                'seconds' => RateLimiter::availableIn($this->throttleKey()),
            ]));

            return true;
        }

        return false;
    }
}
