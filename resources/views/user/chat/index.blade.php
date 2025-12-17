<x-app-layout>
    <div class="flex h-[calc(100vh-80px)] antialiased text-gray-800">
        <div class="flex flex-row h-full w-full overflow-x-hidden bg-white dark:bg-gray-800 rounded-lg shadow-lg border border-gray-200 dark:border-gray-700">

            <!-- SIDEBAR RIWAYAT CHAT -->
            <div class="flex flex-col py-6 pl-6 pr-4 w-full md:w-72 bg-white dark:bg-gray-800 flex-shrink-0 border-r border-gray-200 dark:border-gray-700">
                <div class="flex flex-row items-center justify-between h-12 w-full mb-4">
                    <div class="flex flex-col">
                        <span class="font-bold text-xl text-gray-800 dark:text-white">Riwayat Chat</span>
                        <span class="text-xs text-gray-500">Pilih percakapan untuk melanjutkan</span>
                    </div>
                    <form action="{{ route('user.chat.store') }}" method="POST">
                        @csrf
                        <button type="submit" class="flex items-center justify-center rounded-full text-white bg-primary-600 h-10 w-10 hover:bg-primary-700 transition shadow-md" title="Buat Chat Baru">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                        </button>
                    </form>
                </div>

                <div class="flex flex-col space-y-2 overflow-y-auto h-full pr-2 custom-scrollbar">
                    @forelse($sessions as $s)
                        <!-- Wrapper Item Chat -->
                        <div class="group flex flex-row items-center p-2 rounded-xl hover:bg-gray-50 dark:hover:bg-gray-700 cursor-pointer transition-all duration-200 mb-1 border border-transparent hover:border-gray-200 dark:hover:border-gray-600">

                            <!-- Link Utama -->
                            <a href="{{ route('user.chat.show', $s->id) }}" class="flex items-center flex-1 min-w-0">
                                <div class="flex items-center justify-center h-10 w-10 rounded-full bg-primary-100 text-primary-600 flex-shrink-0">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 01-2-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"></path></svg>
                                </div>
                                <div class="ml-3 flex-1 overflow-hidden">
                                    <div class="text-sm font-semibold text-gray-700 dark:text-gray-300 truncate group-hover:text-primary-700 dark:group-hover:text-white transition-colors">{{ $s->title }}</div>
                                    <div class="text-xs text-gray-400 truncate">{{ $s->created_at->diffForHumans() }}</div>
                                </div>
                            </a>

                            <!-- Tombol Delete (Hanya muncul saat hover) -->
                            <form action="{{ route('user.chat.delete', $s->id) }}" method="POST" class="hidden group-hover:block ml-1" onsubmit="return confirm('Hapus riwayat chat ini?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="p-1.5 text-gray-400 hover:text-red-500 hover:bg-red-50 rounded-md transition dark:hover:bg-red-900/20" title="Hapus Chat">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                </button>
                            </form>
                        </div>
                    @empty
                        <div class="text-center py-10">
                            <div class="bg-gray-100 dark:bg-gray-700 rounded-full w-16 h-16 flex items-center justify-center mx-auto mb-3">
                                <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path></svg>
                            </div>
                            <p class="text-sm text-gray-500 dark:text-gray-400">Belum ada riwayat percakapan.</p>
                        </div>
                    @endforelse
                </div>
            </div>

            <!-- Area Kosong (Desktop Only) - Tetap sama -->
            <div class="hidden md:flex flex-col flex-auto h-full p-6 bg-gray-50 dark:bg-gray-900 items-center justify-center rounded-r-lg border-l border-gray-200 dark:border-gray-700">
                <div class="text-center max-w-lg">
                    <div class="w-24 h-24 bg-gradient-to-br from-primary-100 to-primary-200 dark:from-gray-700 dark:to-gray-600 rounded-full flex items-center justify-center mx-auto mb-6 shadow-inner">
                        <svg class="w-12 h-12 text-primary-600 dark:text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"></path></svg>
                    </div>
                    <h2 class="text-3xl font-bold text-gray-800 dark:text-white mb-3">Symbiosis AI</h2>
                    <p class="text-gray-500 dark:text-gray-400 mb-8 leading-relaxed">
                        Asisten cerdas untuk menganalisis dokumen lingkungan Anda. Pilih percakapan di sebelah kiri atau mulai baru untuk mendapatkan wawasan instan.
                    </p>
                    <form action="{{ route('user.chat.store') }}" method="POST">
                        @csrf
                        <button type="submit" class="px-8 py-4 bg-primary-600 text-white font-semibold rounded-xl hover:bg-primary-700 transition-all shadow-lg hover:shadow-primary-500/30 transform hover:-translate-y-1 flex items-center gap-2 mx-auto">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                            Mulai Percakapan Baru
                        </button>
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
</x-app-layout>
