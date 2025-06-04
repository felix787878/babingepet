@extends('layouts.app')

@section('content')
<div class="container mx-auto">
    {{-- Tombol Kembali --}}
    <div class="mb-6">
        <a href="{{ url()->previous() != url()->current() ? url()->previous() : route('ukm-ormawa.show', ['slug' => $item->slug]) }}" class="inline-flex items-center text-blue-600 hover:text-blue-800 transition-colors group text-sm font-medium">
            <span class="material-icons mr-1.5 group-hover:-translate-x-1 transition-transform">arrow_back</span>
            Kembali ke Detail {{ $item->name }}
        </a>
    </div>

    <div class="bg-white p-6 md:p-8 rounded-xl shadow-xl max-w-2xl mx-auto">
        <div class="text-center mb-8">
            @if(isset($item->logo_url) && $item->logo_url)
            <img src="{{ asset('storage/' . $item->logo_url) }}" alt="Logo {{ $item->name }}" class="w-28 h-28 object-contain mx-auto mb-4 rounded-lg shadow-md border border-gray-200">
            @else
            <span class="material-icons text-7xl text-gray-300 mx-auto mb-4">groups</span>
            @endif
            <h1 class="text-2xl md:text-3xl font-bold text-gray-800">Formulir Pendaftaran</h1>
            <p class="text-xl text-red-600 font-semibold mt-1">{{ $item->name }}</p>
            <p class="text-sm text-gray-500 mt-2">Lengkapi data di bawah ini dengan cermat untuk bergabung.</p>
            @if($item->registration_deadline)
                <p class="text-sm text-red-500 mt-1 font-medium">Batas akhir pendaftaran: {{ $item->registration_deadline->translatedFormat('d F Y') }}</p>
            @endif
        </div>

        @if ($errors->any())
            <div class="mb-6 p-4 rounded-md bg-red-100 border border-red-200 text-red-700 text-sm">
                <p class="font-semibold mb-2 text-red-800">Oops! Ada beberapa hal yang perlu diperbaiki:</p>
                <ul class="list-disc list-inside pl-4">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('ukm-ormawa.apply.submit', ['ukm_ormawa_slug' => $item->slug]) }}" method="POST">
            @csrf
            <div class="space-y-6">
                <div>
                    <label for="reason_to_join" class="block text-sm font-medium text-gray-700 mb-1">1. Alasan Bergabung dengan {{ $item->name }} <span class="text-red-500">*</span></label>
                    <textarea name="reason_to_join" id="reason_to_join" rows="5" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 sm:text-sm py-2.5 px-3 @error('reason_to_join') border-red-500 @enderror" placeholder="Jelaskan motivasi utama Anda, apa yang ingin Anda capai, dan mengapa {{ $item->name }} adalah pilihan yang tepat...">{{ old('reason_to_join') }}</textarea>
                    @error('reason_to_join') <span class="text-xs text-red-600 mt-1 block">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label for="skills_experience" class="block text-sm font-medium text-gray-700 mb-1">2. Pengalaman Organisasi / Keahlian Relevan (Opsional)</label>
                    <textarea name="skills_experience" id="skills_experience" rows="4" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 sm:text-sm py-2.5 px-3 @error('skills_experience') border-red-500 @enderror" placeholder="Sebutkan pengalaman organisasi sebelumnya, keahlian khusus (misal: desain, public speaking, programming), atau kontribusi lain yang bisa Anda berikan...">{{ old('skills_experience') }}</textarea>
                    @error('skills_experience') <span class="text-xs text-red-600 mt-1 block">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label for="phone_contact" class="block text-sm font-medium text-gray-700 mb-1">3. Nomor HP Aktif (WhatsApp) <span class="text-red-500">*</span></label>
                    <div class="relative mt-1 rounded-md shadow-sm">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                          <span class="text-gray-500 sm:text-sm">📞</span>
                        </div>
                        <input type="tel" name="phone_contact" id="phone_contact" value="{{ old('phone_contact', Auth::user()->phone_number ?? '') }}" required class="block w-full rounded-md border-gray-300 pl-10 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 sm:text-sm py-2.5 px-3 @error('phone_contact') border-red-500 @enderror" placeholder="Contoh: 081234567890">
                    </div>
                    @error('phone_contact') <span class="text-xs text-red-600 mt-1 block">{{ $message }}</span> @enderror
                </div>
                
                <div class="mt-6 pt-4 border-t border-gray-100">
                     <div class="flex items-start">
                         <div class="flex items-center h-5">
                             <input id="commitment_checkbox" name="commitment_checkbox" type="checkbox" value="1" {{ old('commitment_checkbox') ? 'checked' : '' }} required class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300 rounded @error('commitment_checkbox') border-red-500 ring-red-500 @enderror">
                         </div>
                         <div class="ml-3 text-sm">
                             <label for="commitment_checkbox" class="font-medium text-gray-700">Pernyataan Komitmen <span class="text-red-500">*</span></label>
                             <p class="text-gray-500 text-xs">Dengan ini saya menyatakan bahwa semua informasi yang saya berikan adalah benar. Saya bersedia mengikuti seluruh rangkaian proses pendaftaran dan berkomitmen untuk aktif berkontribusi jika diterima sebagai anggota {{ $item->name }}.</p>
                         </div>
                     </div>
                     @error('commitment_checkbox') <span class="text-xs text-red-600 mt-1 block">{{ $message }}</span> @enderror
                </div>

            </div>

            <div class="mt-8 pt-6 border-t border-gray-200">
                <button type="submit" class="w-full inline-flex items-center justify-center px-6 py-3.5 border border-transparent text-base font-semibold rounded-lg shadow-sm text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition-colors transform hover:scale-105">
                    <span class="material-icons mr-2">send</span>
                    Kirim Formulir Pendaftaran
                </button>
            </div>
        </form>
    </div>
</div>
@endsection