<?php

namespace Database\Seeders;

use App\Models\Task;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TaskSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Task::factory(10)->create()->each(function(Task $task) {
            $task->children()->saveMany(Task::factory( 30)->create(['user_id' => $task->user_id])->each(function(Task $task) {
                $task->children()->saveMany(Task::factory(10)->create(['user_id' => $task->user_id])->each(function (Task $task){
                }));
            }));
        });
    }
}
