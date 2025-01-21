<?php

namespace App\Events;

use App\Models\User;
use App\Notifications\ValidateCodeNotification;
use Illuminate\Broadcasting\{InteractsWithSockets, PrivateChannel};
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class SendNewCode
{
    use Dispatchable;
    use InteractsWithSockets;
    use SerializesModels;

    /**
     * Create a new event instance.
     */
    public function __construct(public User $user)
    {
        //
    }

    public function handle(): void
    {
        $this->user->validation_code = random_int(100000, 999999);
        $this->user->save();

        $this->user->notify(new ValidateCodeNotification());
    }

    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('channel-name'),
        ];
    }
}
