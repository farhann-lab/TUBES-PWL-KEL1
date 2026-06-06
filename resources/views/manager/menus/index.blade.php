@extends('layouts.manager')

@section('content')

{{-- Alert --}}
@if(session('success'))
<div class="mb-6 flex items-center gap-3 bg-emerald-50 border border-emerald-200 text-emerald-700 px-5 py-4 rounded-2xl">
    <i class="ph-fill ph-check-circle text-xl"></i>
    {{ session('success') }}
</div>
@endif

{{-- Header --}}
<div class="mb-8 flex flex-col gap-5 md:flex-row md:items-center md:justify-between">
    <div>
        <h2 class="text-2xl font-display font-bold text-gray-800">Manajemen Menu</h2>
        <p class="mt-2 text-sm text-gray-500">Kelola menu, gambar, resep, dan harga dasar ELCO</p>
    </div>
    <a href="{{ route('manager.menus.create') }}"
       class="inline-flex items-center justify-center gap-2 bg-gradient-to-r from-elco-coffee to-elco-mocha text-white text-sm font-semibold px-6 py-3.5 rounded-2xl shadow-md hover:shadow-hover smooth-transition active:scale-95">
        <i class="ph ph-plus"></i> Tambah Menu
    </a>
</div>

{{-- Filter Kategori --}}
<div class="mb-8 flex flex-wrap gap-3">
    @foreach(['semua', 'minuman', 'makanan', 'snack'] as $cat)
    <button onclick="filterMenu('{{ $cat }}')"
        id="btn-{{ $cat }}"
        class="px-5 py-2.5 rounded-2xl text-sm font-medium smooth-transition
        {{ $cat === 'semua' ? 'bg-elco-coffee text-white shadow-md' : 'bg-white text-gray-500 hover:bg-gray-50 shadow-soft' }}">
        {{ ucfirst($cat) }}
    </button>
    @endforeach
</div>

{{-- Grid Menu --}}
<div class="grid grid-cols-1 gap-8 md:grid-cols-2 xl:grid-cols-3" id="menu-grid">
    @forelse($menus as $menu)
    <div class="menu-card bg-white rounded-3xl shadow-soft overflow-hidden smooth-transition hover:-translate-y-1 hover:shadow-hover"
        data-category="{{ $menu->category }}">

        {{-- Gambar Menu --}}
        <div class="relative h-52 overflow-hidden bg-gradient-to-br from-elco-cream to-orange-50">
            <img src="{{ $menu->image_url }}"
                 alt="{{ $menu->name }}"
                 class="h-full w-full object-cover">

            {{-- Badge Kategori --}}
            <span class="absolute top-3 left-3 px-3 py-1 rounded-full text-xs font-semibold bg-white/80 backdrop-blur-sm text-elco-coffee">
                {{ ucfirst($menu->category) }}
            </span>

            @if(!$menu->is_available)
                <span class="absolute top-3 right-3 px-3 py-1 rounded-full text-xs font-semibold bg-yellow-500 text-white">
                    Nonaktif
                </span>
            @endif
        </div>

        {{-- Info Menu --}}
        <div class="p-6">
            <h3 class="font-display font-bold text-gray-800 text-lg">{{ $menu->name }}</h3>
            <p class="text-sm text-gray-500 mt-1 line-clamp-2">
                {{ $menu->description ?? 'Tidak ada deskripsi' }}
            </p>

            {{-- Harga --}}
            <div class="mt-6 flex items-center justify-between gap-4">
                <div>
                    <p class="text-xs text-gray-400">Harga Dasar</p>
                    <p class="text-lg font-display font-bold text-elco-coffee">
                        Rp {{ number_format($menu->base_price, 0, ',', '.') }}
                    </p>
                </div>

                {{-- Aksi --}}
                <div class="flex gap-2">
                    @if($menu->isIngredientBased())
                        <a href="{{ route('manager.menus.recipe', $menu) }}"
                           class="flex h-10 w-10 items-center justify-center rounded-xl bg-amber-50 text-amber-700 smooth-transition hover:bg-amber-100">
                            <i class="ph ph-flask"></i>
                        </a>
                    @endif
                    <a href="{{ route('manager.menus.edit', $menu) }}"
                       class="flex h-10 w-10 items-center justify-center rounded-xl bg-elco-cream text-elco-coffee smooth-transition hover:bg-elco-latte/30">
                        <i class="ph ph-pencil"></i>
                    </a>
                    <form id="form-hapus-{{ $menu->id }}" method="POST"
                        action="{{ route('manager.menus.destroy', $menu->id) }}">
                        @csrf
                        @method('DELETE')
                        <button type="button"
                            onclick="elcoConfirm({
                                title: 'Hapus Permanen?',
                                text: 'Menu {{ addslashes($menu->name) }} akan dihapus permanen dari daftar menu. Riwayat transaksi tetap tersimpan.',
                                confirmText: 'Hapus Permanen',
                                confirmColor: '#ef4444',
                                icon: 'warning',
                                onConfirm: () => document.getElementById('form-hapus-{{ $menu->id }}').submit()
                            })"
                            class="flex h-10 w-10 items-center justify-center rounded-xl bg-red-50 text-red-500 smooth-transition hover:bg-red-100">
                            <i class="ph ph-trash"></i>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    @empty
    <div class="col-span-3 py-16 text-center">
        <i class="ph ph-coffee text-5xl text-gray-300 block mb-3"></i>
        <p class="text-gray-400">Belum ada menu. Tambahkan menu pertama!</p>
    </div>
    @endforelse
</div>

@endsection

@push('scripts')
<script>
function filterMenu(category) {
    // Update button style
    document.querySelectorAll('[id^="btn-"]').forEach(btn => {
        btn.classList.remove('bg-elco-coffee', 'text-white', 'shadow-md');
        btn.classList.add('bg-white', 'text-gray-500', 'shadow-soft');
    });
    document.getElementById('btn-' + category).classList.add('bg-elco-coffee', 'text-white', 'shadow-md');
    document.getElementById('btn-' + category).classList.remove('bg-white', 'text-gray-500');

    // Filter cards
    document.querySelectorAll('.menu-card').forEach(card => {
        if (category === 'semua' || card.dataset.category === category) {
            card.style.display = 'block';
        } else {
            card.style.display = 'none';
        }
    });
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
