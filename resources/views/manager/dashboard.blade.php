@extends('layouts.manager')

@section('content')
<div class="grid grid-cols-1 xl:grid-cols-3 gap-6">
    
    <!-- ================= LEFT COLUMN (Span 2) ================= -->
    <div class="xl:col-span-2 space-y-6">
        
        <!-- Stats Overview -->
        <div class="flex items-center justify-between mb-2">
            <h2 class="text-lg font-display font-semibold text-gray-800">Ringkasan Operasional <span class="text-gray-400 text-sm font-normal">/ Hari Ini</span></h2>
            <div class="flex gap-2">
                <button class="w-8 h-8 rounded-full bg-white flex items-center justify-center shadow-sm text-gray-500 hover:text-elco-coffee smooth-transition"><i class="ph ph-export"></i></button>
                <button class="w-8 h-8 rounded-full bg-white flex items-center justify-center shadow-sm text-gray-500 hover:text-elco-coffee smooth-transition"><i class="ph ph-arrows-out-simple"></i></button>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <!-- Card 1 -->
            <div class="bg-white p-6 rounded-3xl shadow-soft smooth-transition hover:-translate-y-1 hover:shadow-hover border border-transparent hover:border-elco-latte/30">
                <div class="w-12 h-12 rounded-2xl bg-orange-50 text-orange-500 flex items-center justify-center text-2xl mb-4">
                    <i class="ph-fill ph-wallet"></i>
                </div>
                <p class="text-sm text-gray-500 mb-1">Total Pendapatan</p>
                <h3 class="text-2xl font-display font-bold text-gray-800 mb-2">Rp 24.5M</h3>
                <div class="flex items-center text-xs font-medium text-emerald-500 bg-emerald-50 w-max px-2 py-1 rounded-md">
                    <i class="ph ph-trend-up mr-1"></i> +12.5% 
                    <span class="text-gray-400 ml-1">vs kemarin</span>
                </div>
            </div>

            <!-- Card 2 -->
            <div class="bg-white p-6 rounded-3xl shadow-soft smooth-transition hover:-translate-y-1 hover:shadow-hover border border-transparent hover:border-elco-latte/30">
                <div class="w-12 h-12 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-2xl mb-4">
                    <i class="ph-fill ph-receipt"></i>
                </div>
                <p class="text-sm text-gray-500 mb-1">Total Transaksi</p>
                <h3 class="text-2xl font-display font-bold text-gray-800 mb-2">856 <span class="text-sm text-gray-400 font-normal">struk</span></h3>
                <div class="flex gap-2 w-full mt-3">
                    <div class="h-1.5 flex-1 bg-emerald-500 rounded-full"></div>
                    <div class="h-1.5 w-1/4 bg-gray-200 rounded-full"></div>
                </div>
                <p class="text-xs text-gray-400 mt-2">Target Harian: 1000</p>
            </div>

            <!-- Card 3 -->
            <div class="bg-white p-6 rounded-3xl shadow-soft smooth-transition hover:-translate-y-1 hover:shadow-hover border border-transparent hover:border-elco-latte/30">
                <div class="w-12 h-12 rounded-2xl bg-red-50 text-red-500 flex items-center justify-center text-2xl mb-4">
                    <i class="ph-fill ph-package"></i>
                </div>
                <p class="text-sm text-gray-500 mb-1">Peringatan Stok</p>
                <h3 class="text-2xl font-display font-bold text-gray-800 mb-2">12 <span class="text-sm text-gray-400 font-normal">item kritis</span></h3>
                <div class="flex items-center text-xs font-medium text-red-500">
                    4 Cabang butuh suplai biji kopi
                </div>
            </div>
        </div>

        <!-- Main Chart -->
        <div class="bg-white p-6 rounded-3xl shadow-soft">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-lg font-display font-semibold text-gray-800">Grafik Pendapatan & Transaksi</h2>
                <div class="flex items-center gap-4">
                    <div class="flex items-center gap-2 text-xs text-gray-500">
                        <span class="w-2 h-2 rounded-full bg-elco-mocha"></span> Pendapatan
                    </div>
                    <div class="flex items-center gap-2 text-xs text-gray-500">
                        <span class="w-2 h-2 rounded-full bg-emerald-400"></span> Transaksi
                    </div>
                    <button class="w-8 h-8 rounded-xl border border-gray-200 flex items-center justify-center text-gray-500 hover:bg-gray-50 smooth-transition"><i class="ph ph-calendar-blank"></i></button>
                </div>
            </div>
            <!-- Canvas for Chart.js -->
            <div class="h-64 w-full relative">
                <canvas id="revenueChart"></canvas>
            </div>
        </div>

        <!-- Table Section -->
        <div class="bg-white p-6 rounded-3xl shadow-soft mb-8">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-lg font-display font-semibold text-gray-800">Aktivitas Cabang Terbaru</h2>
                <button class="flex items-center gap-2 text-xs font-medium text-elco-coffee bg-[#F6F3F0] px-3 py-1.5 rounded-lg hover:bg-elco-latte/50 smooth-transition">
                    Semua Cabang <i class="ph ph-caret-down"></i>
                </button>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="text-xs text-gray-400 border-b border-gray-100">
                            <th class="pb-3 font-medium px-4">Manager Cabang</th>
                            <th class="pb-3 font-medium px-4">ID Cabang</th>
                            <th class="pb-3 font-medium px-4">Lokasi</th>
                            <th class="pb-3 font-medium px-4">Omset Hari Ini</th>
                            <th class="pb-3 font-medium px-4">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr class="group hover:bg-gray-50 smooth-transition border-b border-gray-50 last:border-0">
                            <td class="py-4 px-4 flex items-center gap-3">
                                <img src="https://i.pravatar.cc/150?img=33" class="w-8 h-8 rounded-full object-cover">
                                <div>
                                    <p class="text-sm font-semibold text-gray-800">Antwan Graham</p>
                                    <p class="text-xs text-gray-500">ELCO Sudirman</p>
                                </div>
                            </td>
                            <td class="py-4 px-4 text-sm text-gray-600">CBG-62358</td>
                            <td class="py-4 px-4 text-sm text-gray-600">Jakarta Selatan</td>
                            <td class="py-4 px-4">
                                <span class="text-sm font-semibold text-emerald-600">Rp 8.5M</span>
                            </td>
                            <td class="py-4 px-4">
                                <button class="text-xs font-medium text-elco-coffee bg-elco-cream px-4 py-2 rounded-xl group-hover:bg-white group-hover:shadow-sm border border-transparent group-hover:border-elco-latte/30 smooth-transition active:scale-95">Lihat Detail</button>
                            </td>
                        </tr>
                        <tr class="group hover:bg-gray-50 smooth-transition border-b border-gray-50 last:border-0">
                            <td class="py-4 px-4 flex items-center gap-3">
                                <img src="https://i.pravatar.cc/150?img=12" class="w-8 h-8 rounded-full object-cover">
                                <div>
                                    <p class="text-sm font-semibold text-gray-800">Dwight Brown</p>
                                    <p class="text-xs text-gray-500">ELCO Kemang</p>
                                </div>
                            </td>
                            <td class="py-4 px-4 text-sm text-gray-600">CBG-62359</td>
                            <td class="py-4 px-4 text-sm text-gray-600">Jakarta Selatan</td>
                            <td class="py-4 px-4">
                                <span class="text-sm font-semibold text-emerald-600">Rp 6.2M</span>
                            </td>
                            <td class="py-4 px-4">
                                <button class="text-xs font-medium text-elco-coffee bg-elco-cream px-4 py-2 rounded-xl group-hover:bg-white group-hover:shadow-sm border border-transparent group-hover:border-elco-latte/30 smooth-transition active:scale-95">Lihat Detail</button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- ================= RIGHT COLUMN (Span 1) ================= -->
    <div class="space-y-6">
        
        <!-- Performa Cabang -->
        <div class="bg-white p-6 rounded-3xl shadow-soft">
            <div class="flex justify-between items-center mb-6">
                <div>
                    <h2 class="text-lg font-display font-semibold text-gray-800">Performa Cabang</h2>
                    <p class="text-xs text-gray-500 mt-1">Status real-time hari ini</p>
                </div>
                <button class="w-8 h-8 flex items-center justify-center text-gray-400 hover:text-elco-coffee smooth-transition rounded-full hover:bg-gray-50"><i class="ph ph-dots-three text-xl"></i></button>
            </div>

            <!-- Custom Date Picker mimic -->
            <div class="flex justify-between mb-6">
                <div class="text-center">
                    <p class="text-xs text-gray-400 mb-1">Sen</p>
                    <div class="w-10 h-10 flex items-center justify-center rounded-xl text-sm font-medium text-gray-600">04</div>
                </div>
                <div class="text-center">
                    <p class="text-xs text-gray-400 mb-1">Sel</p>
                    <div class="w-10 h-10 flex items-center justify-center rounded-xl bg-gradient-to-br from-elco-coffee to-elco-mocha shadow-md text-white font-semibold transform scale-110">05</div>
                </div>
                <div class="text-center">
                    <p class="text-xs text-gray-400 mb-1">Rab</p>
                    <div class="w-10 h-10 flex items-center justify-center rounded-xl text-sm font-medium text-gray-600">06</div>
                </div>
                <div class="text-center">
                    <p class="text-xs text-gray-400 mb-1">Kam</p>
                    <div class="w-10 h-10 flex items-center justify-center rounded-xl text-sm font-medium text-gray-600">07</div>
                </div>
            </div>

            <!-- List -->
            <div class="space-y-4">
                <div class="flex items-center justify-between p-3 rounded-2xl hover:bg-gray-50 smooth-transition border border-transparent hover:border-gray-100 cursor-pointer">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-full bg-emerald-50 text-emerald-500 flex items-center justify-center border border-emerald-100">
                            <i class="ph-fill ph-storefront"></i>
                        </div>
                        <div>
                            <p class="text-sm font-semibold text-gray-800">ELCO Sudirman</p>
                            <p class="text-xs text-emerald-600 font-medium">Buka • Ramai</p>
                        </div>
                    </div>
                    <div class="flex gap-1">
                        <button class="w-8 h-8 rounded-xl bg-white border border-gray-100 flex items-center justify-center text-gray-500 hover:text-elco-coffee hover:shadow-sm smooth-transition"><i class="ph ph-clock"></i></button>
                    </div>
                </div>
                
                <div class="flex items-center justify-between p-3 rounded-2xl hover:bg-gray-50 smooth-transition border border-transparent hover:border-gray-100 cursor-pointer">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-full bg-orange-50 text-orange-500 flex items-center justify-center border border-orange-100">
                            <i class="ph-fill ph-storefront"></i>
                        </div>
                        <div>
                            <p class="text-sm font-semibold text-gray-800">ELCO Kemang</p>
                            <p class="text-xs text-orange-500 font-medium">Buka • Normal</p>
                        </div>
                    </div>
                    <div class="flex gap-1">
                        <button class="w-8 h-8 rounded-xl bg-white border border-gray-100 flex items-center justify-center text-gray-500 hover:text-elco-coffee hover:shadow-sm smooth-transition"><i class="ph ph-clock"></i></button>
                    </div>
                </div>

                <div class="flex items-center justify-between p-3 rounded-2xl hover:bg-gray-50 smooth-transition border border-transparent hover:border-gray-100 cursor-pointer">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-full bg-gray-100 text-gray-400 flex items-center justify-center border border-gray-200">
                            <i class="ph-fill ph-storefront"></i>
                        </div>
                        <div>
                            <p class="text-sm font-semibold text-gray-800">ELCO Bintaro</p>
                            <p class="text-xs text-gray-500 font-medium">Tutup</p>
                        </div>
                    </div>
                    <div class="flex gap-1">
                        <button class="w-8 h-8 rounded-xl bg-white border border-gray-100 flex items-center justify-center text-gray-500 hover:text-elco-coffee hover:shadow-sm smooth-transition"><i class="ph ph-clock"></i></button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Gradient Promo Card -->
        <div class="p-6 rounded-3xl relative overflow-hidden shadow-lg group">
            <!-- Gradient Background -->
            <div class="absolute inset-0 bg-gradient-to-br from-[#3E2723] via-[#5D4037] to-[#8D6E63]"></div>
            <!-- Decorative glow -->
            <div class="absolute -bottom-10 -right-10 w-40 h-40 bg-white/20 blur-3xl rounded-full"></div>
            
            <div class="relative z-10">
                <div class="flex justify-between items-start mb-4">
                    <h3 class="text-lg font-display font-semibold text-white">Promo Aktif</h3>
                    <button class="bg-white/20 backdrop-blur-md text-white text-xs px-3 py-1.5 rounded-full hover:bg-white/30 smooth-transition">Detail</button>
                </div>
                
                <h2 class="text-xl font-display font-bold text-elco-cream mb-2">Buy 1 Get 1 Free (Latte)</h2>
                <p class="text-sm text-white/80 leading-relaxed mb-6">
                    Berlaku di seluruh cabang untuk pembelian dine-in. Cek efektivitas promo di laporan kasir.
                </p>

                <div class="flex gap-3">
                    <div class="bg-white/20 backdrop-blur-sm text-white text-xs px-3 py-2 rounded-xl flex items-center gap-2">
                        <i class="ph ph-calendar"></i> s/d 10 Mei
                    </div>
                    <div class="bg-white text-elco-coffee font-semibold text-xs px-4 py-2 rounded-xl flex items-center gap-2 shadow-md cursor-pointer hover:shadow-lg smooth-transition active:scale-95">
                        <i class="ph ph-broadcast"></i> Push Notifikasi
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection

@push('scripts')
    <!-- CDN ChartJS khusus halaman ini -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="{{ asset('js/manager-dashboard.js') }}"></script>
@endpush