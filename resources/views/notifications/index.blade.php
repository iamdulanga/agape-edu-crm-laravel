@extends('layouts.sidebar-layout')

@section('content')
<div class="container mx-auto p-6">
    <div class="flex flex-col gap-4 mb-4">
        <div class="flex justify-between items-center">
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

        <form method="GET" action="{{ route('notifications.index') }}" class="flex flex-wrap gap-3 items-end bg-white p-3 rounded shadow">
            <div>
                <label for="type" class="block text-sm text-gray-700 mb-1">Type</label>
                <select id="type" name="type" class="border rounded px-3 py-2">
                    <option value="">All</option>
                    @php
                        $labels = [
                            'lead_created' => 'Lead Created',
                            'lead_updated' => 'Lead Updated',
                            'lead_deleted' => 'Lead Deleted',
                            'follow_up_due' => 'Follow-up Due Today',
                            'follow_up_due_soon' => 'Follow-up Due in 3 Days',
                        ];
                    @endphp
                    @foreach(($types ?? []) as $opt)
                        <option value="{{ $opt }}" @selected(($type ?? '') === $opt)>{{ $labels[$opt] ?? $opt }}</option>
                    @endforeach
                </select>
            </div>
            <div class="flex items-center gap-2">
                <input type="checkbox" id="group" name="group" value="1" @checked(!empty($group))>
                <label for="group" class="text-sm text-gray-700">Group by type</label>
            </div>
            <div class="ml-auto flex gap-2">
                <button type="submit" class="px-4 py-2 bg-gray-700 text-white rounded hover:bg-gray-800">Apply</button>
                <a href="{{ route('notifications.index') }}" class="px-4 py-2 bg-gray-200 text-gray-800 rounded hover:bg-gray-300">Reset</a>
            </div>
        </form>
    </div>
    
    @if(session('success'))
        <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded">
            {{ session('success') }}
        </div>
    @endif
    
    @if($notifications->isEmpty())
        <div class="text-gray-500">No notifications found.</div>
    @else
        @php
            $labels = $labels ?? [
                'lead_created' => 'Lead Created',
                'lead_updated' => 'Lead Updated',
                'lead_deleted' => 'Lead Deleted',
                'follow_up_due' => 'Follow-up Due Today',
                'follow_up_due_soon' => 'Follow-up Due in 3 Days',
            ];
        @endphp

        @if(!empty($group) && isset($grouped) && $grouped->isNotEmpty())
            @foreach($grouped as $groupType => $items)
                <div class="mb-6">
                    <h2 class="text-lg font-semibold text-gray-800 mb-2">{{ $labels[$groupType] ?? ucfirst(str_replace('_',' ', $groupType)) }}</h2>
                    <ul class="divide-y divide-gray-200 bg-white rounded-lg shadow">
                        @foreach($items as $notification)
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
                </div>
            @endforeach
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
    @endif
</div>
@endsection
