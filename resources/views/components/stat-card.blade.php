@props(['label', 'value', 'icon', 'trend' => null, 'trendUp' => true, 'iconColor' => 'primary'])

@php
    $colors = [
        'primary' => 'bg-primary-50 text-primary-600',
        'blue' => 'bg-blue-50 text-blue-600',
        'orange' => 'bg-orange-50 text-orange-600',
        'purple' => 'bg-purple-50 text-purple-600',
        'red' => 'bg-red-50 text-red-600',
        'emerald' => 'bg-emerald-50 text-emerald-600',
        'amber' => 'bg-amber-50 text-amber-600',
        'rose' => 'bg-rose-50 text-rose-600',
    ];
    $colorClass = $colors[$iconColor] ?? $colors['primary'];
@endphp

<div {{ $attributes->merge(['class' => 'bg-white p-6 rounded-2xl shadow-sm border border-gray-100 flex flex-col justify-between']) }}>
    <div class="flex items-center justify-between mb-4">
        <div class="flex items-center space-x-3">
            <div class="{{ $colorClass }} p-3 rounded-xl">
                {!! $icon !!}
            </div>
            <span class="text-xs font-bold text-gray-400 uppercase tracking-wider">{{ $label }}</span>
        </div>
    </div>
    
    <div>
        <h3 class="text-3xl font-bold text-gray-900 leading-none">{{ $value }}</h3>
        @if($trend)
            <div class="mt-2 flex items-center text-sm {{ $trendUp ? 'text-green-500' : 'text-red-500' }}">
                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    @if($trendUp)
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                    @else
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 17h8m0 0v-8m0 8l-8-8-4 4-6-6" />
                    @endif
                </svg>
                <span class="font-medium font-inter">{{ $trend }}</span>
            </div>
        @endif
    </div>
</div>
