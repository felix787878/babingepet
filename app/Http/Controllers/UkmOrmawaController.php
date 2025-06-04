<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\UkmOrmawa; // Import model
use Illuminate\Support\Str;

class UkmOrmawaController extends Controller
{
    public function index(Request $request)
    {
        $query = UkmOrmawa::query();

        // Filter berdasarkan nama
        if ($request->filled('search_name')) {
            $query->where('name', 'like', '%' . $request->search_name . '%');
        }

        // Filter berdasarkan tipe
        if ($request->filled('filter_type')) {
            $query->where('type', $request->filter_type);
        }

        // Filter berdasarkan kategori
        if ($request->filled('filter_category')) {
            $query->where('category', $request->filter_category);
        }
        
        // Ambil UKM/Ormawa dari database dengan paginasi
        $ukmOrmawas = $query->orderBy('name')->paginate(9); // Misal 9 item per halaman

        return view('ukm-ormawa.index', compact('ukmOrmawas'));
    }

    public function show($slug)
    {
        // Cari berdasarkan slug, atau tampilkan 404 jika tidak ditemukan
        $item = UkmOrmawa::where('slug', $slug)->firstOrFail();

        // Anda bisa memuat relasi lain jika perlu, contoh:
        // $item->load('activities'); // jika ada relasi 'activities' di model UkmOrmawa

        return view('ukm-ormawa.show', compact('item'));
    }
}