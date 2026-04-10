<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Http\Helpers\Helper;
use App\Http\Resources\BookResource;
use App\Models\Book;
use Illuminate\Http\Request;

class BookController extends Controller
{
    public function index(Request $request)
    {
        $books = Book::with('author')
            ->paginate(10);

        return Helper::successResponse('Books retrieved successfully.', BookResource::collection($books));
    }

    public function show(Book $book)
    {
        $book->load('author', 'genres');

        return Helper::successResponse('Book retrieved successfully.', new BookResource($book));
    }
}
