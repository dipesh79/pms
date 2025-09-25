<?php

test('returns project list', function () {
    $user = \App\Models\User::factory()->create(
        [
            'role' => \App\Enums\RoleEnum::USER->value,
        ]);
    $this->actingAs($user);
    \App\Models\Project::factory()->count(3)->create([
        'user_id' => $user->id,
    ]);

    $response = $this->getJson('/api/projects');
    $response->assertStatus(200);
});

test('store project', function () {
    $admin = \App\Models\User::factory()->create(
        [
            'role' => \App\Enums\RoleEnum::ADMIN->value,
        ]);
    $this->actingAs($admin);

    $projectData = [
        'title' => 'New Project',
        'description' => 'This is a new project',
        'start_date' => '2023-10-01',
        'end_date' => '2023-12-31',
    ];

    $response = $this->postJson('/api/projects', $projectData);
    $response->assertStatus(200)
        ->assertJsonFragment(['title' => 'New Project']);
});

test('show project', function () {
    $user = \App\Models\User::factory()->create(
        [
            'role' => \App\Enums\RoleEnum::USER->value,
        ]);
    $this->actingAs($user);
    $project = \App\Models\Project::factory()->create([
        'user_id' => $user->id,
    ]);

    $response = $this->getJson("/api/projects/{$project->id}");
    $response->assertStatus(200)
        ->assertJsonFragment(['id' => $project->id]);

});

test('update project', function () {
    $user = \App\Models\User::factory()->create(
        [
            'role' => \App\Enums\RoleEnum::ADMIN->value,
        ]);
    $this->actingAs($user);
    $project = \App\Models\Project::factory()->create([
        'user_id' => $user->id,
    ]);

    $updateData = [
        'title' => 'Updated Project Title',
        'description' => 'Updated description',
        'start_date' => '2023-11-01',
        'end_date' => '2024-01-31',
    ];

    $response = $this->putJson("/api/projects/{$project->id}", $updateData);
    $response->assertStatus(200)
        ->assertJsonFragment(['title' => 'Updated Project Title']);

});


test('delete project', function () {
    $user = \App\Models\User::factory()->create(
        [
            'role' => \App\Enums\RoleEnum::ADMIN->value,
        ]);
    $this->actingAs($user);
    $project = \App\Models\Project::factory()->create([
        'user_id' => $user->id,
    ]);

    $response = $this->deleteJson("/api/projects/{$project->id}");
    $response->assertStatus(200)
        ->assertJsonFragment(['message' => 'Project deleted successfully']);

    // Verify the project is deleted
    $this->assertDatabaseMissing('projects', ['id' => $project->id]);
});
