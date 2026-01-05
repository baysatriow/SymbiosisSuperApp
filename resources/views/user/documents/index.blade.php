<x-app-layout>
    <div class="max-w-6xl mx-auto">

    <x-page-header 
        title="Repository Dokumen" 
        subtitle="Kelola dan pantau status verifikasi dokumen lingkungan Anda.">
        <x-slot:actions>
            <a href="{{ route('user.esg.create') }}" class="inline-flex items-center px-4 py-2 bg-primary-600 text-white rounded-xl text-xs font-bold hover:bg-primary-700 transition-all shadow-sm">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 13h6m-3-3v6m5 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                Buat Laporan ESG
            </a>
            <a href="{{ route('user.documents') }}" class="inline-flex items-center px-4 py-2 bg-white text-gray-700 border border-gray-100 rounded-xl text-xs font-bold hover:bg-gray-50 transition-all shadow-sm">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.586m10.343 1.172a8.5 8.5 0 11.037-5.437m1.51-3.251L14.636 1c-.135-.11-.3-.21-.493-.277a.972.972 0 00-.733.023l-3.376 2.052"></path></svg>
                Refresh
            </a>
        </x-slot:actions>
    </x-page-header>

        <!-- Accordion Container -->
        <div id="accordion-documents" data-accordion="collapse" data-active-classes="bg-primary-50 dark:bg-gray-800 text-primary-600 dark:text-white">

            @foreach($fields as $index => $field)
                <!-- Accordion Heading -->
                <h2 id="accordion-heading-{{ $field->id }}">
                    <button type="button" class="flex items-center justify-between w-full p-5 font-medium rtl:text-right text-gray-500 border border-b-0 border-gray-200 {{ $index == 0 ? 'rounded-t-xl' : '' }} focus:ring-4 focus:ring-primary-200 dark:focus:ring-primary-800 dark:border-gray-700 dark:text-gray-400 hover:bg-primary-50 dark:hover:bg-gray-800 gap-3 transition" data-accordion-target="#accordion-body-{{ $field->id }}" aria-expanded="{{ $index == 0 ? 'true' : 'false' }}" aria-controls="accordion-body-{{ $field->id }}">
                        <div class="flex items-center gap-3">
                            <span class="flex items-center justify-center w-8 h-8 bg-primary-100 text-primary-700 rounded-full text-sm font-bold">{{ $field->code }}</span>
                            <span class="text-lg">{{ $field->name }}</span>
                        </div>
                        <svg data-accordion-icon class="w-3 h-3 rotate-180 shrink-0" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 10 6"><path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5 5 1 1 5"/></svg>
                    </button>
                </h2>

                <!-- Accordion Body -->
                <div id="accordion-body-{{ $field->id }}" class="hidden" aria-labelledby="accordion-heading-{{ $field->id }}">
                    <div class="px-6 py-4 bg-white border-x border-gray-100">
                        <p class="mb-6 text-xs text-gray-400 font-medium leading-relaxed">{{ $field->description }}</p>

                        <div class="overflow-hidden rounded-2xl border border-gray-100 mb-6 shadow-sm">
                            <table class="w-full text-sm text-left text-gray-500">
                                <thead class="text-[10px] text-gray-400 uppercase tracking-widest bg-gray-50/50 border-b border-gray-100">
                                    <tr>
                                        <th class="px-6 py-4">Jenis Dokumen</th>
                                        <th class="px-6 py-4 text-center">Status</th>
                                        <th class="px-6 py-4 text-right">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-50">
                                    @foreach($field->subfields as $sub)
                                        @php $myDoc = $myDocuments[$sub->id] ?? null; @endphp
                                        <tr class="hover:bg-gray-50/50 transition-colors">
                                            <td class="px-6 py-4">
                                                <div class="flex items-center gap-2 mb-1">
                                                    <span class="font-bold text-gray-900">{{ $sub->name }}</span>
                                                    @if($sub->required)
                                                        <span class="text-[10px] font-bold text-red-500 uppercase">Wajib</span>
                                                    @endif
                                                </div>
                                                <div class="text-[10px] text-gray-400 font-medium max-w-sm">{{ $sub->description }}</div>
                                            </td>

                                            <td class="px-6 py-4 text-center">
                                                @if($myDoc)
                                                    <div class="inline-flex flex-col items-center">
                                                        @if($myDoc->status == 'approved')
                                                            <span class="bg-emerald-50 text-emerald-600 text-[10px] font-bold px-3 py-1 rounded-full uppercase border border-emerald-100">Disetujui</span>
                                                        @elseif($myDoc->status == 'rejected')
                                                            <span class="bg-red-50 text-red-600 text-[10px] font-bold px-3 py-1 rounded-full uppercase border border-red-100">Ditolak</span>
                                                        @else
                                                            <span class="bg-orange-50 text-orange-600 text-[10px] font-bold px-3 py-1 rounded-full uppercase border border-orange-100">Review</span>
                                                        @endif
                                                        
                                                        <span class="mt-2 text-[10px] font-bold text-gray-400 truncate max-w-[120px]" title="{{ $myDoc->original_filename }}">
                                                            {{ Str::limit($myDoc->original_filename, 15) }}
                                                        </span>

                                                        @if($myDoc->status == 'rejected' && $myDoc->rejection_reason)
                                                            <div class="mt-1 text-[9px] text-red-400 italic font-medium">"{{ Str::limit($myDoc->rejection_reason, 20) }}"</div>
                                                        @endif
                                                    </div>
                                                @else
                                                    <span class="text-[10px] font-bold text-gray-300 uppercase italic">Kosong</span>
                                                @endif
                                            </td>

                                            <td class="px-6 py-4 text-right">
                                                <button data-modal-target="uploadModal" data-modal-toggle="uploadModal"
                                                        data-subfield-id="{{ $sub->id }}"
                                                        data-subfield-name="{{ $sub->name }}"
                                                        class="upload-btn inline-flex items-center justify-center p-2 rounded-xl border border-gray-100 hover:bg-white hover:text-primary-600 hover:border-primary-100 transition-all active:scale-95 group">
                                                    <svg class="w-5 h-5 text-gray-300 group-hover:text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path></svg>
                                                </button>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <button data-modal-target="customUploadModal" data-modal-toggle="customUploadModal"
                                data-field-id="{{ $field->id }}"
                                data-field-name="{{ $field->name }}"
                                class="custom-upload-btn w-full py-4 border-2 border-dashed border-gray-100 rounded-2xl flex flex-col items-center justify-center text-gray-400 hover:border-primary-300 hover:text-primary-600 hover:bg-primary-50/30 transition-all group">
                            <svg class="w-8 h-8 mb-2 opacity-20 group-hover:opacity-100 transition-opacity" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                            <span class="text-[10px] font-bold uppercase tracking-widest">Tambah Dokumen Lainnya</span>
                        </button>

                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <!-- MODAL UPLOAD STANDARD -->
    <div id="uploadModal" tabindex="-1" aria-hidden="true" class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
        <div class="relative p-4 w-full max-w-md max-h-full">
            <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
                <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t dark:border-gray-600">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Upload Dokumen</h3>
                    <button type="button" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white" data-modal-toggle="uploadModal">
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 14 14"><path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/></svg>
                    </button>
                </div>
                <form action="{{ route('user.documents.upload') }}" method="POST" enctype="multipart/form-data" class="p-4 md:p-5" id="uploadForm">
                    @csrf
                    <input type="hidden" name="subfield_id" id="modal_subfield_id">

                    <div class="mb-4 p-3 bg-blue-50 dark:bg-blue-900/20 rounded-lg border border-blue-100 dark:border-blue-800">
                        <p class="text-xs text-blue-600 dark:text-blue-400 font-bold uppercase mb-1">Jenis Dokumen:</p>
                        <div id="modal_subfield_name" class="text-sm font-medium text-gray-900 dark:text-white">-</div>
                    </div>

                    <div class="mb-4">
                        <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white" for="document_file">Pilih File (PDF)</label>
                        <input class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 dark:text-gray-400 focus:outline-none dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400" id="document_file" name="document_file" type="file" accept=".pdf" required>
                        <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Maksimal 20MB.</p>
                    </div>

                    <!-- Field Rename File -->
                    <div class="mb-6">
                        <label for="display_name" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Nama File (Tampilan)</label>
                        <div class="flex">
                            <input type="text" id="display_name" name="display_name" class="rounded-none rounded-l-lg bg-gray-50 border text-gray-900 focus:ring-blue-500 focus:border-blue-500 block flex-1 min-w-0 w-full text-sm border-gray-300 p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white" placeholder="Nama file otomatis..." required>
                            <span class="inline-flex items-center px-3 text-sm text-gray-900 bg-gray-200 border border-e-0 border-gray-300 border-s-0 rounded-e-md dark:bg-gray-600 dark:text-gray-400 dark:border-gray-600">
                                .pdf
                            </span>
                        </div>
                        <p class="mt-1 text-xs text-gray-500">Anda bisa mengubah nama file agar lebih rapi.</p>
                    </div>

                    <div class="flex items-center border-t border-gray-200 rounded-b dark:border-gray-600 pt-4">
                        <button type="submit" class="text-white bg-primary-700 hover:bg-primary-800 focus:ring-4 focus:outline-none focus:ring-primary-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-primary-600 dark:hover:bg-primary-700 dark:focus:ring-primary-800 w-full">Upload & Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- MODAL CUSTOM UPLOAD -->
    <div id="customUploadModal" tabindex="-1" aria-hidden="true" class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
        <div class="relative p-4 w-full max-w-md max-h-full">
            <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
                <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t dark:border-gray-600">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Tambah Dokumen Lainnya</h3>
                    <button type="button" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white" data-modal-toggle="customUploadModal">
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 14 14"><path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/></svg>
                    </button>
                </div>
                <form action="{{ route('user.documents.custom') }}" method="POST" enctype="multipart/form-data" class="p-4 md:p-5">
                    @csrf
                    <input type="hidden" name="field_id" id="custom_modal_field_id">

                    <div class="mb-4 p-2 bg-gray-100 dark:bg-gray-700 rounded">
                        <span class="text-xs text-gray-500 uppercase">Kategori:</span>
                        <span id="custom_modal_field_name" class="font-bold text-sm ml-1 dark:text-white">-</span>
                    </div>

                    <div class="mb-4">
                        <label for="custom_name" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Jenis Dokumen (Label)</label>
                        <input type="text" name="custom_name" id="custom_name" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white" placeholder="Contoh: Surat Keterangan..." required>
                    </div>

                    <div class="mb-4">
                        <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white" for="custom_file_input">Pilih File (PDF)</label>
                        <input class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 dark:text-gray-400 focus:outline-none dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400" id="custom_file_input" name="document_file" type="file" accept=".pdf" required>
                    </div>

                    <!-- Field Rename File Custom -->
                    <div class="mb-6">
                        <label for="custom_display_name" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Nama File (Tampilan)</label>
                        <div class="flex">
                            <input type="text" id="custom_display_name" name="display_name" class="rounded-none rounded-l-lg bg-gray-50 border text-gray-900 focus:ring-blue-500 focus:border-blue-500 block flex-1 min-w-0 w-full text-sm border-gray-300 p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white" placeholder="Otomatis dari file..." required>
                            <span class="inline-flex items-center px-3 text-sm text-gray-900 bg-gray-200 border border-e-0 border-gray-300 border-s-0 rounded-e-md dark:bg-gray-600 dark:text-gray-400 dark:border-gray-600">
                                .pdf
                            </span>
                        </div>
                    </div>

                    <div class="flex items-center border-t border-gray-200 rounded-b dark:border-gray-600 pt-4">
                        <button type="submit" class="text-white bg-primary-700 hover:bg-primary-800 focus:ring-4 focus:outline-none focus:ring-primary-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-primary-600 dark:hover:bg-primary-700 dark:focus:ring-primary-800 w-full">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // 1. Setup Upload Standard
            const uploadBtns = document.querySelectorAll('.upload-btn');
            const modalSubfieldId = document.getElementById('modal_subfield_id');
            const modalSubfieldName = document.getElementById('modal_subfield_name');

            // Elements for Rename
            const fileInput = document.getElementById('document_file');
            const nameInput = document.getElementById('display_name');

            uploadBtns.forEach(btn => {
                btn.addEventListener('click', function() {
                    modalSubfieldId.value = this.getAttribute('data-subfield-id');
                    modalSubfieldName.textContent = this.getAttribute('data-subfield-name');
                    // Reset form
                    fileInput.value = '';
                    nameInput.value = '';
                });
            });

            // Logic: Auto-fill Name on File Select (Standard)
            fileInput.addEventListener('change', function() {
                if (this.files && this.files[0]) {
                    let name = this.files[0].name;
                    // Remove extension
                    name = name.replace(/\.pdf$/i, "");
                    nameInput.value = name;
                }
            });

            // 2. Setup Custom Upload
            const customUploadBtns = document.querySelectorAll('.custom-upload-btn');
            const customModalFieldId = document.getElementById('custom_modal_field_id');
            const customModalFieldName = document.getElementById('custom_modal_field_name');

            // Elements for Custom Rename
            const customFileInput = document.getElementById('custom_file_input');
            const customNameInput = document.getElementById('custom_display_name');

            customUploadBtns.forEach(btn => {
                btn.addEventListener('click', function() {
                    customModalFieldId.value = this.getAttribute('data-field-id');
                    customModalFieldName.textContent = this.getAttribute('data-field-name');
                    customFileInput.value = '';
                    customNameInput.value = '';
                });
            });

            // Logic: Auto-fill Name on File Select (Custom)
            customFileInput.addEventListener('change', function() {
                if (this.files && this.files[0]) {
                    let name = this.files[0].name;
                    name = name.replace(/\.pdf$/i, "");
                    customNameInput.value = name;
                }
            });
        });
    </script>
</x-app-layout>
