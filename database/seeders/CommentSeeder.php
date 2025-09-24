<?php

namespace Database\Seeders;

use App\Models\Comment;
use App\Models\Task;
use App\Models\User;
use Illuminate\Database\Seeder;

class CommentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Comment::factory(10)->create([
            'user_id' => User::inRandomOrder()->first()->id,
            'task_id' => Task::inRandomOrder()->first()->id,
        ]);
    }
}
