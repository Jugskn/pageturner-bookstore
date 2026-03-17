@extends('layouts.app')

@section('title', 'Order #' . $order->id)

@section('content')

<div class="max-w-3xl mx-auto">
    <div class="flex items-end justify-between mb-6">
        <div>
            <h1 class="text-xl font-bold text-gray-900">Order #{{ $order->id }}</h1>
            <p class="text-sm text-gray-500">Placed on {{ $order->created_at->format('M d, Y h:i A') }}</p>
        </div>
        <a href="{{ route('admin.orders.index') }}" class="text-sm text-gray-500 hover:text-gray-900">&larr; All Orders</a>
    </div>

    <div class="bg-white border border-gray-200 rounded-lg p-5 mb-4 grid grid-cols-2 sm:grid-cols-4 gap-4 text-sm">
        <div>
            <p class="text-xs text-gray-400">Customer</p>
            <p class="font-medium text-gray-900 mt-1">{{ $order->user->name ?? 'Deleted user' }}</p>
            <p class="text-xs text-gray-400">{{ $order->user->email ?? '' }}</p>
        </div>
        <div>
            <p class="text-xs text-gray-400">Total</p>
            <p class="font-bold text-gray-900 mt-1">₱{{ number_format($order->total_amount, 0) }}</p>
        </div>
        <div>
            <p class="text-xs text-gray-400">Shipping Address</p>
            <p class="text-gray-700 mt-1">{{ $order->shipping_address ?? '—' }}</p>
        </div>
        <div>
            <p class="text-xs text-gray-400">Status</p>
            <span class="inline-block mt-1 px-2 py-0.5 text-xs rounded-full
                @if($order->status === 'completed') bg-green-50 text-green-700 border border-green-200
                @elseif($order->status === 'pending') bg-yellow-50 text-yellow-700 border border-yellow-200
                @elseif($order->status === 'shipped') bg-blue-50 text-blue-700 border border-blue-200
                @elseif($order->status === 'received') bg-indigo-50 text-indigo-700 border border-indigo-200
                @elseif($order->status === 'paid') bg-emerald-50 text-emerald-700 border border-emerald-200
                @elseif($order->status === 'cancelled') bg-red-50 text-red-700 border border-red-200
                @else bg-gray-50 text-gray-600 border border-gray-200
                @endif
            ">{{ ucfirst($order->status) }}</span>
        </div>
    </div>

    <div class="bg-white border border-gray-200 rounded-lg p-5 mb-4">
        <p class="text-sm font-semibold text-gray-900 mb-3">Update Status</p>
        <form action="{{ route('admin.orders.updateStatus', $order) }}" method="POST" class="flex items-center gap-3">
            @csrf
            @method('PATCH')
            <select name="status" class="rounded border-gray-300 text-sm">
                <option value="pending" @selected($order->status === 'pending')>Pending</option>
                <option value="shipped" @selected($order->status === 'shipped')>Shipped</option>
                <option value="completed" @selected($order->status === 'completed')>Completed</option>
                <option value="cancelled" @selected($order->status === 'cancelled')>Cancelled</option>
            </select>
            <button class="bg-gray-900 text-white text-sm px-4 py-2 rounded-md hover:bg-gray-800 transition">Update</button>
        </form>
    </div>

    <div class="bg-white border border-gray-200 rounded-lg divide-y divide-gray-100">
        <div class="px-5 py-3">
            <p class="text-sm font-semibold text-gray-900">Items ({{ $order->items->count() }})</p>
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
