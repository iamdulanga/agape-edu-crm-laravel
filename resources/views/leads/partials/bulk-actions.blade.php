<div class="mb-4 bg-yellow-50 p-4 rounded-lg border hidden" id="bulk-actions-panel">
    <div class="flex items-center justify-between">
        <div>
            <span id="selected-count" class="font-medium">0</span> leads selected
        </div>
        <div class="flex space-x-2">
            <!-- Note: Bulk Assign functionality has been removed as assigned_to column is obsolete -->

            <!-- Bulk Status -->
            <button type="button" onclick="toggleModal('bulk-status-modal')" 
                    class="bg-green-600 text-white px-3 py-1 rounded text-sm hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500">
                Update Status
            </button>

            <!-- Export Selected -->
            <form action="{{ route('leads.export.filtered') }}" method="POST" class="inline">
                @csrf
                <input type="hidden" name="lead_ids" id="export-lead-ids">
                <input type="hidden" name="search" value="{{ request('search') }}">
                <input type="hidden" name="status" value="{{ request('status') }}">
                <button type="submit" class="bg-purple-600 text-white px-3 py-1 rounded text-sm hover:bg-purple-700 focus:outline-none focus:ring-2 focus:ring-purple-500">
                    Export Selected
                </button>
            </form>

            <!-- Clear Selection -->
            <button type="button" onclick="clearSelection()" 
                    class="bg-gray-500 text-white px-3 py-1 rounded text-sm hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-gray-500">
                Clear
            </button>
        </div>
    </div>
</div>

<script>
function updateBulkActions() {
    const selected = document.querySelectorAll('.lead-checkbox:checked');
    const panel = document.getElementById('bulk-actions-panel');
    const count = document.getElementById('selected-count');
    const exportIds = document.getElementById('export-lead-ids');
    
    if (selected.length > 0) {
        panel.classList.remove('hidden');
        count.textContent = selected.length;
        
        // Update export form with selected IDs
        const ids = Array.from(selected).map(cb => cb.value);
        exportIds.value = JSON.stringify(ids);
    } else {
        panel.classList.add('hidden');
    }
}

function clearSelection() {
    document.querySelectorAll('.lead-checkbox').forEach(cb => cb.checked = false);
    document.getElementById('select-all').checked = false;
    updateBulkActions();
}

// Initialize
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.lead-checkbox').forEach(checkbox => {
        checkbox.addEventListener('change', updateBulkActions);
    });
    document.getElementById('select-all').addEventListener('change', updateBulkActions);
});
</script>