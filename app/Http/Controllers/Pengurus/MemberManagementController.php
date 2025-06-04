<?php

namespace App\Http\Controllers\Pengurus;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\UkmApplication;
use App\Models\User; // Jika perlu info detail user

class MemberManagementController extends Controller
{
    public function index(Request $request)
    {
        $pengurus = Auth::user();
        $ukmOrmawa = $pengurus->managesUkmOrmawa;

        if (!$ukmOrmawa) {
            return redirect()->route('pengurus.dashboard')->with('error', 'Anda tidak terhubung dengan UKM/Ormawa.');
        }

        $query = UkmApplication::with('user') // Eager load data user
                                ->where('ukm_ormawa_id', $ukmOrmawa->id);

        // Filter berdasarkan status
        if ($request->filled('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        // Pencarian berdasarkan nama atau NIM pendaftar
        if ($request->filled('search')) {
            $searchTerm = $request->search;
            $query->whereHas('user', function ($q) use ($searchTerm) {
                $q->where('name', 'like', "%{$searchTerm}%")
                  ->orWhere('nim', 'like', "%{$searchTerm}%");
            });
        }

        $applications = $query->orderBy('created_at', 'desc')->paginate(15); // Misalnya 15 per halaman

        // Statistik
        $stats = [
            'approved' => UkmApplication::where('ukm_ormawa_id', $ukmOrmawa->id)->where('status', 'approved')->count(),
            'pending' => UkmApplication::where('ukm_ormawa_id', $ukmOrmawa->id)->where('status', 'pending')->count(),
            'rejected' => UkmApplication::where('ukm_ormawa_id', $ukmOrmawa->id)->where('status', 'rejected')->count(),
        ];

        return view('pengurus.members.index', compact('ukmOrmawa', 'applications', 'stats'));
    }

    public function updateStatus(Request $request, UkmApplication $application)
    {
        $pengurus = Auth::user();
        $ukmOrmawa = $pengurus->managesUkmOrmawa;

        // Pastikan aplikasi ini milik UKM/Ormawa yang dikelola pengurus
        if (!$ukmOrmawa || $application->ukm_ormawa_id !== $ukmOrmawa->id) {
            return back()->with('error', 'Aksi tidak diizinkan.');
        }

        $request->validate([
            'status' => 'required|in:approved,rejected,pending',
        ]);

        $application->update(['status' => $request->status]);

        return back()->with('success', 'Status pendaftar berhasil diperbarui.');
    }

    public function showApplication(UkmApplication $application)
    {
        $pengurus = Auth::user();
        $ukmOrmawa = $pengurus->managesUkmOrmawa;

        if (!$ukmOrmawa || $application->ukm_ormawa_id !== $ukmOrmawa->id) {
            return redirect()->route('pengurus.members.index')->with('error', 'Detail pendaftaran tidak ditemukan atau Anda tidak berhak mengaksesnya.');
        }
        
        // Anda bisa membuat view detail di: resources/views/pengurus/members/show.blade.php
        return view('pengurus.members.show', compact('application', 'ukmOrmawa'));
    }
}