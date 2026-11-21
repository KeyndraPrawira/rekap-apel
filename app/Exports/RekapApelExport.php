<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;

/**
 * Ekspor rekap apel mingguan ke file Excel.
 * Tata letak dan isinya mengikuti data dari RekapApelService.
 */
class RekapApelExport implements FromArray, WithTitle, WithColumnWidths, WithEvents
{
    /** Jumlah kolom tetap di sisi kiri tabel: NO dan NAMA. */
    private const KOLOM_TETAP_KIRI = 2;

    /** Nomor baris header pada sheet (1-based). */
    private const BARIS_HEADER_1 = 4;
    private const BARIS_HEADER_2 = 5;

    /** Data rekap dari RekapApelService. */
    private array $data;

    /** Nomor baris yang label-nya digabung di kolom A dan B. */
    private array $barisLabel = [];

    /** Nomor baris terakhir tabel rekap, sebelum bagian tanda tangan. */
    private int $barisAkhirRekap = 0;

    /** Nomor baris terakhir data pegawai. */
    private int $barisAkhirPegawai = 0;

    public function __construct(array $data)
    {
        $this->data = $data;
    }

    /**
     * Nama sheet di file Excel.
     */
    public function title(): string
    {
        return 'Rekap Apel';
    }

    /**
     * Lebar kolom: NO dan NAMA lebar, kolom sesi sempit.
     */
    public function columnWidths(): array
    {
        $lebar = ['A' => 6, 'B' => 34];

        $jumlahKolom = count($this->data['kolom']);
        for ($i = 0; $i < $jumlahKolom; $i++) {
            $lebar[Coordinate::stringFromColumnIndex(self::KOLOM_TETAP_KIRI + 1 + $i)] = 5;
        }

        $lebar[$this->kolomKet()] = 12;

        return $lebar;
    }

    /**
     * Susun seluruh baris sheet: judul, header, pegawai, ringkasan, keterangan, legenda, dan tanda tangan.
     */
    public function array(): array
    {
        $kolom = $this->data['kolom'];
        $rows  = [];

        // Judul laporan
        $rows[] = [$this->data['judul_tabel']];
        $rows[] = ['BULAN ' . $this->data['bulan'] . ' TAHUN ' . $this->data['tahun']];
        $rows[] = [];

        // Header baris 1: nama hari (digabung dua kolom di sheet)
        $header1 = ['NO', 'NAMA'];
        foreach ($this->data['hari'] as $hari) {
            $header1[] = $hari['label'];
            $header1[] = '';
        }
        $header1[] = 'KET';
        $rows[] = $header1;

        // Header baris 2: kode sesi P dan S
        $header2 = ['', ''];
        foreach ($kolom as $k) {
            $header2[] = $k['sesi'];
        }
        $header2[] = '';
        $rows[] = $header2;

        // Data pegawai
        foreach ($this->data['baris'] as $b) {
            $baris = [$b['no'], $b['nama']];
            foreach ($kolom as $k) {
                $baris[] = $b['sel'][$k['key']] ?? '';
            }
            $baris[] = '';
            $rows[] = $baris;
        }
        $this->barisAkhirPegawai = count($rows);

        // Ringkasan jumlah pegawai, hadir, dan tidak hadir
        $rows[] = [];
        $rows = $this->tambahRingkasan($rows, $this->data['ringkasan'], $kolom);

        // Rekap per status pada bagian KETERANGAN
        $rows[] = [];
        $this->barisLabel[] = count($rows) + 1;
        $rows[] = ['KETERANGAN'];
        $rows = $this->tambahRingkasan($rows, $this->data['keterangan'], $kolom);
        $this->barisAkhirRekap = count($rows);

        // Legenda kode sesi dan kode status
        $rows[] = [];
        $rows[] = ['Kode sesi'];
        foreach ($this->data['legenda_sesi'] as $kode => $arti) {
            $rows[] = ['', $kode . ' = ' . $arti];
        }
        $rows[] = ['Kode status'];
        foreach ($this->data['legenda_status'] as $kode => $arti) {
            $rows[] = ['', $kode . ' = ' . $arti];
        }

        // Blok tanda tangan, ditulis bertumpuk di kolom B
        foreach (['kiri', 'kanan', 'pembuat'] as $posisi) {
            $ttd = $this->data['ttd'][$posisi];
            $rows[] = [];
            if ($ttd['judul'] !== '') {
                $rows[] = ['', $ttd['judul']];
            }
            $rows[] = ['', $ttd['jabatan']];
            $rows[] = [];
            $rows[] = [];
            $rows[] = ['', $ttd['nama']];
            $rows[] = ['', 'NIP. ' . $ttd['nip']];
        }

        return $rows;
    }

    /**
     * Tambahkan baris ringkasan atau keterangan, label di kolom A dan nilai per kolom sesi.
     */
    private function tambahRingkasan(array $rows, array $sumber, array $kolom): array
    {
        foreach ($sumber as $label => $nilaiPerKolom) {
            $this->barisLabel[] = count($rows) + 1;

            $row = [$label, ''];
            foreach ($kolom as $k) {
                $row[] = $nilaiPerKolom[$k['key']] ?? '';
            }
            $row[] = '';

            $rows[] = $row;
        }

        return $rows;
    }

    /**
     * Penggabungan sel, border, dan perataan setelah sheet terbentuk.
     */
    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet        = $event->sheet->getDelegate();
                $kolomAkhir   = $this->kolomKet();
                $jumlahHari   = count($this->data['hari']);

                // Judul rata tengah dan digabung di seluruh lebar tabel
                $sheet->mergeCells("A1:{$kolomAkhir}1");
                $sheet->mergeCells("A2:{$kolomAkhir}2");
                $sheet->getStyle("A1:{$kolomAkhir}2")->getFont()->setBold(true);
                $sheet->getStyle("A1:{$kolomAkhir}2")->getAlignment()
                    ->setHorizontal(Alignment::HORIZONTAL_CENTER);

                // NO, NAMA, dan KET digabung vertikal di dua baris header
                $sheet->mergeCells('A' . self::BARIS_HEADER_1 . ':A' . self::BARIS_HEADER_2);
                $sheet->mergeCells('B' . self::BARIS_HEADER_1 . ':B' . self::BARIS_HEADER_2);
                $sheet->mergeCells($kolomAkhir . self::BARIS_HEADER_1 . ':' . $kolomAkhir . self::BARIS_HEADER_2);

                // Nama hari digabung dua kolom (P dan S)
                for ($d = 0; $d < $jumlahHari; $d++) {
                    $mulai  = Coordinate::stringFromColumnIndex(self::KOLOM_TETAP_KIRI + 1 + $d * 2);
                    $selesai = Coordinate::stringFromColumnIndex(self::KOLOM_TETAP_KIRI + 2 + $d * 2);
                    $sheet->mergeCells($mulai . self::BARIS_HEADER_1 . ':' . $selesai . self::BARIS_HEADER_1);
                }

                // Label ringkasan dan keterangan digabung di kolom A dan B
                foreach ($this->barisLabel as $baris) {
                    $sheet->mergeCells("A{$baris}:B{$baris}");
                    $sheet->getStyle("A{$baris}")->getFont()->setBold(true);
                }

                // Header tebal dengan latar abu-abu
                $sheet->getStyle('A' . self::BARIS_HEADER_1 . ":{$kolomAkhir}" . self::BARIS_HEADER_2)
                    ->getFont()->setBold(true);
                $sheet->getStyle('A' . self::BARIS_HEADER_1 . ":{$kolomAkhir}" . self::BARIS_HEADER_2)
                    ->getFill()->setFillType(Fill::FILL_SOLID)
                    ->getStartColor()->setARGB('FFF3F4F6');

                // Border untuk seluruh tabel rekap
                $sheet->getStyle('A' . self::BARIS_HEADER_1 . ":{$kolomAkhir}{$this->barisAkhirRekap}")
                    ->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);

                // Isi sesi dan KET rata tengah, nama rata kiri
                $sheet->getStyle("C" . self::BARIS_HEADER_1 . ":{$kolomAkhir}{$this->barisAkhirRekap}")
                    ->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                $sheet->getStyle("B6:B{$this->barisAkhirPegawai}")
                    ->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);
            },
        ];
    }

    /**
     * Huruf kolom KET, yaitu kolom setelah seluruh kolom sesi.
     */
    private function kolomKet(): string
    {
        return Coordinate::stringFromColumnIndex(self::KOLOM_TETAP_KIRI + count($this->data['kolom']) + 1);
    }
}