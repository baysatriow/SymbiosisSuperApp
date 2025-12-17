<x-app-layout>
    <div class="grid grid-cols-1 gap-6 mb-6">

        <!-- Header -->
        <div class="flex justify-between items-center p-4 bg-white border border-gray-200 rounded-xl shadow-sm dark:bg-gray-800 dark:border-gray-700">
            <div>
                <h2 class="text-2xl font-bold tracking-tight text-gray-900 dark:text-white">Master Data Dokumen</h2>
                <p class="text-sm text-gray-500 dark:text-gray-400">Kelola Kategori dan Jenis Dokumen yang berlaku di sistem.</p>
            </div>
            <button data-modal-target="addFieldModal" data-modal-toggle="addFieldModal" class="text-white bg-primary-700 hover:bg-primary-800 focus:ring-4 focus:ring-primary-300 font-medium rounded-lg text-sm px-5 py-2.5 flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
                Tambah Kategori Baru
            </button>
        </div>

        <!-- Accordion Fields -->
        <div id="accordion-master" data-accordion="collapse" class="space-y-4">
            @foreach($fields as $field)
                <div class="bg-white border border-gray-200 rounded-xl shadow-sm dark:bg-gray-800 dark:border-gray-700 overflow-hidden">

                    <!-- Header Kategori -->
                    <h2 id="accordion-heading-{{ $field->id }}">
                        <button type="button" class="flex items-center justify-between w-full p-5 font-medium rtl:text-right text-gray-500 hover:bg-gray-100 dark:hover:bg-gray-700 gap-3 transition" data-accordion-target="#accordion-body-{{ $field->id }}" aria-expanded="false" aria-controls="accordion-body-{{ $field->id }}">
                            <div class="flex items-center gap-4">
                                <span class="flex items-center justify-center w-10 h-10 bg-primary-100 text-primary-700 rounded-lg font-bold text-lg shadow-sm">
                                    {{ $field->code }}
                                </span>
                                <div class="text-left">
                                    <div class="text-lg font-bold text-gray-900 dark:text-white">{{ $field->name }}</div>
                                    <div class="text-xs text-gray-500">
                                        {{ $field->total_subfields }} Jenis Dokumen | {{ $field->total_docs }} File Terupload
                                    </div>
                                </div>
                            </div>
                            <svg data-accordion-icon class="w-3 h-3 rotate-180 shrink-0" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 10 6"><path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5 5 1 1 5"/></svg>
                        </button>
                    </h2>

                    <!-- Body Kategori -->
                    <div id="accordion-body-{{ $field->id }}" class="hidden" aria-labelledby="accordion-heading-{{ $field->id }}">
                        <div class="p-5 border-t border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900/50">

                            <!-- Action Bar Kategori -->
                            <div class="flex justify-between items-center mb-4 pb-4 border-b border-gray-200 dark:border-gray-700">
                                <div class="text-sm text-gray-500">{{ $field->description }}</div>
                                <div class="flex gap-2">
                                    <!-- Tombol Edit Field -->
                                    <button type="button"
                                            onclick="openEditFieldModal('{{ $field->id }}', '{{ $field->name }}', '{{ $field->code }}', '{{ $field->sort_order }}', '{{ $field->description }}')"
                                            class="px-3 py-1.5 text-xs font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700 focus:ring-4 focus:ring-blue-300">
                                        Edit Kategori
                                    </button>
                                    <!-- Tombol Hapus Field -->
                                    <button type="button" onclick="openDeleteModal('{{ route('admin.master.field.destroy', $field->id) }}', 'Kategori {{ $field->name }}', {{ $field->total_docs }}, 'Field')" class="px-3 py-1.5 text-xs font-medium text-white bg-red-600 rounded-lg hover:bg-red-700 focus:ring-4 focus:ring-red-300">Hapus Kategori</button>
                                </div>
                            </div>

                            <!-- Tabel Subfields -->
                            <div class="relative overflow-x-auto shadow-sm sm:rounded-lg border border-gray-200 dark:border-gray-700">
                                <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                                    <thead class="text-xs text-gray-700 uppercase bg-gray-100 dark:bg-gray-700 dark:text-gray-400">
                                        <tr>
                                            <th scope="col" class="px-4 py-3">Nama Dokumen</th>
                                            <th scope="col" class="px-4 py-3 text-center">Wajib?</th>
                                            <th scope="col" class="px-4 py-3 text-center">Max Size</th>
                                            <th scope="col" class="px-4 py-3 text-center">Total Upload</th>
                                            <th scope="col" class="px-4 py-3 text-center">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white dark:bg-gray-800">
                                        @foreach($field->subfields as $sub)
                                            <tr class="border-b dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                                                <td class="px-4 py-3 font-medium text-gray-900 dark:text-white">
                                                    {{ $sub->name }}
                                                    @if($sub->is_custom)
                                                        <span class="ml-2 bg-purple-100 text-purple-800 text-[10px] px-2 py-0.5 rounded font-bold">CUSTOM (User)</span>
                                                    @endif
                                                    <div class="text-xs text-gray-500 font-normal truncate max-w-xs">{{ $sub->description }}</div>
                                                </td>
                                                <td class="px-4 py-3 text-center">
                                                    @if($sub->required)
                                                        <span class="text-red-600 font-bold text-xs">YA</span>
                                                    @else
                                                        <span class="text-gray-400 text-xs">TIDAK</span>
                                                    @endif
                                                </td>
                                                <td class="px-4 py-3 text-center">{{ $sub->max_size_mb }} MB</td>
                                                <td class="px-4 py-3 text-center">
                                                    <span class="bg-blue-100 text-blue-800 text-xs font-bold px-2.5 py-0.5 rounded">{{ $sub->documents_count }}</span>
                                                </td>
                                                <td class="px-4 py-3 text-center">
                                                    @if(!$sub->is_custom)
                                                        <!-- Tombol Edit Subfield -->
                                                        <button type="button"
                                                                onclick="openEditSubModal('{{ $sub->id }}', '{{ $sub->name }}', '{{ $sub->max_size_mb }}', '{{ $sub->required }}', '{{ $sub->description }}')"
                                                                class="text-blue-600 hover:underline text-xs mr-2 font-bold">
                                                            Edit
                                                        </button>
                                                    @endif
                                                    <button type="button" onclick="openDeleteModal('{{ route('admin.master.subfield.destroy', $sub->id) }}', 'Dokumen {{ $sub->name }}', {{ $sub->documents_count }}, 'Subfield')" class="text-red-600 hover:underline text-xs font-bold">Hapus</button>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>

                            <div class="mt-4">
                                <button type="button" onclick="openAddSubModal('{{ $field->id }}', '{{ $field->name }}')" class="flex items-center gap-1 text-xs font-bold text-primary-700 hover:underline">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                                    Tambah Jenis Dokumen di Kategori {{ $field->code }}
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
    <div id="addFieldModal" tabindex="-1" aria-hidden="true" class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
        <div class="relative p-4 w-full max-w-md max-h-full">
            <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
                <div class="flex items-center justify-between p-4 border-b rounded-t dark:border-gray-600"><h3 class="text-lg font-semibold text-gray-900 dark:text-white">Tambah Kategori</h3><button type="button" class="text-gray-400 bg-transparent hover:bg-gray-200 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center" data-modal-toggle="addFieldModal"><svg class="w-3 h-3" fill="none" viewBox="0 0 14 14"><path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/></svg></button></div>
                <form action="{{ route('admin.master.field.store') }}" method="POST" class="p-4">
                    @csrf
                    <div class="grid gap-4 mb-4 grid-cols-2">
                        <div class="col-span-2 sm:col-span-1"><label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Kode (ex: A)</label><input type="text" name="code" class="bg-gray-50 border border-gray-300 text-sm rounded-lg block w-full p-2.5" required></div>
                        <div class="col-span-2 sm:col-span-1"><label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Urutan</label><input type="number" name="sort_order" class="bg-gray-50 border border-gray-300 text-sm rounded-lg block w-full p-2.5" value="1" required></div>
                        <div class="col-span-2"><label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Nama Kategori</label><input type="text" name="name" class="bg-gray-50 border border-gray-300 text-sm rounded-lg block w-full p-2.5" required></div>
                        <div class="col-span-2"><label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Deskripsi</label><textarea name="description" rows="2" class="block p-2.5 w-full text-sm bg-gray-50 rounded-lg border border-gray-300"></textarea></div>
                    </div>
                    <button type="submit" class="text-white bg-primary-700 hover:bg-primary-800 font-medium rounded-lg text-sm px-5 py-2.5 w-full">Simpan</button>
                </form>
            </div>
        </div>
    </div>

    <!-- 2. MODAL EDIT FIELD (NEW) -->
    <div id="editFieldModal" tabindex="-1" aria-hidden="true" class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
        <div class="relative p-4 w-full max-w-md max-h-full">
            <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
                <div class="flex items-center justify-between p-4 border-b rounded-t dark:border-gray-600">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Edit Kategori</h3>
                    <button type="button" class="text-gray-400 bg-transparent hover:bg-gray-200 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center" data-modal-hide="editFieldModal">
                        <svg class="w-3 h-3" fill="none" viewBox="0 0 14 14"><path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/></svg>
                    </button>
                </div>
                <form id="editFieldForm" method="POST" class="p-4">
                    @csrf
                    @method('PUT')
                    <div class="grid gap-4 mb-4 grid-cols-2">
                        <div class="col-span-2 sm:col-span-1"><label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Kode</label><input type="text" name="code" id="edit_field_code" class="bg-gray-50 border border-gray-300 text-sm rounded-lg block w-full p-2.5" required></div>
                        <div class="col-span-2 sm:col-span-1"><label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Urutan</label><input type="number" name="sort_order" id="edit_field_order" class="bg-gray-50 border border-gray-300 text-sm rounded-lg block w-full p-2.5" required></div>
                        <div class="col-span-2"><label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Nama Kategori</label><input type="text" name="name" id="edit_field_name" class="bg-gray-50 border border-gray-300 text-sm rounded-lg block w-full p-2.5" required></div>
                        <div class="col-span-2"><label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Deskripsi</label><textarea name="description" id="edit_field_desc" rows="2" class="block p-2.5 w-full text-sm bg-gray-50 rounded-lg border border-gray-300"></textarea></div>
                    </div>
                    <button type="submit" class="text-white bg-blue-700 hover:bg-blue-800 font-medium rounded-lg text-sm px-5 py-2.5 w-full">Update Kategori</button>
                </form>
            </div>
        </div>
    </div>

    <!-- 3. MODAL TAMBAH SUBFIELD -->
    <div id="addSubModal" tabindex="-1" aria-hidden="true" class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
        <div class="relative p-4 w-full max-w-md max-h-full">
            <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
                <div class="flex items-center justify-between p-4 border-b rounded-t dark:border-gray-600"><h3 class="text-lg font-semibold text-gray-900 dark:text-white">Tambah Jenis Dokumen di <span id="add_sub_parent"></span></h3><button type="button" class="text-gray-400 bg-transparent hover:bg-gray-200 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center" data-modal-hide="addSubModal"><svg class="w-3 h-3" fill="none" viewBox="0 0 14 14"><path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/></svg></button></div>
                <form action="{{ route('admin.master.subfield.store') }}" method="POST" class="p-4">
                    @csrf
                    <input type="hidden" name="field_id" id="add_sub_field_id">
                    <div class="grid gap-4 mb-4 grid-cols-2">
                        <div class="col-span-2"><label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Nama Dokumen</label><input type="text" name="name" class="bg-gray-50 border border-gray-300 text-sm rounded-lg block w-full p-2.5" required></div>
                        <div class="col-span-2 sm:col-span-1"><label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Max Size (MB)</label><input type="number" name="max_size_mb" class="bg-gray-50 border border-gray-300 text-sm rounded-lg block w-full p-2.5" value="10" required></div>
                        <div class="col-span-2 sm:col-span-1 flex items-center pt-6"><input id="req_check" type="checkbox" name="required" class="w-4 h-4 text-primary-600 bg-gray-100 border-gray-300 rounded focus:ring-primary-500"><label for="req_check" class="ms-2 text-sm font-medium text-gray-900 dark:text-gray-300">Wajib Diisi</label></div>
                        <div class="col-span-2"><label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Deskripsi</label><textarea name="description" rows="2" class="block p-2.5 w-full text-sm bg-gray-50 rounded-lg border border-gray-300"></textarea></div>
                    </div>
                    <button type="submit" class="text-white bg-primary-700 hover:bg-primary-800 font-medium rounded-lg text-sm px-5 py-2.5 w-full">Simpan</button>
                </form>
            </div>
        </div>
    </div>

    <!-- 4. MODAL EDIT SUBFIELD (NEW) -->
    <div id="editSubModal" tabindex="-1" aria-hidden="true" class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
        <div class="relative p-4 w-full max-w-md max-h-full">
            <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
                <div class="flex items-center justify-between p-4 border-b rounded-t dark:border-gray-600">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Edit Jenis Dokumen</h3>
                    <button type="button" class="text-gray-400 bg-transparent hover:bg-gray-200 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center" data-modal-hide="editSubModal">
                        <svg class="w-3 h-3" fill="none" viewBox="0 0 14 14"><path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/></svg>
                    </button>
                </div>
                <form id="editSubForm" method="POST" class="p-4">
                    @csrf
                    @method('PUT')
                    <div class="grid gap-4 mb-4 grid-cols-2">
                        <div class="col-span-2"><label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Nama Dokumen</label><input type="text" name="name" id="edit_sub_name" class="bg-gray-50 border border-gray-300 text-sm rounded-lg block w-full p-2.5" required></div>
                        <div class="col-span-2 sm:col-span-1"><label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Max Size (MB)</label><input type="number" name="max_size_mb" id="edit_sub_max" class="bg-gray-50 border border-gray-300 text-sm rounded-lg block w-full p-2.5" required></div>
                        <div class="col-span-2 sm:col-span-1 flex items-center pt-6">
                            <input id="edit_sub_req" type="checkbox" name="required" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500">
                            <label for="edit_sub_req" class="ms-2 text-sm font-medium text-gray-900 dark:text-gray-300">Wajib Diisi</label>
                        </div>
                        <div class="col-span-2"><label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Deskripsi</label><textarea name="description" id="edit_sub_desc" rows="2" class="block p-2.5 w-full text-sm bg-gray-50 rounded-lg border border-gray-300"></textarea></div>
                    </div>
                    <button type="submit" class="text-white bg-blue-700 hover:bg-blue-800 font-medium rounded-lg text-sm px-5 py-2.5 w-full">Update Dokumen</button>
                </form>
            </div>
        </div>
    </div>

    <!-- 5. MODAL DELETE (WARNING KERAS) -->
    <div id="deleteMasterModal" tabindex="-1" aria-hidden="true" class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
        <div class="relative p-4 w-full max-w-md max-h-full">
            <div class="relative bg-white rounded-lg shadow dark:bg-gray-700 border-l-8 border-red-600">
                <div class="p-4 md:p-5 text-center">
                    <svg class="mx-auto mb-4 text-red-600 w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                    <h3 class="mb-2 text-lg font-bold text-gray-900 dark:text-white">PERINGATAN HAPUS DATA</h3>
                    <p class="text-sm text-gray-500 mb-2">Anda akan menghapus <b id="del_target_name"></b>.</p>
                    <div class="bg-red-50 dark:bg-red-900/20 p-3 rounded-lg text-left mb-4 border border-red-100 dark:border-red-800">
                        <p class="text-xs text-red-700 dark:text-red-400 font-bold">Dampak Tindakan:</p>
                        <ul class="list-disc list-inside text-xs text-red-600 dark:text-red-300 mt-1">
                            <li>Item ini akan hilang permanen dari master data.</li>
                            <li><span id="del_count" class="font-bold">0</span> Dokumen user yang terkait akan IKUT TERHAPUS (Hilang dari penyimpanan).</li>
                            <li>Tindakan ini tidak dapat dibatalkan.</li>
                        </ul>
                    </div>
                    <form id="deleteMasterForm" method="POST" action="">
                        @csrf
                        @method('DELETE')
                        <div class="flex justify-center gap-3">
                            <button data-modal-hide="deleteMasterModal" type="button" class="py-2.5 px-5 text-sm font-medium text-gray-900 focus:outline-none bg-white rounded-lg border border-gray-200 hover:bg-gray-100 hover:text-blue-700">Batal</button>
                            <button type="submit" class="text-white bg-red-600 hover:bg-red-800 focus:ring-4 font-medium rounded-lg text-sm px-5 py-2.5 text-center">Ya, Hapus & Bersihkan Data</button>
                        </div>
                    </form>
                </div>
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
