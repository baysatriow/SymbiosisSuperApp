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
                        primary: { "50": "#ecfdf5", "100": "#d1fae5", "200": "#a7f3d0", "300": "#6ee7b7", "400": "#34d399", "500": "#10b981", "600": "#059669", "700": "#047857", "800": "#065f46", "900": "#064e3b" }
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
        :root {
            --sidebar-width: 260px;
            --sidebar-mini-width: 80px;
        }

        #logo-sidebar {
            width: var(--sidebar-width);
            transition: width 0.3s cubic-bezier(0.4, 0, 0.2, 1), transform 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        #main-content {
            margin-left: var(--sidebar-width);
            transition: margin-left 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        /* Mini Sidebar State */
        .sidebar-mini #logo-sidebar {
            width: var(--sidebar-mini-width);
        }

        .sidebar-mini #main-content {
            margin-left: var(--sidebar-mini-width);
        }

        .sidebar-mini .sidebar-text,
        .sidebar-mini .sidebar-badge,
        .sidebar-mini .sidebar-header-text,
        .sidebar-mini .sidebar-arrow {
            display: none;
        }

        .sidebar-mini .sidebar-item {
            justify-content: center;
            padding-left: 0;
            padding-right: 0;
        }

        .sidebar-mini .sidebar-header {
            justify-content: center;
        }

        .no-scrollbar::-webkit-scrollbar {
            display: none;
        }

        .no-scrollbar {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }

        /* Custom Hover Effect for Sidebar Items */
        .sidebar-item-active {
            background: linear-gradient(135deg, rgba(16, 185, 129, 0.1) 0%, rgba(16, 185, 129, 0.05) 100%);
            color: #059669;
            box-shadow: inset 0 0 0 1px rgba(16, 185, 129, 0.1);
        }

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
    <nav class="fixed top-0 z-50 w-full bg-white/80 backdrop-blur-md border-b border-gray-100 dark:bg-gray-800/80 dark:border-gray-700 shadow-sm transition-all duration-300">
        <div class="px-3 py-2.5 lg:px-5">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-4">
                    <!-- Sidebar Toggle Button -->
                    <button id="sidebar-toggle-btn" type="button" class="p-2 text-gray-500 rounded-xl hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-gray-100 transition-all active:scale-95">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
                    </button>

                    <!-- Search Bar (Premium) -->
                    <div class="hidden md:flex items-center relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-4 w-4 text-gray-400 font-bold" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                        </div>
                        <input type="text" placeholder="Cari fitur atau data..." class="block w-64 lg:w-96 pl-10 pr-3 py-2 bg-gray-50 border-transparent border focus:bg-white focus:border-primary-500 focus:ring-0 rounded-2xl text-sm transition-all shadow-sm">
                    </div>
                </div>

                <div class="flex items-center gap-2 md:gap-4">
                    <!-- Notification Badge (Premium Style) -->
                    <button class="p-2.5 text-gray-400 hover:text-gray-900 hover:bg-gray-50 rounded-xl transition-all relative group">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
                        <span class="absolute top-2.5 right-2.5 block h-2.5 w-2.5 rounded-full bg-rose-500 ring-2 ring-white"></span>
                    </button>

                    <button class="hidden sm:flex p-2.5 text-gray-400 hover:text-gray-900 hover:bg-gray-50 rounded-xl transition-all">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/></svg>
                    </button>

                    <div class="w-px h-6 bg-gray-200 mx-1 hidden sm:block"></div>

                    <!-- User Profile Dropdown -->
                    <div class="flex items-center">
                        <button type="button" class="flex items-center gap-3 p-1 rounded-2xl hover:bg-gray-50 transition-all border border-transparent hover:border-gray-100 group" id="user-menu-button" aria-expanded="false" data-dropdown-toggle="dropdown-user">
                            <div class="w-9 h-9 rounded-xl bg-gradient-to-tr from-primary-500 to-primary-600 flex items-center justify-center text-white font-black text-sm shadow-md shadow-primary-100 group-hover:scale-105 transition-transform border border-white/20">
                                {{ substr(Auth::user()->full_name ?? 'U', 0, 1) }}
                            </div>
                            <div class="hidden lg:block text-left pr-2">
                                <p class="text-xs font-black text-gray-900 leading-tight uppercase tracking-tight">{{ Auth::user()->full_name }}</p>
                                <p class="text-[10px] font-bold text-gray-400 leading-tight">{{ Auth::user()->role === 'admin' ? 'Administrator' : 'Premium User' }}</p>
                            </div>
                        </button>
                        
                        <!-- Dropdown menu -->
                        <div class="z-50 hidden my-4 text-base list-none bg-white divide-y divide-gray-100 rounded-2xl shadow-2xl border border-gray-100 min-w-[200px] animate-in slide-in-from-top-2 duration-200" id="dropdown-user">
                            <div class="px-4 py-3 bg-gray-50/50 rounded-t-2xl">
                                <p class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-1">Signed in as</p>
                                <p class="text-sm font-black text-gray-900 truncate">{{ Auth::user()->email }}</p>
                            </div>
                            <ul class="py-2" role="none">
                                <li><a href="{{ Auth::user()->role === 'admin' ? route('admin.dashboard') : route('user.dashboard') }}" class="flex items-center gap-3 px-4 py-2.5 text-sm text-gray-700 hover:bg-primary-50 hover:text-primary-700 font-bold transition-all"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg> Dashboard</a></li>
                                @if(Auth::user()->role !== 'admin')
                                    <li><a href="{{ route('user.profile') }}" class="flex items-center gap-3 px-4 py-2.5 text-sm text-gray-700 hover:bg-primary-50 hover:text-primary-700 font-bold transition-all"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg> Profil Saya</a></li>
                                @endif
                            </ul>
                            <div class="py-1">
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="flex items-center gap-3 w-full px-4 py-2.5 text-sm text-rose-600 hover:bg-rose-50 font-bold transition-all"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg> Logout</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <aside id="logo-sidebar" class="fixed top-0 left-0 z-40 h-screen transition-all bg-white dark:bg-gray-800 shadow-2xl shadow-gray-200/50 border-r border-gray-100 dark:border-gray-700 flex flex-col" aria-label="Sidebar">
        <!-- Logo Area -->
        <div class="h-20 flex items-center px-6 shrink-0 border-b border-gray-50/50">
            <a href="{{ Auth::user()->role === 'admin' ? route('admin.dashboard') : route('user.dashboard') }}" class="flex items-center gap-3 group overflow-hidden">
                <div class="w-9 h-9 flex-shrink-0 bg-gradient-to-tr from-primary-500 to-primary-600 rounded-xl flex items-center justify-center shadow-lg shadow-primary-100 group-hover:rotate-12 transition-transform">
                    <img src="{{ asset('images/logoSymbiosis.svg') }}" alt="" class="w-6 h-6 brightness-0 invert">
                </div>
                <span class="sidebar-text font-black text-xl text-gray-900 tracking-tighter uppercase dark:text-white">Symbiosis</span>
            </a>
        </div>

        <!-- Menu area -->
        <div class="flex-1 px-4 py-6 overflow-y-auto no-scrollbar">
            <ul class="space-y-1.5 font-medium">
                <!-- DASHBOARD -->
                <li>
                    <a href="{{ Auth::user()->role === 'admin' ? route('admin.dashboard') : route('user.dashboard') }}"
                        class="sidebar-item flex items-center px-3 py-3 text-gray-500 rounded-2xl hover:bg-gray-50 hover:text-gray-900 group transition-all {{ (request()->routeIs('user.dashboard') || request()->routeIs('admin.dashboard')) ? 'sidebar-item-active !text-primary-600 font-bold' : '' }}">
                        <svg class="w-5 h-5 transition-transform group-hover:scale-110" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                        <span class="sidebar-text ms-3 text-sm">Dashboard</span>
                    </a>
                </li>

                <!-- USER MENU -->
                @if(Auth::user()->role !== 'admin')
                    <li class="sidebar-header pt-6 pb-2 px-3 flex items-center">
                        <span class="sidebar-header-text text-[10px] font-black text-gray-400 uppercase tracking-[0.2em]">Sistem & Data</span>
                    </li>
                    <li>
                        <a href="{{ route('user.documents') }}"
                            class="sidebar-item flex items-center px-3 py-3 text-gray-500 rounded-2xl hover:bg-gray-50 hover:text-gray-900 group transition-all {{ request()->routeIs('user.documents') ? 'sidebar-item-active !text-primary-600' : '' }}">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2h-5l-5 5v-5z"></path></svg>
                            <span class="sidebar-text ms-3 text-sm flex-1">Repository</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('user.esg.index') }}"
                            class="sidebar-item flex items-center px-3 py-3 text-gray-500 rounded-2xl hover:bg-gray-50 hover:text-gray-900 group transition-all {{ request()->routeIs('user.esg.*') ? 'sidebar-item-active !text-primary-600' : '' }}">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                            <span class="sidebar-text ms-3 text-sm flex-1">Laporan ESG</span>
                            <span class="sidebar-badge inline-flex items-center justify-center px-2 py-0.5 text-[8px] font-black text-primary-700 bg-primary-100 rounded-full tracking-tighter uppercase ring-1 ring-primary-200">AI</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('user.chat.index') }}"
                            class="sidebar-item flex items-center px-3 py-3 text-gray-500 rounded-2xl hover:bg-gray-50 hover:text-gray-900 group transition-all {{ request()->routeIs('user.chat.*') ? 'sidebar-item-active !text-primary-600' : '' }}">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"></path></svg>
                            <span class="sidebar-text ms-3 text-sm">Chatbot AI</span>
                        </a>
                    </li>

                    <li class="sidebar-header pt-6 pb-2 px-3 flex items-center">
                        <span class="sidebar-header-text text-[10px] font-black text-gray-400 uppercase tracking-[0.2em]">Analitik & Geo</span>
                    </li>
                    <li>
                        <a href="{{ route('user.sroi.index') }}"
                            class="sidebar-item flex items-center px-3 py-3 text-gray-500 rounded-2xl hover:bg-gray-50 hover:text-gray-900 group transition-all {{ request()->routeIs('user.sroi.index') ? 'sidebar-item-active !text-primary-600' : '' }}">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M9 7h6m0 2H9m-4 3h16l-5.197-5.197a7.5 7.5 0 00-10.606 0L4 12z"></path><path d="M9 17v1a3 3 0 106 0v-1"></path></svg>
                            <span class="sidebar-text ms-3 text-sm">SROI Calculator</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('heatmap.index') }}"
                            class="sidebar-item flex items-center px-3 py-3 text-gray-500 rounded-2xl hover:bg-gray-50 hover:text-gray-900 group transition-all {{ request()->routeIs('heatmap.index') ? 'sidebar-item-active !text-primary-600' : '' }}">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
                            <span class="sidebar-text ms-3 text-sm">National Issues</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('geoportal.index') }}"
                            class="sidebar-item flex items-center px-3 py-3 text-gray-500 rounded-2xl hover:bg-gray-50 hover:text-gray-900 group transition-all {{ request()->routeIs('geoportal.index') ? 'sidebar-item-active !text-primary-600' : '' }}">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            <span class="sidebar-text ms-3 text-sm">Geoportal Hub</span>
                        </a>
                    </li>

                    <li class="sidebar-header pt-6 pb-2 px-3 flex items-center">
                        <span class="sidebar-header-text text-[10px] font-black text-gray-400 uppercase tracking-[0.2em]">Pengaturan</span>
                    </li>
                    <li>
                        <a href="{{ route('user.profile') }}"
                            class="sidebar-item flex items-center px-3 py-3 text-gray-500 rounded-2xl hover:bg-gray-50 hover:text-gray-900 group transition-all {{ request()->routeIs('user.profile') ? 'sidebar-item-active !text-primary-600' : '' }}">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                            <span class="sidebar-text ms-3 text-sm">Profil Akun</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('user.company') }}"
                            class="sidebar-item flex items-center px-3 py-3 text-gray-500 rounded-2xl hover:bg-gray-50 hover:text-gray-900 group transition-all {{ request()->routeIs('user.company') ? 'sidebar-item-active !text-primary-600' : '' }}">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                            <span class="sidebar-text ms-3 text-sm">Profil Perusahaan</span>
                        </a>
                    </li>
                @endif

                <!-- ADMIN MENU -->
                @if(Auth::user()->role === 'admin')
                    <li class="sidebar-header pt-6 pb-2 px-3 flex items-center">
                        <span class="sidebar-header-text text-[10px] font-black text-gray-400 uppercase tracking-[0.2em]">Management</span>
                    </li>
                    <li>
                        <a href="{{ route('admin.documents.users') }}"
                            class="sidebar-item flex items-center px-3 py-3 text-gray-500 rounded-2xl hover:bg-gray-50 hover:text-gray-900 group transition-all {{ request()->routeIs('admin.documents.*') ? 'sidebar-item-active !text-primary-600' : '' }}">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path></svg>
                            <span class="sidebar-text ms-3 text-sm flex-1">Monitoring Dokumen</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.master.documents.index') }}"
                            class="sidebar-item flex items-center px-3 py-3 text-gray-500 rounded-2xl hover:bg-gray-50 hover:text-gray-900 group transition-all {{ request()->routeIs('admin.master.*') ? 'sidebar-item-active !text-primary-600' : '' }}">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                            <span class="sidebar-text ms-3 text-sm flex-1">Master Data</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.users.index') }}"
                            class="sidebar-item flex items-center px-3 py-3 text-gray-500 rounded-2xl hover:bg-gray-50 hover:text-gray-900 group transition-all {{ request()->routeIs('admin.users.*') ? 'sidebar-item-active !text-primary-600' : '' }}">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                            <span class="sidebar-text ms-3 text-sm">Kelola Pengguna</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.broadcast.index') }}"
                            class="sidebar-item flex items-center px-3 py-3 text-gray-500 rounded-2xl hover:bg-gray-50 hover:text-gray-900 group transition-all {{ request()->routeIs('admin.broadcast.*') ? 'sidebar-item-active !text-primary-600' : '' }}">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"></path></svg>
                            <span class="sidebar-text ms-3 text-sm">Broadcast Center</span>
                        </a>
                    </li>

                    <li class="sidebar-header pt-6 pb-2 px-3 flex items-center">
                        <span class="sidebar-header-text text-[10px] font-black text-gray-400 uppercase tracking-[0.2em]">Analitik Dashboard</span>
                    </li>
                    <li>
                        <a href="{{ route('heatmap.index') }}"
                            class="sidebar-item flex items-center px-3 py-3 text-gray-500 rounded-2xl hover:bg-gray-50 hover:text-gray-900 group transition-all {{ request()->routeIs('heatmap.index') ? 'sidebar-item-active !text-primary-600' : '' }}">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
                            <span class="sidebar-text ms-3 text-sm">Sentiment Heatmap</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('geoportal.index') }}"
                            class="sidebar-item flex items-center px-3 py-3 text-gray-500 rounded-2xl hover:bg-gray-50 hover:text-gray-900 group transition-all {{ request()->routeIs('geoportal.index') ? 'sidebar-item-active !text-primary-600' : '' }}">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            <span class="sidebar-text ms-3 text-sm">Geoportal Monitor</span>
                        </a>
                    </li>
                @endif
            </ul>
        </div>

        <!-- Sidebar Footer -->
        <div class="p-4 border-t border-gray-50 flex items-center justify-between bg-gray-50/50">
            <div class="flex items-center gap-2">
                <button class="p-2 text-gray-400 hover:text-primary-600 hover:bg-white rounded-xl transition-all"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364-6.364l-.707.707M6.343 17.657l-.707.707m12.728 0l-.707-.707M6.343 6.343l-.707-.707M12 5a7 7 0 000 14 7 7 0 000-14z"/></svg></button>
                <button class="p-2 text-gray-400 hover:text-primary-600 hover:bg-white rounded-xl transition-all"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5h12M9 3v2m1.048 9.5a7 7 0 001.285-1.285M9.143 12.143l1.857 1.857M11.5 15.5H19a2 2 0 002-2v-9a2 2 0 00-2-2h-3l-2 2h-6l-2-2H3a2 2 0 00-2 2v9a2 2 0 002 2h3.5l1.643 1.643a1 1 0 001.414 0z"/></svg></button>
            </div>
            <button class="sidebar-text p-2 text-gray-400 hover:text-rose-600 hover:bg-rose-50 rounded-xl transition-all" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
            </button>
            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="hidden">@csrf</form>
        </div>
    </aside>
    <!-- MAIN CONTENT -->
    <div id="main-content" class="p-6 transition-all duration-300 min-h-screen pt-24 bg-gray-50/50">
        {{ $slot }}
    </div>

    <!-- Scripts -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.2.1/flowbite.min.js"></script>

    <!-- Global Toast & Sidebar Logic -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Sidebar Toggle Logic (Premium)
            const sidebarBtn = document.getElementById('sidebar-toggle-btn');
            const body = document.body;
            const sidebar = document.getElementById('logo-sidebar');
            
            // Check for saved state
            if (localStorage.getItem('sidebar-state') === 'mini') {
                body.classList.add('sidebar-mini');
            }

            sidebarBtn.addEventListener('click', function () {
                const isMobile = window.innerWidth < 1024;
                
                if (isMobile) {
                    sidebar.classList.toggle('-translate-x-full');
                } else {
                    body.classList.toggle('sidebar-mini');
                    localStorage.setItem('sidebar-state', body.classList.contains('sidebar-mini') ? 'mini' : 'full');
                }
            });

            // Close sidebar when clicking outside on mobile
            document.addEventListener('click', function(event) {
                const isMobile = window.innerWidth < 1024;
                if (isMobile && !sidebar.contains(event.target) && !sidebarBtn.contains(event.target)) {
                    sidebar.classList.add('-translate-x-full');
                }
            });

            // --- GLOBAL TOAST FUNCTION ---
            window.showToast = function (message, type = 'info') {
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