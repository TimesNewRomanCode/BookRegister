<?php

namespace App\Http\Controllers\Author;

use App\Enums\BookType;
use App\Http\Controllers\Controller;
use App\Http\Helpers\Helper;
use App\Http\Resources\BookResource;
use App\Models\Book;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class BookController extends Controller
{
    protected function authorId()
    {
        return Auth::user()?->author_id;
    }

    protected function authorizeBook(Book $book): void
    {
        if ($book->author_id !== $this->authorId()) {
            abort(403, 'You are not allowed to manage this book.');
        }
    }

    public function update(Request $request, Book $book)
    {
        $this->authorizeBook($book);

        $data = $request->validate([
            'title' => 'required|string|max:255|unique:books,title,' . $book->id,
            'description' => 'nullable|string',
            'type' => 'required|in:' . implode(',', BookType::values()),
            'published_at' => 'nullable|date',
            'genre_ids' => 'nullable|array',
            'genre_ids.*' => 'integer|exists:genres,id',
        ]);

        $book->update($data);
        $book->genres()->sync($data['genre_ids'] ?? []);
        $book->load(['author', 'genres']);

        Log::info('Book updated by author', ['book_id' => $book->id, 'user_id' => Auth::id()]);

        return Helper::successResponse('Your book has been updated successfully.', new BookResource($book));
    }

    public function destroy(Book $book)
    {
        $this->authorizeBook($book);

        Log::info('Book deleted by author', ['book_id' => $book->id, 'user_id' => Auth::id()]);
        $book->delete();

        return Helper::successResponse('Your book has been deleted successfully.');
    }
}
