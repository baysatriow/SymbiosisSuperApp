<x-app-layout>
    <div class="max-w-6xl mx-auto">

        <!-- Header User Info -->
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6 bg-white p-4 rounded-lg border border-gray-200 dark:bg-gray-800 dark:border-gray-700 shadow-sm">
            <div>
                <div class="flex items-center gap-2 mb-1">
                    <a href="{{ route('admin.documents.users') }}" class="text-gray-500 hover:text-primary-600">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                    </a>
                    <h2 class="text-2xl font-bold tracking-tight text-gray-900 dark:text-white">{{ $targetUser->full_name }}</h2>
                </div>
                <p class="text-sm text-gray-500 dark:text-gray-400">{{ $targetUser->companyProfile->company_name ?? 'Perusahaan Belum Diisi' }} • {{ $targetUser->email }}</p>
            </div>

            <div class="flex gap-4 mt-4 md:mt-0 bg-gray-50 dark:bg-gray-700 p-2 rounded-lg">
                <div class="text-center px-2">
                    <span class="block text-lg font-bold text-green-600">{{ $stats['approved'] }}</span>
                    <span class="text-[10px] text-gray-500 uppercase font-bold">Approved</span>
                </div>
                <div class="text-center px-2 border-l border-gray-200 dark:border-gray-600">
                    <span class="block text-lg font-bold text-yellow-500">{{ $stats['pending'] }}</span>
                    <span class="text-[10px] text-gray-500 uppercase font-bold">Pending</span>
                </div>
                <div class="text-center px-2 border-l border-gray-200 dark:border-gray-600">
                    <span class="block text-lg font-bold text-red-600">{{ $stats['rejected'] }}</span>
                    <span class="text-[10px] text-gray-500 uppercase font-bold">Rejected</span>
                </div>
                <div class="text-center px-2 border-l border-gray-200 dark:border-gray-600">
                    <span class="block text-lg font-bold text-gray-700 dark:text-white">{{ $stats['total'] }}</span>
                    <span class="text-[10px] text-gray-500 uppercase font-bold">Total</span>
                </div>
            </div>
        </div>

        <!-- Accordion Container -->
        <div id="accordion-documents" data-accordion="collapse" data-active-classes="bg-primary-50 dark:bg-gray-800 text-primary-600 dark:text-white">

            @foreach($fields as $index => $field)
                <h2 id="accordion-heading-{{ $field->id }}">
                    <button type="button" class="flex items-center justify-between w-full p-5 font-medium rtl:text-right text-gray-500 border border-b-0 border-gray-200 {{ $index == 0 ? 'rounded-t-xl' : '' }} focus:ring-4 focus:ring-primary-200 dark:focus:ring-primary-800 dark:border-gray-700 dark:text-gray-400 hover:bg-primary-50 dark:hover:bg-gray-800 gap-3" data-accordion-target="#accordion-body-{{ $field->id }}" aria-expanded="{{ $index == 0 ? 'true' : 'false' }}" aria-controls="accordion-body-{{ $field->id }}">
                        <div class="flex items-center gap-3">
                            <span class="flex items-center justify-center w-8 h-8 bg-primary-100 text-primary-700 rounded-full text-sm font-bold">{{ $field->code }}</span>
                            <span class="text-lg">{{ $field->name }}</span>
                        </div>
                        <svg data-accordion-icon class="w-3 h-3 rotate-180 shrink-0" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 10 6"><path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5 5 1 1 5"/></svg>
                    </button>
                </h2>

                <div id="accordion-body-{{ $field->id }}" class="hidden" aria-labelledby="accordion-heading-{{ $field->id }}">
                    <div class="p-5 border border-b-0 border-gray-200 dark:border-gray-700 dark:bg-gray-900">
                        <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
                            <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
                                <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                                    <tr>
                                        <th class="px-6 py-3 w-1/3">Jenis Dokumen</th>
                                        <th class="px-6 py-3 text-center">Status</th>
                                        <th class="px-6 py-3">File Info</th>
                                        <th class="px-6 py-3 text-center">Aksi Admin</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($field->subfields as $sub)
                                        @php $doc = $userDocuments[$sub->id] ?? null; @endphp
                                        <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                                            <!-- 1. Jenis Dokumen (Dengan Hapus Custom) -->
                                            <td class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                                <div class="flex items-center gap-2">
                                                    {{ $sub->name }}
                                                    @if($sub->is_custom)
                                                        <span class="bg-purple-100 text-purple-800 text-[10px] font-semibold px-2 py-0.5 rounded">Custom</span>

                                                        <!-- TOMBOL HAPUS SUBFIELD CUSTOM (TRIGGER MODAL) -->
                                                        <button type="button"
                                                                data-modal-target="deleteSubfieldModal"
                                                                data-modal-toggle="deleteSubfieldModal"
                                                                data-sub-id="{{ $sub->id }}"
                                                                data-sub-name="{{ $sub->name }}"
                                                                class="delete-sub-btn text-red-400 hover:text-red-600 ml-1"
                                                                title="Hapus Jenis Dokumen Ini">
                                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                                        </button>
                                                    @endif
                                                </div>
                                                @if($sub->required) <span class="text-red-500 text-xs ml-1">*Wajib</span> @endif
                                            </td>

                                            <td class="px-6 py-4 text-center">
                                                @if($doc)
                                                    @if($doc->status == 'approved')
                                                        <span class="bg-green-100 text-green-800 text-xs font-medium px-2.5 py-0.5 rounded border border-green-400">Approved</span>
                                                    @elseif($doc->status == 'rejected')
                                                        <span class="bg-red-100 text-red-800 text-xs font-medium px-2.5 py-0.5 rounded border border-red-400">Rejected</span>
                                                    @else
                                                        <span class="bg-yellow-100 text-yellow-800 text-xs font-medium px-2.5 py-0.5 rounded border border-yellow-300 animate-pulse">Pending</span>
                                                    @endif
                                                @else
                                                    <span class="text-gray-400 text-xs italic">Belum ada</span>
                                                @endif
                                            </td>

                                            <td class="px-6 py-4">
                                                @if($doc)
                                                    <a href="{{ route('admin.documents.view', $doc->id) }}" target="_blank" class="text-blue-600 hover:underline text-xs font-bold block truncate max-w-[150px]" title="{{ $doc->original_filename }}">
                                                        {{ $doc->original_filename }}
                                                    </a>
                                                    <span class="text-xs text-gray-400 block">{{ number_format($doc->size_bytes_original / 1024, 0) }} KB | {{ $doc->created_at->format('d M Y') }}</span>

                                                    @if($doc->status == 'rejected' && $doc->rejection_reason)
                                                        <div class="mt-1 text-xs text-red-600 bg-red-50 p-1 rounded border border-red-200">
                                                            <strong>Alasan:</strong> {{ $doc->rejection_reason }}
                                                        </div>
                                                    @endif
                                                @else
                                                    -
                                                @endif
                                            </td>

                                            <td class="px-6 py-4 text-center">
                                                @if($doc)
                                                    <div class="flex justify-center gap-2">
                                                        <!-- View -->
                                                        <a href="{{ route('admin.documents.view', $doc->id) }}" target="_blank" class="p-2 text-gray-500 hover:text-blue-600 bg-gray-100 hover:bg-blue-50 rounded-lg" title="Lihat">
                                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                                        </a>

                                                        @if($doc->status !== 'approved')
                                                            <button type="button" data-modal-target="approveModal" data-modal-toggle="approveModal"
                                                                    data-doc-id="{{ $doc->id }}" data-doc-name="{{ $doc->original_filename }}"
                                                                    class="approve-btn p-2 text-green-500 hover:text-white bg-green-100 hover:bg-green-600 rounded-lg transition">
                                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                                            </button>
                                                        @endif

                                                        @if($doc->status !== 'rejected')
                                                            <button type="button" data-modal-target="rejectModal" data-modal-toggle="rejectModal"
                                                                    data-doc-id="{{ $doc->id }}" data-doc-name="{{ $doc->original_filename }}"
                                                                    class="reject-btn p-2 text-yellow-500 hover:text-white bg-yellow-100 hover:bg-yellow-600 rounded-lg transition">
                                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                                                            </button>
                                                        @endif

                                                        <button type="button" data-modal-target="deleteModal" data-modal-toggle="deleteModal"
                                                                data-doc-id="{{ $doc->id }}" data-doc-name="{{ $doc->original_filename }}"
                                                                class="delete-btn p-2 text-red-500 hover:text-white bg-red-100 hover:bg-red-600 rounded-lg transition">
                                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                                        </button>
                                                    </div>
                                                @else
                                                    <span class="text-gray-300">-</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <!-- MODAL APPROVE -->
    <div id="approveModal" tabindex="-1" class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
        <div class="relative p-4 w-full max-w-md max-h-full">
            <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
                <div class="p-4 md:p-5 text-center">
                    <div class="bg-green-100 w-12 h-12 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    </div>
                    <h3 class="mb-5 text-lg font-normal text-gray-500 dark:text-gray-400">Setujui dokumen <span id="approve_doc_name" class="font-bold"></span>?</h3>
                    <form id="approveForm" method="POST" action="">
                        @csrf
                        <div class="flex justify-center gap-3">
                            <button type="submit" class="text-white bg-green-600 hover:bg-green-800 focus:ring-4 focus:outline-none focus:ring-green-300 font-medium rounded-lg text-sm inline-flex items-center px-5 py-2.5 text-center">
                                Ya, Setujui
                            </button>
                            <button data-modal-hide="approveModal" type="button" class="py-2.5 px-5 ms-3 text-sm font-medium text-gray-900 focus:outline-none bg-white rounded-lg border border-gray-200 hover:bg-gray-100 hover:text-blue-700 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:text-white dark:hover:bg-gray-700">
                                Batal
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- MODAL REJECT -->
    <div id="rejectModal" tabindex="-1" class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
        <div class="relative p-4 w-full max-w-md max-h-full">
            <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
                <div class="p-4 md:p-5 text-center">
                    <div class="bg-red-100 w-12 h-12 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </div>
                    <h3 class="mb-2 text-lg font-normal text-gray-500 dark:text-gray-400">Tolak Dokumen <span id="reject_doc_name" class="font-bold"></span>?</h3>
                    <form id="rejectForm" method="POST" action="">
                        @csrf
                        <div class="mb-4 text-left">
                            <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Alasan Penolakan <span class="text-red-500">*</span></label>
                            <textarea name="rejection_reason" rows="3" class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-red-500 focus:border-red-500" placeholder="Contoh: Dokumen tidak terbaca..." required></textarea>
                        </div>
                        <div class="flex justify-center gap-3">
                            <button type="submit" class="text-white bg-red-600 hover:bg-red-800 focus:ring-4 focus:outline-none focus:ring-red-300 font-medium rounded-lg text-sm inline-flex items-center px-5 py-2.5 text-center">
                                Tolak Dokumen
                            </button>
                            <button data-modal-hide="rejectModal" type="button" class="py-2.5 px-5 ms-3 text-sm font-medium text-gray-900 focus:outline-none bg-white rounded-lg border border-gray-200 hover:bg-gray-100 hover:text-blue-700">
                                Batal
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- MODAL DELETE FILE -->
    <div id="deleteModal" tabindex="-1" class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
        <div class="relative p-4 w-full max-w-md max-h-full">
            <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
                <div class="p-4 md:p-5 text-center">
                    <div class="bg-gray-100 w-12 h-12 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-6 h-6 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                    </div>
                    <h3 class="mb-5 text-lg font-normal text-gray-500 dark:text-gray-400">Hapus permanen file <span id="delete_doc_name" class="font-bold"></span>?</h3>
                    <form id="deleteForm" method="POST" action="">
                        @csrf
                        @method('DELETE')
                        <div class="flex justify-center gap-3">
                            <button type="submit" class="text-white bg-gray-600 hover:bg-gray-800 focus:ring-4 focus:outline-none focus:ring-gray-300 font-medium rounded-lg text-sm inline-flex items-center px-5 py-2.5 text-center">
                                Ya, Hapus File
                            </button>
                            <button data-modal-hide="deleteModal" type="button" class="py-2.5 px-5 ms-3 text-sm font-medium text-gray-900 focus:outline-none bg-white rounded-lg border border-gray-200 hover:bg-gray-100 hover:text-blue-700">
                                Batal
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- MODAL DELETE SUBFIELD (NEW) -->
    <div id="deleteSubfieldModal" tabindex="-1" class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
        <div class="relative p-4 w-full max-w-md max-h-full">
            <div class="relative bg-white rounded-lg shadow dark:bg-gray-700 border-l-8 border-red-600">
                <div class="p-4 md:p-5 text-center">
                    <svg class="mx-auto mb-4 text-red-600 w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                    <h3 class="mb-2 text-lg font-bold text-gray-900 dark:text-white">Hapus Kategori Dokumen?</h3>
                    <p class="text-sm text-gray-500 mb-4">
                        Anda akan menghapus jenis dokumen custom: <br>
                        <b id="del_sub_name" class="text-gray-800 dark:text-white"></b>
                    </p>
                    <div class="bg-red-50 dark:bg-red-900/20 p-3 rounded-lg text-left mb-4 border border-red-100 dark:border-red-800">
                        <p class="text-xs text-red-700 dark:text-red-400 font-bold">Peringatan:</p>
                        <ul class="list-disc list-inside text-xs text-red-600 dark:text-red-300 mt-1">
                            <li>Jika ada file yang diupload di kategori ini, file tersebut akan <b>DIHAPUS PERMANEN</b>.</li>
                            <li>Tindakan ini tidak dapat dibatalkan.</li>
                        </ul>
                    </div>
                    <form id="deleteSubfieldForm" method="POST" action="">
                        @csrf
                        @method('DELETE')
                        <div class="flex justify-center gap-3">
                            <button data-modal-hide="deleteSubfieldModal" type="button" class="py-2.5 px-5 text-sm font-medium text-gray-900 focus:outline-none bg-white rounded-lg border border-gray-200 hover:bg-gray-100 hover:text-blue-700">
                                Batal
                            </button>
                            <button type="submit" class="text-white bg-red-600 hover:bg-red-800 focus:ring-4 font-medium rounded-lg text-sm px-5 py-2.5 text-center">
                                Ya, Hapus
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- JS Logic Modal -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // --- Modal Elements ---
            const approveModal = new Modal(document.getElementById('approveModal'));
            const rejectModal = new Modal(document.getElementById('rejectModal'));
            const deleteModal = new Modal(document.getElementById('deleteModal'));
            const deleteSubModal = new Modal(document.getElementById('deleteSubfieldModal'));

            // --- Approve File ---
            document.querySelectorAll('.approve-btn').forEach(btn => {
                btn.addEventListener('click', function() {
                    const id = this.getAttribute('data-doc-id');
                    document.getElementById('approve_doc_name').innerText = this.getAttribute('data-doc-name');
                    document.getElementById('approveForm').action = `/admin/documents/${id}/approve`;
                    approveModal.show();
                });
            });

            // --- Reject File ---
            document.querySelectorAll('.reject-btn').forEach(btn => {
                btn.addEventListener('click', function() {
                    const id = this.getAttribute('data-doc-id');
                    document.getElementById('reject_doc_name').innerText = this.getAttribute('data-doc-name');
                    document.getElementById('rejectForm').action = `/admin/documents/${id}/reject`;
                    rejectModal.show();
                });
            });

            // --- Delete File ---
            document.querySelectorAll('.delete-btn').forEach(btn => {
                btn.addEventListener('click', function() {
                    const id = this.getAttribute('data-doc-id');
                    document.getElementById('delete_doc_name').innerText = this.getAttribute('data-doc-name');
                    document.getElementById('deleteForm').action = `/admin/documents/${id}/delete`;
                    deleteModal.show();
                });
            });

            // --- Delete Subfield Custom ---
            document.querySelectorAll('.delete-sub-btn').forEach(btn => {
                btn.addEventListener('click', function() {
                    const id = this.getAttribute('data-sub-id');
                    const name = this.getAttribute('data-sub-name');

                    document.getElementById('del_sub_name').innerText = name;
                    document.getElementById('deleteSubfieldForm').action = `/admin/master-documents/subfield/${id}`;

                    deleteSubModal.show();
                });
            });

            // --- Close Modals (Universal handler for data-modal-hide) ---
            document.querySelectorAll('[data-modal-hide]').forEach(btn => {
                btn.addEventListener('click', function() {
                    const targetId = this.getAttribute('data-modal-hide');
                    const modalEl = document.getElementById(targetId);
                    if (modalEl) {
                        // Flowbite Instance Check
                        if (targetId === 'approveModal') approveModal.hide();
                        else if (targetId === 'rejectModal') rejectModal.hide();
                        else if (targetId === 'deleteModal') deleteModal.hide();
                        else if (targetId === 'deleteSubfieldModal') deleteSubModal.hide();
                        else {
                            // Fallback manual hide
                            modalEl.classList.add('hidden');
                            modalEl.setAttribute('aria-hidden', 'true');
                            document.body.classList.remove('overflow-hidden');
                        }
                    }
                });
            });
        });
    </script>
</x-app-layout>
