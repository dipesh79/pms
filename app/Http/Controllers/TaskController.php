<?php

namespace App\Http\Controllers;

use App\Http\Requests\Task\CommentIndexRequest;
use App\Http\Requests\Task\CommentStoreRequest;
use App\Http\Requests\Task\TaskDeleteRequest;
use App\Http\Requests\Task\TaskIndexRequest;
use App\Http\Requests\Task\TaskShowRequest;
use App\Http\Requests\Task\TaskStoreRequest;
use App\Http\Requests\Task\TaskUpdateRequest;
use App\Http\Resources\Comment\CommentCollection;
use App\Http\Resources\Task\TaskCollection;
use App\Http\Resources\Task\TaskResource;
use App\Models\Comment;
use App\Models\Project;
use App\Models\Task;
use App\Service\TaskService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Response;

class TaskController extends Controller
{
    public function __construct(public TaskService $taskService)
    {
    }

    /**
     * Task Index.
     */
    public function index(TaskIndexRequest $request, int $projectId): JsonResponse
    {
        $cacheKey = $request->cacheKey() . "_project_{$projectId}";
        $tasks = Cache::rememberForever($cacheKey, function () use ($request, $projectId) {
            $tasks = Task::with(['user']);
            if ($request->search) {
                $tasks->customFilter($request->search);
            }
            if ($request->page && $request->size) {
                $tasks = $tasks->paginate($request->size);
            } else {
                $tasks = $tasks->get();
            }

            return new TaskCollection($tasks);
        });
        return Response::collection($tasks, "Tasks retrieved successfully");

    }

    /**
     * Task Store.
     */
    public function store(TaskStoreRequest $request, int $projectId): JsonResponse
    {
        $project = Project::find($projectId);
        if (!$project) {
            return Response::error("Project not found", 404);
        }
        try {
            $data = $request->validated();
            // Assign the authenticated user's ID to the task
            $data['user_id'] = auth()->id();
            $data['project_id'] = $project->id;
            $task = $this->taskService->createTask($data);
            $response = new TaskResource($task);
        } catch (\Exception $e) {
            return Response::error($e);
        }
        return Response::success($response, "Task created successfully");

    }

    /**
     * Task Show.
     */
    public function show(TaskShowRequest $request, int $id): JsonResponse
    {
        $task = Task::with(['user'])->find($id);
        if (!$task) {
            return Response::error("Task not found", 404);
        }
        $response = new TaskResource($task);
        return Response::success($response, "Task retrieved successfully");
    }

    /**
     * Task Update.
     */
    public function update(TaskUpdateRequest $request, int $id): JsonResponse
    {
        $task = Task::find($id);
        if (!$task) {
            return Response::error("Task not found", 404);
        }
        try {
            $data = $request->validated();
            $task = $this->taskService->updateTask($data, $task->id);
            $response = new TaskResource($task);
        } catch (\Exception $e) {
            return Response::error($e);
        }
        return Response::success($response, "Task updated successfully");
    }

    /**
     * Task Delete.
     */
    public function destroy(TaskDeleteRequest $request, int $id): JsonResponse
    {
        $task = Task::find($id);
        if (!$task) {
            return Response::error("Task not found", 404);
        }
        try {
            $this->taskService->deleteTask($task->id);
        } catch (\Exception $e) {
            return Response::error($e);
        }
        return Response::success(null, "Task deleted successfully");
    }

    /**
     * Add Comment to Task.
     */
    public function addComment(CommentStoreRequest $request, int $taskId): JsonResponse
    {
        $task = Task::find($taskId);
        if (!$task) {
            return Response::error("Task not found", 404);
        }
        try {
            $data = $request->validated();
            $data['user_id'] = auth()->id();
            $comment = $this->taskService->storeComment($data, $task->id);
        } catch (\Exception $e) {
            return Response::error($e);
        }
        return Response::success($comment, "Comment added successfully");
    }

    /**
     * Get Comments of Task.
     */
    public function getComments(CommentIndexRequest $request, int $taskId): JsonResponse
    {
        $task = Task::find($taskId);
        if (!$task) {
            return Response::error("Task not found", 404);
        }
        $cacheKey = $request->cacheKey() . "_task_{$taskId}_comments";
        $comments = Cache::rememberForever($cacheKey, function () use ($task, $request) {
            $comments = Comment::with(['user'])->where('task_id', $task->id);
            if ($request->search) {
                $comments->customFilter($request->search);
            }
            if ($request->page && $request->size) {
                $comments = $comments->paginate($request->size);
            } else {
                $comments = $comments->get();
            }
            return new CommentCollection($comments);
        });
        return Response::collection($comments, "Comments retrieved successfully");
    }
}
