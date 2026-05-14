@extends('layouts.manager')

@section('content')

<div class="max-w-2xl mx-auto">

    {{-- Header --}}
    <div class="flex items-center gap-4 mb-6">
        <a href="{{ route('manager.branches.index') }}"
           class="w-10 h-10 bg-white rounded-xl flex items-center justify-center shadow-soft hover:shadow-hover smooth-transition text-gray-500 hover:text-elco-coffee">
            <i class="ph ph-arrow-left"></i>
        </a>
        <div>
            <h2 class="text-xl font-display font-bold text-gray-800">Tambah Cabang Baru</h2>
            <p class="text-sm text-gray-500">Isi data cabang ELCO yang baru</p>
        </div>
    </div>

    {{-- Form --}}
    <div class="bg-white rounded-3xl shadow-soft p-8">
        <form action="{{ route('manager.branches.store') }}" method="POST" class="space-y-6">
            @csrf

            {{-- Nama Cabang --}}
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">
                    Nama Cabang <span class="text-red-500">*</span>
                </label>
                <input type="text" name="name" value="{{ old('name') }}"
                    placeholder="contoh: ELCO Banda Aceh"
                    class="w-full px-4 py-3 rounded-2xl border border-gray-200 focus:outline-none focus:ring-2 focus:ring-elco-mocha/30 focus:border-elco-mocha text-sm smooth-transition
                    @error('name') border-red-400 @enderror">
                @error('name')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Alamat --}}
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">
                    Alamat <span class="text-red-500">*</span>
                </label>
                <textarea name="address" rows="3"
                    placeholder="Alamat lengkap cabang..."
                    class="w-full px-4 py-3 rounded-2xl border border-gray-200 focus:outline-none focus:ring-2 focus:ring-elco-mocha/30 focus:border-elco-mocha text-sm smooth-transition resize-none
                    @error('address') border-red-400 @enderror">{{ old('address') }}</textarea>
                @error('address')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Telepon --}}
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">
                    Nomor Telepon
                </label>
                <input type="text" name="phone" value="{{ old('phone') }}"
                    placeholder="contoh: 0811-2345-6789"
                    class="w-full px-4 py-3 rounded-2xl border border-gray-200 focus:outline-none focus:ring-2 focus:ring-elco-mocha/30 focus:border-elco-mocha text-sm smooth-transition">
            </div>

            {{-- Status --}}
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">
                    Status <span class="text-red-500">*</span>
                </label>
                <select name="status"
                    class="w-full px-4 py-3 rounded-2xl border border-gray-200 focus:outline-none focus:ring-2 focus:ring-elco-mocha/30 focus:border-elco-mocha text-sm smooth-transition bg-white">
                    <option value="active" {{ old('status') === 'active' ? 'selected' : '' }}>Aktif</option>
                    <option value="inactive" {{ old('status') === 'inactive' ? 'selected' : '' }}>Nonaktif</option>
                </select>
            </div>

            {{-- Tombol --}}
            <div class="flex gap-3 pt-2">
                <a href="{{ route('manager.branches.index') }}"
                   class="flex-1 text-center py-3 rounded-2xl border border-gray-200 text-sm font-medium text-gray-600 hover:bg-gray-50 smooth-transition">
                    Batal
                </a>
                <button type="submit"
                    class="flex-1 py-3 rounded-2xl bg-gradient-to-r from-elco-coffee to-elco-mocha text-white text-sm font-semibold shadow-md hover:shadow-hover smooth-transition active:scale-95">
                    <i class="ph ph-plus mr-1"></i> Simpan Cabang
                </button>
            </div>
        </form>
    </div>
    {{-- Section Admin Cabang --}}
    <div class="border-t border-gray-100 pt-6 mt-6">
        <div class="flex items-center gap-2 mb-4">
            <div class="w-8 h-8 rounded-xl bg-blue-50 text-blue-500 flex items-center justify-center">
                <i class="ph-fill ph-user-circle text-lg"></i>
            </div>
            <div>
                <p class="text-sm font-semibold text-gray-800">Akun Admin Cabang</p>
                <p class="text-xs text-gray-500">Akan dibuat otomatis saat cabang disimpan</p>
            </div>
        </div>

        {{-- Nama Admin --}}
        <div class="mb-4">
            <label class="block text-sm font-semibold text-gray-700 mb-2">
                Nama Admin <span class="text-red-500">*</span>
            </label>
            <input type="text" name="admin_name" value="{{ old('admin_name') }}"
                placeholder="contoh: Budi Santoso"
                class="w-full px-4 py-3 rounded-2xl border border-gray-200 focus:outline-none focus:ring-2 focus:ring-elco-mocha/30 focus:border-elco-mocha text-sm smooth-transition
                @error('admin_name') border-red-400 @enderror">
            @error('admin_name')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>

        {{-- Email Admin --}}
        <div class="mb-4">
            <label class="block text-sm font-semibold text-gray-700 mb-2">
                Email Admin <span class="text-red-500">*</span>
            </label>
            <input type="email" name="admin_email" value="{{ old('admin_email') }}"
                placeholder="admin@elco.com"
                class="w-full px-4 py-3 rounded-2xl border border-gray-200 focus:outline-none focus:ring-2 focus:ring-elco-mocha/30 focus:border-elco-mocha text-sm smooth-transition
                @error('admin_email') border-red-400 @enderror">
            @error('admin_email')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>

        {{-- Password Admin --}}
        <div>
            <label class="block text-sm font-semibold text-gray-700 mb-2">
                Password Admin <span class="text-red-500">*</span>
            </label>
            <input type="password" name="admin_password"
                placeholder="Minimal 8 karakter"
                class="w-full px-4 py-3 rounded-2xl border border-gray-200 focus:outline-none focus:ring-2 focus:ring-elco-mocha/30 focus:border-elco-mocha text-sm smooth-transition
                @error('admin_password') border-red-400 @enderror">
            @error('admin_password')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>
    </div>
</div>

@endsection