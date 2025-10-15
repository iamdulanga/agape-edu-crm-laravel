@php
$priorityColors = [
'very_high' => 'bg-red-100 text-red-800',
'high' => 'bg-orange-100 text-orange-800',
'medium' => 'bg-yellow-100 text-yellow-800',
'low' => 'bg-green-100 text-green-800',
'very_low' => 'bg-blue-100 text-blue-800',
];

$priorityText = [
'very_high' => 'Very High',
'high' => 'High',
'medium' => 'Medium',
'low' => 'Low',
'very_low' => 'Very Low',
];
@endphp

<span class="rounded-full px-2 py-1 text-xs font-medium {{ isset($priority) && is_string($priority) ? ($priorityColors[$priority] ?? 'bg-gray-100 text-gray-800') : 'bg-gray-100 text-gray-800' }}">
    {{ isset($priority) && is_string($priority) ? ($priorityText[$priority] ?? $priority) : 'Unknown' }}
</span>