@extends('layouts.app')

@section('title', 'Home')

@section('content')

<section class="bg-white border border-gray-200 rounded-lg px-6 py-12 text-center mb-10">
    <p class="text-xs uppercase tracking-widest text-gray-400 mb-2">Welcome to</p>
    <h1 class="text-3xl font-bold text-gray-900 mb-3">PageTurner Bookstore</h1>
    <p class="text-sm text-gray-500 max-w-md mx-auto mb-6">Discover and order your favorite books online.</p>
    <div class="flex justify-center gap-3">
        <a href="{{ route('books.index') }}" class="bg-gray-900 text-white text-sm px-5 py-2 rounded-md hover:bg-gray-800 transition">Browse Books</a>
        <a href="{{ route('categories.index') }}" class="border border-gray-300 text-gray-700 text-sm px-5 py-2 rounded-md hover:bg-gray-50 transition">By Category</a>
    </div>
</section>

<section>
    <div class="flex items-end justify-between mb-6">
        <div>
            <h2 class="text-xl font-bold text-gray-900">New Arrivals</h2>
            <p class="text-sm text-gray-500">Recently added to our shelves</p>
        </div>
        <a href="{{ route('books.index') }}" class="text-sm text-gray-500 hover:text-gray-900">View all &rarr;</a>
    </div>

    @if($books->isNotEmpty())
        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-5">
            @foreach($books as $book)
                <a href="{{ route('books.show', $book) }}" class="group bg-white border border-gray-200 rounded-lg overflow-hidden hover:shadow-md transition">
                    <div class="w-full h-36 bg-gray-100 overflow-hidden">
                        <img src="https://images.unsplash.com/photo-1512820790803-83ca734da794?w=400&q=80" alt="{{ $book->title }}" class="w-full h-full object-cover">
                    </div>
                    <div class="p-3">
                        <h3 class="text-sm font-semibold text-gray-900 line-clamp-1 group-hover:text-blue-600 transition">{{ $book->title }}</h3>
                        <p class="text-xs text-gray-500 mt-0.5">{{ $book->author }}</p>
                        <p class="text-sm font-bold text-gray-900 mt-1">₱{{ number_format($book->price, 0) }}</p>
                    </div>
                </a>
            @endforeach
        </div>
    @else
        <div class="border border-dashed border-gray-300 rounded-lg py-10 text-center text-sm text-gray-400">
            No books yet. Check back soon.
        </div>
    @endif
</section>

<section class="mt-12 pt-8 border-t border-gray-200">
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-6 text-center text-sm">
        <div>
            <div class="w-8 h-8 mx-auto mb-2 bg-gray-100 rounded-full flex items-center justify-center text-gray-500 font-bold text-xs">1</div>
            <p class="font-semibold text-gray-900">Browse</p>
            <p class="text-gray-500 text-xs mt-1">Explore our catalog by book or category.</p>
        </div>
        <div>
            <div class="w-8 h-8 mx-auto mb-2 bg-gray-100 rounded-full flex items-center justify-center text-gray-500 font-bold text-xs">2</div>
            <p class="font-semibold text-gray-900">Add to Cart</p>
            <p class="text-gray-500 text-xs mt-1">Pick your favorites and review them in your cart.</p>
        </div>
        <div>
            <div class="w-8 h-8 mx-auto mb-2 bg-gray-100 rounded-full flex items-center justify-center text-gray-500 font-bold text-xs">3</div>
            <p class="font-semibold text-gray-900">Enjoy</p>
            <p class="text-gray-500 text-xs mt-1">Place your order and enjoy your new reads.</p>
        </div>
    </div>
</section>

@endsection
