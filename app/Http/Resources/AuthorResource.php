<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AuthorResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'bio' => $this->bio,
            'user_id' => $this->user_id,
            'books_count' => $this->when(isset($this->books_count), $this->books_count),
            'books' => $this->whenLoaded('books', function () {
                return BookResource::collection($this->books->loadMissing('genres'));
            }),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
