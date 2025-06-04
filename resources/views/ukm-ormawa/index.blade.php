@extends('layouts.app')

@section('content')
    <div class="mb-6">
        <h1 class="text-3xl font-semibold text-gray-800">Daftar UKM & Ormawa</h1>
        <p class="text-gray-600 mt-1">Temukan Unit Kegiatan Mahasiswa dan Organisasi Mahasiswa yang sesuai dengan minat dan bakat Anda di Telkom University.</p>
    </div>

    {{-- Notifikasi Sesi --}}
    @if(session('success'))
        <div class="mb-6 p-4 rounded-md bg-green-100 border border-green-300 text-green-700 text-sm transition-opacity duration-300" id="successMessageUKMIndex">
            {{ session('success') }}
            <button type="button" class="float-right font-semibold text-lg leading-none" onclick="document.getElementById('successMessageUKMIndex').style.display='none'">&times;</button>
        </div>
    @endif
    @if(session('error'))
        <div class="mb-6 p-4 rounded-md bg-red-100 border border-red-300 text-red-700 text-sm transition-opacity duration-300" id="errorMessageUKMIndex">
            {{ session('error') }}
            <button type="button" class="float-right font-semibold text-lg leading-none" onclick="document.getElementById('errorMessageUKMIndex').style.display='none'">&times;</button>
        </div>
    @endif
    @if(session('warning'))
        <div class="mb-6 p-4 rounded-md bg-yellow-100 border border-yellow-300 text-yellow-700 text-sm transition-opacity duration-300" id="warningMessageUKMIndex">
            {{ session('warning') }}
            <button type="button" class="float-right font-semibold text-lg leading-none" onclick="document.getElementById('warningMessageUKMIndex').style.display='none'">&times;</button>
        </div>
    @endif

    {{-- Bagian Filter (Opsional) --}}
    {{-- Anda bisa mengimplementasikan fungsi filter ini dengan JavaScript atau request ke server --}}
    <div class="mb-8 bg-white p-4 sm:p-6 rounded-lg shadow-md">
        <h2 class="text-xl font-semibold text-gray-700 mb-4">Filter Pencarian</h2>
        <form method="GET" action="{{ route('ukm-ormawa.index') }}"> {{-- Arahkan ke route yang sama untuk filter --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 items-end">
                <div>
                    <label for="search_name" class="block text-sm font-medium text-gray-700">Cari Nama</label>
                    <input type="text" name="search_name" id="search_name" value="{{ request('search_name') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 py-2 px-3" placeholder="Nama UKM/Ormawa...">
                </div>
                <div>
                    <label for="filter_type" class="block text-sm font-medium text-gray-700">Tipe</label>
                    <select id="filter_type" name="filter_type" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 py-2 px-3">
                        <option value="">Semua Tipe</option>
                        <option value="UKM" {{ request('filter_type') == 'UKM' ? 'selected' : '' }}>UKM</option>
                        <option value="Ormawa" {{ request('filter_type') == 'Ormawa' ? 'selected' : '' }}>Ormawa</option>
                    </select>
                </div>
                <div>
                    <label for="filter_category" class="block text-sm font-medium text-gray-700">Kategori</label>
                    <select id="filter_category" name="filter_category" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 py-2 px-3">
                        <option value="">Semua Kategori</option>
                        {{-- Idealnya kategori diambil dari database atau config --}}
                        <option value="Olahraga" {{ request('filter_category') == 'Olahraga' ? 'selected' : '' }}>Olahraga</option>
                        <option value="Seni & Budaya" {{ request('filter_category') == 'Seni & Budaya' ? 'selected' : '' }}>Seni & Budaya</option>
                        <option value="Seni & Media" {{ request('filter_category') == 'Seni & Media' ? 'selected' : '' }}>Seni & Media</option>
                        <option value="Akademik & Penalaran" {{ request('filter_category') == 'Akademik & Penalaran' ? 'selected' : '' }}>Akademik & Penalaran</option>
                        <option value="Organisasi Mahasiswa" {{ request('filter_category') == 'Organisasi Mahasiswa' ? 'selected' : '' }}>Organisasi Mahasiswa</option>
                        <option value="Organisasi Eksekutif" {{ request('filter_category') == 'Organisasi Eksekutif' ? 'selected' : '' }}>Organisasi Eksekutif</option>
                        <option value="Himpunan Jurusan" {{ request('filter_category') == 'Himpunan Jurusan' ? 'selected' : '' }}>Himpunan Jurusan</option>
                    </select>
                </div>
                <div class="flex space-x-2">
                    <button type="submit" class="w-full sm:w-auto inline-flex items-center justify-center px-5 py-2.5 bg-blue-600 text-white text-sm font-medium rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors">
                        <span class="material-icons text-sm inline-block align-middle mr-1">filter_list</span>
                        Terapkan
                    </button>
                    <a href="{{ route('ukm-ormawa.index') }}" class="w-full sm:w-auto inline-flex items-center justify-center px-4 py-2.5 bg-gray-300 text-gray-700 text-sm font-medium rounded-md hover:bg-gray-400 transition-colors">
                        Reset
                    </a>
                </div>
           </div>
        </form>
    </div>

    {{-- Daftar Card UKM/Ormawa --}}
    @if(isset($ukmOrmawas) && $ukmOrmawas->count() > 0)
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-3 gap-x-6 gap-y-8">
            @foreach($ukmOrmawas as $item)
                <div class="bg-white rounded-xl shadow-lg overflow-hidden flex flex-col transition-all duration-300 hover:shadow-2xl hover:-translate-y-1">
                    <a href="{{ route('ukm-ormawa.show', ['slug' => $item->slug]) }}" class="block group">
                        <img class="w-full h-48 object-cover group-hover:opacity-90 transition-opacity" src="{{ $item->logo_url ? asset('storage/' . $item->logo_url) : 'https://via.placeholder.com/400x250/E0E0E0/BDBDBD?text=' . urlencode($item->name) }}" alt="Logo {{ $item->name }}">
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
                            @if($item->is_registration_open)
                                <a href="{{ route('ukm-ormawa.apply.form', ['ukm_ormawa_slug' => $item->slug]) }}" class="flex items-center justify-center w-full px-4 py-2.5 text-sm font-medium text-white bg-red-600 rounded-md hover:bg-red-700 transition-colors">
                                     <span class="material-icons text-sm mr-1.5">person_add</span>
                                    Daftar Sekarang
                                    @if($item->registration_deadline)
                                    <span class="text-xs ml-1.5 opacity-80">(s/d {{ $item->registration_deadline->format('d M Y') }})</span>
                                    @endif
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
        @if ($ukmOrmawas instanceof \Illuminate\Pagination\LengthAwarePaginator && $ukmOrmawas->hasPages())
            <div class="mt-8">
                {{ $ukmOrmawas->links() }}
            </div>
        @endif
    @else
        <div class="text-center py-12 bg-white rounded-lg shadow-md">
            <span class="material-icons text-6xl text-gray-400 mb-3">search_off</span>
            <p class="text-xl text-gray-500 mb-2">Oops! Tidak ada UKM atau Ormawa yang ditemukan.</p>
            <p class="text-gray-400">Silakan coba filter lain atau kembali lagi nanti.</p>
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
        fadeOutAndHide('successMessageUKMIndex');
        fadeOutAndHide('errorMessageUKMIndex');
        fadeOutAndHide('warningMessageUKMIndex');
    });
</script>
@endpush