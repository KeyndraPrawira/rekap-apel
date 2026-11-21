@extends('layouts.app')

@section('title', 'Edit Data Absen')

@section('content')
<div class="max-w-5xl mx-auto space-y-6">

    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-800 tracking-tight">Edit Data Absen</h1>
            <p class="text-sm text-gray-500 mt-0.5">Perbarui data rekap absen tanggal {{ \Carbon\Carbon::parse($rekap->tanggal)->format('d/m/Y') }}.</p>
        </div>
        <a href="{{ route('absen.index') }}" 
           class="inline-flex items-center space-x-2 px-4 py-2 bg-white border border-gray-200 text-gray-700 text-sm font-medium rounded-xl hover:bg-gray-50 transition shadow-sm">
            <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
            <span>Kembali</span>
        </a>
    </div>

    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 sm:p-8">
        @if ($errors->any())
    <div class="rounded-xl bg-rose-50 border border-rose-200 p-4 text-sm text-rose-700">
        <ul class="list-disc pl-5 space-y-1">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
        <form id="form-edit-absen" action="{{ route('absen.update', $rekap->id) }}" method="POST" class="space-y-8">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div>
                    <label for="tanggal" class="block text-sm font-semibold text-gray-700 mb-2">
                        Tanggal <span class="text-rose-500">*</span>
                    </label>
                    <input type="date" id="tanggal" name="tanggal" value="{{ old('tanggal', $rekap->tanggal) }}"
                           class="w-full px-4 py-2.5 rounded-xl border border-gray-200 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-600 transition @error('tanggal') border-rose-500 @enderror" required>
                    @error('tanggal')
                        <p class="text-rose-500 text-xs mt-1.5 font-medium">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="jenis_apel" class="block text-sm font-semibold text-gray-700 mb-2">
                        Jenis Apel <span class="text-rose-500">*</span>
                    </label>
                <select id="jenis_apel" name="jenis_apel"
                    class="w-full px-4 py-2.5 rounded-xl border border-gray-200 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-600 transition @error('jenis_apel') border-rose-500 @enderror" required>
                    <option value="" disabled>-- Pilih Jenis Apel --</option>
                    @foreach($jenisApel as $jenis)
                        <option value="{{ $jenis }}" {{ old('jenis_apel', $rekap->jenis_apel) == $jenis ? 'selected' : '' }}>
                            {{ $jenis }}
                        </option>
                    @endforeach
                </select>
                    @error('jenis_apel')
                        <p class="text-rose-500 text-xs mt-1.5 font-medium">{{ $message }}</p>
                    @enderror
                </div>

              
            </div>

            <div class="border-t border-gray-100 pt-6">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-sm font-semibold text-gray-800 uppercase tracking-wider">Detail Kehadiran</h2>
                </div>

                <div class="overflow-x-auto border border-gray-100 rounded-xl">
                    <table class="w-full text-left border-collapse text-sm">
                        <thead>
                            <tr class="bg-gray-50/70 border-b border-gray-100 text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                <th class="py-3 px-4">Pegawai</th>
                                <th class="py-3 px-4 w-40">Status</th>
                                <th class="py-3 px-4">Keterangan</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @php
                                $details = $rekap->detailRekap->keyBy('pegawai_id');
                            @endphp

                            @foreach($pegawai as $p)
                                @php
                                    $existing = $details->get($p->id);
                                    $currentStatus = old("detail.{$p->id}.status", $existing->status ?? 'Hadir');
                                    $currentKeterangan = old("detail.{$p->id}.keterangan", $existing->keterangan ?? '');
                                @endphp
                                <tr>
                                    <td class="py-3 px-4 font-medium text-gray-700">
                                        {{ $p->nama }}
                                        <input type="hidden" name="detail[{{ $p->id }}][pegawai_id]" value="{{ $p->id }}">
                                    </td>
                                    <td class="py-3 px-4">
                                        <select name="detail[{{ $p->id }}][status]" class="w-full px-3 py-2 bg-gray-50 border border-gray-200 text-sm rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" required>
                                            @foreach($statusOptions as $st)
                                                <option value="{{ $st }}" {{ $currentStatus == $st ? 'selected' : '' }}>{{ $st }}</option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td class="py-3 px-4">
                                        <input type="text" name="detail[{{ $p->id }}][keterangan]" value="{{ $currentKeterangan }}" placeholder="Keterangan (opsional)"
                                               class="w-full px-3 py-2 bg-gray-50 border border-gray-200 text-sm rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <p class="text-xs text-gray-400 mt-2">Menyimpan akan menggantikan detail kehadiran yang ada dengan data di atas.</p>
            </div>

            <div class="flex items-center justify-end space-x-3 pt-6 border-t border-gray-100">
                <a href="{{ route('absen.index') }}" 
                   class="px-5 py-2.5 bg-white border border-gray-200 text-gray-700 text-sm font-semibold rounded-xl hover:bg-gray-50 transition shadow-sm">
                    Batal
                </a>
                <button type="button" onclick="confirmUpdate()"
                        class="inline-flex items-center space-x-2 px-5 py-2.5 bg-indigo-600 text-white text-sm font-semibold rounded-xl hover:bg-indigo-700 focus:ring-4 focus:ring-indigo-100 transition shadow-sm">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    <span>Simpan Perubahan</span>
                </button>
            </div>
        </form>
    </div>

</div>



<script>
    function confirmUpdate() {
        if (typeof Swal !== 'undefined') {
            Swal.fire({
                title: 'Simpan Perubahan?',
                text: "Apakah Anda yakin ingin memperbarui data absen ini?",
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#4f46e5',
                cancelButtonColor: '#6b7280',
                confirmButtonText: 'Ya, Simpan!',
                cancelButtonText: 'Batal',
                customClass: {
                    popup: 'rounded-2xl',
                    confirmButton: 'px-4 py-2 rounded-lg text-sm font-medium',
                    cancelButton: 'px-4 py-2 rounded-lg text-sm font-medium'
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('form-edit-absen').submit();
                }
            });
        } else {
            document.getElementById('form-edit-absen').submit();
        }
    }
</script>
@endsection
