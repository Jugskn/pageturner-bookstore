@extends('layouts.app')

@section('title', 'Verify Your Email')

@section('content')
<div class="max-w-md mx-auto">
    <div class="bg-white border border-gray-200 rounded-lg p-8">
        <h2 class="text-xl font-bold text-gray-900 mb-2">Verify Your Email Address</h2>
        <p class="text-sm text-gray-500 mb-6">
            Thanks for signing up! Before getting started, please verify your email address by clicking on the link we just emailed to you. If you didn't receive the email, we will gladly send you another.
        </p>

        @if (session('status') == 'verification-link-sent')
            <div class="mb-4 rounded-md bg-green-50 border border-green-200 px-4 py-3 text-sm text-green-800">
                A new verification link has been sent to the email address you provided during registration.
            </div>
        @endif

        <div class="flex items-center justify-between">
            <form method="POST" action="{{ route('verification.send') }}">
                @csrf
                <button type="submit" class="px-4 py-2 bg-gray-900 text-white rounded-md text-sm font-medium hover:bg-gray-800 transition">
                    Resend Verification Email
                </button>
            </form>

            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="text-sm text-gray-500 hover:text-gray-700 underline">
                    Log Out
                </button>
            </form>
        </div>
    </div>
</div>
@endsection
