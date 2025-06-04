<x-pengurus-app-layout>
    <x-slot name="header">
        {{ __('Daftar Anggota & Pendaftar - ') . $ukmOrmawa->name }}
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

            {{-- Ringkasan Statistik --}}
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <div class="bg-white p-6 rounded-lg shadow-md">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 bg-green-500 rounded-md p-3">
                            <span class="material-icons text-white text-2xl">check_circle</span>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-500">Anggota Diterima</p>
                            <p class="text-2xl font-semibold text-gray-900">{{ $stats['approved'] ?? 0 }}</p>
                        </div>
                    </div>
                </div>
                <div class="bg-white p-6 rounded-lg shadow-md">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 bg-yellow-500 rounded-md p-3">
                            <span class="material-icons text-white text-2xl">hourglass_empty</span>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-500">Pendaftar Pending</p>
                            <p class="text-2xl font-semibold text-gray-900">{{ $stats['pending'] ?? 0 }}</p>
                        </div>
                    </div>
                </div>
                <div class="bg-white p-6 rounded-lg shadow-md">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 bg-red-500 rounded-md p-3">
                            <span class="material-icons text-white text-2xl">cancel</span>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-500">Pendaftar Ditolak</p>
                            <p class="text-2xl font-semibold text-gray-900">{{ $stats['rejected'] ?? 0 }}</p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Filter dan Pencarian --}}
            <div class="mb-6 bg-white p-4 sm:p-6 rounded-lg shadow-md">
                <form method="GET" action="{{ route('pengurus.members.index') }}">
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 items-end">
                        <div>
                            <label for="search" class="block text-sm font-medium text-gray-700">Cari Nama/NIM</label>
                            <input type="text" name="search" id="search" value="{{ request('search') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 py-2 px-3" placeholder="Masukkan nama atau NIM...">
                        </div>
                        <div>
                            <label for="status" class="block text-sm font-medium text-gray-700">Filter Status</label>
                            <select id="status" name="status" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 py-2 px-3">
                                <option value="all" {{ request('status', 'all') == 'all' ? 'selected' : '' }}>Semua Status</option>
                                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Approved</option>
                                <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejected</option>
                            </select>
                        </div>
                        <div class="flex items-center space-x-2">
                            <button type="submit" class="w-full sm:w-auto inline-flex items-center justify-center px-4 py-2.5 bg-blue-600 text-white text-sm font-medium rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors">
                                <span class="material-icons text-sm mr-1.5">filter_list</span>
                                Filter
                            </button>
                             <a href="{{ route('pengurus.members.index') }}" class="w-full sm:w-auto inline-flex items-center justify-center px-4 py-2.5 bg-gray-300 text-gray-700 text-sm font-medium rounded-md hover:bg-gray-400 transition-colors">
                                Reset
                            </a>
                        </div>
                    </div>
                </form>
            </div>

            {{-- Tabel Anggota --}}
            <div class="bg-white overflow-hidden shadow-lg sm:rounded-lg">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-100">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Pendaftar</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">NIM</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal Daftar</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse ($applications as $app)
                                <tr class="hover:bg-gray-50 transition-colors">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium text-gray-900">{{ $app->user->name }}</div>
                                        <div class="text-xs text-gray-500">{{ $app->user->study_program ?? 'N/A' }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $app->user->nim ?? 'N/A' }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $app->created_at->isoFormat('D MMM YYYY, HH:mm') }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-2.5 py-1 inline-flex text-xs leading-5 font-semibold rounded-full 
                                            @if($app->status == 'pending') bg-yellow-100 text-yellow-800 @elseif($app->status == 'approved') bg-green-100 text-green-800 @elseif($app->status == 'rejected') bg-red-100 text-red-800 @else bg-gray-100 text-gray-800 @endif">
                                            {{ ucfirst($app->status) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium space-x-2">
                                        <a href="{{ route('pengurus.members.show', $app->id) }}" class="text-indigo-600 hover:text-indigo-900" title="Lihat Detail Pendaftaran">
                                            <span class="material-icons text-base align-middle">visibility</span>
                                        </a>
                                        @if($app->status == 'pending')
                                            <form action="{{ route('pengurus.members.updateStatus', $app->id) }}" method="POST" class="inline" onsubmit="return confirm('Anda yakin ingin menyetujui pendaftar ini?');">
                                                @csrf
                                                @method('PATCH')
                                                <input type="hidden" name="status" value="approved">
                                                <button type="submit" class="text-green-600 hover:text-green-900" title="Setujui">
                                                    <span class="material-icons text-base align-middle">check_circle_outline</span>
                                                </button>
                                            </form>
                                            <form action="{{ route('pengurus.members.updateStatus', $app->id) }}" method="POST" class="inline" onsubmit="return confirm('Anda yakin ingin menolak pendaftar ini?');">
                                                @csrf
                                                @method('PATCH')
                                                <input type="hidden" name="status" value="rejected">
                                                <button type="submit" class="text-red-600 hover:text-red-900" title="Tolak">
                                                     <span class="material-icons text-base align-middle">highlight_off</span>
                                                </button>
                                            </form>
                                        @elseif($app->status == 'approved' || $app->status == 'rejected')
                                             <form action="{{ route('pengurus.members.updateStatus', $app->id) }}" method="POST" class="inline" onsubmit="return confirm('Kembalikan status pendaftar ini ke Pending?');">
                                                @csrf
                                                @method('PATCH')
                                                <input type="hidden" name="status" value="pending">
                                                <button type="submit" class="text-gray-500 hover:text-gray-700" title="Set ke Pending">
                                                     <span class="material-icons text-base align-middle">settings_backup_restore</span>
                                                </button>
                                            </form>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-10 text-center text-gray-500">
                                        <span class="material-icons text-4xl text-gray-400 mb-2">search_off</span>
                                        <p>Tidak ada pendaftar yang cocok dengan kriteria Anda.</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                @if ($applications->hasPages())
                    <div class="p-6 border-t border-gray-200">
                        {{ $applications->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-pengurus-app-layout>