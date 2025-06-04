<x-pengurus-app-layout> {{-- Sesuaikan dengan nama layout Anda --}}
    <x-slot name="header">
        {{ __('Dashboard Pengurus') }}
    </x-slot>

    {{-- Notifikasi Sesi (Tambahan) --}}
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
            $id = $type . 'MessageDashboardPengurus';
        @endphp
        <div id="{{ $id }}" class="{{ $bgColor }} p-4 rounded-lg mb-6 relative text-sm border transition-opacity duration-300">
            <span>{{ $message }}</span>
            <button type="button" class="absolute top-1/2 right-3 transform -translate-y-1/2 font-semibold text-xl" onclick="document.getElementById('{{ $id }}').style.display='none'">&times;</button>
        </div>
    @endif

    <div class="space-y-6">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
            <h3 class="text-2xl font-semibold text-gray-800">
                Selamat Datang, Pengurus {{ Auth::user()->managesUkmOrmawa->name ?? 'UKM/Ormawa' }}!
            </h3>
            <p class="text-gray-600">Ini adalah halaman utama untuk mengelola {{ Auth::user()->managesUkmOrmawa->name ?? 'UKM/Ormawa Anda' }}.</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <div class="bg-white overflow-hidden shadow-md sm:rounded-lg p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0 bg-blue-500 rounded-md p-3">
                        <span class="material-icons text-white text-2xl">group</span> {{-- Material Icon --}}
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-500 truncate">Jumlah Anggota</p>
                        <p class="text-2xl font-semibold text-gray-900">{{ $memberCount ?? 0 }}</p>
                    </div>
                </div>
            </div>
            <div class="bg-white overflow-hidden shadow-md sm:rounded-lg p-6">
                <div class="flex items-center">
                     <div class="flex-shrink-0 bg-green-500 rounded-md p-3">
                        <span class="material-icons text-white text-2xl">person_add</span> {{-- Material Icon --}}
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-500 truncate">Pendaftar Baru</p>
                        <p class="text-2xl font-semibold text-gray-900">{{ $newApplicationsCount ?? 0 }}</p>
                    </div>
                </div>
            </div>
             <div class="bg-white overflow-hidden shadow-md sm:rounded-lg p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0 bg-yellow-500 rounded-md p-3">
                        <span class="material-icons text-white text-2xl">article</span> {{-- Material Icon --}}
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-500 truncate">Artikel UKM</p> {{-- Ubah teks --}}
                        <p class="text-2xl font-semibold text-gray-900">{{ $publishedArticlesCount ?? 0 }}</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
            <h4 class="text-lg font-semibold text-gray-700 mb-4">Statistik Pendaftaran Anggota (Per Bulan)</h4>
            <div class="h-64 md:h-96"> {{-- Adjust height as needed --}}
                <canvas id="registrationStatsChart"></canvas>
            </div>
        </div>

        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6">
                <h4 class="text-lg font-semibold text-gray-700 mb-4">Aktivitas Terbaru / Pendaftar</h4>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Pendaftar</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse ($recentApplications ?? [] as $application)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $application->user->name }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $application->created_at->format('d M Y, H:i') }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                        @if($application->status == 'pending') bg-yellow-100 text-yellow-800 @elseif($application->status == 'approved') bg-green-100 text-green-800 @elseif($application->status == 'rejected') bg-red-100 text-red-800 @else bg-gray-100 text-gray-800 @endif">
                                        {{ ucfirst($application->status) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <a href="#" class="text-indigo-600 hover:text-indigo-900">Lihat Detail</a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">Tidak ada aktivitas terbaru.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Contoh data untuk chart - Anda harus menggantinya dengan data dari controller
            const chartData = {
                labels: {!! json_encode($chartData['labels'] ?? ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun']) !!},
                data: {!! json_encode($chartData['data'] ?? [5, 10, 8, 15, 12, 17]) !!}
            };

            if (document.getElementById('registrationStatsChart')) {
                const ctx = document.getElementById('registrationStatsChart').getContext('2d');
                new Chart(ctx, {
                    type: 'line', // bisa 'bar', 'pie', dll.
                    data: {
                        labels: chartData.labels,
                        datasets: [{
                            label: 'Pendaftaran Baru per Bulan',
                            data: chartData.data,
                            borderColor: 'rgb(59, 130, 246)', // Tailwind blue-500
                            backgroundColor: 'rgba(59, 130, 246, 0.1)',
                            tension: 0.1,
                            fill: true,
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        scales: {
                            y: { beginAtZero: true }
                        }
                    }
                });
            }

            // Script untuk notifikasi session agar hilang setelah beberapa detik
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
            fadeOutAndHide('successMessageDashboardPengurus');
            fadeOutAndHide('errorMessageDashboardPengurus');
            fadeOutAndHide('warningMessageDashboardPengurus');
        });
    </script>
    @endpush
</x-pengurus-app-layout>