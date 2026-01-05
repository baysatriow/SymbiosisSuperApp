@props(['title', 'subtitle' => null])

<div {{ $attributes->merge(['class' => 'flex flex-col md:flex-row md:items-center justify-between mb-8 space-y-4 md:space-y-0']) }}>
    <div>
        <h1 class="text-2xl font-bold text-gray-900">{{ $title }}</h1>
        @if($subtitle)
            <p class="text-gray-500 mt-1">{{ $subtitle }}</p>
        @endif
    </div>
    <div class="flex items-center space-x-3">
        {{ $actions ?? '' }}
    </div>
</div>
