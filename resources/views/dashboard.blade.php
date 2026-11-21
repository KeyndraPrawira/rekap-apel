@extends('layouts.app')

@section('content')

    {{-- Form filter periode tanggal; kosong berarti seluruh data --}}
    <form method="GET" action="{{ url()->current() }}"
        class="bg-white rounded-xl border border-gray-100 p-5 shadow-sm mb-6 flex flex-wrap items-end gap-4">
        <div>
            <label for="mulai" class="block text-sm font-medium text-gray-600 mb-1">Dari Tanggal</label>
            <input type="date" id="mulai" name="mulai" value="{{ $mulai }}"
                class="border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500">
        </div>
        <div>
            <label for="selesai" class="block text-sm font-medium text-gray-600 mb-1">Sampai Tanggal</label>
            <input type="date" id="selesai" name="selesai" value="{{ $selesai }}"
                class="border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500">
        </div>
        <button type="submit"
            class="bg-emerald-500 hover:bg-emerald-600 text-white text-sm font-semibold px-4 py-2 rounded-lg">
            Terapkan
        </button>
        <a href="{{ url()->current() }}"
            class="border border-gray-200 hover:bg-gray-50 text-gray-600 text-sm font-semibold px-4 py-2 rounded-lg">
            Reset
        </a>
    </form>

    {{-- Ringkasan periode: jumlah pegawai dan rentang tanggal --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
        <div class="bg-white rounded-xl border border-gray-100 p-5 shadow-sm">
            <p class="text-sm font-medium text-gray-500">Jumlah Pegawai</p>
            <h2 class="text-2xl font-bold text-gray-800 mt-2">{{ $jumlahPegawai }} orang</h2>
        </div>
        <div class="bg-white rounded-xl border border-gray-100 p-5 shadow-sm">
            <p class="text-sm font-medium text-gray-500">Periode</p>
            <h2 class="text-lg font-bold text-gray-800 mt-2">
                @if ($mulai && $selesai)
                    {{ \Carbon\Carbon::parse($mulai)->translatedFormat('d M Y') }}
                    s/d
                    {{ \Carbon\Carbon::parse($selesai)->translatedFormat('d M Y') }}
                @else
                    Seluruh data
                @endif
            </h2>
        </div>
    </div>

    {{-- Grafik tren kehadiran harian --}}
    <div class="bg-white rounded-xl border border-gray-100 p-6 shadow-sm mb-6">
        <h3 class="text-base font-bold text-gray-800 mb-4">Tren Kehadiran (%) per Tanggal</h3>
        @if (count($labels) > 0)
            <canvas id="trenChart" height="100"></canvas>
        @else
            <p class="py-10 text-center text-gray-400">Tidak ada data apel pada periode ini.</p>
        @endif
    </div>

    @if (count($labels) > 0)
        <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
        <script>
            // Grafik garis tren kehadiran apel pagi dan siang
            new Chart(document.getElementById('trenChart'), {
                type: 'line',
                data: {
                    labels: @json($labels),
                    datasets: [
                        {
                            label: 'Apel Pagi',
                            data: @json($dataPagi),
                            borderColor: '#10b981',
                            tension: 0.3,
                            spanGaps: true,
                        },
                        {
                            label: 'Apel Siang',
                            data: @json($dataSiang),
                            borderColor: '#6366f1',
                            tension: 0.3,
                            spanGaps: true,
                        },
                    ],
                },
                options: {
                    responsive: true,
                    scales: {
                        y: { min: 0, max: 100, ticks: { callback: v => v + '%' } },
                    },
                },
            });
        </script>
    @endif

@endsection