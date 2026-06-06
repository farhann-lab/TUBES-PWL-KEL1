@extends('layouts.admin')

@section('content')
<div class="mx-auto max-w-6xl">
    <div class="mb-8 flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
        <div class="flex items-center gap-4">
            <a href="{{ route('admin.stock-requests.index') }}"
               class="flex h-11 w-11 items-center justify-center rounded-2xl bg-white text-gray-500 shadow-soft smooth-transition hover:text-elco-coffee hover:shadow-hover">
                <i class="ph ph-arrow-left"></i>
            </a>
            <div>
                <h2 class="font-display text-2xl font-bold text-gray-800">Request Stok</h2>
                <p class="mt-1 text-sm text-gray-500">Ajukan kebutuhan cabang ke manager pusat</p>
            </div>
        </div>
        <a href="{{ route('admin.stock-requests.index') }}"
           class="inline-flex items-center justify-center gap-2 rounded-2xl border border-gray-200 px-5 py-3 text-sm font-semibold text-gray-600 smooth-transition hover:bg-gray-50">
            <i class="ph ph-clock-counter-clockwise"></i> Riwayat
        </a>
    </div>

    <div class="grid grid-cols-1 gap-7 xl:grid-cols-[minmax(0,1.35fr)_minmax(320px,0.65fr)]">
        <form id="stockRequestForm" action="{{ route('admin.stock-requests.store') }}" method="POST"
              class="space-y-7 rounded-3xl bg-white p-7 shadow-soft">
            @csrf
            <input type="hidden" name="item_name" id="itemNameHidden" value="{{ old('item_name') }}">

            <div>
                <label class="mb-3 block text-sm font-semibold text-gray-700">Jenis Pengajuan <span class="text-red-500">*</span></label>
                <div class="grid grid-cols-1 gap-3 md:grid-cols-2">
                    <label class="cursor-pointer">
                        <input type="radio" name="type" value="stock"
                               class="sr-only peer" {{ old('type', 'stock') === 'stock' ? 'checked' : '' }}>
                        <div class="h-full rounded-2xl border-2 border-gray-200 p-5 smooth-transition peer-checked:border-elco-coffee peer-checked:bg-elco-cream">
                            <div class="mb-4 flex h-11 w-11 items-center justify-center rounded-xl bg-blue-50 text-blue-500">
                                <i class="ph-fill ph-package text-xl"></i>
                            </div>
                            <p class="text-sm font-bold text-gray-800">Stok Barang</p>
                            <p class="mt-1 text-xs text-gray-500">Bahan baku atau produk jadi</p>
                        </div>
                    </label>
                    <label class="cursor-pointer">
                        <input type="radio" name="type" value="operational"
                               class="sr-only peer" {{ old('type') === 'operational' ? 'checked' : '' }}>
                        <div class="h-full rounded-2xl border-2 border-gray-200 p-5 smooth-transition peer-checked:border-elco-coffee peer-checked:bg-elco-cream">
                            <div class="mb-4 flex h-11 w-11 items-center justify-center rounded-xl bg-purple-50 text-purple-500">
                                <i class="ph-fill ph-wrench text-xl"></i>
                            </div>
                            <p class="text-sm font-bold text-gray-800">Operasional</p>
                            <p class="mt-1 text-xs text-gray-500">Peralatan atau kebutuhan cabang</p>
                        </div>
                    </label>
                </div>
                @error('type')
                    <p class="mt-2 text-xs text-red-500">{{ $message }}</p>
                @enderror
            </div>

            <div id="stockSection" class="space-y-6">
                <div>
                    <label class="mb-3 block text-sm font-semibold text-gray-700">Kategori Stok <span class="text-red-500">*</span></label>
                    <div class="grid grid-cols-1 gap-3 md:grid-cols-2">
                        <label class="cursor-pointer">
                            <input type="radio" name="stock_item_type" value="bahan_baku"
                                   class="sr-only peer" {{ old('stock_item_type', 'bahan_baku') === 'bahan_baku' ? 'checked' : '' }}>
                            <div class="rounded-2xl border-2 border-gray-200 p-4 smooth-transition peer-checked:border-amber-500 peer-checked:bg-amber-50">
                                <div class="flex items-center gap-3">
                                    <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-amber-50 text-amber-600">
                                        <i class="ph ph-flask text-lg"></i>
                                    </div>
                                    <div>
                                        <p class="text-sm font-bold text-gray-800">Bahan Baku</p>
                                        <p class="text-xs text-gray-500">Dipakai untuk menu minuman</p>
                                    </div>
                                </div>
                            </div>
                        </label>
                        <label class="cursor-pointer">
                            <input type="radio" name="stock_item_type" value="produk_jadi"
                                   class="sr-only peer" {{ old('stock_item_type') === 'produk_jadi' ? 'checked' : '' }}>
                            <div class="rounded-2xl border-2 border-gray-200 p-4 smooth-transition peer-checked:border-emerald-500 peer-checked:bg-emerald-50">
                                <div class="flex items-center gap-3">
                                    <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-emerald-50 text-emerald-600">
                                        <i class="ph ph-bowl-food text-lg"></i>
                                    </div>
                                    <div>
                                        <p class="text-sm font-bold text-gray-800">Produk Jadi</p>
                                        <p class="text-xs text-gray-500">Makanan dan snack per pcs</p>
                                    </div>
                                </div>
                            </div>
                        </label>
                    </div>
                    @error('stock_item_type')
                        <p class="mt-2 text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <div id="ingredientField">
                    <label class="mb-2 block text-sm font-semibold text-gray-700">Bahan Baku <span class="text-red-500">*</span></label>
                    <select name="item_name_bahan" id="selectBahan"
                        class="w-full rounded-2xl border border-gray-200 bg-white px-4 py-3 text-sm smooth-transition focus:border-elco-mocha focus:outline-none focus:ring-2 focus:ring-elco-mocha/30">
                        <option value="">Pilih bahan baku</option>
                        @foreach($ingredients as $ing)
                        <option value="{{ $ing->nama_bahan }}" data-unit="{{ $ing->satuan }}"
                            {{ old('item_name_bahan', old('item_name')) === $ing->nama_bahan ? 'selected' : '' }}>
                            {{ $ing->nama_bahan }} ({{ $ing->satuan }})
                        </option>
                        @endforeach
                    </select>
                </div>

                <div id="finishedProductField" class="hidden">
                    <label class="mb-2 block text-sm font-semibold text-gray-700">Produk Jadi <span class="text-red-500">*</span></label>
                    <select name="item_name_produk" id="selectProduk"
                        class="w-full rounded-2xl border border-gray-200 bg-white px-4 py-3 text-sm smooth-transition focus:border-elco-mocha focus:outline-none focus:ring-2 focus:ring-elco-mocha/30">
                        <option value="">Pilih menu makanan/snack</option>
                        @foreach($produkJadi as $menu)
                        <option value="{{ $menu->name }}" data-unit="pcs"
                            {{ old('item_name_produk', old('item_name')) === $menu->name ? 'selected' : '' }}>
                            {{ $menu->name }}
                        </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div id="operationalSection" class="hidden">
                <label class="mb-2 block text-sm font-semibold text-gray-700">Nama Kebutuhan <span class="text-red-500">*</span></label>
                <input type="text" name="item_name_ops" id="itemNameOps" value="{{ old('item_name_ops', old('item_name')) }}"
                    placeholder="contoh: Filter air, grinder, gelas take away"
                    class="w-full rounded-2xl border border-gray-200 px-4 py-3 text-sm smooth-transition focus:border-elco-mocha focus:outline-none focus:ring-2 focus:ring-elco-mocha/30">
            </div>

            @error('item_name')
                <p class="-mt-4 text-xs text-red-500">{{ $message }}</p>
            @enderror

            <div class="grid grid-cols-1 gap-4 md:grid-cols-[minmax(0,1fr)_180px]">
                <div>
                    <label class="mb-2 block text-sm font-semibold text-gray-700">Jumlah <span class="text-red-500">*</span></label>
                    <input type="number" name="quantity" id="quantityInput" value="{{ old('quantity') }}" min="1" step="1"
                        placeholder="Masukkan jumlah"
                        class="w-full rounded-2xl border border-gray-200 px-4 py-3 text-sm smooth-transition focus:border-elco-mocha focus:outline-none focus:ring-2 focus:ring-elco-mocha/30 @error('quantity') border-red-400 @enderror">
                    @error('quantity')
                        <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label class="mb-2 block text-sm font-semibold text-gray-700">Satuan</label>
                    <input type="text" name="unit" id="unitInput" value="{{ old('unit') }}"
                        placeholder="gram / ml / pcs"
                        class="w-full rounded-2xl border border-gray-200 px-4 py-3 text-sm smooth-transition focus:border-elco-mocha focus:outline-none focus:ring-2 focus:ring-elco-mocha/30">
                </div>
            </div>

            <div>
                <label class="mb-2 block text-sm font-semibold text-gray-700">Catatan</label>
                <textarea name="reason" rows="4"
                    placeholder="Tambahkan konteks kebutuhan cabang"
                    class="w-full resize-none rounded-2xl border border-gray-200 px-4 py-3 text-sm smooth-transition focus:border-elco-mocha focus:outline-none focus:ring-2 focus:ring-elco-mocha/30">{{ old('reason') }}</textarea>
            </div>

            <div class="flex flex-col gap-3 border-t border-gray-100 pt-2 md:flex-row">
                <a href="{{ route('admin.stock-requests.index') }}"
                   class="flex-1 rounded-2xl border border-gray-200 py-3 text-center text-sm font-medium text-gray-600 smooth-transition hover:bg-gray-50">
                    Batal
                </a>
                <button type="submit"
                    class="flex-1 rounded-2xl bg-gradient-to-r from-elco-coffee to-elco-mocha py-3 text-sm font-semibold text-white shadow-md smooth-transition hover:shadow-hover active:scale-95">
                    <i class="ph ph-paper-plane-right mr-1"></i> Kirim Request
                </button>
            </div>
        </form>

        <aside class="space-y-5">
            <div class="rounded-3xl bg-white p-6 shadow-soft">
                <p class="text-xs font-semibold uppercase tracking-wide text-gray-400">Ringkasan Request</p>
                <div class="mt-5 space-y-4">
                    <div class="flex items-center justify-between gap-4">
                        <span class="text-sm text-gray-500">Tipe</span>
                        <span id="summaryType" class="text-sm font-bold text-gray-800">Stok Barang</span>
                    </div>
                    <div class="flex items-center justify-between gap-4">
                        <span class="text-sm text-gray-500">Item</span>
                        <span id="summaryItem" class="max-w-[180px] truncate text-right text-sm font-bold text-gray-800">-</span>
                    </div>
                    <div class="flex items-center justify-between gap-4">
                        <span class="text-sm text-gray-500">Jumlah</span>
                        <span id="summaryQty" class="text-sm font-bold text-gray-800">-</span>
                    </div>
                </div>
            </div>

            <div class="rounded-3xl bg-white p-6 shadow-soft">
                <p class="text-xs font-semibold uppercase tracking-wide text-gray-400">Status Setelah Dikirim</p>
                <div class="mt-5 space-y-3">
                    <div class="flex items-center gap-3 rounded-2xl bg-yellow-50 p-3">
                        <div class="flex h-9 w-9 items-center justify-center rounded-xl bg-yellow-100 text-yellow-600">
                            <i class="ph ph-hourglass-medium"></i>
                        </div>
                        <div>
                            <p class="text-sm font-semibold text-gray-800">Pending</p>
                            <p class="text-xs text-gray-500">Menunggu keputusan manager</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-3 rounded-2xl bg-blue-50 p-3">
                        <div class="flex h-9 w-9 items-center justify-center rounded-xl bg-blue-100 text-blue-600">
                            <i class="ph ph-truck"></i>
                        </div>
                        <div>
                            <p class="text-sm font-semibold text-gray-800">Menunggu Barang</p>
                            <p class="text-xs text-gray-500">Aktif setelah disetujui</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-3 rounded-2xl bg-emerald-50 p-3">
                        <div class="flex h-9 w-9 items-center justify-center rounded-xl bg-emerald-100 text-emerald-600">
                            <i class="ph ph-check-circle"></i>
                        </div>
                        <div>
                            <p class="text-sm font-semibold text-gray-800">Stok Bertambah</p>
                            <p class="text-xs text-gray-500">Setelah final confirm manager</p>
                        </div>
                    </div>
                </div>
            </div>
        </aside>
    </div>
</div>
@endsection

@push('scripts')
<script>
const typeInputs = document.querySelectorAll('input[name="type"]');
const stockTypeInputs = document.querySelectorAll('input[name="stock_item_type"]');
const stockSection = document.getElementById('stockSection');
const operationalSection = document.getElementById('operationalSection');
const ingredientField = document.getElementById('ingredientField');
const finishedProductField = document.getElementById('finishedProductField');
const selectBahan = document.getElementById('selectBahan');
const selectProduk = document.getElementById('selectProduk');
const itemNameOps = document.getElementById('itemNameOps');
const itemNameHidden = document.getElementById('itemNameHidden');
const unitInput = document.getElementById('unitInput');
const quantityInput = document.getElementById('quantityInput');
const summaryType = document.getElementById('summaryType');
const summaryItem = document.getElementById('summaryItem');
const summaryQty = document.getElementById('summaryQty');

function checkedValue(name) {
    return document.querySelector(`input[name="${name}"]:checked`)?.value;
}

function selectedText(select) {
    if (!select || !select.value) return '';
    return select.options[select.selectedIndex]?.text?.replace(/\s+\([^)]*\)$/, '') ?? select.value;
}

function syncRequestForm() {
    const type = checkedValue('type');
    const stockType = checkedValue('stock_item_type');
    const isStock = type === 'stock';

    stockSection.classList.toggle('hidden', !isStock);
    operationalSection.classList.toggle('hidden', isStock);
    ingredientField.classList.toggle('hidden', !isStock || stockType !== 'bahan_baku');
    finishedProductField.classList.toggle('hidden', !isStock || stockType !== 'produk_jadi');

    let itemName = '';
    let unit = unitInput.value;

    if (isStock && stockType === 'bahan_baku') {
        itemName = selectBahan.value;
        unit = selectBahan.options[selectBahan.selectedIndex]?.dataset?.unit || unit;
    } else if (isStock && stockType === 'produk_jadi') {
        itemName = selectProduk.value;
        unit = 'pcs';
    } else {
        itemName = itemNameOps.value;
        unit = unit || 'unit';
    }

    itemNameHidden.value = itemName;
    if (unit) unitInput.value = unit;

    summaryType.textContent = isStock ? (stockType === 'produk_jadi' ? 'Produk Jadi' : 'Bahan Baku') : 'Operasional';
    summaryItem.textContent = isStock && stockType === 'bahan_baku'
        ? (selectedText(selectBahan) || '-')
        : (isStock ? (selectedText(selectProduk) || '-') : (itemName || '-'));
    summaryQty.textContent = quantityInput.value ? `${quantityInput.value} ${unitInput.value || ''}` : '-';
}

[...typeInputs, ...stockTypeInputs].forEach(input => input.addEventListener('change', syncRequestForm));
[selectBahan, selectProduk, itemNameOps, unitInput, quantityInput].forEach(input => {
    input?.addEventListener('input', syncRequestForm);
    input?.addEventListener('change', syncRequestForm);
});

document.getElementById('stockRequestForm').addEventListener('submit', syncRequestForm);
document.addEventListener('DOMContentLoaded', syncRequestForm);
syncRequestForm();
</script>
@endpush
