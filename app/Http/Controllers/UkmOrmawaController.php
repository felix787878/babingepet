<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\UkmOrmawa; // Import model
// Jika menggunakan paginator manual
// use Illuminate\Pagination\Paginator;
// use Illuminate\Pagination\LengthAwarePaginator;


class UkmOrmawaController extends Controller
{
    public function index()
    {
        // Ambil semua UKM/Ormawa dari database
        // Anda bisa menambahkan filter atau pagination di sini
        $ukmOrmawas = UkmOrmawa::orderBy('name')->get(); 
        // Jika ingin pagination: $ukmOrmawas = UkmOrmawa::orderBy('name')->paginate(9);

        return view('ukm-ormawa.index', compact('ukmOrmawas'));
    }

    public function show($slug)
    {
        $item = UkmOrmawa::where('slug', $slug)->firstOrFail();

        // Tambahkan baris ini untuk debugging
        // Ini akan menampilkan isi variabel dan menghentikan eksekusi
        // dd($item->misi, gettype($item->misi));

        return view('ukm-ormawa.show', compact('item'));
    }
}