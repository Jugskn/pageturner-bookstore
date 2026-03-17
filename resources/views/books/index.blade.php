@extends('layouts.app')

@section('title', 'Books')

@section('content')

<div class="mb-6">
    <h1 class="text-xl font-bold text-gray-900">Books</h1>
    <p class="text-sm text-gray-500">Browse our catalog of available titles.</p>
</div>

@if($books->isNotEmpty())
    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-5">
        @foreach($books as $book)
            <a href="{{ route('books.show', $book) }}" class="group bg-white border border-gray-200 rounded-lg overflow-hidden hover:shadow-md transition">
                <div class="w-full h-36 bg-gray-100 overflow-hidden">
                    <img src="https://images.unsplash.com/photo-1512820790803-83ca734da794?w=400&q=80" alt="{{ $book->title }}" class="w-full h-full object-cover">
                </div>
                <div class="p-3">
                    <h2 class="text-sm font-semibold text-gray-900 line-clamp-1 group-hover:text-blue-600 transition">{{ $book->title }}</h2>
                    <p class="text-xs text-gray-500 mt-0.5">{{ $book->author }}</p>
                    <p class="text-sm font-bold text-gray-900 mt-1">₱{{ number_format($book->price, 0) }}</p>
                </div>
            </a>
        @endforeach
    </div>
@else
    <div class="border border-dashed border-gray-300 rounded-lg py-10 text-center text-sm text-gray-400">
        No books found.
    </div>
@endif

<div class="mt-6">{{ $books->links() }}</div>

@endsection
