<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Task;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

uses(TestCase::class,RefreshDatabase::class);

it('allows a user to list all tasks', function () {
    $user = User::factory()->create();
    $task = Task::factory()->for($user)->create([
        'title' => 'Walk dog'
    ]);

    $this->actingAs($user)
        ->getJson('/api/tasks')
        ->assertStatus(200)
        ->assertJsonFragment(['title' => 'Walk dog']);
});

it('allows a user to view a single task', function () {
    $user = User::factory()->create();
    $task = Task::factory()->for($user)->create([
        'title' => 'Walk dog'
    ]);

    $this->actingAs($user)
        ->getJson("/api/tasks/{$task->id}")
        ->assertStatus(200)
        ->assertJsonPath('data.title', 'Walk dog');
});
