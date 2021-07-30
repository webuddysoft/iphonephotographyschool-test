<?php

namespace App\Models;

use App\Events\AchievementUnlocked;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Achievement extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'count', 'type'];

    /**
     * Get achievement defines by type
     * 
     * @param string $type achievement type => lesson/comment
     * @param integer $count if count is null, return all of the type, otherwise the achievements have <= $count
     * 
     * @return Collection 
     */
    public static function getAchievements($type, $count = null)
    {
        $query = self::where('type', $type)->orderBy('count', 'desc');
        if ($count !== null) {
            $query->where('count', '<=', $count);
        }
        return $query->get();
    }
    
    /**
     * Get current Achievement by count
     * 
     * @param integer $count
     * @param string $type
     * 
     * @return Achievement or null
     */
    public static function getCurrentAchievement($type, $count) 
    {
        return self::where('type', $type)->where('count', '<=', $count)->orderBy('count', 'desc')->first();
    }

    
    /**
     * Get next Achievement by count
     * 
     * @param integer $count
     * @param string $type
     * 
     * @return Achievement or null
     */
    public static function getNextAchievement($type, $count) 
    {
        return self::where('type', $type)->where('count', '>', $count)->orderBy('count', 'asc')->first();
    }

    /**
     * Check the count unlocked new achievement
     * 
     * @param integer $count
     * @param string $type  achievement type => lesson/comment
     * 
     * @return Achievement or Null
     */
    public static function isNewAchievement($user, $type) 
    {
        $count = 0;
        if ($type == "comment") {
            $count = $user->comments()->count();
        } else if ($type == "lesson") {
            $count = $user->watched()->count();
        } else {
            return null;
        }
        $achievements = self::getAchievements($type);
        
        $curAchievement = $achievements->firstWhere('count', '<=', $count);
        $prevAchievement = $achievements->firstWhere('count', '<=', $count - 1);
        
        //if new achievement is unlocked, dispatch event
        if ($curAchievement != $prevAchievement) {
            AchievementUnlocked::dispatch($curAchievement->title, $user);
            //check new badge is unlocked
            Badge::isNewBadge($user);
            return $curAchievement;
        }
        return null;
    }

}
