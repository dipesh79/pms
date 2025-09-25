<?php

namespace App\Http\Resources\Project;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class ProjectCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public function toArray(Request $request): array
    {
        if ($request->page && $request->size) {
            return ['data' => $this->collection, 'paginate' => $this->resource];
        } else {
            return ['data' => $this->collection];
        }
    }
}
