<x-app-layout>
    <!-- Tampilkan Error Validasi (khusus untuk create template duplicate) -->
    @if ($errors->any())
        <div class="mb-4 p-4 text-sm text-red-800 rounded-lg bg-red-50 dark:bg-gray-800 dark:text-red-400 border border-red-200" role="alert">
            <ul class="list-disc list-inside">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="grid grid-cols-1 xl:grid-cols-3 gap-6 mb-6">

        <!-- KOLOM KIRI: Template & Editor -->
        <div class="xl:col-span-1 space-y-6">

            <!-- 1. DAFTAR TEMPLATE -->
            <div class="p-6 bg-white border border-gray-200 rounded-lg shadow-sm dark:bg-gray-800 dark:border-gray-700">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-sm font-bold text-gray-900 dark:text-white uppercase">Template Tersimpan</h3>
                    <button data-modal-target="addTemplateModal" data-modal-toggle="addTemplateModal" class="text-xs text-primary-600 hover:underline flex items-center gap-1">
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                        Tambah Baru
                    </button>
                </div>
                <ul class="space-y-2 max-h-48 overflow-y-auto pr-1 custom-scrollbar">
                    @forelse($templates as $t)
                        <li class="flex justify-between items-center bg-gray-50 dark:bg-gray-700 p-2.5 rounded-lg border border-gray-200 dark:border-gray-600 group">
                            <button type="button" onclick="applyTemplate('{{ e($t->content) }}')" class="text-sm font-medium text-gray-700 dark:text-gray-200 truncate flex-1 text-left hover:text-primary-600 transition-colors" title="Klik untuk gunakan">
                                {{ $t->name }}
                            </button>
                            <!-- FIX: Hapus data-modal-toggle disini agar tidak bentrok dengan JS -->
                            <button type="button"
                                    data-id="{{ $t->id }}"
                                    data-name="{{ $t->name }}"
                                    class="delete-template-btn text-gray-400 hover:text-red-500 p-1 opacity-0 group-hover:opacity-100 transition-opacity">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                            </button>
                        </li>
                    @empty
                        <li class="text-xs text-gray-500 text-center italic py-4">Belum ada template. Buat baru di atas.</li>
                    @endforelse
                </ul>
            </div>

            <!-- 2. FORM BUAT PESAN -->
            <div class="p-6 bg-white border border-gray-200 rounded-lg shadow-sm dark:bg-gray-800 dark:border-gray-700 sticky top-24">
                <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-4">Buat Pesan</h3>

                <form id="broadcastForm" action="{{ route('admin.broadcast.send') }}" method="POST">
                    @csrf

                    <!-- Toggle Kirim Semua -->
                    <div class="mb-4 p-4 bg-blue-50 dark:bg-blue-900/30 rounded-lg border border-blue-200 dark:border-blue-800">
                        <label class="inline-flex items-center cursor-pointer">
                            <input type="checkbox" name="send_all" id="send_all_toggle" value="1" class="sr-only peer">
                            <div class="relative w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 dark:peer-focus:ring-blue-800 rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-blue-600"></div>
                            <span class="ms-3 text-sm font-bold text-gray-900 dark:text-gray-300">Kirim ke SELURUH Pengguna</span>
                        </label>
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-2">
                            Aktifkan untuk mengirim ke <b>{{ $totalUsers }}</b> pengguna terdaftar.
                        </p>
                    </div>

                    <!-- Dropdown Template -->
                    <div class="mb-4">
                        <label for="template_selector" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Gunakan Template</label>
                        <select id="template_selector" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white">
                            <option value="">-- Pilih Cepat --</option>
                            @foreach($templates as $t)
                                <option value="{{ $t->content }}">{{ $t->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Textarea Pesan -->
                    <div class="mb-2">
                        <label for="message_content" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Isi Pesan WhatsApp</label>
                        <textarea id="message_content" name="message" rows="8" class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white font-mono" placeholder="Halo {name},..." required></textarea>
                    </div>

                    <!-- Kamus Variabel (Accordion) -->
                    <div id="accordion-variables" data-accordion="collapse" class="mb-6">
                        <h2 id="accordion-heading-vars">
                            <button type="button" class="flex items-center justify-between w-full p-3 font-medium rtl:text-right text-gray-500 border border-gray-200 rounded-t-xl focus:ring-4 focus:ring-gray-200 dark:focus:ring-gray-800 dark:border-gray-700 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-800 gap-3" data-accordion-target="#accordion-body-vars" aria-expanded="false" aria-controls="accordion-body-vars">
                                <span class="flex items-center gap-2 text-xs">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                    Kamus Variabel
                                </span>
                                <svg data-accordion-icon class="w-3 h-3 rotate-180 shrink-0" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 10 6"><path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5 5 1 1 5"/></svg>
                            </button>
                        </h2>
                        <div id="accordion-body-vars" class="hidden" aria-labelledby="accordion-heading-vars">
                            <div class="p-3 border border-t-0 border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900 max-h-60 overflow-y-auto text-xs">
                                <table class="w-full text-left">
                                    <tbody class="text-gray-600 dark:text-gray-400 font-mono">
                                        <tr><td class="py-1 text-blue-600 cursor-pointer hover:underline" onclick="insertVar('{name}')">{name}</td><td>Nama Lengkap</td></tr>
                                        <tr><td class="py-1 text-blue-600 cursor-pointer hover:underline" onclick="insertVar('{name}')">{name}</td><td>Nama User</td></tr>
                                        <tr><td class="py-1 text-blue-600 cursor-pointer hover:underline" onclick="insertVar('{username}')">{username}</td><td>Username</td></tr>
                                        <tr><td class="py-1 text-blue-600 cursor-pointer hover:underline" onclick="insertVar('{email}')">{email}</td><td>Email</td></tr>
                                        <tr><td class="py-1 text-blue-600 cursor-pointer hover:underline" onclick="insertVar('{phone}')">{phone}</td><td>No. HP</td></tr>
                                        <tr><td class="py-1 text-blue-600 cursor-pointer hover:underline" onclick="insertVar('{status}')">{status}</td><td>Status</td></tr>
                                        <tr><td class="py-1 text-blue-600 cursor-pointer hover:underline" onclick="insertVar('{join_date}')">{join_date}</td><td>Tgl Join</td></tr>
                                        <tr><td class="pt-2 text-blue-600 cursor-pointer hover:underline" onclick="insertVar('{company_full}')">{company_full}</td><td class="pt-2">Nama PT</td></tr>
                                        <tr><td class="py-1 text-blue-600 cursor-pointer hover:underline" onclick="insertVar('{job_title}')">{job_title}</td><td>Jabatan</td></tr>
                                        <tr><td class="py-1 text-blue-600 cursor-pointer hover:underline" onclick="insertVar('{city}')">{city}</td><td>Kota</td></tr>
                                        <tr><td class="pt-2 text-blue-600 cursor-pointer hover:underline" onclick="insertVar('{link_symbiosis}')">{link_symbiosis}</td><td class="pt-2">Link Web</td></tr>
                                        <tr><td class="py-1 text-blue-600 cursor-pointer hover:underline" onclick="insertVar('{nama_system}')">{nama_system}</td><td>Nm Sistem</td></tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <div id="selected_users_container"></div>

                    <!-- Trigger Modal Kirim -->
                    <button type="button" id="btn-trigger-confirm" class="w-full text-white bg-green-600 hover:bg-green-700 focus:ring-4 focus:outline-none focus:ring-green-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-green-600 dark:hover:bg-green-700 dark:focus:ring-green-800 flex justify-center items-center gap-2 shadow-lg disabled:opacity-50 disabled:cursor-not-allowed" disabled>
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path></svg>
                        Kirim Broadcast (<span id="count-display">0</span>)
                    </button>
                </form>
            </div>
        </div>

        <!-- KOLOM KANAN: Daftar Pengguna (Tabel) -->
        <div class="xl:col-span-2">
            <div class="bg-white border border-gray-200 rounded-lg shadow-sm dark:bg-gray-800 dark:border-gray-700 flex flex-col h-full">
                <div class="p-4 border-b border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800 rounded-t-lg flex flex-col sm:flex-row justify-between items-center gap-4">
                    <div>
                        <h3 class="text-lg font-bold text-gray-900 dark:text-white">Pilih Penerima</h3>
                        <p class="text-sm text-gray-500">Total User: {{ $totalUsers }}</p>
                    </div>
                    <form action="{{ route('admin.broadcast.index') }}" method="GET" class="flex gap-2 w-full sm:w-auto">
                        <input type="text" name="search" value="{{ request('search') }}" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white" placeholder="Cari User...">
                        <button type="submit" class="p-2.5 ms-2 text-sm font-medium text-white bg-primary-700 rounded-lg hover:bg-primary-800">
                            <svg class="w-4 h-4" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20"><path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z"/></svg>
                        </button>
                    </form>
                </div>

                <div class="flex-1 overflow-x-auto max-h-[600px] overflow-y-auto custom-scrollbar relative" id="table-container">
                    <div id="table-overlay" class="absolute inset-0 bg-gray-100/80 dark:bg-gray-900/80 z-20 hidden flex flex-col items-center justify-center text-center backdrop-blur-sm">
                        <div class="bg-white dark:bg-gray-800 p-6 rounded-xl shadow-2xl border border-gray-200 dark:border-gray-700 max-w-sm mx-auto">
                            <div class="w-16 h-16 bg-blue-100 text-blue-600 rounded-full flex items-center justify-center mx-auto mb-4">
                                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            </div>
                            <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-2">Mode Global Aktif</h3>
                            <p class="text-sm text-gray-500 dark:text-gray-400">
                                Pesan akan dikirim ke <b>{{ $totalUsers }} pengguna</b> sekaligus.
                                <br>Fitur pilih manual dinonaktifkan.
                            </p>
                        </div>
                    </div>

                    <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                        <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400 sticky top-0 z-10 shadow-sm">
                            <tr>
                                <th scope="col" class="p-4 w-4 text-center">
                                    <input id="checkbox-all" type="checkbox" class="w-4 h-4 text-primary-600 bg-gray-100 border-gray-300 rounded focus:ring-primary-500 dark:focus:ring-primary-600 focus:ring-2 dark:bg-gray-700 dark:border-gray-600 cursor-pointer">
                                </th>
                                <th scope="col" class="px-6 py-3">Nama Pengguna</th>
                                <th scope="col" class="px-6 py-3">Kontak</th>
                                <th scope="col" class="px-6 py-3 text-center">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                            @forelse($users as $u)
                            <tr class="bg-white hover:bg-gray-50 dark:bg-gray-800 dark:hover:bg-gray-700 transition-colors cursor-pointer" onclick="toggleRowCheck('{{ $u->id }}')">
                                <td class="w-4 p-4 text-center" onclick="event.stopPropagation()">
                                    <input id="checkbox-user-{{ $u->id }}" type="checkbox" value="{{ $u->id }}" class="user-checkbox w-4 h-4 text-primary-600 bg-gray-100 border-gray-300 rounded focus:ring-primary-500 dark:focus:ring-primary-600 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                                </td>
                                <td class="px-6 py-4">
                                    <div class="font-medium text-gray-900 dark:text-white">{{ $u->full_name }}</div>
                                    <div class="text-xs text-gray-500">{{ $u->companyProfile->company_name ?? '-' }}</div>
                                </td>
                                <td class="px-6 py-4 font-mono text-xs">
                                    {{ $u->phone_number }}
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <span class="bg-green-100 text-green-800 text-xs font-medium px-2.5 py-0.5 rounded dark:bg-green-900 dark:text-green-300">{{ ucfirst($u->status) }}</span>
                                </td>
                            </tr>
                            @empty
                            <tr><td colspan="4" class="px-6 py-10 text-center text-gray-500">Tidak ada data.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="p-4 border-t border-gray-200 dark:border-gray-700">
                    {{ $users->links() }}
                </div>
            </div>
        </div>
    </div>

    <!-- MODAL TAMBAH TEMPLATE (UKURAN BESAR) -->
    <div id="addTemplateModal" tabindex="-1" aria-hidden="true" class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
        <div class="relative p-4 w-full max-w-4xl max-h-full">
            <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
                <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t dark:border-gray-600">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Simpan Template Baru</h3>
                    <button type="button" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white" data-modal-toggle="addTemplateModal">
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 14 14"><path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/></svg>
                    </button>
                </div>
                <form action="{{ route('admin.broadcast.templates.store') }}" method="POST" class="p-4 md:p-5">
                    @csrf

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <!-- KOLOM 1: Form Input (2/3 lebar) -->
                        <div class="md:col-span-2 space-y-4">
                            <div>
                                <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Nama Template</label>
                                <input type="text" name="name" id="template_name_input" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:text-white" placeholder="Contoh: Pengingat Dokumen" required>
                            </div>
                            <div>
                                <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Isi Pesan</label>
                                <textarea name="content" id="template_content_input" rows="10" class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white font-mono" placeholder="Halo {name},..." required></textarea>
                            </div>
                        </div>

                        <!-- KOLOM 2: Kamus Variabel (1/3 lebar) -->
                        <div class="border-l pl-6 border-gray-200 dark:border-gray-600 md:col-span-1 flex flex-col h-full">
                            <h4 class="text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">Kamus Variabel</h4>
                            <p class="text-xs text-gray-500 mb-3">Klik kode untuk menyalin.</p>

                            <div class="overflow-y-auto flex-1 custom-scrollbar max-h-[350px]">
                                <table class="w-full text-left text-xs">
                                    <tbody class="text-gray-600 dark:text-gray-400 font-mono">
                                        <tr><td class="py-1 text-blue-600 cursor-pointer hover:underline" onclick="insertVarToTemplate('{name}')">{name}</td><td>Nama User</td></tr>
                                        <tr><td class="py-1 text-blue-600 cursor-pointer hover:underline" onclick="insertVarToTemplate('{username}')">{username}</td><td>Username</td></tr>
                                        <tr><td class="py-1 text-blue-600 cursor-pointer hover:underline" onclick="insertVarToTemplate('{email}')">{email}</td><td>Email</td></tr>
                                        <tr><td class="py-1 text-blue-600 cursor-pointer hover:underline" onclick="insertVarToTemplate('{phone}')">{phone}</td><td>No. HP</td></tr>
                                        <tr><td class="py-1 text-blue-600 cursor-pointer hover:underline" onclick="insertVarToTemplate('{status}')">{status}</td><td>Status</td></tr>
                                        <tr><td class="py-1 text-blue-600 cursor-pointer hover:underline" onclick="insertVarToTemplate('{join_date}')">{join_date}</td><td>Tgl Join</td></tr>
                                        <tr><td class="pt-2 text-blue-600 cursor-pointer hover:underline" onclick="insertVarToTemplate('{company_full}')">{company_full}</td><td class="pt-2">Nama PT</td></tr>
                                        <tr><td class="py-1 text-blue-600 cursor-pointer hover:underline" onclick="insertVarToTemplate('{job_title}')">{job_title}</td><td>Jabatan</td></tr>
                                        <tr><td class="py-1 text-blue-600 cursor-pointer hover:underline" onclick="insertVarToTemplate('{city}')">{city}</td><td>Kota</td></tr>
                                        <tr><td class="pt-2 text-blue-600 cursor-pointer hover:underline" onclick="insertVarToTemplate('{link_symbiosis}')">{link_symbiosis}</td><td class="pt-2">Link Web</td></tr>
                                        <tr><td class="py-1 text-blue-600 cursor-pointer hover:underline" onclick="insertVarToTemplate('{nama_system}')">{nama_system}</td><td>Nm Sistem</td></tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <div class="mt-6 flex justify-end gap-3">
                        <button type="button" data-modal-hide="addTemplateModal" class="px-5 py-2.5 text-sm font-medium text-gray-900 focus:outline-none bg-white rounded-lg border border-gray-200 hover:bg-gray-100 hover:text-blue-700 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:text-white dark:hover:bg-gray-700">Batal</button>
                        <button type="submit" id="btn-save-template" class="text-white bg-primary-700 hover:bg-primary-800 focus:ring-4 focus:outline-none focus:ring-primary-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center opacity-50 cursor-not-allowed transition" disabled>Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- MODAL HAPUS TEMPLATE -->
    <div id="deleteTemplateModal" tabindex="-1" aria-hidden="true" class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
        <div class="relative p-4 w-full max-w-md max-h-full">
            <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
                <button type="button" class="absolute top-3 end-2.5 text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center" data-modal-hide="deleteTemplateModal">
                    <svg class="w-3 h-3" fill="none" viewBox="0 0 14 14"><path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/></svg>
                </button>
                <div class="p-4 md:p-5 text-center">
                    <svg class="mx-auto mb-4 text-gray-400 w-12 h-12" fill="none" viewBox="0 0 20 20"><path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 11V6m0 8h.01M19 10a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/></svg>
                    <h3 class="mb-5 text-lg font-normal text-gray-500 dark:text-gray-400">Hapus template <span id="del_tpl_name" class="font-bold"></span>?</h3>
                    <form id="deleteTemplateForm" method="POST" action="">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-white bg-red-600 hover:bg-red-800 font-medium rounded-lg text-sm inline-flex items-center px-5 py-2.5 text-center">Ya, Hapus</button>
                        <button data-modal-hide="deleteTemplateModal" type="button" class="py-2.5 px-5 ms-3 text-sm font-medium text-gray-900 bg-white rounded-lg border border-gray-200 hover:bg-gray-100 hover:text-blue-700 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:text-white dark:hover:bg-gray-700">Batal</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- MODAL KONFIRMASI KIRIM -->
    <div id="confirmBroadcastModal" tabindex="-1" aria-hidden="true" class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
        <div class="relative p-4 w-full max-w-md max-h-full">
            <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
                <button type="button" class="absolute top-3 end-2.5 text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center" data-modal-hide="confirmBroadcastModal">
                    <svg class="w-3 h-3" fill="none" viewBox="0 0 14 14"><path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/></svg>
                </button>
                <div class="p-4 md:p-5 text-center">
                    <div class="bg-green-100 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path></svg>
                    </div>
                    <h3 class="mb-2 text-lg font-bold text-gray-900 dark:text-white">Kirim Pesan Massal?</h3>
                    <p class="mb-6 text-sm text-gray-500 dark:text-gray-400">
                        Anda akan mengirim pesan ke <span id="confirm_count" class="font-bold text-primary-600">0</span> pengguna.
                    </p>
                    <div class="flex justify-center gap-3">
                        <button data-modal-hide="confirmBroadcastModal" type="button" class="py-2.5 px-5 text-sm font-medium text-gray-900 bg-white rounded-lg border border-gray-200 hover:bg-gray-100">Batal</button>
                        <button type="button" id="final-submit-btn" class="text-white bg-green-600 hover:bg-green-800 font-medium rounded-lg text-sm px-5 py-2.5 text-center shadow-lg">Ya, Kirim Sekarang</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Fungsi Insert Variabel ke Textarea Utama
        function insertVar(code) {
            const textarea = document.getElementById('message_content');
            insertTextAtCursor(textarea, code);
        }

        // Fungsi Insert Variabel ke Textarea Modal Tambah Template
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

            // Logic Validasi Modal Tambah Template
            const tplName = document.getElementById('template_name_input');
            const tplContent = document.getElementById('template_content_input');
            const tplSaveBtn = document.getElementById('btn-save-template');

            function checkTemplateForm() {
                if(tplName.value.trim() !== '' && tplContent.value.trim() !== '') {
                    tplSaveBtn.disabled = false;
                    tplSaveBtn.classList.remove('opacity-50', 'cursor-not-allowed');
                } else {
                    tplSaveBtn.disabled = true;
                    tplSaveBtn.classList.add('opacity-50', 'cursor-not-allowed');
                }
            }
            tplName.addEventListener('input', checkTemplateForm);
            tplContent.addEventListener('input', checkTemplateForm);

            // Elemen Utama
            const checkboxAll = document.getElementById('checkbox-all');
            const userCheckboxes = document.querySelectorAll('.user-checkbox');
            const countDisplay = document.getElementById('count-display');
            const triggerConfirmBtn = document.getElementById('btn-trigger-confirm');
            const finalSubmitBtn = document.getElementById('final-submit-btn');
            const hiddenContainer = document.getElementById('selected_users_container');
            const sendAllToggle = document.getElementById('send_all_toggle');
            const tableOverlay = document.getElementById('table-overlay');
            const totalUsers = {{ $totalUsers }};

            // Modal Elements
            const confirmModalEl = document.getElementById('confirmBroadcastModal');
            const confirmModal = new Modal(confirmModalEl);
            const confirmCountSpan = document.getElementById('confirm_count');

            const deleteModalEl = document.getElementById('deleteTemplateModal');
            const deleteModal = new Modal(deleteModalEl);
            const delForm = document.getElementById('deleteTemplateForm');
            const delNameSpan = document.getElementById('del_tpl_name');

            // Handle Delete Template
            document.querySelectorAll('.delete-template-btn').forEach(btn => {
                btn.addEventListener('click', function() {
                    const id = this.getAttribute('data-id');
                    const name = this.getAttribute('data-name');
                    delNameSpan.textContent = `"${name}"`;
                    delForm.action = `/admin/broadcast/templates/${id}`;
                    deleteModal.show();
                });
            });

            // Handle Kirim Broadcast Trigger
            triggerConfirmBtn.addEventListener('click', function() {
                if(!messageArea.value.trim()) {
                    alert('Isi pesan tidak boleh kosong!');
                    return;
                }
                let recipientCount = sendAllToggle.checked ? totalUsers : document.querySelectorAll('.user-checkbox:checked').length;
                if(recipientCount === 0) return;

                confirmCountSpan.innerText = recipientCount;
                confirmModal.show();
            });

            finalSubmitBtn.addEventListener('click', function() {
                document.getElementById('broadcastForm').submit();
                this.disabled = true;
                this.innerHTML = 'Mengirim...';
            });

            sendAllToggle.addEventListener('change', function() {
                if(this.checked) {
                    tableOverlay.classList.remove('hidden');
                    triggerConfirmBtn.disabled = false;
                    triggerConfirmBtn.classList.remove('opacity-50', 'cursor-not-allowed');
                    countDisplay.innerText = totalUsers;
                    userCheckboxes.forEach(cb => cb.checked = false);
                    checkboxAll.checked = false;
                } else {
                    tableOverlay.classList.add('hidden');
                    updateSelection();
                }
            });

            templateSelector.addEventListener('change', function() {
                if(this.value) messageArea.value = this.value;
            });

            function updateSelection() {
                if(sendAllToggle.checked) return;
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
                        cb.closest('tr').classList.add('bg-blue-50', 'dark:bg-blue-900/20');
                    } else {
                        cb.closest('tr').classList.remove('bg-blue-50', 'dark:bg-blue-900/20');
                    }
                });
                countDisplay.innerText = count;
                triggerConfirmBtn.disabled = count === 0;
                if(count === 0) triggerConfirmBtn.classList.add('opacity-50', 'cursor-not-allowed');
                else triggerConfirmBtn.classList.remove('opacity-50', 'cursor-not-allowed');
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
