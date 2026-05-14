@extends('layouts.kasir')

@section('content')

<div class="space-y-6">

    <!-- HEADER -->
    <div class="flex items-center justify-between">
        <h2 class="text-2xl font-bold text-gray-800">
            Dashboard Kasir
        </h2>

        <p class="text-sm text-gray-500">
            {{ now()->format('d M Y') }}
        </p>
    </div>

    <!-- STAT -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

        <!-- MENU -->
        <div class="bg-white p-6 rounded-3xl shadow-soft">

            <div class="flex items-center gap-4">

                <div class="w-14 h-14 rounded-2xl bg-orange-100 flex items-center justify-center">
                    <i class="ph-fill ph-coffee text-3xl text-orange-500"></i>
                </div>

                <div>
                    <p class="text-sm text-gray-500">
                        Menu Tersedia
                    </p>

                    <h3 class="text-2xl font-bold text-gray-800">
                        {{ $data['available_menus']->count() }}
                    </h3>
                </div>

            </div>

        </div>

        <!-- TRANSAKSI -->
        <div class="bg-white p-6 rounded-3xl shadow-soft">

            <div class="flex items-center gap-4">

                <div class="w-14 h-14 rounded-2xl bg-emerald-100 flex items-center justify-center">
                    <i class="ph-fill ph-receipt text-3xl text-emerald-600"></i>
                </div>

                <div>
                    <p class="text-sm text-gray-500">
                        Transaksi Hari Ini
                    </p>

                    <h3 class="text-2xl font-bold text-gray-800">
                        {{ $data['today_transactions'] }}
                    </h3>
                </div>

            </div>

        </div>

        <!-- TOTAL -->
        <div class="bg-white p-6 rounded-3xl shadow-soft">

            <div class="flex items-center gap-4">

                <div class="w-14 h-14 rounded-2xl bg-blue-100 flex items-center justify-center">
                    <i class="ph-fill ph-wallet text-3xl text-blue-600"></i>
                </div>

                <div>
                    <p class="text-sm text-gray-500">
                        Total Penjualan
                    </p>

                    <h3 class="text-xl font-bold text-gray-800">
                        Rp {{ number_format($data['today_total'], 0, ',', '.') }}
                    </h3>
                </div>

            </div>

        </div>

    </div>

    <!-- POS -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        <!-- MENU -->
        <div class="lg:col-span-2 bg-white p-6 rounded-3xl shadow-soft">

            <div class="flex items-center justify-between mb-6">

                <h2 class="text-xl font-bold text-gray-800">
                    Menu Café
                </h2>

            </div>

            <div class="grid grid-cols-2 md:grid-cols-3 gap-4">

                @forelse($data['available_menus'] as $item)

                <div class="border border-gray-100 rounded-2xl p-4 hover:shadow-lg transition">

                    <div class="w-12 h-12 rounded-xl bg-orange-100 flex items-center justify-center mb-4">
                        <i class="ph-fill ph-coffee text-2xl text-orange-500"></i>
                    </div>

                    <h3 class="font-bold text-gray-800">
                        {{ $item->menu->name }}
                    </h3>

                    <p class="text-sm text-gray-500 mt-1">
                        Stock : {{ $item->stock }}
                    </p>

                    <p class="text-sm font-bold text-elco-coffee mt-2">
                        Rp {{ number_format($item->effective_price, 0, ',', '.') }}
                    </p>

                    <button
                        class="w-full mt-4 bg-elco-coffee text-white py-2 rounded-xl font-medium hover:opacity-90 transition">

                        Tambah

                    </button>

                </div>

                @empty

                <div class="col-span-3 text-center py-10 text-gray-400">
                    Tidak ada menu tersedia
                </div>

                @endforelse

            </div>

        </div>

        <!-- CHECKOUT -->
        <div class="bg-white p-6 rounded-3xl shadow-soft">

            <h2 class="text-xl font-bold text-gray-800 mb-6">
                Checkout
            </h2>

            <!-- TOTAL -->
            <div class="border-b border-dashed pb-4 mb-5">

                <div class="flex justify-between items-center">

                    <span class="text-gray-500">
                        Total
                    </span>

                    <span class="text-2xl font-bold text-elco-coffee">
                        Rp 50.000
                    </span>

                </div>

            </div>

            <!-- PAYMENT -->
            <div class="mb-5">

                <label class="block text-sm font-medium text-gray-700 mb-3">
                    Metode Pembayaran
                </label>

                <div class="grid grid-cols-2 gap-3">

                    <!-- CASH -->
                    <label class="cursor-pointer">

                        <input
                            type="radio"
                            name="payment_method"
                            value="cash"
                            checked
                            class="hidden peer">

                        <div class="border border-gray-200 rounded-2xl p-4
                                    peer-checked:border-emerald-500
                                    peer-checked:bg-emerald-50">

                            <div class="flex items-center gap-2">

                                <i class="ph-fill ph-money text-2xl text-emerald-600"></i>

                                <span class="font-semibold text-sm">
                                    Cash
                                </span>

                            </div>

                        </div>

                    </label>

                    <!-- QRIS -->
                    <label class="cursor-pointer">

                        <input
                            type="radio"
                            name="payment_method"
                            value="qris"
                            class="hidden peer">

                        <div class="border border-gray-200 rounded-2xl p-4
                                    peer-checked:border-blue-500
                                    peer-checked:bg-blue-50">

                            <div class="flex items-center gap-2">

                                <i class="ph-fill ph-qr-code text-2xl text-blue-600"></i>

                                <span class="font-semibold text-sm">
                                    QRIS
                                </span>

                            </div>

                        </div>

                    </label>

                </div>

            </div>

            <!-- CASH AREA -->
            <div id="cash-area" class="mb-5">

                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Uang Bayar
                </label>

                <input
                    type="number"
                    id="paid_amount"
                    placeholder="Masukkan nominal"
                    class="w-full border border-gray-200 rounded-2xl px-4 py-3">

            </div>

            <!-- QRIS AREA -->
            <div id="qris-area" class="hidden mb-5">

                <div class="bg-gray-50 border border-gray-200 rounded-2xl p-4 text-center">

                    <img
                        src="/images/qris.png"
                        alt="QRIS"
                        class="w-44 mx-auto mb-3">

                    <p class="text-sm text-gray-500">
                        Scan QRIS untuk pembayaran
                    </p>

                </div>

            </div>

            <!-- CHANGE -->
            <div class="flex justify-between items-center mb-6">

                <span class="text-gray-500">
                    Kembalian
                </span>

                <span id="change_text" class="font-bold text-emerald-600">
                    Rp 0
                </span>

            </div>

            <!-- BUTTON -->
            <button
                class="w-full bg-elco-coffee text-white py-3 rounded-2xl font-bold hover:opacity-90 transition">

                Proses Pembayaran

            </button>

        </div>

    </div>

</div>

<script>

document.addEventListener('DOMContentLoaded', function () {

    const paymentInputs = document.querySelectorAll('input[name="payment_method"]');

    const cashArea = document.getElementById('cash-area');

    const qrisArea = document.getElementById('qris-area');

    function updatePaymentUI() {

        const selected = document.querySelector('input[name="payment_method"]:checked');

        if (!selected) return;

        if (selected.value === 'cash') {

            cashArea.classList.remove('hidden');
            qrisArea.classList.add('hidden');

        } else {

            cashArea.classList.add('hidden');
            qrisArea.classList.remove('hidden');

        }

    }

    paymentInputs.forEach(input => {

        input.addEventListener('change', updatePaymentUI);

    });

    updatePaymentUI();

    // KEMBALIAN
    const total = 50000;

    const paidInput = document.getElementById('paid_amount');

    const changeText = document.getElementById('change_text');

    paidInput.addEventListener('input', () => {

        let paid = parseInt(paidInput.value) || 0;

        let change = paid - total;

        changeText.innerHTML =
            'Rp ' + change.toLocaleString('id-ID');

    });

});

</script>

@endsection