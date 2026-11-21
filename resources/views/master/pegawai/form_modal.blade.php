@php
    // Daftar field form, cukup diubah di sini jika ada kolom baru
    $fieldsPegawai = [
        'nama'    => ['label' => 'Nama Lengkap', 'type' => 'text'],
        'jabatan' => ['label' => 'Jabatan', 'type' => 'text'],
        'no_hp'   => ['label' => 'Nomor HP / WhatsApp', 'type' => 'text'],
        'email'   => ['label' => 'Alamat Email', 'type' => 'email'],
    ];
@endphp

<div id="modal-pegawai" class="fixed inset-0 z-50 hidden items-center justify-center bg-gray-900/40 p-4">
    <div class="w-full max-w-lg bg-white rounded-2xl shadow-xl border border-gray-100">

        <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100">
            <h2 id="modal-pegawai-title" class="text-lg font-bold text-gray-800">Tambah Pegawai</h2>
            <button type="button" onclick="closeModalPegawai()" class="text-gray-400 hover:text-gray-600 text-2xl leading-none">&times;</button>
        </div>

        <form id="form-pegawai" method="POST" action="{{ route('pegawai.store') }}" class="p-6 space-y-5">
            @csrf
            {{-- Diisi PUT saat edit, POST saat tambah --}}
            <input type="hidden" name="_method" id="form-pegawai-method" value="POST">
            {{-- Berisi id pegawai saat edit, kosong saat tambah --}}
            <input type="hidden" name="pegawai_id" id="form-pegawai-id" value="">

            @foreach($fieldsPegawai as $name => $field)
                <div>
                    <label for="form-pegawai-{{ $name }}" class="block text-sm font-semibold text-gray-700 mb-2">
                        {{ $field['label'] }} <span class="text-rose-500">*</span>
                    </label>
                    <input type="{{ $field['type'] }}" id="form-pegawai-{{ $name }}" name="{{ $name }}"
                           value="{{ old($name) }}" required
                           class="w-full px-4 py-2.5 rounded-xl border border-gray-200 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-600 transition @error($name) border-rose-500 @enderror">
                    @error($name)
                        <p class="text-rose-500 text-xs mt-1.5 font-medium">{{ $message }}</p>
                    @enderror
                </div>
            @endforeach

            <div class="flex items-center justify-end gap-3 pt-4 border-t border-gray-100">
                <button type="button" onclick="closeModalPegawai()"
                        class="px-5 py-2.5 bg-white border border-gray-200 text-gray-700 text-sm font-semibold rounded-xl hover:bg-gray-50 transition shadow-sm">
                    Batal
                </button>
                <button type="submit"
                        class="px-5 py-2.5 bg-indigo-600 text-white text-sm font-semibold rounded-xl hover:bg-indigo-700 transition shadow-sm">
                    Simpan
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    // URL untuk update, placeholder __ID__ diganti id pegawai saat dibuka
    const pegawaiStoreUrl = @json(route('pegawai.store'));
    const pegawaiUpdateUrl = @json(route('pegawai.update', '__ID__'));

    // Membuka modal. mode: 'create' atau 'edit'. data berisi nilai field.
    function openModalPegawai(mode, data = {}) {
        const isEdit = mode === 'edit';
        const form = document.getElementById('form-pegawai');

        document.getElementById('modal-pegawai-title').textContent = isEdit ? 'Edit Pegawai' : 'Tambah Pegawai';
        form.action = isEdit ? pegawaiUpdateUrl.replace('__ID__', data.id) : pegawaiStoreUrl;
        document.getElementById('form-pegawai-method').value = isEdit ? 'PUT' : 'POST';
        document.getElementById('form-pegawai-id').value = isEdit ? (data.id ?? '') : '';

        // Isi semua field dari data, kosong jika tidak ada
        @foreach(array_keys($fieldsPegawai) as $name)
            document.getElementById('form-pegawai-{{ $name }}').value = data['{{ $name }}'] ?? '';
        @endforeach

        const modal = document.getElementById('modal-pegawai');
        modal.classList.remove('hidden');
        modal.classList.add('flex');
    }

    // Menutup modal tanpa mengosongkan form
    function closeModalPegawai() {
        const modal = document.getElementById('modal-pegawai');
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    }

    // Jika simpan gagal validasi, modal dibuka lagi dengan isian lama dan pesan error
   @if($errors->any())
    @php
        $oldMode = old('pegawai_id') ? 'edit' : 'create';
        $oldPegawai = [
            'id'      => old('pegawai_id'),
            'nama'    => old('nama'),
            'jabatan' => old('jabatan'),
            'no_hp'   => old('no_hp'),
            'email'   => old('email'),
        ];
    @endphp
    document.addEventListener('DOMContentLoaded', () => {
        openModalPegawai(@json($oldMode), @json($oldPegawai));
    });
@endif
</script>
