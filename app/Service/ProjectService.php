<?php

namespace App\Service;

use App\Models\Project;

class ProjectService
{
    public function createProject(array $data): Project
    {
        return Project::create($data);
    }

    public function updateProject(array $data, int $id): Project
    {
        $project = Project::find($id);
        $project->update($data);
        return $project;
    }

    public function deleteProject(int $id): void
    {
        $project = Project::find($id);
        $project->delete();
    }

}
