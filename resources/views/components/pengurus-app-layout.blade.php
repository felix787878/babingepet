<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }} - Pengurus</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet"> {{-- Tambahkan ini untuk Material Icons --}}

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')
</head>
<body class="font-sans antialiased bg-gray-100">
    <div x-data="{ sidebarOpen: false }" class="flex h-screen bg-gray-100">
        @include('layouts.partials.pengurus-sidebar')

        <div class="flex-1 flex flex-col overflow-hidden">
            <header class="bg-white shadow relative z-10"> {{-- Tambahkan relative z-10 --}}
                <div class="max-w-7xl mx-auto py-4 px-4 sm:px-6 lg:px-8 flex justify-between items-center">
                    {{-- Tombol untuk toggle sidebar di mobile --}}
                    <button @click.stop="sidebarOpen = !sidebarOpen" class="md:hidden text-gray-500 focus:outline-none">
                        <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M4 6H20M4 12H20M4 18H11Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </button>
                    <h1 class="text-lg font-semibold text-gray-800">{{ $header ?? (Auth::user()->managesUkmOrmawa->name ?? 'Dashboard Pengurus') }}</h1> {{-- Dinamiskan header --}}
                    
                    {{-- Dropdown Profil Pengurus (mirip dengan layout app.blade.php) --}}
                    <div class="flex items-center ml-4">
                        <div class="relative">
                            <div>
                                <button type="button" class="max-w-xs bg-white rounded-full flex items-center text-sm focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-slate-300" id="user-menu-button-pengurus" aria-expanded="false" aria-haspopup="true" @click="open = ! open">
                                    <span class="sr-only">Open user menu</span>
                                    <span class="material-icons text-3xl text-gray-500 hover:text-gray-700">account_circle</span>
                                </button>
                            </div>
                            <div x-show="open" @click.outside="open = false" class="origin-top-right absolute right-0 mt-2 w-56 rounded-md shadow-lg py-1 bg-white ring-1 ring-black ring-opacity-5 focus:outline-none" role="menu" aria-orientation="vertical" aria-labelledby="user-menu-button-pengurus" tabindex="-1">
                                <div class="px-4 py-3">
                                    <p class="text-sm font-semibold text-gray-900">{{ Auth::user()->name }}</p>
                                    <p class="text-xs text-gray-500 truncate">{{ Auth::user()->email }}</p>
                                    <span class="mt-1 inline-block text-xs font-medium px-2 py-0.5 rounded-full bg-slate-200 text-slate-700">{{ ucfirst(Auth::user()->role) }}</span>
                                </div>
                                <div class="border-t border-gray-100"></div>
                                <a href="{{ route('settings.index') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100" role="menuitem" tabindex="-1">
                                    <span class="material-icons text-base mr-2 align-middle">manage_accounts</span>Pengaturan Akun
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
                    </div>
                </div>
            </header>

            <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-100 p-6">
                {{ $slot }}
            </main>
        </div>
    </div>
    <script>
        // Memastikan sidebar toggle berfungsi di layout pengurus
        document.addEventListener('DOMContentLoaded', function() {
            const userMenuButtonPengurus = document.getElementById('user-menu-button-pengurus');
            const userMenuPengurus = document.getElementById('user-menu-pengurus'); // Menggunakan ID yang berbeda

            if (userMenuButtonPengurus && userMenuPengurus) {
                userMenuButtonPengurus.addEventListener('click', (event) => {
                    event.stopPropagation();
                    userMenuPengurus.classList.toggle('hidden');
                });

                document.addEventListener('click', function(event) {
                    if (userMenuPengurus && !userMenuPengurus.classList.contains('hidden')) {
                        if (!userMenuButtonPengurus.contains(event.target) && !userMenuPengurus.contains(event.target)) {
                            userMenuPengurus.classList.add('hidden');
                        }
                    }
                });
            }
        });
    </script>
    @stack('scripts')
</body>
</html>