<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Achievement;
use Illuminate\Http\Request;

class AchievementsController extends Controller
{
    public function index(User $user)
    {
        $unlockedAchievements = array_merge($user->getLessonAchievements()->pluck('title')->toArray(), $user->getCommentAchievements()->pluck('title')->toArray());

        $nextLessonAchievement = $user->getNextLessonAchievement();
        $nextCommentAchievement = $user->getNextCommentAchievement();
        $nextAchievement = [];
        if ($nextLessonAchievement) {
            $nextAchievement[] = $nextLessonAchievement->title;
        }
        if ($nextCommentAchievement) {
            $nextAchievement[] = $nextCommentAchievement->title;
        }

        $curBadge = $user->getCurrentBadge();
        $nextBadge = $user->getNextBadge();

        $remaingToUnlock = 0;
        if ($nextBadge) {
            $remaingToUnlock = $nextBadge->achievements - $user->total_achievements_count;
        }

        return response()->json([
            'unlocked_achievements' => $unlockedAchievements,
            'next_available_achievements' => $nextAchievement,
            'current_badge' => $curBadge ? $curBadge->title : '',
            'next_badge' => $nextBadge ? $nextBadge->title : '',
            'remaing_to_unlock_next_badge' => $remaingToUnlock,
        ]);
    }
}
