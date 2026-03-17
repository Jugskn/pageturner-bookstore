<nav x-data="{ open: false }" class="bg-white border-b border-gray-200">
    <div class="max-w-6xl mx-auto px-4 sm:px-6">
        <div class="flex items-center justify-between h-14">
            <a href="{{ route('home') }}" class="text-lg font-bold text-gray-900">PageTurner</a>

            <div class="hidden sm:flex items-center gap-5 text-sm text-gray-600">
                <a href="{{ route('home') }}" class="hover:text-gray-900">Home</a>
                <span class="text-gray-300">|</span>
                <a href="{{ route('books.index') }}" class="hover:text-gray-900">Books</a>
                <span class="text-gray-300">|</span>
                <a href="{{ route('categories.index') }}" class="hover:text-gray-900">Categories</a>
                <span class="text-gray-300">|</span>
                <a href="{{ route('orders.index') }}" class="hover:text-gray-900">My Orders</a>
                @if(Auth::user()?->role === 'admin')
                    <span class="text-gray-300">|</span>
                    <a href="/admin/books" class="hover:text-gray-900">Admin</a>
                @endif

                <span class="text-gray-300">|</span>
                <span class="text-xs text-gray-500">{{ Auth::user()->name }}</span>
                <a href="{{ route('profile.edit') }}" class="text-xs text-gray-500 hover:text-gray-900">Profile</a>
                <form method="POST" action="{{ route('logout') }}" class="inline">
                    @csrf
                    <button class="text-xs text-gray-500 hover:text-gray-900">Log out</button>
                </form>
            </div>

            <div class="sm:hidden">
                <button @click="open = !open" class="p-2 text-gray-500 hover:text-gray-700">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path :class="{'hidden': open}" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': !open}" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <div :class="{'block': open, 'hidden': !open}" class="hidden sm:hidden border-t border-gray-200 py-2 px-4 space-y-1 text-sm text-gray-600">
        <a href="{{ route('home') }}" class="block py-1 hover:text-gray-900">Home</a>
        <a href="{{ route('books.index') }}" class="block py-1 hover:text-gray-900">Books</a>
        <a href="{{ route('categories.index') }}" class="block py-1 hover:text-gray-900">Categories</a>
        <a href="{{ route('orders.index') }}" class="block py-1 hover:text-gray-900">My Orders</a>
        @if(Auth::user()?->role === 'admin')
            <a href="/admin/books" class="block py-1 hover:text-gray-900">Admin</a>
        @endif
        <div class="pt-2 mt-2 border-t border-gray-100 text-xs text-gray-500">
            <p>{{ Auth::user()->name }}</p>
            <a href="{{ route('profile.edit') }}" class="block py-1 hover:text-gray-900">Profile</a>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button class="py-1 hover:text-gray-900">Log out</button>
            </form>
        </div>
    </div>
</nav>
