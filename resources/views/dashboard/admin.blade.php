@extends('layouts.app')

@section('title', 'Admin Dashboard')

@section('content')
<div class="space-y-8">
    <div>
        <h1 class="text-2xl font-bold text-gray-900">Admin Dashboard</h1>
        <p class="text-sm text-gray-500 mt-1">Overview of your bookstore</p>
    </div>

    {{-- Stats Grid --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="bg-white border border-gray-200 rounded-lg p-5">
            <p class="text-xs font-medium text-gray-500 uppercase tracking-wider">Users</p>
            <p class="text-2xl font-bold text-gray-900 mt-1">{{ number_format($totalUsers) }}</p>
        </div>
        <div class="bg-white border border-gray-200 rounded-lg p-5">
            <p class="text-xs font-medium text-gray-500 uppercase tracking-wider">Books</p>
            <p class="text-2xl font-bold text-gray-900 mt-1">{{ number_format($totalBooks) }}</p>
        </div>
        <div class="bg-white border border-gray-200 rounded-lg p-5">
            <p class="text-xs font-medium text-gray-500 uppercase tracking-wider">Categories</p>
            <p class="text-2xl font-bold text-gray-900 mt-1">{{ number_format($totalCategories) }}</p>
        </div>
        <div class="bg-white border border-gray-200 rounded-lg p-5">
            <p class="text-xs font-medium text-gray-500 uppercase tracking-wider">Orders</p>
            <p class="text-2xl font-bold text-gray-900 mt-1">{{ number_format($totalOrders) }}</p>
        </div>
    </div>

    {{-- Order Status Summary --}}
    <div class="bg-white border border-gray-200 rounded-lg p-6">
        <h2 class="text-lg font-semibold text-gray-900 mb-4">Order Status Summary</h2>
        <div class="flex flex-wrap gap-3">
            @foreach (['pending', 'paid', 'shipped', 'completed', 'cancelled'] as $status)
                @php
                    $count = $orderStatuses[$status] ?? 0;
                    $colors = [
                        'pending' => 'bg-yellow-100 text-yellow-800',
                        'paid' => 'bg-blue-100 text-blue-800',
                        'shipped' => 'bg-indigo-100 text-indigo-800',
                        'completed' => 'bg-green-100 text-green-800',
                        'cancelled' => 'bg-red-100 text-red-800',
                    ];
                @endphp
                <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-sm font-medium {{ $colors[$status] }}">
                    {{ ucfirst($status) }}: {{ $count }}
                </span>
            @endforeach
        </div>
    </div>

    <div class="grid lg:grid-cols-2 gap-6">
        {{-- Recent Orders --}}
        <div class="bg-white border border-gray-200 rounded-lg p-6">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-lg font-semibold text-gray-900">Recent Orders</h2>
                <a href="{{ route('admin.orders.index') }}" class="text-sm text-gray-500 hover:text-gray-700">View all</a>
            </div>
            @if ($recentOrders->isEmpty())
                <p class="text-sm text-gray-400">No orders yet.</p>
            @else
                <div class="space-y-3">
                    @foreach ($recentOrders as $order)
                        <a href="{{ route('admin.orders.show', $order) }}" class="block border border-gray-100 rounded-md p-3 hover:bg-gray-50 transition">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-sm font-medium text-gray-900">Order #{{ $order->id }}</p>
                                    <p class="text-xs text-gray-500">{{ $order->user->name ?? 'Unknown' }}</p>
                                </div>
                                <div class="text-right">
                                    <p class="text-sm font-medium text-gray-900">₱{{ number_format($order->total_amount, 2) }}</p>
                                    @php
                                        $badge = [
                                            'pending' => 'bg-yellow-100 text-yellow-700',
                                            'paid' => 'bg-blue-100 text-blue-700',
                                            'shipped' => 'bg-indigo-100 text-indigo-700',
                                            'completed' => 'bg-green-100 text-green-700',
                                            'cancelled' => 'bg-red-100 text-red-700',
                                        ][$order->status] ?? 'bg-gray-100 text-gray-700';
                                    @endphp
                                    <span class="inline-block mt-1 px-2 py-0.5 rounded text-xs font-medium {{ $badge }}">{{ ucfirst($order->status) }}</span>
                                </div>
                            </div>
                        </a>
                    @endforeach
                </div>
            @endif
        </div>

        {{-- Recent Reviews --}}
        <div class="bg-white border border-gray-200 rounded-lg p-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-4">Recent Reviews</h2>
            @if ($recentReviews->isEmpty())
                <p class="text-sm text-gray-400">No reviews yet.</p>
            @else
                <div class="space-y-3">
                    @foreach ($recentReviews as $review)
                        <div class="border border-gray-100 rounded-md p-3">
                            <div class="flex items-start justify-between">
                                <div>
                                    <p class="text-sm font-medium text-gray-900">{{ $review->book->title ?? 'Deleted Book' }}</p>
                                    <p class="text-xs text-gray-500">by {{ $review->user->name ?? 'Unknown' }}</p>
                                </div>
                                <div class="flex items-center gap-0.5">
                                    @for ($i = 1; $i <= 5; $i++)
                                        <span class="text-xs {{ $i <= $review->rating ? 'text-yellow-400' : 'text-gray-300' }}">&#9733;</span>
                                    @endfor
                                </div>
                            </div>
                            @if ($review->comment)
                                <p class="mt-1 text-xs text-gray-600 line-clamp-2">{{ $review->comment }}</p>
                            @endif
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>

    {{-- Quick Links --}}
    <div class="bg-white border border-gray-200 rounded-lg p-6">
        <h2 class="text-lg font-semibold text-gray-900 mb-4">Quick Links</h2>
        <div class="flex flex-wrap gap-3">
            <a href="{{ route('admin.books.index') }}" class="px-4 py-2 bg-gray-900 text-white rounded-md text-sm hover:bg-gray-800 transition">Manage Books</a>
            <a href="{{ route('categories.index') }}" class="px-4 py-2 bg-gray-100 text-gray-700 rounded-md text-sm hover:bg-gray-200 transition">Manage Categories</a>
            <a href="{{ route('admin.orders.index') }}" class="px-4 py-2 bg-gray-100 text-gray-700 rounded-md text-sm hover:bg-gray-200 transition">Manage Orders</a>
        </div>
    </div>
</div>
@endsection
