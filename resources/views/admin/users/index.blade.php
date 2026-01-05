<x-app-layout>
    <div class="space-y-6 animate-in fade-in duration-700">
        <!-- HEADER -->
        <x-page-header 
            title="Manajemen Pengguna" 
            subtitle="Kelola akun admin dan pengguna aplikasi dengan kontrol penuh.">
            <x-slot:actions>
                <button data-modal-target="createUserModal" data-modal-toggle="createUserModal" class="flex items-center gap-2 px-5 py-2.5 bg-primary-600 text-white font-bold rounded-xl hover:bg-primary-700 transition-all shadow-lg shadow-primary-200 active:scale-95 group">
                    <svg class="w-5 h-5 group-hover:rotate-90 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    TAMBAH PENGGUNA
                </button>
            </x-slot:actions>
        </x-page-header>

        <!-- FILTERS & SEARCH -->
        <div class="grid grid-cols-1 gap-4">
            <x-content-card class="!p-4 bg-white/80 backdrop-blur-md border-gray-100 shadow-sm">
                <form method="GET" action="{{ route('admin.users.index') }}" class="flex flex-col md:flex-row gap-4">
                    <div class="relative flex-1 group">
                        <div class="absolute inset-y-0 start-0 flex items-center ps-4 pointer-events-none">
                            <svg class="w-4 h-4 text-gray-400 group-focus-within:text-primary-500 transition-colors" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20"><path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z"/></svg>
                        </div>
                        <input type="search" name="search" value="{{ request('search') }}" 
                            class="block w-full p-3.5 ps-11 text-sm text-gray-900 border border-gray-100 rounded-2xl bg-gray-50/50 focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500 transition-all outline-none" 
                            placeholder="Cari Nama, Username, atau Email...">
                    </div>
                    <button type="submit" class="px-8 py-3.5 bg-gray-900 text-white text-sm font-bold rounded-2xl hover:bg-black transition-all active:scale-95 shadow-lg shadow-gray-200">
                        CARI DATA
                    </button>
                    @if(request('search'))
                        <a href="{{ route('admin.users.index') }}" class="px-6 py-3.5 bg-gray-100 text-gray-600 text-sm font-bold rounded-2xl hover:bg-gray-200 transition-all text-center">
                            RESET
                        </a>
                    @endif
                </form>
            </x-content-card>
        </div>

        <!-- USERS TABLE -->
        <x-content-card class="!p-0 overflow-hidden border-gray-100 shadow-xl shadow-gray-50/50">
            <div class="overflow-x-auto custom-scrollbar">
                <table class="w-full text-sm text-left">
                    <thead class="text-[10px] text-gray-400 uppercase tracking-widest bg-gray-50/50 border-b border-gray-100">
                        <tr>
                            <th scope="col" class="px-6 py-5 font-black">Informasi Pengguna</th>
                            <th scope="col" class="px-6 py-5 font-black">Akses & Status</th>
                            <th scope="col" class="px-6 py-5 font-black">Aktivitas Terakhir</th>
                            <th scope="col" class="px-6 py-5 font-black text-center">Tindakan</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @forelse($users as $u)
                        <tr class="group hover:bg-gray-50/80 transition-all">
                            <td class="px-6 py-5">
                                <div class="flex items-center gap-4">
                                    <div class="w-11 h-11 rounded-2xl bg-gradient-to-br from-gray-100 to-gray-200 flex items-center justify-center text-gray-500 font-bold text-lg border-2 border-white shadow-sm transition-transform group-hover:scale-105">
                                        {{ substr($u->full_name, 0, 1) }}
                                    </div>
                                    <div>
                                        <div class="font-bold text-gray-900 text-base leading-none mb-1 group-hover:text-primary-600 transition-colors">{{ $u->full_name }}</div>
                                        <div class="text-xs text-gray-400 font-medium flex items-center gap-2">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.206"></path></svg>
                                            {{ $u->email }}
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-5">
                                <div class="flex flex-col gap-1.5 items-start">
                                    <!-- Badge Role -->
                                    @if($u->role === 'admin')
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg bg-indigo-50 text-indigo-700 text-[10px] font-black tracking-widest uppercase border border-indigo-100">
                                            <span class="w-1 h-1 rounded-full bg-indigo-500"></span>
                                            ADMINISTRATOR
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg bg-emerald-50 text-emerald-700 text-[10px] font-black tracking-widest uppercase border border-emerald-100">
                                            <span class="w-1 h-1 rounded-full bg-emerald-500"></span>
                                            REGULAR USER
                                        </span>
                                    @endif

                                    <!-- Badge Status -->
                                    @if($u->status === 'active')
                                        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full bg-emerald-100 text-emerald-800 text-[9px] font-bold">
                                            <svg class="w-2.5 h-2.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
                                            ACTIVE
                                        </span>
                                    @elseif($u->status === 'pending_admin_review')
                                        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full bg-amber-100 text-amber-800 text-[9px] font-bold">
                                            <svg class="w-2.5 h-2.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"></path></svg>
                                            PENDING
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full bg-rose-100 text-rose-800 text-[9px] font-bold uppercase">
                                            <svg class="w-2.5 h-2.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path></svg>
                                            {{ $u->status }}
                                        </span>
                                    @endif
                                </div>
                            </td>
                            <td class="px-6 py-5">
                                @if($u->last_login_at)
                                    <div class="text-gray-900 font-bold text-sm">{{ $u->last_login_at->diffForHumans() }}</div>
                                    <div class="text-[10px] text-gray-400 font-medium uppercase tracking-wider mt-0.5">{{ $u->last_login_at->format('d M Y, H:i') }}</div>
                                @else
                                    <span class="inline-flex items-center gap-1.5 text-gray-300 italic text-xs">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                                        Belum login
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-5">
                                <div class="flex justify-center gap-2">
                                    <button type="button"
                                            data-modal-target="editUserModal"
                                            data-modal-toggle="editUserModal"
                                            class="edit-btn p-2.5 text-gray-400 hover:text-primary-600 hover:bg-primary-50 rounded-xl transition-all"
                                            title="Edit Data"
                                            data-id="{{ $u->id }}"
                                            data-fullname="{{ $u->full_name }}"
                                            data-email="{{ $u->email }}"
                                            data-username="{{ $u->username }}"
                                            data-phone="{{ $u->phone_number }}"
                                            data-role="{{ $u->role }}"
                                            data-status="{{ $u->status }}">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                    </button>

                                    @if($u->id !== Auth::id())
                                        <button type="button"
                                                data-modal-target="deleteUserModal"
                                                data-modal-toggle="deleteUserModal"
                                                class="delete-btn p-2.5 text-gray-400 hover:text-rose-600 hover:bg-rose-50 rounded-xl transition-all"
                                                title="Hapus Pengguna"
                                                data-id="{{ $u->id }}"
                                                data-fullname="{{ $u->full_name }}">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                        </button>
                                    @else
                                        <button disabled class="p-2.5 text-gray-200 cursor-not-allowed" title="Sedang Anda gunakan">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                                        </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="px-6 py-12 text-center">
                                <div class="flex flex-col items-center justify-center opacity-40">
                                    <svg class="w-16 h-16 text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                                    <p class="text-sm font-bold text-gray-400 uppercase tracking-widest">Tidak ada data pengguna ditemukan</p>
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

    <!-- MODAL CREATE USER -->
    <div id="createUserModal" tabindex="-1" aria-hidden="true" class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full transition-all duration-300 backdrop-blur-sm">
        <div class="relative p-6 w-full max-w-xl max-h-full">
            <div class="relative bg-white rounded-[2.5rem] shadow-2xl border-4 border-white overflow-hidden">
                <!-- Header Modal -->
                <div class="flex items-center justify-between p-8 bg-gradient-to-r from-primary-600 to-primary-800 text-white">
                    <div>
                        <h3 class="text-xl font-black uppercase tracking-tight">Tambah Pengguna</h3>
                        <p class="text-primary-100 text-xs font-medium mt-1">Lengkapi informasi untuk membuat akun baru.</p>
                    </div>
                    <button type="button" class="w-10 h-10 flex items-center justify-center bg-white/20 hover:bg-white/30 rounded-2xl transition-all" data-modal-toggle="createUserModal">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 14 14"><path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/></svg>
                    </button>
                </div>

                <form action="{{ route('admin.users.store') }}" method="POST" class="p-8 space-y-6 bg-white">
                    @csrf
                    <div class="grid gap-6 grid-cols-2">
                        <div class="col-span-2">
                            <label class="block mb-2 text-[10px] font-black text-gray-400 uppercase tracking-widest">Nama Lengkap</label>
                            <input type="text" name="full_name" value="{{ old('full_name') }}" class="w-full bg-gray-50/50 border border-gray-100 text-gray-900 text-sm font-bold rounded-2xl focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500 block p-4 outline-none transition-all" placeholder="Masukkan nama lengkap..." required>
                            @error('full_name') <span class="text-rose-500 text-[10px] font-bold mt-1 inline-block">{{ $message }}</span> @enderror
                        </div>
                        <div class="col-span-2 sm:col-span-1">
                            <label class="block mb-2 text-[10px] font-black text-gray-400 uppercase tracking-widest">Username</label>
                            <input type="text" name="username" value="{{ old('username') }}" class="w-full bg-gray-50/50 border border-gray-100 text-gray-900 text-sm font-bold rounded-2xl focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500 block p-4 outline-none transition-all" placeholder="Username..." required>
                            @error('username') <span class="text-rose-500 text-[10px] font-bold mt-1 inline-block">{{ $message }}</span> @enderror
                        </div>
                        <div class="col-span-2 sm:col-span-1">
                            <label class="block mb-2 text-[10px] font-black text-gray-400 uppercase tracking-widest">No. HP (WA)</label>
                            <input type="text" name="phone_number" value="{{ old('phone_number') }}" class="w-full bg-gray-50/50 border border-gray-100 text-gray-900 text-sm font-bold rounded-2xl focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500 block p-4 outline-none transition-all" placeholder="08123..." required>
                            @error('phone_number') <span class="text-rose-500 text-[10px] font-bold mt-1 inline-block">{{ $message }}</span> @enderror
                        </div>
                        <div class="col-span-2">
                            <label class="block mb-2 text-[10px] font-black text-gray-400 uppercase tracking-widest">Email Address</label>
                            <input type="email" name="email" value="{{ old('email') }}" class="w-full bg-gray-50/50 border border-gray-100 text-gray-900 text-sm font-bold rounded-2xl focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500 block p-4 outline-none transition-all" placeholder="email@example.com" required>
                            @error('email') <span class="text-rose-500 text-[10px] font-bold mt-1 inline-block">{{ $message }}</span> @enderror
                        </div>
                        <div class="col-span-2 sm:col-span-1">
                            <label class="block mb-2 text-[10px] font-black text-gray-400 uppercase tracking-widest">Hak Akses</label>
                            <select name="role" class="w-full bg-gray-50/50 border border-gray-100 text-gray-900 text-sm font-bold rounded-2xl focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500 block p-4 outline-none transition-all">
                                <option value="user" {{ old('role') == 'user' ? 'selected' : '' }}>Regular User</option>
                                <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Administrator</option>
                            </select>
                        </div>
                        <div class="col-span-2 sm:col-span-1">
                            <label class="block mb-2 text-[10px] font-black text-gray-400 uppercase tracking-widest">Status Akun</label>
                            <select name="status" class="w-full bg-gray-50/50 border border-gray-100 text-gray-900 text-sm font-bold rounded-2xl focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500 block p-4 outline-none transition-all">
                                <option value="active" {{ old('status') == 'active' ? 'selected' : '' }}>Active</option>
                                <option value="pending_admin_review" {{ old('status') == 'pending_admin_review' ? 'selected' : '' }}>Pending</option>
                            </select>
                        </div>
                        <div class="col-span-2">
                            <label class="block mb-2 text-[10px] font-black text-gray-400 uppercase tracking-widest">Set Password</label>
                            <input type="password" name="password" class="w-full bg-gray-50/50 border border-gray-100 text-gray-900 text-sm font-bold rounded-2xl focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500 block p-4 outline-none transition-all" placeholder="Min. 8 karakter..." required>
                            @error('password') <span class="text-rose-500 text-[10px] font-bold mt-1 inline-block">{{ $message }}</span> @enderror
                        </div>
                    </div>
                    
                    <div class="pt-4">
                        <button type="submit" class="w-full bg-primary-600 hover:bg-primary-700 text-white font-black py-4 rounded-[1.5rem] shadow-xl shadow-primary-100 transition-all hover:shadow-2xl active:scale-[0.98] uppercase tracking-widest text-sm">
                            DAFTARKAN PENGGUNA
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- MODAL EDIT USER -->
    <div id="editUserModal" tabindex="-1" aria-hidden="true" class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full transition-all duration-300 backdrop-blur-sm">
        <div class="relative p-6 w-full max-w-xl max-h-full">
            <div class="relative bg-white rounded-[2.5rem] shadow-2xl border-4 border-white overflow-hidden">
                <div class="flex items-center justify-between p-8 bg-gradient-to-r from-blue-600 to-blue-800 text-white">
                    <div>
                        <h3 class="text-xl font-black uppercase tracking-tight">Edit Pengguna</h3>
                        <p class="text-blue-100 text-xs font-medium mt-1">Perbarui data atau akses akun pengguna.</p>
                    </div>
                    <button type="button" class="w-10 h-10 flex items-center justify-center bg-white/20 hover:bg-white/30 rounded-2xl transition-all" data-modal-toggle="editUserModal">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 14 14"><path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/></svg>
                    </button>
                </div>

                <form id="editUserForm" method="POST" class="p-8 space-y-6 bg-white">
                    @csrf
                    @method('PUT')
                    <div class="grid gap-6 grid-cols-2">
                        <div class="col-span-2">
                            <label class="block mb-2 text-[10px] font-black text-gray-400 uppercase tracking-widest">Nama Lengkap</label>
                            <input type="text" name="full_name" id="edit_full_name" class="w-full bg-gray-50/50 border border-gray-100 text-gray-900 text-sm font-bold rounded-2xl focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 block p-4 outline-none transition-all" required>
                        </div>
                        <div class="col-span-2 sm:col-span-1">
                            <label class="block mb-2 text-[10px] font-black text-gray-400 uppercase tracking-widest">Username</label>
                            <input type="text" name="username" id="edit_username" class="w-full bg-gray-50/50 border border-gray-100 text-gray-900 text-sm font-bold rounded-2xl focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 block p-4 outline-none transition-all" required>
                        </div>
                        <div class="col-span-2 sm:col-span-1">
                            <label class="block mb-2 text-[10px] font-black text-gray-400 uppercase tracking-widest">No. HP</label>
                            <input type="text" name="phone_number" id="edit_phone" class="w-full bg-gray-50/50 border border-gray-100 text-gray-900 text-sm font-bold rounded-2xl focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 block p-4 outline-none transition-all" required>
                        </div>
                        <div class="col-span-2">
                            <label class="block mb-2 text-[10px] font-black text-gray-400 uppercase tracking-widest">Email Address</label>
                            <input type="email" name="email" id="edit_email" class="w-full bg-gray-50/50 border border-gray-100 text-gray-900 text-sm font-bold rounded-2xl focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 block p-4 outline-none transition-all" required>
                        </div>
                        <div class="col-span-2 sm:col-span-1">
                            <label class="block mb-2 text-[10px] font-black text-gray-400 uppercase tracking-widest">Hak Akses</label>
                            <select name="role" id="edit_role" class="w-full bg-gray-50/50 border border-gray-100 text-gray-900 text-sm font-bold rounded-2xl focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 block p-4 outline-none transition-all">
                                <option value="user">Regular User</option>
                                <option value="admin">Administrator</option>
                            </select>
                        </div>
                        <div class="col-span-2 sm:col-span-1">
                            <label class="block mb-2 text-[10px] font-black text-gray-400 uppercase tracking-widest">Status Akun</label>
                            <select name="status" id="edit_status" class="w-full bg-gray-50/50 border border-gray-100 text-gray-900 text-sm font-bold rounded-2xl focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 block p-4 outline-none transition-all">
                                <option value="active">Active</option>
                                <option value="pending_admin_review">Pending</option>
                                <option value="frozen">Frozen</option>
                                <option value="rejected">Rejected</option>
                            </select>
                        </div>
                        <div class="col-span-2">
                            <label class="block mb-2 text-[10px] font-black text-gray-400 uppercase tracking-widest">Ubah Password (Opsional)</label>
                            <input type="password" name="password" class="w-full bg-gray-50/50 border border-gray-100 text-gray-900 text-sm font-bold rounded-2xl focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 block p-4 outline-none transition-all" placeholder="Kosongkan jika tidak diubah">
                        </div>
                    </div>
                    <div class="pt-4">
                        <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-black py-4 rounded-[1.5rem] shadow-xl shadow-blue-100 transition-all hover:shadow-2xl active:scale-[0.98] uppercase tracking-widest text-sm">
                            SIMPAN PERUBAHAN
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- MODAL DELETE CONFIRMATION -->
    <div id="deleteUserModal" tabindex="-1" aria-hidden="true" class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full transition-all duration-300 backdrop-blur-sm">
        <div class="relative p-6 w-full max-w-md max-h-full">
            <div class="relative bg-white rounded-[2.5rem] shadow-2xl border-4 border-white overflow-hidden p-10 text-center">
                <div class="w-24 h-24 bg-rose-50 text-rose-500 rounded-[2rem] flex items-center justify-center mx-auto mb-6 shadow-sm border border-rose-100">
                    <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                </div>
                
                <h3 class="text-2xl font-black text-gray-900 uppercase tracking-tight mb-2">Hapus Pengguna?</h3>
                <p class="text-gray-500 text-sm font-medium mb-8">
                    Tindakan ini akan menghapus akun <br>
                    <span id="delete_user_name" class="text-rose-600 font-black"></span> <br>
                    secara permanen. Lanjutkan?
                </p>

                <form id="deleteUserForm" method="POST" action="" class="flex gap-4">
                    @csrf
                    @method('DELETE')
                    <button type="button" data-modal-hide="deleteUserModal" class="flex-1 py-4 bg-gray-100 text-gray-600 font-black rounded-2xl hover:bg-gray-200 transition-all uppercase text-xs tracking-widest">
                        BATAL
                    </button>
                    <button type="submit" class="flex-1 py-4 bg-rose-600 text-white font-black rounded-2xl hover:bg-rose-700 transition-all shadow-xl shadow-rose-100 uppercase text-xs tracking-widest">
                        YA, HAPUS
                    </button>
                </form>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // --- AUTO OPEN CREATE MODAL IF ERRORS EXIST ---
            @if($errors->any())
                const createModalBtn = document.querySelector('[data-modal-target="createUserModal"]');
                if(createModalBtn) createModalBtn.click();
            @endif

            // --- HANDLE EDIT ---
            const editBtns = document.querySelectorAll('.edit-btn');
            const editForm = document.getElementById('editUserForm');

            const nameInput = document.getElementById('edit_full_name');
            const emailInput = document.getElementById('edit_email');
            const userInput = document.getElementById('edit_username');
            const phoneInput = document.getElementById('edit_phone');
            const roleInput = document.getElementById('edit_role');
            const statusInput = document.getElementById('edit_status');

            editBtns.forEach(btn => {
                btn.addEventListener('click', function() {
                    const id = this.getAttribute('data-id');
                    nameInput.value = this.getAttribute('data-fullname');
                    emailInput.value = this.getAttribute('data-email');
                    userInput.value = this.getAttribute('data-username');
                    phoneInput.value = this.getAttribute('data-phone');
                    roleInput.value = this.getAttribute('data-role');
                    statusInput.value = this.getAttribute('data-status');
                    editForm.action = `/admin/users/${id}`;
                });
            });

            // --- HANDLE DELETE ---
            const deleteBtns = document.querySelectorAll('.delete-btn');
            const deleteForm = document.getElementById('deleteUserForm');
            const deleteNameSpan = document.getElementById('delete_user_name');

            deleteBtns.forEach(btn => {
                btn.addEventListener('click', function() {
                    const id = this.getAttribute('data-id');
                    const name = this.getAttribute('data-fullname');

                    deleteNameSpan.textContent = `"${name}"`;
                    deleteForm.action = `/admin/users/${id}`;
                });
            });
        });
    </script>
</x-app-layout>
