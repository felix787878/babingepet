<?php

namespace App\Http\Controllers\Pengurus;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Activity; // Pastikan Anda punya model Activity
use App\Models\UkmOrmawa;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage; // Untuk menghapus gambar jika ada
use Illuminate\Support\Str;           // Untuk slug jika diperlukan

class ActivityManagementController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $pengurus = Auth::user();
        $ukmOrmawa = $pengurus->managesUkmOrmawa;

        if (!$ukmOrmawa) {
            return redirect()->route('pengurus.dashboard')->with('error', 'Anda tidak terhubung dengan UKM/Ormawa untuk mengelola kegiatan.');
        }

        $query = Activity::where('ukm_ormawa_id', $ukmOrmawa->id);

        // Filter berdasarkan nama kegiatan
        if ($request->filled('search_activity')) {
            $query->where('name', 'like', '%' . $request->search_activity . '%');
        }

        // Filter berdasarkan status kegiatan (contoh sederhana)
        if ($request->filled('filter_status_kegiatan')) {
            $status = $request->filter_status_kegiatan;
            if ($status === 'upcoming') {
                $query->where('date_start', '>', now())->where('is_published', true);
            } elseif ($status === 'ongoing') {
                $query->where('date_start', '<=', now())->where(fn($q) => $q->whereNull('date_end')->orWhere('date_end', '>=', now()))->where('is_published', true);
            } elseif ($status === 'finished') {
                $query->whereNotNull('date_end')->where('date_end', '<', now())->where('is_published', true);
            } elseif ($status === 'draft') {
                $query->where('is_published', false);
            } elseif ($status === 'published') {
                $query->where('is_published', true);
            }
        }

        $activities = $query->orderBy('date_start', 'desc')->paginate(10); // Paginasi

        return view('pengurus.activities.index', compact('ukmOrmawa', 'activities'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $pengurus = Auth::user();
        $ukmOrmawa = $pengurus->managesUkmOrmawa;

        if (!$ukmOrmawa) {
            return redirect()->route('pengurus.dashboard')->with('error', 'Anda tidak terhubung dengan UKM/Ormawa untuk menambah kegiatan.');
        }
        return view('pengurus.activities.create', compact('ukmOrmawa'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $pengurus = Auth::user();
        $ukmOrmawa = $pengurus->managesUkmOrmawa;

        if (!$ukmOrmawa) {
            return redirect()->route('pengurus.dashboard')->with('error', 'Tidak dapat menyimpan kegiatan.');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'date_start' => 'required|date',
            'date_end' => 'nullable|date|after_or_equal:date_start',
            'time_start' => 'required', // Anda mungkin ingin validasi format waktu juga
            'time_end' => 'required',
            'location' => 'required|string|max:255',
            'type' => 'required|string|max:255',
            'image_banner' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:4096',
            'is_published' => 'sometimes|boolean',
        ]);

        $dataToStore = $validated;
        $dataToStore['ukm_ormawa_id'] = $ukmOrmawa->id;
        $dataToStore['user_id'] = $pengurus->id; // User pengurus yang membuat
        $dataToStore['is_published'] = $request->has('is_published');

        if ($request->hasFile('image_banner')) {
            $dataToStore['image_banner_url'] = $request->file('image_banner')->store('activity_banners', 'public');
        }

        Activity::create($dataToStore);

        return redirect()->route('pengurus.activities.index')->with('success', 'Kegiatan berhasil ditambahkan.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Activity $activity)
    {
        $pengurus = Auth::user();
        $ukmOrmawa = $pengurus->managesUkmOrmawa;

        // Pastikan kegiatan ini milik UKM yang dikelola pengurus
        if (!$ukmOrmawa || $activity->ukm_ormawa_id !== $ukmOrmawa->id) {
            return redirect()->route('pengurus.activities.index')->with('error', 'Anda tidak berhak mengedit kegiatan ini.');
        }

        return view('pengurus.activities.edit', compact('activity', 'ukmOrmawa'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Activity $activity)
    {
        $pengurus = Auth::user();
        $ukmOrmawa = $pengurus->managesUkmOrmawa;

        if (!$ukmOrmawa || $activity->ukm_ormawa_id !== $ukmOrmawa->id) {
            return redirect()->route('pengurus.activities.index')->with('error', 'Anda tidak berhak mengupdate kegiatan ini.');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'date_start' => 'required|date',
            'date_end' => 'nullable|date|after_or_equal:date_start',
            'time_start' => 'required',
            'time_end' => 'required',
            'location' => 'required|string|max:255',
            'type' => 'required|string|max:255',
            'image_banner' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:4096',
            'is_published' => 'sometimes|boolean',
        ]);

        $dataToUpdate = $validated;
        $dataToUpdate['is_published'] = $request->has('is_published');

        if ($request->hasFile('image_banner')) {
            // Hapus gambar lama jika ada
            if ($activity->image_banner_url && Storage::disk('public')->exists($activity->image_banner_url)) {
                Storage::disk('public')->delete($activity->image_banner_url);
            }
            $dataToUpdate['image_banner_url'] = $request->file('image_banner')->store('activity_banners', 'public');
        }

        $activity->update($dataToUpdate);

        return redirect()->route('pengurus.activities.index')->with('success', 'Kegiatan berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Activity $activity)
    {
        $pengurus = Auth::user();
        $ukmOrmawa = $pengurus->managesUkmOrmawa;

        if (!$ukmOrmawa || $activity->ukm_ormawa_id !== $ukmOrmawa->id) {
            return redirect()->route('pengurus.activities.index')->with('error', 'Anda tidak berhak menghapus kegiatan ini.');
        }

        // Hapus gambar terkait jika ada
        if ($activity->image_banner_url && Storage::disk('public')->exists($activity->image_banner_url)) {
            Storage::disk('public')->delete($activity->image_banner_url);
        }

        $activity->delete();

        return redirect()->route('pengurus.activities.index')->with('success', 'Kegiatan berhasil dihapus.');
    }
    
    // Method attendanceReport yang sudah ada
    public function attendanceReport(Request $request)
    {
        $pengurus = Auth::user();
        $ukmOrmawa = $pengurus->managesUkmOrmawa;

        if (!$ukmOrmawa) {
            return redirect()->route('pengurus.dashboard')->with('error', 'Anda tidak terhubung dengan UKM/Ormawa.');
        }
        
        // DATA CONTOH SEMENTARA untuk kegiatan yang bisa dipilih
        // Idealnya ini diambil dari database, kegiatan yang sudah selesai dan milik UKM ini
        // $completedActivities = Activity::where('ukm_ormawa_id', $ukmOrmawa->id)
        //                                ->where('date_end', '<', now()) // Asumsi date_end menandakan selesai
        //                                ->orderBy('date_start', 'desc')
        //                                ->get();

        $completedActivities = collect([
            (object)['id' => 1, 'name' => 'Workshop Fotografi Dasar (Selesai) - ' . $ukmOrmawa->name],
            (object)['id' => 3, 'name' => 'Pelatihan Kepemimpinan (Selesai) - ' . $ukmOrmawa->name],
        ]);


        $selectedActivityId = $request->input('activity_id');
        $reportData = collect(); 
        $reportType = null; 
        $activityName = null;
        
        if ($selectedActivityId) {
            $reportType = 'single_activity';
            $selectedActivity = $completedActivities->firstWhere('id', (int)$selectedActivityId);
            $activityName = $selectedActivity ? $selectedActivity->name : 'Kegiatan Tidak Ditemukan';

            // DATA CONTOH untuk laporan per kegiatan
            // Anda akan mengganti ini dengan query ke tabel ActivityAttendance
            if($selectedActivityId == 1){
                 $items = collect([
                    (object)['user' => (object)['name' => 'Budi Santoso', 'nim' => '102022300010'], 'status' => 'Hadir', 'notes' => '-'],
                    (object)['user' => (object)['name' => 'Citra Lestari', 'nim' => '102022300011'], 'status' => 'Absen', 'notes' => 'Tanpa keterangan'],
                    (object)['user' => (object)['name' => 'Ahmad Yani', 'nim' => '102022300012'], 'status' => 'Izin', 'notes' => 'Sakit'],
                    (object)['user' => (object)['name' => 'Dewi Anggraini', 'nim' => '102022300013'], 'status' => 'Hadir', 'notes' => '-'],
                ]);
            } elseif ($selectedActivityId == 3) {
                 $items = collect([
                    (object)['user' => (object)['name' => 'Eko Prasetyo', 'nim' => '102022300014'], 'status' => 'Hadir', 'notes' => '-'],
                    (object)['user' => (object)['name' => 'Fitri Indah', 'nim' => '102022300015'], 'status' => 'Hadir', 'notes' => '-'],
                ]);
            } else {
                $items = collect();
            }
             $reportData = new \Illuminate\Pagination\LengthAwarePaginator(
                $items->forPage($request->page, 10),
                $items->count(),
                10,
                $request->page,
                ['path' => $request->url(), 'query' => $request->query()]
            );


        } else {
            $reportType = 'overall_summary';
            // DATA CONTOH untuk rekap umum dari SEMUA anggota UKM yang terdaftar (status 'approved')
            $approvedMembers = UkmApplication::where('ukm_ormawa_id', $ukmOrmawa->id)
                                            ->where('status', 'approved')
                                            ->with('user') // Eager load user
                                            ->get();
            $items = collect();
            if($approvedMembers->isNotEmpty()){
                 $items = $approvedMembers->map(function($application) {
                    // Logika dummy untuk jumlah kegiatan diikuti, hadir, absen
                    $kegiatan_diikuti = rand(3, 7);
                    $jumlah_hadir = rand(1, $kegiatan_diikuti);
                    $jumlah_absen = $kegiatan_diikuti - $jumlah_hadir;
                    $persentase_kehadiran = $kegiatan_diikuti > 0 ? round(($jumlah_hadir / $kegiatan_diikuti) * 100) . '%' : '0%';
                    return (object)[
                        'user' => $application->user, // Menggunakan data user asli
                        'kegiatan_diikuti' => $kegiatan_diikuti,
                        'jumlah_hadir' => $jumlah_hadir,
                        'jumlah_absen' => $jumlah_absen,
                        'persentase_kehadiran' => $persentase_kehadiran
                    ];
                });
            }

             $reportData = new \Illuminate\Pagination\LengthAwarePaginator(
                $items->forPage($request->page, 10),
                $items->count(),
                10,
                $request->page,
                ['path' => $request->url(), 'query' => $request->query()]
            );
        }


        return view('pengurus.attandance.index', compact('ukmOrmawa', 'completedActivities', 'reportData', 'selectedActivityId', 'reportType', 'activityName'));
    }
}