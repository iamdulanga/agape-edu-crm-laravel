@php
    $statusColors = [
        'new' => 'bg-blue-100 text-blue-800',
        'contacted' => 'bg-yellow-100 text-yellow-800',
        'qualified' => 'bg-green-100 text-green-800',
        'converted' => 'bg-purple-100 text-purple-800',
        'rejected' => 'bg-red-100 text-red-800',
    ];

    $statusText = [
        'new' => 'New',
        'contacted' => 'Contacted',
        'qualified' => 'Qualified',
        'converted' => 'Converted',
        'rejected' => 'Rejected',
    ];
@endphp

<span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium {{ $statusColors[$status] ?? 'bg-gray-100 text-gray-800' }}">
    {{ $statusText[$status] ?? $status }}
</span>