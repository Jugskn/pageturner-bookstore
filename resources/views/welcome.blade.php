@extends('layouts.app')

@section('title', 'Welcome')

@section('content')

<section class="bg-white border border-gray-200 rounded-lg px-6 py-16 text-center">
    <p class="text-xs uppercase tracking-widest text-gray-400 mb-2">Welcome to</p>
    <h1 class="text-3xl font-bold text-gray-900 mb-3">PageTurner Bookstore</h1>
    <p class="text-sm text-gray-500 max-w-md mx-auto mb-8">Your online destination for discovering and ordering books.</p>
    <div class="flex justify-center gap-3">
        <a href="{{ route('home') }}" class="bg-gray-900 text-white text-sm px-5 py-2 rounded-md hover:bg-gray-800 transition">Get Started</a>
        @guest
            <a href="{{ route('login') }}" class="border border-gray-300 text-gray-700 text-sm px-5 py-2 rounded-md hover:bg-gray-50 transition">Sign In</a>
        @endguest
    </div>
</section>

@endsection
