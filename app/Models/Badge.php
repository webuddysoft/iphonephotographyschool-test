<?php

namespace App\Models;

use App\Events\BadgeUnlocked;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Badge extends Model
{
    use HasFactory;
    
    protected $fillable = ['title', 'achievements'];

    /**
     * get badges by achievements
     * 
     * @param integer $achievements
     * 
     * @return Collection
     */
    public static function getBadges($achievements = null) 
    {
        $query = self::orderBy('achievements', 'desc');
        if ($achievements !== null) {
            $query->where('achievements', '<=', $achievements);
        }
        return $query->get();
    }

    /**
     * Get current badge by achievements
     * 
     * @param integer $achievements
     * 
     * @return Badge
     */
    public static function getCurrentBadge($achievements)
    {
        return self::where('achievements', '<=', $achievements)->orderBy('achievements', 'desc')->first();
    }

    /**
     * Get current badge by achievements
     * 
     * @param integer $achievements
     * 
     * @return Badge
     */
    public static function getNextBadge($achievements)
    {
        return self::where('achievements', '>', $achievements)->orderBy('achievements', 'asc')->first();
    }

    /**
     * Check unlocked new badge
     */
    public static function isNewBadge($user)
    {
        $badges = self::getBadges();

        $achievements = $user->total_achievements_count;
        $curBadge = $badges->firstWhere('achievements', '<=', $achievements);
        $prevBadge = $badges->firstWhere('achievements', '<=', $achievements - 1);
        //Check if new badge is unlocked
        if ($curBadge != $prevBadge) {
            //Dispatch Badge Unlocked Event
            BadgeUnlocked::dispatch($curBadge->title, $user);
            return $curBadge;
        }
        return null;
    }
}
