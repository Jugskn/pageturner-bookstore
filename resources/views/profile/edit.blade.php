@extends('layouts.app')

@section('title', 'Profile & Security Settings')

@section('content')

<div class="max-w-lg mx-auto space-y-6">
    <div>
        <a href="{{ route('dashboard') }}" class="text-sm text-gray-500 hover:text-gray-700">&larr; Back to My Account</a>
        <h1 class="text-xl font-bold text-gray-900 mt-2">Profile & Security Settings</h1>
    </div>

    <div class="bg-white border border-gray-200 rounded-lg p-5">
        @include('profile.partials.update-profile-information-form')
    </div>

    <div class="bg-white border border-gray-200 rounded-lg p-5">
        @include('profile.partials.update-password-form')
    </div>

    {{-- Two-Factor Authentication --}}
    <div class="bg-white border border-gray-200 rounded-lg p-5">
        <h2 class="text-lg font-semibold text-gray-900 mb-1">Two-Factor Authentication</h2>
        <p class="text-sm text-gray-500 mb-4">
            When enabled, you'll receive a 6-digit OTP code via email each time you log in.
        </p>

        @if (auth()->user()->two_factor_enabled)
            <div class="flex items-center gap-2 mb-4">
                <span class="inline-block w-2 h-2 rounded-full bg-green-500"></span>
                <span class="text-sm text-green-700 font-medium">2FA is enabled</span>
            </div>
            <p class="text-xs text-gray-500 mb-4">A one-time code will be sent to <strong>{{ auth()->user()->email }}</strong> on every login.</p>

            <form method="POST" action="{{ route('two-factor.disable') }}">
                @csrf
                <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded-md text-sm hover:bg-red-700 transition">
                    Disable 2FA
                </button>
            </form>
        @else
            <div class="flex items-center gap-2 mb-4">
                <span class="inline-block w-2 h-2 rounded-full bg-yellow-500"></span>
                <span class="text-sm text-gray-600">2FA is not enabled</span>
            </div>
            <form method="POST" action="{{ route('two-factor.enable') }}">
                @csrf
                <button type="submit" class="px-4 py-2 bg-gray-900 text-white rounded-md text-sm hover:bg-gray-800 transition">
                    Enable 2FA
                </button>
            </form>
        @endif
    </div>

    <div class="bg-white border border-gray-200 rounded-lg p-5">
        @include('profile.partials.delete-user-form')
    </div>
</div>

@endsection
