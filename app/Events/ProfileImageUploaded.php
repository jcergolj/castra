<?php

namespace App\Events;

use App\Models\User;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ProfileImageUploaded
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /** @var \App\Models\User */
    public $user;

    /**
     * @return void
     */
    public function __construct(User $user)
    {
        $this->user = $user;
    }
}
