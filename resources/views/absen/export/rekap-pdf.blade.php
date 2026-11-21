{{-- Ekspor PDF rekap apel: hanya judul dan tabel, layout portrait A4, tabel memenuhi lebar kertas --}}
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>Rekap Apel {{ $periode }}</title>
    <style>
        /* Margin kertas 3mm di setiap sisi */
        @page { margin: 15mm; }
        body { margin: 0; font-family: DejaVu Sans, sans-serif; font-size: 9px; color: #000; }

        .judul-tabel { text-align: center; font-weight: bold; font-size: 11px; line-height: 1.5; margin-bottom: 4px; }

        /* Tabel mengisi lebar penuh. Kolom membesar proporsional sesuai isi */
        table.rekap { width: 100%; border-collapse: collapse; }
        table.rekap th, table.rekap td {
            border: 1px solid #000;
            padding: 3px 2px;
            text-align: center;
            font-size: 9px;
            white-space: nowrap;
        }
        table.rekap th { font-weight: bold; }
        table.rekap th.hari { background: #e5e7eb; font-size: 8.5px; }
        table.rekap th.sesi { background: #facc15; font-size: 8.5px; }
        table.rekap td.nama { text-align: left; padding-left: 4px; font-weight: bold; }
        table.rekap td.label { text-align: left; font-weight: bold; padding-left: 4px; }
    </style>
</head>
<body>

    <div class="judul-tabel">
        {{ $judul_tabel }}<br>
        BULAN {{ $bulan }} TAHUN {{ $tahun }}
    </div>

    <table class="rekap">
        <thead>
            <tr>
                <th rowspan="2">NO</th>
                <th rowspan="2">NAMA</th>
                @foreach($hari as $h)
                    <th class="hari" colspan="2">{{ $h['label'] }}</th>
                @endforeach
                <th rowspan="2">KET</th>
            </tr>
            <tr>
                @foreach($kolom as $k)
                    <th class="sesi">{{ $k['sesi'] }}</th>
                @endforeach
            </tr>
        </thead>

        <tbody>
            @forelse($baris as $b)
                <tr>
                    <td>{{ $b['no'] }}</td>
                    <td class="nama">{{ $b['nama'] }}</td>
                    @foreach($kolom as $k)
                        <td>{{ $b['sel'][$k['key']] ?? '' }}</td>
                    @endforeach
                    <td></td>
                </tr>
            @empty
                <tr>
                    <td colspan="{{ count($kolom) + 3 }}">Belum ada data pegawai.</td>
                </tr>
            @endforelse
        </tbody>

        <tbody>
            @foreach($ringkasan as $label => $nilai)
                <tr>
                    <td colspan="2" class="label">{{ $label }}</td>
                    @foreach($kolom as $k)
                        <td>{{ $nilai[$k['key']] ?? '' }}</td>
                    @endforeach
                    <td></td>
                </tr>
            @endforeach
        </tbody>

        <tbody>
            <tr>
                <td colspan="{{ count($kolom) + 3 }}" class="label">KETERANGAN</td>
            </tr>
            @foreach($keterangan as $label => $nilai)
                <tr>
                    <td colspan="2" class="label">{{ $label }}</td>
                    @foreach($kolom as $k)
                        <td>{{ $nilai[$k['key']] ?? '' }}</td>
                    @endforeach
                    <td></td>
                </tr>
            @endforeach
        </tbody>
    </table>

</body>
</html>