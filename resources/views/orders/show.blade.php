@extends('layouts.app')

@section('title', 'Order #' . $order->id)

@section('content')

<div class="max-w-3xl mx-auto">
    <div class="flex items-end justify-between mb-6">
        <div>
            <h1 class="text-xl font-bold text-gray-900">Order #{{ $order->id }}</h1>
            <p class="text-sm text-gray-500">Placed on {{ $order->created_at->format('M d, Y h:i A') }}</p>
        </div>
        <a href="{{ route('orders.index') }}" class="text-sm text-gray-500 hover:text-gray-900">&larr; My Orders</a>
    </div>

    <div class="bg-white border border-gray-200 rounded-lg p-5 mb-4 grid grid-cols-2 sm:grid-cols-3 gap-4 text-sm">
        <div>
            <p class="text-xs text-gray-400">Status</p>
            @php
                $badgeClass = match($order->status) {
                    'completed' => 'bg-green-50 text-green-700 border-green-200',
                    'shipped' => 'bg-blue-50 text-blue-700 border-blue-200',
                    'cancelled' => 'bg-red-50 text-red-700 border-red-200',
                    default => 'bg-gray-50 text-gray-600 border-gray-200',
                };
            @endphp
            <span class="inline-block mt-1 px-2 py-0.5 text-xs rounded-full border {{ $badgeClass }}">{{ ucfirst($order->status) }}</span>
        </div>
        <div>
            <p class="text-xs text-gray-400">Total</p>
            <p class="font-bold text-gray-900 mt-1">₱{{ number_format($order->total_amount, 0) }}</p>
        </div>
        <div class="col-span-2 sm:col-span-1">
            <p class="text-xs text-gray-400">Shipping Address</p>
            <p class="text-gray-700 mt-1">{{ $order->shipping_address }}</p>
        </div>
    </div>

    {{-- Action Buttons --}}
    <div class="mb-4 flex flex-wrap gap-2">
        @if ($order->status === 'shipped')
            <form method="POST" action="{{ route('orders.receive', $order) }}">
                @csrf
                @method('PATCH')
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white text-sm font-semibold rounded-md hover:bg-blue-700 shadow-sm transition">
                    Order Received
                </button>
            </form>
            <form method="POST" action="{{ route('orders.cancel', $order) }}" onsubmit="return confirm('Are you sure you want to cancel this order?')">
                @csrf
                @method('PATCH')
                <button type="submit" class="px-4 py-2 bg-red-600 text-white text-sm font-semibold rounded-md hover:bg-red-700 shadow-sm transition">
                    Cancel Order
                </button>
            </form>

        @elseif ($order->status === 'completed')
            @php
                $orderBookIds = $order->items->pluck('book_id')->toArray();
                $allReviewed = !empty($orderBookIds) && empty(array_diff($orderBookIds, $reviewedBookIds));
            @endphp
            <a href="{{ route('orders.review', $order) }}" class="px-4 py-2 bg-gray-900 text-white text-sm font-semibold rounded-md hover:bg-gray-800 shadow-sm transition">
                {{ $allReviewed ? 'Edit Review' : 'Write a Review' }}
            </a>
            <form method="POST" action="{{ route('orders.buyAgain', $order) }}">
                @csrf
                <button type="submit" class="px-4 py-2 bg-gray-100 text-gray-700 text-sm font-semibold rounded-md hover:bg-gray-200 transition">
                    Buy Again
                </button>
            </form>
        @endif
    </div>

    <div class="bg-white border border-gray-200 rounded-lg divide-y divide-gray-100">
        <div class="px-5 py-3">
            <p class="text-sm font-semibold text-gray-900">Items</p>
        </div>
        @foreach($order->items as $item)
            <div class="flex items-center gap-4 px-5 py-3">
                <div class="w-12 h-12 flex-shrink-0 bg-gray-100 rounded overflow-hidden">
                    <img src="https://images.unsplash.com/photo-1512820790803-83ca734da794?w=400&q=80" alt="{{ $item->book->title ?? 'Book' }}" class="w-full h-full object-cover">
                </div>
                <div class="flex-grow min-w-0">
                    <p class="text-sm font-semibold text-gray-900 truncate">{{ $item->book->title ?? 'Deleted book' }}</p>
                    <p class="text-xs text-gray-500">Qty: {{ $item->quantity }} &times; ₱{{ number_format($item->unit_price, 0) }}</p>
                </div>
                <p class="text-sm font-bold text-gray-900">₱{{ number_format($item->quantity * $item->unit_price, 0) }}</p>
            </div>
        @endforeach
    </div>
</div>

@endsection
