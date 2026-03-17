@extends('layouts.app')

@section('title', 'Manage Books')

@section('content')

<div class="flex items-end justify-between mb-6">
    <div>
        <h1 class="text-xl font-bold text-gray-900">Manage Books</h1>
        <p class="text-sm text-gray-500">{{ $books->total() }} book(s) in the catalog</p>
    </div>
    <a href="{{ route('admin.books.create') }}" class="bg-gray-900 text-white text-sm px-4 py-2 rounded-md hover:bg-gray-800 transition">+ Add Book</a>
</div>

<div class="bg-white border border-gray-200 rounded-lg overflow-hidden">
    <table class="w-full text-sm">
        <thead class="bg-gray-50 border-b border-gray-200">
            <tr>
                <th class="text-left px-4 py-3 font-medium text-gray-500 text-xs uppercase tracking-wider">Book</th>
                <th class="text-left px-4 py-3 font-medium text-gray-500 text-xs uppercase tracking-wider">Category</th>
                <th class="text-left px-4 py-3 font-medium text-gray-500 text-xs uppercase tracking-wider">Price</th>
                <th class="text-left px-4 py-3 font-medium text-gray-500 text-xs uppercase tracking-wider">Status</th>
                <th class="text-right px-4 py-3 font-medium text-gray-500 text-xs uppercase tracking-wider">Actions</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
            @forelse($books as $book)
                <tr class="hover:bg-gray-50">
                    <td class="px-4 py-3">
                        <p class="font-semibold text-gray-900">{{ $book->title }}</p>
                        <p class="text-xs text-gray-500">{{ $book->author }}</p>
                    </td>
                    <td class="px-4 py-3 text-gray-600">{{ $book->category->name ?? '—' }}</td>
                    <td class="px-4 py-3 font-medium text-gray-900">₱{{ number_format($book->price, 0) }}</td>
                    <td class="px-4 py-3">
                        <form action="{{ route('admin.books.toggleStatus', $book) }}" method="POST" class="inline">
                            @csrf
                            @method('PATCH')
                            @if($book->status === 'available')
                                <button class="px-2 py-0.5 text-xs rounded-full bg-green-50 text-green-700 border border-green-200 hover:bg-green-100 transition">Available</button>
                            @else
                                <button class="px-2 py-0.5 text-xs rounded-full bg-red-50 text-red-700 border border-red-200 hover:bg-red-100 transition">Sold</button>
                            @endif
                        </form>
                    </td>
                    <td class="px-4 py-3 text-right">
                        <div class="flex items-center justify-end gap-3">
                            <a href="{{ route('admin.books.edit', $book) }}" class="text-xs text-gray-500 hover:text-gray-900">Edit</a>
                            <form action="{{ route('admin.books.destroy', $book) }}" method="POST" onsubmit="return confirm('Delete this book?')">
                                @csrf
                                @method('DELETE')
                                <button class="text-xs text-red-500 hover:text-red-700">Delete</button>
                            </form>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="px-4 py-8 text-center text-gray-400">No books found.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

<div class="mt-4">{{ $books->links() }}</div>

@endsection
