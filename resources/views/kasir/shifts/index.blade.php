@extends('layouts.kasir')

@section('content')

<div class="space-y-6">

    <!-- HEADER -->
    <div class="flex items-center justify-between">

        <h2 class="text-lg font-display font-semibold text-gray-800">
            Ringkasan Hari Ini
            <span class="text-gray-400 text-sm font-normal">
                / {{ now()->format('d M Y') }}
            </span>
        </h2>

    </div>

    <!-- STAT -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

        <!-- MENU -->
        <div class="bg-white p-6 rounded-3xl shadow-soft">

            <div class="w-12 h-12 rounded-2xl bg-orange-50 text-orange-500 flex items-center justify-center text-2xl mb-4">
                <i class="ph-fill ph-coffee"></i>
            </div>

            <p class="text-sm text-gray-500 mb-1">
                Menu Tersedia
            </p>

            <h3 class="text-2xl font-display font-bold text-gray-800">
                {{ $data['available_menus']->count() }}
                <span class="text-sm text-gray-400 font-normal">
                    item
                </span>
            </h3>

        </div>

        <!-- TRANSAKSI -->
        <div class="bg-white p-6 rounded-3xl shadow-soft">

            <div class="w-12 h-12 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-2xl mb-4">
                <i class="ph-fill ph-receipt"></i>
            </div>

            <p class="text-sm text-gray-500 mb-1">
                Transaksi Hari Ini
            </p>

            <h3 class="text-2xl font-display font-bold text-gray-800">
                {{ $data['today_transactions'] }}

                <span class="text-sm text-gray-400 font-normal">
                    struk
                </span>

            </h3>

        </div>

        <!-- PENJUALAN -->
        <div class="bg-white p-6 rounded-3xl shadow-soft">

            <div class="w-12 h-12 rounded-2xl bg-blue-50 text-blue-500 flex items-center justify-center text-2xl mb-4">
                <i class="ph-fill ph-wallet"></i>
            </div>

            <p class="text-sm text-gray-500 mb-1">
                Total Penjualan
            </p>

            <h3 class="text-xl font-display font-bold text-gray-800">
                Rp {{ number_format($data['today_total'], 0, ',', '.') }}
            </h3>

        </div>

    </div>

    <!-- POS -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        <!-- MENU -->
        <div class="lg:col-span-2 bg-white p-6 rounded-3xl shadow-soft">

            <div class="flex justify-between items-center mb-5">

                <h2 class="text-lg font-semibold text-gray-800">
                    Menu Café
                </h2>

            </div>

            <div class="grid grid-cols-2 md:grid-cols-3 gap-4">

                @forelse($data['available_menus'] as $item)

                <div class="p-4 rounded-2xl border border-gray-100 hover:shadow-soft transition">

                    <h3 class="font-semibold text-gray-800">
                        {{ $item->menu->name }}
                    </h3>

                    <p class="text-xs text-gray-500 mt-1">
                        Stock: {{ $item->stock }}
                    </p>

                    <p class="text-sm font-bold text-elco-coffee mt-2">
                        Rp {{ number_format($item->effective_price, 0, ',', '.') }}
                    </p>

                    <button
                        class="mt-4 w-full bg-elco-coffee text-white py-2 rounded-xl hover:opacity-90">

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

            <h2 class="text-lg font-semibold text-gray-800 mb-5">
                Checkout
            </h2>

            <!-- TOTAL -->
            <div class="border-t border-dashed pt-4 mb-5">

                <div class="flex justify-between text-lg font-bold text-elco-coffee">

                    <span>Total</span>

                    <span id="total-price">
                        Rp 50.000
                    </span>

                </div>

            </div>

            <!-- PAYMENT -->
            <div class="mb-5">

                <label class="text-sm font-medium text-gray-700 block mb-3">
                    Metode Pembayaran
                </label>

                <select
                    id="payment_method"
                    class="w-full border border-gray-200 rounded-2xl px-4 py-3">

                    <option value="cash">
                        Cash
                    </option>

                    <option value="qris">
                        QRIS
                    </option>

                </select>

            </div>

            <!-- CASH -->
            <div id="cash-area" class="mb-5">

                <label class="text-sm font-medium text-gray-700 block mb-2">
                    Uang Bayar
                </label>

                <input
                    type="number"
                    id="paid_amount"
                    class="w-full border border-gray-200 rounded-2xl px-4 py-3"
                    placeholder="Masukkan nominal">

            </div>

            <!-- QRIS -->
            <div id="qris-area" style="display:none;" class="mb-5">

                <div class="bg-gray-50 border border-gray-200 rounded-2xl p-4 text-center">

                    <img
                        src="https://api.qrserver.com/v1/create-qr-code/?size=220x220&data=ELCO-KASIR"
                        class="mx-auto rounded-xl mb-3">

                    <p class="text-sm text-gray-500">
                        Scan QR untuk pembayaran
                    </p>

                </div>

            </div>

            <!-- KEMBALIAN -->
            <div class="flex justify-between mb-6">

                <span class="text-gray-500">
                    Kembalian
                </span>

                <span
                    id="change_text"
                    class="font-bold text-emerald-600">

                    Rp 0

                </span>

            </div>

            <!-- BUTTON -->
            <button
                class="w-full bg-elco-coffee text-white py-3 rounded-2xl font-semibold hover:opacity-90">

                Proses Pembayaran

            </button>

        </div>

    </div>

</div>

<script>

document.addEventListener('DOMContentLoaded', function () {

    const paymentMethod =
        document.getElementById('payment_method');

    const cashArea =
        document.getElementById('cash-area');

    const qrisArea =
        document.getElementById('qris-area');

    function updatePayment() {

        if (paymentMethod.value === 'cash') {

        cashArea.style.display = 'block';
        qrisArea.style.display = 'none';

    }

        if (paymentMethod.value === 'qris') {

        cashArea.style.display = 'none';
        qrisArea.style.display = 'block';

    }
    }

    paymentMethod.addEventListener('change', updatePayment);

    updatePayment();

    const total = 50000;

    const paidInput =
        document.getElementById('paid_amount');

    const changeText =
        document.getElementById('change_text');

    paidInput.addEventListener('input', () => {

        let paid =
            parseInt(paidInput.value) || 0;

        let change =
            paid - total;

        if (change < 0) {

            change = 0;

        }

        changeText.innerHTML =
            'Rp ' + change.toLocaleString('id-ID');

    });

});

</script>

@endsection