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
        aria-label="Toggle sidebar"
        class="elco-toggle-btn absolute -right-3 top-1/2 -translate-y-1/2 w-6 h-6 flex items-center justify-center z-30 cursor-pointer rounded-full">
        <i class="ph ph-caret-left text-[#6b4c30] text-sm transition-transform duration-[400ms] ease-[cubic-bezier(0.4,0,0.2,1)]"
           :class="open ? 'rotate-0' : 'rotate-180'"></i>
    </button>

    {{-- ─── Header / Logo ─── --}}
    <div class="h-[88px] flex items-center border-b border-white/40 flex-shrink-0 overflow-hidden" :class="open ? 'px-5' : 'justify-center px-0'">
        <div class="flex items-center gap-3 min-w-0">
            <div class="w-[42px] h-[42px] rounded-[14px] bg-gradient-to-br from-[#8B5E3C] to-[#5C3A1E] flex items-center justify-center flex-shrink-0 shadow-lg"
                 style="box-shadow: 0 4px 16px rgba(92,58,30,0.35), inset 0 1px 0 rgba(255,255,255,0.2)">
                <i class="ph ph-coffee text-white text-2xl"></i>
            </div>
            <div class="elco-label-transition overflow-hidden whitespace-nowrap"
                 :class="open ? 'max-w-[160px] opacity-100' : 'max-w-0 opacity-0'">
                <div style="font-family: 'Playfair Display', serif; font-size: 18px; color: #3d2310; letter-spacing: 0.03em; line-height: 1.1;">
                    ELCO
                </div>
                <div style="font-size: 10px; font-weight: 600; color: #8B5E3C; letter-spacing: 0.12em; text-transform: uppercase;">
                    Kasir
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

        {{-- Transaksi --}}
        <a href="{{ route('kasir.transactions.index') }}"
           class="elco-nav-item flex items-center gap-3 px-[14px] py-[11px] rounded-2xl font-medium smooth-transition mb-0.5 no-underline
           {{ request()->routeIs('kasir.transactions*') ? 'elco-nav-active' : 'text-[rgba(80,50,25,0.6)]' }}"
           :class="open ? '' : 'justify-center !px-0 !gap-0'">
            <i class="{{ request()->routeIs('kasir.transactions*') ? 'ph-fill' : 'ph' }} ph-shopping-cart text-xl flex-shrink-0"></i>
            <span class="elco-label-transition overflow-hidden whitespace-nowrap"
                  :class="open ? 'max-w-[140px] opacity-100' : 'max-w-0 opacity-0'">Transaksi</span>
            @if(request()->routeIs('kasir.transactions*'))
                <i class="ph ph-arrow-right ml-auto text-sm elco-label-transition"
                   :class="open ? 'max-w-[20px] opacity-100' : 'max-w-0 opacity-0 overflow-hidden'"></i>
            @endif
        </a>

        {{-- ─── Section Label: Lainnya ─── --}}
        <div class="elco-section-collapse overflow-hidden whitespace-nowrap px-2"
             :class="open ? 'max-h-10 opacity-100 pt-4 pb-1' : 'max-h-0 opacity-0 pt-0 pb-0'"
             style="font-size: 10px; font-weight: 600; color: rgba(92,58,30,0.45); letter-spacing: 0.14em; text-transform: uppercase;">
            Lainnya
        </div>

        {{-- Pengaturan --}}
        <a href="{{ route('profile.edit') }}"
           class="elco-nav-item flex items-center gap-3 px-[14px] py-[11px] rounded-2xl font-medium smooth-transition mb-0.5 no-underline
           {{ request()->routeIs('profile*') ? 'elco-nav-active' : 'text-[rgba(80,50,25,0.6)]' }}"
           :class="open ? '' : 'justify-center !px-0 !gap-0'">
            <i class="ph ph-gear text-xl flex-shrink-0"></i>
            <span class="elco-label-transition overflow-hidden whitespace-nowrap"
                  :class="open ? 'max-w-[140px] opacity-100' : 'max-w-0 opacity-0'">Pengaturan</span>
        </a>

    </nav>

    {{-- ─── Footer / Logout ─── --}}
    <div class="px-3 pb-5 pt-3 border-t border-white/35 flex-shrink-0">
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button
                type="submit"
                class="elco-logout-btn w-full flex items-center gap-3 px-[14px] py-[11px] rounded-2xl text-white cursor-pointer overflow-hidden"
                :class="open ? '' : 'justify-center !px-0 !gap-0'"
                style="font-size: 13px; font-weight: 600; letter-spacing: 0.03em; border: none;">
                <i class="ph ph-sign-out text-xl flex-shrink-0"></i>
                <span class="elco-label-transition overflow-hidden whitespace-nowrap"
                      :class="open ? 'max-w-[140px] opacity-100' : 'max-w-0 opacity-0'">Keluar</span>
            </button>
        </form>
    </div>

</aside>
