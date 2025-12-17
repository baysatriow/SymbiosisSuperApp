<x-app-layout>
    <div class="flex h-[calc(100vh-80px)] antialiased text-gray-800">
        <div class="flex flex-row h-full w-full overflow-x-hidden bg-white dark:bg-gray-800 rounded-lg shadow-lg border border-gray-200 dark:border-gray-700">

            <!-- SIDEBAR RIWAYAT CHAT -->
            <div class="hidden md:flex flex-col py-6 pl-6 pr-4 w-72 bg-white dark:bg-gray-800 flex-shrink-0 border-r border-gray-200 dark:border-gray-700">
                <div class="flex flex-row items-center justify-between h-12 w-full mb-4">
                    <div class="flex flex-col">
                        <span class="font-bold text-xl text-gray-800 dark:text-white">Riwayat Chat</span>
                        <a href="{{ route('user.chat.index') }}" class="text-xs text-gray-500 hover:text-primary-600 flex items-center gap-1 mt-1">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                            Ke Daftar Utama
                        </a>
                    </div>
                    <form action="{{ route('user.chat.store') }}" method="POST">
                        @csrf
                        <button type="submit" class="flex items-center justify-center rounded-full text-white bg-primary-600 h-10 w-10 hover:bg-primary-700 transition shadow-md" title="Buat Chat Baru">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                        </button>
                    </form>
                </div>

                <div class="flex flex-col space-y-2 overflow-y-auto h-full pr-2 custom-scrollbar">
                    @foreach($sessions as $s)
                        <!-- Wrapper Item Chat -->
                        <div class="group flex flex-row items-center p-2 rounded-xl transition-all duration-200 mb-1 cursor-pointer
                           {{ $session->id == $s->id
                                ? 'bg-primary-50 border-l-4 border-primary-600 shadow-sm dark:bg-gray-700 dark:border-primary-500'
                                : 'hover:bg-gray-50 border-l-4 border-transparent dark:hover:bg-gray-700'
                           }}">

                            <!-- Link Utama (Klik untuk buka chat) -->
                            <a href="{{ route('user.chat.show', $s->id) }}" class="flex items-center flex-1 min-w-0">
                                <div class="flex items-center justify-center h-9 w-9 rounded-full flex-shrink-0
                                    {{ $session->id == $s->id ? 'bg-primary-100 text-primary-600' : 'bg-gray-100 text-gray-500 dark:bg-gray-600 dark:text-gray-300' }}">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 01-2-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"></path></svg>
                                </div>
                                <div class="ml-3 flex-1 overflow-hidden">
                                    <div class="text-sm font-semibold truncate {{ $session->id == $s->id ? 'text-primary-800 dark:text-primary-300' : 'text-gray-700 dark:text-gray-300' }}">
                                        {{ $s->title }}
                                    </div>
                                    <div class="text-xs text-gray-400 truncate">
                                        {{ $s->created_at->diffForHumans() }}
                                    </div>
                                </div>
                            </a>

                            <!-- Tombol Delete (Muncul saat Hover atau Aktif) -->
                            <form action="{{ route('user.chat.delete', $s->id) }}" method="POST"
                                  class="{{ $session->id == $s->id ? 'block' : 'hidden group-hover:block' }} ml-1"
                                  onsubmit="return confirm('Apakah Anda yakin ingin menghapus sesi chat ini?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="p-1.5 text-gray-400 hover:text-red-500 hover:bg-red-50 rounded-md transition dark:hover:bg-red-900/20" title="Hapus Chat">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                </button>
                            </form>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- AREA CHAT UTAMA (Kode di bawah ini sama, hanya menyertakan pembuka untuk konteks file) -->
            <div class="flex flex-col flex-auto h-full bg-gray-50 dark:bg-gray-900 relative">

                <!-- Chat Header -->
                <div class="flex flex-row justify-between items-center px-6 py-4 bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700 shadow-sm z-10">
                    <!-- ... (Header Content Tetap Sama) ... -->
                    <div class="flex items-center">
                        <a href="{{ route('user.chat.index') }}" class="md:hidden mr-3 text-gray-500 hover:text-primary-600">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
                        </a>

                        <div class="flex items-center justify-center h-10 w-10 rounded-full bg-gradient-to-br from-primary-500 to-primary-700 text-white font-bold shadow-sm">
                            AI
                        </div>
                        <div class="ml-3">
                            <div class="font-bold text-gray-800 dark:text-white text-lg leading-tight">Symbiosis AI</div>
                            <div class="text-xs text-green-500 font-medium flex items-center gap-1">
                                <span class="w-2 h-2 bg-green-500 rounded-full animate-pulse"></span>
                                Online • Gemini 2.5 Flash
                            </div>
                        </div>
                    </div>

                    <div class="flex gap-2">
                        <!-- Tombol Pilih Dokumen -->
                        <button data-modal-target="docModal" data-modal-toggle="docModal" class="flex items-center gap-2 px-3 py-2 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 text-sm font-medium text-gray-700 shadow-sm transition dark:bg-gray-700 dark:border-gray-600 dark:text-gray-200 dark:hover:bg-gray-600">
                            <svg class="w-4 h-4 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                            <span class="hidden sm:inline">Pilih Dokumen:</span>
                            <span class="bg-primary-100 text-primary-700 px-2 py-0.5 rounded-full text-xs font-bold">{{ $session->documents->count() }}</span>
                        </button>

                        <!-- Tombol Hapus Chat (Header) -->
                        <form action="{{ route('user.chat.clear', $session->id) }}" method="POST" onsubmit="return confirm('Hapus semua pesan dalam sesi ini?');">
                            @csrf
                            <button type="submit" class="p-2.5 text-gray-400 hover:text-red-500 hover:bg-red-50 rounded-lg transition dark:hover:bg-red-900/30" title="Bersihkan Chat">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                            </button>
                        </form>
                    </div>
                </div>

                <!-- Messages Container -->
                <div class="flex-1 overflow-y-auto p-4 md:p-6 space-y-6 scroll-smooth" id="messages-container">
                    <div class="flex justify-center">
                        <div class="bg-gray-100 dark:bg-gray-700 text-gray-500 dark:text-gray-300 text-xs px-4 py-2 rounded-full shadow-sm border border-gray-200 dark:border-gray-600">
                            Sesi dimulai {{ $session->created_at->format('d M Y, H:i') }}
                        </div>
                    </div>

                    <!-- Welcome Message -->
                    @if($session->messages->isEmpty())
                        <div class="flex justify-start w-full">
                            <div class="flex items-end gap-2 max-w-[85%] md:max-w-[75%]">
                                <div class="w-8 h-8 rounded-full bg-gradient-to-br from-primary-500 to-primary-700 flex-shrink-0 flex items-center justify-center text-white text-xs font-bold">AI</div>
                                <div class="bg-white dark:bg-gray-800 p-4 rounded-2xl rounded-bl-none shadow-sm border border-gray-200 dark:border-gray-700 text-gray-800 dark:text-gray-200 text-sm leading-relaxed">
                                    Halo! Saya siap membantu menganalisis dokumen Anda.
                                </div>
                            </div>
                        </div>
                    @endif

                    @foreach($session->messages as $msg)
                        @if($msg->role == 'user')
                            <div class="flex justify-end w-full">
                                <div class="flex items-end gap-2 max-w-[85%] md:max-w-[75%] flex-row-reverse">
                                    <div class="w-8 h-8 rounded-full bg-gray-300 flex-shrink-0 flex items-center justify-center text-gray-600 text-xs font-bold uppercase">
                                        {{ substr(Auth::user()->full_name, 0, 1) }}
                                    </div>
                                    <div class="bg-primary-600 p-4 rounded-2xl rounded-br-none shadow-md text-white text-sm leading-relaxed">
                                        <p class="whitespace-pre-wrap">{{ $msg->content }}</p>
                                    </div>
                                </div>
                            </div>
                        @else
                            <div class="flex justify-start w-full">
                                <div class="flex items-end gap-2 max-w-[85%] md:max-w-[75%]">
                                    <div class="w-8 h-8 rounded-full bg-gradient-to-br from-primary-500 to-primary-700 flex-shrink-0 flex items-center justify-center text-white text-xs font-bold">AI</div>
                                    <div class="bg-white dark:bg-gray-800 p-4 rounded-2xl rounded-bl-none shadow-sm border border-gray-200 dark:border-gray-700 text-gray-800 dark:text-gray-200 text-sm leading-relaxed prose dark:prose-invert max-w-none">
                                        {!! Str::markdown($msg->content) !!}
                                    </div>
                                </div>
                            </div>
                        @endif
                    @endforeach

                    <!-- Loading Indicator (Hidden by default) -->
                    <div id="loading-bubble" class="flex justify-start w-full hidden">
                        <div class="flex items-end gap-2 max-w-[85%] md:max-w-[75%]">
                            <div class="w-8 h-8 rounded-full bg-gradient-to-br from-primary-500 to-primary-700 flex-shrink-0 flex items-center justify-center text-white text-xs font-bold animate-pulse">AI</div>
                            <div class="bg-white dark:bg-gray-800 p-4 rounded-2xl rounded-bl-none shadow-sm border border-gray-200 dark:border-gray-700 text-gray-800 dark:text-gray-200 text-sm">
                                <div class="flex space-x-2 items-center h-5">
                                    <div class="w-2 h-2 bg-gray-400 rounded-full animate-bounce" style="animation-delay: 0s"></div>
                                    <div class="w-2 h-2 bg-gray-400 rounded-full animate-bounce" style="animation-delay: 0.2s"></div>
                                    <div class="w-2 h-2 bg-gray-400 rounded-full animate-bounce" style="animation-delay: 0.4s"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Input Area (Dengan Fix JS Sebelumnya) -->
                <div class="bg-white dark:bg-gray-800 p-4 border-t border-gray-200 dark:border-gray-700">
                    <form action="{{ route('user.chat.send', $session->id) }}" method="POST" class="relative max-w-4xl mx-auto" id="chat-form">
                        @csrf
                        <div class="relative">
                            <input type="text" name="message" id="chat-input" class="w-full pl-5 pr-14 py-4 bg-gray-100 dark:bg-gray-700 border-transparent focus:border-primary-500 focus:bg-white dark:focus:bg-gray-600 focus:ring-0 rounded-xl transition-all text-gray-800 dark:text-white shadow-inner disabled:opacity-50 disabled:cursor-not-allowed" placeholder="Ketik pertanyaan Anda di sini..." required autocomplete="off" autofocus>
                            <button type="submit" id="send-btn" class="absolute right-2 top-2 p-2 bg-primary-600 text-white rounded-lg hover:bg-primary-700 transition shadow-md group disabled:opacity-50 disabled:cursor-not-allowed">
                                <svg class="w-5 h-5 transform group-hover:rotate-45 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path></svg>
                            </button>
                        </div>
                        <div class="text-center mt-2">
                            <p class="text-xs text-gray-400">AI dapat membuat kesalahan. Periksa informasi penting.</p>
                        </div>
                    </form>
                </div>

            </div>
        </div>
    </div>

    <!-- (MODAL PILIH DOKUMEN - Tetap sama) -->
    <div id="docModal" tabindex="-1" aria-hidden="true" class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
        <div class="relative p-4 w-full max-w-2xl max-h-full">
            <div class="relative bg-white rounded-lg shadow-xl dark:bg-gray-700 flex flex-col max-h-[90vh]">
                <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t dark:border-gray-600 bg-gray-50 dark:bg-gray-800">
                    <div>
                        <h3 class="text-xl font-bold text-gray-900 dark:text-white flex items-center gap-2">
                            <svg class="w-6 h-6 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"></path></svg>
                            Pilih dari Repository
                        </h3>
                        <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Centang dokumen yang ingin dianalisis oleh AI.</p>
                    </div>
                    <button type="button" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white" data-modal-hide="docModal">
                        <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14"><path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/></svg>
                        <span class="sr-only">Close modal</span>
                    </button>
                </div>
                <form action="{{ route('user.chat.documents', $session->id) }}" method="POST" class="flex flex-col flex-1 overflow-hidden">
                    @csrf
                    <div class="p-4 md:p-5 space-y-3 overflow-y-auto flex-1 bg-gray-50 dark:bg-gray-900 custom-scrollbar">
                        @if($allDocuments->isEmpty())
                            <div class="flex flex-col items-center justify-center py-10 text-center border-2 border-dashed border-gray-300 rounded-xl bg-white dark:bg-gray-800 dark:border-gray-600">
                                <div class="p-3 bg-gray-100 rounded-full dark:bg-gray-700 mb-3">
                                    <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                </div>
                                <h4 class="text-lg font-semibold text-gray-900 dark:text-white">Repository Kosong</h4>
                                <p class="text-gray-500 dark:text-gray-400 text-sm mb-4">Anda belum memiliki dokumen yang disetujui.</p>
                                <a href="{{ route('user.documents') }}" class="text-white bg-primary-600 hover:bg-primary-700 focus:ring-4 focus:ring-primary-300 font-medium rounded-lg text-sm px-5 py-2.5 dark:bg-primary-600 dark:hover:bg-primary-700 focus:outline-none dark:focus:ring-primary-800">
                                    Pergi ke Halaman Dokumen
                                </a>
                            </div>
                        @else
                            @foreach($allDocuments as $doc)
                                <label class="flex items-start p-4 bg-white dark:bg-gray-800 rounded-xl hover:bg-primary-50 dark:hover:bg-primary-900/20 cursor-pointer border border-gray-200 dark:border-gray-600 shadow-sm transition-all group">
                                    <div class="flex items-center h-5 mt-1">
                                        <input type="checkbox" name="document_ids[]" value="{{ $doc->id }}"
                                            class="w-5 h-5 text-primary-600 bg-gray-100 border-gray-300 rounded focus:ring-primary-500 focus:ring-2 dark:bg-gray-700 dark:border-gray-600"
                                            {{ $session->documents->contains($doc->id) ? 'checked' : '' }}>
                                    </div>
                                    <div class="ml-3 flex-1">
                                        <div class="flex justify-between items-start">
                                            <div class="text-sm font-semibold text-gray-900 dark:text-gray-200 group-hover:text-primary-700 dark:group-hover:text-primary-400 break-all">
                                                {{ $doc->original_filename }}
                                            </div>
                                            <span class="bg-green-100 text-green-800 text-xs font-medium px-2 py-0.5 rounded dark:bg-green-900 dark:text-green-300 whitespace-nowrap ml-2">
                                                Tersimpan
                                            </span>
                                        </div>
                                        <div class="flex items-center gap-3 mt-1 text-xs text-gray-500 dark:text-gray-400">
                                            <span class="flex items-center gap-1">
                                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path></svg>
                                                {{ $doc->subfield->name }}
                                            </span>
                                            <span class="flex items-center gap-1">
                                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                                {{ $doc->created_at->format('d M Y') }}
                                            </span>
                                        </div>
                                    </div>
                                </label>
                            @endforeach
                        @endif
                    </div>
                    <div class="flex items-center justify-between p-4 md:p-5 border-t border-gray-200 rounded-b dark:border-gray-600 bg-white dark:bg-gray-800">
                        <span class="text-xs text-gray-500 dark:text-gray-400">Maksimal 10 dokumen</span>
                        <div class="flex gap-3">
                            <button data-modal-hide="docModal" type="button" class="py-2.5 px-5 text-sm font-medium text-gray-900 focus:outline-none bg-white rounded-lg border border-gray-200 hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-4 focus:ring-gray-100 dark:focus:ring-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:text-white dark:hover:bg-gray-700">Batal</button>
                            <button type="submit" class="text-white bg-primary-700 hover:bg-primary-800 focus:ring-4 focus:outline-none focus:ring-primary-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center shadow-md transition disabled:opacity-50 disabled:cursor-not-allowed" {{ $allDocuments->isEmpty() ? 'disabled' : '' }}>
                                Gunakan Dokumen
                            </button>
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

            // Scroll to bottom on load
            if(container) container.scrollTop = container.scrollHeight;

            if(chatForm) {
                chatForm.addEventListener('submit', function(e) {
                    const message = chatInput.value.trim();
                    if (!message) {
                        e.preventDefault();
                        return;
                    }

                    // 1. Tampilkan Bubble User (Visual Langsung)
                    const userBubble = `
                        <div class="flex justify-end w-full mb-6">
                            <div class="flex items-end gap-2 max-w-[85%] md:max-w-[75%] flex-row-reverse">
                                <div class="w-8 h-8 rounded-full bg-gray-300 flex-shrink-0 flex items-center justify-center text-gray-600 text-xs font-bold uppercase">
                                    {{ substr(Auth::user()->full_name, 0, 1) }}
                                </div>
                                <div class="bg-primary-600 p-4 rounded-2xl rounded-br-none shadow-md text-white text-sm leading-relaxed">
                                    <p class="whitespace-pre-wrap">${message}</p>
                                </div>
                            </div>
                        </div>
                    `;

                    // Insert before loading bubble
                    loadingBubble.insertAdjacentHTML('beforebegin', userBubble);

                    // 2. Tampilkan Loading Bubble
                    loadingBubble.classList.remove('hidden');

                    // 3. Scroll ke bawah
                    container.scrollTop = container.scrollHeight;

                    // 4. Disable Input (Readonly) & Button - JANGAN KOSONGKAN VALUE
                    // Menggunakan readonly agar nilai tetap terkirim saat submit
                    chatInput.readOnly = true;
                    sendBtn.disabled = true;

                    // Form akan submit normal (refresh page) setelah ini
                });
            }
        });
    </script>

    <style>
        .custom-scrollbar::-webkit-scrollbar { width: 6px; }
        .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background-color: #cbd5e1; border-radius: 20px; }
        .dark .custom-scrollbar::-webkit-scrollbar-thumb { background-color: #475569; }
    </style>
</x-app-layout>
