@extends('layouts.app')

@section('content')
    <div class="mb-6">
        <h1 class="text-3xl font-semibold text-gray-800">Pengaturan Akun</h1>
        <p class="text-gray-600 mt-1">Kelola informasi profil, keamanan, dan preferensi akun Anda.</p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        {{-- Kolom Kiri: Update Profil --}}
        <div class="lg:col-span-2">
            <div class="bg-white p-6 rounded-xl shadow-lg">
                <h2 class="text-xl font-semibold text-gray-700 mb-1">Informasi Profil</h2>
                <p class="text-sm text-gray-500 mb-6">Perbarui data pribadi Anda di sini.</p>

                @if(session('successProfile'))
                    <div class="mb-4 p-3 rounded-md bg-green-100 border border-green-300 text-green-700 text-sm transition-opacity duration-300" id="successProfileAlert">
                        {{ session('successProfile') }}
                        <button type="button" class="float-right font-semibold" onclick="document.getElementById('successProfileAlert').style.display='none'">&times;</button>
                    </div>
                @endif

                <form action="{{ route('settings.profile.update') }}" method="POST">
                    @csrf
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-4">
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Username</label>
                            <input type="text" name="name" id="name" value="{{ old('name', $user->name) }}" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm py-2.5 px-3 @error('name', 'updateProfile') border-red-500 @enderror">
                            @error('name', 'updateProfile') <span class="text-xs text-red-600">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                            <input type="email" name="email" id="email" value="{{ old('email', $user->email) }}" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm py-2.5 px-3 @error('email', 'updateProfile') border-red-500 @enderror">
                             @error('email', 'updateProfile') <span class="text-xs text-red-600">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label for="nim" class="block text-sm font-medium text-gray-700 mb-1">NIM</label>
                            <input type="text" name="nim" id="nim" value="{{ old('nim', $user->nim ?? '') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm py-2.5 px-3 @error('nim', 'updateProfile') border-red-500 @enderror" placeholder="Masukkan NIM Anda">
                             @error('nim', 'updateProfile') <span class="text-xs text-red-600">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label for="phone_number" class="block text-sm font-medium text-gray-700 mb-1">Nomor Telepon</label>
                            <input type="tel" name="phone_number" id="phone_number" value="{{ old('phone_number', $user->phone_number ?? '') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm py-2.5 px-3 @error('phone_number', 'updateProfile') border-red-500 @enderror" placeholder="08xxxxxxxxxx">
                             @error('phone_number', 'updateProfile') <span class="text-xs text-red-600">{{ $message }}</span> @enderror
                        </div>
                        <div class="md:col-span-2">
                            <label for="bio" class="block text-sm font-medium text-gray-700 mb-1">Bio Singkat</label>
                            <textarea name="bio" id="bio" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm py-2.5 px-3 @error('bio', 'updateProfile') border-red-500 @enderror" placeholder="Ceritakan sedikit tentang diri Anda...">{{ old('bio', $user->bio ?? '') }}</textarea>
                            @error('bio', 'updateProfile') <span class="text-xs text-red-600">{{ $message }}</span> @enderror
                            <p class="mt-1 text-xs text-gray-500">Maksimal 500 karakter.</p>
                        </div>
                    </div>
                    <div class="mt-6 text-right">
                        <button type="submit" class="inline-flex items-center justify-center px-5 py-2.5 border border-transparent text-sm font-medium rounded-lg shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors">
                            <span class="material-icons text-base mr-1.5">save</span>
                            Simpan Perubahan Profil
                        </button>
                    </div>
                </form>
            </div>
        </div>

        {{-- Kolom Kanan: Ubah Password & Hapus Akun --}}
        <div class="lg:col-span-1 space-y-8">
            <div class="bg-white p-6 rounded-xl shadow-lg">
                <h2 class="text-xl font-semibold text-gray-700 mb-1">Ubah Password</h2>
                <p class="text-sm text-gray-500 mb-6">Ganti password Anda secara berkala untuk keamanan.</p>

                @if(session('successPassword'))
                     <div class="mb-4 p-3 rounded-md bg-green-100 border border-green-300 text-green-700 text-sm transition-opacity duration-300" id="successPasswordAlert">
                        {{ session('successPassword') }}
                         <button type="button" class="float-right font-semibold" onclick="document.getElementById('successPasswordAlert').style.display='none'">&times;</button>
                    </div>
                @endif
                
                <form action="{{ route('settings.password.update') }}" method="POST" class="space-y-4">
                    @csrf
                    <div>
                        <label for="current_password" class="block text-sm font-medium text-gray-700 mb-1">Password Saat Ini</label>
                        <input type="password" name="current_password" id="current_password" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm py-2.5 px-3 @error('current_password', 'updatePassword') border-red-500 @enderror">
                        @error('current_password', 'updatePassword') <span class="text-xs text-red-600">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label for="new_password" class="block text-sm font-medium text-gray-700 mb-1">Password Baru</label>
                        <input type="password" name="new_password" id="new_password" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm py-2.5 px-3 @error('new_password', 'updatePassword') border-red-500 @enderror">
                        @error('new_password', 'updatePassword') <span class="text-xs text-red-600">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label for="new_password_confirmation" class="block text-sm font-medium text-gray-700 mb-1">Konfirmasi Password Baru</label>
                        <input type="password" name="new_password_confirmation" id="new_password_confirmation" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm py-2.5 px-3">
                        {{-- Error untuk konfirmasi biasanya ditangani oleh validasi 'confirmed' pada 'new_password' --}}
                    </div>
                    <div class="text-right pt-2">
                        <button type="submit" class="inline-flex items-center justify-center px-5 py-2.5 border border-transparent text-sm font-medium rounded-lg shadow-sm text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-colors">
                             <span class="material-icons text-base mr-1.5">vpn_key</span>
                            Ubah Password
                        </button>
                    </div>
                </form>
            </div>

            <div class="bg-white p-6 rounded-xl shadow-lg border-2 border-red-300">
                <h2 class="text-xl font-semibold text-red-700 mb-1">Hapus Akun</h2>
                <p class="text-sm text-gray-600 mb-4">Tindakan ini tidak dapat diurungkan. Semua data Anda akan dihapus secara permanen dari sistem UKM Connect.</p>
                 @if(session('successDelete'))
                     <div class="mb-4 p-3 rounded-md bg-yellow-100 border border-yellow-400 text-yellow-800 text-sm transition-opacity duration-300" id="successDeleteAlert">
                        {{ session('successDelete') }}
                         <button type="button" class="float-right font-semibold" onclick="document.getElementById('successDeleteAlert').style.display='none'">&times;</button>
                    </div>
                @endif
                @error('password_confirm_delete', 'deleteAccount') 
                    <div class="mb-4 p-3 rounded-md bg-red-100 border border-red-300 text-red-700 text-sm">
                        {{ $message }}
                    </div>
                @enderror
                <form action="{{ route('settings.account.delete') }}" method="POST" onsubmit="return confirm('PERINGATAN! Apakah Anda benar-benar yakin ingin menghapus akun Anda? Tindakan ini bersifat permanen dan tidak dapat diurungkan.');">
                    @csrf
                    @method('DELETE')
                    <div class="mb-4">
                        <label for="password_confirm_delete" class="block text-sm font-medium text-gray-700 mb-1">Konfirmasi Password Anda untuk Menghapus</label>
                        <input type="password" name="password_confirm_delete" id="password_confirm_delete" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500 sm:text-sm py-2.5 px-3" placeholder="Masukkan password Anda saat ini">
                    </div>
                    <button type="submit" class="w-full inline-flex items-center justify-center px-5 py-2.5 border border-transparent text-sm font-medium rounded-lg shadow-sm text-white bg-red-700 hover:bg-red-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-600 transition-colors">
                        <span class="material-icons text-base mr-1.5">delete_forever</span>
                        Ya, Hapus Akun Saya
                    </button>
                </form>
            </div>
        </div>
    </div>
    <script>
        // Script sederhana untuk menghilangkan notifikasi sukses setelah beberapa detik
        setTimeout(function() {
            const successProfileAlert = document.getElementById('successProfileAlert');
            if (successProfileAlert) successProfileAlert.style.opacity = '0';
            const successPasswordAlert = document.getElementById('successPasswordAlert');
            if (successPasswordAlert) successPasswordAlert.style.opacity = '0';
             const successDeleteAlert = document.getElementById('successDeleteAlert');
            if (successDeleteAlert) successDeleteAlert.style.opacity = '0';

            setTimeout(function() {
                 if (successProfileAlert) successProfileAlert.style.display = 'none';
                 if (successPasswordAlert) successPasswordAlert.style.display = 'none';
                 if (successDeleteAlert) successDeleteAlert.style.display = 'none';
            }, 300); // Waktu untuk transisi opacity
        }, 5000); // Notifikasi hilang setelah 5 detik
    </script>
@endsection