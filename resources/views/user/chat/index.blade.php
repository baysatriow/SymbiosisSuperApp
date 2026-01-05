<x-app-layout>
    <div class="flex h-[calc(100vh-64px)] overflow-hidden">
        <!-- SIDEBAR: CHAT HISTORY -->
        <div class="w-80 flex-shrink-0 bg-white border-r border-gray-100 flex flex-col">
            <div class="p-6 border-b border-gray-50 bg-gray-50/50">
                <div class="flex items-center justify-between mb-2">
                    <h2 class="text-sm font-bold text-gray-900 uppercase tracking-widest">Riwayat Chat</h2>
                    <form action="{{ route('user.chat.store') }}" method="POST">
                        @csrf
                        <button type="submit" class="w-8 h-8 rounded-lg bg-primary-600 text-white flex items-center justify-center hover:bg-primary-700 transition-all shadow-sm active:scale-95" title="Buat Chat Baru">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                        </button>
                    </form>
                </div>
                <p class="text-[10px] font-medium text-gray-400">Pilih percakapan untuk melanjutkan</p>
            </div>

            <div class="flex-1 overflow-y-auto p-4 space-y-2 custom-scrollbar">
                @forelse($sessions as $s)
                    <div class="group relative flex items-center gap-3 p-3 rounded-xl hover:bg-gray-50 border border-transparent hover:border-gray-100 transition-all cursor-pointer">
                        <a href="{{ route('user.chat.show', $s->id) }}" class="flex items-center gap-3 flex-1 min-w-0">
                            <div class="w-10 h-10 rounded-xl bg-primary-50 text-primary-600 flex items-center justify-center flex-shrink-0 group-hover:scale-105 transition-transform">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 01-2-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"></path></svg>
                            </div>
                            <div class="min-w-0">
                                <h4 class="text-xs font-bold text-gray-900 truncate group-hover:text-primary-700 transition-colors">{{ $s->title }}</h4>
                                <p class="text-[10px] text-gray-400 font-medium mt-0.5">{{ $s->created_at->diffForHumans() }}</p>
                            </div>
                        </a>
                        
                        <form action="{{ route('user.chat.delete', $s->id) }}" method="POST" class="opacity-0 group-hover:opacity-100 transition-opacity" onsubmit="return confirm('Hapus riwayat chat?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="p-1.5 text-gray-300 hover:text-red-500 hover:bg-red-50 rounded-lg transition-all" title="Hapus">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                            </button>
                        </form>
                    </div>
                @empty
                    <div class="py-12 px-6 text-center">
                        <div class="w-12 h-12 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-4 text-gray-200">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path></svg>
                        </div>
                        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Belum Ada Chat</p>
                    </div>
                @endforelse
            </div>
        </div>

        <!-- MAIN VIEW: EMPTY STATE -->
        <div class="flex-1 bg-gray-50/30 flex flex-col items-center justify-center p-8 relative overflow-hidden">
            <!-- Decorative background elements -->
            <div class="absolute top-[10%] left-[10%] w-64 h-64 bg-primary-100/30 rounded-full blur-3xl animate-pulse"></div>
            <div class="absolute bottom-[10%] right-[10%] w-96 h-96 bg-emerald-100/20 rounded-full blur-3xl"></div>

            <div class="max-w-xl w-full text-center relative z-10">
                <div class="inline-flex items-center gap-2 px-4 py-1.5 bg-white border border-gray-100 rounded-full shadow-sm mb-8 animate-bounce">
                    <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                    <span class="text-[10px] font-bold text-gray-600 uppercase tracking-widest">Symbiosis AI v2.5 Online</span>
                </div>

                <div class="w-24 h-24 bg-white rounded-[2rem] shadow-xl flex items-center justify-center mx-auto mb-8 border border-gray-50 relative group">
                    <div class="absolute inset-0 bg-primary-600 rounded-[2rem] rotate-6 group-hover:rotate-12 transition-transform opacity-10"></div>
                    <svg class="w-12 h-12 text-primary-600 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"></path></svg>
                </div>

                <h2 class="text-4xl font-extrabold text-gray-900 tracking-tight mb-4">Mulai Analisis <span class="bg-gradient-to-r from-primary-600 to-emerald-600 bg-clip-text text-transparent">Cerdas</span></h2>
                <p class="text-sm text-gray-500 font-medium leading-relaxed mb-10 px-8">
                    Tanyakan apa saja tentang dokumen ESG Anda, bandingkan regulasi, atau generate wawasan keberlanjutan secara instan menggunakan kekuatan AI tercanggih.
                </p>

                <form action="{{ route('user.chat.store') }}" method="POST">
                    @csrf
                    <button type="submit" class="group relative px-8 py-4 bg-primary-600 text-white font-bold rounded-2xl hover:bg-primary-700 transition-all shadow-xl shadow-primary-200 hover:-translate-y-1 active:scale-95 flex items-center gap-4 mx-auto uppercase tracking-widest text-[11px]">
                        <svg class="w-5 h-5 group-hover:rotate-12 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 01-2-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"></path></svg>
                        Mulai Chatting Sekarang
                        <span class="absolute -right-2 -top-2 w-4 h-4 bg-emerald-400 rounded-full border-2 border-white"></span>
                    </button>
                </form>

                <div class="mt-20 grid grid-cols-3 gap-6">
                    <div class="p-4 bg-white/60 backdrop-blur-sm rounded-2xl border border-white shadow-sm hover:shadow-md transition-all group">
                        <div class="w-8 h-8 rounded-lg bg-orange-50 text-orange-500 flex items-center justify-center mb-3 group-hover:scale-110 transition-transform">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                        </div>
                        <h5 class="text-[10px] font-bold text-gray-900 uppercase tracking-widest mb-1">Akurat</h5>
                        <p class="text-[9px] text-gray-400 leading-relaxed">Berbasis data dokumen teknis Anda.</p>
                    </div>
                    <div class="p-4 bg-white/60 backdrop-blur-sm rounded-2xl border border-white shadow-sm hover:shadow-md transition-all group">
                        <div class="w-8 h-8 rounded-lg bg-blue-50 text-blue-500 flex items-center justify-center mb-3 group-hover:scale-110 transition-transform">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                        </div>
                        <h5 class="text-[10px] font-bold text-gray-900 uppercase tracking-widest mb-1">Cepat</h5>
                        <p class="text-[9px] text-gray-400 leading-relaxed">Respon dalam hitungan detik.</p>
                    </div>
                    <div class="p-4 bg-white/60 backdrop-blur-sm rounded-2xl border border-white shadow-sm hover:shadow-md transition-all group">
                        <div class="w-8 h-8 rounded-lg bg-purple-50 text-purple-500 flex items-center justify-center mb-3 group-hover:scale-110 transition-transform">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4"></path></svg>
                        </div>
                        <h5 class="text-[10px] font-bold text-gray-900 uppercase tracking-widest mb-1">Konteks</h5>
                        <p class="text-[9px] text-gray-400 leading-relaxed">Mengingat detail percakapan Anda.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        .custom-scrollbar::-webkit-scrollbar { width: 4px; }
        .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background-color: #f1f5f9; border-radius: 20px; }
        .custom-scrollbar:hover::-webkit-scrollbar-thumb { background-color: #e2e8f0; }
    </style>
</x-app-layout>
