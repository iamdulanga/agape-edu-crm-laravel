@extends('layouts.sidebar-layout')

@section('content')
<div class="container mx-auto p-6">
    <div class="flex justify-between items-center mb-4">
        <h1 class="text-2xl font-bold">Notifications</h1>
        @if($notifications->count() > 0)
            <form method="POST" action="{{ route('notifications.markAllAsRead') }}">
                @csrf
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 transition">
                    Mark All as Read
                </button>
            </form>
        @endif
    </div>
    
    @if(session('success'))
        <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded">
            {{ session('success') }}
        </div>
    @endif
    
    @if($notifications->isEmpty())
        <div class="text-gray-500">No notifications found.</div>
    @else
        <ul class="divide-y divide-gray-200 bg-white rounded-lg shadow">
            @foreach($notifications as $notification)
                <li class="p-4 hover:bg-gray-50 transition">
                    <form method="POST" action="{{ route('notifications.markAsRead', $notification->id) }}" class="cursor-pointer" onsubmit="return true;">
                        @csrf
                        <button type="submit" class="w-full text-left">
                            <div class="flex items-start">
                                <span class="inline-block w-2 h-2 bg-green-500 rounded-full mt-2 mr-3"></span>
                                <div class="flex-1">
                                    <div class="font-medium text-gray-900">{{ $notification->data['message'] ?? 'Notification' }}</div>
                                    <div class="text-xs text-gray-500 mt-1">{{ $notification->created_at->diffForHumans() }}</div>
                                </div>
                                <svg class="w-5 h-5 text-gray-400 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                </svg>
                            </div>
                        </button>
                    </form>
                </li>
            @endforeach
        </ul>
    @endif
</div>
@endsection
