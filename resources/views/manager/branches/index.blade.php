@extends('layouts.manager')

@section('content')

{{-- Alert Success --}}
@if(session('success'))
<div class="mb-6 flex items-center gap-3 bg-emerald-50 border border-emerald-200 text-emerald-700 px-5 py-4 rounded-2xl">
    <i class="ph-fill ph-check-circle text-xl"></i>
    {{ session('success') }}
</div>
@endif

{{-- Header --}}
<div class="flex items-center justify-between mb-6">
    <div>
        <h2 class="text-xl font-display font-bold text-gray-800">Manajemen Cabang</h2>
        <p class="text-sm text-gray-500 mt-1">Kelola seluruh cabang ELCO</p>
    </div>
    <a href="{{ route('manager.branches.create') }}"
       class="flex items-center gap-2 bg-gradient-to-r from-elco-coffee to-elco-mocha text-white text-sm font-semibold px-5 py-3 rounded-2xl shadow-md hover:shadow-hover smooth-transition active:scale-95">
        <i class="ph ph-plus"></i> Tambah Cabang
    </a>
</div>

{{-- Tabel --}}
<div class="bg-white rounded-3xl shadow-soft overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="text-xs text-gray-400 border-b border-gray-100 bg-gray-50">
                    <th class="py-4 px-6 font-medium">Nama Cabang</th>
                    <th class="py-4 px-6 font-medium">Alamat</th>
                    <th class="py-4 px-6 font-medium">Telepon</th>
                    <th class="py-4 px-6 font-medium">Status</th>
                    <th class="py-4 px-6 font-medium">Dibuat</th>
                    <th class="py-4 px-6 font-medium">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($branches as $branch)
                <tr class="group border-b border-gray-50 last:border-0
                    {{ $branch->trashed() ? 'opacity-50 bg-gray-50' : 'hover:bg-gray-50' }}
                    smooth-transition">

                    {{-- Nama --}}
                    <td class="py-4 px-6">
                        <div class="flex items-center gap-3">
                            <div class="w-9 h-9 rounded-xl
                                {{ $branch->trashed() ? 'bg-gray-100 text-gray-400' : 'bg-orange-50 text-orange-500' }}
                                flex items-center justify-center">
                                <i class="ph-fill ph-storefront"></i>
                            </div>
                            <div>
                                <p class="text-sm font-semibold text-gray-800">{{ $branch->name }}</p>
                                @if($branch->trashed())
                                    <span class="text-xs text-red-400">Dihapus</span>
                                @endif
                            </div>
                        </div>
                    </td>

                    {{-- Alamat --}}
                    <td class="py-4 px-6 text-sm text-gray-600 max-w-xs truncate">
                        {{ $branch->address }}
                    </td>

                    {{-- Telepon --}}
                    <td class="py-4 px-6 text-sm text-gray-600">
                        {{ $branch->phone ?? '-' }}
                    </td>

                    {{-- Status --}}
                    <td class="py-4 px-6">
                        @if($branch->trashed())
                            <span class="px-3 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-500">
                                Dihapus
                            </span>
                        @elseif($branch->status === 'active')
                            <span class="px-3 py-1 rounded-full text-xs font-medium bg-emerald-100 text-emerald-700">
                                ● Aktif
                            </span>
                        @else
                            <span class="px-3 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-700">
                                ● Nonaktif
                            </span>
                        @endif
                    </td>

                    {{-- Dibuat --}}
                    <td class="py-4 px-6 text-sm text-gray-500">
                        {{ $branch->created_at->format('d M Y') }}
                    </td>

                    {{-- Aksi --}}
                    <td class="py-4 px-6">
                        @if($branch->trashed())
                            {{-- Restore --}}
                            <form action="{{ route('manager.branches.restore', $branch->id) }}"
                                  method="POST" class="inline">
                                @csrf
                                <button type="submit"
                                    class="text-xs font-medium text-emerald-600 bg-emerald-50 px-3 py-2 rounded-xl hover:bg-emerald-100 smooth-transition">
                                    <i class="ph ph-arrow-counter-clockwise"></i> Pulihkan
                                </button>
                            </form>
                        @else
                            <div class="flex items-center gap-2">
                                {{-- Edit --}}
                                <a href="{{ route('manager.branches.edit', $branch) }}"
                                   class="text-xs font-medium text-elco-coffee bg-elco-cream px-3 py-2 rounded-xl hover:bg-elco-latte/30 smooth-transition">
                                    <i class="ph ph-pencil"></i> Edit
                                </a>
                                {{-- Hapus --}}
                                <form id="form-hapus-{{ $branch->id }}" method="POST"
                                    action="{{ route('manager.branches.destroy', $branch->id) }}">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button"
                                        onclick="elcoConfirm({
                                            title: 'Hapus Cabang?',
                                            text: 'Cabang {{ addslashes($branch->name) }} akan dinonaktifkan.',
                                            confirmText: 'Ya, Hapus',
                                            confirmColor: '#ef4444',
                                            icon: 'warning',
                                            onConfirm: () => document.getElementById('form-hapus-{{ $branch->id }}').submit()
                                        })"
                                        class="text-xs font-medium text-red-500 bg-red-50 px-3 py-2 rounded-xl hover:bg-red-100 smooth-transition">
                                        <i class="ph ph-trash"></i> Hapus
                                    </button>
                                </form>
                            </div>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="py-12 text-center">
                        <i class="ph ph-storefront text-4xl text-gray-300 block mb-2"></i>
                        <p class="text-gray-400 text-sm">Belum ada cabang. Tambahkan cabang pertama!</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@endsection