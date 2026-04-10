<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Helpers\Helper;
use App\Http\Resources\GenreResource;
use App\Models\Genre;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class GenreController extends Controller
{
    public function index(Request $request)
    {
        $genres = Genre::withCount('books')
            ->paginate(10);

        return Helper::successResponse('Genres retrieved successfully.', GenreResource::collection($genres));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255|unique:genres,name',
            'description' => 'nullable|string',
        ]);

        $genre = Genre::create($data);

        Log::info('Genre created by admin', ['genre_id' => $genre->id, 'name' => $genre->name]);

        return Helper::successResponse('Genre created successfully.', new GenreResource($genre));
    }

    public function show(Genre $genre)
    {
        return Helper::successResponse('Genre retrieved successfully.', new GenreResource($genre));
    }

    public function update(Request $request, Genre $genre)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255|unique:genres,name,' . $genre->id,
            'description' => 'nullable|string',
        ]);

        $genre->update($data);

        Log::info('Genre updated by admin', ['genre_id' => $genre->id, 'name' => $genre->name]);

        return Helper::successResponse('Genre updated successfully.', new GenreResource($genre));
    }

    public function destroy(Genre $genre)
    {
        Log::info('Genre deleted by admin', ['genre_id' => $genre->id, 'name' => $genre->name]);
        $genre->delete();

        return Helper::successResponse('Genre deleted successfully.');
    }
}
