@extends('layouts.app')

@section('title', 'Add Category')

@section('content')

<div class="max-w-lg mx-auto">
    <h1 class="text-xl font-bold text-gray-900 mb-6">Add Category</h1>

    <form action="{{ route('categories.store') }}" method="POST" class="bg-white border border-gray-200 rounded-lg p-5 space-y-4">
        @csrf

        <div>
            <label for="name" class="block text-xs font-medium text-gray-600">Category Name</label>
            <input id="name" name="name" type="text" value="{{ old('name') }}" required class="mt-1 w-full rounded border-gray-300 text-sm">
            @error('name') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
        </div>

        <button class="bg-gray-900 text-white text-sm px-5 py-2 rounded-md hover:bg-gray-800 transition">Save</button>
    </form>
</div>

@endsection
