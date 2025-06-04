<x-pengurus-app-layout>
    <x-slot name="header">
        {{ __('Tambah Kegiatan Baru untuk ') . $ukmOrmawa->name }}
    </x-slot>

    <div class="py-8">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6 md:p-8">
                    <h2 class="text-2xl font-bold text-gray-800 mb-1">Formulir Kegiatan Baru</h2>
                    <p class="text-gray-600 mb-6">Isi detail kegiatan yang akan diselenggarakan.</p>

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

                    <form method="POST" action="{{ route('pengurus.activities.store') }}" enctype="multipart/form-data">
                        @csrf
                        <div class="space-y-6">
                            {{-- Nama Kegiatan --}}
                            <div>
                                <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Nama Kegiatan <span class="text-red-500">*</span></label>
                                <input type="text" name="name" id="name" value="{{ old('name') }}" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 sm:text-sm py-2.5 px-3" placeholder="Contoh: Workshop Public Speaking">
                                @error('name') <span class="text-xs text-red-600 mt-1">{{ $message }}</span> @enderror
                            </div>

                            {{-- Deskripsi --}}
                            <div>
                                <label for="description" class="block text-sm font-medium text-gray-700 mb-1">Deskripsi Kegiatan <span class="text-red-500">*</span></label>
                                <textarea name="description" id="description" rows="5" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 sm:text-sm py-2.5 px-3" placeholder="Jelaskan detail kegiatan, tujuan, manfaat, dll.">{{ old('description') }}</textarea>
                                @error('description') <span class="text-xs text-red-600 mt-1">{{ $message }}</span> @enderror
                            </div>

                            {{-- Tanggal Mulai & Selesai --}}
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label for="date_start" class="block text-sm font-medium text-gray-700 mb-1">Tanggal Mulai <span class="text-red-500">*</span></label>
                                    <input type="date" name="date_start" id="date_start" value="{{ old('date_start') }}" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 sm:text-sm py-2.5 px-3">
                                    @error('date_start') <span class="text-xs text-red-600 mt-1">{{ $message }}</span> @enderror
                                </div>
                                <div>
                                    <label for="date_end" class="block text-sm font-medium text-gray-700 mb-1">Tanggal Selesai (Opsional)</label>
                                    <input type="date" name="date_end" id="date_end" value="{{ old('date_end') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 sm:text-sm py-2.5 px-3">
                                    @error('date_end') <span class="text-xs text-red-600 mt-1">{{ $message }}</span> @enderror
                                </div>
                            </div>

                            {{-- Waktu Mulai & Selesai --}}
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label for="time_start" class="block text-sm font-medium text-gray-700 mb-1">Waktu Mulai <span class="text-red-500">*</span></label>
                                    <input type="time" name="time_start" id="time_start" value="{{ old('time_start') }}" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 sm:text-sm py-2.5 px-3">
                                    @error('time_start') <span class="text-xs text-red-600 mt-1">{{ $message }}</span> @enderror
                                </div>
                                <div>
                                    <label for="time_end" class="block text-sm font-medium text-gray-700 mb-1">Waktu Selesai <span class="text-red-500">*</span></label>
                                    <input type="time" name="time_end" id="time_end" value="{{ old('time_end') }}" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 sm:text-sm py-2.5 px-3">
                                    @error('time_end') <span class="text-xs text-red-600 mt-1">{{ $message }}</span> @enderror
                                </div>
                            </div>

                            {{-- Lokasi --}}
                            <div>
                                <label for="location" class="block text-sm font-medium text-gray-700 mb-1">Lokasi <span class="text-red-500">*</span></label>
                                <input type="text" name="location" id="location" value="{{ old('location') }}" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 sm:text-sm py-2.5 px-3" placeholder="Contoh: Aula Gedung X, Zoom Meeting (Online)">
                                @error('location') <span class="text-xs text-red-600 mt-1">{{ $message }}</span> @enderror
                            </div>

                            {{-- Tipe Kegiatan --}}
                            <div>
                                <label for="type" class="block text-sm font-medium text-gray-700 mb-1">Tipe Kegiatan <span class="text-red-500">*</span></label>
                                <input type="text" name="type" id="type" value="{{ old('type') }}" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 sm:text-sm py-2.5 px-3" placeholder="Contoh: Workshop, Seminar, Lomba, Pertemuan Rutin">
                                @error('type') <span class="text-xs text-red-600 mt-1">{{ $message }}</span> @enderror
                            </div>

                            {{-- Image Banner --}}
                            <div>
                                <label for="image_banner" class="block text-sm font-medium text-gray-700 mb-1">Gambar Banner Kegiatan (Opsional, Max 4MB)</label>
                                <input type="file" name="image_banner" id="image_banner" accept="image/*" onchange="previewActivityImage(event)" class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
                                <div id="imageActivityPreviewContainer" class="mt-2 hidden">
                                    <p class="text-sm text-gray-500">Preview Gambar:</p>
                                    <img id="imageActivityPreview" class="max-h-48 w-auto rounded mt-1 border border-gray-200 shadow-sm" alt="Preview Banner Kegiatan"/>
                                </div>
                                @error('image_banner') <span class="text-xs text-red-600 mt-1">{{ $message }}</span> @enderror
                            </div>

                            {{-- Status Publikasi --}}
                            <div class="mt-4">
                                <label for="is_published" class="inline-flex items-center">
                                    <input id="is_published" type="checkbox" name="is_published" value="1" {{ old('is_published') ? 'checked' : '' }} class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                    <span class="ml-2 text-sm text-gray-700">Publikasikan Kegiatan Ini</span>
                                </label>
                                <p class="text-xs text-gray-500 mt-1">Jika dicentang, kegiatan akan langsung terlihat oleh publik/mahasiswa. Biarkan tidak dicentang untuk menyimpannya sebagai draft.</p>
                            </div>
                        </div>

                        <div class="mt-8 pt-6 border-t border-gray-200 flex items-center justify-end space-x-3">
                            <a href="{{ route('pengurus.activities.index') }}" class="px-4 py-2.5 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                Batal
                            </a>
                            <button type="submit" class="inline-flex items-center justify-center px-6 py-2.5 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                                <span class="material-icons text-base mr-1.5">save</span>
                                Simpan Kegiatan
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
                preview.src = '';
                previewContainer.classList.add('hidden');
            }
        }
    </script>
    @endpush
</x-pengurus-app-layout>