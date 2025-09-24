<?php

namespace App\Http\Controllers;

use App\Http\Requests\Project\ProjectDeleteRequest;
use App\Http\Requests\Project\ProjectIndexRequest;
use App\Http\Requests\Project\ProjectShowRequest;
use App\Http\Requests\Project\ProjectStoreRequest;
use App\Http\Requests\Project\ProjectUpdateRequest;
use App\Http\Resources\Project\ProjectCollection;
use App\Http\Resources\Project\ProjectResource;
use App\Models\Project;
use App\Service\ProjectService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Response;

class ProjectController extends Controller
{
    public function __construct(public ProjectService $projectService)
    {
    }

    /**
     * Project Index.
     */
    public function index(ProjectIndexRequest $request): JsonResponse
    {
        $cacheKey = $request->cacheKey();
        $projects = Cache::rememberForever($cacheKey, function () use ($request) {
            $projects = Project::with(['user']);
            if ($request->search) {
                $projects->customFilter($request->search);
            }
            if ($request->page && $request->size) {
                $projects = $projects->paginate($request->size);
            } else {
                $projects = $projects->get();
            }

            return new ProjectCollection($projects);
        });

        return Response::collection($projects, "Projects retrieved successfully");
    }

    /**
     * Project Store.
     */
    public function store(ProjectStoreRequest $request): JsonResponse
    {
        try {
            $data = $request->validated();
            // Assign the authenticated user's ID to the project
            $data['user_id'] = auth()->id();
            $project = $this->projectService->createProject($data);
            $response = new ProjectResource($project);
        } catch (\Exception $e) {
            return Response::error($e);
        }
        return Response::success($response, "Project created successfully");

    }

    /**
     * Project Show.
     */
    public function show(ProjectShowRequest $request, int $id): JsonResponse
    {
        $project = \App\Models\Project::with(['user', 'tasks'])->find($id);
        if (!$project) {
            return Response::error("Project not found", 404);
        }
        $response = new ProjectResource($project);
        return Response::success($response, "Project retrieved successfully");
    }

    /**
     * Project Update.
     */
    public function update(ProjectUpdateRequest $request, int $id): JsonResponse
    {
        $project = Project::find($id);
        if (!$project) {
            return Response::error("Project not found", 404);
        }
        try {
            $data = $request->validated();
            $project = $this->projectService->updateProject($data, $project->id);
            $response = new ProjectResource($project);
        } catch (\Exception $e) {
            return Response::error($e);
        }
        return Response::success($response, "Project updated successfully");
    }

    /**
     * Project Delete.
     */
    public function destroy(ProjectDeleteRequest $request, int $id): JsonResponse
    {
        $project = Project::find($id);
        if (!$project) {
            return Response::error("Project not found", 404);
        }
        try {
            $this->projectService->deleteProject($project->id);
        } catch (\Exception $e) {
            return Response::error($e);
        }
        return Response::success([], "Project deleted successfully");
    }
}
