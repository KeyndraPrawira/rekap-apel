{{-- Blok tanda tangan: judul, jabatan, nama, dan NIP --}}
<div>
    @if($blok['judul'] !== '')
        {{ $blok['judul'] }}<br>
    @endif
    {{ $blok['jabatan'] }}
    <div class="garis">{{ $blok['nama'] }}</div>
    NIP. {{ $blok['nip'] }}
</div>