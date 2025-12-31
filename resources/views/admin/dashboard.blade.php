<x-app-layout>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <div class="grid grid-cols-1 gap-6 mb-6">

        <!-- HEADER SECTION -->
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center p-6 bg-white border border-gray-200 rounded-xl shadow-sm dark:bg-gray-800 dark:border-gray-700">
            <div>
                <h2 class="text-3xl font-bold tracking-tight text-gray-900 dark:text-white">Dashboard AdminSSS</h2>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                    Pantau aktivitas sistem, verifikasi pengguna, dan validasi dokumen lingkungan.
                </p>
            </div>
            <div class="mt-4 md:mt-0">
                <span class="bg-blue-100 text-blue-800 text-xs font-medium px-3 py-1.5 rounded-full dark:bg-blue-900 dark:text-blue-300 border border-blue-200 dark:border-blue-800 flex items-center gap-2">
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                    {{ now()->format('d F Y') }}
                </span>
            </div>
        </div>

        <!-- STATISTIK CARDS -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            <!-- Card 1: Total User -->
            <div class="p-6 bg-white border border-gray-200 rounded-xl shadow-sm dark:border-gray-700 dark:bg-gray-800 relative overflow-hidden group hover:border-primary-500 transition-colors">
                <div class="absolute right-0 top-0 p-4 opacity-5 group-hover:opacity-10 transition-opacity">
                    <svg class="w-20 h-20 text-primary-600" fill="currentColor" viewBox="0 0 20 20"><path d="M13 6a3 3 0 11-6 0 3 3 0 016 0zM18 8a2 2 0 11-4 0 2 2 0 014 0zM14 15a4 4 0 00-8 0v3h8v-3zM6 8a2 2 0 11-4 0 2 2 0 014 0zM16 18v-3a5.972 5.972 0 00-.75-2.906A3.005 3.005 0 0119 15v3h-3zM4.75 12.094A5.973 5.973 0 004 15v3H1v-3a3 3 0 013.75-2.906z"></path></svg>
                </div>
                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Pengguna Terdaftar</dt>
                <dd class="mt-2 flex items-end justify-between">
                    <span class="text-4xl font-extrabold text-gray-900 dark:text-white">{{ $stats['total_users'] }}</span>
                    <div class="flex flex-col text-right z-10">
                        <span class="text-green-600 bg-green-100 px-2 py-0.5 rounded text-xs font-bold mb-1">{{ $stats['active_users'] }} Aktif</span>
                        @if($stats['pending_users'] > 0)
                            <span class="text-yellow-700 bg-yellow-100 px-2 py-0.5 rounded text-xs font-bold animate-pulse">{{ $stats['pending_users'] }} Pending</span>
                        @endif
                    </div>
                </dd>
            </div>

            <!-- Card 2: Total Dokumen -->
            <div class="p-6 bg-white border border-gray-200 rounded-xl shadow-sm dark:border-gray-700 dark:bg-gray-800 relative overflow-hidden group hover:border-primary-500 transition-colors">
                <div class="absolute right-0 top-0 p-4 opacity-5 group-hover:opacity-10 transition-opacity">
                    <svg class="w-20 h-20 text-purple-600" fill="currentColor" viewBox="0 0 20 20"><path d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4z"></path></svg>
                </div>
                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Total Dokumen</dt>
                <dd class="mt-2 flex items-end justify-between">
                    <span class="text-4xl font-extrabold text-gray-900 dark:text-white">{{ $stats['total_documents'] }}</span>
                    <div class="flex flex-col text-right z-10">
                        @if($stats['pending_documents'] > 0)
                            <span class="text-red-700 bg-red-100 px-2 py-0.5 rounded text-xs font-bold animate-pulse">{{ $stats['pending_documents'] }} Perlu Review</span>
                        @else
                            <span class="text-green-600 bg-green-100 px-2 py-0.5 rounded text-xs font-bold">Semua Bersih</span>
                        @endif
                    </div>
                </dd>
            </div>

            <!-- Card 3: Storage -->
            <div class="p-6 bg-white border border-gray-200 rounded-xl shadow-sm dark:border-gray-700 dark:bg-gray-800 relative overflow-hidden group hover:border-primary-500 transition-colors">
                <div class="absolute right-0 top-0 p-4 opacity-5 group-hover:opacity-10 transition-opacity">
                    <svg class="w-20 h-20 text-blue-600" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M3 17a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm3.293-7.707a1 1 0 011.414 0L9 10.586V3a1 1 0 112 0v7.586l1.293-1.293a1 1 0 111.414 1.414l-3 3a1 1 0 01-1.414 0l-3-3a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>
                </div>
                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Penyimpanan Sistem</dt>
                <dd class="mt-2">
                    <span class="text-4xl font-extrabold text-gray-900 dark:text-white">
                        {{ number_format($stats['total_storage_bytes'] / 1048576, 2) }}
                    </span>
                    <span class="text-lg text-gray-500 font-bold">MB</span>
                </dd>
                <p class="text-xs text-gray-400 mt-2">Total ukuran file asli yang diunggah.</p>
            </div>
        </div>

        <!-- MAIN CONTENT GRID (TABEL SCROLLABLE) -->
        <div class="grid grid-cols-1 xl:grid-cols-2 gap-6">

            <!-- TABEL 1: USER PENDING -->
            <div class="bg-white border border-gray-200 rounded-xl shadow-sm dark:border-gray-700 dark:bg-gray-800 flex flex-col h-[400px]"> <!-- Set Fixed Height -->
                <div class="p-5 border-b border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800 rounded-t-xl flex justify-between items-center flex-shrink-0">
                    <h3 class="text-lg font-bold text-gray-900 dark:text-white flex items-center gap-2">
                        <span class="relative flex h-3 w-3">
                          <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-blue-400 opacity-75"></span>
                          <span class="relative inline-flex rounded-full h-3 w-3 bg-blue-500"></span>
                        </span>
                        Verifikasi Pengguna Baru
                    </h3>
                    <span class="bg-blue-100 text-blue-800 text-xs font-bold px-2.5 py-0.5 rounded-full dark:bg-blue-900 dark:text-blue-300">{{ $pendingUsers->count() }} Pending</span>
                </div>

                <!-- Scrollable Area -->
                <div class="flex-1 overflow-y-auto custom-scrollbar">
                    <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400 relative">
                        <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400 sticky top-0 z-10">
                            <tr>
                                <th class="px-6 py-3">User Detail</th>
                                <th class="px-6 py-3 text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                            @forelse($pendingUsers as $u)
                            <tr class="bg-white hover:bg-gray-50 dark:bg-gray-800 dark:hover:bg-gray-700 transition-colors">
                                <td class="px-6 py-4">
                                    <div class="font-bold text-gray-900 dark:text-white text-base">{{ $u->full_name }}</div>
                                    <div class="text-xs text-gray-500">{{ $u->email }} | {{ $u->phone_number }}</div>
                                    <div class="text-xs text-gray-400 mt-1">{{ $u->created_at->diffForHumans() }}</div>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <div class="flex justify-center gap-2">
                                        <button type="button" onclick="openConfirmModal('{{ route('admin.users.approve', $u->id) }}', 'Setujui User', 'Apakah Anda yakin ingin mengaktifkan user {{ $u->full_name }}?', 'bg-green-600 hover:bg-green-700', 'Ya, Aktifkan')" class="text-white bg-green-600 hover:bg-green-700 focus:ring-4 focus:outline-none focus:ring-green-300 font-medium rounded-lg text-xs px-3 py-2">
                                            Approve
                                        </button>
                                        <button type="button" onclick="openConfirmModal('{{ route('admin.users.reject', $u->id) }}', 'Tolak User', 'Apakah Anda yakin ingin menolak user {{ $u->full_name }}?', 'bg-red-600 hover:bg-red-700', 'Ya, Tolak')" class="text-white bg-red-600 hover:bg-red-700 focus:ring-4 focus:outline-none focus:ring-red-300 font-medium rounded-lg text-xs px-3 py-2">
                                            Reject
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="2" class="px-6 py-6 text-center text-gray-500">Tidak ada antrian.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- TABEL 2: DOKUMEN PENDING -->
            <div class="bg-white border border-gray-200 rounded-xl shadow-sm dark:border-gray-700 dark:bg-gray-800 flex flex-col h-[400px]"> <!-- Set Fixed Height -->
                <div class="p-5 border-b border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800 rounded-t-xl flex justify-between items-center flex-shrink-0">
                    <h3 class="text-lg font-bold text-gray-900 dark:text-white flex items-center gap-2">
                        <span class="relative flex h-3 w-3">
                          <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-yellow-400 opacity-75"></span>
                          <span class="relative inline-flex rounded-full h-3 w-3 bg-yellow-500"></span>
                        </span>
                        Verifikasi Dokumen
                    </h3>
                    <span class="bg-yellow-100 text-yellow-800 text-xs font-bold px-2.5 py-0.5 rounded-full dark:bg-yellow-900 dark:text-yellow-300">{{ $pendingDocuments->count() }} Perlu Review</span>
                </div>

                <!-- Scrollable Area -->
                <div class="flex-1 overflow-y-auto custom-scrollbar">
                    <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400 relative">
                        <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400 sticky top-0 z-10">
                            <tr>
                                <th class="px-6 py-3">Informasi Dokumen</th>
                                <th class="px-6 py-3 text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                            @forelse($pendingDocuments as $d)
                            <tr class="bg-white hover:bg-gray-50 dark:bg-gray-800 dark:hover:bg-gray-700 transition-colors">
                                <td class="px-6 py-4">
                                    <a href="{{ route('admin.documents.view', $d->id) }}" target="_blank" class="font-bold text-blue-600 hover:underline block truncate max-w-[180px]" title="{{ $d->original_filename }}">
                                        {{ $d->original_filename }}
                                    </a>
                                    <div class="text-xs text-gray-500">User: {{ $d->uploader_name }}</div>
                                    <div class="text-xs text-gray-400">
                                        {{ \Carbon\Carbon::parse($d->updated_at ?? $d->created_at)->diffForHumans() }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <div class="flex justify-center gap-2">
                                        <!-- Approve -->
                                        <button type="button" onclick="openConfirmModal('{{ route('admin.documents.approve', $d->id) }}', 'Setujui Dokumen', 'Dokumen {{ $d->original_filename }} valid?', 'bg-green-600 hover:bg-green-700', 'Ya, Setujui')" class="text-white bg-green-600 hover:bg-green-700 focus:ring-4 focus:outline-none focus:ring-green-300 font-medium rounded-lg text-xs px-3 py-2">
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                        </button>

                                        <!-- Reject -->
                                        <button type="button" data-modal-target="rejectModal" data-modal-toggle="rejectModal"
                                                data-doc-id="{{ $d->id }}"
                                                data-doc-name="{{ $d->original_filename }}"
                                                class="reject-btn text-white bg-red-600 hover:bg-red-700 focus:ring-4 focus:outline-none focus:ring-red-300 font-medium rounded-lg text-xs px-3 py-2">
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="2" class="px-6 py-6 text-center text-gray-500">Tidak ada dokumen pending.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- GRAFIK STATISTIK DENGAN FILTER -->
        <div class="bg-white border border-gray-200 rounded-xl shadow-sm dark:border-gray-700 dark:bg-gray-800 p-6">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-bold text-gray-900 dark:text-white">Statistik Aktivitas</h3>
                <form method="GET" action="{{ route('admin.dashboard') }}" id="chartFilterForm" class="flex items-center gap-2">
                    <label for="chart_filter" class="text-sm text-gray-500 dark:text-gray-400">Filter:</label>
                    <select name="filter" id="chart_filter" onchange="document.getElementById('chartFilterForm').submit()" class="bg-gray-50 border border-gray-300 text-gray-900 text-xs rounded-lg focus:ring-blue-500 focus:border-blue-500 block p-2 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white">
                        <option value="7_days" {{ $filter == '7_days' ? 'selected' : '' }}>7 Hari Terakhir</option>
                        <option value="1_month" {{ $filter == '1_month' ? 'selected' : '' }}>1 Bulan Terakhir</option>
                        <option value="1_year" {{ $filter == '1_year' ? 'selected' : '' }}>1 Tahun Terakhir</option>
                    </select>
                </form>
            </div>
            <div class="relative h-72 w-full">
                <canvas id="activityChart"></canvas>
            </div>
        </div>
    </div>

    <!-- MODAL KONFIRMASI UNIVERSAL (Approve User/Doc) -->
    <div id="universalConfirmModal" tabindex="-1" aria-hidden="true" class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
        <div class="relative p-4 w-full max-w-md max-h-full">
            <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
                <div class="p-4 md:p-5 text-center">
                    <svg class="mx-auto mb-4 text-gray-400 w-12 h-12 dark:text-gray-200" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 11V6m0 8h.01M19 10a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/>
                    </svg>
                    <h3 class="mb-2 text-lg font-normal text-gray-500 dark:text-gray-400" id="univ_modal_title">Konfirmasi</h3>
                    <p class="mb-5 text-sm text-gray-500" id="univ_modal_body">Apakah Anda yakin?</p>

                    <form id="univ_modal_form" method="POST" action="">
                        @csrf
                        <div class="flex justify-center gap-3">
                            <button type="submit" id="univ_modal_btn" class="text-white bg-blue-600 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm inline-flex items-center px-5 py-2.5 text-center">
                                Ya, Lanjutkan
                            </button>
                            <button data-modal-hide="universalConfirmModal" type="button" class="py-2.5 px-5 text-sm font-medium text-gray-900 focus:outline-none bg-white rounded-lg border border-gray-200 hover:bg-gray-100 hover:text-blue-700 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:text-white dark:hover:bg-gray-700">
                                Batal
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- MODAL REJECT DOKUMEN (Khusus karena butuh input alasan) -->
    <div id="rejectModal" tabindex="-1" aria-hidden="true" class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
        <div class="relative p-4 w-full max-w-md max-h-full">
            <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
                <div class="p-4 md:p-5 text-center">
                    <h3 class="mb-2 text-lg font-bold text-gray-900 dark:text-white">Tolak Dokumen <span id="modal_doc_name"></span>?</h3>
                    <form id="rejectForm" method="POST" action="">
                        @csrf
                        <div class="mb-4 text-left">
                            <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Alasan Penolakan</label>
                            <textarea name="rejection_reason" rows="3" class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-red-500 focus:border-red-500" required></textarea>
                        </div>
                        <div class="flex gap-3 justify-center">
                            <button type="submit" class="text-white bg-red-600 hover:bg-red-800 focus:ring-4 font-medium rounded-lg text-sm px-5 py-2.5">Tolak Dokumen</button>
                            <button data-modal-hide="rejectModal" type="button" class="py-2.5 px-5 text-sm font-medium text-gray-900 bg-white rounded-lg border border-gray-200 hover:bg-gray-100">Batal</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <style>
        .custom-scrollbar::-webkit-scrollbar { width: 6px; }
        .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background-color: #cbd5e1; border-radius: 20px; }
        .dark .custom-scrollbar::-webkit-scrollbar-thumb { background-color: #475569; }
    </style>

    <script>
        // 1. Inisialisasi Modal
        let univModal;
        document.addEventListener('DOMContentLoaded', function() {
            const modalEl = document.getElementById('universalConfirmModal');
            univModal = new Modal(modalEl);

            // 2. Logic Modal Reject Dokumen
            const rejectBtns = document.querySelectorAll('.reject-btn');
            const modalDocName = document.getElementById('modal_doc_name');
            const rejectForm = document.getElementById('rejectForm');

            rejectBtns.forEach(btn => {
                btn.addEventListener('click', function() {
                    const id = this.getAttribute('data-doc-id');
                    const name = this.getAttribute('data-doc-name');
                    modalDocName.textContent = `"${name}"`;
                    rejectForm.action = `/admin/documents/${id}/reject`;
                });
            });

            // 3. Grafik Chart.js
            const ctx = document.getElementById('activityChart').getContext('2d');
            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: {!! json_encode($chartData['labels']) !!},
                    datasets: [
                        {
                            label: 'User Baru',
                            data: {!! json_encode($chartData['users']) !!},
                            borderColor: '#3b82f6', // Blue
                            backgroundColor: 'rgba(59, 130, 246, 0.1)',
                            borderWidth: 2,
                            tension: 0.4,
                            fill: true
                        },
                        {
                            label: 'Dokumen Diupload',
                            data: {!! json_encode($chartData['documents']) !!},
                            borderColor: '#10b981', // Green
                            backgroundColor: 'rgba(16, 185, 129, 0.1)',
                            borderWidth: 2,
                            tension: 0.4,
                            fill: true
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    interaction: {
                        mode: 'index',
                        intersect: false,
                    },
                    plugins: {
                        legend: { position: 'bottom' }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: { precision: 0 }
                        }
                    }
                }
            });
        });

        // 4. Fungsi Helper Modal Universal
        function openConfirmModal(actionUrl, title, body, btnClass, btnText) {
            document.getElementById('univ_modal_form').action = actionUrl;
            document.getElementById('univ_modal_title').textContent = title;
            document.getElementById('univ_modal_body').textContent = body;

            const btn = document.getElementById('univ_modal_btn');
            btn.className = `text-white font-medium rounded-lg text-sm inline-flex items-center px-5 py-2.5 text-center focus:ring-4 focus:outline-none ${btnClass}`;
            btn.textContent = btnText;

            univModal.show();
        }
    </script>
</x-app-layout>
