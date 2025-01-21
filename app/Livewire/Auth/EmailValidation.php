<?php

namespace App\Livewire\Auth;

use App\Events\SendNewCode;
use App\Models\User;
use App\Notifications\WelcomeNotification;
use App\Rules\CodeValidation;
use Carbon\Carbon;
use Illuminate\View\View;
use Livewire\Attributes\{Layout, On, Rule};
use Livewire\Component;

class EmailValidation extends Component
{
    #[Rule(new CodeValidation())]
    public ?string $code = null;

    public ?Carbon $dateResendAllowed = null;

    #[Layout('components.layouts.guest')]
    public function render(): View
    {
        return view('livewire.auth.email-validation')->title('Verify authentication code');
    }

    public function handle(): void
    {
        try {
            $this->validate();

            /* @var User $user */
            $user = auth()->user();

            $user->email_verified_at = now();
            $user->validation_code   = null;
            $user->save();

            $user->notify(new WelcomeNotification());

            $this->redirect(route('dashboard'));
        } catch (\Exception $e) {
            $this->addError('code', $e->getMessage());
        }
    }

    public function sendNewCode(): void
    {
        if ($this->dateResendAllowed && now()->isBefore($this->dateResendAllowed)) {
            $this->addError('dateResendNotAllowed', 'Wait 3 minutes for another attempt');

            return;
        }

        SendNewCode::dispatch(auth()->user());

        $this->dateResendAllowed = now()->addMinutes(3);
    }

    #[On('resend-allowed')]
    public function resendAllowed()
    {
        $this->dateResendAllowed = null;
    }
}
