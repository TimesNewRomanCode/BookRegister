<?php

namespace App\Http\Controllers\Admin;

use App\Enums\BookType;
use App\Http\Controllers\Controller;
use App\Http\Helpers\Helper;
use App\Http\Resources\BookResource;
use App\Models\Book;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class BookController extends Controller
{
    public function index(Request $request)
    {
        $books = Book::with(['author', 'genres'])
            ->when($request->query('search'), fn($query, $search) => $query->where('title', 'like', "%{$search}%"))
            ->when($request->query('author_id'), fn($query, $authorId) => $query->where('author_id', $authorId))
            ->when($request->query('genres'), function ($query, $genres) {
                $ids = array_filter(explode(',', $genres));
                if (count($ids)) {
                    $query->whereHas('genres', fn($q) => $q->whereIn('genres.id', $ids));
                }
            })
            ->orderBy('title', $request->query('sort', 'asc') === 'desc' ? 'desc' : 'asc')
            ->paginate(10);

        return Helper::successResponse('Admin book list retrieved successfully.', BookResource::collection($books));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255|unique:books,title',
            'description' => 'nullable|string',
            'author_id' => 'required|exists:authors,id',
            'type' => 'required|in:' . implode(',', BookType::values()),
            'published_at' => 'nullable|date',
            'genre_ids' => 'nullable|array',
            'genre_ids.*' => 'integer|exists:genres,id',
        ]);

        $book = Book::create($data);
        $book->genres()->sync($data['genre_ids'] ?? []);
        $book->load(['author', 'genres']);

        Log::info('Book created by admin', ['book_id' => $book->id, 'title' => $book->title]);

        return Helper::successResponse('Book created successfully.', new BookResource($book));
    }

    public function show(Book $book)
    {
        $book->load(['author', 'genres']);

        return Helper::successResponse('Book retrieved successfully.', new BookResource($book));
    }

    public function update(Request $request, Book $book)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255|unique:books,title,' . $book->id,
            'description' => 'nullable|string',
            'author_id' => 'required|exists:authors,id',
            'type' => 'required|in:' . implode(',', BookType::values()),
            'published_at' => 'nullable|date',
            'genre_ids' => 'nullable|array',
            'genre_ids.*' => 'integer|exists:genres,id',
        ]);

        $book->update($data);
        $book->genres()->sync($data['genre_ids'] ?? []);
        $book->load(['author', 'genres']);

        Log::info('Book updated by admin', ['book_id' => $book->id, 'title' => $book->title]);

        return Helper::successResponse('Book updated successfully.', new BookResource($book));
    }

    public function destroy(Book $book)
    {
        Log::info('Book deleted by admin', ['book_id' => $book->id, 'title' => $book->title]);
        $book->delete();

        return Helper::successResponse('Book deleted successfully.');
    }
}
