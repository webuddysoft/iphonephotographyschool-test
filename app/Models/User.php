<?php

namespace App\Models;

use App\Models\Comment;
use App\Events\LessonWatched;
use App\Events\CommentWritten;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use DB;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function getTotalAchievementsCountAttribute()
    {
        return $this->getLessonAchievements()->count() + $this->getCommentAchievements()->count();
    }

    /**
     * The comments that belong to the user.
     */
    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    /**
     * The lessons that a user has access to.
     */
    public function lessons()
    {
        return $this->belongsToMany(Lesson::class);
    }

    /**
     * The lessons that a user has watched.
     */
    public function watched()
    {
        return $this->belongsToMany(Lesson::class)->wherePivot('watched', true);
    }

    /**
     * The lessons that a user has not watched.
     */
    public function unwatched()
    {
        return $this->belongsToMany(Lesson::class)->wherePivot('watched', false);
    }

    /**
     * Watch Lesson
     * 
     * @param integer $lessonId lesson id
     * 
     * @return boolean  true if a user watched new lesson, otherwise false
     * 
     */
    public function watchLesson($lesson)
    {
        $isNewLesson = false;
        //Check if the user already watched this lesson
        if (!$this->watched->pluck('lesson_id')->contains($lesson->id)) {
            $this->lessons()->updateExistingPivot($lesson->id, ['watched' => true]);
            $isNewLesson = true;
            //Check new achievement unlocked
            Achievement::isNewAchievement($this, 'lesson');
        }
        //Dispatch Lesson Watched Event
        LessonWatched::dispatch($lesson, $this, $isNewLesson);
        return $isNewLesson;
    }

    /**
     * Write Comment
     * 
     * @param string $body
     * 
     * @return Comment
     */
    public function writeComment($body) 
    {
       $comment = Comment::create(['user_id' => $this->id, 'body' => $body]);
       //Dispatch Comment Written Event
       CommentWritten::dispatch($comment);
       //Check new achievement unlocked
       Achievement::isNewAchievement($this, 'comment');
       return $comment;
    }

    /**
     * Get unlocked lesson achievements
     * 
     * @return Collection
     */
    public function getLessonAchievements()
    {
        return Achievement::getAchievements('lesson', $this->watched()->count())->sortBy('count');
    }

    /**
     * Get Next Lesson Achievement
     * 
     * @return Achievement or NULL
     */
    public function getNextLessonAchievement()
    {
        return Achievement::getNextAchievement('lesson', $this->watched()->count());
    }

    /**
     * Get unlocked comment Achievements
     * 
     * @return Collection
     */
    public function getCommentAchievements()
    {
        return Achievement::getAchievements('comment', $this->comments()->count())->sortBy('count');
    }
    /**
     * Get Next Comment Achievement
     * 
     * @return Achievement or NULL
     */
    public function getNextCommentAchievement()
    {
        return Achievement::getNextAchievement('comment', $this->comments()->count());
    }

    /**
     * Get current badge
     */
    public function getCurrentBadge()
    {
        return Badge::getCurrentBadge($this->total_achievements_count);
    }

    /**
     * Get next badge
     */
    public function getNextBadge()
    {
        return Badge::getNextBadge($this->total_achievements_count);
    }

    
}
