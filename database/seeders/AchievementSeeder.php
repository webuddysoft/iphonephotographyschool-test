<?php

namespace Database\Seeders;

use App\Models\Achievement;
use Illuminate\Database\Seeder;

class AchievementSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [
            //Lesson Watched Achievements
            ["count" => 1, "title" => "First Lesson Watched", "type" => "lesson"],
            ["count" => 5, "title" => "5 Lessons Watched", "type" => "lesson"],
            ["count" => 10, "title" => "10 Lessons Watched", "type" => "lesson"],
            ["count" => 25, "title" => "25 Lessons Watched", "type" => "lesson"],
            ["count" => 50, "title" => "50 Lessons Watched", "type" => "lesson"],
            //Comment Written Achievements
            ["count" => 1, "title" => "First Comment Written", "type" => "comment"],
            ["count" => 3, "title" => "3 Comments Written", "type" => "comment"],
            ["count" => 5, "title" => "5 Comments Written", "type" => "comment"],
            ["count" => 10, "title" => "10 Comments Written", "type" => "comment"],
            ["count" => 20, "title" => "20 Comments Written", "type" => "comment"],
        ];
        foreach ($data as $row) {
            Achievement::create($row);
        }
    }
}
