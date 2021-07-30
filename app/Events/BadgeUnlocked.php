<?php

namespace App\Events;

use App\Models\User;
use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Events\Dispatchable;

class BadgeUnlocked
{
    use Dispatchable, SerializesModels;

    public $badgeName;
    public $user;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($name, User $user)
    {
        $this->badgeName = $name;
        $this->user = $user;
    }
}
