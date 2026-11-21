@extends('layouts.app')

@section('title', 'Tambah Absen - Rekap Apel')

@section('content')

<!-- Step Indicator -->
<div class="flex items-center justify-between bg-white px-6 py-4 rounded-2xl border border-gray-100 shadow-sm">
    <div class="flex items-center gap-3">
        <span class="w-8 h-8 rounded-full bg-indigo-600 text-white font-bold text-sm flex items-center justify-center shadow-sm">1</span>
        <div>
            <p class="text-sm font-bold text-gray-800">Informasi Apel</p>
            <p class="text-xs text-gray-500">Pilih tanggal & jenis pelaksanaan apel</p>
        </div>
    </div>
    <div class="hidden sm:block h-px flex-1 bg-gray-200 mx-4"></div>
    <div class="flex items-center gap-3 opacity-50">
        <span class="w-8 h-8 rounded-full bg-gray-200 text-gray-600 font-bold text-sm flex items-center justify-center">2</span>
        <div>
            <p class="text-sm font-semibold text-gray-600">Daftar Hadir Pegawai</p>
            <p class="text-xs text-gray-400">Input status & keterangan kehadiran</p>
        </div>
    </div>
</div>

<!-- Form Card Langkah 1 -->
<div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="p-6 sm:p-8">
        <div class="mb-6">
            <h1 class="text-xl font-bold text-gray-800 tracking-tight">Buat Sesi Absen Apel</h1>
            <p class="text-sm text-gray-500 mt-1">Tentukan tanggal dan jenis apel terlebih dahulu sebelum mengisi daftar hadir pegawai.</p>
        </div>

        <form action="{{ route('absen.store') }}" method="POST" class="space-y-6">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Input Tanggal -->
                <div class="space-y-1.5">
                    <label for="tanggal" class="block text-xs font-semibold text-gray-700 uppercase tracking-wider">
                        Tanggal Apel <span class="text-rose-500">*</span>
                    </label>
                    <input type="date" 
                           name="tanggal" 
                           id="tanggal" 
                           value="{{ old('tanggal', date('Y-m-d')) }}"
                           class="w-full px-4 py-2.5 bg-gray-50 border @error('tanggal') border-rose-300 focus:ring-rose-500 focus:border-rose-500 @else border-gray-200 focus:ring-indigo-500 focus:border-indigo-500 @enderror text-sm rounded-xl focus:outline-none focus:ring-2 focus:bg-white text-gray-800 transition-all" 
                           required>
                    @error('tanggal')
                        <p class="text-xs text-rose-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Dropdown Jenis Apel (Foreach dari Enum) -->
                <div class="space-y-1.5">
                    <label for="jenis_apel" class="block text-xs font-semibold text-gray-700 uppercase tracking-wider">
                        Jenis Apel <span class="text-rose-500">*</span>
                    </label>
                    @php
                        // Mengambil daftar enum dari controller ($jenisApelEnum), dengan fallback sesuai migration
                        $daftarJenisApel =$jenisApelEnum ?? ['Apel Pagi', 'Apel Siang'];
                    @endphp
                    <select name="jenis_apel" 
                            id="jenis_apel"
                            class="w-full px-4 py-2.5 bg-gray-50 border @error('jenis_apel') border-rose-300 focus:ring-rose-500 focus:border-rose-500 @else border-gray-200 focus:ring-indigo-500 focus:border-indigo-500 @enderror text-sm rounded-xl focus:outline-none focus:ring-2 focus:bg-white text-gray-800 transition-all" 
                            required>
                        <option value="">-- Pilih Jenis Apel --</option>
                        @foreach($daftarJenisApel as $jenis)
                            <option value="{{ $jenis }}" {{ old('jenis_apel') == $jenis ? 'selected' : '' }}>
                                {{ $jenis }}
                            </option>
                        @endforeach
                    </select>
                    @error('jenis_apel')
                        <p class="text-xs text-rose-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Tombol Aksi -->
            <div class="pt-6 border-t border-gray-100 flex items-center justify-end gap-3">
                <a href="{{ route('absen.index') }}" 
                   class="px-5 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-medium rounded-xl transition-colors">
                    Batal
                </a>
                <button type="submit" 
                        class="inline-flex items-center gap-2 px-6 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold rounded-xl shadow-sm transition-all focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                    <span>Buat & Isi Daftar Hadir</span>
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
                    </svg>
                </button>
            </div>
        </form>
    </div>
</div>


@endsection