<?php

namespace App\Http\Controllers\Pengurus;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\UkmOrmawa; // Asumsi ada model Kegiatan
use App\Models\Activity; // Anda perlu membuat model ini
use Illuminate\Pagination\LengthAwarePaginator; // <-- TAMBAHKAN INI
use Illuminate\Support\Collection; // <-- TAMBAHKAN INI (jika belum ada)

class ActivityManagementController extends Controller
{
    // Menampilkan daftar kegiatan
    public function index(Request $request)
{
    $pengurus = Auth::user();
    $ukmOrmawa = $pengurus->managesUkmOrmawa;

    if (!$ukmOrmawa) {
        return redirect()->route('pengurus.dashboard')->with('error', 'Anda tidak terhubung dengan UKM/Ormawa.');
    }

    // DATA CONTOH SEMENTARA DENGAN PAGINASI MANUAL
    $allSampleActivities = new Collection([
        (object)['id' => 1, 'name' => 'Workshop Fotografi Dasar', 'date_start' => now()->addDays(7), 'date_end' => now()->addDays(7), 'time_start' => '10:00', 'time_end' => '15:00', 'location' => 'Gedung A', 'type' => 'Workshop', 'is_published' => true],
        (object)['id' => 2, 'name' => 'Lomba Desain Poster Nasional', 'date_start' => now()->addDays(14), 'date_end' => now()->addDays(20), 'time_start' => '08:00', 'time_end' => '17:00', 'location' => 'Online', 'type' => 'Lomba', 'is_published' => true],
        (object)['id' => 3, 'name' => 'Pelatihan Kepemimpinan', 'date_start' => now()->subDays(5), 'date_end' => now()->subDays(4), 'time_start' => '09:00', 'time_end' => '16:00', 'location' => 'Aula', 'type' => 'Pelatihan', 'is_published' => false],
        // Tambahkan lebih banyak data contoh jika perlu untuk menguji paginasi
        (object)['id' => 4, 'name' => 'Seminar Karir', 'date_start' => now()->addDays(10), 'date_end' => now()->addDays(10), 'time_start' => '13:00', 'time_end' => '16:00', 'location' => 'Auditorium', 'type' => 'Seminar', 'is_published' => true],
        (object)['id' => 5, 'name' => 'Gathering Anggota', 'date_start' => now()->addDays(5), 'date_end' => now()->addDays(5), 'time_start' => '18:00', 'time_end' => '21:00', 'location' => 'Taman Kampus', 'type' => 'Gathering', 'is_published' => true],
    ]);

    $perPage = 5; // Jumlah item per halaman
    $currentPage = $request->input('page', 1); // Ambil halaman saat ini dari request, default 1
    $currentPageItems = $allSampleActivities->slice(($currentPage - 1) * $perPage, $perPage)->all();
    
    $activities = new LengthAwarePaginator(
        $currentPageItems,
        count($allSampleActivities),
        $perPage,
        $currentPage,
        ['path' => $request->url(), 'query' => $request->query()]
    );

    return view('pengurus.activities.index', compact('ukmOrmawa', 'activities'));
}

    // Menyimpan kegiatan baru
    public function store(Request $request)
    {
        $pengurus = Auth::user();
        $ukmOrmawa = $pengurus->managesUkmOrmawa;
        if (!$ukmOrmawa) {
            return back()->with('error', 'Aksi tidak diizinkan.');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'date_start' => 'required|date',
            'date_end' => 'nullable|date|after_or_equal:date_start',
            'time_start' => 'required', // Anda bisa menggunakan tipe 'time' jika database mendukung
            'time_end' => 'required',
            'location' => 'required|string|max:255',
            'type' => 'required|string|max:100', // Misal: Workshop, Seminar, Lomba
            'image_banner' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:4096',
            'is_published' => 'sometimes|boolean',
            // Tambahkan field lain yang relevan (misal: biaya, link pendaftaran eksternal, dll)
        ]);

        $dataToCreate = $validated;
        $dataToCreate['ukm_ormawa_id'] = $ukmOrmawa->id; // Asosiasikan dengan UKM/Ormawa pengurus
        $dataToCreate['user_id'] = $pengurus->id; // Pengurus yang membuat kegiatan

        if ($request->hasFile('image_banner')) {
            $dataToCreate['image_banner_url'] = $request->file('image_banner')->store('activity_banners', 'public');
        }
        
        $dataToCreate['is_published'] = $request->has('is_published');

        // Activity::create($dataToCreate); // Ganti dengan model Anda

        return redirect()->route('pengurus.activities.index')->with('success', 'Kegiatan berhasil ditambahkan!');
    }

    // Menampilkan form untuk mengedit kegiatan
    public function edit(/*Activity $activity*/) // Ganti dengan model Anda
    {
        // DATA CONTOH SEMENTARA
        $activity = (object)['id' => 1, 'name' => 'Workshop Fotografi Dasar', 'description' => 'Deskripsi workshop...', 'date_start' => now()->addDays(7)->format('Y-m-d'), 'date_end' => now()->addDays(7)->format('Y-m-d'), 'time_start' => '10:00', 'time_end' => '15:00', 'location' => 'Gedung A', 'type' => 'Workshop', 'is_published' => true, 'image_banner_url' => null];

        $pengurus = Auth::user();
        $ukmOrmawa = $pengurus->managesUkmOrmawa;

        // Pastikan kegiatan ini milik UKM/Ormawa yang dikelola pengurus
        // if (!$ukmOrmawa || $activity->ukm_ormawa_id !== $ukmOrmawa->id) {
        //     return redirect()->route('pengurus.activities.index')->with('error', 'Kegiatan tidak ditemukan.');
        // }

        return view('pengurus.activities.edit', compact('ukmOrmawa', 'activity'));
    }

    // Memperbarui kegiatan yang sudah ada
    public function update(Request $request, /*Activity $activity*/) // Ganti dengan model Anda
    {
        // DATA CONTOH SEMENTARA
        $activity = (object)['id' => 1, 'name' => 'Workshop Fotografi Dasar', 'description' => 'Deskripsi workshop...', 'date_start' => now()->addDays(7), 'date_end' => now()->addDays(7), 'time_start' => '10:00', 'time_end' => '15:00', 'location' => 'Gedung A', 'type' => 'Workshop', 'is_published' => true, 'image_banner_url' => null];

        $pengurus = Auth::user();
        $ukmOrmawa = $pengurus->managesUkmOrmawa;
        // if (!$ukmOrmawa || $activity->ukm_ormawa_id !== $ukmOrmawa->id) {
        //     return back()->with('error', 'Aksi tidak diizinkan.');
        // }

        // Validasi mirip dengan store
        // ...
        
        // Logika update
        // ...

        return redirect()->route('pengurus.activities.index')->with('success', 'Kegiatan berhasil diperbarui!');
    }

    // Menghapus kegiatan
    public function destroy(/*Activity $activity*/) // Ganti dengan model Anda
    {
        // DATA CONTOH SEMENTARA
        $activity = (object)['id' => 1, 'name' => 'Workshop Fotografi Dasar'];

        $pengurus = Auth::user();
        $ukmOrmawa = $pengurus->managesUkmOrmawa;
        // if (!$ukmOrmawa || $activity->ukm_ormawa_id !== $ukmOrmawa->id) {
        //     return back()->with('error', 'Aksi tidak diizinkan.');
        // }

        // Hapus gambar terkait jika ada
        // if ($activity->image_banner_url && Storage::disk('public')->exists($activity->image_banner_url)) {
        //     Storage::disk('public')->delete($activity->image_banner_url);
        // }
        // $activity->delete();

        return redirect()->route('pengurus.activities.index')->with('success', 'Kegiatan berhasil dihapus!');
    }
}