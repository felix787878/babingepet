<x-pengurus-app-layout>
    <x-slot name="header">
        {{ __('Kelola Kegiatan - ') . $ukmOrmawa->name }}
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

            <div class="flex flex-col sm:flex-row justify-between items-center mb-6">
                <h2 class="text-2xl font-semibold text-gray-800">Daftar Kegiatan {{ $ukmOrmawa->name }}</h2>
                <a href="{{ route('pengurus.activities.create') }}" class="mt-3 sm:mt-0 inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700 active:bg-green-900 focus:outline-none focus:border-green-900 focus:ring ring-green-300 disabled:opacity-25 transition ease-in-out duration-150">
                    <span class="material-icons text-base mr-1">add_circle_outline</span>
                    Tambah Kegiatan Baru
                </a>
            </div>

            {{-- Filter dan Pencarian --}}
            <div class="mb-6 bg-white p-4 sm:p-6 rounded-lg shadow-md">
                <form method="GET" action="{{-- route('pengurus.activities.index') --}}"> {{-- Sesuaikan jika menggunakan request()--}}
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 items-end">
                        <div>
                            <label for="search_activity" class="block text-sm font-medium text-gray-700">Cari Nama Kegiatan</label>
                            <input type="text" name="search_activity" id="search_activity" value="{{ request('search_activity') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 py-2 px-3" placeholder="Masukkan nama kegiatan...">
                        </div>
                        <div>
                            <label for="filter_status_kegiatan" class="block text-sm font-medium text-gray-700">Filter Status</label>
                            <select id="filter_status_kegiatan" name="filter_status_kegiatan" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 py-2 px-3">
                                <option value="">Semua</option>
                                <option value="upcoming" {{ request('filter_status_kegiatan') == 'upcoming' ? 'selected' : '' }}>Akan Datang</option>
                                <option value="ongoing" {{ request('filter_status_kegiatan') == 'ongoing' ? 'selected' : '' }}>Sedang Berlangsung</option>
                                <option value="finished" {{ request('filter_status_kegiatan') == 'finished' ? 'selected' : '' }}>Selesai</option>
                                <option value="draft" {{ request('filter_status_kegiatan') == 'draft' ? 'selected' : '' }}>Draft</option>
                                <option value="published" {{ request('filter_status_kegiatan') == 'published' ? 'selected' : '' }}>Dipublikasikan</option>
                            </select>
                        </div>
                        <div class="flex items-center space-x-2">
                            <button type="submit" class="w-full sm:w-auto inline-flex items-center justify-center px-4 py-2.5 bg-blue-600 text-white text-sm font-medium rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors">
                                <span class="material-icons text-sm mr-1.5">filter_list</span>
                                Terapkan
                            </button>
                             <a href="{{-- route('pengurus.activities.index') --}}" class="w-full sm:w-auto inline-flex items-center justify-center px-4 py-2.5 bg-gray-300 text-gray-700 text-sm font-medium rounded-md hover:bg-gray-400 transition-colors">
                                Reset
                            </a>
                        </div>
                    </div>
                </form>
            </div>

            {{-- Tabel Kegiatan --}}
            <div class="bg-white overflow-hidden shadow-lg sm:rounded-lg">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-100">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Kegiatan</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Lokasi</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tipe</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse ($activities as $activity)
                                <tr class="hover:bg-gray-50 transition-colors">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium text-gray-900">{{ $activity->name }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ \Carbon\Carbon::parse($activity->date_start)->isoFormat('D MMM YY') }}
                                        @if($activity->date_end && $activity->date_end != $activity->date_start)
                                            - {{ \Carbon\Carbon::parse($activity->date_end)->isoFormat('D MMM YY') }}
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $activity->location }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $activity->type }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-2.5 py-1 inline-flex text-xs leading-5 font-semibold rounded-full 
                                            {{ $activity->is_published ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-700' }}">
                                            {{ $activity->is_published ? 'Dipublikasikan' : 'Draft' }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium space-x-2">
                                        <a href="{{ route('pengurus.activities.edit', $activity->id) }}" class="text-yellow-600 hover:text-yellow-900" title="Edit Kegiatan">
                                            <span class="material-icons text-base align-middle">edit</span>
                                        </a>
                                        <form action="{{ route('pengurus.activities.destroy', $activity->id) }}" method="POST" class="inline" onsubmit="return confirm('Anda yakin ingin menghapus kegiatan ini?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-900" title="Hapus Kegiatan">
                                                 <span class="material-icons text-base align-middle">delete_outline</span>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-10 text-center text-gray-500">
                                        <span class="material-icons text-4xl text-gray-400 mb-2">event_busy</span>
                                        <p>Belum ada kegiatan yang ditambahkan.</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                @if ($activities->hasPages())
                    <div class="p-6 border-t border-gray-200">
                        {{ $activities->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-pengurus-app-layout>