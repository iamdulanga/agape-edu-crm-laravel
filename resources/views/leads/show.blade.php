@extends('layouts.sidebar-layout')


@section('content')
<div class="max-w-6xl mx-auto py-12 px-4 sm:px-6 lg:px-8">

    <!-- HEADER -->
    <div class="bg-gradient-to-r from-blue-600 to-indigo-600 rounded-2xl shadow-lg p-8 flex flex-col md:flex-row items-center md:items-end justify-between space-y-4 md:space-y-0 mb-10">
        <div class="flex items-center space-x-6">
            <div class="relative">
                @if($lead->avatar)
                    <img src="{{ asset('storage/' . $lead->avatar) }}" alt="Avatar"
                         class="h-28 w-28 rounded-full border-4 border-white shadow-xl object-cover">
                @else
                    <div class="h-28 w-28 rounded-full bg-white/20 flex items-center justify-center shadow-xl">
                        <span class="text-3xl font-bold text-white">
                            {{ strtoupper(substr($lead->first_name, 0, 1)) }}{{ strtoupper(substr($lead->last_name, 0, 1)) }}
                        </span>
                    </div>
                @endif
                <span class="absolute bottom-1 right-1 block h-5 w-5 rounded-full ring-2 ring-white
                    {{ $lead->status === 'active' ? 'bg-green-400' : 'bg-gray-400' }}">
                </span>
            </div>

            <div>
                <h1 class="text-3xl font-bold text-white tracking-wide">
                    {{ $lead->first_name }} {{ $lead->last_name }}
                </h1>
                <p class="text-indigo-100">{{ $lead->email ?? 'No email provided' }}</p>
                <div class="mt-3 space-x-2">
                    @include('leads.partials.status-badge', ['status' => $lead->status])
                    @include('leads.partials.priority-badge', ['priority' => $lead->priority])
                </div>
            </div>
        </div>

        <div class="flex space-x-3">
            <a href="{{ route('leads.edit', $lead->id) }}"
               class="inline-flex items-center px-4 py-2 bg-white text-blue-700 text-sm font-semibold rounded-md shadow hover:bg-gray-100 transition">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" stroke-width="2"
                     viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round"
                     d="M11 5h2m-1 0v14m-7 4h14a2 2 0 002-2V7l-6-6H6a2 2 0 00-2 2v16a2 2 0 002 2z"/></svg>
                Edit
            </a>
            <a href="{{ route('leads.index') }}"
               class="inline-flex items-center px-4 py-2 bg-white/20 text-white text-sm font-semibold rounded-md hover:bg-white/30 transition">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" stroke-width="2"
                     viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round"
                     d="M15 19l-7-7 7-7" /></svg>
                Back
            </a>
        </div>
    </div>

    <!-- INFO SECTIONS -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
        <!-- PERSONAL INFO -->
        <div class="bg-white shadow-xl rounded-2xl border border-gray-100 p-6 hover:shadow-2xl transition">
            <h2 class="text-lg font-semibold text-gray-700 mb-4 flex items-center">
                <svg class="w-5 h-5 mr-2 text-blue-600" fill="none" stroke="currentColor" stroke-width="2"
                     viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round"
                     d="M5.121 17.804A10 10 0 1121 12v.01M12 12v.01" /></svg>
                Personal Information
            </h2>
            <div class="divide-y divide-gray-100">
                <div class="py-2 flex justify-between"><span class="text-gray-500">Full Name</span><span class="font-medium text-gray-800">{{ $lead->first_name }} {{ $lead->last_name }}</span></div>
                <div class="py-2 flex justify-between"><span class="text-gray-500">Age</span><span class="font-medium text-gray-800">{{ $lead->age ?? 'N/A' }}</span></div>
                <div class="py-2 flex justify-between"><span class="text-gray-500">City</span><span class="font-medium text-gray-800">{{ $lead->city ?? 'N/A' }}</span></div>
                <div class="py-2 flex justify-between"><span class="text-gray-500">Phone</span><span class="font-medium text-gray-800">{{ $lead->phone ?? 'N/A' }}</span></div>
                <div class="py-2 flex justify-between"><span class="text-gray-500">Email</span><span class="font-medium text-gray-800">{{ $lead->email ?? 'N/A' }}</span></div>
            </div>
        </div>

        <!-- STUDY INFO -->
        <div class="bg-white shadow-xl rounded-2xl border border-gray-100 p-6 hover:shadow-2xl transition">
            <h2 class="text-lg font-semibold text-gray-700 mb-4 flex items-center">
                <svg class="w-5 h-5 mr-2 text-blue-600" fill="none" stroke="currentColor" stroke-width="2"
                     viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round"
                     d="M8 7V3m8 4V3M5 11h14M5 19h14M9 15h6" /></svg>
                Study & Inquiry Details
            </h2>
            <div class="divide-y divide-gray-100">
                <div class="py-2 flex justify-between"><span class="text-gray-500">Passport</span><span class="font-medium text-gray-800">{{ ucfirst($lead->passport ?? 'N/A') }}</span></div>
                <div class="py-2 flex justify-between"><span class="text-gray-500">Study Level</span><span class="font-medium text-gray-800">{{ ucfirst($lead->study_level ?? 'N/A') }}</span></div>
                <div class="py-2 flex justify-between"><span class="text-gray-500">Inquiry Date</span><span class="font-medium text-gray-800">{{ $lead->inquiry_date ? $lead->inquiry_date->format('Y-m-d') : 'N/A' }}</span></div>
                <div class="py-2 flex justify-between"><span class="text-gray-500">Preferred Universities</span><span class="font-medium text-gray-800 text-right">{{ $lead->preferred_universities ?? 'None' }}</span></div>
                <div class="py-2 flex flex-col">
                    <span class="text-gray-500 mb-1">Special Notes</span>
                    <p class="font-medium text-gray-800 bg-gray-50 p-3 rounded-md">{{ $lead->special_notes ?? 'No notes added' }}</p>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection
