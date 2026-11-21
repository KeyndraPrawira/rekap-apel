<?php

namespace App\Http\Controllers;

use App\Models\DetailRekap;

use App\Models\Pegawai;
use App\Models\Rekap;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use SweetAlert2\Laravel\Swal;

class AbsenController extends Controller
{
    /**
     * Tampilkan daftar rekap absen.
     */
    public function index()
    {
        $rekap = Rekap::with(['pegawai', 'detailRekap.pegawai'])
            ->latest('tanggal')
            ->paginate(10);

        return view('absen.index', compact('rekap'));
    }

    /**
     * Form tambah absen.
     */
    public function create()
    {
      

        return view('absen.create');
    }

    /**
     * Simpan data absen (rekap dan detail rekap).
     */// Langkah 1: Simpan header absen (tanggal & jenis apel), lalu arahkan ke halaman detail
public function store(Request $request)
{
    $validated = $request->validate([
        'tanggal' => 'required|date',
        'jenis_apel' => 'required',
    ]);

    $absen = Rekap::create($validated);

    // Redirect ke halaman pengisian daftar hadir (Halaman 2)
    return redirect()->route('absen.detail', $absen->id)
        ->with('success', 'Sesi apel dibuat, silakan isi daftar hadir pegawai.');
}

// Langkah 2: Tampilkan halaman tabel daftar hadir pegawai
public function detail(Rekap $absen)
{
    $absen->load([ 'detailRekap']);
    $pegawai = Pegawai::orderBy('nama')->get();

    // Opsional: Ambil nilai enum langsung dari database atau definisikan array enum
    $statusOptions = ['Hadir', 'Tidak Hadir', 'Izin', 'Sakit', 'Alpa', 'Dinas Luar', 'Cuti', 'Terlambat'];

    return view('absen.detail', compact('absen', 'pegawai', 'statusOptions'));
}

// Langkah 2 (Submit): Simpan semua baris detail kehadiran pegawai
public function storeDetail(Request $request, Rekap $absen)
{
    $request->validate([
        'detail' => 'required|array',
        'detail.*.pegawai_id' => 'required|exists:pegawai,id',
        'detail.*.status' => 'required|string',
        'detail.*.keterangan' => 'required|string|max:255',
    ]);

    foreach ($request->detail as $item) {
        DetailRekap::updateOrCreate(
            [
                'rekap_id' => $absen->id,
                'pegawai_id' => $item['pegawai_id'],
            ],
            [
                'status' => $item['status'],
                'keterangan' => $item['keterangan'],
            ]
        );
    }

    return redirect()->route('absen.index')
        ->with('success', 'Rekap kehadiran apel berhasil disimpan.');
}
    /**
     * Tampilkan detail absen.
     */
    public function show(Rekap $absen)
    {
        $absen->load(['pegawai', 'detailRekap.pegawai']);

        return view('absen.show', ['rekap' => $absen]);
    }

    /**
     * Form edit absen.
     */
    public function edit(Rekap $absen)
    {
        $absen->load(['detailRekap']);
        $pegawai = Pegawai::orderBy('nama')->get();
       $jenisApel = ['Apel Pagi', 'Apel Siang'];
       $statusOptions = ['Hadir', 'Izin', 'Sakit', 'Alpa', 'Dinas Luar', 'Cuti', 'Terlambat'];

        return view('absen.edit', [
            'rekap' => $absen,
            'pegawai' => $pegawai,
            'jenisApel' => $jenisApel,
            'statusOptions' => $statusOptions,
        ]);
    }

    /**
     * Perbarui data absen.
     */
    public function update(Request $request, Rekap $absen)
    {
        $validated = $request->validate([
            'jenis_apel' => 'required|in:Apel Pagi,Apel Siang',
            'tanggal'       => 'required|date',
            'detail'        => 'nullable|array',
            'detail.*.pegawai_id'  => 'required_with:detail|exists:pegawai,id',
            'detail.*.status'      => 'required_with:detail|in:Hadir,Izin,Sakit,Alpa',
            'detail.*.keterangan'  => 'nullable|string|max:255',
        ]);

        DB::transaction(function () use ($validated, $absen) {
            $absen->update([
                'jenis_apel' => $validated['jenis_apel'],
                'tanggal'       => $validated['tanggal'],
            ]);

            $absen->detailRekap()->delete();

            foreach ($validated['detail'] ?? [] as $item) {
                $absen->detailRekap()->create([
                    'pegawai_id'  => $item['pegawai_id'],
                    'status'      => $item['status'],
                    'keterangan'  => $item['keterangan'] ?? null,
                ]);
            }
        });

        Swal::success([
            'title' => 'Berhasil!',
            'text'  => 'Data absen berhasil diperbarui.',
        ]);

        return redirect()->route('absen.index')
            ->with('success', 'Data absen berhasil diperbarui.');
    }

    /**
     * Hapus data absen.
     */
    public function destroy(Rekap $absen)
    {
        $absen->delete();

        Swal::success([
            'title' => 'Berhasil!',
            'text'  => 'Data absen berhasil dihapus.',
        ]);

        return redirect()->route('absen.index')
            ->with('success', 'Data absen berhasil dihapus.');
    }
}
