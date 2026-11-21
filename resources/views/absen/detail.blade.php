@extends('layouts.app')

@section('title', 'Daftar Hadir Pegawai - Rekap Apel')

@section('content')

<!-- Step Indicator -->
<div class="flex items-center justify-between bg-white px-6 py-4 rounded-2xl border border-gray-100 shadow-sm">
    <div class="flex items-center gap-3">
        <span class="w-8 h-8 rounded-full bg-emerald-100 text-emerald-600 font-bold text-sm flex items-center justify-center">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path>
            </svg>
        </span>
        <div>
            <p class="text-sm font-semibold text-gray-700">Informasi Apel</p>
            <p class="text-xs text-gray-400">Selesai dibuat</p>
        </div>
    </div>
    <div class="hidden sm:block h-px flex-1 bg-indigo-200 mx-4"></div>
    <div class="flex items-center gap-3">
        <span class="w-8 h-8 rounded-full bg-indigo-600 text-white font-bold text-sm flex items-center justify-center shadow-sm">2</span>
        <div>
            <p class="text-sm font-bold text-gray-800">Daftar Hadir Pegawai</p>
            <p class="text-xs text-gray-500">Lengkapi absensi & keterangan tiap pegawai</p>
        </div>
    </div>
</div>

<!-- Banner Keterangan Tanggal & Jenis Apel (Dari Halaman Sebelumnya) -->
<div class="bg-gradient-to-r from-indigo-600 to-indigo-700 rounded-2xl p-6 text-white shadow-sm flex flex-col sm:flex-row sm:items-center justify-between gap-4">
    <div class="space-y-1">
        <span class="inline-block px-2.5 py-0.5 bg-white/20 text-white text-xs font-semibold rounded-md uppercase tracking-wider">
            Rekap Kehadiran Apel
        </span>
        <h1 class="text-2xl font-bold tracking-tight">
            {{ $absen->jenisApel->nama_jenis ?? 'Apel Rutin' }}
        </h1>
        <p class="text-indigo-100 text-sm flex items-center gap-2 pt-1">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
            </svg>
            <span>Tanggal: <strong>{{ \Carbon\Carbon::parse($absen->tanggal)->translatedFormat('l, d F Y') }}</strong></span>
        </p>
    </div>

    <div class="bg-white/10 backdrop-blur-sm border border-white/20 rounded-xl px-4 py-3 text-right">
        <p class="text-xs text-indigo-100">Total Pegawai</p>
        <p class="text-2xl font-bold">{{ $pegawai->count() }} <span class="text-sm font-normal">Orang</span></p>
    </div>
</div>

<!-- Form Tabel Daftar Hadir -->
<div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
    <form id="form-detail-apel" action="{{ route('absen.detail.store', $absen->id) }}" method="POST">
        @csrf

        <!-- Header & Quick Action -->
        <div class="p-6 border-b border-gray-100 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <h2 class="text-base font-bold text-gray-800">Daftar Kehadiran Pegawai</h2>
                <p class="text-xs text-gray-500 mt-0.5">Pilih status absensi dan isi keterangan untuk setiap pegawai di bawah ini.</p>
            </div>

            <!-- Tombol Bantu: Set Semua Hadir -->
            <button type="button" 
                    onclick="setAllStatus('Hadir')"
                    class="inline-flex items-center gap-1.5 px-3.5 py-2 bg-emerald-50 hover:bg-emerald-100 text-emerald-700 text-xs font-semibold rounded-lg border border-emerald-200 transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                Tandai Semua Hadir
            </button>
        </div>

        @if ($errors->any())
            <div class="mx-6 mt-4 p-4 bg-rose-50 border border-rose-200 rounded-xl text-xs text-rose-600">
                <p class="font-bold mb-1">Terdapat kesalahan pengisian:</p>
                <ul class="list-disc list-inside space-y-0.5">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Tabel Input Pegawai -->
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-sm">
                <thead>
                    <tr class="bg-gray-50/80 border-b border-gray-100 text-xs font-semibold text-gray-500 uppercase tracking-wider">
                        <th class="py-3.5 px-4 w-12 text-center">No</th>
                        <th class="py-3.5 px-4">Nama Pegawai</th>
                        <th class="py-3.5 px-4 w-52">Status Absensi <span class="text-rose-500">*</span></th>
                        <th class="py-3.5 px-4">Keterangan <span class="text-rose-500">*</span></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @php
                        $details = $absen?->details ?? collect();
                    @endphp
                    @forelse($pegawai as $index =>$p)
                        @php
                            // Jika sedang edit / sudah ada data detail sebelumnya

                           
                                $existingDetail = $details->firstWhere('pegawai_id', $p->id);
                                $currentStatus = old("detail.{$p->id}.status", $existingDetail->status ?? '');
                                $currentKeterangan = old("detail.{$p->id}.keterangan", $existingDetail->keterangan ?? '');
                         
                        @endphp
                        <tr class="hover:bg-gray-50/60 transition-colors">
                            <!-- Nomor -->
                            <td class="py-3.5 px-4 text-center text-gray-500 font-medium">
                                {{ $index + 1 }}
                            </td>

                            <!-- Nama & Jabatan Pegawai -->
                            <td class="py-3.5 px-4">
                                <input type="hidden" name="detail[{{ $p->id }}][pegawai_id]" value="{{ $p->id }}">
                                <div class="font-semibold text-gray-800">{{ $p->nama }}</div>
                                @if($p->jabatan)
                                    <div class="text-xs text-gray-400">{{ $p->jabatan }}</div>
                                @endif
                            </td>

                            <!-- Dropdown Enum Status Absensi -->
                            <td class="py-3.5 px-4">
                                <select name="detail[{{ $p->id }}][status]" 
                                        class="status-select w-full px-3 py-2 bg-gray-50 border border-gray-200 text-sm rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 focus:bg-white transition-all" 
                                        required>
                                    <option value="" disabled {{ $currentStatus == '' ? 'selected' : '' }}>-- Pilih Status --</option>
                                    {{-- Sesuaikan daftar enum ini dengan kolom enum di tabel detail_absen kamu --}}
                                   @foreach($statusOptions ?? ['Hadir', 'Tidak Hadir', 'Izin', 'Sakit', 'Dinas Luar', 'Cuti', 'Terlambat'] as $status)
                                        <option value="{{ $status }}" {{ $currentStatus ==$status ? 'selected' : '' }}>
                                            {{ $status }}
                                        </option>
                                    @endforeach
                                </select>
                            </td>

                            <!-- Input Keterangan -->
                            <td class="py-3.5 px-4">
                                <input type="text" 
                                       name="detail[{{ $p->id }}][keterangan]" 
                                       value="{{ $currentKeterangan }}"
                                       placeholder="Masukkan keterangan (contoh: Tepat waktu / Surat dokter)"
                                       class="w-full px-3 py-2 bg-gray-50 border border-gray-200 text-sm rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 focus:bg-white transition-all"
                                       >
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="py-10 text-center text-gray-400">
                                Belum ada data pegawai di master data.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>


        <form id="form-batal-apel"
            action="{{ route('absen.destroy', $absen->id) }}"
            method="POST"
            class="hidden">
            @csrf
            @method('DELETE')
        </form>

       <!-- Footer Simpan -->
<div class="p-6 bg-gray-50/50 border-t border-gray-100 flex items-center justify-end gap-3">
    <button type="button"
            id="btn-batal-apel"
            class="px-5 py-2.5 bg-white border border-gray-200 hover:bg-gray-50 text-gray-700 text-sm font-medium rounded-xl transition-colors">
        Batal
    </button>

    <button type="submit"
            class="inline-flex items-center gap-2 px-6 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold rounded-xl shadow-sm transition-all focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
        </svg>
        Simpan Rekap Kehadiran
    </button>
</div>
</form>
</div>

<!-- Form hapus sesi apel, wajib di luar form utama -->
<form id="form-batal-apel"
      action="{{ route('absen.destroy', $absen->id) }}"
      method="POST"
      class="hidden">
    @csrf
    @method('DELETE')
</form>

<script>
    /**
     * Mengelola siklus hidup sesi apel yang sedang diisi.
     * - Tombol Batal: konfirmasi SweetAlert2, lalu hapus sesi.
     * - Meninggalkan halaman tanpa Simpan: hapus sesi secara otomatis.
     * - Simpan: sesi dipertahankan.
     */
    (function () {
        const formDetail = document.getElementById('form-detail-apel');
        const formBatal = document.getElementById('form-batal-apel');
        const urlHapus = "{{ route('absen.destroy', $absen->id) }}";
        const csrfToken = document.querySelector('meta[name="csrf-token"]').content;

        // Penanda agar sesi tidak dihapus dua kali (misalnya setelah Batal, halaman juga ditinggalkan)
        let sudahSelesai = false;

        // Saat Simpan ditekan, sesi harus dipertahankan
        formDetail.addEventListener('submit', function () {
            sudahSelesai = true;
        });

        // Tombol Batal: konfirmasi dulu, lalu kirim form hapus
        document.addEventListener('click', function (event) {
            const btnBatal = event.target.closest('#btn-batal-apel');
            if (!btnBatal) {
                return;
            }

            event.preventDefault();

            Swal.fire({
                title: 'Batalkan sesi apel?',
                text: 'Sesi ini dan seluruh daftar hadir yang sudah diisi akan dihapus permanen.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Ya, batalkan',
                cancelButtonText: 'Tidak',
                confirmButtonColor: '#dc2626',
                cancelButtonColor: '#6b7280',
                reverseButtons: true
            }).then(function (result) {
                if (result.isConfirmed) {
                    sudahSelesai = true;
                    formBatal.submit();
                }
            });
        });

        // Meninggalkan halaman tanpa Simpan: hapus sesi di background.
        // sendBeacon hanya bisa POST, jadi method DELETE dikirim lewat field _method
        // (Laravel membacanya otomatis).
        window.addEventListener('pagehide', function () {
            if (sudahSelesai) {
                return;
            }

            const data = new FormData();
            data.append('_token', csrfToken);
            data.append('_method', 'DELETE');

            navigator.sendBeacon(urlHapus, data);
        });
    })();
</script>


@endsection

