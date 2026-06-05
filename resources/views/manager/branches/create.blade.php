@extends('layouts.manager')

@section('content')
<div class="max-w-2xl mx-auto">

    <div class="flex items-center gap-4 mb-6">
        <a href="{{ route('manager.branches.index') }}"
           class="w-10 h-10 bg-white rounded-xl flex items-center justify-center shadow-soft hover:shadow-hover smooth-transition">
            <i class="ph ph-arrow-left text-gray-500"></i>
        </a>
        <div>
            <h2 class="text-xl font-display font-bold text-gray-800">Buka Cabang Baru</h2>
            <p class="text-sm text-gray-500">3 langkah untuk membuka cabang</p>
        </div>
    </div>

    {{-- Step Indicator --}}
    <div class="flex items-center gap-2 mb-8">
        @foreach([['1','Data Cabang'],['2','Akun Admin'],['3','Stok Awal']] as $i => [$num, $label])
        <div class="flex items-center gap-2 flex-1">
            <div id="step-dot-{{ $num }}"
                class="w-8 h-8 rounded-full flex items-center justify-center text-sm font-bold smooth-transition
                {{ $num === '1' ? 'bg-elco-coffee text-white' : 'bg-gray-200 text-gray-500' }}">
                {{ $num }}
            </div>
            <span id="step-label-{{ $num }}"
                class="text-xs font-medium smooth-transition
                {{ $num === '1' ? 'text-elco-coffee' : 'text-gray-400' }}">
                {{ $label }}
            </span>
            @if($i < 2)
            <div id="step-line-{{ $num }}" class="flex-1 h-0.5 smooth-transition
                {{ 'bg-gray-200' }}"></div>
            @endif
        </div>
        @endforeach
    </div>

    <form action="{{ route('manager.branches.store') }}" method="POST" id="branchForm">
        @csrf

        {{-- STEP 1: Data Cabang --}}
        <div id="step1" class="bg-white rounded-3xl shadow-soft p-8 space-y-5">
            <h3 class="font-display font-semibold text-gray-800 text-lg">Data Cabang</h3>

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Nama Cabang *</label>
                <input type="text" name="name" value="{{ old('name') }}" placeholder="contoh: Elco Coffee - Medan Kota"
                    class="w-full px-4 py-3 rounded-2xl border border-gray-200 focus:outline-none focus:ring-2 focus:ring-elco-mocha/30 text-sm">
            </div>
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Alamat *</label>
                <textarea name="address" rows="3" placeholder="Alamat lengkap cabang..."
                    class="w-full px-4 py-3 rounded-2xl border border-gray-200 focus:outline-none focus:ring-2 focus:ring-elco-mocha/30 text-sm resize-none">{{ old('address') }}</textarea>
            </div>
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">No. Telepon</label>
                <input type="text" name="phone" value="{{ old('phone') }}" placeholder="contoh: 0812xxxxxxxx"
                    class="w-full px-4 py-3 rounded-2xl border border-gray-200 focus:outline-none focus:ring-2 focus:ring-elco-mocha/30 text-sm">
            </div>
            <input type="hidden" name="status" value="active">

            <button type="button" onclick="goStep(2)"
                class="w-full py-3 rounded-2xl bg-gradient-to-r from-elco-coffee to-elco-mocha text-white text-sm font-semibold shadow-md hover:shadow-hover smooth-transition">
                Lanjut → Akun Admin
            </button>
        </div>

        {{-- STEP 2: Akun Admin --}}
        <div id="step2" class="hidden bg-white rounded-3xl shadow-soft p-8 space-y-5">
            <h3 class="font-display font-semibold text-gray-800 text-lg">Akun Admin Cabang</h3>

            <div class="flex items-start gap-3 bg-blue-50 border border-blue-100 rounded-2xl p-4">
                <i class="ph ph-info text-blue-500 text-xl mt-0.5"></i>
                <p class="text-sm text-blue-600">Email harus menggunakan domain <strong>@elco.com</strong></p>
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Nama Admin *</label>
                <input type="text" name="admin_name" value="{{ old('admin_name') }}" placeholder="Nama lengkap admin"
                    class="w-full px-4 py-3 rounded-2xl border border-gray-200 focus:outline-none focus:ring-2 focus:ring-elco-mocha/30 text-sm">
            </div>
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Email Admin *</label>
                <input type="email" name="admin_email" id="adminEmail" value="{{ old('admin_email') }}" placeholder="nama@elco.com"
                    pattern="^[A-Za-z0-9._%+\-]+@elco\.com$"
                    title="Email admin harus menggunakan domain @elco.com"
                    oninput="validateAdminEmail()"
                    class="w-full px-4 py-3 rounded-2xl border border-gray-200 focus:outline-none focus:ring-2 focus:ring-elco-mocha/30 text-sm">
                <p id="adminEmailError" class="mt-1 hidden text-xs text-red-500">
                    Email admin harus menggunakan domain @elco.com.
                </p>
                @error('admin_email')
                    <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                @enderror
            </div>
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Password Admin *</label>
                <input type="password" name="admin_password" placeholder="Minimal 8 karakter"
                    class="w-full px-4 py-3 rounded-2xl border border-gray-200 focus:outline-none focus:ring-2 focus:ring-elco-mocha/30 text-sm">
            </div>

            <div class="flex gap-3">
                <button type="button" onclick="goStep(1)"
                    class="flex-1 py-3 rounded-2xl border border-gray-200 text-sm font-medium text-gray-600 hover:bg-gray-50 smooth-transition">
                    ← Kembali
                </button>
                <button type="button" id="adminNextBtn" onclick="goStep(3)"
                    class="flex-1 py-3 rounded-2xl bg-gradient-to-r from-elco-coffee to-elco-mocha text-white text-sm font-semibold smooth-transition disabled:cursor-not-allowed disabled:opacity-50">
                    Lanjut → Stok Awal
                </button>
            </div>
        </div>

        {{-- STEP 3: Stok Awal --}}
        <div id="step3" class="hidden bg-white rounded-3xl shadow-soft p-8 space-y-5">
            <h3 class="font-display font-semibold text-gray-800 text-lg">Stok Awal Cabang</h3>

            <div class="flex items-start gap-3 bg-amber-50 border border-amber-100 rounded-2xl p-4">
                <i class="ph ph-info text-amber-500 text-xl mt-0.5"></i>
                <div class="text-sm text-amber-700">
                    <p><strong>Minuman</strong> — isi stok bahan baku (gram/ml)</p>
                    <p class="mt-1"><strong>Makanan/Snack</strong> — isi stok produk jadi (pcs)</p>
                    <p class="mt-1">Kosongkan jika akan diisi nanti via Pengajuan Stok.</p>
                </div>
            </div>

            {{-- Stok Bahan Baku --}}
            @if(isset($ingredients) && $ingredients->count())
            <div>
                <p class="text-sm font-semibold text-gray-700 mb-3">☕ Bahan Baku Minuman</p>
                <div class="space-y-2 max-h-48 overflow-y-auto pr-1">
                    @foreach($ingredients as $ing)
                    <div class="flex items-center gap-3">
                        <label class="text-sm text-gray-700 flex-1">{{ $ing->nama_bahan }}</label>
                        <input type="number" name="stok_bahan[{{ $ing->id }}]" min="0" step="0.1"
                            placeholder="0"
                            class="w-28 px-3 py-2 rounded-xl border border-gray-200 text-sm focus:outline-none focus:ring-2 focus:ring-elco-mocha/30">
                        <span class="text-xs text-gray-400 w-10">{{ $ing->satuan }}</span>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif

            {{-- Stok Makanan/Snack --}}
            @if(isset($menusMakanan) && $menusMakanan->count())
            <div>
                <p class="text-sm font-semibold text-gray-700 mb-3">🍰 Makanan & Snack</p>
                <div class="space-y-2 max-h-48 overflow-y-auto pr-1">
                    @foreach($menusMakanan as $menu)
                    <div class="flex items-center gap-3">
                        <label class="text-sm text-gray-700 flex-1">{{ $menu->name }}</label>
                        <input type="number" name="stok_makanan[{{ $menu->id }}]" min="0"
                            placeholder="0"
                            class="w-28 px-3 py-2 rounded-xl border border-gray-200 text-sm focus:outline-none focus:ring-2 focus:ring-elco-mocha/30">
                        <span class="text-xs text-gray-400 w-10">pcs</span>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif

            <div class="flex gap-3 pt-2">
                <button type="button" onclick="goStep(2)"
                    class="flex-1 py-3 rounded-2xl border border-gray-200 text-sm font-medium text-gray-600 hover:bg-gray-50 smooth-transition">
                    ← Kembali
                </button>
                <button type="submit"
                    class="flex-1 py-3 rounded-2xl bg-gradient-to-r from-elco-coffee to-elco-mocha text-white text-sm font-semibold shadow-md hover:shadow-hover smooth-transition">
                    <i class="ph ph-check mr-1"></i> Buka Cabang
                </button>
            </div>
        </div>

    </form>
</div>
@endsection

@push('scripts')
<script>
function validateAdminEmail() {
    const input = document.getElementById('adminEmail');
    const error = document.getElementById('adminEmailError');
    const nextButton = document.getElementById('adminNextBtn');
    const value = input.value.trim();
    const isValid = /^[A-Za-z0-9._%+\-]+@elco\.com$/i.test(value);
    const shouldShowError = value.length > 0 && !isValid;

    input.classList.toggle('border-red-400', shouldShowError);
    error.classList.toggle('hidden', !shouldShowError);

    if (nextButton) {
        nextButton.disabled = !isValid;
    }

    return isValid;
}

function goStep(n) {
    if (n === 3 && !validateAdminEmail()) {
        document.getElementById('adminEmail').focus();
        return;
    }

    [1, 2, 3].forEach(i => {
        document.getElementById('step' + i).classList.add('hidden');
        document.getElementById('step-dot-' + i).className =
            'w-8 h-8 rounded-full flex items-center justify-center text-sm font-bold smooth-transition bg-gray-200 text-gray-500';
        document.getElementById('step-label-' + i).className =
            'text-xs font-medium smooth-transition text-gray-400';
        if (i < 3) document.getElementById('step-line-' + i).className =
            'flex-1 h-0.5 smooth-transition bg-gray-200';
    });
    document.getElementById('step' + n).classList.remove('hidden');
    document.getElementById('step-dot-' + n).className =
        'w-8 h-8 rounded-full flex items-center justify-center text-sm font-bold smooth-transition bg-elco-coffee text-white';
    document.getElementById('step-label-' + n).className =
        'text-xs font-medium smooth-transition text-elco-coffee';
    for (let i = 1; i < n; i++) {
        document.getElementById('step-line-' + i).className =
            'flex-1 h-0.5 smooth-transition bg-elco-coffee';
        document.getElementById('step-dot-' + i).className =
            'w-8 h-8 rounded-full flex items-center justify-center text-sm font-bold smooth-transition bg-emerald-500 text-white';
    }
}

document.addEventListener('DOMContentLoaded', validateAdminEmail);
</script>
@endpush