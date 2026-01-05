<x-app-layout>
    <div class="space-y-6 animate-in fade-in duration-700">
        <!-- HEADER -->
        <x-page-header 
            title="Master Data Dokumen" 
            subtitle="Kelola kategori dan jenis dokumen kebijakan yang berlaku di sistem.">
            <x-slot:actions>
                <button data-modal-target="addFieldModal" data-modal-toggle="addFieldModal" class="flex items-center gap-2 px-5 py-2.5 bg-primary-600 text-white font-bold rounded-xl hover:bg-primary-700 transition-all shadow-lg shadow-primary-200 active:scale-95 group">
                    <svg class="w-5 h-5 group-hover:rotate-90 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
                    TAMBAH KATEGORI
                </button>
            </x-slot:actions>
        </x-page-header>

        <!-- ACCORDION FIELDS -->
        <div id="accordion-master" data-accordion="collapse" class="space-y-4">
            @foreach($fields as $field)
                <div class="bg-white border border-gray-100 rounded-[2rem] shadow-sm hover:shadow-md transition-all overflow-hidden">
                    <!-- Header Kategori -->
                    <h2 id="accordion-heading-{{ $field->id }}">
                        <button type="button" class="flex items-center justify-between w-full p-6 font-bold text-gray-900 hover:bg-gray-50/50 gap-3 transition-all" data-accordion-target="#accordion-body-{{ $field->id }}" aria-expanded="false" aria-controls="accordion-body-{{ $field->id }}">
                            <div class="flex items-center gap-5">
                                <div class="w-14 h-14 bg-gradient-to-br from-primary-500 to-primary-700 text-white rounded-2xl flex items-center justify-center font-black text-xl shadow-lg shadow-primary-100 transition-transform group-hover:scale-105">
                                    {{ $field->code }}
                                </div>
                                <div class="text-left">
                                    <div class="text-lg font-black text-gray-900 tracking-tight">{{ $field->name }}</div>
                                    <div class="flex items-center gap-3 mt-1">
                                        <span class="text-[10px] font-bold text-gray-400 uppercase tracking-widest flex items-center gap-1">
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                            {{ $field->total_subfields }} JENIS DOKUMEN
                                        </span>
                                        <span class="w-1 h-1 rounded-full bg-gray-200"></span>
                                        <span class="text-[10px] font-bold text-primary-600 uppercase tracking-widest flex items-center gap-1">
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/></svg>
                                            {{ $field->total_docs }} FILE TERUPLOAD
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class="flex items-center gap-4">
                                <span class="bg-gray-50 text-gray-400 text-[10px] font-black px-3 py-1 rounded-full border border-gray-100">ORDER: {{ $field->sort_order }}</span>
                                <svg data-accordion-icon class="w-4 h-4 rotate-180 shrink-0 text-gray-400 transition-transform duration-300" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 10 6"><path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5 5 1 1 5"/></svg>
                            </div>
                        </button>
                    </h2>

                    <!-- Body Kategori -->
                    <div id="accordion-body-{{ $field->id }}" class="hidden" aria-labelledby="accordion-heading-{{ $field->id }}">
                        <div class="p-8 border-t border-gray-50 bg-gray-50/30">
                            <!-- Action Bar Kategori -->
                            <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-8 pb-6 border-b border-gray-100">
                                <div class="max-w-2xl">
                                    <h4 class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">Deskripsi Kategori</h4>
                                    <p class="text-sm text-gray-600 leading-relaxed font-medium">{{ $field->description ?: 'Tidak ada deskripsi untuk kategori ini.' }}</p>
                                </div>
                                <div class="flex gap-2 shrink-0">
                                    <button type="button"
                                            onclick="openEditFieldModal('{{ $field->id }}', '{{ $field->name }}', '{{ $field->code }}', '{{ $field->sort_order }}', '{{ $field->description }}')"
                                            class="flex items-center gap-2 px-4 py-2 text-xs font-bold text-blue-600 bg-blue-50 hover:bg-blue-100 rounded-xl transition-all border border-blue-100">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                        EDIT KATEGORI
                                    </button>
                                    <button type="button" 
                                            onclick="openDeleteModal('{{ route('admin.master.field.destroy', $field->id) }}', 'Kategori {{ $field->name }}', {{ $field->total_docs }}, 'Field')" 
                                            class="flex items-center gap-2 px-4 py-2 text-xs font-bold text-rose-600 bg-rose-50 hover:bg-rose-100 rounded-xl transition-all border border-rose-100">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                        HAPUS
                                    </button>
                                </div>
                            </div>

                            <!-- Tabel Subfields -->
                            <div class="bg-white rounded-3xl border border-gray-100 shadow-sm overflow-hidden">
                                <table class="w-full text-sm text-left">
                                    <thead class="text-[10px] text-gray-400 uppercase tracking-widest bg-gray-50/50 border-b border-gray-50">
                                        <tr>
                                            <th scope="col" class="px-6 py-4 font-black">Jenis Dokumen</th>
                                            <th scope="col" class="px-6 py-4 font-black text-center">Config</th>
                                            <th scope="col" class="px-6 py-4 font-black text-center">Statistik</th>
                                            <th scope="col" class="px-6 py-4 font-black text-center">Tindakan</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-gray-50">
                                        @forelse($field->subfields as $sub)
                                            <tr class="group hover:bg-gray-50/50 transition-all">
                                                <td class="px-6 py-5">
                                                    <div class="flex items-start gap-3">
                                                        <div class="w-8 h-8 rounded-lg bg-gray-100 flex items-center justify-center text-gray-400 flex-shrink-0 group-hover:bg-primary-50 group-hover:text-primary-500 transition-colors">
                                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                                        </div>
                                                        <div>
                                                            <div class="font-bold text-gray-900 flex items-center gap-2">
                                                                {{ $sub->name }}
                                                                @if($sub->is_custom)
                                                                    <span class="px-2 py-0.5 rounded-md bg-purple-50 text-purple-600 text-[9px] font-black uppercase border border-purple-100 shadow-sm shadow-purple-50">Custom</span>
                                                                @endif
                                                            </div>
                                                            <div class="text-[11px] text-gray-400 font-medium mt-0.5 line-clamp-1">{{ $sub->description ?: 'Tidak ada deskripsi.' }}</div>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td class="px-6 py-5">
                                                    <div class="flex flex-col items-center gap-1.5">
                                                        @if($sub->required)
                                                            <span class="inline-flex items-center px-2 py-0.5 rounded-full bg-rose-50 text-rose-700 text-[9px] font-bold border border-rose-100">REQUIRED</span>
                                                        @else
                                                            <span class="inline-flex items-center px-2 py-0.5 rounded-full bg-gray-50 text-gray-400 text-[9px] font-bold border border-gray-100">OPTIONAL</span>
                                                        @endif
                                                        <span class="text-[10px] font-black text-gray-900">MAX {{ $sub->max_size_mb }} MB</span>
                                                    </div>
                                                </td>
                                                <td class="px-6 py-5 text-center">
                                                    <div class="inline-flex flex-col items-center p-2 rounded-2xl bg-gray-50/50 border border-gray-100">
                                                        <span class="text-base font-black text-gray-900 leading-none">{{ $sub->documents_count }}</span>
                                                        <span class="text-[8px] font-black text-gray-400 uppercase tracking-tighter mt-1">FILES</span>
                                                    </div>
                                                </td>
                                                <td class="px-6 py-5 text-center">
                                                    <div class="flex justify-center gap-1">
                                                        @if(!$sub->is_custom)
                                                            <button type="button"
                                                                    onclick="openEditSubModal('{{ $sub->id }}', '{{ $sub->name }}', '{{ $sub->max_size_mb }}', '{{ $sub->required }}', '{{ $sub->description }}')"
                                                                    class="p-2 text-gray-400 hover:text-blue-600 hover:bg-blue-50 rounded-xl transition-all"
                                                                    title="Edit">
                                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                                            </button>
                                                        @endif
                                                        <button type="button" onclick="openDeleteModal('{{ route('admin.master.subfield.destroy', $sub->id) }}', 'Dokumen {{ $sub->name }}', {{ $sub->documents_count }}, 'Subfield')" 
                                                                class="p-2 text-gray-400 hover:text-rose-600 hover:bg-rose-50 rounded-xl transition-all"
                                                                title="Hapus">
                                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                                        </button>
                                                    </div>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="4" class="px-6 py-8 text-center text-gray-400 font-medium italic">Belum ada jenis dokumen di kategori ini.</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>

                            <div class="mt-6 flex justify-center">
                                <button type="button" onclick="openAddSubModal('{{ $field->id }}', '{{ $field->name }}')" class="flex items-center gap-2 px-6 py-3 bg-white border-2 border-dashed border-gray-200 text-gray-400 hover:text-primary-600 hover:border-primary-200 hover:bg-primary-50 rounded-2xl transition-all font-bold text-xs group">
                                    <svg class="w-4 h-4 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                                    TAMBAH JENIS DOKUMEN BARU
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <!-- ================= MODAL SECTION ================= -->

    <!-- 1. MODAL TAMBAH FIELD -->
    <div id="addFieldModal" tabindex="-1" aria-hidden="true" class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full transition-all duration-300 backdrop-blur-sm">
        <div class="relative p-6 w-full max-w-lg max-h-full">
            <div class="relative bg-white rounded-[2.5rem] shadow-2xl border-4 border-white overflow-hidden">
                <div class="flex items-center justify-between p-8 bg-gradient-to-r from-primary-600 to-primary-800 text-white">
                    <div>
                        <h3 class="text-xl font-black uppercase tracking-tight">Tambah Kategori</h3>
                        <p class="text-primary-100 text-xs font-medium mt-1">Buat pengelompokan master data dokumen baru.</p>
                    </div>
                    <button type="button" class="w-10 h-10 flex items-center justify-center bg-white/20 hover:bg-white/30 rounded-2xl transition-all" data-modal-toggle="addFieldModal">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 14 14"><path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/></svg>
                    </button>
                </div>
                <form action="{{ route('admin.master.field.store') }}" method="POST" class="p-8 space-y-6 bg-white">
                    @csrf
                    <div class="grid gap-6 grid-cols-2">
                        <div class="col-span-1">
                            <label class="block mb-2 text-[10px] font-black text-gray-400 uppercase tracking-widest">Kode (ex: A, B1)</label>
                            <input type="text" name="code" class="w-full bg-gray-50/50 border border-gray-100 text-gray-900 text-sm font-bold rounded-2xl focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500 block p-4 outline-none transition-all" placeholder="ID Kode..." required>
                        </div>
                        <div class="col-span-1">
                            <label class="block mb-2 text-[10px] font-black text-gray-400 uppercase tracking-widest">Urutan Tampil</label>
                            <input type="number" name="sort_order" class="w-full bg-gray-50/50 border border-gray-100 text-gray-900 text-sm font-bold rounded-2xl focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500 block p-4 outline-none transition-all" value="1" required>
                        </div>
                        <div class="col-span-2">
                            <label class="block mb-2 text-[10px] font-black text-gray-400 uppercase tracking-widest">Nama Kategori</label>
                            <input type="text" name="name" class="w-full bg-gray-50/50 border border-gray-100 text-gray-900 text-sm font-bold rounded-2xl focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500 block p-4 outline-none transition-all" placeholder="Contoh: Kebijakan Lingkungan..." required>
                        </div>
                        <div class="col-span-2">
                            <label class="block mb-2 text-[10px] font-black text-gray-400 uppercase tracking-widest">Deskripsi (Opsional)</label>
                            <textarea name="description" rows="3" class="w-full bg-gray-50/50 border border-gray-100 text-gray-900 text-sm font-bold rounded-2xl focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500 block p-4 outline-none transition-all" placeholder="Jelaskan isi kategori ini..."></textarea>
                        </div>
                    </div>
                    <div class="pt-4">
                        <button type="submit" class="w-full bg-primary-600 hover:bg-primary-700 text-white font-black py-4 rounded-[1.5rem] shadow-xl shadow-primary-100 transition-all hover:shadow-2xl active:scale-[0.98] uppercase tracking-widest text-sm">
                            SIMPAN KATEGORI
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- 2. MODAL EDIT FIELD -->
    <div id="editFieldModal" tabindex="-1" aria-hidden="true" class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full transition-all duration-300 backdrop-blur-sm">
        <div class="relative p-6 w-full max-w-lg max-h-full">
            <div class="relative bg-white rounded-[2.5rem] shadow-2xl border-4 border-white overflow-hidden">
                <div class="flex items-center justify-between p-8 bg-gradient-to-r from-blue-600 to-blue-800 text-white">
                    <div>
                        <h3 class="text-xl font-black uppercase tracking-tight">Edit Kategori</h3>
                        <p class="text-blue-100 text-xs font-medium mt-1">Perbarui informasi pengelompokan dokumen.</p>
                    </div>
                    <button type="button" class="w-10 h-10 flex items-center justify-center bg-white/20 hover:bg-white/30 rounded-2xl transition-all" data-modal-hide="editFieldModal">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 14 14"><path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/></svg>
                    </button>
                </div>
                <form id="editFieldForm" method="POST" class="p-8 space-y-6 bg-white">
                    @csrf
                    @method('PUT')
                    <div class="grid gap-6 grid-cols-2">
                        <div class="col-span-1">
                            <label class="block mb-2 text-[10px] font-black text-gray-400 uppercase tracking-widest">Kode</label>
                            <input type="text" name="code" id="edit_field_code" class="w-full bg-gray-50/50 border border-gray-100 text-gray-900 text-sm font-bold rounded-2xl focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 block p-4 outline-none transition-all" required>
                        </div>
                        <div class="col-span-1">
                            <label class="block mb-2 text-[10px] font-black text-gray-400 uppercase tracking-widest">Urutan</label>
                            <input type="number" name="sort_order" id="edit_field_order" class="w-full bg-gray-50/50 border border-gray-100 text-gray-900 text-sm font-bold rounded-2xl focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 block p-4 outline-none transition-all" required>
                        </div>
                        <div class="col-span-2">
                            <label class="block mb-2 text-[10px] font-black text-gray-400 uppercase tracking-widest">Nama Kategori</label>
                            <input type="text" name="name" id="edit_field_name" class="w-full bg-gray-50/50 border border-gray-100 text-gray-900 text-sm font-bold rounded-2xl focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 block p-4 outline-none transition-all" required>
                        </div>
                        <div class="col-span-2">
                            <label class="block mb-2 text-[10px] font-black text-gray-400 uppercase tracking-widest">Deskripsi</label>
                            <textarea name="description" id="edit_field_desc" rows="3" class="w-full bg-gray-50/50 border border-gray-100 text-gray-900 text-sm font-bold rounded-2xl focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 block p-4 outline-none transition-all"></textarea>
                        </div>
                    </div>
                    <div class="pt-4">
                        <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-black py-4 rounded-[1.5rem] shadow-xl shadow-blue-100 transition-all hover:shadow-2xl active:scale-[0.98] uppercase tracking-widest text-sm">
                            UPDATE KATEGORI
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- 3. MODAL TAMBAH SUBFIELD -->
    <div id="addSubModal" tabindex="-1" aria-hidden="true" class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full transition-all duration-300 backdrop-blur-sm">
        <div class="relative p-6 w-full max-w-lg max-h-full">
            <div class="relative bg-white rounded-[2.5rem] shadow-2xl border-4 border-white overflow-hidden">
                <div class="flex items-center justify-between p-8 bg-gradient-to-r from-emerald-600 to-emerald-800 text-white">
                    <div>
                        <h3 class="text-xl font-black uppercase tracking-tight leading-none mb-1">Tambah Jenis Dokumen</h3>
                        <p class="text-emerald-100 text-[10px] font-bold uppercase tracking-widest">KATEGORI: <span id="add_sub_parent"></span></p>
                    </div>
                    <button type="button" class="w-10 h-10 flex items-center justify-center bg-white/20 hover:bg-white/30 rounded-2xl transition-all" data-modal-hide="addSubModal">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 14 14"><path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/></svg>
                    </button>
                </div>
                <form action="{{ route('admin.master.subfield.store') }}" method="POST" class="p-8 space-y-6 bg-white">
                    @csrf
                    <input type="hidden" name="field_id" id="add_sub_field_id">
                    <div class="grid gap-6 grid-cols-2">
                        <div class="col-span-2">
                            <label class="block mb-2 text-[10px] font-black text-gray-400 uppercase tracking-widest">Nama Dokumen</label>
                            <input type="text" name="name" class="w-full bg-gray-50/50 border border-gray-100 text-gray-900 text-sm font-bold rounded-2xl focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 block p-4 outline-none transition-all" placeholder="Contoh: Izin Lingkungan (AMDAL/UKL-UPL)..." required>
                        </div>
                        <div class="col-span-2 sm:col-span-1">
                            <label class="block mb-2 text-[10px] font-black text-gray-400 uppercase tracking-widest">Max Size (MB)</label>
                            <input type="number" name="max_size_mb" class="w-full bg-gray-50/50 border border-gray-100 text-gray-900 text-sm font-bold rounded-2xl focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 block p-4 outline-none transition-all" value="10" required>
                        </div>
                        <div class="col-span-2 sm:col-span-1 flex items-center pt-6">
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" name="required" value="1" class="sr-only peer">
                                <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-emerald-600"></div>
                                <span class="ms-3 text-[10px] font-black text-gray-400 uppercase tracking-widest">Wajib Diisi?</span>
                            </label>
                        </div>
                        <div class="col-span-2">
                            <label class="block mb-2 text-[10px] font-black text-gray-400 uppercase tracking-widest">Keterangan / Instruksi</label>
                            <textarea name="description" rows="3" class="w-full bg-gray-50/50 border border-gray-100 text-gray-900 text-sm font-bold rounded-2xl focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 block p-4 outline-none transition-all" placeholder="Berikan instruksi pengunggahan untuk user..."></textarea>
                        </div>
                    </div>
                    <div class="pt-4">
                        <button type="submit" class="w-full bg-emerald-600 hover:bg-emerald-700 text-white font-black py-4 rounded-[1.5rem] shadow-xl shadow-emerald-100 transition-all hover:shadow-2xl active:scale-[0.98] uppercase tracking-widest text-sm">
                            SIMPAN JENIS DOKUMEN
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- 4. MODAL EDIT SUBFIELD -->
    <div id="editSubModal" tabindex="-1" aria-hidden="true" class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full transition-all duration-300 backdrop-blur-sm">
        <div class="relative p-6 w-full max-w-lg max-h-full">
            <div class="relative bg-white rounded-[2.5rem] shadow-2xl border-4 border-white overflow-hidden">
                <div class="flex items-center justify-between p-8 bg-gradient-to-r from-indigo-600 to-indigo-800 text-white">
                    <div>
                        <h3 class="text-xl font-black uppercase tracking-tight">Edit Jenis Dokumen</h3>
                        <p class="text-indigo-100 text-xs font-medium mt-1">Perbarui konfigurasi data jenis dokumen.</p>
                    </div>
                    <button type="button" class="w-10 h-10 flex items-center justify-center bg-white/20 hover:bg-white/30 rounded-2xl transition-all" data-modal-hide="editSubModal">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 14 14"><path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/></svg>
                    </button>
                </div>
                <form id="editSubForm" method="POST" class="p-8 space-y-6 bg-white">
                    @csrf
                    @method('PUT')
                    <div class="grid gap-6 grid-cols-2">
                        <div class="col-span-2">
                            <label class="block mb-2 text-[10px] font-black text-gray-400 uppercase tracking-widest">Nama Dokumen</label>
                            <input type="text" name="name" id="edit_sub_name" class="w-full bg-gray-50/50 border border-gray-100 text-gray-900 text-sm font-bold rounded-2xl focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 block p-4 outline-none transition-all" required>
                        </div>
                        <div class="col-span-2 sm:col-span-1">
                            <label class="block mb-2 text-[10px] font-black text-gray-400 uppercase tracking-widest">Max Size (MB)</label>
                            <input type="number" name="max_size_mb" id="edit_sub_max" class="w-full bg-gray-50/50 border border-gray-100 text-gray-900 text-sm font-bold rounded-2xl focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 block p-4 outline-none transition-all" required>
                        </div>
                        <div class="col-span-2 sm:col-span-1 flex items-center pt-6">
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" name="required" id="edit_sub_req" value="1" class="sr-only peer">
                                <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-indigo-600"></div>
                                <span class="ms-3 text-[10px] font-black text-gray-400 uppercase tracking-widest">Wajib Diisi?</span>
                            </label>
                        </div>
                        <div class="col-span-2">
                            <label class="block mb-2 text-[10px] font-black text-gray-400 uppercase tracking-widest">Keterangan / Instruksi</label>
                            <textarea name="description" id="edit_sub_desc" rows="3" class="w-full bg-gray-50/50 border border-gray-100 text-gray-900 text-sm font-bold rounded-2xl focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 block p-4 outline-none transition-all"></textarea>
                        </div>
                    </div>
                    <div class="pt-4">
                        <button type="submit" class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-black py-4 rounded-[1.5rem] shadow-xl shadow-indigo-100 transition-all hover:shadow-2xl active:scale-[0.98] uppercase tracking-widest text-sm">
                            UPDATE JENIS DOKUMEN
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- 5. MODAL DELETE -->
    <div id="deleteMasterModal" tabindex="-1" aria-hidden="true" class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full transition-all duration-300 backdrop-blur-sm">
        <div class="relative p-6 w-full max-w-md max-h-full">
            <div class="relative bg-white rounded-[2.5rem] shadow-2xl border-4 border-white overflow-hidden p-10 text-center">
                <div class="w-24 h-24 bg-rose-50 text-rose-500 rounded-[2rem] flex items-center justify-center mx-auto mb-6 shadow-sm border border-rose-100">
                    <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                </div>
                
                <h3 class="text-2xl font-black text-gray-900 uppercase tracking-tight mb-2">Hapus Data?</h3>
                <p class="text-gray-500 text-sm font-medium mb-2">Anda akan menghapus <br><span id="del_target_name" class="text-rose-600 font-black"></span>.</p>
                
                <div class="bg-rose-50 rounded-2xl p-4 text-left border border-rose-100 mb-8">
                    <div class="flex items-center gap-2 text-rose-700 text-[10px] font-black uppercase tracking-widest mb-1">
                        <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path></svg>
                        DAMPAK PENGHAPUSAN:
                    </div>
                    <ul class="text-[10px] text-rose-600 font-bold space-y-1">
                        <li>• Data master akan hilang selamanya.</li>
                        <li>• <span id="del_count">0</span> file fisik user akan <span class="underline">TERHAPUS PERMANEN</span>.</li>
                    </ul>
                </div>

                <form id="deleteMasterForm" method="POST" action="" class="flex gap-4">
                    @csrf
                    @method('DELETE')
                    <button type="button" data-modal-hide="deleteMasterModal" class="flex-1 py-4 bg-gray-100 text-gray-600 font-black rounded-2xl hover:bg-gray-200 transition-all uppercase text-xs tracking-widest">
                        BATAL
                    </button>
                    <button type="submit" class="flex-1 py-4 bg-rose-600 text-white font-black rounded-2xl hover:bg-rose-700 transition-all shadow-xl shadow-rose-100 uppercase text-xs tracking-widest">
                        YA, HAPUS
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- LOGIC -->
    <script>
        let deleteModal, addSubModal, editFieldModal, editSubModal;

        document.addEventListener('DOMContentLoaded', function() {
            deleteModal = new Modal(document.getElementById('deleteMasterModal'));
            addSubModal = new Modal(document.getElementById('addSubModal'));
            editFieldModal = new Modal(document.getElementById('editFieldModal'));
            editSubModal = new Modal(document.getElementById('editSubModal'));
        });

        // Hapus
        function openDeleteModal(url, name, count, type) {
            document.getElementById('deleteMasterForm').action = url;
            document.getElementById('del_target_name').textContent = name;
            document.getElementById('del_count').textContent = count;
            deleteModal.show();
        }

        // Tambah Sub
        function openAddSubModal(fieldId, fieldName) {
            document.getElementById('add_sub_field_id').value = fieldId;
            document.getElementById('add_sub_parent').textContent = fieldName;
            addSubModal.show();
        }

        // Edit Field
        function openEditFieldModal(id, name, code, order, desc) {
            document.getElementById('editFieldForm').action = `/admin/master-documents/field/${id}`;
            document.getElementById('edit_field_name').value = name;
            document.getElementById('edit_field_code').value = code;
            document.getElementById('edit_field_order').value = order;
            document.getElementById('edit_field_desc').value = desc;
            editFieldModal.show();
        }

        // Edit Subfield
        function openEditSubModal(id, name, max, req, desc) {
            document.getElementById('editSubForm').action = `/admin/master-documents/subfield/${id}`;
            document.getElementById('edit_sub_name').value = name;
            document.getElementById('edit_sub_max').value = max;
            document.getElementById('edit_sub_req').checked = (req == 1);
            document.getElementById('edit_sub_desc').value = desc;
            editSubModal.show();
        }
    </script>
</x-app-layout>
