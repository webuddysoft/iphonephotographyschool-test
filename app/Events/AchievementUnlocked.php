<?php

namespace App\Events;

use App\Models\User;
use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Events\Dispatchable;

class AchievementUnlocked
{
    use Dispatchable, SerializesModels;

    public $achievementName;
    public $user;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($name, User $user)
    {
        $this->achievementName = $name;
        $this->user = $user;
    }
}
