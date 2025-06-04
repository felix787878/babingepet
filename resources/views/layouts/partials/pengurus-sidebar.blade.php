<aside
    class="fixed inset-y-0 left-0 z-30 w-64 bg-slate-800 text-slate-100 transform transition duration-300 ease-in-out md:translate-x-0 md:static md:inset-0"
    :class="{'translate-x-0 ease-out': sidebarOpen, '-translate-x-full ease-in': !sidebarOpen}"
    @click.away="sidebarOpen = false" {{-- Tutup sidebar saat klik di luar --}}
>
    <div class="flex items-center justify-center mt-8 px-4"> {{-- Tambahkan px-4 --}}
        <div class="flex flex-col items-center">
            @if(Auth::user()->managesUkmOrmawa && Auth::user()->managesUkmOrmawa->logo_url)
                <img src="{{ asset('storage/' . Auth::user()->managesUkmOrmawa->logo_url) }}" alt="{{ Auth::user()->managesUkmOrmawa->name }} Logo" class="w-20 h-20 mx-auto rounded-full mb-2 object-cover border-2 border-slate-500 shadow-md"> {{-- Tambahkan border dan shadow --}}
            @elseif(Auth::user()->managesUkmOrmawa)
                 <div class="w-20 h-20 mx-auto rounded-full bg-slate-600 flex items-center justify-center text-3xl mb-2 text-white font-semibold border-2 border-slate-500 shadow-md">
                    {{ strtoupper(substr(Auth::user()->managesUkmOrmawa->name, 0, 1)) }}
                </div>
            @else
                <span class="material-icons text-6xl text-slate-500 mb-2">groups</span>
            @endif
            <span class="text-white text-lg font-semibold mt-1 px-2 text-center leading-tight">{{ Auth::user()->managesUkmOrmawa->name ?? 'Area Pengurus' }}</span>
            @if(Auth::user()->managesUkmOrmawa)
                <span class="text-slate-300 text-xs mt-1">Pengurus {{ Auth::user()->managesUkmOrmawa->type }}</span>
            @endif
        </div>
    </div>

    <nav class="mt-10 px-2 space-y-1">
        {{-- Dashboard Pengurus --}}
        <a href="{{ route('pengurus.dashboard') }}" class="group flex items-center px-3 py-2.5 text-sm font-medium rounded-md {{ request()->routeIs('pengurus.dashboard') ? 'bg-slate-900 text-white' : 'text-slate-300 hover:bg-slate-700 hover:text-white' }}">
            <span class="material-icons mr-3 text-base">dashboard</span>
            Dashboard
        </a>

        {{-- Kelola Profil UKM/Ormawa Saya --}}
        @if(Auth::user()->managesUkmOrmawa) {{-- Hanya tampil jika pengurus sudah terhubung ke UKM --}}
        <a href="{{ route('pengurus.ukm-ormawa.edit') }}" class="group flex items-center px-3 py-2.5 text-sm font-medium rounded-md {{ request()->routeIs('pengurus.ukm-ormawa.edit') ? 'bg-slate-900 text-white' : 'text-slate-300 hover:bg-slate-700 hover:text-white' }}">
            <span class="material-icons mr-3 text-base">storefront</span>
            Kelola Profil UKM/Ormawa
        </a>
        @endif
        
        {{-- Sesuai Sketsa 5 di Proposal - Tambahkan tautan dummy untuk fitur yang akan datang --}}
        <a href="{{ route('pengurus.members.index') }}" class="group flex items-center px-3 py-2.5 text-sm font-medium rounded-md text-slate-300 hover:bg-slate-700 hover:text-white">
            <span class="material-icons mr-3 text-base">groups</span>
            Daftar Anggota
        </a>
        <a href="{{ route('pengurus.activities.index') }}" class="group flex items-center px-3 py-2.5 text-sm font-medium rounded-md text-slate-300 hover:bg-slate-700 hover:text-white">
            <span class="material-icons mr-3 text-base">event_note</span>
            Daftar Kegiatan
        </a>
        <a href="#" class="group flex items-center px-3 py-2.5 text-sm font-medium rounded-md text-slate-300 hover:bg-slate-700 hover:text-white">
            <span class="material-icons mr-3 text-base">playlist_add_check</span>
            Laporan Kehadiran
        </a>
        {{-- Fitur Tambahan yang Mungkin Berguna untuk Pengurus --}}
        <a href="#" class="group flex items-center px-3 py-2.5 text-sm font-medium rounded-md text-slate-300 hover:bg-slate-700 hover:text-white">
            <span class="material-icons mr-3 text-base">campaign</span>
            Lowongan Pendaftaran
        </a>
        <a href="#" class="group flex items-center px-3 py-2.5 text-sm font-medium rounded-md text-slate-300 hover:bg-slate-700 hover:text-white">
            <span class="material-icons mr-3 text-base">article</span>
            Artikel/Berita UKM
        </a>


        {{-- Pengaturan Akun Pengurus --}}
        <div class="border-t border-slate-700 my-4"></div> {{-- Separator --}}
        <a href="{{ route('settings.index') }}" class="group flex items-center px-3 py-2.5 text-sm font-medium rounded-md {{ request()->routeIs('settings.index') ? 'bg-slate-900 text-white' : 'text-slate-300 hover:bg-slate-700 hover:text-white' }}">
            <span class="material-icons mr-3 text-base">settings</span>
            Pengaturan Akun Saya
        </a>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <a href="{{ route('logout') }}"
               onclick="event.preventDefault(); this.closest('form').submit();"
               class="group flex items-center px-3 py-2.5 text-sm font-medium rounded-md text-slate-300 hover:bg-red-700 hover:text-white">
                <span class="material-icons mr-3 text-base">logout</span>
                Logout
            </a>
        </form>
    </nav>
</aside>