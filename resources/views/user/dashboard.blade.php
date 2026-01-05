<x-app-layout>
    <div class="grid grid-cols-1 gap-4 mb-4">

    <x-page-header 
        title="Halo, {{ Auth::user()->full_name }}! 👋" 
        subtitle="Selamat datang kembali di Symbiosis App.">
        <x-slot:actions>
            @if(strtolower($tierLabel) == 'green')
                <span class="bg-primary-50 text-primary-600 text-xs font-bold px-4 py-2 rounded-xl border border-primary-100 shadow-sm flex items-center gap-2 uppercase tracking-wider">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M2.166 4.9L9.03 1.393a2 2 0 011.94 0l6.864 3.507A2 2 0 0119 6.671V15c0 1.105-.895 2-2 2H3c-1.105 0-2-.895-2-2V6.671a2 2 0 011.166-1.771zM10 8a3 3 0 100-6 3 3 0 000 6z" clip-rule="evenodd"></path></svg>
                    Green Tier
                </span>
            @elseif(strtolower($tierLabel) == 'amber' || strtolower($tierLabel) == 'gold')
                <span class="bg-orange-50 text-orange-600 text-xs font-bold px-4 py-2 rounded-xl border border-orange-100 shadow-sm flex items-center gap-2 uppercase tracking-wider">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 2a1 1 0 01.707.293l6 6a1 1 0 01-1.414 1.414L10 4.414 4.707 9.707a1 1 0 01-1.414-1.414l6-6A1 1 0 0110 2z" clip-rule="evenodd"></path></svg>
                    Gold Tier
                </span>
            @else
                <span class="bg-gray-50 text-gray-600 text-xs font-bold px-4 py-2 rounded-xl border border-gray-100 shadow-sm flex items-center gap-2 uppercase tracking-wider">
                    Basic Tier
                </span>
            @endif
        </x-slot:actions>
    </x-page-header>

    <!-- STATUS PROFIL & PERUSAHAAN -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
        <x-content-card class="group cursor-pointer hover:border-primary-500 transition-all duration-300">
            <a href="{{ route('user.profile') }}" class="flex items-center">
                <div class="p-4 rounded-2xl mr-4 {{ $user->userProfile?->is_completed ? 'bg-primary-50 text-primary-600' : 'bg-red-50 text-red-600' }}">
                    <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 20 20"><path d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z"></path></svg>
                </div>
                <div class="flex-1">
                    <h4 class="text-sm font-bold text-gray-900 uppercase tracking-wider group-hover:text-primary-600 transition-colors">Profil Anda</h4>
                    <p class="text-xs {{ $user->userProfile?->is_completed ? 'text-primary-600 font-medium' : 'text-red-500 font-bold' }}">
                        {{ $user->userProfile?->is_completed ? '● Sudah Lengkap' : '● Segera Lengkap Data' }}
                    </p>
                </div>
                <svg class="w-4 h-4 text-gray-300 group-hover:text-primary-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
            </a>
        </x-content-card>

        <x-content-card class="group cursor-pointer hover:border-primary-500 transition-all duration-300">
            <a href="{{ route('user.company') }}" class="flex items-center">
                <div class="p-4 rounded-2xl mr-4 {{ $user->companyProfile?->is_completed ? 'bg-primary-50 text-primary-600' : 'bg-red-50 text-red-600' }}">
                    <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M4 4a2 2 0 012-2h8a2 2 0 012 2v12a1 1 0 110 2h-3a1 1 0 01-1-1v-2a1 1 0 00-1-1H9a1 1 0 00-1 1v2a1 1 0 01-1 1H4a1 1 0 110-2V4zm3 1h2v2H7V5zm2 4H7v2h2V9zm2-4h2v2h-2V5zm2 4h-2v2h2V9z" clip-rule="evenodd"></path></svg>
                </div>
                <div class="flex-1">
                    <h4 class="text-sm font-bold text-gray-900 uppercase tracking-wider group-hover:text-primary-600 transition-colors">Profil Perusahaan</h4>
                    <p class="text-xs {{ $user->companyProfile?->is_completed ? 'text-primary-600 font-medium' : 'text-red-500 font-bold' }}">
                        {{ $user->companyProfile?->is_completed ? '● Sudah Lengkap' : '● Segera Lengkap Data' }}
                    </p>
                </div>
                <svg class="w-4 h-4 text-gray-300 group-hover:text-primary-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
            </a>
        </x-content-card>
    </div>

    @if(!$isAllComplete)
    <x-content-card class="bg-red-50 border-red-100 flex items-center gap-4 mb-8 !py-4">
        <div class="bg-red-100 p-2 rounded-lg text-red-600">
            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path></svg>
        </div>
        <p class="text-xs text-red-800 font-medium">Beberapa akses menu masih terkunci. Silakan lengkapi profil di atas untuk membuka fitur Dokumen & Chatbot.</p>
    </x-content-card>
    @endif

    <!-- STATISTIK GRID -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
        <x-stat-card 
            label="Total Dokumen" 
            value="{{ $stats['total_documents'] }}" 
            icon='<svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20"><path d="M9 2a2 2 0 00-2 2v8a2 2 0 002 2h6a2 2 0 002-2V6l-4-4H9z"></path><path d="M5 6a2 2 0 00-2 2v8a2 2 0 002 2h4a2 2 0 002-2v-1a1 1 0 10-2 0v1H5V8h2a1 1 0 000-2H5z"></path></svg>'
            trend="Files diupload"
            iconColor="purple"
        />
        
        <x-stat-card 
            label="Pending" 
            value="{{ $stats['pending'] }}" 
            icon='<svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"></path></svg>'
            trend="Menunggu verifikasi"
            iconColor="orange"
        />

        <x-stat-card 
            label="Disetujui" 
            value="{{ $stats['approved'] }}" 
            icon='<svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>'
            trend="Aktif di Chatbot"
            iconColor="primary"
        />

        <x-stat-card 
            label="Penyimpanan" 
            value="{{ number_format($stats['total_size_bytes'] / 1048576, 1) }} MB" 
            icon='<svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20"><path d="M3 12v3c0 1.657 3.134 3 7 3s7-1.343 7-3v-3c0 1.657-3.134 3-7 3s-7-1.343-7-3z"></path><path d="M3 7v3c0 1.657 3.134 3 7 3s7-1.343 7-3V7c0 1.657-3.134 3-7 3S3 8.657 3 7z"></path><path d="M17 4c0 1.657-3.134 3-7 3S3 5.657 3 4s3.134-3 7-3 7 1.343 7 3z"></path></svg>'
            trend="Total data"
            iconColor="blue"
        />
    </div>

    </div>
</x-app-layout>
