@extends('layouts.app')

@section('title', 'Edit Data Pegawai')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">

    <!-- Header & Tombol Kembali -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-800 tracking-tight">Edit Data Pegawai</h1>
            <p class="text-sm text-gray-500 mt-0.5">Perbarui informasi data pegawai "{{ $pegawai->nama }}".</p>
        </div>
        <a href="{{ route('pegawai.index') }}" 
           class="inline-flex items-center space-x-2 px-4 py-2 bg-white border border-gray-200 text-gray-700 text-sm font-medium rounded-xl hover:bg-gray-50 transition shadow-sm">
            <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
            <span>Kembali</span>
        </a>
    </div>

    <!-- Form Card Container -->
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 sm:p-8">
        <form id="form-edit-pegawai" action="{{ route('pegawai.update', $pegawai->id) }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                <!-- Input Nama Lengkap -->
                <div>
                    <label for="nama" class="block text-sm font-semibold text-gray-700 mb-2">
                        Nama Lengkap <span class="text-rose-500">*</span>
                    </label>
                    <input type="text" 
                           id="nama" 
                           name="nama" 
                           value="{{ old('nama', $pegawai->nama) }}" 
                           placeholder="Masukkan nama lengkap"
                           class="w-full px-4 py-2.5 rounded-xl border border-gray-200 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-600 transition @error('nama') border-rose-500 @enderror" 
                           required>
                    @error('nama')
                        <p class="text-rose-500 text-xs mt-1.5 font-medium flex items-center gap-1">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                <!-- Input Jabatan -->
                <div>
                    <label for="jabatan" class="block text-sm font-semibold text-gray-700 mb-2">
                        Jabatan <span class="text-rose-500">*</span>
                    </label>
                    <input type="text" 
                           id="jabatan" 
                           name="jabatan" 
                           value="{{ old('jabatan', $pegawai->jabatan) }}" 
                           placeholder="Contoh: Manager Operasional"
                           class="w-full px-4 py-2.5 rounded-xl border border-gray-200 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-600 transition @error('jabatan') border-rose-500 @enderror" 
                           required>
                    @error('jabatan')
                        <p class="text-rose-500 text-xs mt-1.5 font-medium flex items-center gap-1">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                <!-- Input Nomor HP -->
                <div>
                    <label for="no_hp" class="block text-sm font-semibold text-gray-700 mb-2">
                        Nomor HP / WhatsApp <span class="text-rose-500">*</span>
                    </label>
                    <input type="text" 
                           id="no_hp" 
                           name="no_hp" 
                           value="{{ old('no_hp', $pegawai->no_hp) }}" 
                           placeholder="Contoh: 081234567890"
                           class="w-full px-4 py-2.5 rounded-xl border border-gray-200 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-600 transition @error('no_hp') border-rose-500 @enderror" 
                           required>
                    @error('no_hp')
                        <p class="text-rose-500 text-xs mt-1.5 font-medium flex items-center gap-1">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                <!-- Input Email -->
                <div>
                    <label for="email" class="block text-sm font-semibold text-gray-700 mb-2">
                        Alamat Email <span class="text-rose-500">*</span>
                    </label>
                    <input type="email" 
                           id="email" 
                           name="email" 
                           value="{{ old('email', $pegawai->email) }}" 
                           placeholder="contoh@email.com"
                           class="w-full px-4 py-2.5 rounded-xl border border-gray-200 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-600 transition @error('email') border-rose-500 @enderror" 
                           required>
                    @error('email')
                        <p class="text-rose-500 text-xs mt-1.5 font-medium flex items-center gap-1">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            {{ $message }}
                        </p>
                    @enderror
                </div>

            </div>

            <!-- Action Buttons -->
            <div class="flex items-center justify-end space-x-3 pt-6 border-t border-gray-100">
                <a href="{{ route('pegawai.index') }}" 
                   class="px-5 py-2.5 bg-white border border-gray-200 text-gray-700 text-sm font-semibold rounded-xl hover:bg-gray-50 transition shadow-sm">
                    Batal
                </a>
                <button type="button" 
                        onclick="confirmUpdate()"
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

<!-- Optional Script Konfirmasi Update via SweetAlert2 -->
<script>
    function confirmUpdate() {
        if (typeof Swal !== 'undefined') {
            Swal.fire({
                title: 'Simpan Perubahan?',
                text: "Apakah Anda yakin ingin memperbarui data pegawai ini?",
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#4f46e5', // indigo-600
                cancelButtonColor: '#6b7280',  // gray-500
                confirmButtonText: 'Ya, Simpan!',
                cancelButtonText: 'Batal',
                customClass: {
                    popup: 'rounded-2xl',
                    confirmButton: 'px-4 py-2 rounded-lg text-sm font-medium',
                    cancelButton: 'px-4 py-2 rounded-lg text-sm font-medium'
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('form-edit-pegawai').submit();
                }
            });
        } else {
            // Fallback submit biasa jika SweetAlert2 tidak aktif
            document.getElementById('form-edit-pegawai').submit();
        }
    }
</script>
@endsection