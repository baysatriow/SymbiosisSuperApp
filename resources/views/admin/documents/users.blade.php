<x-app-layout>
    <div class="grid grid-cols-1 gap-6 mb-6">

        <!-- HEADER STATISTIK DOKUMEN (NEW) -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            <!-- Total -->
            <div class="p-4 bg-white border border-gray-200 rounded-xl shadow-sm dark:bg-gray-800 dark:border-gray-700 relative overflow-hidden">
                <div class="absolute right-0 top-0 p-3 opacity-10">
                    <svg class="w-12 h-12 text-blue-600" fill="currentColor" viewBox="0 0 20 20"><path d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4z"></path></svg>
                </div>
                <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Diupload</p>
                <h4 class="text-2xl font-bold text-gray-900 dark:text-white mt-1">{{ $stats['total_uploaded'] }}</h4>
            </div>

            <!-- Pending -->
            <div class="p-4 bg-white border border-gray-200 rounded-xl shadow-sm dark:bg-gray-800 dark:border-gray-700 relative overflow-hidden">
                <div class="absolute right-0 top-0 p-3 opacity-10">
                    <svg class="w-12 h-12 text-yellow-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"></path></svg>
                </div>
                <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Menunggu Review</p>
                <h4 class="text-2xl font-bold text-yellow-500 mt-1">{{ $stats['pending'] }}</h4>
            </div>

            <!-- Rejected -->
            <div class="p-4 bg-white border border-gray-200 rounded-xl shadow-sm dark:bg-gray-800 dark:border-gray-700 relative overflow-hidden">
                <div class="absolute right-0 top-0 p-3 opacity-10">
                    <svg class="w-12 h-12 text-red-600" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path></svg>
                </div>
                <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Ditolak</p>
                <h4 class="text-2xl font-bold text-red-600 mt-1">{{ $stats['rejected'] }}</h4>
            </div>

            <!-- Storage -->
            <div class="p-4 bg-white border border-gray-200 rounded-xl shadow-sm dark:bg-gray-800 dark:border-gray-700 relative overflow-hidden">
                <div class="absolute right-0 top-0 p-3 opacity-10">
                    <svg class="w-12 h-12 text-green-600" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M2 5a2 2 0 012-2h12a2 2 0 012 2v10a2 2 0 01-2 2H4a2 2 0 01-2-2V5zm3.293 1.293a1 1 0 011.414 0l3 3a1 1 0 010 1.414l-3 3a1 1 0 01-1.414-1.414L7.586 10 5.293 7.707a1 1 0 010-1.414zM11 12a1 1 0 100 2h3a1 1 0 100-2h-3z" clip-rule="evenodd"></path></svg>
                </div>
                <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Storage</p>
                <h4 class="text-2xl font-bold text-gray-900 dark:text-white mt-1">
                    {{ number_format($stats['storage_bytes'] / 1048576, 2) }} <span class="text-sm font-normal text-gray-500">MB</span>
                </h4>
            </div>
        </div>

        <div class="flex justify-between items-center mt-2">
            <h2 class="text-xl font-bold tracking-tight text-gray-900 dark:text-white">Daftar Pengguna</h2>
        </div>

        <!-- Search -->
        <div class="bg-white dark:bg-gray-800 p-4 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700">
            <form method="GET" action="{{ route('admin.documents.users') }}">
                <div class="relative">
                    <div class="absolute inset-y-0 start-0 flex items-center ps-3 pointer-events-none">
                        <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z"/>
                        </svg>
                    </div>
                    <input type="search" name="search" value="{{ request('search') }}" class="block w-full p-4 ps-10 text-sm text-gray-900 border border-gray-300 rounded-lg bg-gray-50 focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white" placeholder="Cari Nama, Email...">
                    <button type="submit" class="text-white absolute end-2.5 bottom-2.5 bg-primary-700 hover:bg-primary-800 focus:ring-4 focus:outline-none focus:ring-primary-300 font-medium rounded-lg text-sm px-4 py-2">Cari</button>
                </div>
            </form>
        </div>

        <!-- User List Table -->
        <div class="relative overflow-x-auto shadow-md sm:rounded-lg bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700">
            <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
                <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                    <tr>
                        <th scope="col" class="px-6 py-3">Pengguna</th>
                        <th scope="col" class="px-6 py-3">Perusahaan</th>
                        <th scope="col" class="px-6 py-3 text-center">Jumlah Dokumen</th>
                        <th scope="col" class="px-6 py-3 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $user)
                    <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                        <td class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                            <div class="text-base font-semibold">{{ $user->full_name }}</div>
                            <div class="font-normal text-gray-500">{{ $user->email }}</div>
                        </td>
                        <td class="px-6 py-4">
                            {{ $user->companyProfile->company_name ?? '-' }}
                            <div class="text-xs text-gray-400">{{ $user->companyProfile->legal_entity_type ?? '' }}</div>
                        </td>
                        <td class="px-6 py-4 text-center">
                            <span class="bg-blue-100 text-blue-800 text-xs font-bold px-2.5 py-0.5 rounded-full dark:bg-blue-900 dark:text-blue-300">
                                {{ $user->documents_count }} File
                            </span>
                        </td>
                        <td class="px-6 py-4 text-center">
                            <!-- BUTTON LEBIH JELAS -->
                            <a href="{{ route('admin.documents.user.show', $user->id) }}" class="inline-flex items-center px-4 py-2 text-sm font-medium text-center text-white bg-primary-700 rounded-lg hover:bg-primary-800 focus:ring-4 focus:outline-none focus:ring-primary-300 dark:bg-primary-600 dark:hover:bg-primary-700 dark:focus:ring-primary-800 transition-transform hover:-translate-y-0.5 shadow-md">
                                <svg class="w-4 h-4 me-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                Kelola Dokumen
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="px-6 py-4 text-center text-gray-500">Tidak ada data pengguna.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
            <div class="p-4">
                {{ $users->links() }}
            </div>
        </div>

    </div>
</x-app-layout>
