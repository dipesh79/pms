<?php

test('list tasks from a project', function () {
    $user = \App\Models\User::factory()->create(
        [
            'role' => \App\Enums\RoleEnum::USER->value,
        ]);
    $this->actingAs($user);
    $project = \App\Models\Project::factory()->create([
        'user_id' => $user->id,
    ]);
    \App\Models\Task::factory()->count(3)->create([
        'project_id' => $project->id,
        'user_id' => $user->id,
    ]);

    $response = $this->getJson("/api/projects/{$project->id}/tasks");
    $response->assertStatus(200);
});


test('store task to a project', function () {
    $user = \App\Models\User::factory()->create(
        [
            'role' => \App\Enums\RoleEnum::MANAGER->value,
        ]);
    $this->actingAs($user);
    $project = \App\Models\Project::factory()->create([
        'user_id' => $user->id,
    ]);
    $taskData = [
        'title' => 'New Task',
        'description' => 'This is a new task',
        'status' => \App\Enums\TaskStatusEnum::PENDING->value,
        'due_date' => now()->addWeek()->toDateString(),
        'user_id' => $user->id,
    ];
    $response = $this->postJson("/api/projects/{$project->id}/tasks", $taskData);
    $response->assertStatus(200)
        ->assertJsonFragment(['title' => 'New Task']);
});

test('show a task', function () {
    $user = \App\Models\User::factory()->create(
        [
            'role' => \App\Enums\RoleEnum::USER->value,
        ]);
    $this->actingAs($user);
    $project = \App\Models\Project::factory()->create([
        'user_id' => $user->id,
    ]);
    $task = \App\Models\Task::factory()->create([
        'project_id' => $project->id,
        'user_id' => $user->id,
    ]);

    $response = $this->getJson("/api/tasks/{$task->id}");
    $response->assertStatus(200)
        ->assertJsonFragment(['id' => $task->id]);

});

test('update task', function () {
    $user = \App\Models\User::factory()->create(
        [
            'role' => \App\Enums\RoleEnum::MANAGER->value,
        ]);
    $this->actingAs($user);
    $project = \App\Models\Project::factory()->create([
        'user_id' => $user->id,
    ]);
    $task = \App\Models\Task::factory()->create([
        'project_id' => $project->id,
        'user_id' => $user->id,
    ]);
    $updateData = [
        'title' => 'Updated Task Title',
        'description' => 'Updated description',
        'status' => \App\Enums\TaskStatusEnum::IN_PROGRESS->value,
        'due_date' => now()->addDays(10)->toDateString(),
        'user_id' => $user->id,
    ];
    $response = $this->putJson("/api/tasks/{$task->id}", $updateData);
    $response->assertStatus(200)
        ->assertJsonFragment(['title' => 'Updated Task Title']);
});

test('delete a task', function () {
    $user = \App\Models\User::factory()->create(
        [
            'role' => \App\Enums\RoleEnum::MANAGER->value,
        ]);
    $this->actingAs($user);
    $project = \App\Models\Project::factory()->create([
        'user_id' => $user->id,
    ]);
    $task = \App\Models\Task::factory()->create([
        'project_id' => $project->id,
        'user_id' => $user->id,
    ]);

    $response = $this->deleteJson("/api/tasks/{$task->id}");
    $response->assertStatus(200)
        ->assertJsonFragment(['message' => 'Task deleted successfully']);

});

test('add a comment to a task', function () {
    $user = \App\Models\User::factory()->create(
        [
            'role' => \App\Enums\RoleEnum::USER->value,
        ]);
    $this->actingAs($user);
    $project = \App\Models\Project::factory()->create([
        'user_id' => $user->id,
    ]);
    $task = \App\Models\Task::factory()->create([
        'project_id' => $project->id,
        'user_id' => $user->id,
    ]);
    $commentData = [
        'body' => 'This is a comment on the task',
    ];
    $response = $this->postJson("/api/tasks/{$task->id}/comments", $commentData);
    $response->assertStatus(200)
        ->assertJsonFragment(['body' => 'This is a comment on the task']);

});

test('list comment of a task', function () {
    $user = \App\Models\User::factory()->create(
        [
            'role' => \App\Enums\RoleEnum::USER->value,
        ]);
    $this->actingAs($user);
    $project = \App\Models\Project::factory()->create([
        'user_id' => $user->id,
    ]);
    $task = \App\Models\Task::factory()->create([
        'project_id' => $project->id,
        'user_id' => $user->id,
    ]);
    \App\Models\Comment::factory()->count(2)->create([
        'task_id' => $task->id,
        'user_id' => $user->id,
    ]);

    $response = $this->getJson("/api/tasks/{$task->id}/comments");
    $response->assertStatus(200);


});
