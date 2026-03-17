@extends('layouts.app')

@section('title', $category->name)

@section('content')

<div class="flex items-end justify-between mb-6">
    <div>
        <h1 class="text-xl font-bold text-gray-900">{{ $category->name }}</h1>
        <p class="text-sm text-gray-500">{{ $books->total() }} book(s) in this category</p>
    </div>
    <a href="{{ route('categories.index') }}" class="text-sm text-gray-500 hover:text-gray-900">&larr; All Categories</a>
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
    <div class="mt-6">{{ $books->links() }}</div>
@else
    <div class="border border-dashed border-gray-300 rounded-lg py-10 text-center text-sm text-gray-400">
        No books in this category yet.
    </div>
@endif

@endsection
