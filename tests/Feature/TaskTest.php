<?php

namespace Tests\Feature;

use App\Models\Task;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class TaskTest extends TestCase
{
    use WithFaker;
    use RefreshDatabase;

    public function testTaskCreation(): void
    {
        $this->actingAs( User::factory()->create());
        $response = $this->post('/api/task', [
            'priority' => 2,
            'title' => 'Test title',
            'description' => 'Test description',
        ]);

        $response->assertStatus(201)->assertJsonFragment([
            'message' => 'Task created successfully',
        ]);
    }

    public function testTaskCreationWithTask(): void
    {
        $this->actingAs( User::factory()->create());
        $task = Task::factory()->create();
        $response = $this->post('/api/task', [
            'priority' => 2,
            'title' => 'Test title test child',
            'description' => 'Test description test child',
            'task_id' => $task->id,
        ]);

        $this->assertDatabaseHas('tasks', [
            'priority' => 2,
            'title' => 'Test title test child',
            'description' => 'Test description test child',
        ]);
        $response->assertStatus(201)->assertJsonFragment([
            'message' => 'Task created successfully',
        ]);
    }

    public function testTaskCreationFailedPriority(): void
    {
        $this->actingAs( User::factory()->create());
        $response = $this->post('/api/task', [
            'priority' => 'hi',
            'title' => 'Test title',
            'description' => 'Test description',
        ], [
            'Accept' => 'application/json'
        ]);

        $response->assertJsonValidationErrorFor('priority');
    }

    public function testGetAllTasks(): void
    {
        $this->actingAs( User::factory()->create());
        Task::factory()->create();
        $response = $this->get('/api/tasks', [
            'Accept' => 'application/json'
        ]);

        $response->assertStatus(200);
    }

    public function testGetTask(): void
    {
        $this->actingAs( $user = User::factory()->create());
        $task = Task::factory()->create(['user_id' => $user->id]);
        $response = $this->get('/api/task/' . $task->id, [
            'Accept' => 'application/json'
        ]);

        $response->assertStatus(200);
    }

    public function testUpdateTask(): void
    {
        $this->actingAs( $user = User::factory()->create());
        $task = Task::factory()->create(
            [
                'user_id' => $user->id,
                'status' => 'todo',
            ]);
        $response = $this->put('/api/task/' . $task->id, [
            'priority' => 2,
            'title' => 'Test title',
            'description' => 'Test description',
        ], [
            'Accept' => 'application/json'
        ]);

        $response->assertStatus(200);
    }

    public function testDeleteTask(): void
    {
        $this->actingAs( $user = User::factory()->create());
        $task = Task::factory()->create(['user_id' => $user->id]);
        $response = $this->delete('/api/task/' . $task->id, [
            'Accept' => 'application/json'
        ]);

        $response->assertStatus(200);
    }

    public function testUpdateTaskComplete(): void
    {
        $this->actingAs( $user = User::factory()->create());
        $task = Task::factory()->create(['user_id' => $user->id]);
        $response = $this->patch('/api/task/' . $task->id . '/complete', [
            'Accept' => 'application/json'
        ]);

        $response->assertStatus(200);
    }

    public function testSortTasks(): void
    {
        $this->actingAs( $user = User::factory()->create());
        $taskFirst = Task::factory()->create(['user_id' => $user->id, 'status' => 'done']);
        $taskSecond =Task::factory()->create(['user_id' => $user->id, 'status' => 'done']);
        Task::factory()->create(['user_id' => $user->id, 'status' => 'todo']);
        $response = $this->post('/api/task/sort', [
            'status' => 'done',
        ], [
            'Accept' => 'application/json'
        ]);

        $response->assertStatus(200)
                 ->assertJsonFragment(array_merge($taskFirst->toArray(), $taskSecond->toArray()));
    }
}
