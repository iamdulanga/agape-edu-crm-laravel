<div id="bulk-status-modal" class="fixed inset-0 z-50 hidden overflow-y-auto">
    <div class="flex min-h-screen items-end justify-center px-4 pb-20 pt-4 text-center sm:block sm:p-0">
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" 
             onclick="toggleModal('bulk-status-modal')"></div>

        <div class="relative inline-block transform overflow-hidden rounded-lg bg-white px-4 pb-4 pt-5 text-left align-bottom shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-sm sm:p-6 sm:align-middle">
            <h3 class="text-lg font-medium leading-6 text-gray-900 mb-4">
                Bulk Update Status
            </h3>

            <form id="bulk-status-form" action="{{ route('leads.bulk-status.update') }}" method="POST">
                @csrf
                <div class="space-y-2 mb-4">
                    @foreach(['new' => 'New', 'contacted' => 'Contacted', 'qualified' => 'Qualified', 'converted' => 'Converted', 'rejected' => 'Rejected'] as $value => $label)
                        <label class="flex items-center">
                            <input type="radio" name="status" value="{{ $value }}" 
                                   class="mr-3 text-blue-600 focus:ring-blue-500" required>
                            <span class="text-sm text-gray-700">{{ $label }}</span>
                        </label>
                    @endforeach
                </div>

                <!-- Hidden input for selected lead IDs will be populated by JavaScript -->
                <input type="hidden" name="lead_ids" id="bulk-status-lead-ids">

                <div class="mt-5 sm:mt-4 sm:flex sm:flex-row-reverse">
                    <button type="submit"
                            class="inline-flex w-full justify-center rounded-md border border-transparent bg-green-600 px-4 py-2 text-base font-medium text-white shadow-sm hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 sm:ml-3 sm:w-auto sm:text-sm">
                        Update Status
                    </button>
                    <button type="button" 
                            onclick="toggleModal('bulk-status-modal')"
                            class="mt-3 inline-flex w-full justify-center rounded-md border border-gray-300 bg-white px-4 py-2 text-base font-medium text-gray-700 shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 sm:mt-0 sm:w-auto sm:text-sm">
                        Cancel
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.getElementById('bulk-status-form').addEventListener('submit', function(e) {
    const selectedLeads = document.querySelectorAll('.lead-checkbox:checked');
    const leadIdsInput = document.getElementById('bulk-status-lead-ids');
    
    if (selectedLeads.length === 0) {
        e.preventDefault();
        alert('Please select at least one lead.');
        return;
    }
    
    // Populate the hidden input with selected lead IDs
    const ids = Array.from(selectedLeads).map(cb => cb.value);
    leadIdsInput.value = JSON.stringify(ids);
});
</script>