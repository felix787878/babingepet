<?php

namespace App\Http\Controllers\Pengurus;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\UkmApplication;
use App\Models\Article;
use App\Models\User;
use Carbon\Carbon; // Import Carbon

class PengurusDashboardController extends Controller
{
    public function index()
    {
        $pengurus = Auth::user();
        $ukmOrmawa = $pengurus->managesUkmOrmawa;

        if (!$ukmOrmawa) {
            return redirect()->route('home')->with('error', 'Anda tidak terhubung dengan UKM/Ormawa manapun untuk mengakses dashboard pengurus.');
        }

        // Statistik Dashboard
        // Jumlah Anggota (yang pendaftarannya approved untuk UKM ini)
        $memberCount = UkmApplication::where('ukm_ormawa_id', $ukmOrmawa->id)
                                    ->where('status', 'approved')
                                    ->count();
        
        // Pendaftar Baru (yang statusnya pending untuk UKM ini)
        $newApplicationsCount = UkmApplication::where('ukm_ormawa_id', $ukmOrmawa->id)
                                            ->where('status', 'pending')
                                            ->count();
        
        // Contoh: Artikel yang dibuat oleh pengurus ini (jika pengurus yang sama membuat artikel)
        // Atau, jika artikel terkait dengan UKM/Ormawa (Anda perlu menambahkan kolom ukm_ormawa_id ke tabel articles)
        $publishedArticlesCount = Article::where('user_id', $pengurus->id)->count(); // Contoh: artikel yang dibuat oleh pengurus
        // Jika ada kolom ukm_ormawa_id di tabel articles:
        // $publishedArticlesCount = Article::where('ukm_ormawa_id', $ukmOrmawa->id)->count();


        // Data untuk Chart Pendaftaran Anggota per Bulan
        $months = [];
        $data = [];
        for ($i = 5; $i >= 0; $i--) { // Ambil data 6 bulan terakhir
            $date = Carbon::now()->subMonths($i);
            $months[] = $date->locale('id')->isoFormat('MMM'); // Format bulan (contoh: Jan, Feb)
            $count = UkmApplication::where('ukm_ormawa_id', $ukmOrmawa->id)
                                   ->whereMonth('created_at', $date->month)
                                   ->whereYear('created_at', $date->year)
                                   ->count();
            $data[] = $count;
        }

        $chartData = [
            'labels' => $months,
            'data' => $data
        ];
        
        // Pendaftar Terkini (recent applications)
        $recentApplications = UkmApplication::with('user') // Eager load user pendaftar
                                          ->where('ukm_ormawa_id', $ukmOrmawa->id)
                                          ->orderBy('created_at', 'desc')
                                          ->take(5) // Ambil 5 terbaru
                                          ->get();

        return view('pengurus.dashboard', compact(
            'ukmOrmawa',
            'memberCount',
            'newApplicationsCount',
            'publishedArticlesCount',
            'chartData',
            'recentApplications'
        ));
    }
}