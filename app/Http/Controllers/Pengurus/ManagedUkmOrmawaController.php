<?php

namespace App\Http\Controllers\Pengurus;

use App\Http\Controllers\Controller;
use App\Models\UkmOrmawa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ManagedUkmOrmawaController extends Controller
{
    /**
     * Show the form for editing the specified resource.
     */
    public function edit()
{
    $user = Auth::user();
    $ukmOrmawa = $user->managesUkmOrmawa;

    if (!$ukmOrmawa) {
        return redirect()->route('pengurus.dashboard')->with('error', 'Anda tidak terhubung dengan UKM/Ormawa manapun untuk dikelola.');
    }

    // Tambahkan baris ini untuk debugging
    // dd($ukmOrmawa->misi, gettype($ukmOrmawa->misi));

    return view('pengurus.ukm-ormawa.edit', compact('ukmOrmawa'));
}

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        $user = Auth::user();
        $ukmOrmawa = $user->managesUkmOrmawa;

        if (!$ukmOrmawa) {
            return redirect()->route('pengurus.dashboard')->with('error', 'Tidak ada UKM/Ormawa yang bisa diupdate.');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:ukm_ormawas,name,' . $ukmOrmawa->id,
            'type' => 'required|in:UKM,Ormawa',
            'category' => 'required|string|max:255',
            'logo_url_file' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048', // Untuk upload file logo
            'banner_url_file' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:4096', // Untuk upload file banner
            'description_short' => 'nullable|string|max:500',
            'description_full' => 'nullable|string',
            'visi' => 'nullable|string',
            'misi_input' => 'nullable|string', // Untuk input misi sebagai teks dipisahkan baris baru
            'contact_email' => 'nullable|email|max:255',
            'contact_instagram' => 'nullable|string|max:255',
            'is_registration_open' => 'sometimes|boolean',
            'registration_deadline' => 'nullable|date|after_or_equal:today',
        ]);

        $dataToUpdate = $validated;
        $dataToUpdate['slug'] = Str::slug($validated['name']); // Update slug berdasarkan nama baru

        // Handle upload logo
        if ($request->hasFile('logo_url_file')) {
            if ($ukmOrmawa->logo_url && Storage::disk('public')->exists($ukmOrmawa->logo_url)) {
                Storage::disk('public')->delete($ukmOrmawa->logo_url);
            }
            $dataToUpdate['logo_url'] = $request->file('logo_url_file')->store('ukm_logos', 'public');
        }

        // Handle upload banner
        if ($request->hasFile('banner_url_file')) {
            if ($ukmOrmawa->banner_url && Storage::disk('public')->exists($ukmOrmawa->banner_url)) {
                Storage::disk('public')->delete($ukmOrmawa->banner_url);
            }
            $dataToUpdate['banner_url'] = $request->file('banner_url_file')->store('ukm_banners', 'public');
        }

        // Proses input misi
        if (!empty($validated['misi_input'])) {
            $misiArray = array_map('trim', explode("\n", $validated['misi_input']));
            $misiArray = array_filter($misiArray); // Hapus baris kosong
            $dataToUpdate['misi'] = $misiArray; // Eloquent akan otomatis encode ke JSON karena ada di $casts
        } else {
            $dataToUpdate['misi'] = [];
        }
        
        // Konversi 'is_registration_open'
        $dataToUpdate['is_registration_open'] = $request->has('is_registration_open');


        $ukmOrmawa->update($dataToUpdate);

        return redirect()->route('pengurus.ukm-ormawa.edit')->with('success', 'Data UKM/Ormawa berhasil diperbarui!');
    }

    // Jika butuh method create dan store:
    /*
    public function create()
    {
        // Cek apakah pengurus ini sudah mengelola UKM
        if (Auth::user()->managesUkmOrmawa) {
            return redirect()->route('pengurus.ukm-ormawa.edit')->with('info', 'Anda sudah mengelola UKM. Anda bisa mengeditnya di sini.');
        }
        return view('pengurus.ukm-ormawa.create');
    }

    public function store(Request $request)
    {
        // Validasi mirip dengan update, tapi tanpa ID unik
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:ukm_ormawas,name',
            'type' => 'required|in:UKM,Ormawa',
            'category' => 'required|string|max:255',
            'logo_url_file' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'banner_url_file' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:4096',
            'description_short' => 'nullable|string|max:500',
            'description_full' => 'nullable|string',
            'visi' => 'nullable|string',
            'misi_input' => 'nullable|string',
            'contact_email' => 'nullable|email|max:255',
            'contact_instagram' => 'nullable|string|max:255',
            'is_registration_open' => 'sometimes|boolean',
            'registration_deadline' => 'nullable|date|after_or_equal:today',
        ]);

        $dataToCreate = $validated;
        $dataToCreate['slug'] = Str::slug($validated['name']);
        $dataToCreate['pengurus_id'] = Auth::id(); // Set pengurus yang membuat

        if ($request->hasFile('logo_url_file')) {
            $dataToCreate['logo_url'] = $request->file('logo_url_file')->store('ukm_logos', 'public');
        }
        if ($request->hasFile('banner_url_file')) {
            $dataToCreate['banner_url'] = $request->file('banner_url_file')->store('ukm_banners', 'public');
        }

        if (!empty($validated['misi_input'])) {
            $misiArray = array_map('trim', explode("\n", $validated['misi_input']));
            $misiArray = array_filter($misiArray);
            $dataToCreate['misi'] = $misiArray;
        } else {
            $dataToCreate['misi'] = [];
        }
        
        $dataToCreate['is_registration_open'] = $request->has('is_registration_open');

        $ukmOrmawa = UkmOrmawa::create($dataToCreate);

        // Update user pengurus untuk menandakan UKM yang dikelolanya
        $user = Auth::user();
        $user->manages_ukm_ormawa_id = $ukmOrmawa->id;
        $user->save();

        return redirect()->route('pengurus.dashboard')->with('success', 'UKM/Ormawa berhasil dibuat dan ditambahkan ke pengelolaan Anda!');
    }
    */
}