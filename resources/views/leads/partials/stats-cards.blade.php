@php
    // Check if $leads variable exists, otherwise use empty collection
    $leads = $leads ?? collect();
    $stats = [
        'total' => [
            'count' => $leads->count(),
            'label' => 'Total Leads',
            'color' => 'blue',
            'icon' => 'bar-chart'
        ],
        'new' => [
            'count' => $leads->where('status', 'new')->count(),
            'label' => 'New Leads',
            'color' => 'green',
            'icon' => 'user-plus'
        ],
        'contacted' => [
            'count' => $leads->where('status', 'contacted')->count(),
            'label' => 'Contacted',
            'color' => 'yellow',
            'icon' => 'phone'
        ],
        'converted' => [
            'count' => $leads->where('status', 'converted')->count(),
            'label' => 'Converted',
            'color' => 'purple',
            'icon' => 'check-circle'
        ]
    ];
@endphp

<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
    @foreach($stats as $key => $stat)
        @php
            $colors = [
                'blue' => 'bg-blue-100 border-blue-300 text-blue-900',
                'green' => 'bg-green-100 border-green-300 text-green-900',
                'yellow' => 'bg-yellow-100 border-yellow-300 text-yellow-900',
                'purple' => 'bg-purple-100 border-purple-300 text-purple-900'
            ];
            $iconSvgs = [
                'bar-chart' => '<svg class="h-8 w-8 text-blue-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 3v18h18"/><rect x="7" y="13" width="3" height="5" rx="1"/><rect x="12" y="9" width="3" height="9" rx="1"/><rect x="17" y="5" width="3" height="13" rx="1"/></svg>',
                'user-plus' => '<svg class="h-8 w-8 text-green-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/><circle cx="8.5" cy="7" r="4"/><path stroke-linecap="round" stroke-linejoin="round" d="M20 8v6m3-3h-6"/></svg>',
                'phone' => '<svg class="h-8 w-8 text-yellow-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>',
                'check-circle' => '<svg class="h-8 w-8 text-purple-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4"/></svg>'
            ];
        @endphp
        <div class="{{ $colors[$stat['color']] }} border rounded-xl p-5 shadow flex items-center transition hover:scale-[1.03] hover:shadow-lg bg-opacity-90">
            <div class="mr-4 flex items-center justify-center rounded-full bg-white/80 shadow p-2">
                {!! $iconSvgs[$stat['icon']] !!}
            </div>
            <div>
                <div class="text-2xl font-extrabold leading-tight">{{ $stat['count'] }}</div>
                <div class="text-sm font-semibold opacity-80">{{ $stat['label'] }}</div>
            </div>
        </div>
    @endforeach
</div>