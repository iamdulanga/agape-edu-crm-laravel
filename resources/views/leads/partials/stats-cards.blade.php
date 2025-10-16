@php
    // Check if $leads variable exists, otherwise use empty collection
    $leads = $leads ?? collect();
    
    $stats = [
        'total' => [
            'count' => $leads->count(),
            'label' => 'Total Leads',
            'color' => 'blue',
            'icon' => '📊'
        ],
        'new' => [
            'count' => $leads->where('status', 'new')->count(),
            'label' => 'New Leads',
            'color' => 'green',
            'icon' => '🆕'
        ],
        'contacted' => [
            'count' => $leads->where('status', 'contacted')->count(),
            'label' => 'Contacted',
            'color' => 'yellow',
            'icon' => '📞'
        ],
        'converted' => [
            'count' => $leads->where('status', 'converted')->count(),
            'label' => 'Converted',
            'color' => 'purple',
            'icon' => '✅'
        ]
    ];
@endphp

<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
    @foreach($stats as $key => $stat)
        @php
            $colors = [
                'blue' => 'bg-blue-50 border-blue-200 text-blue-800',
                'green' => 'bg-green-50 border-green-200 text-green-800',
                'yellow' => 'bg-yellow-50 border-yellow-200 text-yellow-800',
                'purple' => 'bg-purple-50 border-purple-200 text-purple-800'
            ];
        @endphp
        
        <div class="{{ $colors[$stat['color']] }} border rounded-lg p-4">
            <div class="flex items-center">
                <div class="text-2xl mr-3">{{ $stat['icon'] }}</div>
                <div>
                    <div class="text-2xl font-bold">{{ $stat['count'] }}</div>
                    <div class="text-sm font-medium">{{ $stat['label'] }}</div>
                </div>
            </div>
        </div>
    @endforeach
</div>