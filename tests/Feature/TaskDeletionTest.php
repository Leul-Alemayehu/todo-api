<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Task;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

uses(TestCase::class,RefreshDatabase::class);

it('allows a user to delete a task', function () {
    $user = User::factory()->create();
    $task = Task::factory()->for($user)->create();

    $this->actingAs($user)
        ->deleteJson("/api/tasks/{$task->id}")
        ->assertStatus(204);

    $this->assertDatabaseMissing('tasks', ['id' => $task->id]);
});
