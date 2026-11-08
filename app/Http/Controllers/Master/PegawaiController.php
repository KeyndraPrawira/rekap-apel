<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Models\Pegawai;
use Illuminate\Http\Request;
use SweetAlert2\Laravel\Swal;

class PegawaiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $pegawai = Pegawai::latest()->paginate(10);
        return view('master.pegawai.index', compact('pegawai'));
    }

    /**
     * Menampilkan form tambah master.pegawai.
     */
    public function create()
    {
        return view('master.pegawai.create');
    }

    /**
     * Menyimpan data pegawai baru.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama'    => 'required|string|max:255',
            'jabatan' => 'required|string|max:255',
            'no_hp'   => 'required|string|max:20',
            'email'   => 'required|email|unique:pegawai,email',
        ]);

        Pegawai::create($validated);

        Swal::success([
            'title' => 'Berhasil!',
            'text' => 'Data pegawai berhasil ditambahkan.',
        ]);

        return redirect()->route('pegawai.index')
            ->with('success', 'Data pegawai berhasil ditambahkan.');
    }

    /**
     * Menampilkan detail master.pegawai.
     */
    public function show(Pegawai $pegawai)
    {
        return view('master.pegawai.show', compact('pegawai'));
    }

    /**
     * Menampilkan form edit master.pegawai.
     */
    public function edit(Pegawai $pegawai)
    {
        return view('master.pegawai.edit', compact('pegawai'));
    }

    /**
     * Memperbarui data master.pegawai.
     */
    public function update(Request $request, Pegawai $pegawai)
    {
        $validated = $request->validate([
            'nama'    => 'required|string|max:255',
            'jabatan' => 'required|string|max:255',
            'no_hp'   => 'required|string|max:20',
            'email'   => 'required|email|unique:pegawai,email,' . $pegawai->id,
        ]);

        $pegawai->update($validated);

        return redirect()->route('pegawai.index')
            ->with('success', 'Data pegawai berhasil diperbarui.');
    }

    /**
     * Menghapus data master.pegawai.
     */
    public function destroy(Pegawai $pegawai)
    {
        
        $pegawai->delete();
        Swal::success([
            'title' => 'Berhasil!',
            'text' => 'Data pegawai berhasil dihapus.',
        ]);
        
        return redirect()->route('pegawai.index')
            ->with('success', 'Data pegawai berhasil dihapus.');
    }
}
