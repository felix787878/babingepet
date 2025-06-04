@extends('layouts.auth') {{-- Menggunakan layout baru --}}

@section('content')
<div class="flex flex-col justify-center items-center min-h-screen pt-6 sm:pt-0">
    {{-- Logo dan Judul Aplikasi --}}
    <div class="mb-8 text-center">
        {{-- Ganti YOUR_UKM_CONNECT_LOGO.PNG dengan path ke logo UKM Connect Anda --}}
        <img src="{{ asset('images/UKM_CONNECT_LOGO.PNG') }}" alt="UKM Connect Logo" class="w-24 h-24 mx-auto mb-3">
        <h1 class="text-3xl font-bold text-gray-700">UKM Connect</h1>
    </div>

    {{-- Kontainer Form --}}
    <div class="w-full sm:max-w-md mt-2 px-6 py-8 bg-white shadow-xl rounded-lg overflow-hidden relative">
        {{-- Link Register --}}
        <a href="{{ route('register') }}" class="absolute top-5 right-6 text-sm text-indigo-600 hover:text-indigo-800 transition-colors duration-200">
            Register &rarr;
        </a>

        <h2 class="text-2xl font-bold text-center text-gray-800 mb-8">Sign In</h2>

        {{-- Pesan Error atau Sukses --}}
        @if(session('error') || session('success'))
            @php
                $type = session('error') ? 'error' : 'success';
                $message = session($type);
                $bgColor = $type === 'error' ? 'bg-red-100 text-red-700' : 'bg-green-100 text-green-700';
                $id = $type . 'Message';
                $closeFunction = 'close' . ucfirst($type) . 'Message';
            @endphp
            <div id="{{ $id }}" class="{{ $bgColor }} p-3 rounded-md mb-6 relative text-sm">
                <span>{{ $message }}</span>
                <button class="absolute top-1/2 right-3 transform -translate-y-1/2 text-lg" onclick="{{ $closeFunction }}()">
                    <span class="material-icons text-sm align-middle">close</span>
                </button>
            </div>
            <script>
                function {{ $closeFunction }}() {
                    document.getElementById('{{ $id }}').style.display = 'none';
                }
                setTimeout(function() {
                    var el = document.getElementById('{{ $id }}');
                    if (el) el.style.display = 'none';
                }, 7000); // Notifikasi hilang setelah 7 detik
            </script>
        @endif

        {{-- Menampilkan semua error validasi --}}
        @if ($errors->any())
            <div class="bg-red-100 text-red-700 p-3 rounded-md mb-6 text-sm">
                <ul class="list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('login') }}">
            @csrf

            {{-- Input Username (tetap menggunakan 'email' untuk backend Laravel) --}}
            <div class="mb-5">
                <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Username</label>
                <div class="relative rounded-md shadow-sm">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <span class="material-icons text-gray-400">person_outline</span>
                    </div>
                    <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus
                           placeholder="username" {{-- Sesuai screenshot --}}
                           class="block w-full pl-10 pr-3 py-3 border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm @error('email') border-red-500 ring-red-500 @enderror">
                </div>
            </div>

            {{-- Input Password --}}
            <div class="mb-5">
                <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                <div class="relative rounded-md shadow-sm">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <span class="material-icons text-gray-400">lock_outline</span>
                    </div>
                    <input id="password" type="password" name="password" required
                           placeholder="••••••••"
                           class="block w-full pl-10 pr-3 py-3 border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm @error('password') border-red-500 ring-red-500 @enderror">
                </div>
            </div>

            {{-- Opsi Show Password & Forgot Password --}}
            <div class="flex items-center justify-between mb-8 text-sm">
                <div class="flex items-center">
                    <input id="show_password_checkbox" type="checkbox" onclick="togglePasswordVisibility()" class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                    <label for="show_password_checkbox" class="ml-2 block text-gray-800">Show Password</label>
                </div>
                <a href="#" class="font-medium text-sm text-red-600 hover:text-red-500"> {{-- "I Forgot My Password" berwarna merah --}}
                    I Forgot My Password
                </a>
            </div>

            {{-- Tombol Sign In --}}
            <div class="flex items-center justify-center">
                <button type="submit"
                        class="w-full flex justify-center py-3 px-4 border border-transparent rounded-md shadow-sm text-md font-semibold text-white bg-gray-800 hover:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-700 transition-colors duration-200">
                    Sign In
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    function togglePasswordVisibility() {
        const passwordField = document.getElementById('password');
        const checkbox = document.getElementById('show_password_checkbox');
        if (checkbox.checked) {
            passwordField.type = 'text';
        } else {
            passwordField.type = 'password';
        }
    }
</script>
@endsection