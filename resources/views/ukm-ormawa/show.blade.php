@extends('layouts.app')

@section('content')
<div class="container mx-auto">
    @if(isset($item))
        {{-- Tombol Kembali --}}
        <div class="mb-6">
            <a href="{{ url()->previous() != url()->current() ? url()->previous() : route('ukm-ormawa.index') }}" class="inline-flex items-center text-blue-600 hover:text-blue-800 transition-colors group text-sm font-medium">
                <span class="material-icons mr-1.5 group-hover:-translate-x-1 transition-transform">arrow_back</span>
                Kembali
            </a>
        </div>

        <div class="bg-white rounded-xl shadow-xl overflow-hidden">
            {{-- Gambar Banner --}}
            <div class="h-56 sm:h-72 md:h-80 bg-gray-200">
                <img class="w-full h-full object-cover" src="{{ $item->banner_url ? asset('storage/' . $item->banner_url) : ($item->logo_url ? asset('storage/' . $item->logo_url) : 'https://via.placeholder.com/1200x400/E0E0E0/BDBDBD?text=' . urlencode($item->name)) }}" alt="Banner {{ $item->name }}">
            </div>
            
            <div class="p-6 md:p-10">
                {{-- Header Detail: Logo, Nama, Tipe, Kategori --}}
                <div class="flex flex-col sm:flex-row items-start sm:items-center mb-6 md:mb-8">
                    <img src="{{ $item->logo_url ? asset('storage/' . $item->logo_url) : 'https://via.placeholder.com/150x150/E0E0E0/BDBDBD?text=Logo' }}" alt="Logo {{ $item->name }}" class="w-24 h-24 md:w-32 md:h-32 object-contain rounded-lg shadow-md mr-0 mb-4 sm:mr-6 sm:mb-0 border border-gray-200">
                    <div class="flex-1">
                        <h1 class="text-3xl md:text-4xl font-bold text-gray-800">{{ $item->name }}</h1>
                        <div class="mt-2 flex flex-wrap items-center gap-2">
                            <span class="text-sm font-semibold px-3 py-1 rounded-full shadow-sm
                                {{ $item->type === 'UKM' ? 'bg-blue-100 text-blue-800' : 'bg-green-100 text-green-800' }}">
                                {{ $item->type }}
                            </span>
                            <span class="text-sm font-semibold px-3 py-1 rounded-full bg-gray-100 text-gray-700 shadow-sm">
                                Kategori: {{ $item->category }}
                            </span>
                        </div>
                    </div>
                </div>

                {{-- Notifikasi Session --}}
                @if(session('success'))
                    <div class="mb-6 p-4 rounded-md bg-green-100 border border-green-300 text-green-700 text-sm transition-opacity duration-300" id="successMessageDetail">
                        {{ session('success') }}
                        <button type="button" class="float-right font-semibold text-lg leading-none" onclick="document.getElementById('successMessageDetail').style.display='none'">&times;</button>
                    </div>
                @endif
                @if(session('error'))
                    <div class="mb-6 p-4 rounded-md bg-red-100 border border-red-300 text-red-700 text-sm transition-opacity duration-300" id="errorMessageDetail">
                        {{ session('error') }}
                        <button type="button" class="float-right font-semibold text-lg leading-none" onclick="document.getElementById('errorMessageDetail').style.display='none'">&times;</button>
                    </div>
                @endif
                @if(session('warning'))
                    <div class="mb-6 p-4 rounded-md bg-yellow-100 border border-yellow-300 text-yellow-700 text-sm transition-opacity duration-300" id="warningMessageDetail">
                        {{ session('warning') }}
                         <button type="button" class="float-right font-semibold text-lg leading-none" onclick="document.getElementById('warningMessageDetail').style.display='none'">&times;</button>
                    </div>
                @endif

                {{-- Tombol Aksi Utama (Daftar/Status Pendaftaran) --}}
                <div class="mb-8 p-4 bg-gray-50 rounded-lg shadow">
                    @php 
                        $application = null;
                        if(Auth::check()){ // Hanya cek jika user login
                            $application = \App\Models\UkmApplication::where('user_id', Auth::id())
                                                                   ->where('ukm_ormawa_id', $item->id) // Gunakan ukm_ormawa_id
                                                                   ->whereIn('status', ['pending', 'approved'])
                                                                   ->first();
                        }
                    @endphp

                    @if($item->is_registration_open)
                        @if($application)
                             <div class="text-center">
                                <p class="font-semibold text-lg {{ $application->status == 'approved' ? 'text-green-600' : 'text-yellow-600' }}">
                                    Status Pendaftaran Anda: <span class="font-bold">{{ ucfirst($application->status) }}</span>
                                </p>
                                <p class="text-sm text-gray-600 mt-1">Terima kasih telah mendaftar. Pengurus akan segera menghubungi Anda jika ada pembaruan.</p>
                            </div>
                        @else
                             @guest
                                <a href="{{ route('login', ['redirect' => route('ukm-ormawa.apply.form', ['ukm_ormawa_slug' => $item->slug])]) }}" class="w-full block text-center px-8 py-3.5 text-base font-semibold text-white bg-gray-500 rounded-lg hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition-colors shadow-md hover:shadow-lg">
                                    <span class="material-icons mr-2 align-middle">login</span>
                                    Login untuk Mendaftar
                                </a>
                            @else
                                <a href="{{ route('ukm-ormawa.apply.form', ['ukm_ormawa_slug' => $item->slug]) }}" class="w-full block text-center px-8 py-3.5 text-base font-semibold text-white bg-red-600 rounded-lg hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition-colors transform hover:scale-105 shadow-md hover:shadow-lg">
                                    <span class="material-icons mr-2 align-middle">how_to_reg</span>
                                    Daftar ke {{ $item->name }} Sekarang!
                                </a>
                            @endguest
                            @if($item->registration_deadline)
                            <p class="text-center text-sm text-red-500 mt-2 font-medium">Batas Pendaftaran: {{ $item->registration_deadline->translatedFormat('d F Y') }}</p>
                            @endif
                        @endif
                    @else
                        <button disabled class="w-full block text-center px-8 py-3.5 text-base font-medium text-gray-500 bg-gray-200 rounded-lg cursor-not-allowed shadow">
                            <span class="material-icons mr-2 align-middle">lock</span>
                            Pendaftaran Saat Ini Ditutup
                        </button>
                    @endif
                </div>

                {{-- Konten Detail: Deskripsi, Visi Misi, Kegiatan, Galeri (dalam tabs) --}}
                <div>
                    <div class="border-b border-gray-200 mb-6">
                        <nav class="-mb-px flex space-x-6 overflow-x-auto" aria-label="Tabs">
                            <button id="tab-deskripsi" class="tab-detail whitespace-nowrap pb-3 px-1 border-b-2 font-semibold text-sm text-indigo-600 border-indigo-500" onclick="showDetailTab('deskripsi')">Deskripsi</button>
                            <button id="tab-kegiatan" class="tab-detail whitespace-nowrap pb-3 px-1 border-b-2 font-semibold text-sm text-gray-500 hover:text-gray-700 hover:border-gray-300 border-transparent" onclick="showDetailTab('kegiatan')">Kegiatan</button>
                            {{-- <button id="tab-galeri" class="tab-detail whitespace-nowrap pb-3 px-1 border-b-2 font-semibold text-sm text-gray-500 hover:text-gray-700 hover:border-gray-300 border-transparent" onclick="showDetailTab('galeri')">Galeri</button> --}}
                            <button id="tab-kontak" class="tab-detail whitespace-nowrap pb-3 px-1 border-b-2 font-semibold text-sm text-gray-500 hover:text-gray-700 hover:border-gray-300 border-transparent" onclick="showDetailTab('kontak')">Kontak</button>
                        </nav>
                    </div>

                    <div id="content-deskripsi" class="tab-detail-content prose prose-indigo max-w-none text-gray-700 leading-relaxed">
                        <h2 class="text-2xl font-semibold text-gray-800 mt-0 mb-3 not-prose">Tentang {{ $item->name }}</h2>
                        <div class="whitespace-pre-line">{{ $item->description_full ?: ($item->description_short ?: 'Deskripsi lengkap belum tersedia.') }}</div>
                        
                        @if(isset($item->visi) && !empty($item->visi))
                        <h3 class="text-xl font-semibold text-gray-800 mt-6 mb-2 not-prose">Visi</h3>
                        <p>{{ $item->visi }}</p>
                        @endif

                        @if(isset($item->misi) && is_array($item->misi) && count($item->misi) > 0)
                        <h3 class="text-xl font-semibold text-gray-800 mt-4 mb-2 not-prose">Misi</h3>
                        <ul class="list-disc pl-5 space-y-1">
                            @foreach($item->misi as $misi_item)
                                @if(!empty(trim($misi_item)))
                                    <li>{{ $misi_item }}</li>
                                @endif
                            @endforeach
                        </ul>
                        @elseif (!is_array($item->misi) && !empty($item->misi))
                        <h3 class="text-xl font-semibold text-gray-800 mt-4 mb-2 not-prose">Misi</h3>
                         <p>{{ $item->misi }}</p> {{-- Fallback jika misi bukan array tapi string --}}
                        @endif
                    </div>

                    <div id="content-kegiatan" class="tab-detail-content hidden">
                        <h2 class="text-2xl font-semibold text-gray-800 mt-0 mb-4">Kegiatan Utama</h2>
                        {{-- Anda perlu memuat data kegiatan dari relasi atau query terpisah --}}
                        @php $kegiatanUkm = \App\Models\Activity::where('ukm_ormawa_id', $item->id)->where('is_published', true)->orderBy('date_start', 'desc')->take(5)->get(); @endphp
                        @if($kegiatanUkm->isNotEmpty())
                            <div class="space-y-4">
                                @foreach($kegiatanUkm as $activity)
                                <div class="p-4 bg-gray-50 rounded-lg border border-gray-200">
                                    <h4 class="font-semibold text-gray-700">{{ $activity->name }}</h4>
                                    <p class="text-sm text-gray-500">Jenis: {{ $activity->type }} | Jadwal: {{ \Carbon\Carbon::parse($activity->date_start)->translatedFormat('d M Y') }}
                                        @if($activity->date_end && $activity->date_end != $activity->date_start)
                                            - {{ \Carbon\Carbon::parse($activity->date_end)->translatedFormat('d M Y') }}
                                        @endif
                                    </p>
                                    <p class="text-sm text-gray-600 mt-1">{{ Str::limit($activity->description, 150) }}</p>
                                </div>
                                @endforeach
                            </div>
                        @else
                            <p class="text-gray-500">Belum ada daftar kegiatan yang ditampilkan untuk {{ $item->name }}.</p>
                        @endif
                    </div>

                    {{-- <div id="content-galeri" class="tab-detail-content hidden">
                        <h2 class="text-2xl font-semibold text-gray-800 mt-0 mb-4">Galeri Foto</h2>
                        @if(isset($item->gallery_images) && count($item->gallery_images) > 0)
                            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-4">
                                @foreach($item->gallery_images as $image_url)
                                <a href="{{ $image_url }}" data-fancybox="gallery" class="block rounded-lg overflow-hidden shadow-md hover:shadow-xl transition-shadow">
                                    <img src="{{ $image_url }}" alt="Galeri {{ $item->name }}" class="w-full h-32 sm:h-40 object-cover transform group-hover:scale-110 transition-transform duration-300">
                                </a>
                                @endforeach
                            </div>
                        @else
                            <p class="text-gray-500">Galeri foto untuk {{ $item->name }} belum tersedia.</p>
                        @endif
                    </div> --}}

                     <div id="content-kontak" class="tab-detail-content hidden">
                        <h2 class="text-2xl font-semibold text-gray-800 mt-0 mb-4">Hubungi Kami</h2>
                        <div class="space-y-3 text-gray-700">
                            @if(isset($item->contact_email) && !empty($item->contact_email))
                            <p class="flex items-center">
                                <span class="material-icons mr-2 text-gray-500">email</span>
                                Email: <a href="mailto:{{$item->contact_email}}" class="text-indigo-600 hover:underline ml-1">{{$item->contact_email}}</a>
                            </p>
                            @endif
                             @if(isset($item->contact_instagram) && !empty($item->contact_instagram))
                            <p class="flex items-center">
                                <span class="material-icons mr-2 text-gray-500">camera_alt</span> {{-- Ikon Instagram --}}
                                Instagram: <a href="https://instagram.com/{{ str_replace('@','',$item->contact_instagram) }}" target="_blank" class="text-indigo-600 hover:underline ml-1">{{$item->contact_instagram}}</a>
                            </p>
                            @endif
                            @if(empty($item->contact_email) && empty($item->contact_instagram))
                             <p class="text-gray-500">Informasi kontak belum tersedia.</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

    @else
        <div class="text-center py-12 bg-white rounded-xl shadow-xl">
            <span class="material-icons text-6xl text-gray-400 mb-3">error_outline</span>
            <p class="text-xl text-gray-500">Detail untuk UKM atau Ormawa ini tidak dapat ditemukan.</p>
            <a href="{{ route('ukm-ormawa.index') }}" class="mt-4 inline-block px-6 py-2 text-sm font-medium text-white bg-blue-600 rounded-md hover:bg-blue-700">
                Kembali ke Daftar
            </a>
        </div>
    @endif
</div>
@endsection

@push('scripts')
<script>
    function showDetailTab(tabName) {
        document.querySelectorAll('.tab-detail-content').forEach(content => content.classList.add('hidden'));
        document.querySelectorAll('.tab-detail').forEach(button => {
            button.classList.remove('text-indigo-600', 'border-indigo-500');
            button.classList.add('text-gray-500', 'hover:text-gray-700', 'hover:border-gray-300', 'border-transparent');
        });
        document.getElementById('content-' + tabName).classList.remove('hidden');
        const activeButton = document.getElementById('tab-' + tabName);
        activeButton.classList.add('text-indigo-600', 'border-indigo-500');
        activeButton.classList.remove('text-gray-500', 'hover:text-gray-700', 'hover:border-gray-300', 'border-transparent');
    }
    document.addEventListener('DOMContentLoaded', function() {
       showDetailTab('deskripsi'); 

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
        fadeOutAndHide('successMessageDetail');
        fadeOutAndHide('errorMessageDetail');
        fadeOutAndHide('warningMessageDetail');
    });
</script>
@endpush