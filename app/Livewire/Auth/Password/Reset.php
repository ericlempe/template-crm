<?php

namespace App\Livewire\Auth\Password;

use App\Models\User;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Facades\{DB, Hash, Password};
use Illuminate\Support\Str;
use Illuminate\View\View;
use Livewire\Attributes\{Computed, Rule};
use Livewire\Component;

class Reset extends Component
{
    #[Rule(['required'])]
    public ?string $token = null;

    #[Rule(['required', 'email', 'confirmed'])]
    public ?string $email = null;

    #[Rule(['required', 'email'])]
    public ?string $email_confirmation = null;

    #[Rule(['required', 'confirmed'])]
    public ?string $password = null;

    public ?string $password_confirmation = null;

    public function mount(?string $token = null, ?string $email = null): void
    {
        $this->token = request('token', $token);
        $this->email = request('email', $email);

        if ($this->tokenIsNotValid()) {
            session()->flash("status", "Token invalid");
            $this->redirectRoute('login');
        }
    }

    public function changePassword()
    {
        $this->validate();

        $status = Password::reset(
            $this->only('email', 'password', 'password_confirmation', 'token'),
            function (User $user, $password) {
                $user->password       = $password;
                $user->remember_token = Str::random(60);
                $user->save();

                event(new PasswordReset($user));
            }
        );

        session()->flash('status', __($status));

        if ($status !== Password::PASSWORD_RESET) {
            return;
        }

        $this->redirectRoute('login');
    }

    public function render(): view
    {
        return view('livewire.auth.password.reset')
            ->title('Reset Password')
            ->layout('components.layouts.guest');
    }

    private function tokenIsNotValid(): bool
    {
        $tokens = DB::table('password_reset_tokens')->get();

        foreach ($tokens as $t) {
            if (Hash::check($this->token, $t->token)) {
                return false;
            }
        }

        return true;
    }

    #[Computed]
    public function obfuscatedEmail(): string
    {
        return obfuscate_email($this->email);
    }
}
