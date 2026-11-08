@extends('layouts.app')

@section('title', 'Tambah Pegawai - Rekap Apel')

@section('content')

    <div>
        <a href="{{ route('pegawai.index') }}" 
           class="inline-flex items-center gap-2 px-4 py-2 bg-white border border-gray-200 hover:bg-gray-50 text-gray-700 text-sm font-medium rounded-lg shadow-sm transition-all duration-150">
            <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
            Kembali
        </a>
    </div>


<!-- Form Card -->
<div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="p-6 sm:p-8">
        <form action="{{ route('pegawai.store') }}" method="POST" class="space-y-6">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Nama Lengkap -->
                <div class="space-y-1.5 md:col-span-2">
                    <label for="nama" class="block text-xs font-semibold text-gray-700 uppercase tracking-wider">
                        Nama Lengkap <span class="text-rose-500">*</span>
                    </label>
                    <div class="relative">
                        <input type="text" 
                               name="nama" 
                               id="nama" 
                               value="{{ old('nama') }}" 
                               placeholder="Contoh: Ahmad Subagja, S.Kom."
                               class="w-full px-4 py-2.5 bg-gray-50 border @error('nama') border-rose-300 focus:ring-rose-500 focus:border-rose-500 @else border-gray-200 focus:ring-indigo-500 focus:border-indigo-500 @enderror text-sm rounded-lg focus:outline-none focus:ring-2 focus:bg-white text-gray-800 placeholder-gray-400 transition-all"
                               required>
                    </div>
                    @error('nama')
                        <p class="text-xs text-rose-600 flex items-center gap-1 mt-1">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                <!-- Jabatan -->
                <div class="space-y-1.5 md:col-span-2">
                    <label for="jabatan" class="block text-xs font-semibold text-gray-700 uppercase tracking-wider">
                        Jabatan <span class="text-rose-500">*</span>
                    </label>
                    <input type="text" 
                           name="jabatan" 
                           id="jabatan" 
                           value="{{ old('jabatan') }}" 
                           placeholder="Contoh: Staf Admin / Analis Kepegawaian"
                           class="w-full px-4 py-2.5 bg-gray-50 border @error('jabatan') border-rose-300 focus:ring-rose-500 focus:border-rose-500 @else border-gray-200 focus:ring-indigo-500 focus:border-indigo-500 @enderror text-sm rounded-lg focus:outline-none focus:ring-2 focus:bg-white text-gray-800 placeholder-gray-400 transition-all"
                           required>
                    @error('jabatan')
                        <p class="text-xs text-rose-600 flex items-center gap-1 mt-1">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                <!-- No HP -->
                <div class="space-y-1.5">
                    <label for="no_hp" class="block text-xs font-semibold text-gray-700 uppercase tracking-wider">
                        No. Telepon / WhatsApp <span class="text-rose-500">*</span>
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-gray-400 text-xs font-medium">
                            +62 /
                        </div>
                        <input type="number" 
                               name="no_hp" 
                               id="no_hp" 
                               value="{{ old('no_hp') }}" 
                               placeholder="81234567890"
                               class="w-full pl-14 pr-4 py-2.5 bg-gray-50 border @error('no_hp') border-rose-300 focus:ring-rose-500 focus:border-rose-500 @else border-gray-200 focus:ring-indigo-500 focus:border-indigo-500 @enderror text-sm rounded-lg focus:outline-none focus:ring-2 focus:bg-white text-gray-800 placeholder-gray-400 transition-all"
                               required>
                    </div>
                    @error('no_hp')
                        <p class="text-xs text-rose-600 flex items-center gap-1 mt-1">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                <!-- Email -->
                <div class="space-y-1.5">
                    <label for="email" class="block text-xs font-semibold text-gray-700 uppercase tracking-wider">
                        Alamat Email <span class="text-rose-500">*</span>
                    </label>
                    <input type="email" 
                           name="email" 
                           id="email" 
                           value="{{ old('email') }}" 
                           placeholder="pegawai@instansi.go.id"
                           class="w-full px-4 py-2.5 bg-gray-50 border @error('email') border-rose-300 focus:ring-rose-500 focus:border-rose-500 @else border-gray-200 focus:ring-indigo-500 focus:border-indigo-500 @enderror text-sm rounded-lg focus:outline-none focus:ring-2 focus:bg-white text-gray-800 placeholder-gray-400 transition-all"
                           required>
                    @error('email')
                        <p class="text-xs text-rose-600 flex items-center gap-1 mt-1">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            {{ $message }}
                        </p>
                    @enderror
                </div>
            </div>

            <!-- Form Action Buttons -->
            <div class="pt-6 border-t border-gray-100 flex items-center justify-end gap-3">
                <a href="{{ route('pegawai.index') }}" 
                   class="px-5 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-medium rounded-lg transition-colors">
                    Batal
                </a>
                <button type="submit" 
                        class="inline-flex items-center gap-2 px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium rounded-lg shadow-sm transition-all focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    Simpan Data
                </button>
            </div>
        </form>
    </div>
</div>
@endsection