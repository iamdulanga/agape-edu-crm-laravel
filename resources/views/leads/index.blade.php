@extends('layouts.app')

@section('title', 'Leads Management')

@section('header')
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Leads Management</h1>
            <p class="mt-1 text-sm text-gray-500">Manage and track your leads efficiently</p>
        </div>
        <div class="flex items-center space-x-2">
            <!-- Export Button -->
            <form action="{{ route('leads.export') }}" method="POST" class="inline">
                @csrf
                <button type="submit" class="inline-flex items-center rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                    <svg class="mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    Export
                </button>
            </form>
            
            <!-- Add Lead Button -->
            <button 
                onclick="toggleModal('add-lead-modal')"
                class="inline-flex items-center rounded-lg bg-blue-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2"
            >
                <svg class="mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                </svg>
                Add Lead
            </button>
        </div>
    </div>
@endsection

@section('content')
    @php
        // Make sure assignableUsers is available for all partials
        if (!isset($assignableUsers)) {
            $assignableUsers = \App\Models\User::whereHas('roles', function($query) {
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
                    <span class="inline-flex items-center rounded-full bg-blue-100 px-3 py-1 text-sm font-medium text-blue-800">
                        {{ $leads->count() }} leads
                    </span>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="w-12 px-6 py-3 text-left">
                                <input type="checkbox" id="select-all" class="h-4 w-4 rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Student</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Age</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Location</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Contact</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Status</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Priority</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Assigned To</th>
                            <th scope="col" class="relative px-6 py-3">
                                <span class="sr-only">Actions</span>
                            </th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 bg-white">
                        @forelse($leads as $lead)
                            <tr class="transition-colors hover:bg-gray-50" data-lead-id="{{ $lead->id }}">
                                <td class="whitespace-nowrap px-6 py-4">
                                    <input type="checkbox" name="lead_ids[]" value="{{ $lead->id }}" class="lead-checkbox h-4 w-4 rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                                </td>
                                <td class="whitespace-nowrap px-6 py-4">
                                    <div class="flex items-center">
                                        <div class="h-10 w-10 flex-shrink-0">
                                            <div class="flex h-10 w-10 items-center justify-center rounded-full bg-blue-100">
                                                <span class="font-medium text-blue-800">
                                                    {{ substr($lead->first_name, 0, 1) }}{{ substr($lead->last_name, 0, 1) }}
                                                </span>
                                            </div>
                                        </div>
                                        <div class="ml-4">
                                            <div class="font-medium text-gray-900">
                                                {{ $lead->first_name }} {{ $lead->last_name }}
                                            </div>
                                            <div class="text-sm text-gray-500">{{ $lead->email ?? 'No email' }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="whitespace-nowrap px-6 py-4">
                                    <div class="text-sm text-gray-900">{{ $lead->age ?? 'N/A' }}</div>
                                </td>
                                <td class="whitespace-nowrap px-6 py-4">
                                    <div class="flex items-center text-sm text-gray-900">
                                        <svg class="mr-1.5 h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        </svg>
                                        {{ $lead->city ?? 'N/A' }}
                                    </div>
                                </td>
                                <td class="whitespace-nowrap px-6 py-4">
                                    <div class="flex items-center text-sm text-gray-900">
                                        <svg class="mr-1.5 h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                                        </svg>
                                        {{ $lead->phone ?? 'N/A' }}
                                    </div>
                                </td>
                                <td class="whitespace-nowrap px-6 py-4">
                                    @include('leads.partials.status-badge', ['status' => $lead->status])
                                </td>
                                <td class="whitespace-nowrap px-6 py-4">
                                    @include('leads.partials.priority-badge', ['priority' => $lead->priority])
                                </td>
                                <td class="whitespace-nowrap px-6 py-4">
                                    @if($lead->assignedUser)
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
                                        @if(auth()->user()->hasRole('manager') || auth()->user()->hasRole('owner'))
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
                                        @if(auth()->user()->hasRole('manager') || auth()->user()->hasRole('owner'))
                                            <button onclick="toggleModal('assign-modal-{{ $lead->id }}')" 
                                                    class="mt-1 text-xs text-blue-600 hover:text-blue-900">
                                                Assign
                                            </button>
                                        @endif
                                    @endif
                                </td>
                                <td class="whitespace-nowrap px-6 py-4 text-right text-sm font-medium">
                                    <div class="flex justify-end space-x-2">
                                        <button onclick="toggleModal('status-modal-{{ $lead->id }}')" 
                                                class="inline-flex items-center rounded-md bg-green-50 px-2.5 py-1.5 text-xs font-medium text-green-700 hover:bg-green-100">
                                            Status
                                        </button>
                                        <a href="{{ route('leads.edit', $lead) }}" 
                                           class="inline-flex items-center rounded-md bg-blue-50 px-2.5 py-1.5 text-xs font-medium text-blue-700 hover:bg-blue-100">
                                            Edit
                                        </a>
                                        <form action="{{ route('leads.destroy', $lead) }}" method="POST" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" 
                                                    class="inline-flex items-center rounded-md bg-red-50 px-2.5 py-1.5 text-xs font-medium text-red-700 hover:bg-red-100" 
                                                    onclick="return confirm('Are you sure you want to delete this lead?')">
                                                Delete
                                            </button>
                                        </form>
                                    </div>
                                </td>
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
                                        <svg class="mb-4 h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        <h3 class="text-lg font-medium text-gray-900">No leads found</h3>
                                        <p class="mt-1 text-sm text-gray-500">Get started by creating a new lead.</p>
                                        <button 
                                            onclick="toggleModal('add-lead-modal')"
                                            class="mt-4 inline-flex items-center rounded-md bg-blue-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2"
                                        >
                                            <svg class="mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
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
@endsection

@push('scripts')
<script>
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