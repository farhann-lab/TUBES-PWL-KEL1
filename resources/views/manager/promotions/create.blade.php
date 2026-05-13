@extends('layouts.manager')

@section('content')
<div class="max-w-2xl mx-auto">

    {{-- Header --}}
    <div class="flex items-center gap-4 mb-6">
        <a href="{{ route('manager.menus.index') }}"
           class="w-10 h-10 bg-white rounded-xl flex items-center justify-center shadow-soft hover:shadow-hover smooth-transition text-gray-500 hover:text-elco-coffee">
            <i class="ph ph-arrow-left"></i>
        </a>
        <div>
            <h2 class="text-xl font-display font-bold text-gray-800">Tambah Menu Baru</h2>
            <p class="text-sm text-gray-500">Menu akan otomatis terdistribusi ke semua cabang aktif</p>
        </div>
    </div>

    <div class="bg-white rounded-3xl shadow-soft p-8">
        <form action="{{ route('manager.menus.store') }}" method="POST"
              enctype="multipart/form-data" class="space-y-6">
            @csrf

            {{-- Preview Gambar --}}
            <div class="flex flex-col items-center justify-center border-2 border-dashed border-gray-200 rounded-2xl p-6 cursor-pointer hover:border-elco-mocha smooth-transition"
                 onclick="document.getElementById('imageInput').click()">
                <div id="imagePreview" class="hidden mb-3">
                    <img id="previewImg" src="" class="h-32 rounded-xl object-cover">
                </div>
                <div id="uploadPlaceholder">
                    <i class="ph ph-image text-4xl text-gray-300 block text-center mb-2"></i>
                    <p class="text-sm text-gray-400 text-center">Klik untuk upload foto menu</p>
                    <p class="text-xs text-gray-300 text-center mt-1">JPG, PNG, WEBP — Maks 2MB</p>
                </div>
                <input type="file" id="imageInput" name="image" accept="image/*" class="hidden"
                       onchange="previewImage(this)">
            </div>
            @error('image')
                <p class="text-red-500 text-xs -mt-4">{{ $message }}</p>
            @enderror

            {{-- Nama Menu --}}
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">
                    Nama Menu <span class="text-red-500">*</span>
                </label>
                <input type="text" name="name" value="{{ old('name') }}"
                    placeholder="contoh: Kopi Arabika Gayo"
                    class="w-full px-4 py-3 rounded-2xl border border-gray-200 focus:outline-none focus:ring-2 focus:ring-elco-mocha/30 focus:border-elco-mocha text-sm smooth-transition
                    @error('name') border-red-400 @enderror">
                @error('name')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Deskripsi --}}
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Deskripsi</label>
                <textarea name="description" rows="3"
                    placeholder="Deskripsi singkat menu..."
                    class="w-full px-4 py-3 rounded-2xl border border-gray-200 focus:outline-none focus:ring-2 focus:ring-elco-mocha/30 focus:border-elco-mocha text-sm smooth-transition resize-none">{{ old('description') }}</textarea>
            </div>

            {{-- Kategori & Harga --}}
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        Kategori <span class="text-red-500">*</span>
                    </label>
                    <select name="category"
                        class="w-full px-4 py-3 rounded-2xl border border-gray-200 focus:outline-none focus:ring-2 focus:ring-elco-mocha/30 focus:border-elco-mocha text-sm smooth-transition bg-white
                        @error('category') border-red-400 @enderror">
                        <option value="">Pilih Kategori</option>
                        <option value="minuman" {{ old('category') === 'minuman' ? 'selected' : '' }}>☕ Minuman</option>
                        <option value="makanan" {{ old('category') === 'makanan' ? 'selected' : '' }}>🍱 Makanan</option>
                        <option value="snack"   {{ old('category') === 'snack'   ? 'selected' : '' }}>🍪 Snack</option>
                    </select>
                    @error('category')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        Harga Dasar (Rp) <span class="text-red-500">*</span>
                    </label>
                    <input type="number" name="base_price" value="{{ old('base_price') }}"
                        placeholder="contoh: 25000"
                        min="0"
                        class="w-full px-4 py-3 rounded-2xl border border-gray-200 focus:outline-none focus:ring-2 focus:ring-elco-mocha/30 focus:border-elco-mocha text-sm smooth-transition
                        @error('base_price') border-red-400 @enderror">
                    @error('base_price')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            {{-- Info distribusi --}}
            <div class="flex items-start gap-3 bg-blue-50 border border-blue-100 rounded-2xl p-4">
                <i class="ph ph-info text-blue-500 text-xl mt-0.5"></i>
                <p class="text-sm text-blue-600">
                    Menu ini akan otomatis terdistribusi ke <strong>semua cabang aktif</strong>
                    dengan stok awal 0. Admin cabang dapat mengajukan penambahan stok.
                </p>
            </div>

            {{-- Tombol --}}
            <div class="flex gap-3 pt-2">
                <a href="{{ route('manager.menus.index') }}"
                   class="flex-1 text-center py-3 rounded-2xl border border-gray-200 text-sm font-medium text-gray-600 hover:bg-gray-50 smooth-transition">
                    Batal
                </a>
                <button type="submit"
                    class="flex-1 py-3 rounded-2xl bg-gradient-to-r from-elco-coffee to-elco-mocha text-white text-sm font-semibold shadow-md hover:shadow-hover smooth-transition active:scale-95">
                    <i class="ph ph-plus mr-1"></i> Simpan Menu
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
function previewImage(input) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = e => {
            document.getElementById('previewImg').src = e.target.result;
            document.getElementById('imagePreview').classList.remove('hidden');
            document.getElementById('uploadPlaceholder').classList.add('hidden');
        };
        reader.readAsDataURL(input.files[0]);
    }
}
</script>
<script>
// Toggle field nama item berdasarkan tipe
document.querySelectorAll('input[name="type"]').forEach(radio => {
    radio.addEventListener('change', function() {
        const isStock = this.value === 'stock';
        document.getElementById('stockSelect').classList.toggle('hidden', !isStock);
        document.getElementById('operationalInput').classList.toggle('hidden', isStock);

        // Sync nilai ke field yang aktif
        document.getElementById('itemNameSelect').required = isStock;
        document.getElementById('itemNameOps').required    = !isStock;
    });
});

// Sebelum submit, satukan nilai item_name
document.querySelector('form').addEventListener('submit', function(e) {
    const type = document.querySelector('input[name="type"]:checked')?.value;
    if (type === 'operational') {
        // Buat hidden input dengan nama item_name
        const val = document.getElementById('itemNameOps').value;
        const hidden = document.createElement('input');
        hidden.type  = 'hidden';
        hidden.name  = 'item_name';
        hidden.value = val;
        this.appendChild(hidden);
    }
});
</script>
@endpush