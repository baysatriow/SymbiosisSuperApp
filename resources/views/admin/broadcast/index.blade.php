<x-app-layout>
    <div class="space-y-6 animate-in fade-in duration-700">
        <!-- HEADER -->
        <x-page-header 
            title="Broadcast Center" 
            subtitle="Kirim pengumuman massal ke pengguna melalui WhatsApp secara instan.">
            <x-slot:actions>
                <div class="flex items-center gap-3 bg-white p-1 rounded-2xl border border-gray-100 shadow-sm">
                    <span class="px-3 py-1 text-[10px] font-black text-gray-400 uppercase tracking-widest">Status Engine:</span>
                    <span class="flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-emerald-50 text-emerald-600 text-[10px] font-black tracking-widest border border-emerald-100">
                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                        READY TO SEND
                    </span>
                </div>
            </x-slot:actions>
        </x-page-header>

        <!-- Tampilkan Error Validasi -->
        @if ($errors->any())
            <div class="p-4 text-sm text-red-800 rounded-2xl bg-red-50 border border-red-100 animate-bounce" role="alert">
                <ul class="list-disc list-inside font-bold">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="grid grid-cols-1 xl:grid-cols-12 gap-6">
            <!-- KOLOM KIRI: Template & Editor (xl:col-span-1 -> xl:col-span-4) -->
            <div class="xl:col-span-4 space-y-6">
                <!-- 1. DAFTAR TEMPLATE -->
                <x-content-card class="!p-6 bg-white/80 backdrop-blur-md border-gray-100">
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-[10px] font-black text-gray-400 uppercase tracking-[0.2em]">Template Tersimpan</h3>
                        <button data-modal-target="addTemplateModal" data-modal-toggle="addTemplateModal" class="flex items-center gap-1.5 text-[10px] font-black text-primary-600 hover:text-primary-700 transition-colors uppercase tracking-widest">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
                            TAMBAH
                        </button>
                    </div>
                    <div class="space-y-2 max-h-56 overflow-y-auto pr-2 custom-scrollbar">
                        @forelse($templates as $t)
                            <div class="group relative flex items-center justify-between p-3.5 rounded-2xl border border-gray-50 bg-gray-50/50 hover:bg-white hover:border-primary-100 hover:shadow-lg hover:shadow-primary-100/20 transition-all cursor-pointer overflow-hidden">
                                <button type="button" onclick="applyTemplate('{{ e($t->content) }}')" class="flex-1 text-left min-w-0 pr-8">
                                    <div class="text-xs font-black text-gray-900 truncate uppercase tracking-tight">{{ $t->name }}</div>
                                    <div class="text-[9px] text-gray-400 font-bold truncate mt-0.5">{{ Str::limit($t->content, 40) }}</div>
                                </button>
                                <button type="button"
                                        data-id="{{ $t->id }}"
                                        data-name="{{ $t->name }}"
                                        class="delete-template-btn absolute right-3 p-1.5 text-gray-300 hover:text-rose-500 hover:bg-rose-50 rounded-lg opacity-0 group-hover:opacity-100 transition-all">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                </button>
                            </div>
                        @empty
                            <div class="text-center py-8">
                                <div class="w-12 h-12 bg-gray-50 text-gray-200 rounded-full flex items-center justify-center mx-auto mb-2">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/></svg>
                                </div>
                                <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Belum ada template</p>
                            </div>
                        @endforelse
                    </div>
                </x-content-card>

                <!-- 2. FORM BUAT PESAN -->
                <x-content-card class="!p-0 bg-white border-gray-100 shadow-xl overflow-hidden sticky top-24">
                    <div class="px-6 py-4 bg-gradient-to-r from-gray-900 to-black text-white flex items-center gap-3">
                        <div class="w-8 h-8 rounded-lg bg-white/10 flex items-center justify-center">
                            <svg class="w-4 h-4 text-primary-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                        </div>
                        <h3 class="text-xs font-black uppercase tracking-widest">Message Composer</h3>
                    </div>

                    <form id="broadcastForm" action="{{ route('admin.broadcast.send') }}" method="POST" class="p-6 space-y-6">
                        @csrf
                        
                        <!-- Toggle Kirim Semua -->
                        <div class="p-4 bg-blue-50/50 rounded-2xl border border-blue-100 flex items-start gap-3">
                            <label class="relative inline-flex items-center cursor-pointer mt-0.5">
                                <input type="checkbox" name="send_all" id="send_all_toggle" value="1" class="sr-only peer">
                                <div class="w-10 h-5 bg-gray-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:bg-blue-600"></div>
                            </label>
                            <div>
                                <span class="text-[11px] font-black text-blue-900 uppercase tracking-tight">Kirim ke SELURUH Pengguna</span>
                                <p class="text-[9px] text-blue-500 font-bold uppercase tracking-widest mt-0.5">Target: {{ $totalUsers }} Kontrak aktif</p>
                            </div>
                        </div>

                        <!-- Dropdown Template -->
                        <div>
                            <label class="block mb-2 text-[10px] font-black text-gray-400 uppercase tracking-widest">Gunakan Template</label>
                            <select id="template_selector" class="w-full bg-gray-50/50 border border-gray-100 text-gray-900 text-sm font-bold rounded-2xl focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500 block p-4 outline-none transition-all appearance-none cursor-pointer">
                                <option value="">-- Pilih Template Cepat --</option>
                                @foreach($templates as $t)
                                    <option value="{{ $t->content }}">{{ $t->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Textarea Pesan -->
                        <div>
                            <label class="block mb-2 text-[10px] font-black text-gray-400 uppercase tracking-widest">Isi Pesan WhatsApp</label>
                            <textarea id="message_content" name="message" rows="8" class="w-full bg-gray-50/50 border border-gray-100 text-gray-900 text-sm font-bold rounded-2xl focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500 block p-4 outline-none transition-all font-mono placeholder:text-gray-300" placeholder="Halo {name},..." required></textarea>
                        </div>

                        <!-- Kamus Variabel -->
                        <div id="accordion-variables" data-accordion="collapse">
                            <h2 id="accordion-heading-vars">
                                <button type="button" class="flex items-center justify-between w-full p-4 bg-gray-50/50 rounded-2xl border border-gray-100 hover:bg-gray-100 transition-all font-black text-[10px] uppercase tracking-widest text-gray-500" data-accordion-target="#accordion-body-vars" aria-expanded="false">
                                    <span class="flex items-center gap-2">
                                        <svg class="w-4 h-4 text-primary-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                        KAMUS VARIABEL
                                    </span>
                                    <svg data-accordion-icon class="w-3 h-3 rotate-180 shrink-0 text-gray-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 10 6"><path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5 5 1 1 5"/></svg>
                                </button>
                            </h2>
                            <div id="accordion-body-vars" class="hidden">
                                <div class="p-4 bg-white border border-t-0 border-gray-100 rounded-b-2xl max-h-48 overflow-y-auto custom-scrollbar">
                                    <div class="grid grid-cols-1 gap-1">
                                        @foreach(['{name}' => 'Nama User', '{username}' => 'Username', '{email}' => 'Email', '{phone}' => 'No. HP', '{status}' => 'Status', '{join_date}' => 'Tgl Join', '{company_full}' => 'Nama PT', '{job_title}' => 'Jabatan', '{city}' => 'Kota', '{link_symbiosis}' => 'Link Web', '{nama_system}' => 'Nama Sistem'] as $key => $val)
                                            <div class="flex items-center justify-between p-2 hover:bg-gray-50 rounded-lg transition-colors group cursor-pointer" onclick="insertVar('{{ $key }}')">
                                                <span class="font-mono text-[11px] font-black text-blue-600 group-hover:underline">{{ $key }}</span>
                                                <span class="text-[10px] font-bold text-gray-400 uppercase tracking-tighter">{{ $val }}</span>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div id="selected_users_container"></div>

                        <!-- Trigger Modal Kirim -->
                        <button type="button" id="btn-trigger-confirm" class="w-full bg-emerald-600 hover:bg-emerald-700 text-white font-black py-4 rounded-[1.5rem] shadow-xl shadow-emerald-100 transition-all hover:shadow-2xl active:scale-[0.98] uppercase tracking-widest text-sm flex justify-center items-center gap-3 disabled:opacity-50 disabled:cursor-not-allowed disabled:grayscale" disabled>
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path></svg>
                            KIRIM BROADCAST (<span id="count-display">0</span>)
                        </button>
                    </form>
                </x-content-card>
            </div>

            <!-- KOLOM KANAN: Daftar Pengguna (xl:col-span-2 -> xl:col-span-8) -->
            <div class="xl:col-span-8">
                <x-content-card class="!p-0 border-gray-100 shadow-xl shadow-gray-50/50 flex flex-col h-full overflow-hidden">
                    <div class="p-6 border-b border-gray-50 bg-gray-50/30 flex flex-col sm:flex-row justify-between items-center gap-4">
                        <div>
                            <h3 class="text-sm font-black text-gray-900 uppercase tracking-widest">Pilih Penerima</h3>
                            <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mt-1">TOTAL USER: {{ $totalUsers }}</p>
                        </div>
                        <form action="{{ route('admin.broadcast.index') }}" method="GET" class="flex gap-2 w-full sm:w-auto">
                            <div class="relative group">
                                <div class="absolute inset-y-0 start-0 flex items-center ps-4 pointer-events-none">
                                    <svg class="w-4 h-4 text-gray-400 group-focus-within:text-primary-500 transition-colors" fill="none" viewBox="0 0 20 20"><path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z"/></svg>
                                </div>
                                <input type="text" name="search" value="{{ request('search') }}" 
                                    class="block w-64 p-3 ps-11 text-sm text-gray-900 border border-gray-100 rounded-2xl bg-white focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500 transition-all outline-none" 
                                    placeholder="Cari User atau PT...">
                            </div>
                            <button type="submit" class="px-5 py-3 bg-gray-900 text-white text-xs font-black rounded-2xl hover:bg-black transition-all active:scale-95 shadow-lg shadow-gray-200">
                                SEARCH
                            </button>
                        </form>
                    </div>

                    <div class="flex-1 overflow-x-auto max-h-[700px] overflow-y-auto custom-scrollbar relative" id="table-container">
                        <!-- OVERLAY GLOBAL MODE -->
                        <div id="table-overlay" class="absolute inset-0 bg-gray-50/90 backdrop-blur-[2px] z-20 hidden flex-col items-center justify-center text-center p-8 animate-in fade-in zoom-in duration-300">
                            <div class="bg-white/90 p-10 rounded-[3rem] shadow-2xl border border-white max-w-sm mx-auto">
                                <div class="w-20 h-20 bg-blue-50 text-blue-600 rounded-[2rem] flex items-center justify-center mx-auto mb-6 shadow-sm border border-blue-100">
                                    <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                </div>
                                <h3 class="text-xl font-black text-gray-900 uppercase tracking-tight mb-2">Global Mode Aktif</h3>
                                <p class="text-xs text-gray-500 font-medium leading-relaxed">
                                    Pesan akan dikirim ke <span class="text-blue-600 font-black">{{ $totalUsers }} pengguna</span> terdaftar sekaligus. Fitur pemilihan manual dinonaktifkan.
                                </p>
                            </div>
                        </div>

                        <table class="w-full text-sm text-left">
                            <thead class="text-[10px] text-gray-400 uppercase tracking-widest bg-gray-50/50 border-b border-gray-50 sticky top-0 z-10">
                                <tr>
                                    <th scope="col" class="p-6 w-4 text-center">
                                        <input id="checkbox-all" type="checkbox" class="w-5 h-5 text-primary-600 bg-gray-50 border-gray-200 rounded-lg focus:ring-primary-500 transition-all cursor-pointer">
                                    </th>
                                    <th scope="col" class="px-6 py-5 font-black">Informasi Pengguna</th>
                                    <th scope="col" class="px-6 py-5 font-black">Kontak WhatsApp</th>
                                    <th scope="col" class="px-6 py-5 font-black text-center">Status</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-50 bg-white">
                                @forelse($users as $u)
                                <tr class="group hover:bg-gray-50/50 transition-all cursor-pointer" onclick="toggleRowCheck('{{ $u->id }}')">
                                    <td class="w-4 p-6 text-center" onclick="event.stopPropagation()">
                                        <input id="checkbox-user-{{ $u->id }}" type="checkbox" value="{{ $u->id }}" class="user-checkbox w-5 h-5 text-primary-600 bg-gray-50 border-gray-200 rounded-lg focus:ring-primary-500 transition-all cursor-pointer">
                                    </td>
                                    <td class="px-6 py-5">
                                        <div class="flex items-center gap-4">
                                            <div class="w-10 h-10 rounded-2xl bg-gray-100 text-gray-400 flex items-center justify-center font-bold text-lg border-2 border-white shadow-sm transition-transform group-hover:scale-105 group-hover:bg-primary-50 group-hover:text-primary-500">
                                                {{ substr($u->full_name, 0, 1) }}
                                            </div>
                                            <div>
                                                <div class="font-bold text-gray-900 text-sm group-hover:text-primary-600 transition-colors">{{ $u->full_name }}</div>
                                                <div class="text-[10px] text-gray-400 font-black uppercase tracking-widest mt-0.5">{{ $u->companyProfile->company_name ?? 'NO COMPANY RECORD' }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-5">
                                        <div class="flex items-center gap-2 text-gray-900 font-mono text-xs font-bold bg-gray-50 w-fit px-3 py-1.5 rounded-xl border border-gray-100">
                                            <svg class="w-3.5 h-3.5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                                            {{ $u->phone_number }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-5 text-center">
                                        @if($u->status === 'active')
                                            <span class="inline-flex items-center px-2.5 py-1 rounded-lg bg-emerald-50 text-emerald-700 text-[10px] font-black uppercase border border-emerald-100">Active</span>
                                        @else
                                            <span class="inline-flex items-center px-2.5 py-1 rounded-lg bg-gray-50 text-gray-400 text-[10px] font-black uppercase border border-gray-100">{{ $u->status }}</span>
                                        @endif
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="px-6 py-12 text-center">
                                        <div class="flex flex-col items-center justify-center opacity-40">
                                            <svg class="w-16 h-16 text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                                            <p class="text-sm font-bold text-gray-400 uppercase tracking-widest">Tidak ada data pengguna</p>
                                        </div>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    
                    @if($users->hasPages())
                        <div class="px-6 py-4 border-t border-gray-50 bg-gray-50/30">
                            {{ $users->links() }}
                        </div>
                    @endif
                </x-content-card>
            </div>
        </div>
    </div>

    <!-- MODAL SECTION -->

    <!-- MODAL TAMBAH TEMPLATE -->
    <div id="addTemplateModal" tabindex="-1" aria-hidden="true" class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full transition-all duration-300 backdrop-blur-sm">
        <div class="relative p-6 w-full max-w-4xl max-h-full">
            <div class="relative bg-white rounded-[2.5rem] shadow-2xl border-4 border-white overflow-hidden">
                <div class="flex items-center justify-between p-8 bg-gradient-to-r from-primary-600 to-primary-800 text-white">
                    <div>
                        <h3 class="text-xl font-black uppercase tracking-tight">Simpan Template Pesan</h3>
                        <p class="text-primary-100 text-xs font-medium mt-1">Buat format pesan tetap untuk mempermudah broadcast.</p>
                    </div>
                    <button type="button" class="w-10 h-10 flex items-center justify-center bg-white/20 hover:bg-white/30 rounded-2xl transition-all" data-modal-toggle="addTemplateModal">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 14 14"><path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/></svg>
                    </button>
                </div>
                <form action="{{ route('admin.broadcast.templates.store') }}" method="POST" class="p-8 space-y-6 bg-white">
                    @csrf
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                        <div class="md:col-span-2 space-y-6">
                            <div>
                                <label class="block mb-2 text-[10px] font-black text-gray-400 uppercase tracking-widest">Nama Template</label>
                                <input type="text" name="name" id="template_name_input" class="w-full bg-gray-50/50 border border-gray-100 text-gray-900 text-sm font-bold rounded-2xl focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500 block p-4 outline-none transition-all" placeholder="Contoh: Pengingat Izin Lingkungan..." required>
                            </div>
                            <div>
                                <label class="block mb-2 text-[10px] font-black text-gray-400 uppercase tracking-widest">Isi Pesan (WA Format)</label>
                                <textarea name="content" id="template_content_input" rows="10" class="w-full bg-gray-50/50 border border-gray-100 text-gray-900 text-sm font-bold rounded-2xl focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500 block p-4 outline-none transition-all font-mono" placeholder="Halo {name},..." required></textarea>
                            </div>
                        </div>
                        <div class="md:col-span-1 bg-gray-50/50 rounded-3xl p-6 border border-gray-100 flex flex-col h-full">
                            <h4 class="text-[10px] font-black text-gray-900 uppercase tracking-[0.2em] mb-4">KAMUS VARIABEL</h4>
                            <div class="overflow-y-auto flex-1 custom-scrollbar pr-2 space-y-1">
                                @foreach(['{name}' => 'Nama User', '{username}' => 'Username', '{email}' => 'Email', '{phone}' => 'No. HP', '{status}' => 'Status', '{company_full}' => 'Nama PT', '{job_title}' => 'Jabatan', '{city}' => 'Kota', '{link_symbiosis}' => 'Link Web'] as $key => $val)
                                    <div class="flex items-center justify-between p-2 hover:bg-white rounded-xl transition-all group cursor-pointer border border-transparent hover:border-gray-100 hover:shadow-sm" onclick="insertVarToTemplate('{{ $key }}')">
                                        <span class="font-mono text-[11px] font-black text-blue-600 group-hover:underline">{{ $key }}</span>
                                        <span class="text-[8px] font-black text-gray-400 uppercase tracking-widest">{{ $val }}</span>
                                    </div>
                                @endforeach
                            </div>
                            <p class="text-[9px] text-gray-400 font-bold mt-4 leading-tight italic">Klik variabel untuk menyisipkan ke dalam isi pesan.</p>
                        </div>
                    </div>
                    <div class="pt-4 flex gap-4">
                        <button type="button" data-modal-hide="addTemplateModal" class="flex-1 py-4 bg-gray-100 text-gray-600 font-black rounded-2xl hover:bg-gray-200 transition-all uppercase text-xs tracking-widest">BATAL</button>
                        <button type="submit" id="btn-save-template" class="flex-2 py-4 px-8 bg-primary-600 text-white font-black rounded-2xl hover:bg-primary-700 transition-all shadow-xl shadow-primary-100 uppercase text-xs tracking-widest disabled:opacity-50 disabled:cursor-not-allowed" disabled>SIMPAN TEMPLATE</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- MODAL HAPUS TEMPLATE -->
    <div id="deleteTemplateModal" tabindex="-1" aria-hidden="true" class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full transition-all duration-300 backdrop-blur-sm">
        <div class="relative p-6 w-full max-w-md max-h-full">
            <div class="relative bg-white rounded-[2.5rem] shadow-2xl border-4 border-white overflow-hidden p-10 text-center">
                <div class="w-24 h-24 bg-rose-50 text-rose-500 rounded-[2rem] flex items-center justify-center mx-auto mb-6 shadow-sm border border-rose-100">
                    <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                </div>
                <h3 class="text-2xl font-black text-gray-900 uppercase tracking-tight mb-2">Hapus Template?</h3>
                <p class="text-gray-500 text-sm font-medium mb-8">Format pesan <span id="del_tpl_name" class="text-rose-600 font-black"></span> akan dihapus dari daftar.</p>
                <form id="deleteTemplateForm" method="POST" action="" class="flex gap-4">
                    @csrf
                    @method('DELETE')
                    <button type="button" data-modal-hide="deleteTemplateModal" class="flex-1 py-4 bg-gray-100 text-gray-600 font-black rounded-2xl hover:bg-gray-200 transition-all uppercase text-xs tracking-widest">BATAL</button>
                    <button type="submit" class="flex-1 py-4 bg-rose-600 text-white font-black rounded-2xl hover:bg-rose-700 transition-all shadow-xl shadow-rose-100 uppercase text-xs tracking-widest">HAPUS</button>
                </form>
            </div>
        </div>
    </div>

    <!-- MODAL KONFIRMASI KIRIM -->
    <div id="confirmBroadcastModal" tabindex="-1" aria-hidden="true" class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full transition-all duration-300 backdrop-blur-sm">
        <div class="relative p-6 w-full max-w-md max-h-full">
            <div class="relative bg-white rounded-[2.5rem] shadow-2xl border-4 border-white overflow-hidden p-10 text-center">
                <div class="w-24 h-24 bg-emerald-50 text-emerald-500 rounded-[2rem] flex items-center justify-center mx-auto mb-6 shadow-sm border border-emerald-100">
                    <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path></svg>
                </div>
                <h3 class="text-2xl font-black text-gray-900 uppercase tracking-tight mb-2">Kirim Broadcast?</h3>
                <p class="text-gray-500 text-sm font-medium mb-8">
                    Anda akan mengirim pesan massal ke <br>
                    <span id="confirm_count" class="text-emerald-600 font-black text-xl">0</span> pengguna.<br>
                    Tindakan ini tidak dapat dibatalkan.
                </p>
                <div class="flex gap-4">
                    <button type="button" data-modal-hide="confirmBroadcastModal" class="flex-1 py-4 bg-gray-100 text-gray-600 font-black rounded-2xl hover:bg-gray-200 transition-all uppercase text-xs tracking-widest">BATAL</button>
                    <button type="button" id="final-submit-btn" class="flex-1 py-4 bg-emerald-600 text-white font-black rounded-2xl hover:bg-emerald-700 transition-all shadow-xl shadow-emerald-100 uppercase text-xs tracking-widest">YA, KIRIM</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Fungsi Insert Variabel
        function insertVar(code) {
            const textarea = document.getElementById('message_content');
            insertTextAtCursor(textarea, code);
        }

        function insertVarToTemplate(code) {
            const textarea = document.getElementById('template_content_input');
            insertTextAtCursor(textarea, code);
            textarea.dispatchEvent(new Event('input'));
        }

        function insertTextAtCursor(textarea, text) {
            if (textarea.selectionStart || textarea.selectionStart == '0') {
                var startPos = textarea.selectionStart;
                var endPos = textarea.selectionEnd;
                textarea.value = textarea.value.substring(0, startPos) + text + textarea.value.substring(endPos, textarea.value.length);
                textarea.selectionStart = startPos + text.length;
                textarea.selectionEnd = startPos + text.length;
            } else { textarea.value += text; }
            textarea.focus();
        }

        function applyTemplate(content) {
            document.getElementById('message_content').value = content;
            document.getElementById('message_content').dispatchEvent(new Event('input'));
            updateSelection(); // Re-validate trigger button
        }

        function toggleRowCheck(id) {
            const cb = document.getElementById('checkbox-user-' + id);
            if(!document.getElementById('send_all_toggle').checked) {
                cb.checked = !cb.checked;
                cb.dispatchEvent(new Event('change'));
            }
        }

        document.addEventListener('DOMContentLoaded', function() {
            const templateSelector = document.getElementById('template_selector');
            const messageArea = document.getElementById('message_content');
            const tplName = document.getElementById('template_name_input');
            const tplContent = document.getElementById('template_content_input');
            const tplSaveBtn = document.getElementById('btn-save-template');
            const checkboxAll = document.getElementById('checkbox-all');
            const userCheckboxes = document.querySelectorAll('.user-checkbox');
            const countDisplay = document.getElementById('count-display');
            const triggerConfirmBtn = document.getElementById('btn-trigger-confirm');
            const finalSubmitBtn = document.getElementById('final-submit-btn');
            const hiddenContainer = document.getElementById('selected_users_container');
            const sendAllToggle = document.getElementById('send_all_toggle');
            const tableOverlay = document.getElementById('table-overlay');
            const totalUsers = {{ $totalUsers }};
            const confirmModal = new Modal(document.getElementById('confirmBroadcastModal'));
            const deleteModal = new Modal(document.getElementById('deleteTemplateModal'));
            const delForm = document.getElementById('deleteTemplateForm');
            const delNameSpan = document.getElementById('del_tpl_name');

            function checkTemplateForm() {
                if(tplName.value.trim() !== '' && tplContent.value.trim() !== '') {
                    tplSaveBtn.disabled = false;
                } else {
                    tplSaveBtn.disabled = true;
                }
            }
            tplName.addEventListener('input', checkTemplateForm);
            tplContent.addEventListener('input', checkTemplateForm);

            document.querySelectorAll('.delete-template-btn').forEach(btn => {
                btn.addEventListener('click', function(e) {
                    e.stopPropagation();
                    const id = this.getAttribute('data-id');
                    const name = this.getAttribute('data-name');
                    delNameSpan.textContent = `"${name}"`;
                    delForm.action = `/admin/broadcast/templates/${id}`;
                    deleteModal.show();
                });
            });

            triggerConfirmBtn.addEventListener('click', function() {
                if(!messageArea.value.trim()) {
                    alert('Isi pesan tidak boleh kosong!');
                    return;
                }
                let recipientCount = sendAllToggle.checked ? totalUsers : document.querySelectorAll('.user-checkbox:checked').length;
                if(recipientCount === 0) return;
                document.getElementById('confirm_count').innerText = recipientCount;
                confirmModal.show();
            });

            finalSubmitBtn.addEventListener('click', function() {
                document.getElementById('broadcastForm').submit();
                this.disabled = true;
                this.innerHTML = '<span class="flex items-center gap-2"><svg class="animate-spin h-4 w-4 text-white" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg> SENDING...</span>';
            });

            sendAllToggle.addEventListener('change', function() {
                if(this.checked) {
                    tableOverlay.classList.remove('hidden');
                    triggerConfirmBtn.disabled = false;
                    countDisplay.innerText = totalUsers;
                    userCheckboxes.forEach(cb => cb.checked = false);
                    checkboxAll.checked = false;
                } else {
                    tableOverlay.classList.add('hidden');
                    updateSelection();
                }
            });

            templateSelector.addEventListener('change', function() {
                if(this.value) {
                    messageArea.value = this.value;
                    updateSelection();
                }
            });

            messageArea.addEventListener('input', updateSelection);

            function updateSelection() {
                let hasMessage = messageArea.value.trim().length > 0;
                if(sendAllToggle.checked) {
                    triggerConfirmBtn.disabled = !hasMessage;
                    return;
                }
                let count = 0;
                hiddenContainer.innerHTML = '';
                userCheckboxes.forEach(cb => {
                    if(cb.checked) {
                        count++;
                        const input = document.createElement('input');
                        input.type = 'hidden';
                        input.name = 'user_ids[]';
                        input.value = cb.value;
                        hiddenContainer.appendChild(input);
                        cb.closest('tr').classList.add('bg-blue-50/50', 'border-primary-100');
                    } else {
                        cb.closest('tr').classList.remove('bg-blue-50/50', 'border-primary-100');
                    }
                });
                countDisplay.innerText = count;
                triggerConfirmBtn.disabled = (count === 0 || !hasMessage);
            }

            checkboxAll.addEventListener('change', function() {
                if(!sendAllToggle.checked) {
                    userCheckboxes.forEach(cb => cb.checked = this.checked);
                    updateSelection();
                }
            });

            userCheckboxes.forEach(cb => {
                cb.addEventListener('change', updateSelection);
                cb.addEventListener('click', function(e) { e.stopPropagation(); });
            });
        });
    </script>
</x-app-layout>
