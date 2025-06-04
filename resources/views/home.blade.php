@extends('layouts.app')

@section('content')
    {{-- Salam Pengguna --}}
    <div class="mb-8 p-6 bg-gradient-to-r from-red-600 to-red-800 text-white rounded-xl shadow-lg flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold">Halo, {{ Auth::user()->name ?? 'Mahasiswa Teladan' }}!</h1>
            <p class="mt-1 text-red-100">Selamat datang kembali di UKM Connect. Mari produktif hari ini!</p>
        </div>
        <span class="material-icons text-6xl text-white opacity-50 transform -rotate-12 hidden sm:block">dashboard_customize</span>
    </div>

    {{-- Notifikasi Sesi --}}
    @if(session('error') || session('success') || session('warning'))
        @php
            $type = session('error') ? 'error' : (session('success') ? 'success' : 'warning');
            $message = session($type);
            $colors = [
                'error' => 'bg-red-100 border-red-300 text-red-700',
                'success' => 'bg-green-100 border-green-300 text-green-700',
                'warning' => 'bg-yellow-100 border-yellow-300 text-yellow-700',
            ];
            $bgColor = $colors[$type];
            $id = $type . 'MessageDashboard';
        @endphp
        <div id="{{ $id }}" class="{{ $bgColor }} p-4 rounded-lg mb-6 relative text-sm border transition-opacity duration-300">
            <span>{{ $message }}</span>
            <button type="button" class="absolute top-1/2 right-3 transform -translate-y-1/2 font-semibold text-xl" onclick="document.getElementById('{{ $id }}').style.display='none'">&times;</button>
        </div>
    @endif

    {{-- Grid Utama Dashboard --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2 space-y-6">
            <div class="bg-white p-6 rounded-xl shadow-lg hover:shadow-xl transition-shadow duration-300">
                <div class="flex justify-between items-center mb-4">
                    <h2 class="text-xl font-semibold text-gray-800">Pengumuman Terbaru</h2>
                    <a href="#" class="text-sm text-red-600 hover:text-red-800 font-medium flex items-center">
                        Lihat Semua <span class="material-icons text-base ml-1">arrow_forward</span>
                    </a>
                </div>
                <div class="space-y-5">
                    @php
                        $announcements = [
                            ['date' => '28 Mei, 10:30', 'source' => 'DIREKTORAT KEMAHASISWAAN', 'title' => 'Jadwal Ulang Open Recruitment Beberapa UKM', 'link' => '#', 'type_color' => 'bg-red-500'],
                            ['date' => '25 Mei, 14:00', 'source' => 'UKM MUSIK HARMONI', 'title' => 'Audisi Vokalis & Gitaris Baru Angkatan 2025', 'link' => '#', 'type_color' => 'bg-blue-500'],
                            ['date' => '23 Mei, 09:00', 'source' => 'UKM DEBAT SPECTRUM', 'title' => 'Pendaftaran Lomba Debat Nasional Telkom Open 2025', 'link' => '#', 'type_color' => 'bg-yellow-500'],
                        ];
                    @endphp
                    @forelse ($announcements as $announcement)
                        <div class="flex items-start space-x-4 p-3 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors">
                            <div class="flex-shrink-0 w-2 h-12 rounded-full {{ $announcement['type_color'] }} mt-1"></div>
                            <div>
                                <p class="text-xs text-gray-500">{{ $announcement['date'] }} - {{ $announcement['source'] }}</p>
                                <a href="{{ $announcement['link'] }}" class="text-base font-medium text-gray-700 hover:text-indigo-600 block">{{ $announcement['title'] }}</a>
                            </div>
                        </div>
                    @empty
                        <p class="text-gray-500 text-center py-4">Belum ada pengumuman terbaru.</p>
                    @endforelse
                </div>
            </div>

            <div>
                <div class="flex justify-between items-center mb-4">
                    <h2 class="text-xl font-semibold text-gray-800">Pendaftaran Sedang Dibuka</h2>
                     <a href="{{ route('ukm-ormawa.index') }}" class="text-sm text-red-600 hover:text-red-800 font-medium flex items-center">
                        Lihat Semua yang Buka <span class="material-icons text-base ml-1">arrow_forward</span>
                    </a>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    @php
                        // DATA CONTOH - Ambil dari controller nanti, misal 2-4 item yang buka
                        // Pastikan ada 'slug' dan 'is_registration_open'
                        $openRegistrationsDashboard = [
                            ['name' => 'UKM Coding Revolution', 'logo_url' => 'https://via.placeholder.com/400x200/2ECC71/FFFFFF?text=Coding+Rev', 'deadline' => '15 Juli 2025', 'slug' => 'ukm-coding-revolution', 'is_registration_open' => true],
                            ['name' => 'UKM Pecinta Alam "Everest"', 'logo_url' => 'https://via.placeholder.com/400x200/3498DB/FFFFFF?text=Pecinta+Alam', 'deadline' => '20 Juli 2025', 'slug' => 'ukm-pecinta-alam-everest', 'is_registration_open' => true],
                        ];
                    @endphp
                    @forelse ($openRegistrationsDashboard as $item)
                        @if(isset($item['is_registration_open']) && $item['is_registration_open'])
                        <div class="bg-white rounded-xl shadow-lg overflow-hidden transform hover:scale-105 transition-transform duration-300 flex flex-col">
                            <a href="{{ route('ukm-ormawa.show', ['slug' => $item['slug']]) }}" class="block">
                                <img class="w-full h-36 object-cover" src="{{ $item['logo_url'] }}" alt="Logo {{ $item['name'] }}">
                            </a>
                            <div class="p-4 flex flex-col flex-grow">
                                <h3 class="text-md font-semibold text-gray-800 mb-1 hover:text-red-600 transition-colors">
                                    <a href="{{ route('ukm-ormawa.show', ['slug' => $item['slug']]) }}">{{ $item['name'] }}</a>
                                </h3>
                                <p class="text-xs text-red-600 font-medium mb-2">Batas: {{ $item['deadline'] }}</p>
                                <div class="mt-auto">
                                    {{-- TOMBOL DAFTAR DIPERBARUI --}}
                                    <a href="{{ route('ukm-ormawa.apply.form', ['ukm_ormawa_slug' => $item['slug']]) }}" class="block w-full text-center px-3 py-2 text-sm font-medium text-white bg-red-600 rounded-md hover:bg-red-700 transition-colors">
                                        Daftar Sekarang
                                    </a>
                                </div>
                            </div>
                        </div>
                        @endif
                    @empty
                        <div class="md:col-span-2 bg-white p-6 rounded-xl shadow-lg text-center text-gray-500">
                            Saat ini tidak ada pendaftaran yang sedang dibuka.
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        <div class="lg:col-span-1 space-y-6">
            <div class="bg-white p-6 rounded-xl shadow-lg hover:shadow-xl transition-shadow duration-300 h-full flex flex-col">
                <h2 class="text-xl font-semibold text-gray-800 mb-4">UKM/Ormawa Saya</h2>
                @php
                    $joinedUkms = [
                        (object)['name' => 'UKM SENI TARI', 'status' => 'Anggota Aktif', 'type' => 'UKM', 'image_url' => 'https://via.placeholder.com/80x80/FFC0CB/000000?text=ST'],
                    ]; 
                    $joinedOrmawas = [];
                @endphp
                <div class="flex-grow space-y-3 overflow-y-auto pr-1" style="max-height: 300px;">
                    @if (count($joinedUkms) == 0 && count($joinedOrmawas) == 0)
                        <div class="flex flex-col items-center justify-center h-full text-center">
                             <span class="material-icons text-5xl text-gray-300 mb-2">group_add</span>
                            <p class="text-gray-500 text-sm">Kamu belum bergabung dengan UKM atau Ormawa apapun.</p>
                            <a href="{{ route('ukm-ormawa.index') }}" class="text-sm text-red-600 hover:underline mt-2 font-medium">
                                Yuk, cari & daftar sekarang!
                            </a>
                        </div>
                    @else
                        @foreach ($joinedUkms as $ukm)
                            <a href="#" class="flex items-center p-3 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors group">
                                <img src="{{$ukm->image_url}}" alt="{{$ukm->name}}" class="w-10 h-10 rounded-full object-cover mr-3">
                                <div>
                                    <p class="font-semibold text-sm text-blue-700 group-hover:text-blue-800">{{ $ukm->name }}</p>
                                    <p class="text-xs text-gray-500">{{ $ukm->status }}</p>
                                </div>
                            </a>
                        @endforeach
                        @foreach ($joinedOrmawas as $ormawa)
                             <a href="#" class="flex items-center p-3 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors group">
                                <img src="{{$ormawa->image_url}}" alt="{{$ormawa->name}}" class="w-10 h-10 rounded-full object-cover mr-3">
                                <div>
                                    <p class="font-semibold text-sm text-green-700 group-hover:text-green-800">{{ $ormawa->name }}</p>
                                    <p class="text-xs text-gray-500">{{ $ormawa->status }}</p>
                                </div>
                            </a>
                        @endforeach
                    @endif
                </div>
                 @if (count($joinedUkms) > 0 || count($joinedOrmawas) > 0)
                <div class="mt-4 pt-3 border-t border-gray-200">
                     <a href="{{ route('my-activities.index') }}" class="text-sm text-red-600 hover:text-red-800 font-medium flex items-center justify-center">
                        Lihat Semua Kegiatanku <span class="material-icons text-base ml-1">arrow_forward</span>
                    </a>
                </div>
                @endif
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        function fadeOutAndHide(elementId) {
            const element = document.getElementById(elementId);
            if (element) {
                setTimeout(() => {
                    element.style.transition = 'opacity 0.3s ease-out';
                    element.style.opacity = '0';
                    setTimeout(() => element.style.display = 'none', 300);
                }, 7000);
            }
        }
        fadeOutAndHide('successMessageDashboard');
        fadeOutAndHide('errorMessageDashboard');
        fadeOutAndHide('warningMessageDashboard');
    });
</script>
@endpush