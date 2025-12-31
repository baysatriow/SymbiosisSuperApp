<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Symbiosis') }} - Dashboard</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:300,400,500,600,700,800&display=swap" rel="stylesheet" />

    <!-- Scripts & Styles -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    colors: {
                        primary: {"50":"#ecfdf5","100":"#d1fae5","200":"#a7f3d0","300":"#6ee7b7","400":"#34d399","500":"#10b981","600":"#059669","700":"#047857","800":"#065f46","900":"#064e3b"}
                    },
                    fontFamily: {
                        'body': ['Inter', 'ui-sans-serif', 'system-ui', '-apple-system', 'system-ui', 'Segoe UI', 'Roboto', 'Helvetica Neue', 'Arial', 'sans-serif'],
                        'sans': ['Inter', 'ui-sans-serif', 'system-ui', '-apple-system', 'system-ui', 'Segoe UI', 'Roboto', 'Helvetica Neue', 'Arial', 'sans-serif']
                    }
                }
            }
        }
    </script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.2.1/flowbite.min.css" rel="stylesheet" />
    <style>
        #logo-sidebar, #main-content { transition: all 0.3s ease-in-out; }
        .no-scrollbar::-webkit-scrollbar { display: none; }
        .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }

        /* Animasi Toast */
        @keyframes slideIn {
            from { transform: translateX(100%); opacity: 0; }
            to { transform: translateX(0); opacity: 1; }
        }
        @keyframes fadeOut {
            from { opacity: 1; }
            to { opacity: 0; }
        }
        .toast-enter { animation: slideIn 0.3s ease-out forwards; }
        .toast-exit { animation: fadeOut 0.3s ease-in forwards; }
    </style>
</head>
<body class="bg-gray-50 dark:bg-gray-900 font-body antialiased">

    <!-- TOAST CONTAINER (Fixed di pojok kanan atas) -->
    <div id="toast-container" class="fixed top-20 right-5 z-[60] flex flex-col gap-2">
        <!-- Toasts akan disuntikkan di sini oleh JS -->
    </div>

    <!-- NAVBAR FIXED -->
    <nav class="fixed top-0 z-50 w-full bg-white border-b border-gray-200 dark:bg-gray-800 dark:border-gray-700 shadow-sm">
        <div class="px-3 py-3 lg:px-5 lg:pl-3">
            <div class="flex items-center justify-between">
                <div class="flex items-center justify-start rtl:justify-end">
                    <button id="sidebar-toggle-btn" type="button" class="inline-flex items-center p-2 text-sm text-gray-500 rounded-lg hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-gray-200 dark:text-gray-400 dark:hover:bg-gray-700 dark:focus:ring-gray-600">
                        <span class="sr-only">Open sidebar</span>
                        <svg class="w-6 h-6" aria-hidden="true" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                           <path clip-rule="evenodd" fill-rule="evenodd" d="M2 4.75A.75.75 0 012.75 4h14.5a.75.75 0 010 1.5H2.75A.75.75 0 012 4.75zm0 10.5a.75.75 0 01.75-.75h7.5a.75.75 0 010 1.5h-7.5a.75.75 0 01-.75-.75zM2 10a.75.75 0 01.75-.75h14.5a.75.75 0 010 1.5H2.75A.75.75 0 012 10z"></path>
                        </svg>
                    </button>
                    <a href="{{ Auth::user()->role === 'admin' ? route('admin.dashboard') : route('user.dashboard') }}" class="flex ms-2 md:ms-4 items-center group">
                        <div class="w-9 h-9 mr-2.5 bg-gradient-to-br from-primary-500 to-primary-700 rounded-lg flex items-center justify-center text-white font-bold text-lg shadow-sm group-hover:from-primary-600 group-hover:to-primary-800 transition-all">S</div>
                        <span class="self-center text-xl font-bold whitespace-nowrap dark:text-white text-gray-800 tracking-tight">Symbiosis</span>
                    </a>
                </div>
                <div class="flex items-center">
                    <div class="flex items-center ms-3">
                        <div>
                            <button type="button" class="flex text-sm bg-gray-800 rounded-full focus:ring-4 focus:ring-gray-300 dark:focus:ring-gray-600" aria-expanded="false" data-dropdown-toggle="dropdown-user">
                                <span class="sr-only">Open user menu</span>
                                <div class="w-9 h-9 rounded-full bg-primary-100 text-primary-700 border-2 border-primary-200 flex items-center justify-center font-bold uppercase text-sm hover:bg-primary-200 transition-colors">
                                    {{ substr(Auth::user()->full_name ?? 'U', 0, 1) }}
                                </div>
                            </button>
                        </div>
                        <div class="z-50 hidden my-4 text-base list-none bg-white divide-y divide-gray-100 rounded shadow dark:bg-gray-700 dark:divide-gray-600 min-w-[180px]" id="dropdown-user">
                            <div class="px-4 py-3" role="none">
                                <p class="text-sm text-gray-900 dark:text-white font-semibold" role="none">{{ Auth::user()->full_name }}</p>
                                <p class="text-xs font-medium text-gray-500 truncate dark:text-gray-300" role="none">{{ Auth::user()->email }}</p>
                            </div>
                            <ul class="py-1" role="none">
                                <li><a href="{{ route('user.dashboard') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-600 dark:hover:text-white" role="menuitem">Dashboard</a></li>
                                @if(Auth::user()->role !== 'admin')
                                <li><a href="{{ route('user.profile') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-600 dark:hover:text-white" role="menuitem">Profil Saya</a></li>
                                @endif
                                <li>
                                    <form method="POST" action="{{ route('logout') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-600 dark:hover:text-white" role="menuitem">
                                        @csrf
                                        <button type="submit" class="w-full text-left">Sign out</button>
                                    </form>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <!-- SIDEBAR -->
    <aside id="logo-sidebar" class="fixed top-0 left-0 z-40 w-64 h-screen pt-20 transition-transform bg-white border-r border-gray-200 sm:translate-x-0 dark:bg-gray-800 dark:border-gray-700 shadow-lg" aria-label="Sidebar">
        <div class="h-full px-3 pb-4 overflow-y-auto no-scrollbar bg-white dark:bg-gray-800">
            <ul class="space-y-2 font-medium">

                <!-- DASHBOARD (User & Admin) -->
                <li>
                    <a href="{{ Auth::user()->role === 'admin' ? route('admin.dashboard') : route('user.dashboard') }}" class="flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 group {{ (request()->routeIs('user.dashboard') || request()->routeIs('admin.dashboard')) ? 'bg-primary-50 text-primary-600 dark:bg-gray-700 dark:text-primary-400' : '' }}">
                        <svg class="w-5 h-5 transition duration-75 {{ (request()->routeIs('user.dashboard') || request()->routeIs('admin.dashboard')) ? 'text-primary-600' : 'text-gray-500 group-hover:text-gray-900 dark:text-gray-400 dark:group-hover:text-white' }}" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20"><path d="M2 2h6v6H2V2zm8 0h6v6h-6V2zm-8 8h6v6H2v-6zm8 0h6v6h-6v-6z"/></svg>
                        <span class="ms-3">Dashboard</span>
                    </a>
                </li>

                <!-- MENU USER BIASA -->
                @if(Auth::user()->role !== 'admin')
                    <div class="pt-4 pb-2"><span class="px-2 text-xs font-semibold text-gray-400 uppercase tracking-wider">Fitur Utama</span></div>
                    <li>
                        <a href="{{ route('user.documents') }}" class="flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 group {{ request()->routeIs('user.documents') ? 'bg-primary-50 text-primary-600 dark:bg-gray-700 dark:text-primary-400' : '' }}">
                            <svg class="flex-shrink-0 w-5 h-5 transition duration-75 {{ request()->routeIs('user.documents') ? 'text-primary-600' : 'text-gray-500 dark:text-gray-400 group-hover:text-gray-900 dark:group-hover:text-white' }}" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                            <span class="flex-1 ms-3 whitespace-nowrap">Dokumen</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('user.chat.index') }}" class="flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 group {{ request()->routeIs('user.chat.*') ? 'bg-primary-50 text-primary-600 dark:bg-gray-700 dark:text-primary-400' : '' }}">
                            <svg class="flex-shrink-0 w-5 h-5 transition duration-75 {{ request()->routeIs('user.chat.*') ? 'text-primary-600' : 'text-gray-500 dark:text-gray-400 group-hover:text-gray-900 dark:group-hover:text-white' }}" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z" /></svg>
                            <span class="flex-1 ms-3 whitespace-nowrap">Chatbot AI</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('geoportal.index') }}" class="flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 group {{ request()->routeIs('geoportal.*') ? 'bg-primary-50 text-primary-600' : '' }}">
                            <!-- Icon Map -->
                            <svg class="flex-shrink-0 w-5 h-5 transition duration-75 {{ request()->routeIs('geoportal.*') ? 'text-primary-600' : 'text-gray-500 dark:text-gray-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            <span class="flex-1 ms-3 whitespace-nowrap">Geoportal</span>
                        </a>
                    </li>
                    <li>
                        <!-- UPDATE: Link SROI sekarang aktif -->
                        <a href="{{ route('user.sroi.index') }}" class="flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 group {{ request()->routeIs('user.sroi.*') ? 'bg-primary-50 text-primary-600 dark:bg-gray-700 dark:text-primary-400' : '' }}">
                            <svg class="flex-shrink-0 w-5 h-5 transition duration-75 {{ request()->routeIs('user.sroi.*') ? 'text-primary-600' : 'text-gray-500 dark:text-gray-400 group-hover:text-gray-900 dark:group-hover:text-white' }}" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 7h6m0 2H9m-4 3h16l-5.197-5.197a7.5 7.5 0 00-10.606 0L4 12z" /><path stroke-linecap="round" stroke-linejoin="round" d="M9 17v1a3 3 0 106 0v-1" /></svg>
                            <span class="flex-1 ms-3 whitespace-nowrap">SROI Calculator</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('heatmap.index') }}" class="flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 group {{ request()->routeIs('heatmap.*') ? 'bg-primary-50 text-primary-600 dark:bg-gray-700 dark:text-primary-400' : '' }}">
                            <svg class="flex-shrink-0 w-5 h-5 transition duration-75 {{ request()->routeIs('heatmap.*') ? 'text-primary-600' : 'text-gray-500 dark:text-gray-400 group-hover:text-gray-900 dark:group-hover:text-white' }}" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                            </svg>
                            <span class="flex-1 ms-3 whitespace-nowrap">Isu Nasional (Heatmap)</span>
                        </a>
                    </li>
                    <div class="pt-4 pb-2"><span class="px-2 text-xs font-semibold text-gray-400 uppercase tracking-wider">Pengaturan</span></div>
                    <li>
                        <a href="{{ route('user.profile') }}" class="flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 group {{ request()->routeIs('user.profile') ? 'bg-primary-50 text-primary-600 dark:bg-gray-700 dark:text-primary-400' : '' }}">
                            <svg class="flex-shrink-0 w-5 h-5 transition duration-75 {{ request()->routeIs('user.profile') ? 'text-primary-600' : 'text-gray-500 dark:text-gray-400 group-hover:text-gray-900 dark:group-hover:text-white' }}" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M5.121 17.804A13.937 13.937 0 0112 16c2.5 0 4.847.655 6.879 1.804M15 10a3 3 0 11-6 0 3 3 0 016 0zm6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                            <span class="flex-1 ms-3 whitespace-nowrap">Profil User</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('user.company') }}" class="flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 group {{ request()->routeIs('user.company') ? 'bg-primary-50 text-primary-600 dark:bg-gray-700 dark:text-primary-400' : '' }}">
                            <svg class="flex-shrink-0 w-5 h-5 transition duration-75 {{ request()->routeIs('user.company') ? 'text-primary-600' : 'text-gray-500 dark:text-gray-400 group-hover:text-gray-900 dark:group-hover:text-white' }}" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" /></svg>
                            <span class="flex-1 ms-3 whitespace-nowrap">Profil Perusahaan</span>
                        </a>
                    </li>

                @endif

                <!-- MENU ADMIN -->
                @if(Auth::user()->role === 'admin')
                    <div class="pt-4 pb-2"><span class="px-2 text-xs font-semibold text-gray-400 uppercase tracking-wider">Admin Panel</span></div>
                    <li>
                        <!-- UPDATE: Link Manajemen Dokumen (List User) -->
                        <a href="{{ route('admin.documents.users') }}" class="flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 group {{ request()->routeIs('admin.documents.*') ? 'bg-primary-50 text-primary-600 dark:bg-gray-700 dark:text-primary-400' : '' }}">
                            <svg class="flex-shrink-0 w-5 h-5 transition duration-75 {{ request()->routeIs('admin.documents.*') ? 'text-primary-600' : 'text-gray-500 dark:text-gray-400 group-hover:text-gray-900 dark:group-hover:text-white' }}" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" /></svg>
                            <span class="flex-1 ms-3 whitespace-nowrap">Manajemen Dokumen</span>
                        </a>
                    </li>
                    <li>
                        <!-- PERBAIKAN DISINI: Tambahkan 'admin.' di depan nama route -->
                        <a href="{{ route('admin.master.documents.index') }}" class="flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 group {{ request()->routeIs('admin.master.*') ? 'bg-primary-50 text-primary-600 dark:bg-gray-700 dark:text-primary-400' : '' }}">
                            <svg class="flex-shrink-0 w-5 h-5 transition duration-75" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                            <span class="flex-1 ms-3 whitespace-nowrap">Master Data Dokumen</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.users.index') }}" class="flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 group {{ request()->routeIs('admin.users.*') ? 'bg-primary-50 text-primary-600 dark:bg-gray-700 dark:text-primary-400' : '' }}">
                            <svg class="flex-shrink-0 w-5 h-5 transition duration-75 {{ request()->routeIs('admin.users.*') ? 'text-primary-600' : 'text-gray-500 dark:text-gray-400 group-hover:text-gray-900 dark:group-hover:text-white' }}" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" /></svg>
                            <span class="flex-1 ms-3 whitespace-nowrap">Manajemen Pengguna</span>
                        </a>
                    </li>
                    <li>
                        <!-- UPDATE LINK BROADCAST -->
                        <a href="{{ route('admin.broadcast.index') }}" class="flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 group {{ request()->routeIs('admin.broadcast.*') ? 'bg-primary-50 text-primary-600 dark:bg-gray-700 dark:text-primary-400' : '' }}">
                            <svg class="flex-shrink-0 w-5 h-5 transition duration-75 {{ request()->routeIs('admin.broadcast.*') ? 'text-primary-600' : 'text-gray-500 dark:text-gray-400 group-hover:text-gray-900 dark:group-hover:text-white' }}" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z" />
                            </svg>
                            <span class="flex-1 ms-3 whitespace-nowrap">Broadcast Pesan</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('heatmap.index') }}" class="flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 group {{ request()->routeIs('heatmap.*') ? 'bg-primary-50 text-primary-600 dark:bg-gray-700 dark:text-primary-400' : '' }}">
                            <svg class="flex-shrink-0 w-5 h-5 transition duration-75 {{ request()->routeIs('heatmap.*') ? 'text-primary-600' : 'text-gray-500 dark:text-gray-400 group-hover:text-gray-900 dark:group-hover:text-white' }}" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                            </svg>
                            <span class="flex-1 ms-3 whitespace-nowrap">Isu Nasional (Heatmap)</span>
                        </a>
                    </li>

                    <li>
                        <a href="{{ route('geoportal.index') }}" class="flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 group {{ request()->routeIs('geoportal.*') ? 'bg-primary-50 text-primary-600' : '' }}">
                            <!-- Icon Map -->
                            <svg class="flex-shrink-0 w-5 h-5 transition duration-75 {{ request()->routeIs('geoportal.*') ? 'text-primary-600' : 'text-gray-500 dark:text-gray-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            <span class="flex-1 ms-3 whitespace-nowrap">Geoportal</span>
                        </a>
                    </li>
                @endif
            </ul>
        </div>
    </aside>

    <!-- MAIN CONTENT -->
    <div id="main-content" class="p-4 sm:ml-64 mt-14">
        {{ $slot }}
    </div>

    <!-- Scripts -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.2.1/flowbite.min.js"></script>

    <!-- Global Toast & Sidebar Logic -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Sidebar Toggle
            const sidebarBtn = document.getElementById('sidebar-toggle-btn');
            const sidebar = document.getElementById('logo-sidebar');
            const mainContent = document.getElementById('main-content');
            let isSidebarOpen = true;

            sidebarBtn.addEventListener('click', function() {
                const isMobile = window.innerWidth < 640;
                if (isMobile) {
                    if (sidebar.classList.contains('-translate-x-full')) {
                        sidebar.classList.remove('-translate-x-full'); sidebar.classList.add('transform-none');
                    } else {
                        sidebar.classList.add('-translate-x-full'); sidebar.classList.remove('transform-none');
                    }
                } else {
                    if (isSidebarOpen) {
                        sidebar.classList.remove('sm:translate-x-0'); sidebar.classList.add('-translate-x-full');
                        mainContent.classList.remove('sm:ml-64'); mainContent.classList.add('sm:ml-0');
                    } else {
                        sidebar.classList.add('sm:translate-x-0'); sidebar.classList.remove('-translate-x-full');
                        mainContent.classList.add('sm:ml-64'); mainContent.classList.remove('sm:ml-0');
                    }
                    isSidebarOpen = !isSidebarOpen;
                }
            });

            // --- GLOBAL TOAST FUNCTION ---
            window.showToast = function(message, type = 'info') {
                const container = document.getElementById('toast-container');

                // Tentukan icon dan warna berdasarkan tipe
                let icon = '';
                let colorClass = '';

                if (type === 'success') {
                    colorClass = 'text-green-500 bg-green-100 dark:bg-green-800 dark:text-green-200';
                    icon = '<svg class="w-5 h-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20"><path d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5Zm3.707 8.207-4 4a1 1 0 0 1-1.414 0l-2-2a1 1 0 0 1 1.414-1.414L9 10.586l3.293-3.293a1 1 0 0 1 1.414 1.414Z"/></svg>';
                } else if (type === 'error') {
                    colorClass = 'text-red-500 bg-red-100 dark:bg-red-800 dark:text-red-200';
                    icon = '<svg class="w-5 h-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20"><path d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5Zm3.707 11.793a1 1 0 1 1-1.414 1.414L10 11.414l-2.293 2.293a1 1 0 0 1-1.414-1.414L8.586 10 6.293 7.707a1 1 0 0 1 1.414-1.414L10 8.586l2.293-2.293a1 1 0 0 1 1.414 1.414L11.414 10l2.293 2.293Z"/></svg>';
                } else if (type === 'warning') {
                    colorClass = 'text-orange-500 bg-orange-100 dark:bg-orange-700 dark:text-orange-200';
                    icon = '<svg class="w-5 h-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20"><path d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5ZM10 15a1 1 0 1 1 0-2 1 1 0 0 1 0 2Zm1-4a1 1 0 0 1-2 0V6a1 1 0 0 1 2 0v5Z"/></svg>';
                }

                const toastHtml = `
                    <div class="toast-enter flex items-center w-full max-w-xs p-4 text-gray-500 bg-white rounded-lg shadow dark:text-gray-400 dark:bg-gray-800 border border-gray-200 dark:border-gray-700" role="alert">
                        <div class="inline-flex items-center justify-center flex-shrink-0 w-8 h-8 ${colorClass} rounded-lg">
                            ${icon}
                            <span class="sr-only">${type} icon</span>
                        </div>
                        <div class="ms-3 text-sm font-normal">${message}</div>
                        <button type="button" class="ms-auto -mx-1.5 -my-1.5 bg-white text-gray-400 hover:text-gray-900 rounded-lg focus:ring-2 focus:ring-gray-300 p-1.5 hover:bg-gray-100 inline-flex items-center justify-center h-8 w-8 dark:text-gray-500 dark:hover:text-white dark:bg-gray-800 dark:hover:bg-gray-700" aria-label="Close" onclick="this.parentElement.remove()">
                            <span class="sr-only">Close</span>
                            <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
                            </svg>
                        </button>
                    </div>
                `;

                const wrapper = document.createElement('div');
                wrapper.innerHTML = toastHtml.trim();
                const toastEl = wrapper.firstChild;

                container.appendChild(toastEl);

                setTimeout(() => {
                    toastEl.classList.remove('toast-enter');
                    toastEl.classList.add('toast-exit');
                    toastEl.addEventListener('animationend', () => {
                        toastEl.remove();
                    });
                }, 5000);
            }

            @if(session('success'))
                showToast("{{ session('success') }}", 'success');
            @endif

            @if(session('error'))
                showToast("{{ session('error') }}", 'error');
            @endif

            @if(session('warning'))
                showToast("{{ session('warning') }}", 'warning');
            @endif

            @if($errors->any())
                showToast("Ada kesalahan input. Silakan periksa kembali.", 'error');
            @endif
        });
    </script>
</body>
</html>
