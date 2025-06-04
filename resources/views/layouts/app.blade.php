<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Laravel') }} - Dashboard</title>
    <link rel="shortcut icon" type="image/png" href="{{ asset('/logo.png') }}" />
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        .sidebar-scroll::-webkit-scrollbar {
            width: 6px;
        }
        .sidebar-scroll::-webkit-scrollbar-thumb {
            background-color: rgba(255,255,255,0.3);
            border-radius: 3px;
        }
        .sidebar-scroll::-webkit-scrollbar-track {
            background: transparent;
        }
    </style>
</head>
<body class="bg-gray-100 font-inter">
    <div class="flex h-screen overflow-hidden">
        <aside id="sidebar" class="bg-red-800 text-white w-64 min-h-screen fixed inset-y-0 left-0 top-0 z-40 transform -translate-x-full transition-transform duration-300 ease-in-out md:translate-x-0 md:relative md:flex md:flex-col sidebar-scroll overflow-y-auto pt-16">
            <nav class="pt-2 pb-4 px-2 space-y-1">
                <a href="{{ route('home') }}" class="flex items-center px-3 py-2.5 text-sm font-medium rounded-md hover:bg-red-700 {{ request()->routeIs('home') ? 'bg-red-900' : '' }}">
                    <span class="material-icons mr-3">dashboard</span>
                    Dashboard
                </a>
                <a href="{{ route('ukm-ormawa.index') }}" class="flex items-center px-3 py-2.5 text-sm font-medium rounded-md hover:bg-red-700 {{ request()->routeIs('ukm-ormawa.index') || request()->routeIs('ukm-ormawa.show') || request()->routeIs('ukm-ormawa.apply.form') ? 'bg-red-900' : '' }}">
                    <span class="material-icons mr-3">list_alt</span>
                    Daftar UKM/Ormawa
                </a>
                <a href="{{ route('my-activities.index') }}" class="flex items-center px-3 py-2.5 text-sm font-medium rounded-md hover:bg-red-700 {{ request()->routeIs('my-activities.index') ? 'bg-red-900' : '' }}">
                    <span class="material-icons mr-3">event_note</span>
                    Kegiatan Saya
                </a>
                {{-- MENU LOWONGAN PENDAFTARAN TELAH DIHAPUS --}}
                <a href="{{ route('settings.index') }}" class="flex items-center px-3 py-2.5 text-sm font-medium rounded-md hover:bg-red-700 {{ request()->routeIs('settings.index') ? 'bg-red-900' : '' }}">
                    <span class="material-icons mr-3">settings</span>
                    Pengaturan
                </a>
            </nav>
        </aside>

        <div class="flex-1 flex flex-col overflow-hidden">
            <header class="bg-red-700 text-white shadow-md fixed w-full top-0 left-0 z-30 md:relative">
                <div class="container mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="flex items-center justify-between h-16">
                        <div class="flex items-center">
                            <button id="sidebarToggle" class="text-white focus:outline-none md:hidden mr-3">
                                <span class="material-icons">menu</span>
                            </button>
                            <a href="{{ route('home') }}" class="flex items-center">
                                <img src="{{ asset('/logo.png') }}" alt="Logo" class="h-8 w-auto mr-2">
                                <span class="text-xl font-semibold">UKM Connect</span>
                            </a>
                        </div>

                        <div class="flex-1 flex justify-center px-2 lg:ml-6 lg:justify-end">
                            <div class="max-w-md w-full lg:max-w-xs">
                                <label for="search" class="sr-only">Search</label>
                                <div class="relative text-gray-400 focus-within:text-gray-600">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <span class="material-icons text-sm">search</span>
                                    </div>
                                    <input id="search" name="search" class="block w-full bg-red-600 placeholder-red-300 text-white border border-transparent rounded-md py-2 pl-10 pr-3 leading-5 focus:outline-none focus:bg-white focus:text-gray-900 focus:placeholder-gray-500 focus:ring-0 focus:border-white sm:text-sm" placeholder="Search..." type="search">
                                </div>
                            </div>
                        </div>

                        <div class="flex items-center ml-4">
                            @auth
                                <button class="p-1 rounded-full text-red-200 hover:text-white focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-red-700 focus:ring-white">
                                    <span class="material-icons">notifications</span>
                                </button>

                                <div class="ml-3 relative">
                                    <div>
                                        <button type="button" class="max-w-xs bg-red-700 rounded-full flex items-center text-sm focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-red-700 focus:ring-white" id="user-menu-button" aria-expanded="false" aria-haspopup="true">
                                            <span class="sr-only">Open user menu</span>
                                            <span class="material-icons text-3xl text-red-200 hover:text-white">account_circle</span>
                                        </button>
                                    </div>
                                    <div id="user-menu" class="hidden origin-top-right absolute right-0 mt-2 w-56 rounded-md shadow-lg py-1 bg-white ring-1 ring-black ring-opacity-5 focus:outline-none" role="menu" aria-orientation="vertical" aria-labelledby="user-menu-button" tabindex="-1">
                                        <div class="px-4 py-3">
                                            <p class="text-sm font-semibold text-gray-900">{{ Auth::user()->name }}</p>
                                            <p class="text-xs text-gray-500 truncate">{{ Auth::user()->email }}</p>
                                            <span class="mt-1 inline-block text-xs font-medium px-2 py-0.5 rounded-full bg-gray-200 text-gray-700">{{ ucfirst(Auth::user()->role) }}</span>
                                        </div>
                                        <div class="border-t border-gray-100"></div>
                                        <a href="{{ route('settings.index') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100" role="menuitem" tabindex="-1">
                                            <span class="material-icons text-base mr-2 align-middle">manage_accounts</span>Profil & Pengaturan Akun
                                        </a>
                                        <div class="border-t border-gray-100"></div>
                                        <form method="POST" action="{{ route('logout') }}">
                                            @csrf
                                            <button type="submit" class="flex items-center w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-red-50 hover:text-red-700" role="menuitem" tabindex="-1">
                                                <span class="material-icons text-base mr-2 align-middle">logout</span>
                                                Logout
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            @else
                                <a href="{{ route('login') }}" class="text-sm font-medium hover:text-red-200">Login</a>
                                <a href="{{ route('register') }}" class="ml-4 text-sm font-medium hover:text-red-200">Register</a>
                            @endauth
                        </div>
                    </div>
                </div>
            </header>

            <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-100 mt-16 md:mt-0">
                <div class="container mx-auto px-6 py-8">
                    @yield('content')
                </div>
            </main>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const sidebarToggle = document.getElementById('sidebarToggle');
            const sidebar = document.getElementById('sidebar');
            const userMenuButton = document.getElementById('user-menu-button');
            const userMenu = document.getElementById('user-menu');

            if (sidebarToggle && sidebar) {
                sidebarToggle.addEventListener('click', () => {
                    sidebar.classList.toggle('-translate-x-full');
                    sidebar.classList.toggle('translate-x-0');
                });
            }
            
            if (sidebar) {
                sidebar.querySelectorAll('a').forEach(link => {
                    link.addEventListener('click', () => {
                        if (window.innerWidth < 768 && !sidebar.classList.contains('-translate-x-full')) {
                            sidebar.classList.add('-translate-x-full');
                            sidebar.classList.remove('translate-x-0');
                        }
                    });
                });
            }

            if (userMenuButton && userMenu) {
                userMenuButton.addEventListener('click', (event) => {
                    event.stopPropagation(); 
                    userMenu.classList.toggle('hidden');
                });

                document.addEventListener('click', function(event) {
                    if (userMenu && !userMenu.classList.contains('hidden')) {
                        if (!userMenuButton.contains(event.target) && !userMenu.contains(event.target)) {
                            userMenu.classList.add('hidden');
                        }
                    }
                });
            }
        });
    </script>
    @stack('scripts')
</body>
</html>