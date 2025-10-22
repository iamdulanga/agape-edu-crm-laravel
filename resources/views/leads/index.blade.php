@extends('layouts.sidebar-layout')

@section('title', 'Leads Management')

@section('header')
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 ml-10 top-0 ">Leads Management</h1>
            <p class="mt-1 text-sm text-gray-500 ml-10">Manage and track your leads efficiently</p>
        </div>
        <div class="flex items-center space-x-2">
            <!-- Create Account Button (Only for Owners and Managers) -->
            @if (auth()->user()->hasRole('owner') || auth()->user()->hasRole('manager'))
                <button onclick="toggleModal('create-account-modal')"
                    class="inline-flex items-center rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                    <svg class="mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                        xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path>
                    </svg>
                    Create Account
                </button>
            @endif

            <!-- Export Button -->
            <form action="{{ route('leads.export') }}" method="POST" class="inline">
                @csrf
                <button type="submit"
                    class="inline-flex items-center rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                    <svg class="mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                        xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                        </path>
                    </svg>
                    Export
                </button>
            </form>

            <!-- Add Lead Button -->
            <button onclick="toggleModal('add-lead-modal')"
                class="inline-flex items-center rounded-lg bg-blue-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                <svg class="mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                    xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6">
                    </path>
                </svg>
                Add Lead
            </button>
        </div>
    </div>
@endsection

@section('content')
    @if (session('success'))
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-6">
            <div class="rounded-md bg-green-50 p-4">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-green-400" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd"
                                d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-green-800">{{ session('success') }}</p>
                    </div>
                </div>
            </div>
        </div>
    @endif

    @if (session('error'))
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-6">
            <div class="rounded-md bg-red-50 p-4">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd"
                                d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                                clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-red-800">{{ session('error') }}</p>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
        @php
            // Make sure assignableUsers is available for all partials
            if (!isset($assignableUsers)) {
                $assignableUsers = \App\Models\User::whereHas('roles', function ($query) {
                    $query->whereIn('name', ['counselor', 'manager']);
                })->get();
            }
        @endphp

        <div class="space-y-6">
            <!-- Stats Cards -->
            @include('leads.partials.stats-cards')

            <!-- Search and Filters -->
            @include('leads.partials.search-filters')

            <!-- Bulk Actions -->
            @include('leads.partials.bulk-actions')

            <div class="overflow-hidden rounded-xl bg-white shadow">
                <div class="px-6 py-5">
                    <div class="flex items-center justify-between">
                        <h3 class="text-lg font-medium text-gray-900">All Leads</h3>
                        <span
                            class="inline-flex items-center rounded-full bg-blue-100 px-3 py-1 text-sm font-medium text-blue-800">
                            {{ $leads->count() }} leads
                        </span>
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="w-12 px-2 py-3 text-left">
                                    <input type="checkbox" id="select-all"
                                        class="h-4 w-4 rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                                </th>
                                <th scope="col"
                                    class="px-2 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">
                                    Student</th>
                                <th scope="col"
                                    class="px-2 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">
                                    Age</th>
                                <th scope="col"
                                    class="px-2 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">
                                    Location</th>
                                <th scope="col"
                                    class="px-2 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">
                                    Contact</th>
                                <th scope="col"
                                    class="px-2 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">
                                    Status</th>
                                <th scope="col"
                                    class="px-2 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">
                                    Priority</th>
                                <th scope="col"
                                    class="px-2 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">
                                    Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 bg-white">
                            @forelse($leads as $lead)
                                <tr class="transition-colors hover:bg-gray-50" data-lead-id="{{ $lead->id }}">
                                    <td class="whitespace-nowrap px-2 py-4">
                                        <input type="checkbox" name="lead_ids[]" value="{{ $lead->id }}"
                                            class="lead-checkbox h-4 w-4 rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                                    </td>
                                    <td class="whitespace-nowrap px-2 py-4">
                                        <div class="flex items-center">
                                            <div class="h-10 w-10 flex-shrink-0">
                                                @if ($lead->avatar)
                                                    <img src="{{ asset('storage/' . $lead->avatar) }}"
                                                        alt="Student Picture"
                                                        class="h-10 w-10 rounded-full object-cover" />
                                                @else
                                                    <div
                                                        class="flex h-10 w-10 items-center justify-center rounded-full bg-blue-100">
                                                        <span class="font-medium text-blue-800">
                                                            {{ substr($lead->first_name, 0, 1) }}{{ substr($lead->last_name, 0, 1) }}
                                                        </span>
                                                    </div>
                                                @endif
                                            </div>
                                            <div class="ml-4">
                                                <div class="font-medium text-gray-900">
                                                    {{ $lead->first_name }} {{ $lead->last_name }}
                                                </div>
                                                <div class="text-sm text-gray-500">{{ $lead->email ?? 'No email' }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="whitespace-nowrap px-2 py-4">
                                        <div class="text-sm text-gray-900">{{ $lead->age ?? 'N/A' }}</div>
                                    </td>
                                    <td class="whitespace-nowrap px-2 py-4">
                                        <div class="flex items-center text-sm text-gray-900">
                                            <svg class="mr-1.5 h-4 w-4 text-gray-400" fill="none"
                                                stroke="currentColor" viewBox="0 0 24 24"
                                                xmlns="http://www.w3.org/2000/svg">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z">
                                                </path>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            </svg>
                                            {{ $lead->city ?? 'N/A' }}
                                        </div>
                                    </td>
                                    <td class="whitespace-nowrap px-2 py-4">
                                        <div class="flex items-center text-sm text-gray-900">
                                            <svg class="mr-1.5 h-4 w-4 text-gray-400" fill="none"
                                                stroke="currentColor" viewBox="0 0 24 24"
                                                xmlns="http://www.w3.org/2000/svg">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z">
                                                </path>
                                            </svg>
                                            {{ $lead->phone ?? 'N/A' }}
                                        </div>
                                    </td>
                                    <td class="whitespace-nowrap px-2 py-4">
                                        @include('leads.partials.status-badge', [
                                            'status' => $lead->status,
                                        ])
                                    </td>
                                    <td class="whitespace-nowrap px-2 py-4">
                                        @include('leads.partials.priority-badge', [
                                            'priority' => $lead->priority,
                                        ])
                                    </td>
                                    <!-- <td class="whitespace-nowrap px-6 py-4">
                                            @if ($lead->assignedUser)
    <div class="flex items-center">
                                                    <div class="h-8 w-8 flex-shrink-0">
                                                        <div class="flex h-8 w-8 items-center justify-center rounded-full bg-green-100">
                                                            <span class="text-xs font-medium text-green-800">
                                                                {{ substr($lead->assignedUser->name, 0, 1) }}
                                                            </span>
                                                        </div>
                                                    </div>
                                                    <div class="ml-3">
                                                        <div class="text-sm font-medium text-gray-900">{{ $lead->assignedUser->name }}</div>
                                                    </div>
                                                </div>
                                                @if (auth()->user()->hasRole('manager') || auth()->user()->hasRole('owner'))
    <button onclick="toggleModal('unassign-modal-{{ $lead->id }}')"
                                                            class="mt-1 text-xs text-red-600 hover:text-red-900">
                                                        Unassign
                                                    </button>
    @endif
@else
    <div class="flex items-center text-gray-500">
                                                    <svg class="mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                                                    </svg>
                                                    <span class="text-sm">Unassigned</span>
                                                </div>
                                                @if (auth()->user()->hasRole('manager') || auth()->user()->hasRole('owner'))
    <button onclick="toggleModal('assign-modal-{{ $lead->id }}')"
                                                            class="mt-1 text-xs text-blue-600 hover:text-blue-900">
                                                        Assign
                                                    </button>
    @endif
    @endif
                                            @once
                                                        <style>
                                                            /* Make the table fit its container to avoid horizontal scrolling */
                                                            .overflow-x-auto table { width: 100% !important; min-width: 0 !important; table-layout: auto; }
                                                            /* Reduce vertical spacing in table rows/cells */
                                                            .overflow-x-auto table th,
                                                            .overflow-x-auto table td {
                                                                padding-top: 0.5rem;
                                                                padding-bottom: 0.5rem;
                                                                padding-left: 0.75rem;
                                                                padding-right: 0.75rem;
                                                            }
                                                        </style>
                                            @endonce
                                        </td> -->

                                    <td class="whitespace-nowrap px-2 py-4">
                                        <div x-data="{ open: false }" class="relative">
                                            <!-- 3-dot button -->
                                            <button @click="open = !open"
                                                class="p-2 rounded-full hover:bg-gray-100 focus:outline-none">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-600"
                                                    fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M12 6h.01M12 12h.01M12 18h.01" />
                                                </svg>
                                            </button>

                                            <!-- Dropdown menu -->
                                            <div x-show="open" @click.away="open = false"
                                                class="absolute right-0 mt-2 w-36 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 z-50">

                                                <ul class="py-1 text-sm text-gray-700">
                                                    <li>
                                                        <a href="{{ route('leads.show', $lead) }}"
                                                            class="flex items-center px-4 py-2 hover:bg-gray-100">
                                                            <svg class="mr-2 h-4 w-4 text-gray-600" fill="none"
                                                                stroke="currentColor" viewBox="0 0 24 24"
                                                                xmlns="http://www.w3.org/2000/svg">
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z">
                                                                </path>
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    stroke-width="2"
                                                                    d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z">
                                                                </path>
                                                            </svg>
                                                            View
                                                        </a>
                                                    </li>
                                                    <!-- Status Button -->
                                                    <li>
                                                        <button onclick="toggleModal('status-modal-{{ $lead->id }}')"
                                                            class="flex w-full items-center px-4 py-2 hover:bg-gray-100">
                                                            <svg class="mr-2 h-4 w-4 text-green-600" fill="none"
                                                                stroke="currentColor" viewBox="0 0 24 24"
                                                                xmlns="http://www.w3.org/2000/svg">
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    stroke-width="2"
                                                                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z">
                                                                </path>
                                                            </svg>
                                                            Status
                                                        </button>
                                                    </li>

                                                    <!-- Edit Button -->
                                                    <li>
                                                        <a href="{{ route('leads.edit', $lead) }}"
                                                            class="flex items-center px-4 py-2 hover:bg-gray-100">
                                                            <svg class="mr-2 h-4 w-4 text-blue-600" fill="none"
                                                                stroke="currentColor" viewBox="0 0 24 24"
                                                                xmlns="http://www.w3.org/2000/svg">
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    stroke-width="2"
                                                                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                                                </path>
                                                            </svg>
                                                            Edit
                                                        </a>
                                                    </li>

                                                    <!-- Delete Button -->
                                                    <li>
                                                        <form action="{{ route('leads.destroy', $lead) }}" method="POST"
                                                            class="w-full">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit"
                                                                onclick="return confirm('Are you sure you want to delete this lead?')"
                                                                class="flex w-full items-center px-4 py-2 text-red-600 hover:bg-gray-100">
                                                                <svg class="mr-2 h-4 w-4" fill="none"
                                                                    stroke="currentColor" viewBox="0 0 24 24"
                                                                    xmlns="http://www.w3.org/2000/svg">
                                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                                        stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5
                                               4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1
                                               1 0 00-1 1v3M4 7h16"></path>
                                                                </svg>
                                                                Delete
                                                            </button>
                                                        </form>
                                                    </li>

                                                </ul>
                                            </div>
                                        </div>
                                    </td>

                                    {{-- <td class="whitespace-nowrap px-6 py-4">
                                        <div class="flex items-center space-x-2">
                                            <button onclick="toggleModal('status-modal-{{ $lead->id }}')"
                                                class="inline-flex items-center rounded-md bg-green-600 px-3 py-1.5 text-xs font-medium text-white shadow-sm hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2">
                                                <svg class="mr-1 h-4 w-4" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                </svg>
                                                Status
                                            </button>
                                            <a href="{{ route('leads.edit', $lead) }}"
                                                class="inline-flex items-center rounded-md bg-blue-600 px-3 py-1.5 text-xs font-medium text-white shadow-sm hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                                                <svg class="mr-1 h-4 w-4" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                                    </path>
                                                </svg>
                                                Edit
                                            </a>
                                            <form action="{{ route('leads.destroy', $lead) }}" method="POST"
                                                class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                    class="inline-flex items-center rounded-md bg-red-50 px-2.5 py-1.5 text-xs font-medium text-red-700 hover:bg-red-100"
                                                    <button type="submit"
                                                    class="inline-flex items-center rounded-md bg-red-600 px-3 py-1.5 text-xs font-medium text-white shadow-sm hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2"
                                                    onclick="return confirm('Are you sure you want to delete this lead?')">
                                                    <svg class="mr-1 h-4 w-4" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                                        </path>
                                                    </svg>
                                                    Delete
                                                </button>
                                            </form>
                                        </div>
                                    </td> --}}

                                </tr>

                                <!-- Status Update Modal for each lead -->
                                @include('leads.partials.status-modal', ['lead' => $lead])

                                <!-- Assignment Modal for each lead -->
                                @include('leads.partials.assign-modal', ['lead' => $lead])

                                <!-- Unassign Modal for each lead -->
                                @include('leads.partials.unassign-modal', ['lead' => $lead])

                            @empty
                                <tr>
                                    <td colspan="9" class="px-6 py-12 text-center">
                                        <div class="flex flex-col items-center justify-center">
                                            <svg class="mb-4 h-12 w-12 text-gray-400" fill="none"
                                                stroke="currentColor" viewBox="0 0 24 24"
                                                xmlns="http://www.w3.org/2000/svg">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z">
                                                </path>
                                            </svg>
                                            <h3 class="text-lg font-medium text-gray-900">No leads found</h3>
                                            <p class="mt-1 text-sm text-gray-500">Get started by creating a new lead.</p>
                                            <button onclick="toggleModal('add-lead-modal')"
                                                class="mt-4 inline-flex items-center rounded-md bg-blue-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                                                <svg class="mr-2 h-5 w-5" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                                </svg>
                                                Add New Lead
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="border-t border-gray-200 px-6 py-4">
                    {{-- {{ $leads->links() }} --}}
                </div>
            </div>
        </div>

        <!-- Add Lead Modal -->
        @include('leads.partials.create-modal')

        <!-- Bulk Assign Modal -->
        @include('leads.partials.bulk-assign-modal')

        <!-- Bulk Status Modal -->
        @include('leads.partials.bulk-status-modal')

        <!-- Create Account Modal (Only for Owners and Managers) -->
        @if (auth()->user()->hasRole('owner') || auth()->user()->hasRole('manager'))
            @include('users.partials.create-account-modal')
        @endif
    </div>
@endsection

@push('scripts')
    <script>
        function toggleModal(modalId) {
            const modal = document.getElementById(modalId);
            if (modal) {
                modal.classList.toggle('hidden');
            }
        }
        // Select all functionality
        document.getElementById('select-all').addEventListener('change', function() {
            const checkboxes = document.querySelectorAll('.lead-checkbox');
            checkboxes.forEach(checkbox => {
                checkbox.checked = this.checked;
            });
        });
        // Bulk actions form handling
        document.getElementById('bulk-assign-form').addEventListener('submit', function(e) {
            const selectedLeads = document.querySelectorAll('.lead-checkbox:checked');
            if (selectedLeads.length === 0) {
                e.preventDefault();
                alert('Please select at least one lead.');
            }
        });
        document.getElementById('bulk-status-form').addEventListener('submit', function(e) {
            const selectedLeads = document.querySelectorAll('.lead-checkbox:checked');
            if (selectedLeads.length === 0) {
                e.preventDefault();
                alert('Please select at least one lead.');
            }
        });
    </script>
@endpush
<script src="//unpkg.com/alpinejs" defer></script>
