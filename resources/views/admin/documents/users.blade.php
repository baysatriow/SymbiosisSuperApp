<x-app-layout>
    <div class="space-y-6 animate-in fade-in duration-700">
        <!-- HEADER -->
        <x-page-header 
            title="Monitoring Dokumen" 
            subtitle="Pantau statistik unggahan dokumen dan review kelengkapan data per pengguna.">
        </x-page-header>

        <!-- STATISTIK DOKUMEN -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            <x-stat-card 
                label="Total Diupload" 
                value="{{ $stats['total_uploaded'] }}" 
                icon='<svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20"><path d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4z"></path></svg>'
                iconColor="blue" />

            <x-stat-card 
                label="Menunggu Review" 
                value="{{ $stats['pending'] }}" 
                icon='<svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"></path></svg>'
                iconColor="amber" />

            <x-stat-card 
                label="Ditolak/Revisi" 
                value="{{ $stats['rejected'] }}" 
                icon='<svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path></svg>'
                iconColor="rose" />

            <x-stat-card 
                label="Total Storage" 
                value="{{ number_format($stats['storage_bytes'] / 1048576, 2) }} MB" 
                icon='<svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M2 5a2 2 0 012-2h12a2 2 0 012 2v10a2 2 0 01-2 2H4a2 2 0 01-2-2V5zm3.293 1.293a1 1 0 011.414 0l3 3a1 1 0 010 1.414l-3 3a1 1 0 01-1.414-1.414L7.586 10 5.293 7.707a1 1 0 010-1.414zM11 12a1 1 0 100 2h3a1 1 0 100-2h-3z" clip-rule="evenodd"></path></svg>'
                iconColor="primary" />
        </div>

        <!-- SEARCH & TABLE SECTION -->
        <x-content-card class="!p-0 border-gray-100 shadow-xl shadow-gray-50/50 overflow-hidden">
            <!-- Table Header with Search -->
            <div class="p-6 border-b border-gray-50 bg-gray-50/30 flex flex-col md:flex-row justify-between items-center gap-4">
                <div>
                    <h3 class="text-sm font-black text-gray-900 uppercase tracking-widest">Daftar Pengguna</h3>
                    <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mt-1">Kelola dokumen berdasarkan kepemilikan user</p>
                </div>
                <form method="GET" action="{{ route('admin.documents.users') }}" class="flex gap-2 w-full md:w-auto">
                    <div class="relative group flex-1 md:w-80">
                        <div class="absolute inset-y-0 start-0 flex items-center ps-4 pointer-events-none">
                            <svg class="w-4 h-4 text-gray-400 group-focus-within:text-primary-500 transition-colors" fill="none" viewBox="0 0 20 20"><path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z"/></svg>
                        </div>
                        <input type="search" name="search" value="{{ request('search') }}" 
                            class="block w-full p-3 ps-11 text-sm text-gray-900 border border-gray-100 rounded-2xl bg-white focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500 transition-all outline-none font-bold" 
                            placeholder="Cari Nama, Email, Perusahaan...">
                    </div>
                    <button type="submit" class="px-5 py-3 bg-gray-900 text-white text-[10px] font-black rounded-2xl hover:bg-black transition-all active:scale-95 shadow-lg shadow-gray-200 uppercase tracking-widest">
                        FILTER
                    </button>
                    @if(request('search'))
                        <a href="{{ route('admin.documents.users') }}" class="p-3 bg-gray-100 text-gray-500 rounded-2xl hover:bg-gray-200 transition-all">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                        </a>
                    @endif
                </form>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left">
                    <thead class="text-[10px] text-gray-400 uppercase tracking-widest bg-gray-50/50 border-b border-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-5 font-black">Identitas Pengguna</th>
                            <th scope="col" class="px-6 py-5 font-black">Perusahaan / Entitas</th>
                            <th scope="col" class="px-6 py-5 font-black text-center">Statistik File</th>
                            <th scope="col" class="px-6 py-5 font-black text-center">Tindakan</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50 bg-white">
                        @forelse($users as $user)
                        <tr class="group hover:bg-gray-50/50 transition-all">
                            <td class="px-6 py-5">
                                <div class="flex items-center gap-4">
                                    <div class="relative">
                                        <div class="w-12 h-12 rounded-2xl bg-gradient-to-br from-primary-500 to-primary-700 text-white flex items-center justify-center font-black text-xl shadow-lg shadow-primary-100 transition-transform group-hover:scale-105">
                                            {{ substr($user->full_name, 0, 1) }}
                                        </div>
                                        <div class="absolute -bottom-1 -right-1 w-5 h-5 bg-white rounded-full flex items-center justify-center shadow-sm">
                                            <div class="w-3 h-3 bg-emerald-500 rounded-full border-2 border-white"></div>
                                        </div>
                                    </div>
                                    <div>
                                        <div class="font-black text-gray-900 text-base group-hover:text-primary-600 transition-colors">{{ $user->full_name }}</div>
                                        <div class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mt-0.5">{{ $user->email }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-5">
                                <div class="inline-flex items-center gap-2 px-3 py-1.5 bg-gray-50 border border-gray-100 rounded-xl group-hover:bg-white group-hover:border-primary-100 transition-colors">
                                    <svg class="w-3.5 h-3.5 text-gray-400 group-hover:text-primary-500 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                                    <div>
                                        <div class="text-xs font-black text-gray-700 uppercase tracking-tight">{{ $user->companyProfile->company_name ?? 'NOT REGISTERED' }}</div>
                                        <div class="text-[9px] font-bold text-gray-400 uppercase tracking-widest">{{ $user->companyProfile->legal_entity_type ?? 'Generic Entity' }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-5 text-center">
                                <div class="inline-flex flex-col items-center p-2.5 rounded-2xl bg-primary-50 border border-primary-100 min-w-[80px] shadow-sm shadow-primary-50">
                                    <span class="text-lg font-black text-primary-700 leading-none">{{ $user->documents_count }}</span>
                                    <span class="text-[8px] font-black text-primary-400 uppercase tracking-widest mt-1">DOKUMEN</span>
                                </div>
                            </td>
                            <td class="px-6 py-5 text-center">
                                <a href="{{ route('admin.documents.user.show', $user->id) }}" class="inline-flex items-center justify-center p-3 text-primary-600 bg-primary-50/50 hover:bg-primary-600 hover:text-white rounded-2xl border border-primary-100 hover:border-primary-600 transition-all hover:shadow-lg hover:shadow-primary-200 active:scale-95 group/btn">
                                    <svg class="w-5 h-5 group-hover/btn:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                    <span class="ml-2 text-[10px] font-black uppercase tracking-widest">Kunjungi Profile</span>
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="px-6 py-12 text-center">
                                <div class="flex flex-col items-center justify-center opacity-40">
                                    <svg class="w-16 h-16 text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
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
</x-app-layout>
