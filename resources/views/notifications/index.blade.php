@extends('layouts.sidebar-layout')

@section('content')
<div class="container mx-auto p-6">
    <h1 class="text-2xl font-bold mb-4">Notifications</h1>
    @if($notifications->isEmpty())
        <div class="text-gray-500">No notifications found.</div>
    @else
        <ul class="divide-y divide-gray-200 bg-white rounded-lg shadow">
            @foreach($notifications as $notification)
                <li class="p-4">
                    <div class="font-medium">{{ $notification->data['message'] ?? 'Notification' }}</div>
                    <div class="text-xs text-gray-500">{{ $notification->created_at->diffForHumans() }}</div>
                </li>
            @endforeach
        </ul>
    @endif
</div>
@endsection
