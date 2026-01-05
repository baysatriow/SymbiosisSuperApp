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
                        // Tema Hijau Emerald
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
    <!-- Flowbite JS & CSS -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.2.1/flowbite.min.css" rel="stylesheet" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.2.1/flowbite.min.js"></script>

    <style>
        /* Custom scrollbar untuk sidebar */
        .no-scrollbar::-webkit-scrollbar {
            display: none;
        }
        .no-scrollbar {
            -ms-overflow-style: none;  /* IE and Edge */
            scrollbar-width: none;  /* Firefox */
        }
    </style>
</head>
<body class="bg-gray-50 dark:bg-gray-900 font-body antialiased">

    <!-- NAVBAR FIXED -->
    <nav class="fixed top-0 z-50 w-full bg-white border-b border-gray-200 dark:bg-gray-800 dark:border-gray-700">
        <div class="px-3 py-3 lg:px-5 lg:pl-3">
            <div class="flex items-center justify-between">
                <div class="flex items-center justify-start rtl:justify-end">
                    <!-- Tombol Hamburger (Mobile) -->
                    <!-- Logo (Left) -->
                    <a href="{{ Auth::user()->role === 'admin' ? route('admin.dashboard') : route('user.dashboard') }}" class="flex items-center gap-3 group">
                        <img src="{{ asset('images/logoSymbiosis.svg') }}" alt="Symbiosis" class="h-9 w-auto transition-transform group-hover:scale-105">
                    </a>

                    <!-- Tombol Hamburger (Right of Logo) -->
                    <button data-drawer-target="logo-sidebar" data-drawer-toggle="logo-sidebar" aria-controls="logo-sidebar" type="button" class="inline-flex items-center p-2 ms-3 text-sm text-gray-500 rounded-lg sm:hidden hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-gray-200 dark:text-gray-400 dark:hover:bg-gray-700 dark:focus:ring-gray-600">
                        <span class="sr-only">Open sidebar</span>
                        <svg class="w-6 h-6" aria-hidden="true" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                           <path clip-rule="evenodd" fill-rule="evenodd" d="M2 4.75A.75.75 0 012.75 4h14.5a.75.75 0 010 1.5H2.75A.75.75 0 012 4.75zm0 10.5a.75.75 0 01.75-.75h7.5a.75.75 0 010 1.5h-7.5a.75.75 0 01-.75-.75zM2 10a.75.75 0 01.75-.75h14.5a.75.75 0 010 1.5H2.75A.75.75 0 012 10z"></path>
                        </svg>
                    </button>
                </div>

                <!-- User Menu (Kanan) -->
                <div class="flex items-center">
                    <div class="flex items-center ms-3">
                        <div>
                            <button type="button" class="flex text-sm bg-gray-800 rounded-full focus:ring-4 focus:ring-gray-300 dark:focus:ring-gray-600" aria-expanded="false" data-dropdown-toggle="dropdown-user">
                                <span class="sr-only">Open user menu</span>
                                <!-- Avatar Placeholder -->
                                <div class="w-8 h-8 rounded-full bg-primary-500 flex items-center justify-center text-white font-bold uppercase border-2 border-white">
                                    {{ substr(Auth::user()->full_name ?? 'U', 0, 1) }}
                                </div>
                            </button>
                        </div>
                        <!-- Dropdown Content -->
                        <div class="z-50 hidden my-4 text-base list-none bg-white divide-y divide-gray-100 rounded shadow dark:bg-gray-700 dark:divide-gray-600" id="dropdown-user">
                            <div class="px-4 py-3" role="none">
                                <p class="text-sm text-gray-900 dark:text-white" role="none">
                                    {{ Auth::user()->full_name }}
                                </p>
                                <p class="text-sm font-medium text-gray-900 truncate dark:text-gray-300" role="none">
                                    {{ Auth::user()->email }}
                                </p>
                            </div>
                            <ul class="py-1" role="none">
                                <li>
                                    <a href="{{ Auth::user()->role === 'admin' ? route('admin.dashboard') : route('user.dashboard') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-600 dark:hover:text-white" role="menuitem">Dashboard</a>
                                </li>
                                <!-- Hanya tampilkan Profil jika bukan Admin -->
                                @if(Auth::user()->role !== 'admin')
                                <li>
                                    <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-600 dark:hover:text-white" role="menuitem">Profil Saya</a>
                                </li>
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
    <!-- END NAVBAR FIXED -->

    <!-- SIDEBAR (Dibuat Responsif berdasarkan Role) -->
    <aside id="logo-sidebar" class="fixed top-0 left-0 z-40 w-64 h-screen pt-24 transition-transform -translate-x-full bg-white/95 backdrop-blur-sm border-r-0 sm:translate-x-0 dark:bg-gray-800/95 shadow-xl" aria-label="Sidebar">
        <div class="h-full px-3 pb-4 overflow-y-auto no-scrollbar bg-white dark:bg-gray-800">
            <ul class="space-y-2 font-medium">

                <!-- START MENU DASHBOARD UTAMA -->
                <li>
                    <a href="{{ Auth::user()->role === 'admin' ? route('admin.dashboard') : route('user.dashboard') }}" class="flex items-center px-3 py-2.5 text-gray-900 rounded-xl dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 group {{ (request()->routeIs('user.dashboard') || request()->routeIs('admin.dashboard')) ? 'bg-primary-50 text-primary-600 dark:bg-gray-700 dark:text-primary-400' : '' }}">
                        <svg class="w-5 h-5 transition duration-75 {{ (request()->routeIs('user.dashboard') || request()->routeIs('admin.dashboard')) ? 'text-primary-600' : 'text-gray-500 dark:text-gray-400 group-hover:text-gray-900 dark:group-hover:text-white' }}" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 22 21">
                           <path d="M16.975 11H10V4.025a1 1 0 0 0-1.066-.998 8.5 8.5 0 1 0 9.8 11.834A1 1 0 0 0 17 14.85v-3.876a1 1 0 0 0-.025-.975Z"/>
                           <path d="M10 14H6.025a1 1 0 0 0-.998.998 8.5 8.5 0 1 0 9.8-11.834A1 1 0 0 0 14.85 10v3.876a1 1 0 0 0 .975.025Z"/>
                        </svg>
                        <span class="ms-3 font-medium">Dashboard</span>
                    </a>
                </li>
                <!-- END MENU DASHBOARD UTAMA -->

                <!-- MENU UNTUK USER BIASA -->
                @if(Auth::user()->role !== 'admin')
                    <li>
                        <!-- Menu Utama: Documents -->
                        <a href="{{ route('user.documents') }}" class="flex items-center px-3 py-2.5 text-gray-900 rounded-xl dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 group {{ request()->routeIs('user.documents') ? 'bg-primary-50 text-primary-600 dark:bg-gray-700 dark:text-primary-400' : '' }}">
                            <svg class="flex-shrink-0 w-5 h-5 transition duration-75 {{ request()->routeIs('user.documents') ? 'text-primary-600' : 'text-gray-500 dark:text-gray-400 group-hover:text-gray-900 dark:group-hover:text-white' }}" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 18 20">
                                <path d="M18 11.196v6.864a.999.999 0 0 1-1.026 1L1 18.06A.999.999 0 0 1 0 17.06V2.94A.997.997 0 0 1 1 2h7.026a1 1 0 0 1 .954.685l.135.342a1 1 0 0 0 .937.644h4.453a1 1 0 0 1 .937.644l.135.342A1 1 0 0 0 18 6.804Zm-10 2.27a1 1 0 0 0-1 1v2a1 1 0 1 0 2 0v-2a1 1 0 0 0-1-1Zm4 0a1 1 0 0 0-1 1v2a1 1 0 1 0 2 0v-2a1 1 0 0 0-1-1Zm4 0a1 1 0 0 0-1 1v2a1 1 0 1 0 2 0v-2a1 1 0 0 0-1-1Z"/>
                            </svg>
                            <span class="flex-1 ms-3 font-medium whitespace-nowrap">Dokumen</span>
                        </a>
                    </li>
                    <li>
                        <!-- Menu Utama: Chatbot -->
                        <a href="#" class="flex items-center px-3 py-2.5 text-gray-900 rounded-xl dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 group">
                            <svg class="flex-shrink-0 w-5 h-5 text-gray-500 transition duration-75 dark:text-gray-400 group-hover:text-gray-900 dark:group-hover:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M17.3 4.8A8.8 8.8 0 0 0 10 0C4.5 0 0 4.5 0 10a5.2 5.2 0 0 0 3.3 4.8c-.1.3-.3.6-.3.9v3.3l3.2-1.7a8.5 8.5 0 0 0 3.8 1.1c5.5 0 10-4.5 10-10a5.2 5.2 0 0 0-2.7-4.6ZM9 15a1 1 0 1 1 0-2 1 1 0 0 1 0 2Zm2.5-4a1 1 0 1 1 0-2 1 1 0 0 1 0 2Z"/>
                            </svg>
                            <span class="flex-1 ms-3 font-medium whitespace-nowrap">Chatbot AI</span>
                        </a>
                    </li>
                    <li>
                        <!-- Menu Utama: SROI Calculator -->
                        <a href="{{ route('user.sroi.index') }}" class="flex items-center px-3 py-2.5 text-gray-900 rounded-xl dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 group {{ request()->routeIs('user.sroi.*') ? 'bg-primary-50 text-primary-600 dark:bg-gray-700 dark:text-primary-400' : '' }}">
                            <svg class="flex-shrink-0 w-5 h-5 transition duration-75 {{ request()->routeIs('user.sroi.*') ? 'text-primary-600' : 'text-gray-500 dark:text-gray-400 group-hover:text-gray-900 dark:group-hover:text-white' }}" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M11.9 14.7C10.6 15.6 9 16 7.4 16c-4.4 0-8-3.6-8-8s3.6-8 8-8 8 3.6 8 8c0 1.6-.4 3.2-1.3 4.5L19.4 18l-1.4 1.4-6.1-4.7ZM7.4 4c-2.2 0-4 1.8-4 4s1.8 4 4 4 4-1.8 4-4-1.8-4-4-4Z"/>
                                <path d="M13 5H7a1 1 0 0 0-1 1v4a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V6a1 1 0 0 0-1-1ZM8 9a1 1 0 0 1-1-1V7a1 1 0 0 1 2 0v1a1 1 0 0 1-1 1Zm4 0a1 1 0 0 1-1-1V7a1 1 0 0 1 2 0v1a1 1 0 0 1-1 1Z"/>
                            </svg>
                            <span class="flex-1 ms-3 font-medium whitespace-nowrap">SROI Calculator</span>
                        </a>
                    </li>
                    <li>
                        <!-- Menu Utama: Heatmap (Isu Nasional) -->
                        <a href="{{ route('heatmap.index') }}" class="flex items-center px-3 py-2.5 text-gray-900 rounded-xl dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 group {{ request()->routeIs('heatmap.*') ? 'bg-primary-50 text-primary-600 dark:bg-gray-700 dark:text-primary-400' : '' }}">
                            <svg class="flex-shrink-0 w-5 h-5 transition duration-75 {{ request()->routeIs('heatmap.*') ? 'text-primary-600' : 'text-gray-500 dark:text-gray-400 group-hover:text-gray-900 dark:group-hover:text-white' }}" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                            </svg>
                            <span class="flex-1 ms-3 font-medium whitespace-nowrap">Isu Nasional (Heatmap)</span>
                        </a>
                    </li>

                    <li class="border-t border-gray-200 dark:border-gray-700 pt-2">
                        <!-- Menu Profil User -->
                        <a href="{{ route('user.profile') }}" class="flex items-center px-3 py-2.5 text-gray-900 rounded-xl dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 group {{ request()->routeIs('user.profile') ? 'bg-primary-50 text-primary-600 dark:bg-gray-700 dark:text-primary-400' : '' }}">
                            <svg class="flex-shrink-0 w-5 h-5 transition duration-75 {{ request()->routeIs('user.profile') ? 'text-primary-600' : 'text-gray-500 dark:text-gray-400 group-hover:text-gray-900 dark:group-hover:text-white' }}" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M10 0a10 10 0 1 0 10 10A10.01 10.01 0 0 0 10 0Zm0 18a8 8 0 1 1 8-8A8.01 8.01 0 0 1 10 18Zm-2.5-7.5A1.5 1.5 0 0 1 8 9h4a1.5 1.5 0 0 1 0 3H8a1.5 1.5 0 0 1 0-3Z"/>
                            </svg>
                            <span class="flex-1 ms-3 font-medium whitespace-nowrap">Profil User</span>
                        </a>
                    </li>
                    <li>
                        <!-- Menu Profil Perusahaan -->
                        <a href="{{ route('user.company') }}" class="flex items-center px-3 py-2.5 text-gray-900 rounded-xl dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 group {{ request()->routeIs('user.company') ? 'bg-primary-50 text-primary-600 dark:bg-gray-700 dark:text-primary-400' : '' }}">
                            <svg class="flex-shrink-0 w-5 h-5 transition duration-75 {{ request()->routeIs('user.company') ? 'text-primary-600' : 'text-gray-500 dark:text-gray-400 group-hover:text-gray-900 dark:group-hover:text-white' }}" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M10 0a10 10 0 1 0 10 10A10.01 10.01 0 0 0 10 0Zm0 18a8 8 0 1 1 8-8A8.01 8.01 0 0 1 10 18ZM5 9h10a1 1 0 0 0 0-2H5a1 1 0 0 0 0 2Zm0 4h10a1 1 0 0 0 0-2H5a1 1 0 0 0 0 2Z"/>
                            </svg>
                            <span class="flex-1 ms-3 font-medium whitespace-nowrap">Profil Perusahaan</span>
                        </a>
                    </li>
                @endif

                <!-- MENU UNTUK ADMIN -->
                @if(Auth::user()->role === 'admin')
                    <li>
                        <!-- Menu Admin: Manajemen Pengguna -->
                        <a href="{{ route('admin.users.index') }}" class="flex items-center px-3 py-2.5 text-gray-900 rounded-xl dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 group {{ request()->routeIs('admin.users.*') ? 'bg-primary-50 text-primary-600 dark:bg-gray-700 dark:text-primary-400' : '' }}">
                            <svg class="flex-shrink-0 w-5 h-5 transition duration-75 {{ request()->routeIs('admin.users.*') ? 'text-primary-600' : 'text-gray-500 dark:text-gray-400 group-hover:text-gray-900 dark:group-hover:text-white' }}" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M10 0a10 10 0 1 0 10 10A10.01 10.01 0 0 0 10 0Zm0 18a8 8 0 1 1 8-8A8.01 8.01 0 0 1 10 18Zm0-11a3 3 0 1 0 0 6 3 3 0 0 0 0-6Zm0-2a5 5 0 1 1 0 10 5 5 0 0 1 0-10Z"/>
                            </svg>
                            <span class="flex-1 ms-3 font-medium whitespace-nowrap">Manajemen Pengguna</span>
                        </a>
                    </li>
                    <li>
                        <!-- Menu Admin: Verifikasi Dokumen -->
                        <a href="#" class="flex items-center px-3 py-2.5 text-gray-900 rounded-xl dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 group">
                            <svg class="flex-shrink-0 w-5 h-5 text-gray-500 transition duration-75 dark:text-gray-400 group-hover:text-gray-900 dark:group-hover:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M17 5h-2V2a2 2 0 0 0-2-2H7a2 2 0 0 0-2 2v3H3a2 2 0 0 0-2 2v10a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V7a2 2 0 0 0-2-2ZM9 2h2v3H9V2Zm4 15H7v-2h6v2Zm2-4H5V7h10v6Z"/>
                            </svg>
                            <span class="flex-1 ms-3 font-medium whitespace-nowrap">Verifikasi Dokumen</span>
                            <span class="inline-flex items-center justify-center w-3 h-3 p-3 ms-3 text-sm font-medium text-red-800 bg-red-100 rounded-full dark:bg-red-900 dark:text-red-300">N</span>
                        </a>
                    </li>
                    <li>
                        <!-- Menu Admin: Broadcast Pesan -->
                        <a href="{{ route('admin.broadcast.index') }}" class="flex items-center px-3 py-2.5 text-gray-900 rounded-xl dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 group {{ request()->routeIs('admin.broadcast.*') ? 'bg-primary-50 text-primary-600 dark:bg-gray-700 dark:text-primary-400' : '' }}">
                            <svg class="flex-shrink-0 w-5 h-5 transition duration-75 {{ request()->routeIs('admin.broadcast.*') ? 'text-primary-600' : 'text-gray-500 dark:text-gray-400 group-hover:text-gray-900 dark:group-hover:text-white' }}" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M17 4H3a1 1 0 0 0-1 1v10a1 1 0 0 0 1 1h14a1 1 0 0 0 1-1V5a1 1 0 0 0-1-1ZM5 7v6a1 1 0 0 1-2 0V7a1 1 0 0 1 2 0Zm10 0v6a1 1 0 0 1-2 0V7a1 1 0 0 1 2 0Zm-4 0v6a1 1 0 0 1-2 0V7a1 1 0 0 1 2 0Z"/>
                            </svg>
                            <span class="flex-1 ms-3 font-medium whitespace-nowrap">Broadcast Pesan</span>
                        </a>
                    </li>
                    <li>
                        <!-- Menu Admin: Heatmap (Isu Nasional) -->
                        <a href="{{ route('heatmap.index') }}" class="flex items-center px-3 py-2.5 text-gray-900 rounded-xl dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 group {{ request()->routeIs('heatmap.*') ? 'bg-primary-50 text-primary-600 dark:bg-gray-700 dark:text-primary-400' : '' }}">
                            <svg class="flex-shrink-0 w-5 h-5 transition duration-75 {{ request()->routeIs('heatmap.*') ? 'text-primary-600' : 'text-gray-500 dark:text-gray-400 group-hover:text-gray-900 dark:group-hover:text-white' }}" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                            </svg>
                            <span class="flex-1 ms-3 font-medium whitespace-nowrap">Isu Nasional (Heatmap)</span>
                        </a>
                    </li>
                @endif

                <!-- LOGOUT BUTTON -->
                <li class="mt-4 pt-4 border-t border-gray-200 dark:border-gray-700">
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit"
                            class="flex w-full items-center p-2 text-red-600 rounded-lg hover:bg-red-50 dark:text-red-400 dark:hover:bg-red-900/20 group transition-colors">
                            <svg class="w-5 h-5 transition duration-75" aria-hidden="true"
                                xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                            </svg>
                            <span class="ms-3 font-medium">Logout</span>
                        </button>
                    </form>
                </li>

            </ul>
        </div>
    </aside>
    <!-- END SIDEBAR -->

    <!-- MAIN CONTENT AREA -->
    <div class="p-4 sm:ml-64 mt-14">
        <!-- Slot untuk konten spesifik halaman (Dashboard User/Admin) -->
        {{ $slot }}
    </div>

    <!-- Script Flowbite JS, ditempatkan di akhir body untuk performa, sudah dipindahkan ke head -->

</body>
</html>
