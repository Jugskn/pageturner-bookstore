@extends('layouts.app')

@section('title', $book->title)

@section('content')

<div class="max-w-3xl mx-auto">
    <div class="bg-white border border-gray-200 rounded-lg overflow-hidden">
        <div class="sm:flex">
            <div class="sm:w-48 sm:flex-shrink-0">
                <div class="w-full h-48 sm:h-full bg-gray-100 overflow-hidden">
                    <img src="https://images.unsplash.com/photo-1512820790803-83ca734da794?w=400&q=80" alt="{{ $book->title }}" class="w-full h-full object-cover">
                </div>
            </div>

            <div class="p-5 sm:p-6 flex-grow">
                <h1 class="text-xl font-bold text-gray-900">{{ $book->title }}</h1>
                <p class="text-sm text-gray-500 mt-1">by {{ $book->author }}</p>
                <p class="text-xs text-gray-400 mt-0.5">{{ optional($book->category)->name ?? 'Uncategorized' }}</p>
                <p class="text-lg font-bold text-gray-900 mt-3">₱{{ number_format($book->price, 0) }}</p>

                @if($book->description)
                    <p class="text-sm text-gray-600 mt-3 leading-relaxed">{{ $book->description }}</p>
                @endif

                @auth
                    <form action="{{ route('cart.add', $book) }}" method="POST" class="mt-5 pt-4 border-t border-gray-100 flex items-center gap-3">
                        @csrf
                        <label for="quantity" class="text-xs text-gray-500">Qty</label>
                        <input id="quantity" type="number" name="quantity" value="1" min="1" class="w-16 rounded border-gray-300 text-sm">
                        <button class="bg-gray-900 text-white text-sm px-4 py-2 rounded-md hover:bg-gray-800 transition">Add to Cart</button>
                    </form>
                @endauth
            </div>
        </div>
    </div>

    <div class="mt-8 bg-white border border-gray-200 rounded-lg p-5">
        <h2 class="text-base font-bold text-gray-900 mb-3">Reviews</h2>

        @if($book->reviews()->exists())
            <div class="space-y-3">
                @foreach($book->reviews()->with('user')->latest()->get() as $review)
                    <div class="border border-gray-100 rounded-md px-3 py-2">
                        <p class="text-sm text-gray-800">
                            @for ($i = 0; $i < $review->rating; $i++) ★ @endfor
                            <span class="text-xs text-gray-400 ml-1">by {{ $review->user->name ?? 'Anonymous' }}</span>
                        </p>
                        @if($review->comment)
                            <p class="text-xs text-gray-600 mt-1">{{ $review->comment }}</p>
                        @endif
                    </div>
                @endforeach
            </div>
        @else
            <p class="text-sm text-gray-400">No reviews yet.</p>
        @endif
    </div>
</div>

@endsection
