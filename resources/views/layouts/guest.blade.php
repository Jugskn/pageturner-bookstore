<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>PageTurner Bookstore</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans text-gray-800 antialiased bg-gray-50">
    <div class="min-h-screen flex flex-col items-center justify-center px-4">
        <div class="mb-6 text-center">
            <a href="{{ route('home') }}" class="text-xl font-bold text-gray-900">PageTurner</a>
            <p class="mt-1 text-sm text-gray-500">Your favorite bookstore</p>
        </div>

        <div class="w-full max-w-md bg-white border border-gray-200 rounded-lg shadow-sm px-6 py-6">
            {{ $slot }}
        </div>
    </div>
</body>
</html>
