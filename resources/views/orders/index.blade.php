@extends('layouts.app')

@section('title', 'My Orders')

@section('content')

<div class="max-w-3xl mx-auto">
    <h1 class="text-xl font-bold text-gray-900 mb-6">My Orders</h1>

    @if($orders->isNotEmpty())
        <div class="space-y-4">
            @foreach($orders as $order)
                <div class="bg-white border border-gray-200 rounded-lg p-4">
                    <a href="{{ route('orders.show', $order) }}" class="block">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-semibold text-gray-900">Order #{{ $order->id }}</p>
                                <p class="text-xs text-gray-500 mt-0.5">{{ $order->created_at->format('M d, Y h:i A') }}</p>
                                <p class="text-xs text-gray-400 mt-0.5">{{ $order->items->count() }} item(s)</p>
                            </div>
                            <div class="text-right">
                                <p class="text-sm font-bold text-gray-900">₱{{ number_format($order->total_amount, 0) }}</p>
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
                        </div>
                    </a>

                    <div class="mt-3 pt-3 border-t border-gray-100 flex flex-wrap gap-2">
                        @if ($order->status === 'shipped')
                            <form method="POST" action="{{ route('orders.receive', $order) }}">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="px-3 py-1.5 bg-blue-600 text-white text-xs font-semibold rounded-md hover:bg-blue-700 shadow-sm transition">
                                    Order Received
                                </button>
                            </form>
                            <form method="POST" action="{{ route('orders.cancel', $order) }}" onsubmit="return confirm('Are you sure you want to cancel this order?')">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="px-3 py-1.5 bg-red-600 text-white text-xs font-semibold rounded-md hover:bg-red-700 shadow-sm transition">
                                    Cancel Order
                                </button>
                            </form>

                        @elseif ($order->status === 'completed')
                            @php
                                $orderBookIds = $order->items->pluck('book_id')->toArray();
                                $allReviewed = !empty($orderBookIds) && empty(array_diff($orderBookIds, $reviewedBookIds));
                            @endphp
                            <a href="{{ route('orders.review', $order) }}" class="px-3 py-1.5 bg-gray-900 text-white text-xs font-semibold rounded-md hover:bg-gray-800 shadow-sm transition">
                                {{ $allReviewed ? 'Edit Review' : 'Write a Review' }}
                            </a>
                            <form method="POST" action="{{ route('orders.buyAgain', $order) }}">
                                @csrf
                                <button type="submit" class="px-3 py-1.5 bg-gray-100 text-gray-700 text-xs font-semibold rounded-md hover:bg-gray-200 transition">
                                    Buy Again
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="border border-dashed border-gray-300 rounded-lg py-10 text-center">
            <p class="text-sm text-gray-400 mb-3">You have no orders yet.</p>
            <a href="{{ route('books.index') }}" class="text-sm text-gray-500 hover:text-gray-900">Start shopping &rarr;</a>
        </div>
    @endif
</div>

@endsection
