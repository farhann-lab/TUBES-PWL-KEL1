<header class="elco-topbar-shell" aria-label="Topbar manager">
    <div class="elco-topbar">

        {{-- Kiri: judul halaman + tanggal --}}
        <div class="elco-topbar-left">
            <h1 class="elco-topbar-title">@yield('page_title', 'Dashboard')</h1>
            <p class="elco-topbar-date">{{ now()->translatedFormat('l, j F Y') }}</p>
        </div>

        {{-- Kanan: notif + profil --}}
        <div class="elco-topbar-right">

            {{-- Notifikasi --}}
            @php
                $pendingRequests = \App\Models\StockRequest::where('status', 'pending')->count();
                $pendingExpenses = \App\Models\Expense::where('status', 'pending')->count();
                $pendingPromos   = \App\Models\Promotion::where('type', 'branch')
                                    ->where('review_status', 'pending')->count();
                $notifCount      = $pendingRequests + $pendingExpenses + $pendingPromos;
            @endphp

            <div class="relative" id="managerNotifWrapper">
                <button onclick="toggleManagerNotif()"
                    class="elco-topbar-icon-btn"
                    aria-label="Notifikasi" title="Notifikasi">
                    <i class="ph ph-bell"></i>
                    @if($notifCount > 0)
                    <span class="elco-topbar-badge">{{ $notifCount > 9 ? '9+' : $notifCount }}</span>
                    @endif
                </button>

                <div id="managerNotifDropdown"
                     class="elco-topbar-dropdown hidden">
                    <div class="elco-topbar-dropdown-header">
                        <p>Notifikasi</p>
                        <span>{{ $notifCount }} item</span>
                    </div>
                    <div class="elco-topbar-dropdown-body">

                        @if($pendingRequests > 0)
                        <a href="{{ route('manager.stock-requests.index') }}" class="elco-topbar-notif-item">
                            <div class="elco-topbar-notif-icon" style="background:rgba(59,130,246,0.15);color:#60a5fa;">
                                <i class="ph-fill ph-package"></i>
                            </div>
                            <div>
                                <p>{{ $pendingRequests }} Pengajuan Stok Pending</p>
                                <span>Menunggu verifikasi kamu</span>
                            </div>
                            <span class="elco-topbar-notif-count" style="background:rgba(59,130,246,0.2);color:#93c5fd;">{{ $pendingRequests }}</span>
                        </a>
                        @endif

                        @if($pendingExpenses > 0)
                        <a href="{{ route('manager.expenses.index') }}" class="elco-topbar-notif-item">
                            <div class="elco-topbar-notif-icon" style="background:rgba(239,99,88,0.15);color:#f87171;">
                                <i class="ph-fill ph-receipt"></i>
                            </div>
                            <div>
                                <p>{{ $pendingExpenses }} Pengeluaran Pending</p>
                                <span>Menunggu verifikasi keuangan</span>
                            </div>
                            <span class="elco-topbar-notif-count" style="background:rgba(239,99,88,0.2);color:#fca5a5;">{{ $pendingExpenses }}</span>
                        </a>
                        @endif

                        @if($pendingPromos > 0)
                        <a href="{{ route('manager.promotions.index') }}" class="elco-topbar-notif-item">
                            <div class="elco-topbar-notif-icon" style="background:rgba(240,181,109,0.15);color:#fbbf24;">
                                <i class="ph-fill ph-tag"></i>
                            </div>
                            <div>
                                <p>{{ $pendingPromos }} Promo Cabang Pending</p>
                                <span>Menunggu tinjauan kamu</span>
                            </div>
                            <span class="elco-topbar-notif-count" style="background:rgba(240,181,109,0.2);color:#fcd34d;">{{ $pendingPromos }}</span>
                        </a>
                        @endif

                        @if($notifCount === 0)
                        <div class="elco-topbar-notif-empty">
                            <i class="ph ph-check-circle"></i>
                            <p>Semua sudah diproses!</p>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            <div class="elco-topbar-divider"></div>

            {{-- Profile Pill --}}
            <div class="relative" id="profileWrapper">
                <button class="elco-topbar-profile-pill" onclick="toggleProfile()" aria-label="Profil">
                    <div class="elco-topbar-avatar">
                        {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                    </div>
                    <div class="elco-topbar-profile-info">
                        <span class="elco-topbar-profile-name">{{ auth()->user()->name }}</span>
                        <span class="elco-topbar-profile-role">Manager Pusat</span>
                    </div>
                    <i class="ph ph-caret-down elco-topbar-caret"></i>
                </button>

                <div id="profileDropdown" class="elco-topbar-dropdown hidden" style="min-width:220px;">
                    <div class="elco-topbar-dropdown-header">
                        <p>{{ auth()->user()->name }}</p>
                        <span>{{ auth()->user()->email }}</span>
                    </div>
                    <a href="{{ route('profile.edit') }}" class="elco-topbar-notif-item">
                        <div class="elco-topbar-notif-icon" style="background:rgba(255,246,235,0.08);color:rgba(255,246,235,0.6);">
                            <i class="ph ph-user-circle"></i>
                        </div>
                        <div><p>Edit Profil</p></div>
                    </a>
                    <div style="border-top:1px solid rgba(255,238,220,0.10);margin:4px 0;"></div>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="elco-topbar-notif-item elco-topbar-logout">
                            <div class="elco-topbar-notif-icon" style="background:rgba(239,99,88,0.12);color:#f87171;">
                                <i class="ph ph-sign-out"></i>
                            </div>
                            <div><p>Keluar</p></div>
                        </button>
                    </form>
                </div>
            </div>

        </div>
    </div>
</header>

@push('scripts')
<script>
function toggleManagerNotif() {
    document.getElementById('managerNotifDropdown').classList.toggle('hidden');
    document.getElementById('profileDropdown')?.classList.add('hidden');
}
function toggleProfile() {
    document.getElementById('profileDropdown').classList.toggle('hidden');
    document.getElementById('managerNotifDropdown')?.classList.add('hidden');
}
document.addEventListener('click', function(e) {
    const notifW   = document.getElementById('managerNotifWrapper');
    const profileW = document.getElementById('profileWrapper');
    if (notifW && !notifW.contains(e.target))
        document.getElementById('managerNotifDropdown')?.classList.add('hidden');
    if (profileW && !profileW.contains(e.target))
        document.getElementById('profileDropdown')?.classList.add('hidden');
});
</script>
@endpush
