@extends('layouts.manager')

@section('content')

@if(session('success'))
<div class="mb-6 flex items-center gap-3 bg-emerald-50 border border-emerald-200 text-emerald-700 px-5 py-4 rounded-2xl">
    <i class="ph-fill ph-check-circle text-xl"></i> {{ session('success') }}
</div>
@endif

<div class="flex items-center justify-between mb-6">
    <div>
        <h2 class="text-xl font-display font-bold text-gray-800">Manajemen Promo</h2>
        <p class="text-sm text-gray-500 mt-1">Kelola promo global & pantau promo cabang</p>
    </div>
    <a href="{{ route('manager.promotions.create') }}"
       class="flex items-center gap-2 bg-gradient-to-r from-elco-coffee to-elco-mocha text-white text-sm font-semibold px-5 py-3 rounded-2xl shadow-md hover:shadow-hover smooth-transition active:scale-95">
        <i class="ph ph-plus"></i> Buat Promo Global
    </a>
</div>

{{-- Grid Promo --}}
<div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
    @forelse($promotions as $promo)
    <div class="bg-white rounded-3xl shadow-soft overflow-hidden smooth-transition hover:-translate-y-1 hover:shadow-hover">

        {{-- Header Card --}}
        <div class="p-5 {{ $promo->type === 'global'
            ? 'bg-gradient-to-r from-elco-coffee to-elco-mocha'
            : 'bg-gradient-to-r from-purple-600 to-purple-800' }}">
            <div class="flex justify-between items-start">
                <span class="text-xs font-semibold px-2 py-1 rounded-lg bg-white/20 text-white">
                    {{ $promo->type === 'global' ? '🌐 Global' : '🏪 ' . ($promo->branch->name ?? 'Cabang') }}
                </span>
                @if($promo->is_valid)
                    <span class="text-xs font-semibold px-2 py-1 rounded-lg bg-emerald-400/30 text-white">
                        ● Aktif
                    </span>
                @else
                    <span class="text-xs font-semibold px-2 py-1 rounded-lg bg-white/10 text-white/60">
                        ● Tidak Aktif
                    </span>
                @endif
            </div>
            <h3 class="font-display font-bold text-white text-lg mt-3">{{ $promo->name }}</h3>
            <p class="text-white/70 text-xs mt-1">{{ $promo->description }}</p>
        </div>

        {{-- Detail --}}
        <div class="p-5 space-y-3">
            <div class="flex justify-between items-center">
                <span class="text-xs text-gray-500">Diskon</span>
                <span class="text-lg font-display font-bold text-elco-coffee">
                    {{ $promo->discount_label }}
                </span>
            </div>
            <div class="flex justify-between items-center">
                <span class="text-xs text-gray-500">Min. Pembelian</span>
                <span class="text-sm font-medium text-gray-700">
                    {{ $promo->min_purchase > 0
                        ? 'Rp ' . number_format($promo->min_purchase, 0, ',', '.')
                        : 'Tidak ada' }}
                </span>
            </div>
            <div class="flex justify-between items-center">
                <span class="text-xs text-gray-500">Periode</span>
                <span class="text-xs font-medium text-gray-700">
                    {{ $promo->start_date->format('d M') }} —
                    {{ $promo->end_date->format('d M Y') }}
                </span>
            </div>

            {{-- Aksi (hanya untuk promo global) --}}
            @if($promo->type === 'global')
            <div class="flex gap-2 pt-2 border-t border-gray-100">
                <a href="{{ route('manager.promotions.edit', $promo) }}"
                   class="flex-1 text-center text-xs font-medium text-elco-coffee bg-elco-cream py-2 rounded-xl hover:bg-elco-latte/30 smooth-transition">
                    <i class="ph ph-pencil"></i> Edit
                </a>
                <form id="form-hapus-{{ $promo->id }}" method="POST" 
                    action="{{ route('manager.promotions.destroy', $promo->id) }}">
                    @csrf
                    @method('DELETE')
                    <button type="button"
                        onclick="elcoConfirm({
                            title: 'Hapus Promo?',
                            text: 'Promo {{ addslashes($promo->name) }} akan dihapus permanen.',
                            confirmText: 'Ya, Hapus',
                            confirmColor: '#ef4444',
                            icon: 'warning',
                            onConfirm: () => document.getElementById('form-hapus-{{ $promo->id }}').submit()
                        })"
                        class="w-full text-xs font-medium text-red-500 bg-red-50 py-2 rounded-xl hover:bg-red-100 smooth-transition">
                        <i class="ph ph-trash"></i> Hapus
                    </button>
                </form>
            </div>
            @else
            <div class="pt-2 border-t border-gray-100">
                <p class="text-xs text-gray-400 text-center">Promo dari cabang — hanya bisa dilihat</p>
            </div>
            @endif
        </div>
    </div>
    @empty
    <div class="col-span-3 py-16 text-center">
        <i class="ph ph-tag text-5xl text-gray-300 block mb-3"></i>
        <p class="text-gray-400">Belum ada promo. Buat promo global pertama!</p>
    </div>
    @endforelse
</div>

@endsection