<x-pengurus-app-layout>
    <x-slot name="header">
        {{ __('Laporan Kehadiran Anggota - ') . $ukmOrmawa->name }}
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            {{-- Notifikasi --}}
            @if(session('success'))
                <div class="mb-6 p-4 text-sm text-green-700 bg-green-100 rounded-lg shadow" role="alert">
                    {{ session('success') }}
                </div>
            @endif
            @if(session('error'))
                <div class="mb-6 p-4 text-sm text-red-700 bg-red-100 rounded-lg shadow" role="alert">
                    {{ session('error') }}
                </div>
            @endif

            <div class="bg-white p-6 rounded-xl shadow-lg mb-8">
                <h2 class="text-xl font-semibold text-gray-800 mb-4">Filter Laporan Kehadiran</h2>
                <form method="GET" action="{{ route('pengurus.attendance.reports') }}">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 items-end">
                        <div>
                            <label for="activity_id" class="block text-sm font-medium text-gray-700">Pilih Kegiatan</label>
                            <select name="activity_id" id="activity_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 py-2.5 px-3">
                                <option value="">-- Rekapitulasi Semua Kegiatan --</option>
                                @foreach ($completedActivities as $kegiatan)
                                    <option value="{{ $kegiatan->id }}" {{ $selectedActivityId == $kegiatan->id ? 'selected' : '' }}>
                                        {{ $kegiatan->name }}
                                    </option>
                                @endforeach
                            </select>
                            <p class="text-xs text-gray-500 mt-1">Pilih kegiatan spesifik untuk melihat detail kehadiran, atau kosongkan untuk rekap umum.</p>
                        </div>
                        {{-- Tambahkan filter rentang tanggal jika diperlukan --}}
                        {{-- <div>
                            <label for="date_range" class="block text-sm font-medium text-gray-700">Rentang Tanggal (Opsional)</label>
                            <input type="text" name="date_range" id="date_range" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 py-2 px-3" placeholder="Pilih rentang tanggal...">
                        </div> --}}
                         <div class="flex items-center space-x-3">
                            <button type="submit" class="w-full sm:w-auto inline-flex items-center justify-center px-6 py-2.5 bg-blue-600 text-white text-sm font-medium rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors">
                                <span class="material-icons text-sm mr-1.5">bar_chart</span>
                                Tampilkan Laporan
                            </button>
                            <a href="{{ route('pengurus.attendance.reports') }}" class="w-full sm:w-auto inline-flex items-center justify-center px-4 py-2.5 bg-gray-300 text-gray-700 text-sm font-medium rounded-md hover:bg-gray-400 transition-colors">
                                Reset Filter
                            </a>
                        </div>
                    </div>
                </form>
            </div>

            @if($reportData->isNotEmpty())
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                    <div class="p-6 border-b border-gray-200">
                        @if($reportType === 'single_activity')
                            <h3 class="text-lg font-semibold text-gray-700">Detail Kehadiran: {{ $activityName }}</h3>
                        @elseif($reportType === 'overall_summary')
                            <h3 class="text-lg font-semibold text-gray-700">Rekapitulasi Kehadiran Anggota (Semua Kegiatan)</h3>
                        @else
                             <h3 class="text-lg font-semibold text-gray-700">Laporan Kehadiran</h3>
                        @endif
                    </div>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-100">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Anggota</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">NIM</th>
                                    @if($reportType === 'single_activity')
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status Kehadiran</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Catatan</th>
                                    @elseif($reportType === 'overall_summary')
                                        <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Keg. Diikuti</th>
                                        <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Hadir</th>
                                        <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Absen/Izin</th>
                                        <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">% Kehadiran</th>
                                    @endif
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach ($reportData as $data)
                                    <tr class="hover:bg-gray-50 transition-colors">
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $data->user->name }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $data->user->nim ?? 'N/A' }}</td>
                                        @if($reportType === 'single_activity')
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <span class="px-2.5 py-1 inline-flex text-xs leading-5 font-semibold rounded-full 
                                                    @if($data->status == 'Hadir') bg-green-100 text-green-800
                                                    @elseif($data->status == 'Absen') bg-red-100 text-red-800
                                                    @elseif($data->status == 'Izin') bg-yellow-100 text-yellow-800
                                                    @else bg-gray-100 text-gray-800 @endif">
                                                    {{ $data->status }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $data->notes ?? '-' }}</td>
                                        @elseif($reportType === 'overall_summary')
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">{{ $data->kegiatan_diikuti }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-green-600 text-center">{{ $data->jumlah_hadir }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-red-600 text-center">{{ $data->jumlah_absen }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-blue-600 font-semibold text-center">{{ $data->persentase_kehadiran }}</td>
                                        @endif
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @if ($reportData->hasPages())
                        <div class="p-6 border-t border-gray-200">
                            {{ $reportData->appends(request()->query())->links() }} {{-- Penting untuk menjaga filter saat paginasi --}}
                        </div>
                    @endif
                </div>
            @else
                @if($selectedActivityId || request()->filled('date_range')) {{-- Hanya tampilkan jika ada filter aktif tapi tidak ada hasil --}}
                <div class="bg-white text-center py-12 rounded-lg shadow-md">
                    <span class="material-icons text-5xl text-gray-400 mb-3">search_off</span>
                    <p class="text-xl text-gray-600">Tidak ada data kehadiran ditemukan untuk filter yang dipilih.</p>
                </div>
                @else {{-- Tampil jika belum ada filter sama sekali dan belum ada data --}}
                <div class="bg-white text-center py-12 rounded-lg shadow-md">
                    <span class="material-icons text-5xl text-gray-400 mb-3">info_outline</span>
                    <p class="text-xl text-gray-600">Silakan pilih kegiatan atau filter lain untuk menampilkan laporan.</p>
                    <p class="text-sm text-gray-500 mt-1">Data kehadiran akan muncul di sini setelah Anda memilih filter.</p>
                </div>
                @endif
            @endif
        </div>
    </div>
</x-pengurus-app-layout>