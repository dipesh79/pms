<?php

namespace App\Service;

use App\Models\Task;

class TaskService
{

    public function createTask(array $data): Task
    {
        return Task::create($data);
    }

    public function updateTask(array $data, int $id): Task
    {
        $task = Task::find($id);
        $task->update($data);
        return $task;
    }

    public function deleteTask(int $id): void
    {
        $task = Task::find($id);
        $task->delete();
    }

    public function storeComment(array $data, int $task_id)
    {
        $task = Task::find($task_id);
        return $task->comments()->create($data);
    }
}
