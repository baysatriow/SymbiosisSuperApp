<x-app-layout>
    <div class="max-w-5xl mx-auto">
    <x-page-header 
        title="Generate Laporan ESG" 
        subtitle="Buat draf laporan keberlanjutan profesional menggunakan AI berdasarkan dokumen Anda." 
    />

    @if($pendingReport)
        <x-content-card class="bg-orange-50 border-orange-100 flex flex-col md:flex-row items-center justify-between gap-6 mb-8 !py-6">
            <div class="flex items-start gap-4">
                <div class="bg-orange-100 p-3 rounded-2xl text-orange-600 shadow-sm border border-orange-200">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
                <div>
                    <h4 class="text-sm font-bold text-orange-900 uppercase tracking-wider">Laporan Sedang Diproses</h4>
                    <p class="text-xs text-orange-700 mt-1 leading-relaxed">Anda memiliki laporan <strong>"{{ $pendingReport->title }}"</strong> yang belum selesai.</p>
                </div>
            </div>
            <a href="{{ route('user.esg.show', $pendingReport->id) }}" class="px-6 py-2.5 bg-orange-600 text-white text-[10px] font-bold uppercase tracking-widest rounded-xl hover:bg-orange-700 transition shadow-sm active:scale-95">Lihat Progress</a>
        </x-content-card>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- FORM COLUMN -->
        <div class="lg:col-span-2 space-y-6">
            <form action="{{ route('user.esg.store') }}" method="POST">
                @csrf
                
                <div class="space-y-6">
                    <x-content-card>
                        <h4 class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-8 border-b border-gray-50 pb-4">1. Konfigurasi Laporan</h4>
                        
                        <div class="space-y-8">
                            <div>
                                <label class="block mb-2 text-xs font-bold text-gray-400 uppercase">Judul Laporan</label>
                                <input type="text" name="title" value="{{ old('title', 'Laporan ESG ' . Auth::user()->companyProfile?->company_name . ' ' . now()->year) }}" class="bg-gray-50 border-gray-100 text-gray-900 text-sm rounded-xl focus:ring-primary-500 focus:border-primary-500 block w-full p-4 font-bold" required>
                                <p class="mt-2 text-[10px] text-gray-400 font-medium italic">Judul ini akan digunakan sebagai judul utama di halaman cover.</p>
                            </div>

                            <div>
                                <label class="block mb-4 text-xs font-bold text-gray-400 uppercase">Format Output</label>
                                <div class="grid grid-cols-2 gap-4">
                                    <label class="relative flex flex-col items-center p-6 border border-gray-100 rounded-2xl cursor-pointer transition-all hover:bg-gray-50 group selection-label">
                                        <input type="radio" name="output_format" value="docx" class="sr-only peer" checked>
                                        <div class="peer-checked:ring-2 peer-checked:ring-primary-500 peer-checked:border-primary-500 absolute inset-0 rounded-2xl transition-all"></div>
                                        <div class="w-12 h-12 mb-3 bg-blue-50 text-blue-600 rounded-xl flex items-center justify-center border border-blue-100 group-hover:scale-110 transition-transform shadow-sm">
                                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                        </div>
                                        <span class="text-xs font-bold text-gray-900 z-10 uppercase tracking-wider">MS Word</span>
                                        <span class="text-[9px] text-gray-400 mt-1 z-10 lowercase">dapat diedit kembali</span>
                                    </label>

                                    <label class="relative flex flex-col items-center p-6 border border-gray-100 rounded-2xl cursor-pointer transition-all hover:bg-gray-50 group selection-label">
                                        <input type="radio" name="output_format" value="pdf" class="sr-only peer">
                                        <div class="peer-checked:ring-2 peer-checked:ring-red-500 peer-checked:border-red-500 absolute inset-0 rounded-2xl transition-all"></div>
                                        <div class="w-12 h-12 mb-3 bg-red-50 text-red-600 rounded-xl flex items-center justify-center border border-red-100 group-hover:scale-110 transition-transform shadow-sm">
                                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg>
                                        </div>
                                        <span class="text-xs font-bold text-gray-900 z-10 uppercase tracking-wider">PDF Docs</span>
                                        <span class="text-[9px] text-gray-400 mt-1 z-10 lowercase">siap dipublikasi</span>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </x-content-card>

                    <x-content-card>
                        <h4 class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-6 border-b border-gray-50 pb-4">2. Analisis Sumber Data</h4>
                        
                        <div class="bg-primary-50 rounded-2xl p-4 flex gap-4 border border-primary-100 mb-8">
                            <div class="bg-white p-2 rounded-xl text-primary-600 shadow-sm border border-primary-100 flex-shrink-0">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            </div>
                            <p class="text-[11px] text-primary-900 leading-relaxed font-medium">AI akan memproses semua dokumen Anda yang memiliki status <span class="font-bold uppercase tracking-widest text-[10px]">Approved</span> untuk menyusun narasi laporan.</p>
                        </div>

                        @if($documents->count() > 0)
                            <div class="space-y-3">
                                <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-4">{{ $documents->count() }} Dokumen Terverifikasi:</p>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-3 max-h-80 overflow-y-auto pr-2 custom-scrollbar">
                                    @foreach($documents as $doc)
                                        <div class="p-3 bg-gray-50 rounded-xl border border-gray-100 flex items-center gap-3 group hover:bg-white hover:shadow-sm transition-all">
                                            <div class="w-8 h-8 rounded-lg bg-white border border-gray-100 flex items-center justify-center text-gray-400 group-hover:text-primary-600 group-hover:border-primary-100 transition-colors shadow-sm">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg>
                                            </div>
                                            <div class="min-w-0">
                                                <p class="text-[11px] font-bold text-gray-900 truncate" title="{{ $doc->original_filename }}">{{ $doc->original_filename }}</p>
                                                <p class="text-[9px] font-medium text-gray-400 truncate uppercase mt-0.5">{{ $doc->subfield?->name }}</p>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @else
                            <div class="text-center py-12 bg-gray-50 rounded-3xl border-2 border-dashed border-gray-100">
                                <div class="w-16 h-16 bg-white rounded-full flex items-center justify-center mx-auto mb-4 shadow-sm text-gray-200">
                                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 13h6m-3-3v6m5 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                </div>
                                <h5 class="text-xs font-bold text-gray-400 uppercase tracking-wider">Akses Terbatas</h5>
                                <p class="text-[10px] text-gray-400 mt-2 px-8 leading-relaxed">Belum ada dokumen yang disetujui. Silakan unggah dokumen di menu Repository agar AI dapat menganalisis data Anda.</p>
                                <a href="{{ route('user.documents') }}" class="mt-6 inline-flex items-center text-[10px] font-bold text-primary-600 uppercase tracking-widest hover:underline px-4 py-2 bg-white rounded-full shadow-sm border border-primary-50">Repository &rarr;</a>
                            </div>
                        @endif
                    </x-content-card>

                    <div class="pt-4">
                        <button type="submit" 
                            class="w-full py-5 bg-primary-600 hover:bg-primary-700 text-white font-bold rounded-2xl shadow-lg shadow-primary-200 hover:shadow-xl hover:-translate-y-1 transition-all disabled:opacity-50 disabled:cursor-not-allowed disabled:shadow-none flex items-center justify-center gap-4 text-sm"
                            {{ $documents->count() === 0 || $pendingReport ? 'disabled' : '' }}>
                            <svg class="w-5 h-5 animate-pulse" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                            GENERATE LAPORAN SEKARANG
                        </button>
                        <p class="text-center text-[10px] text-gray-400 mt-4 italic font-medium">Estimasi waktu proses 5-15 menit tergantung jumlah dokumen.</p>
                    </div>
                </div>
            </form>
        </div>

        <!-- PREVIEW COLUMN -->
        <div class="lg:col-span-1">
            <x-content-card class="sticky top-24">
                <div class="flex items-center justify-between mb-8 border-b border-gray-50 pb-4">
                    <h4 class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Outline Laporan</h4>
                    <span class="px-3 py-1 bg-gray-50 border border-gray-100 rounded-full text-[9px] font-bold text-gray-500 uppercase">7 Bab Utama</span>
                </div>

                <div class="space-y-6 relative ml-2">
                    <div class="absolute left-[-9px] top-2 bottom-4 w-px bg-gray-100"></div>

                    @foreach($reportStructure as $chapterNum => $chapter)
                        <div class="relative pl-6 group">
                            <div class="absolute left-[-13px] top-1.5 w-2 h-2 rounded-full border-2 border-white bg-gray-200 group-hover:bg-primary-500 transition-colors z-10 shadow-sm ring-4 ring-white"></div>
                            <h5 class="text-[11px] font-bold text-gray-900 group-hover:text-primary-600 transition-colors uppercase tracking-wider mb-2">Bab {{ $chapterNum }}: {{ $chapter['title'] }}</h5>
                            <div class="space-y-1.5 pl-1">
                                @foreach($chapter['subchapters'] as $sub)
                                    <div class="flex items-center gap-2 text-[10px] text-gray-400 font-medium">
                                        <span class="w-1 h-1 rounded-full bg-gray-300"></span>
                                        <span class="truncate">{{ $sub['title'] }}</span>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                </div>
            </x-content-card>
        </div>
    </div>
</x-app-layout>