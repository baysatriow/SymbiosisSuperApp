<x-app-layout>
    <div class="max-w-6xl mx-auto">
        <!-- Header -->
        <div
            class="mb-6 bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
            <div class="bg-gradient-to-r from-green-600 to-emerald-600 p-6">
                <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
                    <div class="text-white">
                        <h2 class="text-2xl font-bold tracking-tight flex items-center gap-3">
                            <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                            Laporan ESG
                        </h2>
                        <p class="mt-1 text-green-100 text-sm">Generate laporan Environmental, Social, dan Governance
                            dengan AI</p>
                    </div>
                    <a href="{{ route('user.esg.create') }}"
                        class="inline-flex items-center gap-2 px-5 py-2.5 bg-white text-green-700 font-semibold rounded-lg shadow-md hover:bg-green-50 transition-all hover:shadow-lg">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                        Buat Laporan Baru
                    </a>
                </div>
            </div>

            <!-- Stats Bar -->
            <div class="px-6 py-4 bg-gray-50 dark:bg-gray-700/50 border-t border-gray-200 dark:border-gray-700">
                <div class="flex flex-wrap gap-6 text-sm">
                    <div class="flex items-center gap-2 text-gray-600 dark:text-gray-300">
                        <span
                            class="w-6 h-6 rounded-full bg-green-100 dark:bg-green-900 flex items-center justify-center">
                            <svg class="w-3.5 h-3.5 text-green-600 dark:text-green-400" fill="currentColor"
                                viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                    clip-rule="evenodd" />
                            </svg>
                        </span>
                        <span class="font-medium">{{ $reports->where('status', 'completed')->count() }}</span> Selesai
                    </div>
                    <div class="flex items-center gap-2 text-gray-600 dark:text-gray-300">
                        <span
                            class="w-6 h-6 rounded-full bg-blue-100 dark:bg-blue-900 flex items-center justify-center">
                            <svg class="w-3.5 h-3.5 text-blue-600 dark:text-blue-400 animate-spin" fill="none"
                                viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                    stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor"
                                    d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                            </svg>
                        </span>
                        <span
                            class="font-medium">{{ $reports->whereIn('status', ['pending', 'processing'])->count() }}</span>
                        Proses
                    </div>
                    <div class="flex items-center gap-2 text-gray-600 dark:text-gray-300">
                        <span
                            class="w-6 h-6 rounded-full bg-gray-100 dark:bg-gray-600 flex items-center justify-center">
                            <svg class="w-3.5 h-3.5 text-gray-500" fill="currentColor" viewBox="0 0 20 20">
                                <path
                                    d="M4 4a2 2 0 012-2h8a2 2 0 012 2v12a2 2 0 01-2 2H6a2 2 0 01-2-2V4zm2 0v12h8V4H6z" />
                            </svg>
                        </span>
                        <span class="font-medium">{{ $reports->total() }}</span> Total
                    </div>
                </div>
            </div>
        </div>

        <!-- Reports Grid -->
        <div class="space-y-4">
            @foreach($reports as $report)
                <div
                    class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 hover:shadow-md transition-shadow relative">
                    <div class="p-5">
                        <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4">
                            <!-- Left: Report Info -->
                            <div class="flex items-start gap-4 flex-1 min-w-0">
                                <!-- Icon -->
                                <div class="flex-shrink-0 w-12 h-12 rounded-xl flex items-center justify-center
                                            @if($report->status === 'completed') bg-green-100 dark:bg-green-900
                                            @elseif($report->status === 'processing') bg-blue-100 dark:bg-blue-900
                                            @elseif($report->status === 'failed') bg-red-100 dark:bg-red-900
                                            @else bg-yellow-100 dark:bg-yellow-900 @endif">
                                    @if($report->status === 'completed')
                                        <svg class="w-6 h-6 text-green-600 dark:text-green-400" fill="none"
                                            stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                    @elseif($report->status === 'processing')
                                        <svg class="w-6 h-6 text-blue-600 dark:text-blue-400 animate-spin" fill="none"
                                            viewBox="0 0 24 24">
                                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                                stroke-width="4"></circle>
                                            <path class="opacity-75" fill="currentColor"
                                                d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                                        </svg>
                                    @elseif($report->status === 'failed')
                                        <svg class="w-6 h-6 text-red-600 dark:text-red-400" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                    @else
                                        <svg class="w-6 h-6 text-yellow-600 dark:text-yellow-400" fill="none"
                                            stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                    @endif
                                </div>

                                <!-- Info -->
                                <div class="flex-1 min-w-0">
                                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white truncate">
                                        {{ $report->title }}
                                    </h3>
                                    <div
                                        class="flex flex-wrap items-center gap-3 mt-1.5 text-sm text-gray-500 dark:text-gray-400">
                                        <span class="flex items-center gap-1">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                            </svg>
                                            {{ $report->created_at->format('d M Y, H:i') }}
                                        </span>
                                        <span
                                            class="inline-flex items-center px-2 py-0.5 rounded-md text-xs font-medium {{ $report->output_format === 'pdf' ? 'bg-red-100 text-red-700 dark:bg-red-900 dark:text-red-300' : 'bg-blue-100 text-blue-700 dark:bg-blue-900 dark:text-blue-300' }}">
                                            {{ strtoupper($report->output_format) }}
                                        </span>
                                        @if($report->status === 'processing' && $report->progress)
                                            <span class="text-blue-600 dark:text-blue-400 font-medium">
                                                {{ $report->progress['percentage'] ?? 0 }}% -
                                                {{ $report->progress['current_section'] ?? 'Memproses...' }}
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <!-- Right: Status & Actions -->
                            <div class="flex items-center gap-3 flex-shrink-0">
                                <!-- Status Badge -->
                                <span
                                    class="inline-flex items-center px-3 py-1.5 rounded-full text-sm font-medium
                                            @if($report->status === 'completed') bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300
                                            @elseif($report->status === 'processing') bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-300
                                            @elseif($report->status === 'failed') bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300
                                            @else bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-300 @endif">
                                    {{ $report->status_label }}
                                </span>

                                <!-- Actions Dropdown -->
                                <div class="relative" x-data="{ open: false }">
                                    <button @click="open = !open"
                                        class="p-2 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
                                        <svg class="w-5 h-5 text-gray-500 dark:text-gray-400" fill="currentColor"
                                            viewBox="0 0 20 20">
                                            <path
                                                d="M10 6a2 2 0 110-4 2 2 0 010 4zM10 12a2 2 0 110-4 2 2 0 010 4zM10 18a2 2 0 110-4 2 2 0 010 4z" />
                                        </svg>
                                    </button>
                                    <div x-show="open" @click.away="open = false" x-cloak
                                        class="absolute right-0 mt-2 w-48 bg-white dark:bg-gray-800 rounded-lg shadow-lg border border-gray-200 dark:border-gray-700 py-1 z-50">
                                        <a href="{{ route('user.esg.show', $report->id) }}"
                                            class="flex items-center gap-2 px-4 py-2 text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                            </svg>
                                            Lihat Detail
                                        </a>
                                        @if($report->status === 'completed' && $report->file_path)
                                            <a href="{{ route('user.esg.download', $report->id) }}"
                                                class="flex items-center gap-2 px-4 py-2 text-sm text-green-700 dark:text-green-400 hover:bg-gray-100 dark:hover:bg-gray-700">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                                                </svg>
                                                Download File
                                            </a>
                                        @endif
                                        @if(!in_array($report->status, ['processing']))
                                            <div class="border-t border-gray-200 dark:border-gray-700 my-1"></div>
                                            <form action="{{ route('user.esg.destroy', $report->id) }}" method="POST"
                                                onsubmit="return confirm('Yakin ingin menghapus laporan ini?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                    class="w-full flex items-center gap-2 px-4 py-2 text-sm text-red-600 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-900/20">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                    </svg>
                                                    Hapus
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Progress Bar for Processing -->
                    @if($report->status === 'processing' && $report->progress)
                        <div class="px-5 pb-4">
                            <div class="w-full bg-gray-200 rounded-full h-2 overflow-hidden">
                                <div class="bg-gradient-to-r from-blue-500 to-green-500 h-2 rounded-full transition-all duration-500"
                                    style="width: {{ $report->progress['percentage'] ?? 0 }}%"></div>
                            </div>
                        </div>
                    @endif
                </div>
            @endforeach
        </div>

        <!-- Pagination -->
        @if($reports->hasPages())
            <div class="mt-6">
                {{ $reports->links() }}
            </div>
        @endif
    </div>

    <!-- Alpine.js for dropdown -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        [x-cloak] {
            display: none !important;
        }
    </style>
</x-app-layout>