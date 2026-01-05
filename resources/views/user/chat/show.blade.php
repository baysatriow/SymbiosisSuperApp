<x-app-layout>
    <div class="flex h-[calc(100vh-64px)] overflow-hidden">
        <!-- SIDEBAR: CHAT HISTORY -->
        <div class="hidden md:flex w-80 flex-shrink-0 bg-white border-r border-gray-100 flex-col">
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
                <a href="{{ route('user.chat.index') }}" class="text-[10px] font-bold text-gray-400 hover:text-primary-600 flex items-center gap-1 mt-1 transition-colors group">
                    <svg class="w-3 h-3 group-hover:-translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                    KEMBALI KE DAFTAR
                </a>
            </div>

            <div class="flex-1 overflow-y-auto p-4 space-y-2 custom-scrollbar">
                @foreach($sessions as $s)
                    <div class="group relative flex items-center gap-3 p-3 rounded-xl transition-all cursor-pointer border
                        {{ $session->id == $s->id ? 'bg-primary-50 border-primary-100' : 'hover:bg-gray-50 border-transparent hover:border-gray-100' }}">
                        <a href="{{ route('user.chat.show', $s->id) }}" class="flex items-center gap-3 flex-1 min-w-0">
                            <div class="w-10 h-10 rounded-xl flex items-center justify-center flex-shrink-0 transition-transform group-hover:scale-105
                                {{ $session->id == $s->id ? 'bg-primary-600 text-white shadow-md shadow-primary-200' : 'bg-gray-100 text-gray-400' }}">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 01-2-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"></path></svg>
                            </div>
                            <div class="min-w-0">
                                <h4 class="text-xs font-bold truncate transition-colors {{ $session->id == $s->id ? 'text-primary-900' : 'text-gray-900 group-hover:text-primary-700' }}">{{ $s->title }}</h4>
                                <p class="text-[10px] text-gray-400 font-medium mt-0.5">{{ $s->created_at->diffForHumans() }}</p>
                            </div>
                        </a>
                        
                        <form action="{{ route('user.chat.delete', $s->id) }}" method="POST" class="{{ $session->id == $s->id ? 'opacity-100' : 'opacity-0 group-hover:opacity-100' }} transition-opacity" onsubmit="return confirm('Hapus riwayat chat?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="p-1.5 text-gray-300 hover:text-red-500 hover:bg-red-50 rounded-lg transition-all" title="Hapus">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                            </button>
                        </form>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- MAIN CHAT AREA -->
        <div class="flex-1 bg-gray-50/30 flex flex-col relative overflow-hidden">
            <!-- Header -->
            <div class="px-6 py-4 bg-white/80 backdrop-blur-md border-b border-gray-100 flex items-center justify-between sticky top-0 z-20">
                <div class="flex items-center gap-4">
                    <a href="{{ route('user.chat.index') }}" class="md:hidden p-2 -ml-2 text-gray-400 hover:bg-gray-100 rounded-xl">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
                    </a>

                    <div class="relative">
                        <div class="w-12 h-12 rounded-2xl bg-gradient-to-br from-primary-500 to-primary-700 flex items-center justify-center text-white font-black text-xl shadow-lg shadow-primary-200">AI</div>
                        <span class="absolute -bottom-1 -right-1 w-4 h-4 bg-emerald-500 border-2 border-white rounded-full"></span>
                    </div>
                    <div>
                        <h3 class="text-sm font-bold text-gray-900 uppercase tracking-widest leading-none">Symbiosis AI</h3>
                        <div class="flex items-center gap-2 mt-1.5">
                            <span class="flex items-center gap-1.5 text-[10px] font-bold text-emerald-600 bg-emerald-50 px-2 py-0.5 rounded-full border border-emerald-100">
                                <span class="w-1.5 h-1.5 bg-emerald-500 rounded-full animate-pulse"></span>
                                ONLINE
                            </span>
                            <span class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">GEMINI 2.5 FLASH</span>
                        </div>
                    </div>
                </div>

                <div class="flex items-center gap-3">
                    <button data-modal-target="docModal" data-modal-toggle="docModal" class="flex items-center gap-3 px-4 py-2 bg-white border border-gray-100 rounded-xl hover:bg-gray-50 shadow-sm transition-all group">
                        <div class="w-6 h-6 rounded-lg bg-primary-50 text-primary-600 flex items-center justify-center group-hover:scale-110 transition-transform">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                        </div>
                        <div class="text-left hidden sm:block">
                            <p class="text-[9px] font-bold text-gray-400 uppercase tracking-widest leading-none mb-0.5">Analisis Data</p>
                            <p class="text-[11px] font-bold text-gray-900 leading-none">{{ $session->documents->count() }} Dokumen Terpilih</p>
                        </div>
                    </button>

                    <div class="w-px h-8 bg-gray-100 mx-1 hidden sm:block"></div>

                    <form action="{{ route('user.chat.clear', $session->id) }}" method="POST" onsubmit="return confirm('Bersihkan riwayat?')">
                        @csrf
                        <button type="submit" class="p-2.5 text-gray-300 hover:text-red-500 hover:bg-red-50 rounded-xl transition-all" title="Bersihkan Chat">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                        </button>
                    </form>
                </div>
            </div>

            <!-- Messages List -->
            <div class="flex-1 overflow-y-auto p-6 md:p-8 space-y-8 custom-scrollbar" id="messages-container">
                <div class="flex justify-center mb-4">
                    <span class="px-4 py-1 bg-white border border-gray-50 rounded-full text-[9px] font-bold text-gray-400 uppercase tracking-widest shadow-sm">Percakapan dimulai {{ $session->created_at->format('d M Y, H:i') }}</span>
                </div>

                @if($session->messages->isEmpty())
                    <div class="flex gap-4 max-w-2xl animate-in fade-in slide-in-from-bottom-4 duration-700">
                        <div class="w-10 h-10 rounded-xl bg-primary-600 text-white flex items-center justify-center flex-shrink-0 font-bold text-sm shadow-lg shadow-primary-200">AI</div>
                        <div class="space-y-4">
                            <div class="bg-white p-5 rounded-2xl rounded-tl-none shadow-sm border border-white text-sm text-gray-800 leading-relaxed font-medium">
                                Halo <strong>{{ Auth::user()->full_name }}</strong>! Saya siap membantu Anda menganalisis dokumen lingkungan dan memberikan wawasan khusus terkait regulasi ESG. Apa yang bisa saya bantu hari ini?
                            </div>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-2">
                                <button onclick="document.getElementById('chat-input').value = 'Ringkas dokumen yang saya pilih'; document.getElementById('chat-form').submit();" class="text-left p-3 rounded-xl border border-gray-100 bg-white/50 hover:bg-white hover:border-primary-200 text-[11px] text-gray-500 font-bold uppercase tracking-wider transition-all hover:shadow-sm">&rarr; Ringkas Dokumen</button>
                                <button onclick="document.getElementById('chat-input').value = 'Bandingkan regulasi dalam dokumen'; document.getElementById('chat-form').submit();" class="text-left p-3 rounded-xl border border-gray-100 bg-white/50 hover:bg-white hover:border-primary-200 text-[11px] text-gray-500 font-bold uppercase tracking-wider transition-all hover:shadow-sm">&rarr; Bandingkan Regulasi</button>
                            </div>
                        </div>
                    </div>
                @endif

                @foreach($session->messages as $msg)
                    @if($msg->role == 'user')
                        <div class="flex justify-end animate-in fade-in slide-in-from-right-4 duration-300">
                            <div class="flex gap-4 max-w-[85%] flex-row-reverse items-start">
                                <div class="w-10 h-10 rounded-xl bg-gray-200 flex items-center justify-center text-gray-500 font-bold text-sm flex-shrink-0 uppercase">{{ substr(Auth::user()->full_name, 0, 1) }}</div>
                                <div class="bg-primary-600 p-5 rounded-2xl rounded-tr-none shadow-xl shadow-primary-100 text-sm text-white leading-relaxed font-medium">
                                    <p class="whitespace-pre-wrap">{{ $msg->content }}</p>
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="flex justify-start animate-in fade-in slide-in-from-left-4 duration-300">
                            <div class="flex gap-4 max-w-[85%] items-start">
                                <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-primary-600 to-emerald-600 text-white flex items-center justify-center text-sm font-bold flex-shrink-0 shadow-lg shadow-primary-200">AI</div>
                                <div class="bg-white p-5 rounded-2xl rounded-tl-none shadow-sm border border-white text-sm text-gray-800 leading-relaxed font-medium prose dark:prose-invert prose-p:leading-relaxed prose-pre:bg-gray-900 prose-pre:text-emerald-400 prose-strong:text-primary-700 max-w-none">
                                    {!! Str::markdown($msg->content) !!}
                                </div>
                            </div>
                        </div>
                    @endif
                @endforeach

                <div id="loading-bubble" class="hidden flex justify-start">
                    <div class="flex gap-4 items-center">
                        <div class="w-10 h-10 rounded-xl bg-primary-600 text-white flex items-center justify-center text-sm font-bold animate-pulse shadow-lg shadow-primary-200">AI</div>
                        <div class="bg-white px-5 py-4 rounded-2xl rounded-tl-none shadow-sm border border-white">
                            <div class="flex space-x-1.5 h-4 items-center">
                                <div class="w-1.5 h-1.5 bg-gray-300 rounded-full animate-bounce" style="animation-delay: 0s"></div>
                                <div class="w-1.5 h-1.5 bg-gray-300 rounded-full animate-bounce" style="animation-delay: 0.2s"></div>
                                <div class="w-1.5 h-1.5 bg-gray-300 rounded-full animate-bounce" style="animation-delay: 0.4s"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Input Area -->
            <div class="p-6 bg-white border-t border-gray-100">
                <form action="{{ route('user.chat.send', $session->id) }}" method="POST" class="max-w-4xl mx-auto relative group" id="chat-form">
                    @csrf
                    <div class="relative flex items-center">
                        <input type="text" name="message" id="chat-input" class="w-full pl-6 pr-16 py-5 bg-gray-50 border-transparent focus:border-primary-500 focus:bg-white focus:ring-4 focus:ring-primary-50/50 rounded-2xl transition-all text-sm font-medium text-gray-800 shadow-inner group-hover:bg-gray-100 group-hover:border-gray-100" placeholder="Ketik pesan atau tanyakan sesuatu tentang dokumen Anda..." required autocomplete="off">
                        <button type="submit" id="send-btn" class="absolute right-3 p-3 bg-primary-600 text-white rounded-xl hover:bg-primary-700 transition shadow-lg shadow-primary-200 active:scale-95 disabled:opacity-50">
                            <svg class="w-5 h-5 group-focus-within:rotate-12 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path></svg>
                        </button>
                    </div>
                    <div class="text-center mt-4">
                        <p class="text-[10px] text-gray-400 font-bold uppercase tracking-widest">Gunakan kekuatan Gemini AI untuk analisis mendalam</p>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- DOCUMENT MODAL -->
    <div id="docModal" tabindex="-1" class="hidden fixed inset-0 z-50 overflow-y-auto overflow-x-hidden flex items-center justify-center p-4 backdrop-blur-sm bg-gray-900/20">
        <div class="relative w-full max-w-2xl">
            <div class="bg-white rounded-3xl shadow-2xl flex flex-col max-h-[85vh] overflow-hidden border border-white">
                <div class="px-8 py-6 bg-gray-50/80 border-b border-gray-100 flex items-center justify-between">
                    <div>
                        <h3 class="text-xl font-black text-gray-900 tracking-tight uppercase">Pilih Sumber Data</h3>
                        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mt-1">AI akan membaca dokumen yang Anda pilih</p>
                    </div>
                    <button data-modal-hide="docModal" class="p-2 text-gray-400 hover:bg-white rounded-xl transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>

                <form action="{{ route('user.chat.documents', $session->id) }}" method="POST" class="flex flex-col flex-1 overflow-hidden">
                    @csrf
                    <div class="flex-1 overflow-y-auto p-8 space-y-4 custom-scrollbar">
                        @forelse($allDocuments as $doc)
                            <label class="block group cursor-pointer">
                                <input type="checkbox" name="document_ids[]" value="{{ $doc->id }}" class="sr-only peer" {{ $session->documents->contains($doc->id) ? 'checked' : '' }}>
                                <div class="p-4 rounded-2xl border border-gray-100 bg-gray-50 transition-all peer-checked:bg-primary-50 peer-checked:border-primary-200 peer-checked:shadow-inner flex items-center gap-4 group-hover:scale-[1.01]">
                                    <div class="w-12 h-12 rounded-xl bg-white border border-gray-100 flex items-center justify-center flex-shrink-0 shadow-sm text-gray-400 peer-checked:text-primary-600 peer-checked:border-primary-100">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                    </div>
                                    <div class="min-w-0">
                                        <p class="text-[13px] font-bold text-gray-900 truncate">{{ $doc->original_filename }}</p>
                                        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mt-1">{{ $doc->subfield?->name }}</p>
                                    </div>
                                    <div class="ml-auto w-6 h-6 rounded-full border-2 border-gray-200 flex items-center justify-center peer-checked:bg-primary-600 peer-checked:border-primary-600 transition-colors">
                                        <svg class="w-3 h-3 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                                    </div>
                                </div>
                            </label>
                        @empty
                            <div class="py-16 text-center">
                                <div class="w-16 h-16 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-4 text-gray-200">
                                   <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                </div>
                                <h4 class="text-sm font-bold text-gray-900 uppercase tracking-wider mb-2">Repository Kosong</h4>
                                <p class="text-xs text-gray-400 px-12">Belum ada dokumen yang disetujui untuk dianalisis.</p>
                            </div>
                        @endforelse
                    </div>

                    <div class="p-8 bg-gray-50/50 border-t border-gray-100 flex items-center justify-between">
                        <span class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Pilih hingga 10 dokumen</span>
                        <div class="flex gap-4">
                            <button data-modal-hide="docModal" type="button" class="px-6 py-3 text-[11px] font-bold text-gray-500 uppercase tracking-widest hover:text-gray-900 transition-colors">Batal</button>
                            <button type="submit" class="px-8 py-3 bg-primary-600 text-white text-[11px] font-bold uppercase tracking-widest rounded-xl hover:bg-primary-700 transition-all shadow-xl shadow-primary-200">Simpan Pilihan</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const container = document.getElementById('messages-container');
            const chatForm = document.getElementById('chat-form');
            const chatInput = document.getElementById('chat-input');
            const loadingBubble = document.getElementById('loading-bubble');
            const sendBtn = document.getElementById('send-btn');
 
            if(container) container.scrollTop = container.scrollHeight;
 
            if(chatForm) {
                chatForm.addEventListener('submit', function(e) {
                    const message = chatInput.value.trim();
                    if (!message) {
                        e.preventDefault();
                        return;
                    }
 
                    const userBubble = `
                        <div class="flex justify-end animate-in fade-in slide-in-from-right-4 duration-300 mb-8">
                            <div class="flex gap-4 max-w-[85%] flex-row-reverse items-start">
                                <div class="w-10 h-10 rounded-xl bg-gray-200 flex items-center justify-center text-gray-500 font-bold text-sm flex-shrink-0 uppercase">{{ substr(Auth::user()->full_name, 0, 1) }}</div>
                                <div class="bg-primary-600 p-5 rounded-2xl rounded-tr-none shadow-xl shadow-primary-100 text-sm text-white leading-relaxed font-medium">
                                    <p class="whitespace-pre-wrap">${message}</p>
                                </div>
                            </div>
                        </div>
                    `;
 
                    loadingBubble.insertAdjacentHTML('beforebegin', userBubble);
                    loadingBubble.classList.remove('hidden');
                    container.scrollTop = container.scrollHeight;
                    chatInput.readOnly = true;
                    sendBtn.disabled = true;
                });
            }
        });
    </script>

    <style>
        .custom-scrollbar::-webkit-scrollbar { width: 4px; }
        .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background-color: #f1f5f9; border-radius: 20px; }
        .custom-scrollbar:hover::-webkit-scrollbar-thumb { background-color: #e2e8f0; }
        
        /* Typography Scale fixes */
        .prose { font-size: 0.875rem; line-height: 1.625; color: #374151; }
        .prose strong { color: #111827; font-weight: 700; }
        .prose h1, .prose h2, .prose h3 { color: #111827; font-weight: 800; margin-top: 1.5em; margin-bottom: 0.5em; text-transform: uppercase; letter-spacing: 0.05em; font-size: 0.75rem; }
    </style>
</x-app-layout>
