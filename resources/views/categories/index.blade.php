@extends('layouts.app')

@section('title', 'Categories')

@section('content')

<div class="max-w-3xl mx-auto">
    <div class="mb-6">
        <h1 class="text-xl font-bold text-gray-900">Categories</h1>
        <p class="text-sm text-gray-500">Browse books by category.</p>
    </div>

    @if($categories->isNotEmpty())
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            @foreach($categories as $category)
                <a href="{{ route('categories.show', $category) }}" class="bg-white border border-gray-200 rounded-lg p-4 flex items-center justify-between hover:shadow-md transition">
                    <div>
                        <p class="font-semibold text-gray-900 text-sm">{{ $category->name }}</p>
                        <p class="text-xs text-gray-400 mt-0.5">{{ $category->books()->count() }} books</p>
                    </div>
                    <span class="text-xs text-gray-400">View &rarr;</span>
                </a>
            @endforeach
        </div>
    @else
        <div class="border border-dashed border-gray-300 rounded-lg py-10 text-center text-sm text-gray-400">
            No categories found.
        </div>
    @endif
</div>

@endsection
