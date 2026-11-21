<?php

namespace App\Http\Controllers;

use App\Exports\RekapApelExport;
use App\Models\DetailRekap;

use App\Models\Pegawai;
use App\Models\Rekap;
use App\Services\RekapApelService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use SweetAlert2\Laravel\Swal;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Maatwebsite\Excel\Facades\Excel;

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
        // Tanggal dan jenis apel harus kombinasi yang unik, sesi yang sama tidak boleh dibuat dua kali
        $validated = $request->validate([
            'tanggal' => [
                'required',
                'date',
                Rule::unique(Rekap::class, 'tanggal')
                    ->where(fn ($query) => $query->where('jenis_apel', $request->jenis_apel)),
            ],
            'jenis_apel' => 'required',
        ], [
            'tanggal.unique' => 'Sesi apel dengan tanggal dan jenis apel tersebut sudah dibuat.',
        ]);

        $absen = Rekap::create($validated);

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
       $statusOptions = Rekap::STATUS;

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
            'detail.*.status' => ['required_with:detail', Rule::in(Rekap::STATUS)],
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

    /**
 * Membatalkan sesi apel. Detail kehadiran ikut terhapus otomatis lewat cascade di database.
 */
    public function batal(Rekap $absen)
    {
        $absen->delete();

        return redirect()->route('absen.index')
            ->with('success', 'Sesi apel dibatalkan dan dihapus.');
    }

    /**
     * Ekspor rekap apel mingguan ke PDF.
     */

    /** Batas maksimal jumlah hari dalam satu kali ekspor. */
    private const MAKS_HARI_EKSPOR = 7;


   public function exportPdf(Request $request, RekapApelService $rekapService)
{
    [$awal, $akhir] = $this->validasiRentang($request);

    $data = $rekapService->buatRekapRentang($awal, $akhir);

    return Pdf::loadView('absen.export.rekap-pdf', $data)
        ->setPaper('a4', 'potrait')
        ->download($this->namaFile($awal, $akhir, 'pdf'));
}

    /**
 * Ekspor rekap apel mingguan ke Excel.
 */
    public function exportExcel(Request $request, RekapApelService $rekapService)
    {
        [$awal, $akhir] = $this->validasiRentang($request);

        $data = $rekapService->buatRekapRentang($awal, $akhir);

        return Excel::download(new RekapApelExport($data), $this->namaFile($awal, $akhir, 'xlsx'));
    }


    /**
 * Validasi input rentang tanggal dari modal.
 * Jika gagal, pengguna kembali ke halaman sebelumnya dan pesan error ditampilkan di modal.
 */
private function validasiRentang(Request $request): array
{
    $request->validate([
        'tanggal_awal'  => ['required', 'date'],
        'tanggal_akhir' => ['required', 'date', 'after_or_equal:tanggal_awal'],
    ], [
        'tanggal_awal.required'        => 'Tanggal awal wajib diisi.',
        'tanggal_akhir.required'       => 'Tanggal akhir wajib diisi.',
        'tanggal_akhir.after_or_equal' => 'Tanggal akhir tidak boleh sebelum tanggal awal.',
    ]);

    $awal  = Carbon::parse($request->input('tanggal_awal'))->startOfDay();
    $akhir = Carbon::parse($request->input('tanggal_akhir'))->startOfDay();

    // Rentang dihitung inklusif, jadi selisih + 1 hari
    if ($awal->diffInDays($akhir) + 1 > self::MAKS_HARI_EKSPOR) {
        throw ValidationException::withMessages([
            'tanggal_akhir' => 'Rentang ekspor maksimal ' . self::MAKS_HARI_EKSPOR . ' hari.',
        ]);
    }

    return [$awal, $akhir];
}

/**
 * Tanggal acuan minggu dari query string "tanggal", default hari ini.
 */
    
/**
 * Nama file ekspor, mis. Rekap_Apel_Minggu3_OKTOBER_2026.pdf
 */
    private function namaFile(Carbon $awal, Carbon $akhir, string $ekstensi): string
{
    return 'Rekap_Apel_' . $awal->format('d-m-Y') . '_sd_' . $akhir->format('d-m-Y') . '.' . $ekstensi;
}
}
