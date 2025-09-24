<?php

namespace App\Http\Resources\User;

use App\Http\Resources\Comment\CommentResource;
use App\Http\Resources\Project\ProjectResource;
use App\Http\Resources\Task\TaskResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property int $id
 * @property string $name
 * @property string $email
 * @property string|null $email_verified_at
 * @property string $role
 * @property string|null $phone
 * @property string $created_at
 * @property string $updated_at
 */
class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'email_verified_at' => $this->email_verified_at,
            'role' => $this->role,
            'phone' => $this->phone,
            'projects' => ProjectResource::collection($this->whenLoaded('projects')),
            'tasks' => TaskResource::collection($this->whenLoaded('tasks')),
            'comments' => CommentResource::collection($this->whenLoaded('comments')),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
