<nav class="elco-bottom-nav-shell elco-bottom-nav-shell-manager" aria-label="Navigasi manager pusat">
    <div class="elco-bottom-nav elco-bottom-nav-manager">
        @foreach([
            ['route' => 'manager.dashboard', 'active' => 'manager.dashboard', 'icon' => 'ph-squares-four', 'label' => 'Dashboard'],
            ['route' => 'manager.branches.index', 'active' => 'manager.branches*', 'icon' => 'ph-storefront', 'label' => 'Cabang'],
            ['route' => 'manager.stock-requests.index', 'active' => 'manager.stock-requests*', 'icon' => 'ph-package', 'label' => 'Stok'],
            ['route' => 'manager.transactions.index', 'active' => 'manager.transactions*', 'icon' => 'ph-receipt', 'label' => 'Transaksi'],
            ['route' => 'manager.expenses.index', 'active' => 'manager.expenses*', 'icon' => 'ph-money', 'label' => 'Pengeluaran'],
            ['route' => 'manager.reports.index', 'active' => 'manager.reports*', 'icon' => 'ph-chart-line-up', 'label' => 'Laporan'],
            ['route' => 'manager.menus.index', 'active' => 'manager.menus*', 'icon' => 'ph-coffee', 'label' => 'Menu'],
            ['route' => 'manager.promotions.index', 'active' => 'manager.promotions*', 'icon' => 'ph-tag', 'label' => 'Promo'],
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
