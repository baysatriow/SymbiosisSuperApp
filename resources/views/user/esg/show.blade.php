<x-app-layout>
    <div class="max-w-5xl mx-auto">
        <!-- Header -->
        <div class="mb-8">
            <div class="flex items-center gap-3 text-sm text-gray-500 dark:text-gray-400 mb-2">
                <a href="{{ route('user.esg.index') }}"
                    class="hover:text-green-600 dark:hover:text-green-400 transition-colors flex items-center gap-1">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    Kembali ke Daftar
                </a>
                <span>/</span>
                <span>Detail Progress</span>
            </div>

            <div class="flex flex-col md:flex-row items-start md:items-center justify-between gap-4">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900 dark:text-white tracking-tight">{{ $report->title }}
                    </h1>
                    <div class="flex items-center gap-4 mt-2 text-sm text-gray-500 dark:text-gray-400">
                        <span class="flex items-center gap-1">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                            {{ $report->created_at->format('d M Y, H:i') }}
                        </span>
                        <span>•</span>
                        <span
                            class="uppercase font-semibold tracking-wider text-xs px-2 py-0.5 rounded bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-300">
                            {{ $report->output_format }}
                        </span>
                    </div>
                </div>

                <div id="status-badge" class="flex-shrink-0">
                    @include('user.esg._status-badge', ['report' => $report])
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Left: Progress & Chapters -->
            <div class="lg:col-span-2 space-y-8">

                <!-- Progress Card -->
                <div
                    class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden relative">
                    <!-- Progress Decoration -->
                    <div class="absolute top-0 left-0 right-0 h-1 bg-gray-100 dark:bg-gray-700">
                        <div id="progress-bar-top"
                            class="h-full bg-gradient-to-r from-green-400 to-blue-500 transition-all duration-700"
                            style="width: {{ $report->progress['percentage'] ?? 0 }}%"></div>
                    </div>

                    <div class="p-6 sm:p-8">
                        <div class="flex items-center justify-between mb-6">
                            <div>
                                <h2 class="text-xl font-bold text-gray-900 dark:text-white">Status Generasi AI</h2>
                                <p class="text-gray-500 dark:text-gray-400 text-sm mt-1" id="current-activity-text">
                                    @if($report->status === 'processing')
                                        Sedang menulis konten laporan...
                                    @elseif($report->status === 'completed')
                                        Laporan berhasil dibuat!
                                    @elseif($report->status === 'failed')
                                        Terjadi kesalahan saat proses.
                                    @else
                                        Menunggu antrian...
                                    @endif
                                </p>
                            </div>
                            <div class="text-right">
                                <span id="progress-percentage-big"
                                    class="text-4xl font-black text-transparent bg-clip-text bg-gradient-to-br from-green-500 to-blue-600">
                                    {{ $report->progress['percentage'] ?? 0 }}%
                                </span>
                            </div>
                        </div>

                        <!-- Current Activity Box -->
                        <div
                            class="bg-gray-50 dark:bg-gray-700/50 rounded-xl p-4 border border-gray-100 dark:border-gray-600 flex items-center gap-4">
                            <div
                                class="flex-shrink-0 w-12 h-12 rounded-lg bg-white dark:bg-gray-800 flex items-center justify-center shadow-sm text-blue-600 dark:text-blue-400">
                                @if($report->status === 'processing')
                                    <svg class="w-6 h-6 animate-spin" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                            stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor"
                                            d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                        </path>
                                    </svg>
                                @elseif($report->status === 'completed')
                                    <svg class="w-6 h-6 text-green-500" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M5 13l4 4L19 7" />
                                    </svg>
                                @elseif($report->status === 'failed')
                                    <svg class="w-6 h-6 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                @else
                                    <svg class="w-6 h-6 text-yellow-500" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                @endif
                            </div>
                            <div class="flex-1 min-w-0">
                                <p
                                    class="text-xs font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400 mb-0.5">
                                    Aktivitas Saat Ini</p>
                                <p id="current-section" class="font-medium text-gray-900 dark:text-white truncate">
                                    {{ $report->progress['current_section'] ?? ($report->status === 'completed' ? 'Selesai' : 'Menunggu...') }}
                                </p>
                            </div>
                        </div>

                        <!-- Error Message -->
                        @if($report->status === 'failed')
                            <div
                                class="mt-4 p-4 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-xl text-red-800 dark:text-red-200 text-sm">
                                <strong>Error:</strong> {{ $report->error_message }}
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Chapters List -->
                <div
                    class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 p-6 sm:p-8">
                    <h3 class="font-bold text-gray-900 dark:text-white mb-6 flex items-center gap-2">
                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                        </svg>
                        Rincian Bab Laporan
                    </h3>

                    <div class="space-y-4" id="chapter-list">
                        @foreach($reportStructure as $chapterNum => $chapter)
                            @php
                                $currentChapter = $report->progress['current_chapter'] ?? 0;
                                $percentage = $report->progress['percentage'] ?? 0;

                                // Logic status chapter yang lebih robust
                                $chapterStatus = 'pending';
                                if ($report->status === 'completed') {
                                    $chapterStatus = 'completed';
                                } elseif ($report->status === 'failed' && $chapterNum < $currentChapter) {
                                    $chapterStatus = 'completed';
                                } elseif ($chapterNum < $currentChapter) {
                                    $chapterStatus = 'completed';
                                } elseif ($chapterNum == $currentChapter && $report->status === 'processing') {
                                    $chapterStatus = 'processing';
                                }
                            @endphp

                            <div class="group flex items-start gap-4 p-4 rounded-xl transition-all border 
                                    {{ $chapterStatus === 'processing' ? 'bg-blue-50/50 dark:bg-blue-900/10 border-blue-200 dark:border-blue-800 shadow-sm' : 'bg-gray-50 dark:bg-gray-700/30 border-transparent hover:bg-white dark:hover:bg-gray-700 hover:shadow-sm hover:border-gray-200' }}"
                                data-chapter="{{ $chapterNum }}">

                                <!-- Status Icon -->
                                <div class="flex-shrink-0 mt-0.5">
                                    @if($chapterStatus === 'completed')
                                        <div
                                            class="w-6 h-6 rounded-full bg-green-100 dark:bg-green-900 flex items-center justify-center text-green-600 dark:text-green-400">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M5 13l4 4L19 7" />
                                            </svg>
                                        </div>
                                    @elseif($chapterStatus === 'processing')
                                        <div
                                            class="w-6 h-6 rounded-full bg-blue-100 dark:bg-blue-900 flex items-center justify-center text-blue-600 dark:text-blue-400">
                                            <svg class="w-3.5 h-3.5 animate-spin" fill="none" viewBox="0 0 24 24">
                                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                                    stroke-width="4"></circle>
                                                <path class="opacity-75" fill="currentColor"
                                                    d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                                            </svg>
                                        </div>
                                    @else
                                        <div
                                            class="w-6 h-6 rounded-full bg-gray-200 dark:bg-gray-600 flex items-center justify-center text-gray-500 font-medium text-xs">
                                            {{ $chapterNum }}
                                        </div>
                                    @endif
                                </div>

                                <div class="flex-1 min-w-0">
                                    <h4 class="font-bold text-gray-900 dark:text-white text-sm">Bab {{ $chapterNum }}:
                                        {{ $chapter['title'] }}</h4>
                                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                        {{ count($chapter['subchapters']) }} Sub-bab • AI Generation</p>
                                </div>

                                <span
                                    class="text-xs font-medium px-2 py-1 rounded {{ $chapterStatus === 'processing' ? 'bg-blue-100 text-blue-700 dark:bg-blue-900 dark:text-blue-300' : ($chapterStatus === 'completed' ? 'bg-green-100 text-green-700 dark:bg-green-900 dark:text-green-300' : 'bg-gray-100 text-gray-500 dark:bg-gray-700 dark:text-gray-400') }}">
                                    @if($chapterStatus === 'completed') Selesai
                                    @elseif($chapterStatus === 'processing') Proses...
                                    @else Menunggu
                                    @endif
                                </span>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Right: Actions & Info -->
            <div class="space-y-6">
                <!-- Download Card (Success) -->
                <div id="download-card"
                    class="card-enter bg-gradient-to-br from-green-500 to-emerald-600 rounded-2xl shadow-lg p-6 text-white text-center {{ $report->status !== 'completed' ? 'hidden' : '' }}">
                    <div
                        class="mb-4 bg-white/20 w-16 h-16 rounded-full flex items-center justify-center mx-auto backdrop-blur-sm">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold mb-2">Laporan Siap!</h3>
                    <p class="text-green-100 text-sm mb-6">File laporan Anda telah berhasil dibuat dan siap diunduh.</p>

                    <a href="{{ $report->file_path ? route('user.esg.download', $report->id) : '#' }}"
                        class="block w-full py-3 bg-white text-green-600 font-bold rounded-xl shadow-md hover:bg-gray-50 hover:shadow-lg transition transform hover:-translate-y-0.5">
                        Download {{ strtoupper($report->output_format) }}
                    </a>
                </div>

                <!-- Estimation (Processing) -->
                <div id="estimation-card"
                    class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 p-6 {{ $report->status === 'completed' ? 'hidden' : '' }}">
                    <h3 class="text-sm font-bold text-gray-900 dark:text-white uppercase tracking-wider mb-4">Estimasi
                        Waktu</h3>
                    <div class="flex items-end gap-2">
                        <span id="est-time" class="text-4xl font-black text-gray-900 dark:text-white">
                            @if($report->status === 'pending') ~10
                            @else ~{{ max(1, ceil((100 - ($report->progress['percentage'] ?? 0)) / 10)) }}
                            @endif
                        </span>
                        <span class="text-lg text-gray-500 dark:text-gray-400 mb-1">menit</span>
                    </div>
                    <p class="text-xs text-gray-400 mt-2">Waktu dapat bervariasi tergantung kompleksitas dokumen.</p>
                </div>

                <!-- Info Box -->
                <div class="bg-gray-50 dark:bg-gray-700/50 rounded-xl p-5 border border-gray-200 dark:border-gray-600">
                    <h4 class="font-bold text-gray-900 dark:text-white text-sm mb-2">Information</h4>
                    <p class="text-xs text-gray-500 dark:text-gray-400 leading-relaxed">
                        Anda dapat menutup halaman ini. Laporan akan tetap diproses di latar belakang. Status dapat
                        dicek kembali melalui menu Laporan ESG.
                    </p>
                </div>

                <!-- Delete Action -->
                @if(!in_array($report->status, ['processing']))
                    <form action="{{ route('user.esg.destroy', $report->id) }}" method="POST"
                        onsubmit="return confirm('Yakin ingin menghapus laporan ini?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit"
                            class="w-full flex items-center justify-center gap-2 px-4 py-3 border border-red-200 text-red-600 font-medium rounded-xl hover:bg-red-50 dark:border-red-800 dark:text-red-400 dark:hover:bg-red-900/20 transition">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                            </svg>
                            Hapus Laporan
                        </button>
                    </form>
                @endif
            </div>
        </div>
    </div>

    <!-- Animation CSS -->
    <style>
        .card-enter {
            animation: slideUp 0.5s ease-out forwards;
        }

        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>

    <!-- Polling Script -->
    @if(in_array($report->status, ['pending', 'processing']))
        <script>
            const reportId = {{ $report->id }};
            const pollIntervalMs = 3000;
            let pollInterval;

            function updateProgress() {
                fetch(`/esg-reports/${reportId}/progress`)
                    .then(response => response.json())
                    .then(data => {
                        // Update main Percentage
                        document.getElementById('progress-percentage-big').textContent = `${data.progress?.percentage || 0}%`;

                        // Update Progress Bars
                        const progressBar = document.getElementById('progress-bar-top');
                        if (progressBar) progressBar.style.width = `${data.progress?.percentage || 0}%`;

                        // Update Text Status
                        const activityText = document.getElementById('current-activity-text');
                        const sectionEl = document.getElementById('current-section');

                        if (data.status === 'processing') {
                            if (activityText) activityText.textContent = 'Sedang menulis konten laporan...';
                            if (sectionEl) sectionEl.textContent = data.progress?.current_section || 'Memproses...';
                        } else if (data.status === 'completed') {
                            if (activityText) activityText.textContent = 'Laporan berhasil dibuat!';
                            if (sectionEl) sectionEl.textContent = 'Selesai';
                        }

                        // Update Estimation
                        const estEl = document.getElementById('est-time');
                        if (estEl && data.progress?.percentage && data.status === 'processing') {
                            const remaining = Math.max(1, Math.ceil((100 - data.progress.percentage) / 10));
                            estEl.textContent = `~${remaining}`;
                        }

                        // Update Chapter UI
                        const currentChapter = data.progress?.current_chapter || 0;
                        document.querySelectorAll('[data-chapter]').forEach(el => {
                            const chapterNum = parseInt(el.dataset.chapter);
                            const statusBadge = el.querySelector('span');
                            const iconContainer = el.querySelector('.flex-shrink-0');

                            // Reset classes first
                            el.className = 'group flex items-start gap-4 p-4 rounded-xl transition-all border';

                            // Condition Check
                            let status = 'pending';
                            if (data.status === 'completed' || chapterNum < currentChapter) status = 'completed';
                            else if (chapterNum === currentChapter && data.status === 'processing') status = 'processing';

                            if (status === 'completed') {
                                el.classList.add('bg-gray-50', 'dark:bg-gray-700/30', 'border-transparent');
                                if (statusBadge) {
                                    statusBadge.textContent = 'Selesai';
                                    statusBadge.className = 'text-xs font-medium px-2 py-1 rounded bg-green-100 text-green-700 dark:bg-green-900 dark:text-green-300';
                                }
                                if (iconContainer) {
                                    iconContainer.innerHTML = '<div class="w-6 h-6 rounded-full bg-green-100 dark:bg-green-900 flex items-center justify-center text-green-600 dark:text-green-400"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg></div>';
                                }
                            } else if (status === 'processing') {
                                el.classList.add('bg-blue-50/50', 'dark:bg-blue-900/10', 'border-blue-200', 'dark:border-blue-800', 'shadow-sm');
                                if (statusBadge) {
                                    statusBadge.textContent = 'Proses...';
                                    statusBadge.className = 'text-xs font-medium px-2 py-1 rounded bg-blue-100 text-blue-700 dark:bg-blue-900 dark:text-blue-300';
                                }
                                if (iconContainer) {
                                    iconContainer.innerHTML = '<div class="w-6 h-6 rounded-full bg-blue-100 dark:bg-blue-900 flex items-center justify-center text-blue-600 dark:text-blue-400"><svg class="w-3.5 h-3.5 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path></svg></div>';
                                }
                            } else {
                                // Pending
                                el.classList.add('bg-gray-50', 'dark:bg-gray-700/30', 'border-transparent');
                                if (statusBadge) {
                                    statusBadge.textContent = 'Menunggu';
                                    statusBadge.className = 'text-xs font-medium px-2 py-1 rounded bg-gray-100 text-gray-500 dark:bg-gray-700 dark:text-gray-400';
                                }
                                if (iconContainer) {
                                    iconContainer.innerHTML = `<div class="w-6 h-6 rounded-full bg-gray-200 dark:bg-gray-600 flex items-center justify-center text-gray-500 font-medium text-xs">${chapterNum}</div>`;
                                }
                            }
                        });

                        // Completion Logic
                        if (data.status === 'completed' || data.status === 'failed') {
                            clearInterval(pollInterval);
                            setTimeout(() => location.reload(), 1000);
                        }
                    })
                    .catch(err => console.error('Polling error:', err));
            }

            pollInterval = setInterval(updateProgress, pollIntervalMs);
            updateProgress();
        </script>
    @endif
</x-app-layout>