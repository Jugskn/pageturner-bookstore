@extends('layouts.minimal')

@section('title', 'Verify Your Identity')

@section('content')
<div class="bg-white border border-gray-200 rounded-lg p-8 shadow-sm">
    <div class="text-center mb-6">
        <h2 class="text-xl font-bold text-gray-900">Verify Your Identity</h2>
        <p class="text-sm text-gray-500 mt-2">
            We sent a 6-digit code to <strong class="text-gray-700">{{ auth()->user()->email }}</strong>. Enter it below to continue.
        </p>
    </div>

    <form method="POST" action="{{ route('two-factor.verify') }}">
        @csrf

        <div class="mb-5">
            <label for="code" class="block text-sm font-medium text-gray-700 mb-1">OTP Code</label>
            <input type="text" name="code" id="code" autofocus autocomplete="one-time-code"
                maxlength="6" inputmode="numeric" pattern="[0-9]{6}"
                class="w-full border border-gray-300 rounded-md px-3 py-2.5 text-center text-lg tracking-[0.5em] font-mono focus:outline-none focus:ring-2 focus:ring-gray-400"
                placeholder="000000">
            @error('code')
                <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <button type="submit" class="w-full bg-gray-900 text-white rounded-md py-2.5 text-sm font-medium hover:bg-gray-800 transition">
            Verify & Continue
        </button>
    </form>

    <div class="mt-5 flex items-center justify-between">
        <form method="POST" action="{{ route('two-factor.resend') }}">
            @csrf
            <button type="submit" class="text-sm text-gray-500 hover:text-gray-700 underline">
                Resend code
            </button>
        </form>

        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="text-sm text-gray-400 hover:text-gray-600">
                Cancel &amp; logout
            </button>
        </form>
    </div>
</div>

<p class="mt-4 text-xs text-gray-400 text-center">
    The code expires in 10 minutes. Check your spam folder if you don't see it.
</p>
@endsection
