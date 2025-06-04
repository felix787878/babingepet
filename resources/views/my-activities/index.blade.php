@extends('layouts.app')

@section('content')
    <div class="mb-6">
        <h1 class="text-3xl font-semibold text-gray-800">Kegiatan Saya</h1>
        <p class="text-gray-600 mt-1">Jelajahi kegiatan yang akan Anda ikuti dan riwayat partisipasi Anda.</p>
    </div>

    {{-- Navigasi Tab --}}
    <div class="mb-6 border-b border-gray-200">
        <nav class="-mb-px flex space-x-4 sm:space-x-6" aria-label="Tabs">
            <button id="tab-upcoming"
                    class="tab-button whitespace-nowrap pb-3 px-1 border-b-2 font-semibold text-sm text-indigo-600 border-indigo-500"
                    aria-current="page" onclick="showTab('upcoming')">
                Akan Datang
            </button>
            <button id="tab-history"
                    class="tab-button whitespace-nowrap pb-3 px-1 border-b-2 font-semibold text-sm text-gray-500 hover:text-gray-700 hover:border-gray-300 border-transparent"
                    onclick="showTab('history')">
                Riwayat Kegiatan
            </button>
        </nav>
    </div>

    {{-- Konten Tab --}}
    <div>
        {{-- Tab Kegiatan Akan Datang --}}
        <div id="content-upcoming" class="tab-content">
            @if(count($upcoming_activities) > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-2 xl:grid-cols-3 gap-6">
                    @foreach($upcoming_activities as $activity)
                        <div class="bg-white rounded-xl shadow-lg overflow-hidden flex flex-col transition-all duration-300 hover:shadow-xl transform hover:-translate-y-1">
                            <a href="#"> {{-- Ganti dengan route detail kegiatan jika ada --}}
                                <img class="w-full h-44 object-cover" src="{{ $activity->image_url ?? 'https://via.placeholder.com/400x200/E0E0E0/BDBDBD?text=Gambar+Kegiatan' }}" alt="Gambar {{ $activity->name }}">
                            </a>
                            <div class="p-5 flex flex-col flex-grow">
                                <div class="mb-2">
                                    <span class="text-xs font-semibold px-2 py-1 rounded-full bg-blue-100 text-blue-800 self-start">{{ $activity->type ?? 'Kegiatan' }}</span>
                                </div>
                                <h3 class="text-lg font-bold text-gray-800 mb-1">
                                     <a href="#" class="hover:text-indigo-600 transition-colors">{{ $activity->name }}</a> {{-- Ganti dengan route detail --}}
                                </h3>
                                <p class="text-sm text-gray-500 mb-1"><span class="font-medium">Oleh:</span> {{ $activity->organizer }}</p>
                                <p class="text-sm text-gray-500 mb-1 flex items-center">
                                    <span class="material-icons text-base mr-1.5 text-gray-400">calendar_today</span>
                                    {{ \Carbon\Carbon::parse($activity->date_start)->locale('id')->translatedFormat('d M Y') }}
                                    @if($activity->date_start != $activity->date_end && !empty($activity->date_end))
                                    - {{ \Carbon\Carbon::parse($activity->date_end)->locale('id')->translatedFormat('d M Y') }}
                                    @endif
                                </p>
                                <p class="text-sm text-gray-500 mb-1 flex items-center">
                                    <span class="material-icons text-base mr-1.5 text-gray-400">schedule</span>
                                    {{ $activity->time_start }} - {{ $activity->time_end }} WIB
                                </p>
                                <p class="text-sm text-gray-500 mb-3 flex items-center">
                                    <span class="material-icons text-base mr-1.5 text-gray-400">location_on</span>
                                    {{ $activity->location }}
                                </p>
                                <div class="mt-auto pt-4 border-t border-gray-200 text-center">
                                    <span class="px-3 py-1.5 text-sm font-semibold rounded-full bg-green-100 text-green-700">{{ $activity->status_keikutsertaan }}</span>
                                    {{-- <a href="#" class="mt-2 inline-block text-indigo-600 hover:underline text-sm">Lihat Detail</a> --}}
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-12 bg-white rounded-lg shadow-md">
                    <span class="material-icons text-6xl text-gray-400 mb-3">event_available</span>
                    <p class="text-xl text-gray-600">Tidak ada kegiatan yang akan datang untuk Anda saat ini.</p>
                    <p class="text-sm text-gray-500 mt-2">Jangan lewatkan kesempatan berikutnya, ayo cari kegiatan menarik lainnya!</p>
                    <a href="{{ route('ukm-ormawa.index') }}" class="mt-6 inline-flex items-center px-6 py-2.5 bg-red-600 text-white text-sm font-medium rounded-md hover:bg-red-700 transition-colors shadow-sm hover:shadow-md">
                        <span class="material-icons mr-2 text-sm">search</span>
                         Cari Kegiatan Baru
                    </a>
                </div>
            @endif
        </div>

        {{-- Tab Riwayat Kegiatan (Tersembunyi secara default) --}}
        <div id="content-history" class="tab-content hidden">
             @if(count($past_activities) > 0)
                <div class="bg-white rounded-lg shadow-md overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Kegiatan</th>
                                <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Penyelenggara</th>
                                <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal</th>
                                <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status Partisipasi</th>
                                {{-- <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th> --}}
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($past_activities as $activity)
                                <tr class="hover:bg-gray-50 transition-colors">
                                    <td class="px-4 py-3 whitespace-nowrap">
                                        <div class="text-sm font-medium text-gray-900">{{ $activity->name }}</div>
                                        <div class="text-xs text-gray-500">{{ $activity->type ?? 'Kegiatan' }}</div>
                                    </td>
                                    <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-500">{{ $activity->organizer }}</td>
                                    <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-500">
                                        {{ \Carbon\Carbon::parse($activity->date_start)->locale('id')->translatedFormat('d M Y') }}
                                        @if($activity->date_start != $activity->date_end && !empty($activity->date_end))
                                            - {{ \Carbon\Carbon::parse($activity->date_end)->locale('id')->translatedFormat('d M Y') }}
                                        @endif
                                    </td>
                                    <td class="px-4 py-3 whitespace-nowrap">
                                        <span class="px-2.5 py-1 inline-flex text-xs leading-5 font-semibold rounded-full 
                                            {{ $activity->status_keikutsertaan === 'Hadir' ? 'bg-green-100 text-green-800' : 
                                            ($activity->status_keikutsertaan === 'Absen' ? 'bg-red-100 text-red-700' : 
                                            ($activity->status_keikutsertaan === 'Partisipan Tim' || Str::contains(strtolower($activity->status_keikutsertaan), 'juara') ? 'bg-yellow-100 text-yellow-800' : 'bg-gray-100 text-gray-800')) }}">
                                            {{ $activity->status_keikutsertaan }}
                                        </span>
                                    </td>
                                    {{-- <td class="px-4 py-3 whitespace-nowrap text-right text-sm font-medium">
                                        <a href="#" class="text-indigo-600 hover:text-indigo-900">Detail</a>
                                    </td> --}}
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="text-center py-12 bg-white rounded-lg shadow-md">
                    <span class="material-icons text-6xl text-gray-400 mb-3">history</span>
                    <p class="text-xl text-gray-600">Belum ada riwayat kegiatan yang tercatat.</p>
                </div>
            @endif
        </div>
    </div>

    <script>
        function showTab(tabName) {
            document.querySelectorAll('.tab-content').forEach(function(content) {
                content.classList.add('hidden');
            });
            document.querySelectorAll('.tab-button').forEach(function(button) {
                button.classList.remove('text-indigo-600', 'border-indigo-500', 'font-semibold');
                button.classList.add('text-gray-500', 'hover:text-gray-700', 'hover:border-gray-300', 'border-transparent', 'font-medium');
                button.removeAttribute('aria-current');
            });

            document.getElementById('content-' + tabName).classList.remove('hidden');
            const activeButton = document.getElementById('tab-' + tabName);
            activeButton.classList.add('text-indigo-600', 'border-indigo-500', 'font-semibold');
            activeButton.classList.remove('text-gray-500', 'hover:text-gray-700', 'hover:border-gray-300', 'border-transparent', 'font-medium');
            activeButton.setAttribute('aria-current', 'page');
        }
        document.addEventListener('DOMContentLoaded', function() {
           showTab('upcoming'); // Tampilkan tab "Akan Datang" secara default
        });
    </script>
@endsection