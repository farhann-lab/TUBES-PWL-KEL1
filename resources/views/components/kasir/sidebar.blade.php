<nav class="elco-bottom-nav-shell elco-bottom-nav-shell-kasir" aria-label="Navigasi kasir">
    <div class="elco-bottom-nav elco-bottom-nav-kasir">
        @foreach([
            ['route' => 'kasir.dashboard', 'active' => 'kasir.dashboard', 'icon' => 'ph-squares-four', 'label' => 'Dashboard'],
            ['route' => 'kasir.transactions.index', 'active' => 'kasir.transactions*', 'icon' => 'ph-shopping-cart', 'label' => 'Transaksi'],
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
