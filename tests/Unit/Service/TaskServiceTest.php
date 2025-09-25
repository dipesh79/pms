<?php

use App\Models\Task;
use App\Models\User;
use App\Service\TaskService;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->service = new TaskService();
});

it('can create a task', function () {
    $user = User::factory()->create([
        'role' => 'admin'
    ]);

    $project = \App\Models\Project::factory()->create(
        [
            'user_id' => $user->id
        ]
    );

    $data = [
        'title' => 'Test Task',
        'description' => 'Task description',
        'status' => 'pending',
        'user_id' => $user->id,
        'project_id' => $project->id,
    ];

    $task = $this->service->createTask($data);

    expect($task)->toBeInstanceOf(Task::class)
        ->and($task->title)->toBe('Test Task')
        ->and(Task::count())->toBe(1);
});

it('can update a task', function () {
    $user = User::factory()->create([
        'role' => 'admin'
    ]);

    $project = \App\Models\Project::factory()->create(
        [
            'user_id' => $user->id
        ]
    );

    $task = Task::factory()->create(
        [
            'title' => 'Old Task',
            'user_id' => $user->id,
            'project_id' => $project->id,
        ]
    );

    $updated = $this->service->updateTask(['title' => 'Updated Task'], $task->id);

    expect($updated->title)->toBe('Updated Task');
});

it('can delete a task', function () {
    $user = User::factory()->create([
        'role' => 'admin'
    ]);

    $project = \App\Models\Project::factory()->create(
        [
            'user_id' => $user->id
        ]
    );

    $task = Task::factory()->create(
        [
            'title' => 'Task to be deleted',
            'user_id' => $user->id,
            'project_id' => $project->id,
        ]
    );

    $this->service->deleteTask($task->id);

    expect(Task::find($task->id))->toBeNull()
        ->and(Task::count())->toBe(0);
});

it('can store a comment on a task', function () {
    $user = User::factory()->create([
        'role' => 'admin'
    ]);

    $project = \App\Models\Project::factory()->create(
        [
            'user_id' => $user->id
        ]
    );

    $task = Task::factory()->create(
        [
            'title' => 'Task to be deleted',
            'user_id' => $user->id,
            'project_id' => $project->id,
        ]
    );

    $commentData = [
        'body' => 'This is a test comment',
        'user_id' => $user->id,
    ];

    $comment = $this->service->storeComment($commentData, $task->id);

    expect($comment->body)->toBe('This is a test comment')
        ->and($task->comments()->count())->toBe(1);
});
