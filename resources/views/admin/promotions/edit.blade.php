@extends('layouts.admin')

@section('content')
<div class="max-w-2xl mx-auto">

    <div class="flex items-center gap-4 mb-6">
        <a href="{{ route('admin.promotions.index') }}"
           class="w-10 h-10 bg-white rounded-xl flex items-center justify-center shadow-soft hover:shadow-hover smooth-transition text-gray-500 hover:text-elco-coffee">
            <i class="ph ph-arrow-left"></i>
        </a>
        <div>
            <h2 class="text-xl font-display font-bold text-gray-800">Edit Promo Cabang</h2>
            <p class="text-sm text-gray-500">{{ $promotion->name }}</p>
        </div>
    </div>

    <div class="mb-6 rounded-2xl border border-yellow-200 bg-yellow-50 px-5 py-4">
        <p class="text-sm font-semibold text-yellow-700">Promo akan dikirim ulang ke Manager Pusat.</p>
        <p class="mt-1 text-xs text-yellow-600">Setelah disimpan, status promo kembali menjadi menunggu review dan belum aktif sampai disetujui.</p>
    </div>

    <div class="bg-white rounded-3xl shadow-soft p-8">
        <form action="{{ route('admin.promotions.update', $promotion) }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">
                    Nama Promo <span class="text-red-500">*</span>
                </label>
                <input type="text" name="name" value="{{ old('name', $promotion->name) }}"
                    class="w-full px-4 py-3 rounded-2xl border border-gray-200 focus:outline-none focus:ring-2 focus:ring-elco-mocha/30 text-sm smooth-transition @error('name') border-red-400 @enderror">
                @error('name')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Deskripsi</label>
                <textarea name="description" rows="2"
                    class="w-full px-4 py-3 rounded-2xl border border-gray-200 focus:outline-none focus:ring-2 focus:ring-elco-mocha/30 text-sm smooth-transition resize-none">{{ old('description', $promotion->description) }}</textarea>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        Tipe Diskon <span class="text-red-500">*</span>
                    </label>
                    <select name="discount_type" id="discountType" onchange="updateDiscountLabel()"
                        class="w-full px-4 py-3 rounded-2xl border border-gray-200 focus:outline-none focus:ring-2 focus:ring-elco-mocha/30 text-sm smooth-transition bg-white">
                        <option value="percentage" {{ old('discount_type', $promotion->discount_type) === 'percentage' ? 'selected' : '' }}>% Persentase</option>
                        <option value="fixed" {{ old('discount_type', $promotion->discount_type) === 'fixed' ? 'selected' : '' }}>Rp Nominal</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        Nilai Diskon <span class="text-red-500">*</span>
                    </label>
                    <div class="relative">
                        <span id="discountPrefix" class="absolute left-4 top-1/2 -translate-y-1/2 text-sm text-gray-400 font-medium"></span>
                        <input type="number" name="discount_value" value="{{ old('discount_value', $promotion->discount_value) }}"
                            placeholder="0" min="0"
                            class="w-full pl-10 pr-4 py-3 rounded-2xl border border-gray-200 focus:outline-none focus:ring-2 focus:ring-elco-mocha/30 text-sm smooth-transition @error('discount_value') border-red-400 @enderror">
                    </div>
                    @error('discount_value')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Minimum Pembelian (Rp)</label>
                <div class="relative">
                    <span class="absolute left-4 top-1/2 -translate-y-1/2 text-sm text-gray-400">Rp</span>
                    <input type="number" name="min_purchase" value="{{ old('min_purchase', $promotion->min_purchase) }}" min="0"
                        class="w-full pl-10 pr-4 py-3 rounded-2xl border border-gray-200 focus:outline-none focus:ring-2 focus:ring-elco-mocha/30 text-sm smooth-transition">
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        Tanggal Mulai <span class="text-red-500">*</span>
                    </label>
                    <input type="date" name="start_date" value="{{ old('start_date', $promotion->start_date->format('Y-m-d')) }}"
                        class="w-full px-4 py-3 rounded-2xl border border-gray-200 focus:outline-none focus:ring-2 focus:ring-elco-mocha/30 text-sm smooth-transition">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        Tanggal Berakhir <span class="text-red-500">*</span>
                    </label>
                    <input type="date" name="end_date" value="{{ old('end_date', $promotion->end_date->format('Y-m-d')) }}"
                        class="w-full px-4 py-3 rounded-2xl border border-gray-200 focus:outline-none focus:ring-2 focus:ring-elco-mocha/30 text-sm smooth-transition">
                    @error('end_date')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="flex gap-3 pt-2">
                <a href="{{ route('admin.promotions.index') }}"
                   class="flex-1 text-center py-3 rounded-2xl border border-gray-200 text-sm font-medium text-gray-600 hover:bg-gray-50 smooth-transition">
                    Batal
                </a>
                <button type="submit"
                    class="flex-1 py-3 rounded-2xl bg-gradient-to-r from-purple-600 to-purple-800 text-white text-sm font-semibold shadow-md hover:shadow-hover smooth-transition active:scale-95">
                    <i class="ph ph-floppy-disk mr-1"></i> Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
function updateDiscountLabel() {
    const type = document.getElementById('discountType').value;
    document.getElementById('discountPrefix').textContent = type === 'percentage' ? '%' : 'Rp';
}
updateDiscountLabel();
</script>
@endpush
