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

            {{-- Tipe Pengajuan --}}
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-3">
                    Tipe Pengajuan <span class="text-red-500">*</span>
                </label>
                <div class="grid grid-cols-2 gap-3">
                    <label class="cursor-pointer">
                        <input type="radio" name="type" value="stock"
                               class="sr-only peer" {{ old('type') === 'stock' ? 'checked' : '' }}>
                        <div class="p-4 border-2 border-gray-200 rounded-2xl peer-checked:border-elco-coffee peer-checked:bg-elco-cream smooth-transition text-center">
                            <i class="ph-fill ph-package text-2xl text-gray-400 peer-checked:text-elco-coffee block mb-2"></i>
                            <p class="text-sm font-semibold text-gray-700">📦 Pengajuan Stok</p>
                            <p class="text-xs text-gray-400 mt-1">Bahan baku & produk</p>
                        </div>
                    </label>
                    <label class="cursor-pointer">
                        <input type="radio" name="type" value="operational"
                               class="sr-only peer" {{ old('type') === 'operational' ? 'checked' : '' }}>
                        <div class="p-4 border-2 border-gray-200 rounded-2xl peer-checked:border-elco-coffee peer-checked:bg-elco-cream smooth-transition text-center">
                            <i class="ph-fill ph-wrench text-2xl text-gray-400 block mb-2"></i>
                            <p class="text-sm font-semibold text-gray-700">🔧 Alat Operasional</p>
                            <p class="text-xs text-gray-400 mt-1">Peralatan & perlengkapan</p>
                        </div>
                    </label>
                </div>
                @error('type')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Nama Item --}}
            <div id="stockItemField">
                <label class="block text-sm font-semibold text-gray-700 mb-2">
                    Nama Item <span class="text-red-500">*</span>
                </label>
                {{-- Dropdown untuk stok --}}
                <div id="stockSelect">
                    <select name="item_name" id="itemNameSelect"
                        class="w-full px-4 py-3 rounded-2xl border border-gray-200 focus:outline-none focus:ring-2 focus:ring-elco-mocha/30 text-sm smooth-transition bg-white">
                        <option value="">Pilih Menu/Bahan</option>
                        @foreach(\App\Models\Menu::where('is_available', true)->get() as $menu)
                        <option value="{{ $menu->name }}">{{ $menu->name }}</option>
                        @endforeach
                    </select>
                </div>
                {{-- Input text untuk operasional --}}
                <div id="operationalInput" class="hidden">
                    <input type="text" name="item_name_ops" id="itemNameOps"
                        placeholder="contoh: Mesin Espresso, Grinder, dll"
                        class="w-full px-4 py-3 rounded-2xl border border-gray-200 focus:outline-none focus:ring-2 focus:ring-elco-mocha/30 text-sm smooth-transition">
                </div>
            </div>

            {{-- Jumlah & Satuan --}}
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
                    <input type="text" name="unit" value="{{ old('unit') }}"
                        placeholder="kg / pcs / liter / unit"
                        class="w-full px-4 py-3 rounded-2xl border border-gray-200 focus:outline-none focus:ring-2 focus:ring-elco-mocha/30 focus:border-elco-mocha text-sm smooth-transition">
                </div>
            </div>

            {{-- Alasan --}}
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">
                    Alasan Pengajuan
                </label>
                <textarea name="reason" rows="3"
                    placeholder="Jelaskan alasan pengajuan ini..."
                    class="w-full px-4 py-3 rounded-2xl border border-gray-200 focus:outline-none focus:ring-2 focus:ring-elco-mocha/30 focus:border-elco-mocha text-sm smooth-transition resize-none">{{ old('reason') }}</textarea>
            </div>

            {{-- Info --}}
            <div class="flex items-start gap-3 bg-amber-50 border border-amber-100 rounded-2xl p-4">
                <i class="ph ph-info text-amber-500 text-xl mt-0.5"></i>
                <p class="text-sm text-amber-700">
                    Pengajuan akan diproses oleh <strong>Manager Pusat</strong>.
                    Kamu bisa memantau status di halaman Pengajuan.
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