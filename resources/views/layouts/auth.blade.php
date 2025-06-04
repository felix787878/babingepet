<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Laravel') }} - Login</title> {{-- Atau sesuaikan judulnya --}}
    <link rel="shortcut icon" type="image/png" href="{{ asset('/logo.png') }}" /> {{-- Sesuaikan jika logo berbeda --}}
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    {{-- Vite/Tailwind CSS --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://cdn.tailwindcss.com"></script> {{-- Anda sudah punya ini di layouts/app.blade.php --}}
</head>
<body class="bg-gray-100">
    {{-- Bar Merah di Atas --}}
    <div class="w-full h-3 bg-red-700"></div> {{-- Sesuaikan warna (bg-red-700) dan tinggi (h-3) jika perlu --}}

    <div class="font-sans text-gray-900 antialiased">
        @yield('content')
    </div>
</body>
</html>