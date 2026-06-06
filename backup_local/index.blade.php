@extends('layouts.admin')

@section('content')

@if(session('success'))
<div class="mb-6 flex items-center gap-3 bg-emerald-50 border border-emerald-200 text-emerald-700 px-5 py-4 rounded-2xl">
    <i class="ph-fill ph-check-circle text-xl"></i> {{ session('success') }}
</div>
@endif

<div class="flex items-center justify-between mb-6">
    <div>
        <h2 class="text-xl font-display font-bold text-gray-800">Akun Kasir</h2>
        <p class="text-sm text-gray-500 mt-1">Kelola akun kasir cabang ini</p>
    </div>
    <button onclick="document.getElementById('modalTambah').classList.remove('hidden')"
        class="flex items-center gap-2 bg-gradient-to-r from-elco-coffee to-elco-mocha text-white text-sm font-semibold px-5 py-3 rounded-2xl shadow-md hover:shadow-hover smooth-transition">
        <i class="ph ph-plus"></i> Tambah Kasir
    </button>
</div>

<div class="bg-white rounded-3xl shadow-soft overflow-hidden">
    <table class="w-full text-left">
        <thead>
            <tr class="text-xs text-gray-400 border-b border-gray-100 bg-gray-50">
                <th class="py-4 px-6 font-medium">Nama</th>
                <th class="py-4 px-6 font-medium">Email</th>
                <th class="py-4 px-6 font-medium">Status</th>
                <th class="py-4 px-6 font-medium">Bergabung</th>
            </tr>
        </thead>
        <tbody>
            @forelse($kasirs as $kasir)
            <tr class="border-b border-gray-50 last:border-0 hover:bg-gray-50 smooth-transition">
                <td class="py-4 px-6">
                    <div class="flex items-center gap-3">
                        <div class="w-9 h-9 rounded-xl bg-elco-cream text-elco-coffee flex items-center justify-center font-bold text-sm">
                            {{ strtoupper(substr($kasir->name, 0, 1)) }}
                        </div>
                        <span class="text-sm font-semibold text-gray-800">{{ $kasir->name }}</span>
                    </div>
                </td>
                <td class="py-4 px-6 text-sm text-gray-600">{{ $kasir->email }}</td>
                <td class="py-4 px-6">
                    <span class="px-3 py-1 rounded-full text-xs font-medium
                        {{ $kasir->is_active ? 'bg-emerald-100 text-emerald-700' : 'bg-red-100 text-red-600' }}">
                        {{ $kasir->is_active ? 'Aktif' : 'Nonaktif' }}
                    </span>
                </td>
                <td class="py-4 px-6 text-xs text-gray-500">{{ $kasir->created_at->format('d M Y') }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="4" class="py-12 text-center text-gray-400">
                    <i class="ph ph-users text-4xl block mb-2"></i>
                    Belum ada akun kasir
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

{{-- Modal Tambah Kasir --}}
<div id="modalTambah" class="hidden fixed inset-0 bg-black/40 backdrop-blur-sm z-50 flex items-center justify-center">
    <div class="bg-white rounded-3xl shadow-2xl p-8 w-full max-w-md mx-4">
        <h3 class="font-display font-bold text-gray-800 text-lg mb-5">Tambah Akun Kasir</h3>
        <form action="{{ route('admin.kasirs.store') }}" method="POST" class="space-y-4">
            @csrf
            <div>
                <label class="block text-xs font-semibold text-gray-600 mb-1.5">Nama Kasir *</label>
                <input type="text" name="kasir_name" placeholder="Nama lengkap"
                    class="w-full px-3 py-2.5 rounded-xl border border-gray-200 focus:outline-none focus:ring-2 focus:ring-elco-mocha/30 text-sm">
            </div>
            <div>
                <label class="block text-xs font-semibold text-gray-600 mb-1.5">Email *</label>
                <input type="email" name="kasir_email" placeholder="nama@elco.com"
                    class="w-full px-3 py-2.5 rounded-xl border border-gray-200 focus:outline-none focus:ring-2 focus:ring-elco-mocha/30 text-sm">
            </div>
            <div>
                <label class="block text-xs font-semibold text-gray-600 mb-1.5">Password *</label>
                <input type="password" name="kasir_password" placeholder="Minimal 8 karakter"
                    class="w-full px-3 py-2.5 rounded-xl border border-gray-200 focus:outline-none focus:ring-2 focus:ring-elco-mocha/30 text-sm">
            </div>
            <div class="flex gap-3 pt-2">
                <button type="button" onclick="document.getElementById('modalTambah').classList.add('hidden')"
                    class="flex-1 py-2.5 rounded-xl border border-gray-200 text-sm text-gray-600 hover:bg-gray-50">
                    Batal
                </button>
                <button type="submit"
                    class="flex-1 py-2.5 rounded-xl bg-gradient-to-r from-elco-coffee to-elco-mocha text-white text-sm font-semibold">
                    Buat Akun
                </button>
            </div>
        </form>
    </div>
</div>

@endsection