@extends('layouts.app')

@section('title', 'Detail Absen - Rekap Apel')

@section('content')
<div class="max-w-5xl mx-auto space-y-6">

    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-800 tracking-tight">Detail Absen</h1>
            <p class="text-sm text-gray-500 mt-0.5">Informasi rekap absen tanggal {{ \Carbon\Carbon::parse($rekap->tanggal)->format('d/m/Y') }}.</p>
        </div>
        <div class="flex items-center gap-2">
            <a href="{{ route('absen.edit', $rekap->id) }}" 
               class="inline-flex items-center gap-2 px-4 py-2 bg-amber-50 hover:bg-amber-100 text-amber-700 text-sm font-medium rounded-xl transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                </svg>
                Edit
            </a>
            <a href="{{ route('absen.index') }}" 
               class="inline-flex items-center space-x-2 px-4 py-2 bg-white border border-gray-200 text-gray-700 text-sm font-medium rounded-xl hover:bg-gray-50 transition shadow-sm">
                <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                <span>Kembali</span>
            </a>
        </div>
    </div>

    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 sm:p-8">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div>
                <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Tanggal</p>
                <p class="text-sm font-semibold text-gray-900">{{ \Carbon\Carbon::parse($rekap->tanggal)->format('d F Y') }}</p>
            </div>
            <div>
                <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Jenis Apel</p>
                <span class="inline-flex px-2.5 py-1 bg-indigo-50 text-indigo-700 text-xs font-medium rounded-md border border-indigo-100">
                    {{ $rekap->jenisApel->nama_jenis ?? '-' }}
                </span>
            </div>
            <div>
                <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">PJ / Petugas</p>
                <p class="text-sm font-semibold text-gray-900">{{ $rekap->pegawai->nama ?? '-' }}</p>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100">
            <h2 class="text-sm font-semibold text-gray-800 uppercase tracking-wider">Detail Kehadiran</h2>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-sm">
                <thead>
                    <tr class="bg-gray-50/70 border-b border-gray-100 text-xs font-semibold text-gray-500 uppercase tracking-wider">
                        <th class="py-3.5 px-6 w-16 text-center">No</th>
                        <th class="py-3.5 px-6">Pegawai</th>
                        <th class="py-3.5 px-6">Status</th>
                        <th class="py-3.5 px-6">Keterangan</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 text-gray-600">
                    @forelse($rekap->detailRekap as $index => $detail)
                        <tr class="hover:bg-indigo-50/30 transition-colors">
                            <td class="py-4 px-6 text-center font-medium text-gray-400">{{ $index + 1 }}</td>
                            <td class="py-4 px-6 font-medium text-gray-900">{{ $detail->pegawai->nama ?? '-' }}</td>
                            <td class="py-4 px-6">
                                @php
                                    $statusClasses = match($detail->status) {
                                        'Hadir' => 'bg-emerald-50 text-emerald-700 border-emerald-100',
                                        'Izin'  => 'bg-amber-50 text-amber-700 border-amber-100',
                                        'Sakit' => 'bg-sky-50 text-sky-700 border-sky-100',
                                        'Alpa'  => 'bg-rose-50 text-rose-700 border-rose-100',
                                        default => 'bg-gray-50 text-gray-700 border-gray-100',
                                    };
                                @endphp
                                <span class="px-2.5 py-1 text-xs font-medium rounded-md border {{ $statusClasses }}">{{ $detail->status }}</span>
                            </td>
                            <td class="py-4 px-6 text-gray-600">{{ $detail->keterangan ?: '-' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="py-10 text-center text-gray-400">
                                <p class="text-sm font-medium text-gray-500">Belum ada detail kehadiran.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection
