@extends('layouts.app')

@section('title', 'Data Absen - Rekap Apel')

@section('content')

<!-- Page Header & Action Bar -->
<div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2">
    <div>
        <h1 class="text-xl font-bold text-gray-800 tracking-tight mb-3">Data Absen Apel</h1>
    </div>
   <div x-data="{ open: {{ $errors->any() ? 'true' : 'false' }} }">
        <a href="{{ route('absen.create') }}" 
           class="inline-flex items-center gap-2 px-4 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium rounded-lg shadow-sm transition-all duration-150 mb-3 ease-in-out focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
            </svg>
            Tambah Absen
        </a>
 

        @php
            // Default rentang: Senin sampai Sabtu minggu berjalan
            $defaultAwal  = now()->startOfWeek(\Carbon\Carbon::MONDAY)->toDateString();
            $defaultAkhir = now()->startOfWeek(\Carbon\Carbon::MONDAY)->addDays(5)->toDateString();
        @endphp

{{-- Modal ekspor rekap. Terbuka otomatis jika validasi gagal --}}


    <button type="button"
            @click="open = true"
            class="inline-flex items-center gap-2 px-4 py-2.5 bg-white border border-gray-200 hover:bg-gray-50 text-gray-700 text-sm font-medium rounded-lg transition-colors border-purple-500">
        Ekspor Rekap
    </button>

    {{-- Latar belakang modal --}}
    <div x-show="open"
         x-transition.opacity
         x-cloak
         @keydown.escape.window="open = false"
         @click.self="open = false"
         class="fixed inset-0 z-50 flex items-center justify-center bg-gray-900/50 px-4">

        {{-- Kotak modal --}}
        <div x-show="open"
             x-transition
             class="w-full max-w-md bg-white rounded-2xl shadow-xl border border-gray-100 p-6">

            <div class="flex items-center justify-between mb-5">
                <h3 class="text-base font-bold text-gray-800">Ekspor Rekap Apel</h3>
                <button type="button" @click="open = false" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>

            <form method="GET" class="space-y-4">
                <div>
                    <label for="tanggal_awal" class="block text-sm font-medium text-gray-700 mb-1">Tanggal Awal</label>
                    <input type="date"
                           id="tanggal_awal"
                           name="tanggal_awal"
                           value="{{ old('tanggal_awal', $defaultAwal) }}"
                           required
                           class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    @error('tanggal_awal')
                        <p class="text-xs text-rose-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="tanggal_akhir" class="block text-sm font-medium text-gray-700 mb-1">Tanggal Akhir</label>
                    <input type="date"
                           id="tanggal_akhir"
                           name="tanggal_akhir"
                           value="{{ old('tanggal_akhir', $defaultAkhir) }}"
                           required
                           class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    @error('tanggal_akhir')
                        <p class="text-xs text-rose-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex items-center justify-end gap-2 pt-2">
                    <button type="button"
                            @click="open = false"
                            class="px-4 py-2.5 bg-white border border-gray-200 hover:bg-gray-50 text-gray-700 text-sm font-medium rounded-lg transition-colors">
                        Batal
                    </button>
                    <button type="submit"
                            formaction="{{ route('absen.export.pdf') }}"
                            class="px-4 py-2.5 bg-white border border-gray-200 hover:bg-gray-50 text-gray-700 text-sm font-medium rounded-lg transition-colors">
                        Export PDF
                    </button>
                    <button type="submit"
                            formaction="{{ route('absen.export.excel') }}"
                            class="px-4 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-medium rounded-lg transition-colors">
                        Export Excel
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
   
</div>

<!-- Data Table -->
<div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-gray-50/70 border-b border-gray-100 text-xs font-semibold text-gray-500 uppercase tracking-wider">
                    <th class="py-3.5 px-6 w-16 text-center">No</th>
                    <th class="py-3.5 px-6">Tanggal</th>
                    <th class="py-3.5 px-6">Jenis Apel</th>
                   
                   
                    <th class="py-3.5 px-6 text-center w-40">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 text-sm text-gray-600">
                @forelse ($rekap as $index => $item)
                    <tr class="hover:bg-indigo-50/30 transition-colors duration-150">
                        <td class="py-4 px-6 text-center font-medium text-gray-400">
                            {{ method_exists($rekap, 'firstItem') ? $rekap->firstItem() + $index : $index + 1 }}
                        </td>
                        <td class="py-4 px-6 font-semibold text-gray-900 whitespace-nowrap">
                            {{ \Carbon\Carbon::parse($item->tanggal)->format('d/m/Y') }}
                        </td>
                        <td class="py-4 px-6 whitespace-nowrap">
                            <span class="px-2.5 py-1 bg-indigo-50 text-indigo-700 text-xs font-medium rounded-md border border-indigo-100">
                                {{ $item->jenis_apel ?? '-' }}
                            </span>
                        </td>
                       
                        
                        <td class="py-4 px-6 text-center whitespace-nowrap">
                            <div class="flex items-center justify-center gap-2">
                                <a href="{{ route('absen.show', $item->id) }}" 
                                   title="Lihat Detail"
                                   class="p-1.5 text-indigo-600 hover:text-indigo-700 hover:bg-indigo-50 rounded-lg transition-colors">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                    </svg>
                                </a>
                                <a href="{{ route('absen.edit', $item->id) }}" 
                                   title="Edit Data"
                                   class="p-1.5 text-amber-600 hover:text-amber-700 hover:bg-amber-50 rounded-lg transition-colors">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                    </svg>
                                </a>
                                <button type="button" 
                                        onclick='handleDelete(@json(route("absen.destroy", $item->id)), @json(\Carbon\Carbon::parse($item->tanggal)->format("d/m/Y")))'
                                        title="Hapus Data"
                                        class="p-1.5 text-rose-600 hover:text-rose-700 hover:bg-rose-50 rounded-lg transition-colors">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                    </svg>
                                </button>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="py-12 text-center text-gray-400">
                            <svg class="w-12 h-12 mx-auto text-gray-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path>
                            </svg>
                            <p class="text-base font-medium text-gray-500">Belum Ada Data Absen</p>
                            <p class="text-xs text-gray-400 mt-1">Klik tombol 'Tambah Absen' untuk menambahkan data baru.</p>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if(method_exists($rekap, 'links') && $rekap->hasPages())
        <div class="px-6 py-4 bg-gray-50/50 border-t border-gray-100">
            {{ $rekap->links() }}
        </div>
    @endif
</div>

<form id="global-delete-form" method="POST" class="hidden">
    @csrf
    @method('DELETE')
</form>

<script>
    function handleDelete(url, label) {
        if (typeof Swal !== 'undefined') {
            Swal.fire({
                title: 'Hapus Data Absen?',
                text: `Apakah Anda yakin ingin menghapus data absen tanggal "${label}"?`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#e11d48',
                cancelButtonColor: '#6b7280',
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal',
                customClass: {
                    popup: 'rounded-2xl',
                    confirmButton: 'px-4 py-2 rounded-lg text-sm font-medium',
                    cancelButton: 'px-4 py-2 rounded-lg text-sm font-medium'
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    executeDelete(url);
                }
            });
        } else {
            if (confirm(`Apakah Anda yakin ingin menghapus data absen tanggal "${label}"?`)) {
                executeDelete(url);
            }
        }
    }

    function executeDelete(url) {
        const form = document.getElementById('global-delete-form');
        if (form) {
            form.action = url;
            form.submit();
        }
    }
</script>

@endsection
