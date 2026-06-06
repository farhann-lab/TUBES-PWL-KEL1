<nav class="elco-bottom-nav-shell elco-bottom-nav-shell-admin" aria-label="Navigasi admin cabang">
    <div class="elco-bottom-nav elco-bottom-nav-admin">
        @foreach([
            ['route' => 'admin.dashboard', 'active' => 'admin.dashboard', 'icon' => 'ph-squares-four', 'label' => 'Dashboard'],
            ['route' => 'admin.stocks.index', 'active' => 'admin.stocks*', 'icon' => 'ph-package', 'label' => 'Stok'],
            ['route' => 'admin.stock-requests.index', 'active' => 'admin.stock-requests*', 'icon' => 'ph-arrow-circle-up', 'label' => 'Pengajuan'],
            ['route' => 'admin.kasirs.index', 'active' => 'admin.kasirs*', 'icon' => 'ph-users-three', 'label' => 'Kasir'],
            ['route' => 'admin.expenses.index', 'active' => 'admin.expenses*', 'icon' => 'ph-money', 'label' => 'Pengeluaran'],
            ['route' => 'admin.promotions.index', 'active' => 'admin.promotions*', 'icon' => 'ph-tag', 'label' => 'Promo'],
            ['route' => 'admin.reports.index', 'active' => 'admin.reports*', 'icon' => 'ph-chart-bar', 'label' => 'Laporan'],
            ['route' => 'admin.transactions.index', 'active' => 'admin.transactions*', 'icon' => 'ph-receipt', 'label' => 'Transaksi'],
        ] as $item)
            @php($active = request()->routeIs($item['active']))
            <a href="{{ route($item['route']) }}"
               class="elco-bottom-nav-item {{ $active ? 'is-active' : '' }}"
               title="{{ $item['label'] }}"
               aria-label="{{ $item['label'] }}"
               aria-current="{{ $active ? 'page' : 'false' }}">
                <i class="{{ $active ? 'ph-fill' : 'ph' }} {{ $item['icon'] }}"></i>
                <span>{{ $item['label'] }}</span>
            </a>
        @endforeach
    </div>
</nav>
