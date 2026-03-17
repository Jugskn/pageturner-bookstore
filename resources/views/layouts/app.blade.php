<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'PageTurner Bookstore')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50 text-gray-800 min-h-screen flex flex-col font-sans antialiased">

    <nav class="bg-gradient-to-r from-red-900 to-white border-b border-gray-200">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 py-3 flex items-center justify-between">
            <a href="{{ route('home') }}" class="text-lg font-bold text-white flex items-center gap-1.5">
                <span>PageTurner</span>
            </a>

            <div class="flex items-center gap-5 text-sm text-gray-700">
                <a href="{{ route('home') }}" class="hover:text-gray-900">Home</a>

                @auth
                    @if(auth()->user()->isAdmin())
                    {{-- Admin: no Books/Categories nav links --}}
                    @else
                        <span class="text-gray-300">|</span>
                        <a href="{{ route('books.index') }}" class="hover:text-gray-900">Books</a>
                        <span class="text-gray-300">|</span>
                        <a href="{{ route('categories.index') }}" class="hover:text-gray-900">Categories</a>
                    @endif
                @else
                    <span class="text-gray-300">|</span>
                    <a href="{{ route('books.index') }}" class="hover:text-gray-900">Books</a>
                    <span class="text-gray-300">|</span>
                    <a href="{{ route('categories.index') }}" class="hover:text-gray-900">Categories</a>
                @endauth

                @auth
                    @if(auth()->user()->isAdmin())
                        <span class="text-gray-300">|</span>
                        <a href="{{ route('admin.orders.index') }}" class="hover:text-gray-900">View Orders</a>
                        <span class="text-gray-300">|</span>
                        <a href="{{ route('admin.books.index') }}" class="hover:text-gray-900">Manage Books</a>
                    @else
                        <span class="text-gray-300">|</span>
                        <a href="{{ route('cart.index') }}" class="hover:text-gray-900">Cart</a>
                        <span class="text-gray-300">|</span>
                        <a href="{{ route('orders.index') }}" class="hover:text-gray-900">My Orders</a>
                    @endif
                @endauth

                <span class="text-gray-300">|</span>

                @auth
                    @if(! auth()->user()->isAdmin())
                        @php $unreadCount = auth()->user()->unreadNotifications->count(); @endphp
                        <div class="relative" x-data="{ open: false }">
                            <button @click="open = !open" @click.outside="open = false" class="relative hover:text-gray-900 focus:outline-none" title="Notifications">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                                </svg>
                                @if($unreadCount > 0)
                                    <span class="absolute -top-1.5 -right-1.5 bg-red-500 text-white text-[10px] font-bold rounded-full w-4 h-4 flex items-center justify-center leading-none">{{ $unreadCount > 9 ? '9+' : $unreadCount }}</span>
                                @endif
                            </button>

                            <div x-show="open" x-transition:enter="transition ease-out duration-100" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100" x-transition:leave="transition ease-in duration-75" x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95" class="absolute right-0 mt-2 w-80 bg-white border border-gray-200 rounded-lg shadow-lg z-50 overflow-hidden" style="display: none;">
                                <div class="px-4 py-3 border-b border-gray-100 flex items-center justify-between">
                                    <p class="text-sm font-semibold text-gray-900">Notifications</p>
                                    @if($unreadCount > 0)
                                        <form method="POST" action="{{ route('notifications.markAllAsRead') }}">
                                            @csrf
                                            <button type="submit" class="text-xs text-blue-600 hover:underline">Mark all read</button>
                                        </form>
                                    @endif
                                </div>
                                <div class="max-h-72 overflow-y-auto divide-y divide-gray-100">
                                    @forelse(auth()->user()->notifications->take(8) as $notification)
                                        <div class="px-4 py-3 text-xs {{ $notification->read_at ? 'bg-white' : 'bg-blue-50' }}">
                                            <p class="text-gray-800 leading-relaxed">
                                                {{ $notification->data['message'] ?? 'You have a new notification.' }}
                                            </p>
                                            <p class="text-gray-400 mt-1">{{ $notification->created_at->diffForHumans() }}</p>
                                        </div>
                                    @empty
                                        <div class="px-4 py-6 text-center text-xs text-gray-400">No notifications yet.</div>
                                    @endforelse
                                </div>
                                <a href="{{ route('notifications.index') }}" class="block text-center px-4 py-2.5 text-xs font-medium text-blue-600 hover:bg-gray-50 border-t border-gray-100">View all notifications</a>
                            </div>
                        </div>
                        <span class="text-gray-300">|</span>
                    @endif

                    <a href="{{ route('dashboard') }}" class="hover:text-gray-900">{{ auth()->user()->name }}</a>
                    <form action="{{ route('logout') }}" method="POST" class="inline">
                        @csrf
                        <button class="text-gray-600 hover:text-gray-900 text-xs font-medium">Logout</button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="hover:text-gray-900">Login</a>
                    <span class="text-gray-300">|</span>
                    <a href="{{ route('register') }}" class="hover:text-gray-900">Register</a>
                @endauth
            </div>
        </div>
    </nav>

    <main class="flex-grow max-w-6xl mx-auto w-full px-4 sm:px-6 py-8">
        @if (session('status'))
            <div class="mb-6 rounded-md bg-green-50 border border-green-200 px-4 py-3 text-sm text-green-800">
                {{ session('status') }}
            </div>
        @endif
        @yield('content')
    </main>

    <footer class="bg-white border-t border-gray-200 text-center py-4 text-xs text-gray-400">
        &copy; {{ date('Y') }} PageTurner Bookstore
    </footer>
</body>
</html>
