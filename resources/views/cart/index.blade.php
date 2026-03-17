@extends('layouts.app')

@section('title', 'Cart')

@section('content')

<div class="max-w-3xl mx-auto">
    <h1 class="text-xl font-bold text-gray-900 mb-6">Your Cart</h1>

    @if(!empty($items) && count($items) > 0)
        <div class="bg-white border border-gray-200 rounded-lg divide-y divide-gray-100">
            @foreach($items as $item)
                <div class="flex items-center gap-4 p-4">
                    <div class="w-14 h-14 flex-shrink-0 bg-gray-100 rounded overflow-hidden">
                        <img src="https://images.unsplash.com/photo-1512820790803-83ca734da794?w=400&q=80" alt="{{ $item['book']->title }}" class="w-full h-full object-cover">
                    </div>
                    <div class="flex-grow min-w-0">
                        <p class="text-sm font-semibold text-gray-900 truncate">{{ $item['book']->title }}</p>
                        <p class="text-xs text-gray-500">₱{{ number_format($item['unit_price'], 0) }} each</p>
                    </div>
                    <form action="{{ route('cart.update', $item['book']->id) }}" method="POST" class="flex items-center gap-2">
                        @csrf
                        @method('PATCH')
                        <input type="number" name="quantity" value="{{ $item['quantity'] }}" min="1" class="w-14 rounded border-gray-300 text-sm text-center">
                        <button class="text-xs text-gray-500 hover:text-gray-900">Update</button>
                    </form>
                    <form action="{{ route('cart.remove', $item['book']->id) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button class="text-xs text-red-500 hover:text-red-700">Remove</button>
                    </form>
                    <p class="text-sm font-bold text-gray-900 w-20 text-right">₱{{ number_format($item['line_total'], 0) }}</p>
                </div>
            @endforeach
        </div>

        <div class="mt-4 bg-white border border-gray-200 rounded-lg p-4 flex items-center justify-between">
            <p class="text-sm font-semibold text-gray-900">Total</p>
            <p class="text-lg font-bold text-gray-900">₱{{ number_format($total, 0) }}</p>
        </div>

        <form action="{{ route('orders.store') }}" method="POST" class="mt-4">
            @csrf
            <div class="mb-3">
                <label for="shipping_address" class="block text-xs font-medium text-gray-600">Shipping Address</label>
                <input id="shipping_address" name="shipping_address" type="text" required class="mt-1 w-full rounded border-gray-300 text-sm" placeholder="Enter your full shipping address">
                @error('shipping_address') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
            </div>
            <button class="w-full bg-gray-900 text-white text-sm py-2.5 rounded-md hover:bg-gray-800 transition font-medium">Place Order</button>
        </form>
    @else
        <div class="border border-dashed border-gray-300 rounded-lg py-10 text-center">
            <p class="text-sm text-gray-400 mb-3">Your cart is empty.</p>
            <a href="{{ route('books.index') }}" class="text-sm text-gray-500 hover:text-gray-900">Browse books &rarr;</a>
        </div>
    @endif
</div>

@endsection
