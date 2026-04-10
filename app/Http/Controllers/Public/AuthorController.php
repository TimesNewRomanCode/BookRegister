<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Http\Helpers\Helper;
use App\Http\Resources\AuthorResource;
use App\Models\Author;
use Illuminate\Http\Request;

class AuthorController extends Controller
{
    public function index(Request $request)
    {
        $authors = Author::withCount('books')
            ->paginate(10);

        return Helper::successResponse('Authors retrieved successfully.', AuthorResource::collection($authors));
    }

    public function show(Author $author)
    {
        $author->load(['books.genres']);

        return Helper::successResponse('Author retrieved successfully.', new AuthorResource($author));
    }
}
