@php
    $statusColors = [
        'pending' => 'bg-yellow-100 text-yellow-800 border-yellow-300',
        'processing' => 'bg-blue-100 text-blue-800 border-blue-300',
        'completed' => 'bg-green-100 text-green-800 border-green-300',
        'failed' => 'bg-red-100 text-red-800 border-red-300',
    ];
    $color = $statusColors[$report->status] ?? 'bg-gray-100 text-gray-800';
@endphp
<span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium border {{ $color }}">
    @if($report->status === 'processing')
        <svg class="animate-spin -ml-0.5 mr-2 h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
            <path class="opacity-75" fill="currentColor"
                d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
            </path>
        </svg>
    @elseif($report->status === 'completed')
        <svg class="-ml-0.5 mr-2 h-4 w-4 text-green-500" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd"
                d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                clip-rule="evenodd" />
        </svg>
    @elseif($report->status === 'failed')
        <svg class="-ml-0.5 mr-2 h-4 w-4 text-red-500" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd"
                d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"
                clip-rule="evenodd" />
        </svg>
    @endif
    {{ $report->status_label }}
</span>