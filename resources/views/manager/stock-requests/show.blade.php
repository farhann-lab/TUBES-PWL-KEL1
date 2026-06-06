@extends('layouts.manager')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="flex items-center gap-4 mb-6">
        <a href="{{ route('manager.stock-requests.index') }}"
           class="w-10 h-10 bg-white rounded-xl flex items-center justify-center shadow-soft hover:shadow-hover smooth-transition text-gray-500 hover:text-elco-coffee">
            <i class="ph ph-arrow-left"></i>
        </a>
        <div>
            <h2 class="text-xl font-display font-bold text-gray-800">Detail Pengajuan</h2>
            <p class="text-sm text-gray-500">Informasi lengkap pengajuan stok atau operasional cabang</p>
        </div>
    </div>

    <div class="bg-white rounded-3xl shadow-soft overflow-hidden">
        <div class="p-6 border-b border-gray-100 flex items-start justify-between gap-4">
            <div>
                <p class="text-xs uppercase tracking-wide text-gray-400 mb-1">Item Pengajuan</p>
                <h3 class="text-lg font-display font-bold text-gray-800">{{ $stockRequest->item_name }}</h3>
                <p class="text-sm text-gray-500 mt-1">{{ $stockRequest->branch?->name ?? '—' }}</p>
            </div>
            <span class="px-3 py-1 rounded-full text-xs font-semibold
                {{ $stockRequest->status === 'pending' ? 'bg-yellow-100 text-yellow-700' : '' }}
                {{ $stockRequest->status === 'approved' ? 'bg-emerald-100 text-emerald-700' : '' }}
                {{ $stockRequest->status === 'rejected' ? 'bg-red-100 text-red-700' : '' }}">
                {{ $stockRequest->status === 'pending' ? 'Pending' : ($stockRequest->status === 'approved' ? 'Disetujui' : 'Ditolak') }}
            </span>
        </div>

        <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-5">
            <div class="rounded-2xl bg-gray-50 p-4">
                <p class="text-xs text-gray-400 mb-1">Tipe</p>
                <p class="text-sm font-semibold text-gray-700">{{ $stockRequest->type === 'stock' ? 'Stok' : 'Operasional' }}</p>
            </div>
            <div class="rounded-2xl bg-gray-50 p-4">
                <p class="text-xs text-gray-400 mb-1">Jumlah</p>
                <p class="text-sm font-semibold text-gray-700">{{ $stockRequest->quantity }} {{ $stockRequest->unit ?? '' }}</p>
            </div>
            <div class="rounded-2xl bg-gray-50 p-4">
                <p class="text-xs text-gray-400 mb-1">Diajukan Oleh</p>
                <p class="text-sm font-semibold text-gray-700">{{ $stockRequest->requestedBy?->name ?? '—' }}</p>
            </div>
            <div class="rounded-2xl bg-gray-50 p-4">
                <p class="text-xs text-gray-400 mb-1">Tanggal Pengajuan</p>
                <p class="text-sm font-semibold text-gray-700">{{ $stockRequest->created_at?->format('d M Y H:i') ?? '—' }}</p>
            </div>
            <div class="rounded-2xl bg-gray-50 p-4 md:col-span-2">
                <p class="text-xs text-gray-400 mb-1">Alasan</p>
                <p class="text-sm text-gray-700">{{ $stockRequest->reason ?? '—' }}</p>
            </div>

            @if($stockRequest->delivery_status)
            <div class="rounded-2xl bg-gray-50 p-4">
                <p class="text-xs text-gray-400 mb-1">Status Pengiriman</p>
                <p class="text-sm font-semibold text-gray-700">
                    {{ $stockRequest->delivery_status === 'waiting' ? 'Menunggu Kirim' : '' }}
                    {{ $stockRequest->delivery_status === 'delivered' ? 'Barang Sampai' : '' }}
                    {{ $stockRequest->delivery_status === 'confirmed' ? 'Stok Bertambah' : '' }}
                </p>
            </div>
            <div class="rounded-2xl bg-gray-50 p-4">
                <p class="text-xs text-gray-400 mb-1">Diverifikasi Oleh</p>
                <p class="text-sm font-semibold text-gray-700">{{ $stockRequest->verifiedBy?->name ?? '—' }}</p>
            </div>
            @endif

            @if($stockRequest->rejection_note)
            <div class="rounded-2xl bg-red-50 p-4 md:col-span-2">
                <p class="text-xs text-red-400 mb-1">Alasan Penolakan</p>
                <p class="text-sm text-red-700">{{ $stockRequest->rejection_note }}</p>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
