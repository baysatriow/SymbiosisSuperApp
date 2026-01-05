<x-app-layout>
    <div class="max-w-6xl mx-auto">
    <x-page-header 
        title="Laporan ESG" 
        subtitle="Generate dan pantau draf laporan keberlanjutan Anda menggunakan teknologi AI.">
        <x-slot:actions>
            <a href="{{ route('user.esg.create') }}" class="inline-flex items-center px-4 py-2 bg-primary-600 text-white rounded-xl text-xs font-bold hover:bg-primary-700 transition-all shadow-sm">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                Buat Laporan Baru
            </a>
        </x-slot:actions>
    </x-page-header>

    <!-- STATS SUMMARY -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-6 mb-8">
        <x-stat-card 
            label="Laporan Selesai" 
            value="{{ $reports->where('status', 'completed')->count() }}" 
            icon='<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>'
            trend="Arsip tersedia"
            iconColor="emerald"
        />
        <x-stat-card 
            label="Sedang Diproses" 
            value="{{ $reports->whereIn('status', ['pending', 'processing'])->count() }}" 
            icon='<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.586m10.343 1.172a8.5 8.5 0 11.037-5.437m1.51-3.251L14.636 1c-.135-.11-.3-.21-.493-.277a.972.972 0 00-.733.023l-3.376 2.052"></path></svg>'
            trend="Mohon tunggu"
            iconColor="blue"
        />
        <x-stat-card 
            label="Total Draf" 
            value="{{ $reports->total() }}" 
            icon='<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>'
            trend="Seluruh riwayat"
            iconColor="purple"
        />
    </div>

    <!-- REPORTS LIST -->
    <div class="space-y-4">
        @forelse($reports as $report)
            <x-content-card class="group">
                <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-6">
                    <div class="flex items-center gap-5 flex-1 min-w-0">
                        <div class="w-14 h-14 rounded-2xl flex items-center justify-center border shadow-sm transition-transform group-hover:scale-105
                            @if($report->status === 'completed') bg-emerald-50 border-emerald-100 text-emerald-600
                            @elseif($report->status === 'processing') bg-blue-50 border-blue-100 text-blue-600
                            @elseif($report->status === 'failed') bg-red-50 border-red-100 text-red-600
                            @else bg-orange-50 border-orange-100 text-orange-600 @endif">
                            @if($report->status === 'completed')
                                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            @elseif($report->status === 'processing')
                                <svg class="w-7 h-7 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path></svg>
                            @else
                                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            @endif
                        </div>

                        <div class="flex-1 min-w-0">
                            <h3 class="text-base font-bold text-gray-900 group-hover:text-primary-600 transition-colors truncate">{{ $report->title }}</h3>
                            <div class="flex flex-wrap items-center gap-3 mt-1">
                                <span class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">{{ $report->created_at->format('d M Y') }}</span>
                                <span class="w-1 h-1 rounded-full bg-gray-200"></span>
                                <span class="bg-gray-50 text-gray-500 text-[9px] font-bold px-2 py-0.5 rounded border border-gray-100 uppercase">{{ strtoupper($report->output_format) }}</span>
                                @if($report->status === 'processing' && $report->progress)
                                    <span class="text-[10px] font-bold text-blue-600 animate-pulse bg-blue-50 px-2 py-0.5 rounded border border-blue-100 lowercase">
                                        {{ $report->progress['percentage'] ?? 0 }}% • {{ $report->progress['current_section'] ?? 'Memproses...' }}
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="flex items-center gap-4">
                        <span class="text-[10px] font-bold px-3 py-1.5 rounded-full uppercase border
                            @if($report->status === 'completed') bg-emerald-50 text-emerald-600 border-emerald-100
                            @elseif($report->status === 'processing') bg-blue-50 text-blue-600 border-blue-100
                            @elseif($report->status === 'failed') bg-red-50 text-red-600 border-red-100
                            @else bg-orange-50 text-orange-600 border-orange-100 @endif">
                            {{ $report->status_label }}
                        </span>

                        <div class="flex items-center gap-2 border-l border-gray-50 pl-4 ml-2">
                            <a href="{{ route('user.esg.show', $report->id) }}" class="p-2 text-gray-400 hover:text-primary-600 hover:bg-primary-50 rounded-xl transition-all" title="Lihat Detail">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                            </a>
                            @if($report->status === 'completed' && $report->file_path)
                                <a href="{{ route('user.esg.download', $report->id) }}" class="p-2 text-emerald-500 hover:bg-emerald-50 rounded-xl transition-all" title="Download">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                                </a>
                            @endif
                            @if(!in_array($report->status, ['processing']))
                                <form action="{{ route('user.esg.destroy', $report->id) }}" method="POST" onsubmit="return confirm('Hapus laporan?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="p-2 text-red-400 hover:bg-red-50 rounded-xl transition-all" title="Hapus">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>
                </div>

                @if($report->status === 'processing' && $report->progress)
                    <div class="mt-4 w-full bg-gray-50 rounded-full h-1 overflow-hidden">
                        <div class="bg-primary-500 h-1 rounded-full transition-all duration-500" style="width: {{ $report->progress['percentage'] ?? 0 }}%"></div>
                    </div>
                @endif
            </x-content-card>
        @empty
            <x-content-card class="text-center !py-16">
                <div class="w-20 h-20 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-4 text-gray-200">
                    <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 13h6m-3-3v6m5 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                </div>
                <h3 class="text-sm font-bold text-gray-900 uppercase tracking-wider mb-2">Belum Ada Laporan</h3>
                <p class="text-xs text-gray-400 max-w-xs mx-auto mb-8 leading-relaxed">Anda belum pernah membuat laporan ESG. Silakan klik tombol di atas untuk memulai draf pertama Anda.</p>
                <a href="{{ route('user.esg.create') }}" class="inline-flex items-center px-6 py-3 bg-primary-600 text-white rounded-xl text-xs font-bold hover:bg-primary-700 transition-all shadow-sm uppercase tracking-widest">Mulai Laporan Baru</a>
            </x-content-card>
        @endforelse
    </div>

    @if($reports->hasPages())
        <div class="mt-8">
            {{ $reports->links() }}
        </div>
    @endif
</x-app-layout>