<x-app-layout>
    <div class="max-w-6xl mx-auto">

        <!-- Header -->
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6 bg-white dark:bg-gray-800 p-4 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700">
            <div>
                <h2 class="text-2xl font-bold tracking-tight text-gray-900 dark:text-white">Repository Dokumen</h2>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Kelola dan pantau status verifikasi dokumen lingkungan Anda.</p>
            </div>
            <div class="mt-4 md:mt-0 flex flex-wrap gap-2">
                <!-- ESG Report Button -->
                <a href="{{ route('user.esg.create') }}" class="text-white bg-green-600 hover:bg-green-700 focus:ring-4 focus:ring-green-300 font-medium rounded-lg text-sm px-5 py-2.5 dark:bg-green-600 dark:hover:bg-green-700 focus:outline-none dark:focus:ring-green-800 flex items-center gap-2 transition shadow-md">
                    <svg class="w-4 h-4" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 1v3m5-3v3m5-3v3M1 7h18M3 5h14a2 2 0 0 1 2 2v11a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2V7a2 2 0 0 1 2-2Z"/>
                    </svg>
                    Buat Laporan ESG
                </a>
                <!-- Refresh Button -->
                <a href="{{ route('user.documents') }}" class="text-white bg-primary-700 hover:bg-primary-800 focus:ring-4 focus:ring-primary-300 font-medium rounded-lg text-sm px-5 py-2.5 dark:bg-primary-600 dark:hover:bg-primary-700 focus:outline-none dark:focus:ring-primary-800 flex items-center gap-2 transition shadow-md">
                    <svg class="w-4 h-4" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 18 20"><path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 1v5h-5M2 19v-5h5m10-4a8 8 0 0 1-14.947 3.97M1 10a8 8 0 0 1 14.947-3.97"/></svg>
                    Refresh Status
                </a>
            </div>
        </div>

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
                    <div class="p-5 border border-b-0 border-gray-200 dark:border-gray-700 dark:bg-gray-900">
                        <p class="mb-4 text-gray-500 dark:text-gray-400 text-sm">{{ $field->description }}</p>

                        <div class="relative overflow-x-auto shadow-md sm:rounded-lg mb-4">
                            <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
                                <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                                    <tr>
                                        <th scope="col" class="px-6 py-3 w-1/3">Jenis Dokumen</th>
                                        <th scope="col" class="px-6 py-3 text-center">Wajib?</th>
                                        <th scope="col" class="px-6 py-3 text-center">Status & Info</th>
                                        <th scope="col" class="px-6 py-3 text-center">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($field->subfields as $sub)
                                        @php $myDoc = $myDocuments[$sub->id] ?? null; @endphp
                                        <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">

                                            <!-- 1. Jenis Dokumen -->
                                            <td class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white align-top">
                                                <div class="flex items-center gap-2">
                                                    {{ $sub->name }}
                                                    @if($sub->is_custom) <span class="bg-blue-100 text-blue-800 text-[10px] font-semibold px-2 py-0.5 rounded">Custom</span> @endif
                                                </div>
                                                <div class="text-xs text-gray-500 font-normal mt-1">{{ $sub->description }}</div>
                                                <div class="text-xs text-gray-400 mt-0.5">Max: {{ $sub->max_size_mb }} MB</div>
                                            </td>

                                            <!-- 2. Wajib? -->
                                            <td class="px-6 py-4 text-center align-top">
                                                @if($sub->required)
                                                    <span class="bg-red-100 text-red-800 text-xs font-medium px-2.5 py-0.5 rounded dark:bg-red-900 dark:text-red-300">Wajib</span>
                                                @else
                                                    <span class="bg-gray-100 text-gray-800 text-xs font-medium px-2.5 py-0.5 rounded dark:bg-gray-700 dark:text-gray-300">Opsional</span>
                                                @endif
                                            </td>

                                            <!-- 3. Status & Info & REJECTION REASON -->
                                            <td class="px-6 py-4 align-top">
                                                @if($myDoc)
                                                    <div class="flex flex-col items-center gap-2">
                                                        <!-- Badge Status -->
                                                        @if($myDoc->status == 'approved')
                                                            <span class="bg-green-100 text-green-800 text-xs font-medium px-2.5 py-0.5 rounded border border-green-400">Disetujui</span>
                                                        @elseif($myDoc->status == 'rejected')
                                                            <span class="bg-red-100 text-red-800 text-xs font-medium px-2.5 py-0.5 rounded border border-red-400 animate-pulse">Ditolak</span>
                                                        @else
                                                            <span class="bg-yellow-100 text-yellow-800 text-xs font-medium px-2.5 py-0.5 rounded border border-yellow-300">Menunggu Verifikasi</span>
                                                        @endif

                                                        <!-- Nama File -->
                                                        <span class="text-xs font-bold text-gray-700 dark:text-gray-300 break-all text-center" title="{{ $myDoc->original_filename }}">
                                                            {{ Str::limit($myDoc->original_filename, 25) }}
                                                        </span>
                                                        <span class="text-[10px] text-gray-400">{{ number_format($myDoc->size_bytes_original / 1024, 0) }} KB</span>

                                                        <!-- ALASAN PENOLAKAN (PENTING) -->
                                                        @if($myDoc->status == 'rejected' && $myDoc->rejection_reason)
                                                            <div class="mt-2 p-2 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded text-left w-full">
                                                                <p class="text-xs font-bold text-red-600 dark:text-red-400 mb-1">Alasan Penolakan:</p>
                                                                <p class="text-xs text-gray-700 dark:text-gray-300 italic">"{{ $myDoc->rejection_reason }}"</p>
                                                            </div>
                                                        @endif
                                                    </div>
                                                @else
                                                    <div class="text-center">
                                                        <span class="bg-gray-100 text-gray-400 text-xs font-medium px-2.5 py-0.5 rounded dark:bg-gray-700 dark:text-gray-400 border border-gray-300">Belum Ada</span>
                                                    </div>
                                                @endif
                                            </td>

                                            <!-- 4. Aksi -->
                                            <td class="px-6 py-4 text-center align-top">
                                                <button data-modal-target="uploadModal" data-modal-toggle="uploadModal"
                                                        data-subfield-id="{{ $sub->id }}"
                                                        data-subfield-name="{{ $sub->name }}"
                                                        class="upload-btn font-medium text-white bg-blue-600 hover:bg-blue-700 focus:ring-4 focus:ring-blue-300 rounded-lg text-xs px-3 py-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800 shadow-sm transition-transform hover:-translate-y-0.5">
                                                    {{ $myDoc ? ($myDoc->status == 'rejected' ? 'Perbaiki' : 'Ganti File') : 'Upload' }}
                                                </button>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <!-- Tombol Tambah Dokumen Custom -->
                        <button data-modal-target="customUploadModal" data-modal-toggle="customUploadModal"
                                data-field-id="{{ $field->id }}"
                                data-field-name="{{ $field->name }}"
                                class="custom-upload-btn flex items-center gap-2 text-sm font-medium text-gray-500 hover:text-primary-600 dark:text-gray-400 dark:hover:text-white transition-colors border border-dashed border-gray-300 p-2 rounded-lg w-full justify-center hover:border-primary-500">
                            <svg class="w-4 h-4" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20"><path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 5.757v8.486M5.757 10h8.486M19 10a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/></svg>
                            Tambah Dokumen Lainnya di {{ $field->name }}
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
