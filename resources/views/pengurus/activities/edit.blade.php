<x-pengurus-app-layout>
    <x-slot name="header">
        {{ __('Edit Kegiatan: ') . $activity->name }}
    </x-slot>

    <div class="py-8">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6 md:p-8">
                    <div class="flex justify-between items-center mb-6">
                        <div>
                            <h2 class="text-2xl font-bold text-gray-800">Edit Detail Kegiatan</h2>
                            <p class="text-gray-600">Perbarui informasi untuk kegiatan "{{ $activity->name }}".</p>
                        </div>
                        <a href="{{ route('pengurus.activities.index') }}" class="text-sm text-blue-600 hover:text-blue-800 flex items-center">
                            <span class="material-icons text-base mr-1">arrow_back</span>
                            Kembali ke Daftar Kegiatan
                        </a>
                    </div>


                    @if ($errors->any())
                        <div class="mb-4 p-4 bg-red-100 text-red-700 border border-red-300 rounded-md text-sm">
                            <p class="font-semibold">Oops! Ada beberapa kesalahan:</p>
                            <ul class="list-disc list-inside ml-4">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('pengurus.activities.update', $activity->id) }}" enctype="multipart/form-data">
                        @csrf
                        @method('PUT') {{-- Method untuk update --}}
                        <div class="space-y-6">
                            {{-- Nama Kegiatan --}}
                            <div>
                                <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Nama Kegiatan <span class="text-red-500">*</span></label>
                                <input type="text" name="name" id="name" value="{{ old('name', $activity->name) }}" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 sm:text-sm py-2.5 px-3" placeholder="Contoh: Workshop Public Speaking">
                                @error('name') <span class="text-xs text-red-600 mt-1">{{ $message }}</span> @enderror
                            </div>

                            {{-- Deskripsi --}}
                            <div>
                                <label for="description" class="block text-sm font-medium text-gray-700 mb-1">Deskripsi Kegiatan <span class="text-red-500">*</span></label>
                                <textarea name="description" id="description" rows="5" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 sm:text-sm py-2.5 px-3" placeholder="Jelaskan detail kegiatan, tujuan, manfaat, dll.">{{ old('description', $activity->description) }}</textarea>
                                @error('description') <span class="text-xs text-red-600 mt-1">{{ $message }}</span> @enderror
                            </div>

                            {{-- Tanggal Mulai & Selesai --}}
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label for="date_start" class="block text-sm font-medium text-gray-700 mb-1">Tanggal Mulai <span class="text-red-500">*</span></label>
                                    <input type="date" name="date_start" id="date_start" value="{{ old('date_start', \Carbon\Carbon::parse($activity->date_start)->format('Y-m-d')) }}" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 sm:text-sm py-2.5 px-3">
                                    @error('date_start') <span class="text-xs text-red-600 mt-1">{{ $message }}</span> @enderror
                                </div>
                                <div>
                                    <label for="date_end" class="block text-sm font-medium text-gray-700 mb-1">Tanggal Selesai (Opsional)</label>
                                    <input type="date" name="date_end" id="date_end" value="{{ old('date_end', $activity->date_end ? \Carbon\Carbon::parse($activity->date_end)->format('Y-m-d') : '') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 sm:text-sm py-2.5 px-3">
                                    @error('date_end') <span class="text-xs text-red-600 mt-1">{{ $message }}</span> @enderror
                                </div>
                            </div>

                            {{-- Waktu Mulai & Selesai --}}
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label for="time_start" class="block text-sm font-medium text-gray-700 mb-1">Waktu Mulai <span class="text-red-500">*</span></label>
                                    <input type="time" name="time_start" id="time_start" value="{{ old('time_start', $activity->time_start) }}" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 sm:text-sm py-2.5 px-3">
                                    @error('time_start') <span class="text-xs text-red-600 mt-1">{{ $message }}</span> @enderror
                                </div>
                                <div>
                                    <label for="time_end" class="block text-sm font-medium text-gray-700 mb-1">Waktu Selesai <span class="text-red-500">*</span></label>
                                    <input type="time" name="time_end" id="time_end" value="{{ old('time_end', $activity->time_end) }}" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 sm:text-sm py-2.5 px-3">
                                    @error('time_end') <span class="text-xs text-red-600 mt-1">{{ $message }}</span> @enderror
                                </div>
                            </div>

                            {{-- Lokasi --}}
                            <div>
                                <label for="location" class="block text-sm font-medium text-gray-700 mb-1">Lokasi <span class="text-red-500">*</span></label>
                                <input type="text" name="location" id="location" value="{{ old('location', $activity->location) }}" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 sm:text-sm py-2.5 px-3" placeholder="Contoh: Aula Gedung X, Zoom Meeting (Online)">
                                @error('location') <span class="text-xs text-red-600 mt-1">{{ $message }}</span> @enderror
                            </div>

                            {{-- Tipe Kegiatan --}}
                            <div>
                                <label for="type" class="block text-sm font-medium text-gray-700 mb-1">Tipe Kegiatan <span class="text-red-500">*</span></label>
                                <input type="text" name="type" id="type" value="{{ old('type', $activity->type) }}" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 sm:text-sm py-2.5 px-3" placeholder="Contoh: Workshop, Seminar, Lomba, Pertemuan Rutin">
                                @error('type') <span class="text-xs text-red-600 mt-1">{{ $message }}</span> @enderror
                            </div>

                            {{-- Image Banner --}}
                            <div>
                                <label for="image_banner" class="block text-sm font-medium text-gray-700 mb-1">Gambar Banner Kegiatan (Opsional, Max 4MB)</label>
                                <input type="file" name="image_banner" id="image_banner" accept="image/*" onchange="previewActivityImage(event)" class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
                                
                                @if(isset($activity->image_banner_url) && $activity->image_banner_url)
                                    <div class="mt-2 text-sm text-gray-500">
                                        Banner Saat Ini:
                                        <img src="{{ asset('storage/' . $activity->image_banner_url) }}" alt="Banner Kegiatan Saat Ini" class="max-h-40 w-auto rounded mt-1 border border-gray-200 shadow-sm">
                                    </div>
                                @endif

                                <div id="imageActivityPreviewContainer" class="mt-2 {{ old('image_banner') || (isset($activity->image_banner_url) && $activity->image_banner_url) ? '' : 'hidden' }}">
                                    <p class="text-sm text-gray-500">Preview Gambar Baru:</p>
                                    <img id="imageActivityPreview" class="max-h-48 w-auto rounded mt-1 border border-gray-200 shadow-sm" src="#" alt="Preview Banner Kegiatan"/>
                                </div>
                                @error('image_banner') <span class="text-xs text-red-600 mt-1">{{ $message }}</span> @enderror
                            </div>

                            {{-- Status Publikasi --}}
                            <div class="mt-4">
                                <label for="is_published" class="inline-flex items-center">
                                    <input id="is_published" type="checkbox" name="is_published" value="1" {{ old('is_published', $activity->is_published) ? 'checked' : '' }} class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                    <span class="ml-2 text-sm text-gray-700">Publikasikan Kegiatan Ini</span>
                                </label>
                                <p class="text-xs text-gray-500 mt-1">Jika dicentang, kegiatan akan terlihat oleh publik/mahasiswa. Biarkan tidak dicentang untuk menyimpannya sebagai draft.</p>
                            </div>
                        </div>

                        <div class="mt-8 pt-6 border-t border-gray-200 flex items-center justify-end space-x-3">
                            <a href="{{ route('pengurus.activities.index') }}" class="px-4 py-2.5 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                Batal
                            </a>
                            <button type="submit" class="inline-flex items-center justify-center px-6 py-2.5 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-yellow-500 hover:bg-yellow-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-yellow-500">
                                <span class="material-icons text-base mr-1.5">save</span>
                                Update Kegiatan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    @push('scripts')
    <script>
        function previewActivityImage(event) {
            const file = event.target.files[0];
            const preview = document.getElementById('imageActivityPreview');
            const previewContainer = document.getElementById('imageActivityPreviewContainer');

            if (file) {
                preview.src = URL.createObjectURL(file);
                previewContainer.classList.remove('hidden');
            } else {
                // Jika tidak ada file dipilih, mungkin sembunyikan preview atau tampilkan gambar lama jika ada
                // Untuk sekarang, kita sembunyikan jika tidak ada file baru
                 const existingBannerUrl = "{{ isset($activity->image_banner_url) && $activity->image_banner_url ? asset('storage/' . $activity->image_banner_url) : '' }}";
                if (!existingBannerUrl) { // Hanya sembunyikan jika tidak ada gambar lama
                    preview.src = '';
                    previewContainer.classList.add('hidden');
                } else if (preview.src.startsWith('blob:')) { // Jika preview saat ini adalah blob (file baru)
                     preview.src = ''; // Kosongkan src jika file dibatalkan
                     previewContainer.classList.add('hidden'); // Sembunyikan container jika file dibatalkan
                }
            }
        }
         // Untuk memastikan preview container terlihat jika ada old input (misalnya setelah validasi error)
        // Tapi ini tidak bisa menampilkan gambar sebenarnya dari old input karena batasan keamanan browser.
        // Cukup pastikan kontainer terlihat jika ada input file lama.
        document.addEventListener('DOMContentLoaded', function() {
            const inputFile = document.getElementById('image_banner');
            const previewContainer = document.getElementById('imageActivityPreviewContainer');
            if (inputFile.value) { // Jika ada old input (setelah validasi error)
                // Tidak bisa set src ke old value, tapi kita bisa pastikan container terlihat jika ada kesalahan validasi
                // Dan jika user sudah memilih file sebelumnya.
                // Biarkan kosong saja, user harus memilih ulang.
            }
        });
    </script>
    @endpush
</x-pengurus-app-layout>