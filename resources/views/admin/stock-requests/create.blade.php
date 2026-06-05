@extends('layouts.admin')

@section('content')
<div class="max-w-2xl mx-auto">

    <div class="flex items-center gap-4 mb-6">
        <a href="{{ route('admin.stock-requests.index') }}"
           class="w-10 h-10 bg-white rounded-xl flex items-center justify-center shadow-soft hover:shadow-hover smooth-transition text-gray-500 hover:text-elco-coffee">
            <i class="ph ph-arrow-left"></i>
        </a>
        <div>
            <h2 class="text-xl font-display font-bold text-gray-800">Buat Pengajuan</h2>
            <p class="text-sm text-gray-500">Pengajuan akan dikirim ke Manager Pusat</p>
        </div>
    </div>

    <div class="bg-white rounded-3xl shadow-soft p-8">
        <form action="{{ route('admin.stock-requests.store') }}" method="POST" class="space-y-6">
            @csrf

            <!-- {{-- Tipe Pengajuan --}}
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-3">
                    Tipe Pengajuan <span class="text-red-500">*</span>
                </label>
                <div class="grid grid-cols-2 gap-3">
                    <label class="cursor-pointer">
                        <input type="radio" name="type" value="stock"
                               class="sr-only peer" {{ old('type', 'stock') === 'stock' ? 'checked' : '' }}
                               onchange="handleTypeChange()">
                        <div class="p-4 border-2 border-gray-200 rounded-2xl peer-checked:border-elco-coffee peer-checked:bg-elco-cream smooth-transition text-center">
                            <i class="ph-fill ph-package text-2xl text-gray-400 block mb-2"></i>
                            <p class="text-sm font-semibold text-gray-700">Pengajuan Stok</p>
                            <p class="text-xs text-gray-400 mt-1">Bahan baku & produk jadi</p>
                        </div>
                    </label>
                    <label class="cursor-pointer">
                        <input type="radio" name="type" value="operational"
                               class="sr-only peer" {{ old('type') === 'operational' ? 'checked' : '' }}
                               onchange="handleTypeChange()">
                        <div class="p-4 border-2 border-gray-200 rounded-2xl peer-checked:border-elco-coffee peer-checked:bg-elco-cream smooth-transition text-center">
                            <i class="ph-fill ph-wrench text-2xl text-gray-400 block mb-2"></i>
                            <p class="text-sm font-semibold text-gray-700">Alat Operasional</p>
                            <p class="text-xs text-gray-400 mt-1">Peralatan & perlengkapan</p>
                        </div>
                    </label>
                </div>
                @error('type')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div> -->

            {{-- ══ SECTION STOK: Multi-item ══ --}}
            <div id="sectionStok" class="space-y-4">
                <div class="flex items-center justify-between">
                    <label class="text-sm font-semibold text-gray-700">Daftar Item yang Diajukan *</label>
                    <button type="button" onclick="addStokRow()"
                        class="text-xs font-medium text-elco-coffee bg-elco-cream px-3 py-2 rounded-xl hover:bg-elco-latte/30 smooth-transition">
                        <i class="ph ph-plus"></i> Tambah Item
                    </button>
                </div>

                <div id="stokRows" class="space-y-3">
                    {{-- Baris pertama --}}
                    <div class="stok-row grid grid-cols-[auto_1fr_auto_auto_auto] gap-2 items-center bg-gray-50 p-3 rounded-2xl">
                        <select name="items[0][tipe]" onchange="handleTipeChange(this)"
                            class="px-3 py-2 rounded-xl border border-gray-200 text-sm bg-white focus:outline-none w-36">
                            <option value="bahan_baku">☕ Bahan Baku</option>
                            <option value="produk_jadi">🍰 Produk Jadi</option>
                            <option value="operasional">🔧 Operasional</option>
                        </select>
                        <div class="item-name-wrap">
                            <select name="items[0][item_name]" class="item-select w-full px-3 py-2 rounded-xl border border-gray-200 text-sm bg-white focus:outline-none">
                                <option value="">— Pilih Bahan —</option>
                                @foreach($ingredients as $ing)
                                <option value="{{ $ing->nama_bahan }}" data-satuan="{{ $ing->satuan }}">
                                    {{ $ing->nama_bahan }}
                                </option>
                                @endforeach
                            </select>
                            <input type="text" name="items[0][item_name_ops]" placeholder="Nama alat/kebutuhan"
                                class="ops-input hidden w-full px-3 py-2 rounded-xl border border-gray-200 text-sm focus:outline-none">
                        </div>
                        <input type="number" name="items[0][quantity]" placeholder="Jumlah" min="1"
                            class="w-24 px-3 py-2 rounded-xl border border-gray-200 text-sm focus:outline-none">
                        <input type="text" name="items[0][unit]" placeholder="Satuan"
                            class="unit-input w-20 px-3 py-2 rounded-xl border border-gray-200 text-sm focus:outline-none">
                        <button type="button" onclick="removeRow(this)"
                            class="w-8 h-8 rounded-lg bg-red-50 text-red-400 flex items-center justify-center hover:bg-red-100 smooth-transition flex-shrink-0">
                            <i class="ph ph-trash text-sm"></i>
                        </button>
                    </div>
                </div>

                {{-- Select untuk produk jadi (tersembunyi, dipakai via JS) --}}
                <div id="produkJadiOptions" class="hidden">
                    @foreach($produkJadi as $menu)
                    <option value="{{ $menu->name }}" data-satuan="pcs">{{ $menu->name }}</option>
                    @endforeach
                </div>
            </div>

            {{-- ══ SECTION OPERASIONAL ══ --}}
            <div id="sectionOperasional" class="hidden">
                <label class="block text-sm font-semibold text-gray-700 mb-2">
                    Nama Alat/Kebutuhan <span class="text-red-500">*</span>
                </label>
                <input type="text" name="item_name_ops" id="itemNameOps"
                    value="{{ old('item_name_ops') }}"
                    placeholder="contoh: Mesin Espresso, Grinder, dll"
                    class="w-full px-4 py-3 rounded-2xl border border-gray-200 focus:outline-none focus:ring-2 focus:ring-elco-mocha/30 text-sm smooth-transition">
            </div>

            <!-- {{-- Jumlah & Satuan --}}
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        Jumlah <span class="text-red-500">*</span>
                    </label>
                    <input type="number" name="quantity" value="{{ old('quantity') }}"
                        placeholder="contoh: 50" min="1"
                        class="w-full px-4 py-3 rounded-2xl border border-gray-200 focus:outline-none focus:ring-2 focus:ring-elco-mocha/30 focus:border-elco-mocha text-sm smooth-transition
                        @error('quantity') border-red-400 @enderror">
                    @error('quantity')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Satuan</label>
                    <input type="text" name="unit" id="unitInput" value="{{ old('unit') }}"
                        placeholder="gram / pcs / liter / unit"
                        class="w-full px-4 py-3 rounded-2xl border border-gray-200 focus:outline-none focus:ring-2 focus:ring-elco-mocha/30 focus:border-elco-mocha text-sm smooth-transition">
                </div>
            </div> -->

            {{-- Alasan --}}
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">
                    Alasan Pengajuan
                </label>
                <textarea name="reason" rows="3"
                    placeholder="Jelaskan alasan pengajuan ini..."
                    class="w-full px-4 py-3 rounded-2xl border border-gray-200 focus:outline-none focus:ring-2 focus:ring-elco-mocha/30 focus:border-elco-mocha text-sm smooth-transition resize-none">{{ old('reason') }}</textarea>
            </div>

            <div class="flex items-start gap-3 bg-amber-50 border border-amber-100 rounded-2xl p-4">
                <i class="ph ph-info text-amber-500 text-xl mt-0.5"></i>
                <p class="text-sm text-amber-700">
                    Pengajuan akan diproses oleh <strong>Manager Pusat</strong>.
                    Stok akan diperbarui setelah disetujui dan barang diterima.
                </p>
            </div>

            {{-- Tombol --}}
            <div class="flex gap-3 pt-2">
                <a href="{{ route('admin.stock-requests.index') }}"
                   class="flex-1 text-center py-3 rounded-2xl border border-gray-200 text-sm font-medium text-gray-600 hover:bg-gray-50 smooth-transition">
                    Batal
                </a>
                <button type="submit"
                    class="flex-1 py-3 rounded-2xl bg-gradient-to-r from-elco-coffee to-elco-mocha text-white text-sm font-semibold shadow-md hover:shadow-hover smooth-transition active:scale-95">
                    <i class="ph ph-paper-plane-right mr-1"></i> Kirim Pengajuan
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
const bahanOptions = `
    <option value="">— Pilih Bahan —</option>
    @foreach($ingredients as $ing)
    <option value="{{ $ing->nama_bahan }}" data-satuan="{{ $ing->satuan }}">{{ $ing->nama_bahan }}</option>
    @endforeach
`;

const produkOptions = `
    <option value="">— Pilih Menu —</option>
    @foreach($produkJadi as $menu)
    <option value="{{ $menu->name }}" data-satuan="pcs">{{ $menu->name }}</option>
    @endforeach
`;

let rowIdx = 1;

function addStokRow() {
    const div = document.createElement('div');
    div.className = 'stok-row grid grid-cols-[auto_1fr_auto_auto_auto] gap-2 items-center bg-gray-50 p-3 rounded-2xl';
    div.innerHTML = `
        <select name="items[${rowIdx}][tipe]" onchange="handleTipeChange(this)"
            class="px-3 py-2 rounded-xl border border-gray-200 text-sm bg-white focus:outline-none w-36">
            <option value="bahan_baku">☕ Bahan Baku</option>
            <option value="produk_jadi">🍰 Produk Jadi</option>
            <option value="operasional">🔧 Operasional</option>
        </select>
        <div class="item-name-wrap">
            <select name="items[${rowIdx}][item_name]"
                onchange="updateUnit(this)"
                class="item-select w-full px-3 py-2 rounded-xl border border-gray-200 text-sm bg-white focus:outline-none">
                ${bahanOptions}
            </select>
            <input type="text" name="items[${rowIdx}][item_name_ops]" placeholder="Nama alat/kebutuhan"
                class="ops-input hidden w-full px-3 py-2 rounded-xl border border-gray-200 text-sm focus:outline-none">
        </div>
        <input type="number" name="items[${rowIdx}][quantity]" placeholder="Jumlah" min="1"
            class="w-24 px-3 py-2 rounded-xl border border-gray-200 text-sm focus:outline-none">
        <input type="text" name="items[${rowIdx}][unit]" placeholder="Satuan"
            class="unit-input w-20 px-3 py-2 rounded-xl border border-gray-200 text-sm focus:outline-none">
        <button type="button" onclick="removeRow(this)"
            class="w-8 h-8 rounded-lg bg-red-50 text-red-400 flex items-center justify-center hover:bg-red-100 flex-shrink-0">
            <i class="ph ph-trash text-sm"></i>
        </button>
    `;
    document.getElementById('stokRows').appendChild(div);
    rowIdx++;
}

function removeRow(btn) {
    const rows = document.querySelectorAll('.stok-row');
    if (rows.length <= 1) return;
    btn.closest('.stok-row').remove();
}

function handleTipeChange(select) {
    const row  = select.closest('.stok-row');
    const tipe = select.value;
    const itemSelect = row.querySelector('.item-select');
    const opsInput   = row.querySelector('.ops-input');
    const unitInput  = row.querySelector('.unit-input');

    if (tipe === 'bahan_baku') {
        itemSelect.innerHTML = bahanOptions;
        itemSelect.classList.remove('hidden');
        opsInput.classList.add('hidden');
    } else if (tipe === 'produk_jadi') {
        itemSelect.innerHTML = produkOptions;
        itemSelect.classList.remove('hidden');
        opsInput.classList.add('hidden');
        unitInput.value = 'pcs';
    } else {
        itemSelect.classList.add('hidden');
        opsInput.classList.remove('hidden');
        unitInput.value = 'unit';
    }
}

function updateUnit(select) {
    const opt   = select.options[select.selectedIndex];
    const satuan = opt?.dataset?.satuan ?? '';
    const row   = select.closest('.stok-row');
    if (satuan) row.querySelector('.unit-input').value = satuan;
}

// Sebelum submit: gabungkan item_name dari field aktif
document.querySelector('form').addEventListener('submit', function() {
    document.querySelectorAll('.stok-row').forEach(row => {
        const tipe     = row.querySelector('select[name$="[tipe]"]').value;
        const opsInput = row.querySelector('.ops-input');
        const selInput = row.querySelector('.item-select');
        if (tipe === 'operasional') {
            selInput.value = opsInput.value;
        }
    });
});

document.querySelectorAll('.item-select').forEach(sel => {
    sel.addEventListener('change', function() { updateUnit(this); });
});
</script>
@endpush