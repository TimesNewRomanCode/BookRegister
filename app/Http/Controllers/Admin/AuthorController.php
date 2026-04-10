<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Helpers\Helper;
use App\Http\Resources\AuthorResource;
use App\Models\Author;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class AuthorController extends Controller
{
    public function index(Request $request)
    {
        $authors = Author::withCount('books')
            ->paginate(10);

        return Helper::successResponse('Authors retrieved successfully.', AuthorResource::collection($authors));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'bio' => 'nullable|string',
            'user_id' => 'nullable|exists:users,id',
        ]);

        $author = Author::create($data);

        Log::info('Author created by admin', ['author_id' => $author->id, 'name' => $author->name]);

        return Helper::successResponse('Author created successfully.', new AuthorResource($author));
    }

    public function show(Author $author)
    {
        $author->load('books.genres');

        return Helper::successResponse('Author retrieved successfully.', new AuthorResource($author));
    }

    public function update(Request $request, Author $author)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'bio' => 'nullable|string',
            'user_id' => 'nullable|exists:users,id',
        ]);

        $author->update($data);

        Log::info('Author updated by admin', ['author_id' => $author->id, 'name' => $author->name]);

        return Helper::successResponse('Author updated successfully.', new AuthorResource($author));
    }

    public function destroy(Author $author)
    {
        Log::info('Author deleted by admin', ['author_id' => $author->id, 'name' => $author->name]);
        $author->delete();

        return Helper::successResponse('Author deleted successfully.');
    }
}
