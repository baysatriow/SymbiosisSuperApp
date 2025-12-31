<x-app-layout>
    <div class="max-w-5xl mx-auto">
        <!-- Header -->
        <div class="mb-8">
            <nav class="flex mb-4" aria-label="Breadcrumb">
                <ol class="inline-flex items-center space-x-1 md:space-x-3 bg-white dark:bg-gray-800 px-4 py-2 rounded-lg shadow-sm border border-gray-100 dark:border-gray-700">
                    <li class="inline-flex items-center">
                        <a href="{{ route('user.dashboard') }}" class="inline-flex items-center text-sm font-medium text-gray-500 hover:text-green-600 dark:text-gray-400 dark:hover:text-white transition-colors">
                            <svg class="w-3 h-3 mr-2.5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                                <path d="m19.707 9.293-2-2-7-7a1 1 0 0 0-1.414 0l-7 7-2 2a1 1 0 0 0 1.414 1.414L2 10.414V18a2 2 0 0 0 2 2h3a1 1 0 0 0 1-1v-4a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v4a1 1 0 0 0 1 1h3a2 2 0 0 0 2-2v-7.586l.293.293a1 1 0 0 0 1.414-1.414Z"/>
                            </svg>
                            Dashboard
                        </a>
                    </li>
                    <li>
                        <div class="flex items-center">
                            <svg class="w-3 h-3 text-gray-400 mx-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 9 4-4-4-4"/>
                            </svg>
                            <a href="{{ route('user.esg.index') }}" class="ml-1 text-sm font-medium text-gray-500 hover:text-green-600 dark:text-gray-400 dark:hover:text-white md:ml-2 transition-colors">Laporan ESG</a>
                        </div>
                    </li>
                    <li aria-current="page">
                        <div class="flex items-center">
                            <svg class="w-3 h-3 text-gray-400 mx-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 9 4-4-4-4"/>
                            </svg>
                            <span class="ml-1 text-sm font-medium text-gray-700 md:ml-2 dark:text-gray-200">Buat Laporan Baru</span>
                        </div>
                    </li>
                </ol>
            </nav>
            
            <h1 class="text-3xl font-bold text-gray-900 dark:text-white tracking-tight">Generate Laporan ESG</h1>
            <p class="mt-2 text-gray-600 dark:text-gray-400 text-lg">Buat draf laporan keberlanjutan profesional menggunakan AI berdasarkan dokumen Anda.</p>
        </div>

        @if($pendingReport)
            <!-- Warning: Existing Report in Progress -->
            <div
                class="mb-8 p-6 bg-gradient-to-r from-yellow-50 to-orange-50 dark:from-yellow-900/20 dark:to-orange-900/20 border border-yellow-200 dark:border-yellow-800 rounded-xl shadow-sm">
                <div class="flex flex-col md:flex-row items-center justify-between gap-4">
                    <div class="flex items-start gap-4">
                        <div class="p-3 bg-yellow-100 dark:bg-yellow-900 rounded-lg text-yellow-600 dark:text-yellow-400">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-lg font-bold text-yellow-800 dark:text-yellow-100">Laporan Sedang Diproses</h3>
                            <p class="text-yellow-700 dark:text-yellow-300 mt-1">
                                Anda memiliki laporan <strong>"{{ $pendingReport->title }}"</strong> yang belum selesai.
                                Harap tunggu hingga selesai sebelum membuat yang baru.
                            </p>
                        </div>
                    </div>
                    <a href="{{ route('user.esg.show', $pendingReport->id) }}"
                        class="white-space-nowrap px-6 py-2.5 bg-yellow-600 text-white font-semibold rounded-lg hover:bg-yellow-700 transition shadow-md w-full md:w-auto text-center">
                        Lihat Progress
                    </a>
                </div>
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Left: Form -->
            <div class="lg:col-span-2 space-y-8">
                <form action="{{ route('user.esg.store') }}" method="POST" class="space-y-8">
                    @csrf

                    <!-- Section 1: Detail Laporan -->
                    <div
                        class="bg-white dark:bg-gray-800 p-6 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700">
                        <div class="flex items-center gap-3 mb-6">
                            <span
                                class="flex items-center justify-center w-8 h-8 rounded-full bg-green-100 text-green-700 font-bold text-sm">1</span>
                            <h3 class="text-xl font-bold text-gray-900 dark:text-white">Detail Laporan</h3>
                        </div>

                        <div class="space-y-6">
                            <div>
                                <label for="title"
                                    class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Judul
                                    Laporan</label>
                                <input type="text" id="title" name="title"
                                    value="{{ old('title', 'Laporan ESG ' . Auth::user()->companyProfile?->company_name . ' ' . now()->year) }}"
                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-xl focus:ring-green-500 focus:border-green-500 block w-full p-3.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white transition-all shadow-sm focus:shadow-md"
                                    placeholder="Contoh: Laporan Keberlanjutan 2025" required>
                                <p class="mt-2 text-xs text-gray-500 dark:text-gray-400">Judul ini akan muncul di
                                    halaman cover laporan.</p>
                                @error('title') <p class="mt-1 text-sm text-red-600 text-red-500">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label class="block mb-3 text-sm font-medium text-gray-900 dark:text-white">Format
                                    Dokumen</label>
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                    <label
                                        class="relative flex flex-col items-center p-5 border-2 rounded-xl cursor-pointer transition-all hover:border-green-500 hover:bg-green-50/50 dark:hover:bg-green-900/10 hover:shadow-md has-[:checked]:border-green-600 has-[:checked]:bg-green-50 dark:has-[:checked]:bg-green-900/20 has-[:checked]:shadow-md">
                                        <input type="radio" name="output_format" value="docx" class="sr-only peer"
                                            checked>
                                        <div
                                            class="absolute top-3 right-3 opacity-0 peer-checked:opacity-100 transition-opacity">
                                            <svg class="w-6 h-6 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd"
                                                    d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                                    clip-rule="evenodd" />
                                            </svg>
                                        </div>
                                        <div
                                            class="w-12 h-12 mb-3 bg-blue-100 rounded-lg flex items-center justify-center text-blue-600">
                                            <svg class="w-7 h-7" fill="currentColor" viewBox="0 0 24 24">
                                                <path
                                                    d="M6 2a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8l-6-6H6zm0 2h7v5h5v11H6V4zm2 8v2h8v-2H8zm0 4v2h5v-2H8z" />
                                            </svg>
                                        </div>
                                        <span class="font-bold text-gray-900 dark:text-white">Microsoft Word
                                            (.docx)</span>
                                        <span class="text-xs text-gray-500 dark:text-gray-400 mt-1 text-center">Bisa
                                            diedit kembali (Recommended)</span>
                                    </label>

                                    <label
                                        class="relative flex flex-col items-center p-5 border-2 rounded-xl cursor-pointer transition-all hover:border-red-500 hover:bg-red-50/50 dark:hover:bg-red-900/10 hover:shadow-md has-[:checked]:border-red-600 has-[:checked]:bg-red-50 dark:has-[:checked]:bg-red-900/20 has-[:checked]:shadow-md">
                                        <input type="radio" name="output_format" value="pdf" class="sr-only peer">
                                        <div
                                            class="absolute top-3 right-3 opacity-0 peer-checked:opacity-100 transition-opacity">
                                            <svg class="w-6 h-6 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd"
                                                    d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                                    clip-rule="evenodd" />
                                            </svg>
                                        </div>
                                        <div
                                            class="w-12 h-12 mb-3 bg-red-100 rounded-lg flex items-center justify-center text-red-600">
                                            <svg class="w-7 h-7" fill="currentColor" viewBox="0 0 24 24">
                                                <path
                                                    d="M6 2a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8l-6-6H6zm0 2h7v5h5v11H6V4zm3 9c-.6 0-1 .4-1 1v3c0 .6.4 1 1 1s1-.4 1-1v-1h.5c.8 0 1.5-.7 1.5-1.5S11.3 13 10.5 13H9zm2.5 0h1c.8 0 1.5.7 1.5 1.5v2c0 .8-.7 1.5-1.5 1.5h-1c-.3 0-.5-.2-.5-.5v-4c0-.3.2-.5.5-.5zm3 0h2c.3 0 .5.2.5.5s-.2.5-.5.5H15v1h1c.3 0 .5.2.5.5s-.2.5-.5.5h-1v1.5c0 .3-.2.5-.5.5s-.5-.2-.5-.5v-4c0-.3.2-.5.5-.5z" />
                                            </svg>
                                        </div>
                                        <span class="font-bold text-gray-900 dark:text-white">PDF Document (.pdf)</span>
                                        <span class="text-xs text-gray-500 dark:text-gray-400 mt-1 text-center">Format
                                            siap cetak/distribusi</span>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Section 2: Sumber Data -->
                    <div
                        class="bg-white dark:bg-gray-800 p-6 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700">
                        <div class="flex items-center gap-3 mb-6">
                            <span
                                class="flex items-center justify-center w-8 h-8 rounded-full bg-green-100 text-green-700 font-bold text-sm">2</span>
                            <h3 class="text-xl font-bold text-gray-900 dark:text-white">Sumber Data</h3>
                        </div>

                        <div
                            class="bg-blue-50/50 dark:bg-blue-900/10 rounded-xl border border-blue-100 dark:border-blue-800 p-4 mb-4">
                            <div class="flex gap-3">
                                <svg class="w-5 h-5 text-blue-600 dark:text-blue-400 mt-0.5" fill="none"
                                    stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <div class="text-sm text-blue-700 dark:text-blue-300">
                                    AI akan menganalisis dokumen yang <strong>bertanda 'Approved'</strong> untuk mengisi
                                    konten laporan secara otomatis.
                                </div>
                            </div>
                        </div>

                        @if($documents->count() > 0)
                            <div class="space-y-3">
                                <p class="text-sm font-medium text-gray-700 dark:text-gray-300">
                                    {{ $documents->count() }} Dokumen Tersedia:
                                </p>
                                <div class="max-h-60 overflow-y-auto space-y-2 pr-2 custom-scrollbar">
                                    @foreach($documents as $doc)
                                        <div
                                            class="flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-700/50 rounded-lg group hover:bg-white dark:hover:bg-gray-700 border border-transparent hover:border-gray-200 dark:hover:border-gray-600 transition-all">
                                            <div class="flex items-center gap-3 overflow-hidden">
                                                <div
                                                    class="min-w-8 w-8 h-8 rounded bg-white dark:bg-gray-800 flex items-center justify-center text-gray-500 shadow-sm">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                                    </svg>
                                                </div>
                                                <div class="flex flex-col min-w-0">
                                                    <span
                                                        class="text-sm font-medium text-gray-900 dark:text-white truncate">{{ $doc->original_filename }}</span>
                                                    <span
                                                        class="text-xs text-gray-500 dark:text-gray-400 font-mono">{{ $doc->subfield?->name }}</span>
                                                </div>
                                            </div>
                                            <svg class="w-5 h-5 text-green-500 flex-shrink-0" fill="currentColor"
                                                viewBox="0 0 20 20">
                                                <path fill-rule="evenodd"
                                                    d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                                    clip-rule="evenodd" />
                                            </svg>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @else
                            <div
                                class="text-center p-8 border-2 border-dashed border-gray-300 dark:border-gray-600 rounded-xl">
                                <svg class="w-12 h-12 mx-auto text-gray-400 mb-3" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 13h6m-3-3v6m5 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                                <p class="text-gray-600 dark:text-gray-400 font-medium">Belum ada dokumen yang disetujui.
                                </p>
                                <p class="text-sm text-gray-500 dark:text-gray-500 mt-1">Silakan upload dan tunggu
                                    persetujuan dokumen di menu Repository.</p>
                                <a href="{{ route('user.documents') }}"
                                    class="inline-flex items-center gap-2 mt-4 text-sm font-medium text-primary-600 hover:text-primary-700">
                                    Ke Repository Dokumen &rarr;
                                </a>
                            </div>
                        @endif
                    </div>

                    <!-- Action Button -->
                    <button type="submit"
                        class="w-full py-4 bg-gradient-to-r from-green-600 to-emerald-600 hover:from-green-700 hover:to-emerald-700 text-white font-bold rounded-xl shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 transition-all disabled:opacity-50 disabled:cursor-not-allowed disabled:transform-none flex items-center justify-center gap-3 text-lg"
                        {{ $documents->count() === 0 || $pendingReport ? 'disabled' : '' }}>
                        <svg class="w-6 h-6 animate-pulse" fill="none" viewBox="0 0 24 24">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19.5 12c.5-3-1.5-4-1.5-4s-3.5 1-4 3c-.5-2-2.5-3-2.5-3s-1 2.5-1 6c0 3 2.5 5.5 5.5 5.5S21.5 16 19.5 12zM12 12c.5-3-1.5-4-1.5-4s-3.5 1-4 3c-.5-2-2.5-3-2.5-3s-1 2.5-1 6c0 3 2.5 5.5 5.5 5.5S14 16 12 12z" />
                        </svg>
                        Mulai Generate Laporan (AI)
                    </button>
                    <p class="text-center text-xs text-gray-500 dark:text-gray-400">
                        Proses ini memakan waktu sekitar 5-10 menit. Anda akan diberitahu saat selesai.
                    </p>
                </form>
            </div>

            <!-- Right: Structure Preview -->
            <div class="lg:col-span-1">
                <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 text-gray-900 dark:text-white p-6 rounded-2xl shadow-sm sticky top-24">
                    <div class="flex items-center justify-between mb-6 border-b border-gray-200 dark:border-gray-700 pb-4">
                        <h3 class="font-bold text-lg">Struktur Laporan</h3>
                        <span class="px-2.5 py-0.5 text-xs font-semibold bg-gray-100 dark:bg-gray-700 rounded-full text-gray-600 dark:text-gray-300">7 Bab</span>
                    </div>
                    
                    <div class="relative pl-2 space-y-6">
                        <!-- Connecting Line -->
                        <div class="absolute top-2 left-2 bottom-4 w-0.5 bg-gray-200 dark:bg-gray-700"></div>

                        @foreach($reportStructure as $chapterNum => $chapter)
                            <div class="relative pl-6 group">
                                <!-- Dot -->
                                <div class="absolute left-0 top-1.5 w-4 h-4 bg-white dark:bg-gray-800 border-2 border-green-500 rounded-full group-hover:bg-green-500 transition-colors z-10"></div>
                                
                                <h4 class="font-bold text-sm text-gray-800 dark:text-gray-200 group-hover:text-green-600 dark:group-hover:text-green-400 transition-colors">
                                    Bab {{ $chapterNum }}: {{ Str::limit($chapter['title'], 30) }}
                                </h4>
                                
                                <ul class="mt-2 space-y-1.5">
                                    @foreach($chapter['subchapters'] as $subKey => $sub)
                                        <li class="flex items-start gap-2 text-xs text-gray-500 dark:text-gray-400">
                                            <span class="text-gray-400 dark:text-gray-600 mt-0.5">•</span>
                                            <span>{{ $sub['title'] }}</span>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>