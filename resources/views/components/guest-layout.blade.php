<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Symbiosis') }}</title>
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700&display=swap" rel="stylesheet" />
    <!-- Scripts & Styles -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: {"50":"#ecfdf5","100":"#d1fae5","200":"#a7f3d0","300":"#6ee7b7","400":"#34d399","500":"#10b981","600":"#059669","700":"#047857","800":"#065f46","900":"#064e3b"}
                    }
                }
            }
        }
    </script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.2.1/flowbite.min.css" rel="stylesheet" />
    <style>
        @keyframes slideIn { from { transform: translateX(100%); opacity: 0; } to { transform: translateX(0); opacity: 1; } }
        @keyframes fadeOut { from { opacity: 1; } to { opacity: 0; } }
        .toast-enter { animation: slideIn 0.3s ease-out forwards; }
        .toast-exit { animation: fadeOut 0.3s ease-in forwards; }
    </style>
</head>
<body class="bg-gray-50 dark:bg-gray-900 font-sans antialiased">

    <!-- TOAST CONTAINER (High Z-Index) -->
    <div id="toast-container" class="fixed top-5 right-5 z-50 flex flex-col gap-2"></div>

    <div class="flex flex-col items-center justify-center px-6 py-8 mx-auto md:h-screen lg:py-0">
        <a href="/" class="flex items-center mb-6 text-2xl font-semibold text-gray-900 dark:text-white">
            <div class="w-8 h-8 mr-2 bg-primary-600 rounded-lg flex items-center justify-center text-white font-bold">S</div>
            Symbiosis App
        </a>
        <div class="w-full bg-white rounded-lg shadow dark:border md:mt-0 sm:max-w-md xl:p-0 dark:bg-gray-800 dark:border-gray-700">
            <div class="p-6 space-y-4 md:space-y-6 sm:p-8">
                {{ $slot }}
            </div>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.2.1/flowbite.min.js"></script>

    <!-- Toast Logic -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            window.showToast = function(message, type = 'info') {
                const container = document.getElementById('toast-container');
                let icon = '', colorClass = '';

                if (type === 'success') {
                    colorClass = 'text-green-500 bg-green-100 dark:bg-green-800 dark:text-green-200';
                    icon = '<svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5Zm3.707 8.207-4 4a1 1 0 0 1-1.414 0l-2-2a1 1 0 0 1 1.414-1.414L9 10.586l3.293-3.293a1 1 0 0 1 1.414 1.414Z"/></svg>';
                } else if (type === 'error') {
                    colorClass = 'text-red-500 bg-red-100 dark:bg-red-800 dark:text-red-200';
                    icon = '<svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5Zm3.707 11.793a1 1 0 1 1-1.414 1.414L10 11.414l-2.293 2.293a1 1 0 0 1-1.414-1.414L8.586 10 6.293 7.707a1 1 0 0 1 1.414-1.414L10 8.586l2.293-2.293a1 1 0 0 1 1.414 1.414L11.414 10l2.293 2.293Z"/></svg>';
                } else if (type === 'warning') {
                    colorClass = 'text-orange-500 bg-orange-100 dark:bg-orange-700 dark:text-orange-200';
                    icon = '<svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5ZM10 15a1 1 0 1 1 0-2 1 1 0 0 1 0 2Zm1-4a1 1 0 0 1-2 0V6a1 1 0 0 1 2 0v5Z"/></svg>';
                }

                const toastHtml = `
                    <div class="toast-enter flex items-center w-full max-w-xs p-4 text-gray-500 bg-white rounded-lg shadow border border-gray-200 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-700" role="alert">
                        <div class="inline-flex items-center justify-center flex-shrink-0 w-8 h-8 ${colorClass} rounded-lg">
                            ${icon}
                        </div>
                        <div class="ms-3 text-sm font-normal">${message}</div>
                        <button type="button" class="ms-auto -mx-1.5 -my-1.5 bg-white text-gray-400 hover:text-gray-900 rounded-lg focus:ring-2 focus:ring-gray-300 p-1.5 hover:bg-gray-100 inline-flex items-center justify-center h-8 w-8 dark:text-gray-500 dark:hover:text-white dark:bg-gray-800 dark:hover:bg-gray-700" onclick="this.parentElement.remove()">
                            <svg class="w-3 h-3" fill="none" viewBox="0 0 14 14"><path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/></svg>
                        </button>
                    </div>
                `;
                const wrapper = document.createElement('div');
                wrapper.innerHTML = toastHtml.trim();
                const toastEl = wrapper.firstChild;
                container.appendChild(toastEl);
                setTimeout(() => {
                    toastEl.classList.remove('toast-enter'); toastEl.classList.add('toast-exit');
                    toastEl.addEventListener('animationend', () => toastEl.remove());
                }, 5000);
            }

            @if(session('success')) showToast("{{ session('success') }}", 'success'); @endif
            @if(session('error')) showToast("{{ session('error') }}", 'error'); @endif
            @if(session('warning')) showToast("{{ session('warning') }}", 'warning'); @endif
            @if($errors->any()) showToast("Ada data yang tidak valid.", 'error'); @endif
        });
    </script>
</body>
</html>
