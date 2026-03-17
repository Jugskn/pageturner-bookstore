@extends('layouts.app')

@section('title', 'Add Book')

@section('content')

<div class="max-w-lg mx-auto">
    <h1 class="text-xl font-bold text-gray-900 mb-6">Add Book</h1>

    <form action="{{ route('admin.books.store') }}" method="POST" class="bg-white border border-gray-200 rounded-lg p-5 space-y-4">
        @csrf

        <div>
            <label for="title" class="block text-xs font-medium text-gray-600">Title</label>
            <input id="title" name="title" type="text" value="{{ old('title') }}" required class="mt-1 w-full rounded border-gray-300 text-sm">
            @error('title') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
        </div>

        <div>
            <label for="author" class="block text-xs font-medium text-gray-600">Author</label>
            <input id="author" name="author" type="text" value="{{ old('author') }}" required class="mt-1 w-full rounded border-gray-300 text-sm">
            @error('author') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
        </div>

        <div>
            <label for="price" class="block text-xs font-medium text-gray-600">Price (₱)</label>
            <input id="price" name="price" type="number" step="0.01" min="0" value="{{ old('price') }}" required class="mt-1 w-full rounded border-gray-300 text-sm">
            @error('price') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
        </div>

        <div>
            <label for="category_id" class="block text-xs font-medium text-gray-600">Category</label>
            <select id="category_id" name="category_id" class="mt-1 w-full rounded border-gray-300 text-sm">
                @foreach($categories as $category)
                    <option value="{{ $category->id }}" @selected(old('category_id') == $category->id)>{{ $category->name }}</option>
                @endforeach
            </select>
            @error('category_id') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
        </div>

        <div>
            <label for="description" class="block text-xs font-medium text-gray-600">Description</label>
            <textarea id="description" name="description" rows="4" class="mt-1 w-full rounded border-gray-300 text-sm">{{ old('description') }}</textarea>
            @error('description') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
        </div>

        <button class="bg-gray-900 text-white text-sm px-5 py-2 rounded-md hover:bg-gray-800 transition">Save</button>
    </form>
</div>

@endsection
