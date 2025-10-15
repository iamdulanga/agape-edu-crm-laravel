<div class="mt-6 grid grid-cols-1 gap-6 md:grid-cols-3">
    <div class="rounded-lg bg-blue-50 p-4">
        <h4 class="font-semibold text-blue-800">Team Leads</h4>
        <p class="text-2xl font-bold text-blue-600">{{ $teamLeads ?? 0 }}</p>
    </div>
    <div class="rounded-lg bg-green-50 p-4">
        <h4 class="font-semibold text-green-800">Assigned to Me</h4>
        <p class="text-2xl font-bold text-green-600">{{ $assignedLeads ?? 0 }}</p>
    </div>
    <div class="rounded-lg bg-purple-50 p-4">
        <h4 class="font-semibold text-purple-800">Team Performance</h4>
        <p class="text-2xl font-bold text-purple-600">{{ $teamPerformance ?? 0 }}%</p>
    </div>
</div>