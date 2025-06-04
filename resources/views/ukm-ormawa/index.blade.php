@extends('layouts.app')

@section('content')
    <div class="mb-6">
        <h1 class="text-3xl font-semibold text-gray-800">Daftar UKM & Ormawa</h1>
        <p class="text-gray-600 mt-1">Temukan Unit Kegiatan Mahasiswa dan Organisasi Mahasiswa yang sesuai dengan minat dan bakat Anda di Telkom University.</p>
    </div>

    {{-- Notifikasi Sesi --}}
    @if(session('success'))
        <div class="mb-6 p-4 rounded-md bg-green-100 border border-green-300 text-green-700 text-sm transition-opacity duration-300" id="successMessage">
            {{ session('success') }}
            <button type="button" class="float-right font-semibold" onclick="document.getElementById('successMessage').style.display='none'">&times;</button>
        </div>
    @endif
    @if(session('error'))
        <div class="mb-6 p-4 rounded-md bg-red-100 border border-red-300 text-red-700 text-sm transition-opacity duration-300" id="errorMessage">
            {{ session('error') }}
            <button type="button" class="float-right font-semibold" onclick="document.getElementById('errorMessage').style.display='none'">&times;</button>
        </div>
    @endif
    @if(session('warning'))
        <div class="mb-6 p-4 rounded-md bg-yellow-100 border border-yellow-300 text-yellow-700 text-sm transition-opacity duration-300" id="warningMessage">
            {{ session('warning') }}
            <button type="button" class="float-right font-semibold" onclick="document.getElementById('warningMessage').style.display='none'">&times;</button>
        </div>
    @endif

    {{-- Bagian Filter (Opsional) --}}
    <div class="mb-8 bg-white p-4 sm:p-6 rounded-lg shadow-md">
        <h2 class="text-xl font-semibold text-gray-700 mb-4">Filter Pencarian</h2>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
            <div>
                <label for="search_name" class="block text-sm font-medium text-gray-700">Cari Nama</label>
                <input type="text" name="search_name" id="search_name" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 py-2 px-3" placeholder="Nama UKM/Ormawa...">
            </div>
            <div>
                <label for="filter_type" class="block text-sm font-medium text-gray-700">Tipe</label>
                <select id="filter_type" name="filter_type" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 py-2 px-3">
                    <option value="">Semua Tipe</option>
                    <option value="UKM">UKM</option>
                    <option value="Ormawa">Ormawa</option>
                </select>
            </div>
            <div>
                <label for="filter_category" class="block text-sm font-medium text-gray-700">Kategori</label>
                <select id="filter_category" name="filter_category" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 py-2 px-3">
                    <option value="">Semua Kategori</option>
                    <option value="Olahraga">Olahraga</option>
                    <option value="Seni & Budaya">Seni & Budaya</option>
                    <option value="Seni & Media">Seni & Media</option>
                    <option value="Akademik & Penalaran">Akademik & Penalaran</option>
                    <option value="Organisasi Mahasiswa">Organisasi Mahasiswa</option>
                    <option value="Himpunan Jurusan">Himpunan Jurusan</option>
                </select>
            </div>
        </div>
        <div class="mt-4 text-right">
            <button type="button" class="px-5 py-2 bg-blue-600 text-white text-sm font-medium rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors">
                <span class="material-icons text-sm inline-block align-middle mr-1">filter_list</span>
                Terapkan Filter
            </button>
       </div>
    </div>

    {{-- Daftar Card UKM/Ormawa --}}
    @if(isset($ukmOrmawas) && count($ukmOrmawas) > 0)
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-3 gap-x-6 gap-y-8">
            @foreach($ukmOrmawas as $item)
                <div class="bg-white rounded-xl shadow-lg overflow-hidden flex flex-col transition-all duration-300 hover:shadow-2xl">
                    <a href="{{ route('ukm-ormawa.show', ['slug' => $item->slug]) }}" class="block">
                        <img class="w-full h-48 object-cover" src="{{ $item->logo_url ?? 'https://via.placeholder.com/400x250/E0E0E0/BDBDBD?text=Logo+Tidak+Tersedia' }}" alt="Logo {{ $item->name }}">
                    </a>
                    <div class="p-5 flex flex-col flex-grow">
                        <div class="mb-2">
                            <span class="text-xs font-semibold px-2.5 py-1 rounded-full
                                {{ $item->type === 'UKM' ? 'bg-blue-100 text-blue-800' : ($item->type === 'Ormawa' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800') }}">
                                {{ $item->type }}
                            </span>
                        </div>
                        <h3 class="text-xl font-bold text-gray-800 mb-1 hover:text-red-600 transition-colors">
                             <a href="{{ route('ukm-ormawa.show', ['slug' => $item->slug]) }}">{{ $item->name }}</a>
                        </h3>
                        <p class="text-sm text-gray-500 mb-2"><span class="font-medium">Kategori:</span> {{ $item->category }}</p>
                        <p class="text-sm text-gray-600 flex-grow mb-4 leading-relaxed">{{ Str::limit($item->description_short, 120) }}</p>
                        
                        <div class="mt-auto pt-4 border-t border-gray-200 space-y-2">
                            <a href="{{ route('ukm-ormawa.show', ['slug' => $item->slug]) }}" class="flex items-center justify-center w-full px-4 py-2.5 text-sm font-medium text-indigo-700 bg-indigo-100 rounded-md hover:bg-indigo-200 transition-colors">
                                <span class="material-icons text-sm mr-1.5">visibility</span>
                                Lihat Detail
                            </a>
                            @if(isset($item->is_registration_open) && $item->is_registration_open)
                                {{-- TOMBOL DAFTAR DIPERBARUI --}}
                                <a href="{{ route('ukm-ormawa.apply.form', ['ukm_ormawa_slug' => $item->slug]) }}" class="flex items-center justify-center w-full px-4 py-2.5 text-sm font-medium text-white bg-red-600 rounded-md hover:bg-red-700 transition-colors">
                                     <span class="material-icons text-sm mr-1.5">person_add</span>
                                    Daftar Sekarang
                                </a>
                            @else
                                <button disabled class="flex items-center justify-center w-full px-4 py-2.5 text-sm font-medium text-gray-500 bg-gray-200 rounded-md cursor-not-allowed">
                                    <span class="material-icons text-sm mr-1.5">lock</span>
                                    Pendaftaran Ditutup
                                </button>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="text-center py-12 bg-white rounded-lg shadow-md">
            <span class="material-icons text-6xl text-gray-400 mb-3">search_off</span>
            <p class="text-xl text-gray-500 mb-2">Oops! UKM atau Ormawa tidak ditemukan.</p>
            <p class="text-gray-400">Silakan coba lagi nanti atau hubungi administrator.</p>
        </div>
    @endif

@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        function fadeOutAndHide(elementId) {
            const element = document.getElementById(elementId);
            if (element) {
                setTimeout(() => {
                    element.style.transition = 'opacity 0.5s ease-out';
                    element.style.opacity = '0';
                    setTimeout(() => element.style.display = 'none', 500);
                }, 7000);
            }
        }
        fadeOutAndHide('successMessage');
        fadeOutAndHide('errorMessage');
        fadeOutAndHide('warningMessage');
    });
</script>
@endpush