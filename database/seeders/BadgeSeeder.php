<?php

namespace Database\Seeders;

use App\Models\Badge;
use Illuminate\Database\Seeder;

class BadgeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        $data = [
            ['title' => 'Beginner', 'achievements' => 0],
            ['title' => 'Intermediate', 'achievements' => 4],
            ['title' => 'Advanced', 'achievements' => 8],
            ['title' => 'Master', 'achievements' => 10],
        ];
        foreach ($data as $row) {
            Badge::create($row);
        }
    }
}
