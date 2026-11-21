<?php

namespace App\Http\Controllers;

use App\Models\DetailRekap;
use App\Models\Pegawai;
use App\Models\Rekap;
use Illuminate\Http\Request;

class Dashboard extends Controller
{
    // app/Http/Controllers/DashboardController.php
    public function index(Request $request)
    {
        // Validasi format tanggal; keduanya harus diisi jika salah satu diisi
        $request->validate([
            'mulai' => 'nullable|date|required_with:selesai',
            'selesai' => 'nullable|date|required_with:mulai',
        ]);

        $mulai = $request->input('mulai');
        $selesai = $request->input('selesai');

        // Filter periode hanya aktif jika kedua tanggal diisi; jika tidak, tampilkan seluruh data
        $terfilter = filled($mulai) && filled($selesai);

        // Tukar jika tanggal awal lebih besar dari tanggal akhir
        if ($terfilter && $mulai > $selesai) {
            [$mulai, $selesai] = [$selesai, $mulai];
        }

        // Jumlah pegawai unik yang tercatat dalam periode (atau seluruh data)
        $jumlahPegawai = Pegawai::count();

        // Tren persentase kehadiran harian dalam periode (atau seluruh data)
        $tren = Rekap::query()
            ->join('detail_rekap', 'detail_rekap.rekap_id', '=', 'rekap.id')
            ->when($terfilter, fn ($q) => $q->whereBetween('rekap.tanggal', [$mulai, $selesai]))
            ->selectRaw("
                rekap.tanggal,
                rekap.jenis_apel,
                ROUND(SUM(detail_rekap.status = 'Hadir') / COUNT(*) * 100, 1) as persen
            ")
            ->groupBy('rekap.tanggal', 'rekap.jenis_apel')
            ->orderBy('rekap.tanggal')
            ->get();

        // Label sumbu X dan data grafik per tanggal
        $labels = $tren->pluck('tanggal')->unique()->map(fn ($t) => \Carbon\Carbon::parse($t)->format('d M'))->values();
        $dataPagi = [];
        $dataSiang = [];
        foreach ($tren->groupBy('tanggal') as $tanggal => $baris) {
            $dataPagi[] = optional($baris->firstWhere('jenis_apel', 'Apel Pagi'))->persen ?? null;
            $dataSiang[] = optional($baris->firstWhere('jenis_apel', 'Apel Siang'))->persen ?? null;
        }
         

        return view('dashboard', [
            'jumlahPegawai' => $jumlahPegawai,
            'labels' => $labels,
            'dataPagi' => $dataPagi,
            'dataSiang' => $dataSiang,
            'mulai' => $terfilter ? $mulai : null,
            'selesai' => $terfilter ? $selesai : null,
        ]);
    }
}