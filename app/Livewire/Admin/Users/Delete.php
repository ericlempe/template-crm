<?php

namespace App\Livewire\Admin\Users;

use App\Models\User;
use App\Notifications\UserDeletedNotification;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\{On, Rule};
use Livewire\Component;
use Mary\Traits\Toast;

class Delete extends Component
{
    use Toast;

    #[Rule(['accepted'])]
    public bool $confirmedDeletion = false;

    public ?User $user = null;

    public bool $modal = false;

    public function render(): view
    {
        return view('livewire.admin.users.delete');
    }

    public function destroy(): void
    {
        $this->validate();

        if ($this->user->is(auth()->user())) {
            $this->addError('InvalidUserLoggedDeletion', 'The logged-in user cannot be removed');
            $this->reset('confirmedDeletion', 'modal');

            return;
        }

        $this->user->delete();
        $this->user->deleted_by  = auth()->user()->id;
        $this->user->restored_at = null;
        $this->user->restored_by = null;
        $this->user->save();

        $this->user->notify(new UserDeletedNotification());

        $this->reset('confirmedDeletion', 'modal');
        $this->success('User deleted successfully');
        $this->dispatch('user::deleted');
    }

    #[On('user::deletion')]
    public function openConfimation(int $userId): void
    {
        $this->user  = User::select('id', 'name')->find($userId);
        $this->modal = true;
    }

    public function confirmDeletion()
    {
        $this->confirmedDeletion = true;
        $this->destroy();
    }
}
