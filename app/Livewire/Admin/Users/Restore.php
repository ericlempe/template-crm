<?php

namespace App\Livewire\Admin\Users;

use App\Models\User;
use App\Notifications\UserRestoredNotification;
use Livewire\Attributes\{On, Validate};
use Livewire\Component;
use Mary\Traits\Toast;

class Restore extends Component
{
    use Toast;

    #[Validate(['accepted'])]
    public bool $confirmedRestoration = false;

    public ?User $user = null;

    public bool $modal = false;

    public function render()
    {
        return view('livewire.admin.users.restore');
    }

    public function restore(): void
    {
        $this->validate();

        $this->user->restore();
        $this->user->deleted_by  = null;
        $this->user->restored_at = now();
        $this->user->restored_by = auth()->user()->id;
        $this->user->save();

        $this->user->notify(new UserRestoredNotification());

        $this->reset('confirmedRestoration', 'modal');
        $this->success('User restored successfully');
        $this->dispatch('user::restored');
    }

    #[On('user::restore')]
    public function openConfimation(int $id): void
    {
        $this->user  = User::withTrashed()->select('id', 'name')->find($id);
        $this->modal = true;
    }

    public function confirmRestoration()
    {
        $this->confirmedRestoration = true;
        $this->restore();
    }
}
