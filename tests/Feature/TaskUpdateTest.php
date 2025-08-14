<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Task;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

uses(TestCase::class,RefreshDatabase::class);

it('allows a user to update a task', function () {
    $user = User::factory()->create();
    $task = Task::factory()->for($user)->create([
        'title' => 'Old title'
    ]);

    $payload = [
        'title' => 'New title',
        'tags' => ['Urgent', 'Work']
    ];

    $this->actingAs($user)
        ->putJson("/api/tasks/{$task->id}", $payload)
        ->assertStatus(200)
        ->assertJsonPath('data.title', 'New title')
        ->assertJsonPath('data.tags.0', 'Urgent');
});
