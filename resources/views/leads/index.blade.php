@extends('layouts.app')

@section('title', 'Leads Management')

@section('header')
    <h2 class="text-xl font-semibold leading-tight text-gray-800">
        Leads Management
    </h2>
@endsection

<div>
    @if (session('success'))
    <div class="alert alert-success alert-dismissible fade show mt-2" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

@if (session('error'))
    <div class="alert alert-danger alert-dismissible fade show mt-2" role="alert">
        {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

</div>

@section('content')
    <div class="py-12">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
            <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="mb-6 flex items-center justify-between">
                        <h3 class="text-lg font-medium">All Leads</h3>
                        <button
                            onclick="toggleModal('add-lead-modal')"
                            class="rounded-md bg-blue-600 px-4 py-2 text-white hover:bg-blue-700"
                        >
                            Add New Lead
                        </button>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Student Name</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Age</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">City</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Contact</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Email</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Passport</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Inquiry Date</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Study Level</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Priority</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Preferred Universities</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200 bg-white">
                                @forelse($leads as $lead)
                                    <tr class="hover:bg-gray-50">
                                        <td class="whitespace-nowrap px-6 py-4">
                                            <div class="font-medium text-gray-900">
                                                {{ $lead->first_name }} {{ $lead->last_name }}
                                            </div>
                                        </td>
                                        <td class="whitespace-nowrap px-6 py-4">
                                            <div class="text-sm text-gray-900">{{ $lead->age ?? 'N/A' }}</div>
                                        </td>
                                        <td class="whitespace-nowrap px-6 py-4">
                                            <div class="text-sm text-gray-900">{{ $lead->city ?? 'N/A' }}</div>
                                        </td>
                                        <td class="whitespace-nowrap px-6 py-4">
                                            <div class="text-sm text-gray-900">{{ $lead->phone ?? 'N/A' }}</div>
                                        </td>
                                        <td class="whitespace-nowrap px-6 py-4">
                                            <div class="text-sm text-gray-900">{{ $lead->email ?? 'N/A' }}</div>
                                        </td>
                                        <td class="whitespace-nowrap px-6 py-4">
                                            <div class="text-sm capitalize text-gray-900">{{ $lead->passport ?? 'N/A' }}</div>
                                        </td>
                                        <td class="whitespace-nowrap px-6 py-4">
                                            <div class="text-sm text-gray-900">
                                                {{ $lead->inquiry_date ? \Carbon\Carbon::parse($lead->inquiry_date)->format('m/d/Y') : 'N/A' }}
                                            </div>
                                        </td>
                                        <td class="whitespace-nowrap px-6 py-4">
                                            <div class="text-sm capitalize text-gray-900">{{ $lead->study_level ?? 'N/A' }}</div>
                                        </td>
                                        <td class="whitespace-nowrap px-6 py-4">
                                            @include('leads.partials.priority-badge', ['priority' => $lead->priority])
                                        </td>
                                        <td class="px-6 py-4 text-sm text-gray-900">
                                            {{ $lead->preferred_universities ?? 'N/A' }}
                                        </td>
                                        <td class="whitespace-nowrap px-6 py-4 text-sm font-medium">
                                           <button
    class="mr-3 text-blue-600 hover:text-blue-900"
    onclick="openEditModal({{ $lead->toJson() }})">
    Edit
</button>
                                            <form action="{{ route('leads.destroy', $lead) }}" method="POST" class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-600 hover:text-red-900" onclick="return confirm('Are you sure?')">Delete</button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="11" class="px-6 py-4 text-center text-sm text-gray-500">
                                            No leads found. Create your first lead!
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Add Lead Modal -->
    @include('leads.partials.create-modal')

    @push('scripts')
<script>
function toggleModal(id) {
    const modal = document.getElementById(id);
    modal.classList.toggle('hidden');
}

// 🧠 Open Edit Modal & Fill Data
function openEditModal(lead) {
    document.getElementById('edit_lead_id').value = lead.id;
    document.getElementById('edit_first_name').value = lead.first_name || '';
    document.getElementById('edit_last_name').value = lead.last_name || '';
    document.getElementById('edit_age').value = lead.age || '';
    document.getElementById('edit_city').value = lead.city || '';
    document.getElementById('edit_email').value = lead.email || '';
    document.getElementById('edit_phone').value = lead.phone || '';
    document.getElementById('edit_passport').value = lead.passport || '';
    document.getElementById('edit_inquiry_date').value = lead.inquiry_date || '';
    document.getElementById('edit_study_level').value = lead.study_level || '';
    document.getElementById('edit_priority').value = lead.priority || '';
    document.getElementById('edit_preferred_universities').value = lead.preferred_universities || '';
    document.getElementById('edit_special_notes').value = lead.special_notes || '';

    toggleModal('edit-lead-modal');
}

// 🧩 Handle Edit Form Submit (AJAX)
document.getElementById('edit-lead-form').addEventListener('submit', function(e) {
    e.preventDefault();
    const leadId = document.getElementById('edit_lead_id').value;
    const formData = new FormData(this);

    fetch(`/leads/${leadId}`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': formData.get('_token'),
            'Accept': 'application/json',
        },
        body: formData
    })
    .then(response => {
        if (!response.ok) throw new Error('Failed to update lead');
        return response.text();
    })
    .then(() => {
        toggleModal('edit-lead-modal');
        location.reload(); // ✅ reload table (simplest way)
    })
    .catch(err => {
        console.error(err);
        alert('Error updating lead');
    });
});
</script>
@endpush

 <!-- Add Lead Modal -->
    @include('leads.partials.create-modal')

    <!-- Edit Lead Modal -->
    @include('leads.partials.edit-modal')
@endsection
