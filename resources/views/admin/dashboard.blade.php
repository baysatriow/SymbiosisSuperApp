<x-app-layout>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <x-page-header 
        title="Selamat Pagi, Admin! 👋" 
        subtitle="Berikut adalah ringkasan aktivitas sistem hari ini.">
        <x-slot:actions>
            <div class="px-4 py-2 bg-blue-50 text-blue-700 rounded-xl text-xs font-bold border border-blue-100 flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                {{ now()->format('d F Y') }}
            </div>
        </x-slot:actions>
    </x-page-header>

    <!-- STATISTIK CARDS -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <x-stat-card 
            label="Pengguna" 
            value="{{ $stats['total_users'] }}" 
            icon='<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>'
            trend="{{ $stats['active_users'] }} Aktif"
            iconColor="primary"
        />
        
        <x-stat-card 
            label="Dokumen" 
            value="{{ $stats['total_documents'] }}" 
            icon='<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>'
            trend="{{ $stats['pending_documents'] }} Pending"
            trendUp="{{ $stats['pending_documents'] == 0 }}"
            iconColor="purple"
        />

        <x-stat-card 
            label="Penyimpanan" 
            value="{{ number_format($stats['total_storage_bytes'] / 1048576, 1) }} MB" 
            icon='<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4m0 5c0 2.21-3.582 4-8 4s-8-1.79-8-4"></path></svg>'
            trend="Total Usage"
            iconColor="blue"
        />

        <x-stat-card 
            label="Hari Ini" 
            value="{{ $chartData['documents'][count($chartData['documents'])-1] ?? 0 }}" 
            icon='<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path></svg>'
            trend="Upload Baru"
            iconColor="orange"
        />
    </div>

    <!-- MAIN CONTENT GRID -->
    <div class="grid grid-cols-1 xl:grid-cols-2 gap-6 mb-8">

        <!-- TABEL 1: USER PENDING -->
        <x-content-card class="flex flex-col h-[450px] !p-0 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center bg-gray-50/50">
                <h3 class="text-sm font-bold text-gray-900 flex items-center gap-2 uppercase tracking-wider">
                    <span class="flex h-2 w-2 rounded-full bg-blue-500"></span>
                    Verifikasi Pengguna
                </h3>
                <span class="bg-blue-100 text-blue-700 text-[10px] font-bold px-2 py-0.5 rounded-full uppercase">{{ $pendingUsers->count() }} Pending</span>
            </div>

            <div class="flex-1 overflow-y-auto no-scrollbar">
                <table class="w-full text-sm text-left text-gray-500">
                    <thead class="text-[10px] text-gray-400 uppercase tracking-widest bg-gray-50/50 sticky top-0 border-b border-gray-100">
                        <tr>
                            <th class="px-6 py-3">User Detail</th>
                            <th class="px-6 py-3 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @forelse($pendingUsers as $u)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4">
                                <div class="font-bold text-gray-900">{{ $u->full_name }}</div>
                                <div class="text-xs text-gray-400">{{ $u->email }}</div>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <div class="flex justify-end gap-2">
                                    <button type="button" onclick="openConfirmModal('{{ route('admin.users.approve', $u->id) }}', 'Setujui User', 'Aktifkan user {{ $u->full_name }}?', 'bg-primary-600 hover:bg-primary-700', 'Approve')" class="text-white bg-primary-600 hover:bg-primary-700 font-bold rounded-lg text-[10px] px-3 py-1.5 uppercase tracking-wider">
                                        Approve
                                    </button>
                                    <button type="button" onclick="openConfirmModal('{{ route('admin.users.reject', $u->id) }}', 'Tolak User', 'Tolak user {{ $u->full_name }}?', 'bg-red-600 hover:bg-red-700', 'Reject')" class="text-white bg-red-600 hover:bg-red-700 font-bold rounded-lg text-[10px] px-3 py-1.5 uppercase tracking-wider">
                                        Reject
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="2" class="px-6 py-10 text-center text-gray-400 italic">Tidak ada antrian pendig.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </x-content-card>

        <!-- TABEL 2: DOKUMEN PENDING -->
        <x-content-card class="flex flex-col h-[450px] !p-0 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center bg-gray-50/50">
                <h3 class="text-sm font-bold text-gray-900 flex items-center gap-2 uppercase tracking-wider">
                    <span class="flex h-2 w-2 rounded-full bg-yellow-500"></span>
                    Verifikasi Dokumen
                </h3>
                <span class="bg-yellow-100 text-yellow-700 text-[10px] font-bold px-2 py-0.5 rounded-full uppercase">{{ $pendingDocuments->count() }} Review</span>
            </div>

            <div class="flex-1 overflow-y-auto no-scrollbar">
                <table class="w-full text-sm text-left text-gray-500">
                    <thead class="text-[10px] text-gray-400 uppercase tracking-widest bg-gray-50/50 sticky top-0 border-b border-gray-100">
                        <tr>
                            <th class="px-6 py-3">Informasi Dokumen</th>
                            <th class="px-6 py-3 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @forelse($pendingDocuments as $d)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4">
                                <a href="{{ route('admin.documents.view', $d->id) }}" target="_blank" class="font-bold text-gray-900 hover:text-primary-600 truncate block max-w-[200px]">
                                    {{ $d->original_filename }}
                                </a>
                                <div class="text-[10px] text-gray-400 font-medium">By: {{ $d->uploader_name }}</div>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <div class="flex justify-end gap-2">
                                    <button type="button" onclick="openConfirmModal('{{ route('admin.documents.approve', $d->id) }}', 'Setujui Dokumen', 'Validasi dokumen?', 'bg-primary-600 hover:bg-primary-700', 'Sesuai')" class="text-white bg-primary-600 hover:bg-primary-700 rounded-lg p-2 transition-transform active:scale-95">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                    </button>
                                    <button type="button" data-modal-target="rejectModal" data-modal-toggle="rejectModal" data-doc-id="{{ $d->id }}" data-doc-name="{{ $d->original_filename }}" class="reject-btn text-white bg-red-600 hover:bg-red-700 rounded-lg p-2 transition-transform active:scale-95">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="2" class="px-6 py-10 text-center text-gray-400 italic">Semua dokumen telah diperiksa.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </x-content-card>
    </div>

    <!-- GRAFIK STATS -->
    <x-content-card class="mb-8">
        <div class="flex items-center justify-between mb-6">
            <h3 class="text-sm font-bold text-gray-900 uppercase tracking-wider">Analitik Aktivitas</h3>
            <form method="GET" action="{{ route('admin.dashboard') }}" id="chartFilterForm">
                <select name="filter" onchange="this.form.submit()" class="bg-gray-50 border-gray-100 text-gray-600 text-xs rounded-xl focus:ring-primary-500 focus:border-primary-500">
                    <option value="7_days" {{ $filter == '7_days' ? 'selected' : '' }}>Seminggu Terakhir</option>
                    <option value="1_month" {{ $filter == '1_month' ? 'selected' : '' }}>Sebulan Terakhir</option>
                    <option value="1_year" {{ $filter == '1_year' ? 'selected' : '' }}>Setahun Terakhir</option>
                </select>
            </form>
        </div>
        <div class="h-80 w-full">
            <canvas id="activityChart"></canvas>
        </div>
    </x-content-card>

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
