@extends('layouts.app')

@section('title', 'Master Data Pegawai - Rekap Apel')

@section('content')

<!-- Page Header & Action Bar -->
<div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
    <div>
        <h1 class="text-xl font-bold text-gray-800 tracking-tight mb-3">Master Data Pegawai</h1>
    </div>
    <div>
        <button type="button" onclick="openModalPegawai('create')"
        class="inline-flex items-center gap-2 px-4 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium rounded-lg shadow-sm transition-all duration-150 mb-3 ease-in-out focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
            </svg>
            Tambah Pegawai
        </button>
    </div>
</div>







<!-- Data Table -->
<div class="overflow-x-auto">
    <table class="w-full text-left border-collapse">
        <thead>
            <tr class="bg-gray-50/70 border-b border-gray-100 text-xs font-semibold text-gray-500 uppercase tracking-wider">
                <th class="py-3.5 px-6 w-16 text-center">No</th>
                <th class="py-3.5 px-6">Nama Pegawai</th>
                <th class="py-3.5 px-6">Jabatan</th>
                <th class="py-3.5 px-6">No HP</th>
                <th class="py-3.5 px-6">Email</th>
                <th class="py-3.5 px-6 text-center w-36">Aksi</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100 text-sm text-gray-600">
            @forelse ($pegawai as $index =>$item)
                <tr class="hover:bg-indigo-50/30 transition-colors duration-150">
                    <td class="py-4 px-6 text-center font-medium text-gray-400">
                        {{ method_exists($pegawai, 'firstItem') ?$pegawai->firstItem() + $index :$index + 1 }}
                    </td>
                    <td class="py-4 px-6 font-semibold text-gray-900 whitespace-nowrap">
                        <div class="flex items-center gap-3">
                            
                            <span>{{ $item->nama }}</span>
                        </div>
                    </td>
                    <td class="py-4 px-6 whitespace-nowrap">
                        <span class="px-2.5 py-1 bg-indigo-50 text-indigo-700 text-xs font-medium rounded-md border border-indigo-100">
                            {{ $item->jabatan }}
                        </span>
                    </td>
                    <td class="py-4 px-6 whitespace-nowrap text-gray-600">
                        {{ $item->no_hp }}
                    </td>
                    <td class="py-4 px-6 whitespace-nowrap text-gray-600">
                        {{ $item->email }}
                    </td>
                    <td class="py-4 px-6 text-center whitespace-nowrap">
                        <div class="flex items-center justify-center gap-2">
                            <!-- Edit Button -->
                           <button type="button"
                                    onclick="openModalPegawai('edit', this.dataset)"
                                    data-id="{{ $item->id }}"
                                    data-nama="{{ $item->nama }}"
                                    data-jabatan="{{ $item->jabatan }}"
                                    data-no_hp="{{ $item->no_hp }}"
                                    data-email="{{ $item->email }}"
                                    title="Edit Data"
                                    class="p-1.5 text-amber-600 hover:text-amber-700 hover:bg-amber-50 rounded-lg transition-colors">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                </svg>
                            </button>

                            <!-- Delete Button (Triggers SweetAlert2) -->
                            <button type="button" 
                                    onclick="handleDelete('{{ route('pegawai.destroy', $item->id) }}', '{{ addslashes($item->nama) }}')"
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
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        </svg>
                        <p class="text-base font-medium text-gray-500">Belum Ada Data Pegawai</p>
                        <p class="text-xs text-gray-400 mt-1">Klik tombol 'Tambah Pegawai' untuk menambahkan data baru.</p>
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

<!-- Pagination -->
@if(method_exists($pegawai, 'links') &&$pegawai->hasPages())
    <div class="px-6 py-4 bg-gray-50/50 border-t border-gray-100">
        {{ $pegawai->links() }}
    </div>
@endif

@include('master.pegawai.form_modal')

<!-- Form Tersembunyi untuk Proses Delete -->
<form id="global-delete-form" method="POST" class="hidden">
    @csrf
    @method('DELETE')
</form>

<script>
    function handleDelete(url, name) {
        // Cek apakah library SweetAlert2 (Swal) sudah terbaca oleh browser
        if (typeof Swal !== 'undefined') {
            Swal.fire({
                title: 'Hapus Data Pegawai?',
                text: `Apakah Anda yakin ingin menghapus data "${name}"?`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#e11d48', // Merah
                cancelButtonColor: '#6b7280',  // Abu-abu
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
            // Fallback bawaan browser jika SweetAlert2 belum ter-load
            if (confirm(`Apakah Anda yakin ingin menghapus "${name}"?`)) {
                executeDelete(url);
            }
        }
    }

    function executeDelete(url) {
        const form = document.getElementById('global-delete-form');
        if (form) {
            form.action = url;
            form.submit();
        } else {
            console.error('Form dengan ID #global-delete-form tidak ditemukan!');
        }
    }

    function confirmDeleteData(button) {
    const url = button.getAttribute('data-url');
    const nama = button.getAttribute('data-nama');
    
    Swal.fire({
        title: 'Hapus Data Pegawai?',
        text: `Apakah Anda yakin ingin menghapus data "${nama}"?`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#e11d48',
        cancelButtonColor: '#6b7280',
        confirmButtonText: 'Ya, Hapus!',
        cancelButtonText: 'Batal'
    }).then((result) => {
        if (result.isConfirmed) {
            const form = document.getElementById('global-delete-form');
            form.action = url;
            form.submit();
        }
    });
}
</script>





@endsection