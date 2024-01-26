<?php

namespace Tests\Feature;

use App\Models\Task;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TaskControllerTest extends TestCase
{
    use RefreshDatabase;

    public function testUserCanGetTasks()
    {
        $user = User::factory()->create();
        $this->actingAs($user);
        $response = $this->getJson('/api/task/get-tasks');
        $response->assertStatus(200)
            ->assertJsonStructure(['tasks']);
    }

    public function testUserCanStoreTask()
    {
        $user = User::factory()->create();
        $this->actingAs($user);
        $response = $this->postJson('/api/task/store-tasks', [
            'title' => 'New Task',
            'description' => 'Description of the new task',
        ]);
        $response->assertStatus(201)
            ->assertJson(['message' => 'Todo stored successfully']);
        $this->assertDatabaseHas('tasks', [
            'task_title' => 'New Task',
            'task_description' => 'Description of the new task',
            'user_id' => $user->id,
        ]);
    }

    public function testUserCanUpdateTask()
    {
        $user = User::factory()->create();
        $this->actingAs($user);
        $task = Task::create([
            'task_title' => 'Updated Task Title',
            'task_description' => 'Updated description of the task',
            'user_id' => $user->id,
        ]);
        $response = $this->putJson("/api/task/update-task/{$task->id}", [
            'title' => 'Updated Task Title',
            'description' => 'Updated description of the task',
        ]);
        $response->assertStatus(200)
            ->assertJson(['message' => 'Task updated successfully']);
        $this->assertDatabaseHas('tasks', [
            'id' => $task->id,
            'task_title' => 'Updated Task Title',
            'task_description' => 'Updated description of the task',
        ]);
    }

    public function testUserCanUpdateTaskStatus()
    {
        $user = User::factory()->create();
        $this->actingAs($user);
        $task = Task::create([
            'task_title' => 'Updated Task Title',
            'task_description' => 'Updated description of the task',
            'user_id' => $user->id,
        ]);
        $response = $this->putJson("/api/task/update-status/{$task->id}");
        $response->assertStatus(200)
            ->assertJson(['message' => 'Task updated successfully']);
        $this->assertDatabaseHas('tasks', [
            'id' => $task->id,
            'status' => !$task->status,
        ]);
    }

    public function testUserCanRemoveTask()
    {
        $user = User::factory()->create();
        $this->actingAs($user);
        $task = Task::create([
            'task_title' => 'Updated Task Title',
            'task_description' => 'Updated description of the task',
            'user_id' => $user->id,
        ]);
        $response = $this->deleteJson("/api/task/remove/{$task->id}");
        $response->assertStatus(200)
            ->assertJson(['message' => 'Task Deleted successfully']);
    }
}
