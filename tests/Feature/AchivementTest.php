<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Event;
use Tests\TestCase;
use App\Models\User;
use App\Models\Lesson;
use App\Events\LessonWatched;
use App\Events\CommentWritten;
use App\Events\AchievementUnlocked;
use App\Events\BadgeUnlocked;

class AchivementTest extends TestCase
{
    use WithFaker, DatabaseTransactions;

    /**
     * Check initial status
     */
    public function test0()
    {
        //Create user
        $user = User::factory()->create();
        //Assign Lessons
        $user->lessons()->attach(Lesson::all()->pluck('id')->toArray(), ['watched' => false]);
        //Check Initial Status
        $response = $this->json('get', '/users/' . $user->id . '/achievements');
        $response->assertStatus(200)
                ->assertJson([
                    "unlocked_achievements" => [],
                    "next_available_achievements" => ["First Lesson Watched", "First Comment Written"],
                    "current_badge" => "Beginner",
                    "next_badge" => "Intermediate",
                    "remaing_to_unlock_next_badge" => 4
                ]);
        
        
    }

    /**
     * Watch one new lesson
     * Write one comment
     */
    public function test1()
    {
        //Create user
        $user = User::factory()->create();
        //Assign Lessons
        $user->lessons()->attach(Lesson::all()->pluck('id')->toArray(), ['watched' => false]);
        
        Event::fake();

        $user->watchLesson($user->unwatched()->first());
        $user->writeComment($this->faker->text());

        Event::assertDispatched(LessonWatched::class, 1);
        Event::assertDispatched(CommentWritten::class, 1);
        Event::assertDispatched(AchievementUnlocked::class, 2);
        Event::assertNotDispatched(BadgeUnlocked::class);

        $response = $this->json('get', '/users/' . $user->id . '/achievements');

        $response->assertStatus(200)
                ->assertJson([
                    "unlocked_achievements" => ["First Lesson Watched", "First Comment Written"],
                    "next_available_achievements" => ["5 Lessons Watched", "3 Comments Written"],
                    "current_badge" => "Beginner",
                    "next_badge" => "Intermediate",
                    "remaing_to_unlock_next_badge" => 2
                ]);
    }

    
    /**
     * Watch +5 new lesson
     * Write +4 comment
     */
    public function test2()
    {
        //Create user
        $user = User::factory()->create();
        //Assign Lessons
        $user->lessons()->attach(Lesson::all()->pluck('id')->toArray(), ['watched' => false]);
        
        Event::fake();

        $lessonCount = 5;
        $commentCount = 4;

        for ($i = 1; $i <= $lessonCount; $i++) {
            $user->watchLesson($user->unwatched()->first());
        }
        
        for ($i = 1; $i <= $commentCount; $i++) {
            $user->writeComment($this->faker->text());
        }

        Event::assertDispatched(LessonWatched::class, 5);
        Event::assertDispatched(CommentWritten::class, 4);
        Event::assertDispatched(AchievementUnlocked::class, 4);
        Event::assertDispatched(BadgeUnlocked::class, 1);

        $response = $this->json('get', '/users/' . $user->id . '/achievements');

        $response->assertStatus(200)
                ->assertJson([
                    "unlocked_achievements" => ["First Lesson Watched", "5 Lessons Watched", "First Comment Written", "3 Comments Written"],
                    "next_available_achievements" => ["10 Lessons Watched", "5 Comments Written"],
                    "current_badge" => "Intermediate",
                    "next_badge" => "Advanced",
                    "remaing_to_unlock_next_badge" => 4
                ]);
    }

    
    
    /**
     * Watch +45 new lesson
     * Write +15 comment
     */
    public function test3()
    {
        //Create user
        $user = User::factory()->create();
        //Assign Lessons
        $user->lessons()->attach(Lesson::all()->pluck('id')->toArray(), ['watched' => false]);
        
        Event::fake();

        $lessonCount = 45;
        $commentCount = 15;

        for ($i = 1; $i <= $lessonCount; $i++) {
            $user->watchLesson($user->unwatched()->first());
        }
        
        for ($i = 1; $i <= $commentCount; $i++) {
            $user->writeComment($this->faker->text());
        }

        Event::assertDispatched(LessonWatched::class, 45);
        Event::assertDispatched(CommentWritten::class, 15);
        Event::assertDispatched(AchievementUnlocked::class, 8);
        Event::assertDispatched(BadgeUnlocked::class, 2);

        $response = $this->json('get', '/users/' . $user->id . '/achievements');

        $response->assertStatus(200)
                ->assertJson([
                    "unlocked_achievements" => [
                        "First Lesson Watched", 
                        "5 Lessons Watched", 
                        "10 Lessons Watched", 
                        "25 Lessons Watched", 
                        "First Comment Written", 
                        "3 Comments Written",
                        "5 Comments Written",
                        "10 Comments Written"
                    ],
                    "next_available_achievements" => [
                        "50 Lessons Watched", 
                        "20 Comments Written"
                    ],
                    "current_badge" => "Advanced",
                    "next_badge" => "Master",
                    "remaing_to_unlock_next_badge" => 2
                ]);
    }

    
    
    /**
     * Watch +55 new lesson
     * Write +25 comment
     */
    public function test4()
    {
        //Create user
        $user = User::factory()->create();
        //Assign Lessons
        $user->lessons()->attach(Lesson::all()->pluck('id')->toArray(), ['watched' => false]);
        
        Event::fake();

        $lessonCount = 55;
        $commentCount = 25;

        for ($i = 1; $i <= $lessonCount; $i++) {
            $user->watchLesson($user->unwatched()->first());
        }
        
        for ($i = 1; $i <= $commentCount; $i++) {
            $user->writeComment($this->faker->text());
        }

        Event::assertDispatched(LessonWatched::class, 55);
        Event::assertDispatched(CommentWritten::class, 25);
        Event::assertDispatched(AchievementUnlocked::class, 10);
        Event::assertDispatched(BadgeUnlocked::class, 3);

        $response = $this->json('get', '/users/' . $user->id . '/achievements');

        $response->assertStatus(200)
                ->assertJson([
                    "unlocked_achievements" => [
                        "First Lesson Watched", 
                        "5 Lessons Watched", 
                        "10 Lessons Watched", 
                        "25 Lessons Watched", 
                        "50 Lessons Watched", 
                        "First Comment Written", 
                        "3 Comments Written",
                        "5 Comments Written",
                        "10 Comments Written",
                        "20 Comments Written"
                    ],
                    "next_available_achievements" => [],
                    "current_badge" => "Master",
                    "next_badge" => "",
                    "remaing_to_unlock_next_badge" => 0
                ]);
    }
}
