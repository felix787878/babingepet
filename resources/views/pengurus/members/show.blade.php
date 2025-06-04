<x-pengurus-app-layout>
    <x-slot name="header">
        {{ __('Detail Pendaftaran Anggota - ') . $ukmOrmawa->name }}
    </x-slot>

    <div class="py-8">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="mb-6">
                <a href="{{ route('pengurus.members.index') }}" class="inline-flex items-center text-blue-600 hover:text-blue-800 transition-colors group text-sm font-medium">
                    <span class="material-icons mr-1.5 group-hover:-translate-x-1 transition-transform">arrow_back</span>
                    Kembali ke Daftar Anggota
                </a>
            </div>

            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6 md:p-8">
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-6 pb-4 border-b border-gray-200">
                        <div>
                            <h2 class="text-2xl font-bold text-gray-800">{{ $application->user->name }}</h2>
                            <p class="text-sm text-gray-500">NIM: {{ $application->user->nim ?? 'N/A' }}</p>
                            <p class="text-sm text-gray-500">Program Studi: {{ $application->user->study_program ?? 'N/A' }}</p>
                            <p class="text-sm text-gray-500">Email: {{ $application->user->email }}</p>
                            <p class="text-sm text-gray-500">No. Telepon: {{ $application->phone_contact }}</p>
                        </div>
                        <div class="mt-4 sm:mt-0">
                            <span class="px-3 py-1.5 inline-flex text-sm leading-5 font-semibold rounded-full 
                                @if($application->status == 'pending') bg-yellow-100 text-yellow-800 @elseif($application->status == 'approved') bg-green-100 text-green-800 @elseif($application->status == 'rejected') bg-red-100 text-red-800 @else bg-gray-100 text-gray-800 @endif">
                                Status: {{ ucfirst($application->status) }}
                            </span>
                        </div>
                    </div>

                    <div class="space-y-6">
                        <div>
                            <h3 class="text-lg font-semibold text-gray-700 mb-1">Alasan Bergabung:</h3>
                            <p class="text-gray-600 bg-gray-50 p-3 rounded-md whitespace-pre-line">{{ $application->reason_to_join }}</p>
                        </div>

                        @if($application->skills_experience)
                        <div>
                            <h3 class="text-lg font-semibold text-gray-700 mb-1">Pengalaman/Keahlian:</h3>
                            <p class="text-gray-600 bg-gray-50 p-3 rounded-md whitespace-pre-line">{{ $application->skills_experience }}</p>
                        </div>
                        @endif

                        <div>
                            <p class="text-xs text-gray-400">Tanggal Pendaftaran: {{ $application->created_at->isoFormat('dddd, D MMMM YYYY, HH:mm') }}</p>
                            <p class="text-xs text-gray-400">Terakhir Diperbarui: {{ $application->updated_at->isoFormat('dddd, D MMMM YYYY, HH:mm') }}</p>
                        </div>
                    </div>

                    @if($application->status == 'pending')
                    <div class="mt-8 pt-6 border-t border-gray-200 flex flex-col sm:flex-row sm:justify-end space-y-3 sm:space-y-0 sm:space-x-3">
                        <form action="{{ route('pengurus.members.updateStatus', $application->id) }}" method="POST" onsubmit="return confirm('Anda yakin ingin MENOLAK pendaftaran {{ $application->user->name }}?');">
                            @csrf
                            @method('PATCH')
                            <input type="hidden" name="status" value="rejected">
                            <button type="submit" class="w-full sm:w-auto inline-flex items-center justify-center px-5 py-2.5 border border-red-300 text-sm font-medium rounded-md text-red-700 bg-red-50 hover:bg-red-100 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition-colors">
                                <span class="material-icons text-base mr-1.5">cancel</span>
                                Tolak Pendaftaran
                            </button>
                        </form>
                        <form action="{{ route('pengurus.members.updateStatus', $application->id) }}" method="POST" onsubmit="return confirm('Anda yakin ingin MENYETUJUI pendaftaran {{ $application->user->name }}?');">
                            @csrf
                            @method('PATCH')
                            <input type="hidden" name="status" value="approved">
                            <button type="submit" class="w-full sm:w-auto inline-flex items-center justify-center px-5 py-2.5 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-colors">
                                <span class="material-icons text-base mr-1.5">check_circle</span>
                                Setujui Pendaftaran
                            </button>
                        </form>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-pengurus-app-layout>