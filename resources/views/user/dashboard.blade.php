<x-app-layout>
    <div class="grid grid-cols-1 gap-4 mb-4">

        <!-- 1. Header & Tier Badge -->
        <div class="flex flex-col md:flex-row justify-between items-center p-6 mb-2 bg-white border border-gray-200 rounded-lg shadow-sm dark:border-gray-700 dark:bg-gray-800">
            <div class="mb-4 md:mb-0">
                <h2 class="text-3xl font-bold tracking-tight text-gray-900 dark:text-white">
                    Halo, {{ Auth::user()->full_name }}! 👋
                </h2>
                <p class="mt-1 font-normal text-gray-500 dark:text-gray-400">
                    Selamat datang di Symbiosis. Pantau dokumen dan kelola profil Anda di sini.
                </p>
            </div>
            <div class="text-center md:text-right">
                <span class="bg-gray-100 text-gray-800 text-xs font-medium inline-flex items-center px-3 py-1 rounded-full mb-2 dark:bg-gray-700 dark:text-gray-300 border border-gray-400">
                    <svg class="w-3 h-3 me-1.5 text-gray-500 dark:text-gray-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M10 0a10 10 0 1 0 10 10A10.01 10.01 0 0 0 10 0Zm3.982 13.982a1 1 0 0 1-1.414 0l-3.274-3.274A1.012 1.012 0 0 1 9 10V6a1 1 0 0 1 2 0v3.586l2.982 2.982a1 1 0 0 1 0 1.414Z"/>
                    </svg>
                    Status Akun
                </span>
                <div>
                    @if(strtolower($tierLabel) == 'green')
                        <span class="bg-green-100 text-green-800 text-xl font-bold px-5 py-1.5 rounded-lg dark:bg-green-900 dark:text-green-300 border border-green-400 shadow-sm">Green Tier</span>
                    @elseif(strtolower($tierLabel) == 'amber' || strtolower($tierLabel) == 'gold')
                        <span class="bg-yellow-100 text-yellow-800 text-xl font-bold px-5 py-1.5 rounded-lg dark:bg-yellow-900 dark:text-yellow-300 border border-yellow-400 shadow-sm">Gold Tier</span>
                    @else
                        <span class="bg-gray-100 text-gray-800 text-xl font-bold px-5 py-1.5 rounded-lg dark:bg-gray-700 dark:text-gray-300 border border-gray-500 shadow-sm">Basic Tier</span>
                    @endif
                </div>
            </div>
        </div>

        <!-- 2. Status Kelengkapan Profil (Action Cards) -->
        <!-- Area ini dibuat mencolok agar user langsung tahu harus melengkapi data -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">

            <!-- Card Profil Pengguna -->
            <a href="{{ route('user.profile') }}" class="flex items-center p-4 bg-white border rounded-lg shadow-sm hover:bg-gray-50 dark:bg-gray-800 dark:border-gray-700 dark:hover:bg-gray-700 group transition-all duration-200 {{ $user->userProfile?->is_completed ? 'border-green-200 dark:border-green-900' : 'border-red-300 dark:border-red-800 ring-1 ring-red-200 dark:ring-red-900' }}">
                <div class="p-3 mr-4 rounded-full {{ $user->userProfile?->is_completed ? 'bg-green-100 text-green-600 dark:bg-green-900 dark:text-green-300' : 'bg-red-100 text-red-600 dark:bg-red-900 dark:text-red-300' }}">
                    <!-- Icon User -->
                    <svg class="w-8 h-8" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M10 0a10 10 0 1 0 10 10A10.01 10.01 0 0 0 10 0Zm0 5a3 3 0 1 1 0 6 3 3 0 0 1 0-6Zm0 13a8.949 8.949 0 0 1-4.951-1.488A3.987 3.987 0 0 1 9 13h2a3.987 3.987 0 0 1 3.951 3.512A8.949 8.949 0 0 1 10 18Z"/>
                    </svg>
                </div>
                <div class="flex-1">
                    <h5 class="text-lg font-bold tracking-tight text-gray-900 dark:text-white group-hover:text-primary-600">
                        Profil Data Diri
                    </h5>
                    <p class="font-normal text-sm text-gray-700 dark:text-gray-400">
                        @if($user->userProfile?->is_completed)
                            <span class="text-green-600 flex items-center gap-1">
                                <svg class="w-3.5 h-3.5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 16 12">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M1 5.917 5.724 10.5 15 1.5"/>
                                </svg>
                                Data Lengkap
                            </span>
                        @else
                            <span class="text-red-600 font-medium">Data Belum Lengkap!</span>
                        @endif
                    </p>
                </div>
                <div>
                    <svg class="w-4 h-4 text-gray-400 group-hover:text-primary-600" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 9 4-4-4-4"/>
                    </svg>
                </div>
            </a>

            <!-- Card Profil Perusahaan -->
            <a href="{{ route('user.company') }}" class="flex items-center p-4 bg-white border rounded-lg shadow-sm hover:bg-gray-50 dark:bg-gray-800 dark:border-gray-700 dark:hover:bg-gray-700 group transition-all duration-200 {{ $user->companyProfile?->is_completed ? 'border-green-200 dark:border-green-900' : 'border-red-300 dark:border-red-800 ring-1 ring-red-200 dark:ring-red-900' }}">
                <div class="p-3 mr-4 rounded-full {{ $user->companyProfile?->is_completed ? 'bg-blue-100 text-blue-600 dark:bg-blue-900 dark:text-blue-300' : 'bg-red-100 text-red-600 dark:bg-red-900 dark:text-red-300' }}">
                    <!-- Icon Building/Company -->
                    <svg class="w-8 h-8" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M2 2a2 2 0 0 1 2-2h1a2 2 0 0 1 2 2v12h2V7a2 2 0 0 1 2-2h1a2 2 0 0 1 2 2v9h6a1 1 0 0 0-1-1H4a1 1 0 0 0-1 1V2Zm2 16h12V7h-2v9H9v-9H7v9H4v-2Z"/>
                        <path d="M7 16h2v-2H7v2Zm4 0h2v-2h-2v2Zm-4-4h2v-2H7v2Zm4 0h2v-2h-2v2Z"/>
                    </svg>
                </div>
                <div class="flex-1">
                    <h5 class="text-lg font-bold tracking-tight text-gray-900 dark:text-white group-hover:text-primary-600">
                        Profil Perusahaan
                    </h5>
                    <p class="font-normal text-sm text-gray-700 dark:text-gray-400">
                        @if($user->companyProfile?->is_completed)
                            <span class="text-green-600 flex items-center gap-1">
                                <svg class="w-3.5 h-3.5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 16 12">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M1 5.917 5.724 10.5 15 1.5"/>
                                </svg>
                                Data Lengkap
                            </span>
                        @else
                            <span class="text-red-600 font-medium">Data Belum Lengkap!</span>
                        @endif
                    </p>
                </div>
                <div>
                    <svg class="w-4 h-4 text-gray-400 group-hover:text-primary-600" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 9 4-4-4-4"/>
                    </svg>
                </div>
            </a>

        </div>

        <!-- Global Warning (Jika salah satu belum lengkap) -->
        @if(!$isAllComplete)
        <div id="alert-border-2" class="flex items-center p-4 mb-4 text-red-800 border-t-4 border-red-300 bg-red-50 dark:text-red-400 dark:bg-gray-800 dark:border-red-800" role="alert">
            <svg class="flex-shrink-0 w-4 h-4" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
              <path d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5ZM9.5 4a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3ZM12 15H8a1 1 0 0 1 0-2h1v-3H8a1 1 0 0 1 0-2h2a1 1 0 0 1 1 1v4h1a1 1 0 0 1 0 2Z"/>
            </svg>
            <div class="ms-3 text-sm font-medium">
                Akses menu <b>Dokumen</b> dan <b>Chatbot</b> masih terkunci. Silakan lengkapi kedua profil di atas terlebih dahulu.
            </div>
        </div>
        @endif

        <!-- 3. Statistik Grid (Icon Baru) -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-4">
            <!-- Card 1: Total Dokumen -->
            <div class="p-4 bg-white border border-gray-200 rounded-lg shadow-sm dark:border-gray-700 sm:p-6 dark:bg-gray-800">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Total Dokumen</h3>
                    <div class="p-2 bg-purple-100 rounded-lg dark:bg-purple-900">
                        <!-- Icon Document Stack -->
                        <svg class="w-6 h-6 text-purple-600 dark:text-purple-300" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M19 4h-1a1 1 0 1 0 0 2v11a1 1 0 0 1-2 0V2a2 2 0 0 0-2-2H2a2 2 0 0 0-2 2v15a3 3 0 0 0 3 3h14a3 3 0 0 0 3-3V5a1 1 0 0 0-1-1ZM3 2h12v2H3V2Zm14 16H3a1 1 0 0 1-1-1V6h14v11a1 1 0 0 1-1 1Z"/>
                        </svg>
                    </div>
                </div>
                <div class="text-3xl font-bold text-gray-900 dark:text-white">{{ $stats['total_documents'] }}</div>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-2">File diunggah</p>
            </div>

            <!-- Card 2: Pending -->
            <div class="p-4 bg-white border border-gray-200 rounded-lg shadow-sm dark:border-gray-700 sm:p-6 dark:bg-gray-800">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Menunggu Verifikasi</h3>
                    <div class="p-2 bg-yellow-100 rounded-lg dark:bg-yellow-900">
                        <!-- Icon Clock/Hourglass -->
                        <svg class="w-6 h-6 text-yellow-600 dark:text-yellow-300" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M10 0a10 10 0 1 0 10 10A10.01 10.01 0 0 0 10 0Zm3.982 13.982a1 1 0 0 1-1.414 0l-3.274-3.274A1.012 1.012 0 0 1 9 10V6a1 1 0 0 1 2 0v3.586l2.982 2.982a1 1 0 0 1 0 1.414Z"/>
                        </svg>
                    </div>
                </div>
                <div class="text-3xl font-bold text-gray-900 dark:text-white">{{ $stats['pending'] }}</div>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-2">Dalam antrian review</p>
            </div>

            <!-- Card 3: Approved -->
            <div class="p-4 bg-white border border-gray-200 rounded-lg shadow-sm dark:border-gray-700 sm:p-6 dark:bg-gray-800">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Disetujui</h3>
                    <div class="p-2 bg-green-100 rounded-lg dark:bg-green-900">
                        <!-- Icon Check Circle -->
                        <svg class="w-6 h-6 text-green-600 dark:text-green-300" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5Zm3.707 8.207-4 4a1 1 0 0 1-1.414 0l-2-2a1 1 0 0 1 1.414-1.414L9 10.586l3.293-3.293a1 1 0 0 1 1.414 1.414Z"/>
                        </svg>
                    </div>
                </div>
                <div class="text-3xl font-bold text-gray-900 dark:text-white">{{ $stats['approved'] }}</div>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-2">Aktif untuk Chatbot</p>
            </div>

            <!-- Card 4: Storage Used -->
            <div class="p-4 bg-white border border-gray-200 rounded-lg shadow-sm dark:border-gray-700 sm:p-6 dark:bg-gray-800">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Penyimpanan</h3>
                    <div class="p-2 bg-blue-100 rounded-lg dark:bg-blue-900">
                        <!-- Icon Database/Server -->
                        <svg class="w-6 h-6 text-blue-600 dark:text-blue-300" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M2 4a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v2a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V4Zm0 5a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v2a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V9Zm0 5a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v2a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2v-2Z"/>
                        </svg>
                    </div>
                </div>
                <div class="text-3xl font-bold text-gray-900 dark:text-white">
                    {{ number_format($stats['total_size_bytes'] / 1048576, 2) }} <span class="text-lg font-normal text-gray-500">MB</span>
                </div>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-2">Total ukuran data</p>
            </div>
        </div>

    </div>
</x-app-layout>
