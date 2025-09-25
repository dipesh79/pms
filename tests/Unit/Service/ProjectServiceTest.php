<?php

use App\Models\Project;
use App\Service\ProjectService;



beforeEach(function () {
    $this->service = new ProjectService();
});

it('can create a project', function () {
    $user = \App\Models\User::factory()->create([
        'role' => 'admin'
    ]);
    $data = [
        'title' => 'Test Project',
        'description' => 'This is a test project',
        'start_date' => '2024-01-01',
        'end_date' => '2024-12-31',
        'user_id' => $user->id,
    ];

    $project = $this->service->createProject($data);

    expect($project)->toBeInstanceOf(Project::class)
        ->and($project->title)->toBe('Test Project')
        ->and(Project::count())->toBe(1);
});

it('can update a project', function () {
    $project = Project::factory()->create([
        'title' => 'Old Name',
        'user_id' => \App\Models\User::factory()->create(['role' => 'admin'])->id,
    ]);

    $data = ['title' => 'New Name'];

    $updated = $this->service->updateProject($data, $project->id);

    expect($updated->title)->toBe('New Name');
});

it('can delete a project', function () {
    $project = Project::factory()->create([
        'user_id' => \App\Models\User::factory()->create(['role' => 'admin'])->id,
    ]);

    $this->service->deleteProject($project->id);

    expect(Project::find($project->id))->toBeNull()
        ->and(Project::count())->toBe(0);
});
