<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Task;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

uses(TestCase::class,RefreshDatabase::class);

it('allows a user to create a task', function () {

    // create and authenticate user
    $user = User::factory()->create();
    Sanctum::actingAs($user);

    // create task
    $response = $this->postJson('/api/tasks', [
        'title' => 'Buy milk',
        'description' => 'Remember to get 2% milk',
        'tags' => ['Shopping', 'Groceries'],
    ]);

    // assertions
    $response->assertStatus(201)
        ->assertJsonPath('data.title', 'Buy milk')
        ->assertJsonPath('data.tags.0', 'Shopping');
    expect(Task::first()->user_id)->toBe($user->id);
});
