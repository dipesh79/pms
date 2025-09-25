<?php

namespace App\Http\Resources\Comment;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class CommentCollection extends ResourceCollection
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
