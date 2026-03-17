@extends('layouts.app')

@section('title', 'Notifications')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-xl font-bold text-gray-900">Notifications</h1>
        @if(auth()->user()->unreadNotifications->count() > 0)
            <form method="POST" action="{{ route('notifications.markAllAsRead') }}">
                @csrf
                <button type="submit" class="text-sm text-blue-600 hover:underline">Mark all as read</button>
            </form>
        @endif
    </div>

    @if($notifications->isNotEmpty())
        <div class="bg-white border border-gray-200 rounded-lg divide-y divide-gray-100 overflow-hidden">
            @foreach($notifications as $notification)
                <div class="px-5 py-4 flex items-start gap-4 {{ $notification->read_at ? 'bg-white' : 'bg-blue-50' }}">
                    <div class="flex-shrink-0 mt-0.5">
                        @if(! $notification->read_at)
                            <span class="block w-2 h-2 rounded-full bg-blue-500"></span>
                        @else
                            <span class="block w-2 h-2 rounded-full bg-gray-300"></span>
                        @endif
                    </div>
                    <div class="flex-grow min-w-0">
                        <p class="text-sm text-gray-800 leading-relaxed">
                            {{ $notification->data['message'] ?? 'You have a new notification.' }}
                        </p>
                        <p class="text-xs text-gray-400 mt-1">{{ $notification->created_at->diffForHumans() }}</p>
                    </div>
                    @if(! $notification->read_at)
                        <form method="POST" action="{{ route('notifications.markAsRead', $notification->id) }}" class="flex-shrink-0">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="text-xs text-gray-400 hover:text-gray-600" title="Mark as read">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                                </svg>
                            </button>
                        </form>
                    @endif
                </div>
            @endforeach
        </div>

        <div class="mt-4">
            {{ $notifications->links() }}
        </div>
    @else
        <div class="border border-dashed border-gray-300 rounded-lg py-10 text-center">
            <p class="text-sm text-gray-400">No notifications yet.</p>
        </div>
    @endif
</div>
@endsection
