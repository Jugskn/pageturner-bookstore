@extends('layouts.app')

@section('title', 'My Account')

@section('content')
<div class="space-y-8">
    @unless ($user->hasVerifiedEmail())
        <div class="rounded-lg border border-yellow-300 bg-yellow-50 p-4 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
            <div>
                <p class="text-sm font-medium text-yellow-800">Your email address is not verified.</p>
                <p class="text-xs text-yellow-700 mt-0.5">You won't be able to place orders or write reviews until you verify. You can <a href="{{ route('profile.edit') }}" class="underline font-medium">update your email</a> if you made a typo.</p>
            </div>
            <a href="{{ route('verification.notice') }}" class="shrink-0 px-4 py-2 bg-yellow-600 text-white text-sm rounded-md hover:bg-yellow-700 transition">Verify Email</a>
        </div>
    @endunless

    {{-- Header --}}
    <div class="flex items-start justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">My Account</h1>
            <p class="text-sm text-gray-500 mt-1">Welcome back, {{ $user->name }}!</p>
        </div>
        <a href="{{ route('profile.edit') }}" class="px-4 py-2 bg-gray-900 text-white rounded-md text-sm hover:bg-gray-800 transition">
            Profile &amp; Security Settings
        </a>
    </div>

    {{-- Account Info Card --}}
    <div class="bg-white border border-gray-200 rounded-lg p-6">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <p class="text-sm text-gray-500">Signed in as</p>
                <p class="text-base font-semibold text-gray-900">{{ $user->name }}</p>
                <p class="text-sm text-gray-500">{{ $user->email }}</p>
            </div>
            <div class="flex flex-wrap gap-3">
                <div class="flex items-center gap-1.5">
                    @if ($user->hasVerifiedEmail())
                        <span class="w-2 h-2 rounded-full bg-green-500"></span>
                        <span class="text-xs text-gray-600">Email verified</span>
                    @else
                        <span class="w-2 h-2 rounded-full bg-red-500"></span>
                        <span class="text-xs text-gray-600">Email not verified</span>
                    @endif
                </div>
                <div class="flex items-center gap-1.5">
                    @if ($user->two_factor_enabled)
                        <span class="w-2 h-2 rounded-full bg-green-500"></span>
                        <span class="text-xs text-gray-600">2FA on</span>
                    @else
                        <span class="w-2 h-2 rounded-full bg-yellow-500"></span>
                        <span class="text-xs text-gray-600">2FA off</span>
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- Order Summary --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="bg-white border border-gray-200 rounded-lg p-5">
            <p class="text-xs font-medium text-gray-500 uppercase tracking-wider">Total Orders</p>
            <p class="text-2xl font-bold text-gray-900 mt-1">{{ $totalOrders }}</p>
        </div>
        @foreach (['pending', 'shipped', 'completed'] as $status)
            <div class="bg-white border border-gray-200 rounded-lg p-5">
                <p class="text-xs font-medium text-gray-500 uppercase tracking-wider">{{ ucfirst($status) }}</p>
                <p class="text-2xl font-bold text-gray-900 mt-1">{{ $orderStatuses[$status] ?? 0 }}</p>
            </div>
        @endforeach
    </div>

    <div class="grid lg:grid-cols-2 gap-6">
        {{-- Recent Orders --}}
        <div class="bg-white border border-gray-200 rounded-lg p-6">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-lg font-semibold text-gray-900">Recent Orders</h2>
                <a href="{{ route('orders.index') }}" class="text-sm text-gray-500 hover:text-gray-700">View all</a>
            </div>
            @if ($recentOrders->isEmpty())
                <p class="text-sm text-gray-400">You haven't placed any orders yet.</p>
                <a href="{{ route('books.index') }}" class="mt-2 inline-block text-sm text-blue-600 hover:underline">Browse books</a>
            @else
                <div class="space-y-3">
                    @foreach ($recentOrders as $order)
                        <a href="{{ route('orders.show', $order) }}" class="block border border-gray-100 rounded-md p-3 hover:bg-gray-50 transition">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-sm font-medium text-gray-900">Order #{{ $order->id }}</p>
                                    <p class="text-xs text-gray-500">{{ $order->placed_at?->format('M d, Y') }}</p>
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

        {{-- Recently Purchased Books --}}
        <div class="bg-white border border-gray-200 rounded-lg p-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-4">Recently Purchased Books</h2>
            @if ($recentBooks->isEmpty())
                <p class="text-sm text-gray-400">No purchased books yet.</p>
            @else
                <div class="space-y-3">
                    @foreach ($recentBooks as $book)
                        <a href="{{ route('books.show', $book) }}" class="flex items-center gap-3 border border-gray-100 rounded-md p-3 hover:bg-gray-50 transition">
                            <div class="w-10 h-14 bg-gray-200 rounded flex items-center justify-center text-gray-400 text-xs flex-shrink-0">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                            </div>
                            <div class="min-w-0">
                                <p class="text-sm font-medium text-gray-900 truncate">{{ $book->title }}</p>
                                <p class="text-xs text-gray-500">{{ $book->author }}</p>
                            </div>
                        </a>
                    @endforeach
                </div>
            @endif
        </div>
    </div>

    {{-- Review Activity --}}
    <div class="bg-white border border-gray-200 rounded-lg p-6">
        <h2 class="text-lg font-semibold text-gray-900 mb-4">Your Reviews</h2>
        @if ($reviews->isEmpty())
            <p class="text-sm text-gray-400">You haven't written any reviews yet.</p>
        @else
            <div class="space-y-3">
                @foreach ($reviews as $review)
                    <div class="border border-gray-100 rounded-md p-3">
                        <div class="flex items-start justify-between">
                            <div>
                                <a href="{{ route('books.show', $review->book) }}" class="text-sm font-medium text-gray-900 hover:underline">
                                    {{ $review->book->title ?? 'Deleted Book' }}
                                </a>
                                <p class="text-xs text-gray-500">{{ $review->created_at->format('M d, Y') }}</p>
                            </div>
                            <div class="flex items-center gap-0.5">
                                @for ($i = 1; $i <= 5; $i++)
                                    <span class="text-xs {{ $i <= $review->rating ? 'text-yellow-400' : 'text-gray-300' }}">&#9733;</span>
                                @endfor
                            </div>
                        </div>
                        @if ($review->comment)
                            <p class="mt-1 text-xs text-gray-600">{{ $review->comment }}</p>
                        @endif
                    </div>
                @endforeach
            </div>
        @endif
    </div>

    {{-- Quick Links --}}
    <div class="bg-white border border-gray-200 rounded-lg p-6">
        <h2 class="text-lg font-semibold text-gray-900 mb-4">Quick Links</h2>
        <div class="flex flex-wrap gap-3">
            <a href="{{ route('books.index') }}" class="px-4 py-2 bg-gray-100 text-gray-700 rounded-md text-sm hover:bg-gray-200 transition">Browse Books</a>
            <a href="{{ route('orders.index') }}" class="px-4 py-2 bg-gray-100 text-gray-700 rounded-md text-sm hover:bg-gray-200 transition">Order History</a>
            <a href="{{ route('profile.edit') }}" class="px-4 py-2 bg-gray-100 text-gray-700 rounded-md text-sm hover:bg-gray-200 transition">Profile &amp; Security Settings</a>
        </div>
    </div>
</div>
@endsection
