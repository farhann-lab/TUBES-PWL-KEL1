@extends('layouts.admin')

@section('content')

@if(session('success'))
<div class="mb-6 flex items-center gap-3 rounded-2xl border border-emerald-200 bg-emerald-50 px-5 py-4 text-emerald-700">
    <i class="ph-fill ph-check-circle text-xl"></i>
    <span class="text-sm font-medium">{{ session('success') }}</span>
</div>
@endif

<div class="mb-6 flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
    <div>
        <h2 class="font-display text-xl font-bold text-gray-800">Kelola Kasir</h2>
        <p class="mt-1 text-sm text-gray-500">Buat dan pantau akun kasir untuk cabang kamu</p>
    </div>
    <button type="button" onclick="openKasirModal()"
        class="inline-flex items-center justify-center gap-2 rounded-2xl bg-gradient-to-r from-elco-coffee to-elco-mocha px-5 py-3 text-sm font-semibold text-white shadow-md smooth-transition hover:shadow-hover active:scale-95">
        <i class="ph ph-plus"></i> Tambah Kasir
    </button>
</div>

<div class="mb-6 grid grid-cols-1 gap-4 md:grid-cols-3">
    <div class="rounded-3xl bg-white p-5 shadow-soft">
        <div class="mb-3 flex h-10 w-10 items-center justify-center rounded-xl bg-emerald-50 text-emerald-500">
            <i class="ph-fill ph-users-three text-xl"></i>
        </div>
        <p class="text-xs text-gray-500">Total Kasir</p>
        <p class="mt-1 font-display text-2xl font-bold text-gray-800">{{ $kasirs->count() }}</p>
    </div>
    <div class="rounded-3xl bg-white p-5 shadow-soft">
        <div class="mb-3 flex h-10 w-10 items-center justify-center rounded-xl bg-blue-50 text-blue-500">
            <i class="ph-fill ph-storefront text-xl"></i>
        </div>
        <p class="text-xs text-gray-500">Cabang</p>
        <p class="mt-1 truncate font-display text-lg font-bold text-gray-800">{{ auth()->user()->branch?->name ?? '-' }}</p>
    </div>
    <div class="rounded-3xl bg-white p-5 shadow-soft">
        <div class="mb-3 flex h-10 w-10 items-center justify-center rounded-xl bg-orange-50 text-orange-500">
            <i class="ph-fill ph-shield-check text-xl"></i>
        </div>
        <p class="text-xs text-gray-500">Domain Email</p>
        <p class="mt-1 font-display text-lg font-bold text-gray-800">@elco.com</p>
    </div>
</div>

<div class="overflow-hidden rounded-3xl bg-white shadow-soft">
    <div class="overflow-x-auto">
        <table class="w-full min-w-[720px] text-left">
            <thead>
                <tr class="border-b border-gray-100 bg-gray-50 text-xs text-gray-400">
                    <th class="px-6 py-4 font-medium">Kasir</th>
                    <th class="px-6 py-4 font-medium">Email</th>
                    <th class="px-6 py-4 font-medium">Status</th>
                    <th class="px-6 py-4 font-medium">Dibuat</th>
                </tr>
            </thead>
            <tbody>
                @forelse($kasirs as $kasir)
                <tr class="border-b border-gray-50 last:border-0 smooth-transition hover:bg-gray-50">
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-3">
                            <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-emerald-50 font-bold text-emerald-500">
                                {{ strtoupper(substr($kasir->name, 0, 1)) }}
                            </div>
                            <div>
                                <p class="text-sm font-semibold text-gray-800">{{ $kasir->name }}</p>
                                <p class="text-xs text-gray-400">Kasir cabang</p>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-600">{{ $kasir->email }}</td>
                    <td class="px-6 py-4">
                        <span class="inline-flex items-center gap-1.5 rounded-full bg-emerald-100 px-3 py-1 text-xs font-semibold text-emerald-700">
                            <span class="h-1.5 w-1.5 rounded-full bg-current"></span>
                            Aktif
                        </span>
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-500">{{ $kasir->created_at?->format('d M Y') ?? '-' }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="px-6 py-14 text-center text-gray-400">
                        <i class="ph ph-user-plus mb-2 block text-4xl"></i>
                        <p class="text-sm">Belum ada kasir untuk cabang ini</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div id="kasirModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/40 px-4 backdrop-blur-sm">
    <div class="w-full max-w-md rounded-3xl bg-white p-8 shadow-2xl">
        <div class="mb-5 flex items-center justify-between">
            <div>
                <h3 class="font-display text-lg font-bold text-gray-800">Tambah Kasir</h3>
                <p class="mt-1 text-sm text-gray-500">Akun dibuat untuk cabang kamu</p>
            </div>
            <button type="button" onclick="closeKasirModal()"
                class="flex h-9 w-9 items-center justify-center rounded-xl bg-gray-100 text-gray-500 smooth-transition hover:bg-gray-200">
                <i class="ph ph-x"></i>
            </button>
        </div>

        <form action="{{ route('admin.kasirs.store') }}" method="POST" class="space-y-4">
            @csrf
            <div>
                <label class="mb-2 block text-sm font-semibold text-gray-700">Nama Kasir <span class="text-red-500">*</span></label>
                <input type="text" name="kasir_name" value="{{ old('kasir_name') }}" required
                    placeholder="contoh: Siti Aminah"
                    class="w-full rounded-2xl border border-gray-200 px-4 py-3 text-sm smooth-transition focus:border-elco-mocha focus:outline-none focus:ring-2 focus:ring-elco-mocha/30">
            </div>
            <div>
                <label class="mb-2 block text-sm font-semibold text-gray-700">Email Kasir <span class="text-red-500">*</span></label>
                <input type="email" name="kasir_email" value="{{ old('kasir_email') }}" required
                    placeholder="kasir@elco.com"
                    class="w-full rounded-2xl border border-gray-200 px-4 py-3 text-sm smooth-transition focus:border-elco-mocha focus:outline-none focus:ring-2 focus:ring-elco-mocha/30">
            </div>
            <div>
                <label class="mb-2 block text-sm font-semibold text-gray-700">Password <span class="text-red-500">*</span></label>
                <input type="password" name="kasir_password" required minlength="8"
                    placeholder="Minimal 8 karakter"
                    class="w-full rounded-2xl border border-gray-200 px-4 py-3 text-sm smooth-transition focus:border-elco-mocha focus:outline-none focus:ring-2 focus:ring-elco-mocha/30">
            </div>
            <div class="flex gap-3 pt-2">
                <button type="button" onclick="closeKasirModal()"
                    class="flex-1 rounded-2xl border border-gray-200 py-3 text-sm font-medium text-gray-600 smooth-transition hover:bg-gray-50">
                    Batal
                </button>
                <button type="submit"
                    class="flex-1 rounded-2xl bg-gradient-to-r from-elco-coffee to-elco-mocha py-3 text-sm font-semibold text-white shadow-md smooth-transition hover:shadow-hover">
                    Simpan
                </button>
            </div>
        </form>
    </div>
</div>

@endsection

@push('scripts')
<script>
function openKasirModal() {
    document.getElementById('kasirModal').classList.remove('hidden');
    document.getElementById('kasirModal').classList.add('flex');
}

function closeKasirModal() {
    document.getElementById('kasirModal').classList.add('hidden');
    document.getElementById('kasirModal').classList.remove('flex');
}

@if($errors->has('kasir_name') || $errors->has('kasir_email') || $errors->has('kasir_password'))
document.addEventListener('DOMContentLoaded', openKasirModal);
@endif
</script>
@endpush
