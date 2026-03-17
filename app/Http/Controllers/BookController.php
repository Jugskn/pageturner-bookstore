<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Category;
use App\Http\Requests\StoreBookRequest;
use App\Http\Requests\UpdateBookRequest;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class BookController extends Controller
{
    use AuthorizesRequests;

    public function index()
    {
        $books = Book::where('status', 'available')->latest()->paginate(8);

        return view('books.index', compact('books'));
    }

    public function show(Book $book)
    {
        $book->load(['reviews.user', 'category']);

        return view('books.show', compact('book'));
    }

    public function adminIndex()
    {
        $this->authorize('create', Book::class);

        $books = Book::with('category')->latest()->paginate(15);

        return view('admin.books.index', compact('books'));
    }

    public function create()
    {
        $this->authorize('create', Book::class);

        $categories = Category::all();

        return view('books.create', compact('categories'));
    }

    public function store(StoreBookRequest $request)
    {
        $this->authorize('create', Book::class);

        Book::create($request->validated());

        return redirect()->route('admin.books.index')
            ->with('status', 'Book created successfully.');
    }

    public function edit(Book $book)
    {
        $this->authorize('update', $book);

        $categories = Category::all();

        return view('books.edit', compact('book', 'categories'));
    }

    public function update(UpdateBookRequest $request, Book $book)
    {
        $this->authorize('update', $book);

        $book->update($request->validated());

        return redirect()->route('admin.books.index')
            ->with('status', 'Book updated successfully.');
    }

    public function destroy(Book $book)
    {
        $this->authorize('delete', $book);

        $book->delete();

        return redirect()->route('admin.books.index')
            ->with('status', 'Book deleted successfully.');
    }

    public function toggleStatus(Book $book)
    {
        $this->authorize('update', $book);

        $book->update([
            'status' => $book->status === 'available' ? 'sold' : 'available',
        ]);

        return redirect()->route('admin.books.index')
            ->with('status', "Book marked as {$book->status}.");
    }
}
