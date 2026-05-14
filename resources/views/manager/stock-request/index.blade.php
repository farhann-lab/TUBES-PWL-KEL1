@extends('layouts.manager')

@section('content')

@if(session('success'))
<div class="mb-6 flex items-center gap-3 bg-emerald-50 border border-emerald-200 text-emerald-700 px-5 py-4 rounded-2xl">
    <i class="ph-fill ph-check-circle text-xl"></i> {{ session('success') }}
</div>
@endif

<div class="mb-6">
    <h2 class="text-xl font-display font-bold text-gray-800">Verifikasi Pengajuan</h2>
    <p class="text-sm text-gray-500 mt-1">Pengajuan stok & alat dari semua cabang</p>
</div>

{{-- Filter Status --}}
<div class="flex gap-2 mb-6">
    @foreach(['semua' => 'Semua', 'pending' => '⏳ Pending', 'approved' => '✅ Disetujui', 'rejected' => '❌ Ditolak'] as $val => $label)
    <button onclick="filterStatus('{{ $val }}')" id="flt-{{ $val }}"
        class="px-4 py-2 rounded-xl text-sm font-medium smooth-transition
        {{ $val === 'semua' ? 'bg-elco-coffee text-white shadow-md' : 'bg-white text-gray-500 shadow-soft hover:bg-gray-50' }}">
        {{ $label }}
    </button>
    @endforeach
</div>

<div class="bg-white rounded-3xl shadow-soft overflow-hidden">
    <table class="w-full text-left">
        <thead>
            <tr class="text-xs text-gray-400 border-b border-gray-100 bg-gray-50">
                <th class="py-4 px-6 font-medium">Item</th>
                <th class="py-4 px-6 font-medium">Cabang</th>
                <th class="py-4 px-6 font-medium">Tipe</th>
                <th class="py-4 px-6 font-medium">Jumlah</th>
                <th class="py-4 px-6 font-medium">Status</th>
                <th class="py-4 px-6 font-medium">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($requests as $req)
            <tr class="request-row border-b border-gray-50 last:border-0 hover:bg-gray-50 smooth-transition"
                data-status="{{ $req->status }}">
                <td class="py-4 px-6">
                    <p class="text-sm font-semibold text-gray-800">{{ $req->item_name }}</p>
                    <p class="text-xs text-gray-400">{{ $req->created_at->format('d M Y') }}</p>
                </td>
                <td class="py-4 px-6 text-sm text-gray-600">
                    {{ $req->branch->name }}
                </td>
                <td class="py-4 px-6">
                    <span class="px-3 py-1 rounded-full text-xs font-medium
                        {{ $req->type === 'stock' ? 'bg-blue-50 text-blue-600' : 'bg-purple-50 text-purple-600' }}">
                        {{ $req->type === 'stock' ? '📦 Stok' : '🔧 Operasional' }}
                    </span>
                </td>
                <td class="py-4 px-6 text-sm font-medium text-gray-700">
                    {{ $req->quantity }} {{ $req->unit }}
                </td>
                <td class="py-4 px-6">
                    <span class="px-3 py-1 rounded-full text-xs font-medium
                        {{ $req->status === 'pending'  ? 'bg-yellow-100 text-yellow-700' : '' }}
                        {{ $req->status === 'approved' ? 'bg-emerald-100 text-emerald-700' : '' }}
                        {{ $req->status === 'rejected' ? 'bg-red-100 text-red-700' : '' }}">
                        {{ $req->status === 'pending' ? '⏳ Pending' : ($req->status === 'approved' ? '✅ Disetujui' : '❌ Ditolak') }}
                    </span>
                </td>
                <td class="py-4 px-6">
                    @if($req->status === 'pending')
                    <div class="flex gap-2">
                        {{-- Approve --}}
                        <form id="app-{{ $req->id }}"
                            action="{{ route('manager.stock-requests.approve', $req) }}" method="POST">
                            @csrf
                            <button type="button"
                                onclick="elcoConfirm({
                                    title: 'Setujui Pengajuan?',
                                    text: 'Stok {{ addslashes($req->item_name) }} akan bertambah {{ $req->quantity }} {{ $req->unit }} di cabang {{ addslashes($req->branch->name) }}.',
                                    confirmText: 'Ya, Setujui',
                                    confirmColor: '#10b981',
                                    icon: 'question',
                                    onConfirm: () => document.getElementById('app-{{ $req->id }}').submit()
                                })"
                                class="text-xs font-medium text-emerald-600 bg-emerald-50 px-3 py-2 rounded-xl hover:bg-emerald-100 smooth-transition">
                                <i class="ph ph-check"></i> Setujui
                            </button>
                        </form>

                        {{-- Reject --}}
                        <button onclick="openRejectModal({{ $req->id }})"
                            class="text-xs font-medium text-red-500 bg-red-50 px-3 py-2 rounded-xl hover:bg-red-100 smooth-transition">
                            <i class="ph ph-x"></i> Tolak
                        </button>
                    </div>
                    @else
                        <span class="text-xs text-gray-400">Sudah diproses</span>
                    @endif
                </td>
                {{-- Tambah kolom setelah Status --}}
                <th class="py-4 px-6 font-medium">Pengiriman</th>

                {{-- Di dalam foreach, tambah: --}}
                <td class="py-4 px-6">
                    @if($req->status === 'approved')
                        @if($req->delivery_status === 'waiting')
                            <span class="px-3 py-1 rounded-full text-xs font-medium bg-orange-100 text-orange-700">
                                🚚 Menunggu Barang
                            </span>
                        @elseif($req->delivery_status === 'delivered')
                            <div class="space-y-2">
                                <span class="px-3 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-700 block w-fit">
                                    📦 Barang Sampai
                                </span>
                                {{-- Preview foto --}}
                                @if($req->delivery_photo)
                                <a href="{{ Storage::url($req->delivery_photo) }}" target="_blank"
                                class="text-xs text-elco-coffee underline">Lihat Foto</a>
                                @endif
                                <p class="text-xs text-gray-500">{{ $req->delivery_note }}</p>
                                {{-- Tombol Final Confirm --}}
                                <form id="confirm-{{ $req->id }}"
                                    action="{{ route('manager.stock-requests.confirm-delivery', $req) }}" method="POST">
                                    @csrf
                                    <button type="button"
                                        onclick="elcoConfirm({
                                            title: 'Konfirmasi Penerimaan?',
                                            text: 'Stok akan bertambah setelah dikonfirmasi.',
                                            confirmText: 'Ya, Konfirmasi',
                                            confirmColor: '#10b981',
                                            icon: 'question',
                                            onConfirm: () => document.getElementById('confirm-{{ $req->id }}').submit()
                                        })"
                                        class="text-xs font-medium text-emerald-600 bg-emerald-50 px-3 py-2 rounded-xl hover:bg-emerald-100 smooth-transition">
                                        <i class="ph ph-check-circle"></i> Konfirmasi & Tambah Stok
                                    </button>
                                </form>
                            </div>
                        @elseif($req->delivery_status === 'confirmed')
                            <span class="px-3 py-1 rounded-full text-xs font-medium bg-purple-100 text-purple-700">
                                ✅ Stok Bertambah
                            </span>
                        @endif
                    @else
                        <span class="text-xs text-gray-400">—</span>
                    @endif
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" class="py-12 text-center text-gray-400">
                    <i class="ph ph-clipboard-text text-4xl block mb-2"></i>
                    Tidak ada pengajuan
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

{{-- Modal Reject --}}
<div id="rejectModal" class="hidden fixed inset-0 bg-black/40 backdrop-blur-sm z-50 flex items-center justify-center">
    <div class="bg-white rounded-3xl shadow-2xl p-8 w-full max-w-md mx-4">
        <h3 class="font-display font-bold text-gray-800 text-lg mb-2">Tolak Pengajuan</h3>
        <p class="text-sm text-gray-500 mb-6">Berikan alasan penolakan agar admin cabang dapat memahami keputusan ini.</p>
        <form id="rejectForm" method="POST" class="space-y-4">
            @csrf
            <textarea name="rejection_note" rows="4" required
                placeholder="Tulis alasan penolakan..."
                class="w-full px-4 py-3 rounded-2xl border border-gray-200 focus:outline-none focus:ring-2 focus:ring-red-300 text-sm resize-none"></textarea>
            <div class="flex gap-3">
                <button type="button" onclick="closeRejectModal()"
                    class="flex-1 py-3 rounded-2xl border border-gray-200 text-sm font-medium text-gray-600 hover:bg-gray-50 smooth-transition">
                    Batal
                </button>
                <button type="submit"
                    class="flex-1 py-3 rounded-2xl bg-red-500 text-white text-sm font-semibold hover:bg-red-600 smooth-transition">
                    Konfirmasi Tolak
                </button>
            </div>
        </form>
    </div>
</div>

@endsection

@push('scripts')
<script>
// Filter status
function filterStatus(status) {
    document.querySelectorAll('[id^="flt-"]').forEach(btn => {
        btn.classList.remove('bg-elco-coffee', 'text-white', 'shadow-md');
        btn.classList.add('bg-white', 'text-gray-500', 'shadow-soft');
    });
    document.getElementById('flt-' + status).classList.add('bg-elco-coffee', 'text-white', 'shadow-md');
    document.getElementById('flt-' + status).classList.remove('bg-white', 'text-gray-500');

    document.querySelectorAll('.request-row').forEach(row => {
        row.style.display = (status === 'semua' || row.dataset.status === status) ? '' : 'none';
    });
}

// Modal reject
function openRejectModal(id) {
    document.getElementById('rejectForm').action = `/manager/stock-requests/${id}/reject`;
    document.getElementById('rejectModal').classList.remove('hidden');
}

function closeRejectModal() {
    document.getElementById('rejectModal').classList.add('hidden');
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