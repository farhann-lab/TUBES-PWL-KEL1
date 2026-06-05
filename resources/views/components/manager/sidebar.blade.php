{{-- ============================================================
     ELCO Sidebar — Glassmorphism
     Requires: Tailwind CSS, Alpine.js, Phosphor Icons
     ============================================================ --}}

{{-- Tambahkan style ini ke app.css / layouts utama --}}
{{-- <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700&family=DM+Sans:wght@400;500;600&display=swap" rel="stylesheet"> --}}
{{-- <script src="https://unpkg.com/@phosphor-icons/web@2.1.1/src/index.js"></script> --}}

<aside
    x-data="{
        open: JSON.parse(localStorage.getItem('elcoSidebarOpen') ?? 'true'),
        toggle() {
            this.open = !this.open;
            localStorage.setItem('elcoSidebarOpen', JSON.stringify(this.open));
        }
    }"
    :class="open ? 'w-64' : 'w-[88px]'"
    class="elco-sidebar elco-sidebar-transition h-full flex flex-col z-20 flex-shrink-0 relative overflow-visible">

    {{-- ─── Toggle Button ─── --}}
    <button
        type="button"
        @click="toggle()"
        class="elco-toggle-btn absolute -right-3 top-1/2 -translate-y-1/2 w-8 h-8 flex items-center justify-center z-30 cursor-pointer rounded-full">
        <i class="ph ph-caret-left text-[#6b4c30] text-sm transition-transform duration-[400ms] ease-[cubic-bezier(0.4,0,0.2,1)]"
        :class="open ? 'rotate-0' : 'rotate-180'"></i>
    </button>

    {{-- ─── Header / Logo ─── --}}
    <div class="h-[88px] flex items-center border-b border-white/40 flex-shrink-0 overflow-hidden" :class="open ? 'px-5' : 'justify-center px-0'">
        <div class="flex items-center gap-3 min-w-0">
            {{-- Logo Icon --}}
            <div class="w-[42px] h-[42px] rounded-[14px] bg-gradient-to-br from-[#8B5E3C] to-[#5C3A1E] flex items-center justify-center flex-shrink-0 shadow-lg"
                 style="box-shadow: 0 4px 16px rgba(92,58,30,0.35), inset 0 1px 0 rgba(255,255,255,0.2)">
                <i class="ph ph-coffee text-white text-2xl"></i>
            </div>

            {{-- Logo Text --}}
            <div class="elco-label-transition overflow-hidden whitespace-nowrap"
                 :class="open ? 'max-w-[160px] opacity-100' : 'max-w-0 opacity-0'">
                <div style="font-family: 'Playfair Display', serif; font-size: 18px; color: #3d2310; letter-spacing: 0.03em; line-height: 1.1;">
                    ELCO
                </div>
                <div style="font-size: 10px; font-weight: 600; color: #8B5E3C; letter-spacing: 0.12em; text-transform: uppercase;">
                    Manager
                </div>
            </div>
        </div>
    </div>

    {{-- ─── Section Label ─── --}}
    <div class="elco-section-collapse overflow-hidden whitespace-nowrap px-6"
         :class="open ? 'max-h-10 opacity-100 pt-5 pb-2' : 'max-h-0 opacity-0 pt-0 pb-0'"
         style="font-size: 10px; font-weight: 600; color: rgba(92,58,30,0.45); letter-spacing: 0.14em; text-transform: uppercase;">
        Menu Utama
    </div>

    {{-- ─── Navigation ─── --}}
    <nav class="flex-1 px-3 overflow-y-auto overflow-x-hidden hide-scrollbar space-y-0.5 py-1">

        {{-- Dashboard --}}
        <a href="{{ route('manager.dashboard') }}"
           class="elco-nav-item flex items-center gap-3 px-[14px] py-[11px] rounded-2xl font-medium smooth-transition mb-0.5 no-underline
           {{ request()->routeIs('manager.dashboard') ? 'elco-nav-active' : 'text-[rgba(80,50,25,0.6)]' }}"
           ::class="open ? '' : 'justify-center !px-0 !gap-0'">
            <i class="ph-fill ph-squares-four text-xl flex-shrink-0"></i>
            <span class="elco-label-transition overflow-hidden whitespace-nowrap"
                  :class="open ? 'max-w-[140px] opacity-100' : 'max-w-0 opacity-0'">Dashboard</span>
            @if(request()->routeIs('manager.dashboard'))
                <i class="ph ph-arrow-right ml-auto text-sm elco-label-transition"
                   :class="open ? 'max-w-[20px] opacity-100' : 'max-w-0 opacity-0 overflow-hidden'"></i>
            @endif
        </a>

        {{-- Cabang --}}
        <a href="{{ route('manager.branches.index') }}"
           class="elco-nav-item flex items-center gap-3 px-[14px] py-[11px] rounded-2xl font-medium smooth-transition mb-0.5 no-underline
           {{ request()->routeIs('manager.branches*') ? 'elco-nav-active' : 'text-[rgba(80,50,25,0.6)]' }}"
           ::class="open ? '' : 'justify-center !px-0 !gap-0'">
            <i class="ph ph-storefront text-xl flex-shrink-0"></i>
            <span class="elco-label-transition overflow-hidden whitespace-nowrap"
                  :class="open ? 'max-w-[140px] opacity-100' : 'max-w-0 opacity-0'">Cabang</span>
        </a>

        {{-- Manajemen Stok --}}
        <a href="{{ route('manager.stock-requests.index') }}"
           class="elco-nav-item flex items-center gap-3 px-[14px] py-[11px] rounded-2xl font-medium smooth-transition mb-0.5 no-underline
           {{ request()->routeIs('manager.stock-requests*') ? 'elco-nav-active' : 'text-[rgba(80,50,25,0.6)]' }}"
           ::class="open ? '' : 'justify-center !px-0 !gap-0'">
            <i class="ph ph-package text-xl flex-shrink-0"></i>
            <span class="elco-label-transition overflow-hidden whitespace-nowrap"
                  :class="open ? 'max-w-[140px] opacity-100' : 'max-w-0 opacity-0'">Manajemen Stok</span>
        </a>

        {{-- Transaksi --}}
        <a href="{{ route('manager.transactions.index') }}"
           class="elco-nav-item flex items-center gap-3 px-[14px] py-[11px] rounded-2xl font-medium smooth-transition mb-0.5 no-underline
           {{ request()->routeIs('manager.transactions*') ? 'elco-nav-active' : 'text-[rgba(80,50,25,0.6)]' }}"
           ::class="open ? '' : 'justify-center !px-0 !gap-0'">
            <i class="ph ph-receipt text-xl flex-shrink-0"></i>
            <span class="elco-label-transition overflow-hidden whitespace-nowrap"
                  :class="open ? 'max-w-[140px] opacity-100' : 'max-w-0 opacity-0'">Transaksi</span>
        </a>

        {{-- Pengeluaran --}}
        <a href="{{ route('manager.expenses.index') }}"
           class="elco-nav-item flex items-center gap-3 px-[14px] py-[11px] rounded-2xl font-medium smooth-transition mb-0.5 no-underline
           {{ request()->routeIs('manager.expenses*') ? 'elco-nav-active' : 'text-[rgba(80,50,25,0.6)]' }}"
           ::class="open ? '' : 'justify-center !px-0 !gap-0'">
            <i class="ph ph-money text-xl flex-shrink-0"></i>
            <span class="elco-label-transition overflow-hidden whitespace-nowrap"
                  :class="open ? 'max-w-[140px] opacity-100' : 'max-w-0 opacity-0'">Pengeluaran</span>
        </a>

        {{-- Laporan Keuangan --}}
        <a href="{{ route('manager.reports.index') }}"
           class="elco-nav-item flex items-center gap-3 px-[14px] py-[11px] rounded-2xl font-medium smooth-transition mb-0.5 no-underline
           {{ request()->routeIs('manager.reports*') ? 'elco-nav-active' : 'text-[rgba(80,50,25,0.6)]' }}"
           ::class="open ? '' : 'justify-center !px-0 !gap-0'">
            <i class="ph ph-chart-line-up text-xl flex-shrink-0"></i>
            <span class="elco-label-transition overflow-hidden whitespace-nowrap"
                  :class="open ? 'max-w-[140px] opacity-100' : 'max-w-0 opacity-0'">Laporan Keuangan</span>
        </a>

        {{-- Menu --}}
        <a href="{{ route('manager.menus.index') }}"
           class="elco-nav-item flex items-center gap-3 px-[14px] py-[11px] rounded-2xl font-medium smooth-transition mb-0.5 no-underline
           {{ request()->routeIs('manager.menus*') ? 'elco-nav-active' : 'text-[rgba(80,50,25,0.6)]' }}"
           ::class="open ? '' : 'justify-center !px-0 !gap-0'">
            <i class="ph ph-coffee text-xl flex-shrink-0"></i>
            <span class="elco-label-transition overflow-hidden whitespace-nowrap"
                  :class="open ? 'max-w-[140px] opacity-100' : 'max-w-0 opacity-0'">Menu</span>
        </a>

        {{-- Promo --}}
        <a href="{{ route('manager.promotions.index') }}"
           class="elco-nav-item flex items-center gap-3 px-[14px] py-[11px] rounded-2xl font-medium smooth-transition mb-0.5 no-underline
           {{ request()->routeIs('manager.promotions*') ? 'elco-nav-active' : 'text-[rgba(80,50,25,0.6)]' }}"
           ::class="open ? '' : 'justify-center !px-0 !gap-0'">
            <i class="ph ph-coffee-bean text-xl flex-shrink-0"></i>
            <span class="elco-label-transition overflow-hidden whitespace-nowrap"
                  :class="open ? 'max-w-[140px] opacity-100' : 'max-w-0 opacity-0'">Promo</span>
        </a>

    </nav>

    {{-- ─── Footer / Logout ─── --}}
    <div class="px-3 pb-5 pt-3 border-t border-white/35 flex-shrink-0">
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button
                type="submit"
                class="elco-logout-btn w-full flex items-center gap-3 px-[14px] py-[11px] rounded-2xl text-white cursor-pointer overflow-hidden"
                ::class="open ? '' : 'justify-center !px-0 !gap-0'"
                style="font-family: 'DM Sans', sans-serif; font-size: 13px; font-weight: 600; letter-spacing: 0.03em; border: none;">
                <i class="ph ph-sign-out text-xl flex-shrink-0"></i>
                <span class="elco-label-transition overflow-hidden whitespace-nowrap"
                      :class="open ? 'max-w-[140px] opacity-100' : 'max-w-0 opacity-0'">Logout</span>
            </button>
        </form>
    </div>

</aside>