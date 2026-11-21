<?php

namespace App\Services;

use App\Models\Pegawai;
use App\Models\Rekap;
use Carbon\Carbon;

/**
 * Menyusun data rekap absensi apel untuk rentang tanggal.
 * Hasilnya dipakai bersama oleh ekspor PDF dan ekspor Excel.
 */
class RekapApelService
    {
    /** Simbol yang tampil di sel tiap pegawai, sesuai enum status di tabel detail_rekap. */
    const SIMBOL_STATUS = [
        'Hadir'       => '✓',
        'Tidak Hadir' => '-',
        'Izin'        => 'I',
        'Sakit'       => 'S',
        'Dinas Luar'  => 'DL',
        'Cuti'        => 'C',
        'Terlambat'   => 'T',
    ];

    /** Status yang direkap pada bagian KETERANGAN. Tidak Hadir sudah masuk baris ringkasan. */
    const STATUS_KETERANGAN = ['Sakit', 'Izin', 'Dinas Luar', 'Cuti', 'Terlambat'];

    /** Kode sesi apel, dipetakan dari enum jenis_apel. */
    const SESI_PAGI  = 'P';
    const SESI_SIANG = 'S';

    /**
     * Bangun data rekap untuk rentang tanggal $awal sampai $akhir (inklusif).
     */
    public function buatRekapRentang(Carbon $awal, Carbon $akhir): array
    {
        $awal  = $awal->copy()->locale('id');
        $akhir = $akhir->copy()->locale('id');

   
        $pegawai = Pegawai::orderBy('id')->get();
        $rekap   = $this->ambilRekap($awal, $akhir);

        // Susun daftar hari dan kolom (dua kolom per hari: P dan S)
        [$hari, $kolom] = $this->susunKolom($awal, $akhir);

        // Indeks rekap berdasarkan kunci kolom, mis. "2026-10-12|P"
        $rekapPerKolom = [];
        foreach ($rekap as $item) {
            $kunci = Carbon::parse($item->tanggal)->toDateString() . '|' . $this->tentukanSesi($item);
            $rekapPerKolom[$kunci] ??= $item;
        }

        $sel        = [];
        $ringkasan  = ['JUMLAH PEGAWAI' => [], 'HADIR' => [], 'TIDAK HADIR' => []];
        $keterangan = [];
        foreach (self::STATUS_KETERANGAN as $status) {
            $keterangan[mb_strtoupper($status)] = [];
        }

        foreach ($kolom as $k) {
            // Kolom tanpa data rekap dibiarkan kosong
            if (!isset($rekapPerKolom[$k['key']])) {
                $ringkasan['JUMLAH PEGAWAI'][$k['key']] = null;
                $ringkasan['HADIR'][$k['key']]          = null;
                $ringkasan['TIDAK HADIR'][$k['key']]    = null;
                foreach (self::STATUS_KETERANGAN as $status) {
                    $keterangan[mb_strtoupper($status)][$k['key']] = null;
                }
                continue;
            }

            $detail     = $rekapPerKolom[$k['key']]->detailRekap->keyBy('pegawai_id');
            $hadir      = 0;
            $tidakHadir = 0;
            $hitung     = array_fill_keys(self::STATUS_KETERANGAN, 0);

            foreach ($pegawai as $p) {
                $item = $detail->get($p->id);
                if (!$item) {
                    continue;
                }

                $sel[$p->id][$k['key']] = self::SIMBOL_STATUS[$item->status] ?? '';

                if ($item->status === 'Hadir') {
                    $hadir++;
                } else {
                    $tidakHadir++;
                }

                if (isset($hitung[$item->status])) {
                    $hitung[$item->status]++;
                }
            }

            $ringkasan['JUMLAH PEGAWAI'][$k['key']] = $pegawai->count();
            $ringkasan['HADIR'][$k['key']]          = $hadir;
            $ringkasan['TIDAK HADIR'][$k['key']]    = $tidakHadir;

            foreach (self::STATUS_KETERANGAN as $status) {
                $keterangan[mb_strtoupper($status)][$k['key']] = $hitung[$status];
            }
        }

        // Baris pegawai dengan nomor urut dan simbol per kolom
        $baris = [];
        foreach ($pegawai as $index => $p) {
            $baris[] = [
                'no'   => $index + 1,
                'nama' => $this->formatNama($p->nama),
                'sel'  => $sel[$p->id] ?? [],
            ];
        }

        return [
            'judul_tabel'    => config('rekap_apel.judul_tabel'),
            'instansi'       => config('rekap_apel.instansi'),
            'kop'            => config('rekap_apel.kop'),
            'ttd'            => config('rekap_apel.ttd'),
            'hari'           => $hari,
            'kolom'          => $kolom,
            'baris'          => $baris,
            'ringkasan'      => $ringkasan,
            'keterangan'     => $keterangan,
            'legenda_sesi'   => [self::SESI_PAGI => 'Apel Pagi', self::SESI_SIANG => 'Apel Siang'],
            'legenda_status' => array_flip(self::SIMBOL_STATUS),
            'minggu_ke' => $awal->weekOfMonth,
            'bulan'     => mb_strtoupper($awal->translatedFormat('F')),
            'tahun'     => $awal->year,
            'periode'   => $this->formatPeriode($awal, $akhir),
        ];
    }

    /**
     * Format nama pegawai: bagian nama sebelum koma ditulis kapital, sedangkan gelar setelah koma dibiarkan seperti aslinya.
     * Contoh: "Ismet Sitorus, S.H., M.Si" menjadi "ISMET SITORUS, S.H., M.Si"
     */
    private function formatNama(string $nama): string
    {
        $posisiKoma = mb_strpos($nama, ',');

        // Tanpa koma berarti tidak ada gelar, seluruh nama dikapitalkan
        if ($posisiKoma === false) {
            return mb_strtoupper($nama);
        }

        $inti  = mb_substr($nama, 0, $posisiKoma);
        $gelar = mb_substr($nama, $posisiKoma);

        return mb_strtoupper($inti) . $gelar;
    }



    private function formatPeriode(Carbon $awal, Carbon $akhir): string
    {
    if ($awal->isSameMonth($akhir)) {
        return $awal->format('j') . ' – ' . $akhir->translatedFormat('j F Y');
    }

    if ($awal->year === $akhir->year) {
        return $awal->translatedFormat('j F') . ' – ' . $akhir->translatedFormat('j F Y');
    }

    return $awal->translatedFormat('j F Y') . ' – ' . $akhir->translatedFormat('j F Y');
}

    /**
     * Ambil semua rekap pada rentang tanggal beserta detail per pegawai.
     */
    private function ambilRekap(Carbon $awal, Carbon $akhir)
    {
        return Rekap::with('detailRekap')
            ->whereBetween('tanggal', [$awal->toDateString(), $akhir->toDateString()])
            ->get();
    }

    /**
     * Susun daftar hari (untuk header bertingkat) dan daftar kolom sesi (P dan S)
     * untuk setiap tanggal dalam rentang.
     */
    private function susunKolom(Carbon $awal, Carbon $akhir): array
    {
        $hari  = [];
        $kolom = [];

        for ($tanggal = $awal->copy(); $tanggal->lte($akhir); $tanggal->addDay()) {
            $hari[] = [
                'label'   => mb_strtoupper($tanggal->translatedFormat('l, j')),
                'tanggal' => $tanggal->toDateString(),
            ];

            foreach ([self::SESI_PAGI, self::SESI_SIANG] as $sesi) {
                $kolom[] = [
                    'key'  => $tanggal->toDateString() . '|' . $sesi,
                    'sesi' => $sesi,
                ];
            }
        }

        return [$hari, $kolom];
    }

    /**
     * Tentukan sesi dari enum jenis_apel ('Apel Pagi' atau 'Apel Siang').
     */
    private function tentukanSesi(Rekap $rekap): string
    {
        return $rekap->jenis_apel === 'Apel Siang' ? self::SESI_SIANG : self::SESI_PAGI;
    }
}
/**
 * Susun daftar hari (untuk header bertingkat) dan daftar kolom sesi (P dan S)
 * untuk setiap tanggal dalam rentang.
 */

