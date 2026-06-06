@extends('layouts.admin')

@section('content')

@if(session('success'))
<div class="mb-6 flex items-center gap-3 rounded-2xl border border-emerald-200 bg-emerald-50 px-5 py-4 text-emerald-700">
    <i class="ph-fill ph-check-circle text-xl"></i>
    <span class="text-sm font-medium">{{ session('success') }}</span>
</div>
@endif

@php
    $totalRequests = $requests->count();
    $pendingRequests = $requests->where('status', 'pending')->count();
    $waitingRequests = $requests->where('delivery_status', 'waiting')->count();
    $finishedRequests = $requests->where('delivery_status', 'confirmed')->count();
@endphp

<div class="mb-8 flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
    <div>
        <h2 class="font-display text-2xl font-bold text-gray-800">Request Stok</h2>
        <p class="mt-1 text-sm text-gray-500">Pantau pengajuan stok, operasional, dan proses penerimaan barang</p>
    </div>
    <a href="{{ route('admin.stock-requests.create') }}"
       class="inline-flex items-center justify-center gap-2 rounded-2xl bg-gradient-to-r from-elco-coffee to-elco-mocha px-5 py-3 text-sm font-semibold text-white shadow-md smooth-transition hover:shadow-hover active:scale-95">
        <i class="ph ph-plus"></i> Buat Request
    </a>
</div>

<div class="mb-6 grid grid-cols-2 gap-4 xl:grid-cols-4">
    <div class="rounded-3xl bg-white p-5 shadow-soft">
        <div class="mb-3 flex h-10 w-10 items-center justify-center rounded-xl bg-blue-50 text-blue-500">
            <i class="ph-fill ph-clipboard-text text-xl"></i>
        </div>
        <p class="text-xs text-gray-500">Total Request</p>
        <p class="mt-1 font-display text-2xl font-bold text-gray-800">{{ $totalRequests }}</p>
    </div>
    <div class="rounded-3xl bg-white p-5 shadow-soft">
        <div class="mb-3 flex h-10 w-10 items-center justify-center rounded-xl bg-yellow-50 text-yellow-600">
            <i class="ph-fill ph-hourglass-medium text-xl"></i>
        </div>
        <p class="text-xs text-gray-500">Pending</p>
        <p class="mt-1 font-display text-2xl font-bold text-gray-800">{{ $pendingRequests }}</p>
    </div>
    <div class="rounded-3xl bg-white p-5 shadow-soft">
        <div class="mb-3 flex h-10 w-10 items-center justify-center rounded-xl bg-orange-50 text-orange-500">
            <i class="ph-fill ph-truck text-xl"></i>
        </div>
        <p class="text-xs text-gray-500">Menunggu Barang</p>
        <p class="mt-1 font-display text-2xl font-bold text-gray-800">{{ $waitingRequests }}</p>
    </div>
    <div class="rounded-3xl bg-white p-5 shadow-soft">
        <div class="mb-3 flex h-10 w-10 items-center justify-center rounded-xl bg-emerald-50 text-emerald-500">
            <i class="ph-fill ph-check-circle text-xl"></i>
        </div>
        <p class="text-xs text-gray-500">Selesai</p>
        <p class="mt-1 font-display text-2xl font-bold text-gray-800">{{ $finishedRequests }}</p>
    </div>
</div>

<div class="overflow-hidden rounded-3xl bg-white shadow-soft">
    <div class="overflow-x-auto">
        <table class="w-full min-w-[980px] text-left">
            <thead>
                <tr class="border-b border-gray-100 bg-gray-50 text-xs text-gray-400">
                    <th class="px-6 py-4 font-medium">Item</th>
                    <th class="px-6 py-4 font-medium">Kategori</th>
                    <th class="px-6 py-4 font-medium">Jumlah</th>
                    <th class="px-6 py-4 font-medium">Tanggal</th>
                    <th class="px-6 py-4 font-medium">Status</th>
                    <th class="px-6 py-4 font-medium">Aksi</th>
                    <th class="px-6 py-4 font-medium">Catatan</th>
                </tr>
            </thead>
            <tbody>
                @forelse($requests as $req)
                @php
                    $typeLabel = $req->type === 'stock'
                        ? ($req->stock_item_type === 'produk_jadi' ? 'Produk Jadi' : 'Bahan Baku')
                        : 'Operasional';
                @endphp
                <tr class="border-b border-gray-50 last:border-0 smooth-transition hover:bg-gray-50">
                    <td class="px-6 py-4">
                        <p class="text-sm font-semibold text-gray-800">{{ $req->item_name }}</p>
                        <p class="mt-1 text-xs text-gray-400">{{ $req->requestedBy?->name ?? 'Admin Cabang' }}</p>
                    </td>
                    <td class="px-6 py-4">
                        <span class="inline-flex items-center gap-1.5 rounded-full px-3 py-1 text-xs font-semibold
                            {{ $req->type === 'stock' ? 'bg-blue-50 text-blue-600' : 'bg-purple-50 text-purple-600' }}">
                            <i class="ph {{ $req->type === 'stock' ? 'ph-package' : 'ph-wrench' }}"></i>
                            {{ $typeLabel }}
                        </span>
                    </td>
                    <td class="px-6 py-4 text-sm font-semibold text-gray-700">
                        {{ number_format($req->quantity, 0, ',', '.') }} {{ $req->unit }}
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-500">
                        {{ $req->created_at->format('d M Y') }}
                    </td>
                    <td class="px-6 py-4">
                        <div class="space-y-2">
                            <span class="inline-flex w-fit items-center gap-1.5 rounded-full px-3 py-1 text-xs font-semibold
                                {{ $req->status === 'pending' ? 'bg-yellow-100 text-yellow-700' : '' }}
                                {{ $req->status === 'approved' ? 'bg-blue-100 text-blue-700' : '' }}
                                {{ $req->status === 'rejected' ? 'bg-red-100 text-red-700' : '' }}">
                                <i class="ph {{ $req->status === 'pending' ? 'ph-hourglass-medium' : ($req->status === 'approved' ? 'ph-check' : 'ph-x') }}"></i>
                                {{ $req->status === 'pending' ? 'Pending' : ($req->status === 'approved' ? 'Disetujui' : 'Ditolak') }}
                            </span>

                            @if($req->status === 'approved')
                            <span class="inline-flex w-fit items-center gap-1.5 rounded-full px-3 py-1 text-xs font-semibold
                                {{ $req->delivery_status === 'waiting' ? 'bg-orange-100 text-orange-700' : '' }}
                                {{ $req->delivery_status === 'delivered' ? 'bg-emerald-100 text-emerald-700' : '' }}
                                {{ $req->delivery_status === 'confirmed' ? 'bg-purple-100 text-purple-700' : '' }}">
                                <i class="ph {{ $req->delivery_status === 'waiting' ? 'ph-truck' : ($req->delivery_status === 'delivered' ? 'ph-package' : 'ph-check-circle') }}"></i>
                                {{ $req->delivery_status === 'waiting' ? 'Menunggu Barang' : '' }}
                                {{ $req->delivery_status === 'delivered' ? 'Menunggu Manager' : '' }}
                                {{ $req->delivery_status === 'confirmed' ? 'Stok Bertambah' : '' }}
                            </span>
                            @endif
                        </div>
                    </td>
                    <td class="px-6 py-4">
                        @if($req->status === 'approved' && $req->delivery_status === 'waiting')
                        <button type="button" onclick="openDeliveryModal({{ $req->id }})"
                            class="inline-flex items-center gap-2 rounded-xl bg-emerald-50 px-3 py-2 text-xs font-semibold text-emerald-600 smooth-transition hover:bg-emerald-100">
                            <i class="ph ph-package"></i> Konfirmasi Terima
                        </button>
                        @elseif($req->delivery_status === 'delivered')
                        <span class="text-xs font-semibold text-blue-600">Terkirim ke manager</span>
                        @elseif($req->delivery_status === 'confirmed')
                        <span class="text-xs font-semibold text-emerald-600">Selesai</span>
                        @else
                        <span class="text-xs text-gray-400">-</span>
                        @endif
                    </td>
                    <td class="max-w-xs px-6 py-4 text-sm text-gray-500">
                        @if($req->rejection_note)
                            <span class="text-red-500">{{ $req->rejection_note }}</span>
                        @elseif($req->delivery_note)
                            <span>{{ $req->delivery_note }}</span>
                        @else
                            <span class="text-gray-400">-</span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="px-6 py-14 text-center text-gray-400">
                        <i class="ph ph-clipboard-text mb-2 block text-4xl"></i>
                        <p class="text-sm">Belum ada request</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div id="deliveryModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/40 px-4 backdrop-blur-sm">
    <div class="w-full max-w-md rounded-3xl bg-white p-8 shadow-2xl">
        <div class="mb-5 flex items-start justify-between gap-4">
            <div>
                <h3 class="font-display text-lg font-bold text-gray-800">Konfirmasi Barang Sampai</h3>
                <p class="mt-1 text-sm text-gray-500">Kirim bukti penerimaan ke manager</p>
            </div>
            <button type="button" onclick="closeDeliveryModal()"
                class="flex h-9 w-9 items-center justify-center rounded-xl bg-gray-100 text-gray-500 smooth-transition hover:bg-gray-200">
                <i class="ph ph-x"></i>
            </button>
        </div>

        <form id="deliveryForm" method="POST" enctype="multipart/form-data" class="space-y-5">
            @csrf
            <div>
                <label class="mb-2 block text-sm font-semibold text-gray-700">Foto Bukti</label>
                <button type="button" onclick="document.getElementById('deliveryPhotoInput').click()"
                    class="w-full rounded-2xl border-2 border-dashed border-gray-200 p-5 text-center smooth-transition hover:border-elco-mocha">
                    <img id="deliveryPhotoPreview" class="mx-auto mb-3 hidden h-36 rounded-xl object-cover">
                    <span id="deliveryUploadPlaceholder">
                        <i class="ph ph-camera mb-1 block text-3xl text-gray-300"></i>
                        <span class="text-sm text-gray-400">Pilih foto barang</span>
                    </span>
                </button>
                <input type="file" id="deliveryPhotoInput" name="delivery_photo" accept="image/*" class="hidden"
                    onchange="previewDeliveryPhoto(this)">
            </div>

            <div>
                <label class="mb-2 block text-sm font-semibold text-gray-700">Catatan Kondisi <span class="text-red-500">*</span></label>
                <textarea name="delivery_note" rows="3" required
                    placeholder="contoh: Barang diterima lengkap dan kondisi baik"
                    class="w-full resize-none rounded-2xl border border-gray-200 px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-elco-mocha/30"></textarea>
            </div>

            <div class="flex gap-3">
                <button type="button" onclick="closeDeliveryModal()"
                    class="flex-1 rounded-2xl border border-gray-200 py-3 text-sm font-medium text-gray-600 hover:bg-gray-50">
                    Batal
                </button>
                <button type="submit"
                    class="flex-1 rounded-2xl bg-emerald-600 py-3 text-sm font-semibold text-white smooth-transition hover:bg-emerald-700">
                    Kirim Bukti
                </button>
            </div>
        </form>
    </div>
</div>

@endsection

@push('scripts')
<script>
function openDeliveryModal(id) {
    const modal = document.getElementById('deliveryModal');
    document.getElementById('deliveryForm').action = `/admin/stock-requests/${id}/confirm-delivery`;
    modal.classList.remove('hidden');
    modal.classList.add('flex');
}

function closeDeliveryModal() {
    const modal = document.getElementById('deliveryModal');
    modal.classList.add('hidden');
    modal.classList.remove('flex');
}

function previewDeliveryPhoto(input) {
    if (!input.files || !input.files[0]) return;

    const reader = new FileReader();
    reader.onload = e => {
        document.getElementById('deliveryPhotoPreview').src = e.target.result;
        document.getElementById('deliveryPhotoPreview').classList.remove('hidden');
        document.getElementById('deliveryUploadPlaceholder').classList.add('hidden');
    };
    reader.readAsDataURL(input.files[0]);
}
</script>
@endpush
