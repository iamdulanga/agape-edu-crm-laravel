<div class="mt-6 grid grid-cols-1 gap-6 md:grid-cols-3">
    <div class="rounded-lg bg-blue-50 p-4">
        <h4 class="font-semibold text-blue-800">My Leads</h4>
        <p class="text-2xl font-bold text-blue-600">{{ $myLeads ?? 0 }}</p>
    </div>
    <div class="rounded-lg bg-green-50 p-4">
        <h4 class="font-semibold text-green-800">New Assignments</h4>
        <p class="text-2xl font-bold text-green-600">{{ $newAssignments ?? 0 }}</p>
    </div>
    <div class="rounded-lg bg-purple-50 p-4">
        <h4 class="font-semibold text-purple-800">Follow-ups Today</h4>
        <p class="text-2xl font-bold text-purple-600">{{ $followUpsToday ?? 0 }}</p>
    </div>
</div>