<?php

/**
 * Konfigurasi kop surat dan tanda tangan untuk ekspor rekap apel.
 * Nama dan NIP pejabat diisi lewat file .env.
 */
return [

    'judul_tabel' => 'ABSENSI APEL PEGAWAI STAF',

    'instansi' => 'Lembaga Pemasyarakatan Kelas IIB Tasikmalaya',

    // Baris-baris kop surat yang tampil di bagian atas PDF
    'kop' => [
        'KEMENTERIAN IMIGRASI DAN PEMASYARAKATAN REPUBLIK INDONESIA',
        'DIREKTORAT JENDERAL PEMASYARAKATAN',
        'KANTOR WILAYAH JAWA BARAT',
        'LEMBAGA PEMASYARAKATAN KELAS IIB TASIKMALAYA',
        'Jl. Otto Iskandardinata No.1 Tasikmalaya, Kode Pos 46112',
        'Laman: lapastasikmalaya.kemenkumham.go.id   Pos-el: lp.tasik@kemenkumham.go.id',
    ],

    // Blok tanda tangan: kiri, kanan, dan pembuat di bawah
    'ttd' => [
        'kiri' => [
            'judul'   => 'Mengetahui,',
            'jabatan' => 'Kepala Lapas',
            'nama'    => env('REKAP_TTD_KIRI_NAMA', ''),
            'nip'     => env('REKAP_TTD_KIRI_NIP', ''),
        ],
        'kanan' => [
            'judul'   => '',
            'jabatan' => 'Kasubag. Tata Usaha',
            'nama'    => env('REKAP_TTD_KANAN_NAMA', ''),
            'nip'     => env('REKAP_TTD_KANAN_NIP', ''),
        ],
        'pembuat' => [
            'judul'   => 'Dibuat oleh,',
            'jabatan' => 'Kaur. Kepegawaian dan Keuangan',
            'nama'    => env('REKAP_TTD_PEMBUAT_NAMA', ''),
            'nip'     => env('REKAP_TTD_PEMBUAT_NIP', ''),
        ],
    ],
];