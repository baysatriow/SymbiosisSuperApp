<x-app-layout>
    <div class="space-y-8 animate-in fade-in duration-700">
        <!-- HEADER & USER PROFILE SUMMARY -->
        <div class="bg-white rounded-[2.5rem] border border-gray-100 shadow-xl shadow-gray-100/50 overflow-hidden">
            <div class="bg-gradient-to-r from-gray-900 via-gray-800 to-black p-8 md:p-10 flex flex-col md:flex-row justify-between items-center gap-8 relative overflow-hidden">
                <!-- Decorative pattern -->
                <div class="absolute inset-0 opacity-10 pointer-events-none">
                    <svg class="h-full w-full" viewBox="0 0 100 100" preserveAspectRatio="none">
                        <path d="M0 0 L100 0 L100 100 L0 100 Z" fill="url(#grad)" />
                        <defs><linearGradient id="grad" x1="0%" y1="0%" x2="100%" y2="100%"><stop offset="0%" style="stop-color:white;stop-opacity:0.1" /><stop offset="100%" style="stop-color:black;stop-opacity:0.1" /></linearGradient></defs>
                    </svg>
                </div>

                <div class="flex items-center gap-6 relative z-10">
                    <div class="w-20 h-20 rounded-[2rem] bg-white/10 backdrop-blur-md border border-white/20 flex items-center justify-center font-black text-3xl text-white shadow-2xl transition-transform hover:scale-105">
                        {{ substr($targetUser->full_name, 0, 1) }}
                    </div>
                    <div>
                        <div class="flex items-center gap-3">
                            <a href="{{ route('admin.documents.users') }}" class="w-8 h-8 rounded-full bg-white/10 hover:bg-white/20 flex items-center justify-center text-white transition-all">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"/></svg>
                            </a>
                            <h2 class="text-3xl font-black text-white tracking-tight uppercase">{{ $targetUser->full_name }}</h2>
                        </div>
                        <div class="flex flex-wrap items-center gap-3 mt-2">
                            <span class="px-3 py-1 rounded-full bg-primary-500/20 text-primary-300 text-[10px] font-black uppercase tracking-widest border border-primary-500/30">{{ $targetUser->companyProfile->company_name ?? 'NO COMPANY RECORD' }}</span>
                            <span class="text-gray-400 text-[10px] font-bold uppercase tracking-widest">•</span>
                            <span class="text-gray-400 text-[10px] font-bold uppercase tracking-widest">{{ $targetUser->email }}</span>
                        </div>
                    </div>
                </div>

                <div class="flex flex-wrap justify-center gap-4 relative z-10 bg-white/5 backdrop-blur-xl p-4 rounded-[2rem] border border-white/10 shadow-2xl">
                    <div class="text-center px-4 min-w-[70px]">
                        <span class="block text-2xl font-black text-emerald-400 leading-none">{{ $stats['approved'] }}</span>
                        <span class="text-[8px] text-emerald-300/60 uppercase font-black tracking-[0.2em] mt-2 block">Approved</span>
                    </div>
                    <div class="w-px h-10 bg-white/10 my-auto"></div>
                    <div class="text-center px-4 min-w-[70px]">
                        <span class="block text-2xl font-black text-amber-400 leading-none">{{ $stats['pending'] }}</span>
                        <span class="text-[8px] text-amber-300/60 uppercase font-black tracking-[0.2em] mt-2 block">Pending</span>
                    </div>
                    <div class="w-px h-10 bg-white/10 my-auto"></div>
                    <div class="text-center px-4 min-w-[70px]">
                        <span class="block text-2xl font-black text-rose-400 leading-none">{{ $stats['rejected'] }}</span>
                        <span class="text-[8px] text-rose-300/60 uppercase font-black tracking-[0.2em] mt-2 block">Rejected</span>
                    </div>
                    <div class="w-px h-10 bg-white/10 my-auto"></div>
                    <div class="text-center px-4 min-w-[70px]">
                        <span class="block text-2xl font-black text-white leading-none">{{ $stats['total'] }}</span>
                        <span class="text-[8px] text-white/40 uppercase font-black tracking-[0.2em] mt-2 block">Total</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- ACCORDION DOCUMENTS -->
        <div id="accordion-documents" data-accordion="collapse" class="space-y-4">
            @foreach($fields as $index => $field)
                <div class="bg-white border border-gray-100 rounded-[2rem] shadow-sm hover:shadow-md transition-all overflow-hidden border-t-4 border-t-primary-500/10">
                    <h2 id="accordion-heading-{{ $field->id }}">
                        <button type="button" class="flex items-center justify-between w-full p-6 bg-white hover:bg-gray-50/50 transition-all gap-4" data-accordion-target="#accordion-body-{{ $field->id }}" aria-expanded="{{ $index == 0 ? 'true' : 'false' }}">
                            <div class="flex items-center gap-5">
                                <div class="w-14 h-14 bg-gradient-to-br from-primary-500 to-primary-700 text-white rounded-2xl flex items-center justify-center font-black text-xl shadow-lg shadow-primary-100">
                                    {{ $field->code }}
                                </div>
                                <div class="text-left">
                                    <h3 class="text-lg font-black text-gray-900 tracking-tight uppercase">{{ $field->name }}</h3>
                                    <div class="flex items-center gap-2 mt-1">
                                        <span class="px-2 py-0.5 rounded-md bg-gray-100 text-gray-400 text-[9px] font-black uppercase border border-gray-200">{{ count($field->subfields) }} JENIS PERSYARATAN</span>
                                    </div>
                                </div>
                            </div>
                            <svg data-accordion-icon class="w-4 h-4 rotate-180 shrink-0 text-gray-400 transition-transform duration-300" fill="none" viewBox="0 0 10 6"><path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5 5 1 1 5"/></svg>
                        </button>
                    </h2>

                    <div id="accordion-body-{{ $field->id }}" class="hidden" aria-labelledby="accordion-heading-{{ $field->id }}">
                        <div class="p-4 md:p-8 bg-gray-50/30 border-t border-gray-50">
                            <div class="bg-white rounded-3xl border border-gray-100 shadow-sm overflow-hidden">
                                <table class="w-full text-sm text-left">
                                    <thead class="text-[10px] text-gray-400 uppercase tracking-widest bg-gray-50/50 border-b border-gray-50">
                                        <tr>
                                            <th class="px-6 py-4 font-black">Jenis Dokumen</th>
                                            <th class="px-6 py-4 font-black text-center">Status Review</th>
                                            <th class="px-6 py-4 font-black">Informasi Berkas</th>
                                            <th class="px-6 py-4 font-black text-center">Tindakan Admin</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-gray-50 bg-white">
                                        @foreach($field->subfields as $sub)
                                            @php $doc = $userDocuments[$sub->id] ?? null; @endphp
                                            <tr class="group hover:bg-gray-50/50 transition-all">
                                                <td class="px-6 py-5">
                                                    <div class="flex flex-col gap-1">
                                                        <div class="flex items-center gap-2">
                                                            <span class="font-bold text-gray-900">{{ $sub->name }}</span>
                                                            @if($sub->is_custom)
                                                                <span class="px-2 py-0.5 rounded-md bg-purple-50 text-purple-600 text-[8px] font-black uppercase border border-purple-100 shadow-sm">CUSTOM</span>
                                                                <button type="button" data-modal-target="deleteSubfieldModal" data-modal-toggle="deleteSubfieldModal"
                                                                        data-sub-id="{{ $sub->id }}" data-sub-name="{{ $sub->name }}"
                                                                        class="delete-sub-btn p-1 text-gray-300 hover:text-rose-500 hover:bg-rose-50 rounded-lg transition-all" title="Hapus Jenis Dokumen">
                                                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                                                </button>
                                                            @endif
                                                        </div>
                                                        @if($sub->required)
                                                            <span class="w-fit flex items-center gap-1.5 px-2 py-0.5 rounded-lg bg-rose-50 text-rose-600 text-[9px] font-black uppercase border border-rose-100">
                                                                <span class="w-1 h-1 rounded-full bg-rose-500 animate-pulse"></span>
                                                                WAJIB DIISI
                                                            </span>
                                                        @endif
                                                    </div>
                                                </td>

                                                <td class="px-6 py-5 text-center">
                                                    @if($doc)
                                                        @if($doc->status == 'approved')
                                                            <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-emerald-50 text-emerald-700 text-[10px] font-black uppercase border border-emerald-100 shadow-sm shadow-emerald-50">
                                                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                                                                APPROVED
                                                            </span>
                                                        @elseif($doc->status == 'rejected')
                                                            <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-rose-50 text-rose-700 text-[10px] font-black uppercase border border-rose-100 shadow-sm shadow-rose-50">
                                                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/></svg>
                                                                REJECTED
                                                            </span>
                                                        @else
                                                            <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-amber-50 text-amber-700 text-[10px] font-black uppercase border border-amber-200 shadow-sm shadow-amber-50 animate-pulse">
                                                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 8v4l3 2"/></svg>
                                                                PENDING REVIEW
                                                            </span>
                                                        @endif
                                                    @else
                                                        <span class="text-[10px] font-bold text-gray-300 uppercase tracking-widest italic">NOT UPLOADED</span>
                                                    @endif
                                                </td>

                                                <td class="px-6 py-5">
                                                    @if($doc)
                                                        <div class="flex flex-col gap-1.5">
                                                            <a href="{{ route('admin.documents.view', $doc->id) }}" target="_blank" class="text-xs font-black text-blue-600 hover:text-blue-800 transition-colors uppercase tracking-tight flex items-center gap-1.5 group/file">
                                                                <svg class="w-3.5 h-3.5 group-hover/file:rotate-12 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"/></svg>
                                                                {{ Str::limit($doc->original_filename, 20) }}
                                                            </a>
                                                            <div class="flex items-center gap-2">
                                                                <span class="text-[9px] font-black text-gray-400 bg-gray-100 px-1.5 py-0.5 rounded uppercase">{{ number_format($doc->size_bytes_original / 1024, 0) }} KB</span>
                                                                <span class="text-[9px] font-bold text-gray-400 uppercase tracking-tighter">{{ $doc->created_at->format('d/m/Y') }}</span>
                                                            </div>
                                                            @if($doc->status == 'rejected' && $doc->rejection_reason)
                                                                <div class="mt-2 text-[10px] text-rose-600 bg-rose-50/50 p-2.5 rounded-xl border border-rose-100 font-bold leading-relaxed">
                                                                    <div class="flex items-center gap-1.5 mb-1 opacity-60">
                                                                        <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path></svg>
                                                                        CATATAN REVISI:
                                                                    </div>
                                                                    {{ $doc->rejection_reason }}
                                                                </div>
                                                            @endif
                                                        </div>
                                                    @else
                                                        <span class="text-gray-300">-</span>
                                                    @endif
                                                </td>

                                                <td class="px-6 py-5">
                                                    @if($doc)
                                                        <div class="flex justify-center gap-2">
                                                            <a href="{{ route('admin.documents.view', $doc->id) }}" target="_blank" class="p-2.5 text-gray-400 hover:text-blue-600 bg-gray-50 hover:bg-blue-50 rounded-xl transition-all border border-gray-100" title="Preview">
                                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                                            </a>

                                                            @if($doc->status !== 'approved')
                                                                <button type="button" data-modal-target="approveModal" data-modal-toggle="approveModal"
                                                                        data-doc-id="{{ $doc->id }}" data-doc-name="{{ $doc->original_filename }}"
                                                                        class="approve-btn p-2.5 text-emerald-500 hover:text-white bg-emerald-50 hover:bg-emerald-600 rounded-xl transition-all border border-emerald-100" title="Setujui">
                                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                                                                </button>
                                                            @endif

                                                            @if($doc->status !== 'rejected')
                                                                <button type="button" data-modal-target="rejectModal" data-modal-toggle="rejectModal"
                                                                        data-doc-id="{{ $doc->id }}" data-doc-name="{{ $doc->original_filename }}"
                                                                        class="reject-btn p-2.5 text-amber-500 hover:text-white bg-amber-50 hover:bg-amber-600 rounded-xl transition-all border border-amber-100" title="Tolak / Revisi">
                                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                                                                </button>
                                                            @endif

                                                            <button type="button" data-modal-target="deleteModal" data-modal-toggle="deleteModal"
                                                                    data-doc-id="{{ $doc->id }}" data-doc-name="{{ $doc->original_filename }}"
                                                                    class="delete-btn p-2.5 text-rose-500 hover:text-white bg-rose-50 hover:bg-rose-600 rounded-xl transition-all border border-rose-100" title="Hapus File">
                                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                                            </button>
                                                        </div>
                                                    @else
                                                        <div class="flex justify-center items-center text-gray-200">
                                                            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM7 9a1 1 0 100-2 1 1 0 000 2zm7-1a1 1 0 11-2 0 1 1 0 012 0zm-7.53 6.47a.75.75 0 011.06 0L10 13.94l1.47 1.47a.75.75 0 11-1.06 1.06L10 15.06l-.47.47a.75.75 0 01-1.06-1.06l1.47-1.47-1.47-1.47a.75.75 0 010-1.06z" clip-rule="evenodd"></path></svg>
                                                        </div>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <!-- MODAL SECTION -->

    <!-- 1. MODAL APPROVE -->
    <div id="approveModal" tabindex="-1" aria-hidden="true" class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full transition-all duration-300 backdrop-blur-sm">
        <div class="relative p-6 w-full max-w-md max-h-full">
            <div class="relative bg-white rounded-[2.5rem] shadow-2xl border-4 border-white overflow-hidden p-10 text-center">
                <div class="w-24 h-24 bg-emerald-50 text-emerald-500 rounded-[2rem] flex items-center justify-center mx-auto mb-6 shadow-sm border border-emerald-100">
                    <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                </div>
                <h3 class="text-2xl font-black text-gray-900 uppercase tracking-tight mb-2">Setujui Berkas?</h3>
                <p class="text-gray-500 text-sm font-medium mb-8">Anda memvalidasi bahwa berkas <span id="approve_doc_name" class="text-emerald-600 font-black"></span> sudah sesuai persyaratan.</p>
                <form id="approveForm" method="POST" action="" class="flex gap-4">
                    @csrf
                    <button type="button" data-modal-hide="approveModal" class="flex-1 py-4 bg-gray-100 text-gray-600 font-black rounded-2xl hover:bg-gray-200 transition-all uppercase text-xs tracking-widest">BATAL</button>
                    <button type="submit" class="flex-1 py-4 bg-emerald-600 text-white font-black rounded-2xl hover:bg-emerald-700 transition-all shadow-xl shadow-emerald-100 uppercase text-xs tracking-widest">YA, SETUJUI</button>
                </form>
            </div>
        </div>
    </div>

    <!-- 2. MODAL REJECT -->
    <div id="rejectModal" tabindex="-1" aria-hidden="true" class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full transition-all duration-300 backdrop-blur-sm">
        <div class="relative p-6 w-full max-w-lg max-h-full">
            <div class="relative bg-white rounded-[2.5rem] shadow-2xl border-4 border-white overflow-hidden">
                <div class="flex items-center justify-between p-8 bg-gradient-to-r from-amber-500 to-amber-700 text-white">
                    <div>
                        <h3 class="text-xl font-black uppercase tracking-tight">Tolak & Minta Revisi</h3>
                        <p class="text-amber-100 text-xs font-medium mt-1" id="reject_doc_title">Berikan alasan mengapa berkas ini ditolak.</p>
                    </div>
                    <button type="button" class="w-10 h-10 flex items-center justify-center bg-white/20 hover:bg-white/30 rounded-2xl transition-all" data-modal-toggle="rejectModal">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 14 14"><path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/></svg>
                    </button>
                </div>
                <form id="rejectForm" method="POST" action="" class="p-8 space-y-6 bg-white">
                    @csrf
                    <div>
                        <label class="block mb-2 text-[10px] font-black text-gray-400 uppercase tracking-widest">Alasan Penolakan / Catatan Revisi</label>
                        <textarea name="rejection_reason" rows="4" class="w-full bg-gray-50/50 border border-gray-100 text-gray-900 text-sm font-bold rounded-2xl focus:ring-2 focus:ring-amber-500/20 focus:border-amber-500 block p-4 outline-none transition-all placeholder:text-gray-300" placeholder="Contoh: Dokumen tidak terbaca atau sudah kadaluarsa..." required></textarea>
                    </div>
                    <div class="flex gap-4">
                        <button type="button" data-modal-hide="rejectModal" class="flex-1 py-4 bg-gray-100 text-gray-600 font-black rounded-2xl hover:bg-gray-200 transition-all uppercase text-xs tracking-widest">BATAL</button>
                        <button type="submit" class="flex-2 py-4 px-8 bg-amber-600 text-white font-black rounded-2xl hover:bg-amber-700 transition-all shadow-xl shadow-amber-100 uppercase text-xs tracking-widest">KIRIM CATATAN TUNGGU REVISI</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- 3. MODAL DELETE FILE -->
    <div id="deleteModal" tabindex="-1" aria-hidden="true" class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full transition-all duration-300 backdrop-blur-sm">
        <div class="relative p-6 w-full max-w-md max-h-full">
            <div class="relative bg-white rounded-[2.5rem] shadow-2xl border-4 border-white overflow-hidden p-10 text-center">
                <div class="w-24 h-24 bg-rose-50 text-rose-500 rounded-[2rem] flex items-center justify-center mx-auto mb-6 shadow-sm border border-rose-100">
                    <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                </div>
                <h3 class="text-2xl font-black text-gray-900 uppercase tracking-tight mb-2">Hapus Berkas?</h3>
                <p class="text-gray-500 text-sm font-medium mb-8">Berkas <span id="delete_doc_name" class="text-rose-600 font-black"></span> akan dihapus permanen dari server.</p>
                <form id="deleteForm" method="POST" action="" class="flex gap-4">
                    @csrf
                    @method('DELETE')
                    <button type="button" data-modal-hide="deleteModal" class="flex-1 py-4 bg-gray-100 text-gray-600 font-black rounded-2xl hover:bg-gray-200 transition-all uppercase text-xs tracking-widest">BATAL</button>
                    <button type="submit" class="flex-1 py-4 bg-rose-600 text-white font-black rounded-2xl hover:bg-rose-700 transition-all shadow-xl shadow-rose-100 uppercase text-xs tracking-widest">HAPUS FILE</button>
                </form>
            </div>
        </div>
    </div>

    <!-- 4. MODAL DELETE SUBFIELD CUSTOM -->
    <div id="deleteSubfieldModal" tabindex="-1" aria-hidden="true" class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full transition-all duration-300 backdrop-blur-sm">
        <div class="relative p-6 w-full max-w-md max-h-full">
            <div class="relative bg-white rounded-[2.5rem] shadow-2xl border-4 border-white overflow-hidden p-10 text-center">
                <div class="w-24 h-24 bg-rose-50 text-rose-500 rounded-[2rem] flex items-center justify-center mx-auto mb-6 shadow-sm border border-rose-100">
                    <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                </div>
                <h3 class="text-2xl font-black text-gray-900 uppercase tracking-tight mb-2">Hapus Slot Dokumen?</h3>
                <p class="text-gray-500 text-sm font-medium mb-4">Anda akan menghapus slot berkas custom: <br><b id="del_sub_name" class="text-gray-800"></b></p>
                <div class="bg-rose-50 rounded-2xl p-4 text-left border border-rose-100 mb-8">
                    <p class="text-[10px] text-rose-600 font-bold leading-tight">PERINGATAN: Menghapus slot ini akan menghapus semua berkas fisik yang sudah diupload ke dalamnya oleh user ini.</p>
                </div>
                <form id="deleteSubfieldForm" method="POST" action="" class="flex gap-4">
                    @csrf
                    @method('DELETE')
                    <button type="button" data-modal-hide="deleteSubfieldModal" class="flex-1 py-4 bg-gray-100 text-gray-600 font-black rounded-2xl hover:bg-gray-200 transition-all uppercase text-xs tracking-widest">BATAL</button>
                    <button type="submit" class="flex-1 py-4 bg-rose-600 text-white font-black rounded-2xl hover:bg-rose-700 transition-all shadow-xl shadow-rose-100 uppercase text-xs tracking-widest">YA, HAPUS</button>
                </form>
            </div>
        </div>
    </div>

    <!-- JS Logic Modal -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const approveModal = new Modal(document.getElementById('approveModal'));
            const rejectModal = new Modal(document.getElementById('rejectModal'));
            const deleteModal = new Modal(document.getElementById('deleteModal'));
            const deleteSubModal = new Modal(document.getElementById('deleteSubfieldModal'));

            // Approve File
            document.querySelectorAll('.approve-btn').forEach(btn => {
                btn.addEventListener('click', function() {
                    const id = this.getAttribute('data-doc-id');
                    document.getElementById('approve_doc_name').innerText = this.getAttribute('data-doc-name');
                    document.getElementById('approveForm').action = `/admin/documents/${id}/approve`;
                    approveModal.show();
                });
            });

            // Reject File
            document.querySelectorAll('.reject-btn').forEach(btn => {
                btn.addEventListener('click', function() {
                    const id = this.getAttribute('data-doc-id');
                    document.getElementById('reject_doc_title').innerText = `Berkas: ${this.getAttribute('data-doc-name')}`;
                    document.getElementById('rejectForm').action = `/admin/documents/${id}/reject`;
                    rejectModal.show();
                });
            });

            // Delete File
            document.querySelectorAll('.delete-btn').forEach(btn => {
                btn.addEventListener('click', function() {
                    const id = this.getAttribute('data-doc-id');
                    document.getElementById('delete_doc_name').innerText = this.getAttribute('data-doc-name');
                    document.getElementById('deleteForm').action = `/admin/documents/${id}/delete`;
                    deleteModal.show();
                });
            });

            // Delete Subfield Custom
            document.querySelectorAll('.delete-sub-btn').forEach(btn => {
                btn.addEventListener('click', function() {
                    const id = this.getAttribute('data-sub-id');
                    const name = this.getAttribute('data-sub-name');
                    document.getElementById('del_sub_name').innerText = name;
                    document.getElementById('deleteSubfieldForm').action = `/admin/master-documents/subfield/${id}`;
                    deleteSubModal.show();
                });
            });

            // Close Modals
            document.querySelectorAll('[data-modal-hide]').forEach(btn => {
                btn.addEventListener('click', function() {
                    const targetId = this.getAttribute('data-modal-hide');
                    if (targetId === 'approveModal') approveModal.hide();
                    else if (targetId === 'rejectModal') rejectModal.hide();
                    else if (targetId === 'deleteModal') deleteModal.hide();
                    else if (targetId === 'deleteSubfieldModal') deleteSubModal.hide();
                });
            });
        });
    </script>
</x-app-layout>
