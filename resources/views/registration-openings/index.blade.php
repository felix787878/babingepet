@extends('layouts.app')

@section('content')
    <div class="mb-6">
        <h1 class="text-3xl font-semibold text-gray-800">Lowongan Pendaftaran UKM & Ormawa</h1>
        <p class="text-gray-600 mt-1">Kesempatan emas untuk bergabung! Temukan UKM atau Ormawa yang sedang membuka pintu bagi anggota baru.</p>
    </div>

    @if(isset($openRegistrations) && count($openRegistrations) > 0)
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-2 xl:grid-cols-3 gap-x-6 gap-y-8">
            @foreach($openRegistrations as $item)
                <div class="bg-white rounded-xl shadow-lg overflow-hidden flex flex-col transform transition-all duration-300 hover:shadow-2xl hover:-translate-y-1">
                    <a href="{{ route('ukm-ormawa.show', ['slug' => $item->slug]) }}" class="block relative group">
                        <img class="w-full h-52 object-cover" src="{{ $item->logo_url ?? 'https://via.placeholder.com/400x250/E0E0E0/BDBDBD?text=Logo+Organisasi' }}" alt="Logo {{ $item->name }}">
                        <div class="absolute inset-0 bg-black bg-opacity-20 group-hover:bg-opacity-10 transition-opacity duration-300"></div>
                        <div class="absolute top-3 right-3 bg-red-600 text-white text-xs font-semibold px-3 py-1 rounded-full shadow-md">
                            OPEN!
                        </div>
                    </a>
                    <div class="p-5 flex flex-col flex-grow">
                        <div class="mb-2 flex justify-between items-center">
                            <span class="text-xs font-semibold px-2.5 py-1 rounded-full
                                {{ $item->type === 'UKM' ? 'bg-blue-100 text-blue-800' : ($item->type === 'Ormawa' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800') }}">
                                {{ $item->type }}
                            </span>
                            @if(isset($item->registration_deadline_obj))
                            <span class="text-xs text-red-700 font-medium flex items-center">
                                <span class="material-icons text-sm inline-block align-middle mr-0.5">event_busy</span>
                                Batas: {{ $item->registration_deadline_obj->locale('id')->translatedFormat('d F Y') }}
                            </span>
                            @endif
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 mb-1 hover:text-red-700 transition-colors">
                            <a href="{{ route('ukm-ormawa.show', ['slug' => $item->slug]) }}">{{ $item->name }}</a>
                        </h3>
                        <p class="text-sm text-gray-500 mb-2"><span class="font-medium">Kategori:</span> {{ $item->category }}</p>
                        <p class="text-sm text-gray-600 flex-grow mb-4 leading-relaxed">{{ Str::limit($item->description_short, 110) }}</p>
                        
                        <div class="mt-auto pt-4 border-t border-gray-200 space-y-3">
                            <a href="{{ route('ukm-ormawa.apply.form', ['ukm_ormawa_slug' => $item->slug]) }}" class="flex items-center justify-center w-full px-4 py-3 text-sm font-semibold text-white bg-red-600 rounded-lg hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition-colors shadow-md hover:shadow-lg">
                                <span class="material-icons text-base mr-2">how_to_reg</span>
                                Daftar Sekarang
                            </a>
                            <a href="{{ route('ukm-ormawa.show', ['slug' => $item->slug]) }}" class="flex items-center justify-center w-full px-4 py-2.5 text-sm font-medium text-indigo-700 bg-indigo-50 rounded-md hover:bg-indigo-100 transition-colors">
                                <span class="material-icons text-sm mr-1.5">info_outline</span>
                                Pelajari Lebih Lanjut
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="text-center py-16 bg-white rounded-lg shadow-md">
            <span class="material-icons text-7xl text-green-500 mb-4">celebration</span>
            <p class="text-2xl font-semibold text-gray-700 mb-2">Semua Pendaftaran Sudah Terisi atau Belum Ada yang Dibuka!</p>
            <p class="text-gray-500 max-w-md mx-auto">Saat ini belum ada lowongan pendaftaran baru. Silakan cek kembali nanti atau lihat daftar lengkap UKM & Ormawa untuk informasi lebih lanjut.</p>
            <a href="{{ route('ukm-ormawa.index') }}" class="mt-8 inline-flex items-center px-6 py-3 bg-blue-600 text-white text-sm font-medium rounded-md hover:bg-blue-700 transition-colors shadow-sm hover:shadow-md">
                <span class="material-icons mr-2 text-base">view_list</span>
                 Lihat Semua UKM/Ormawa
            </a>
        </div>
    @endif

@endsection