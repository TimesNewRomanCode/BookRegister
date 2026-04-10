<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Http\Helpers\Helper;
use App\Http\Resources\GenreResource;
use App\Models\Genre;
use Illuminate\Http\Request;

class GenreController extends Controller
{
    public function index(Request $request)
    {
        $genres = Genre::withCount('books')
            ->paginate(10);

        return Helper::successResponse('Genres retrieved successfully.', GenreResource::collection($genres));
    }
}
