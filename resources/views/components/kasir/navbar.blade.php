<header class="elco-topbar-shell" aria-label="Topbar kasir">
    <div class="elco-topbar elco-topbar-simple">
        <div class="elco-topbar-left">
            <h1 class="elco-topbar-title">@yield('page_title', 'Kasir ELCO')</h1>
            <p class="elco-topbar-date" data-elco-clock>{{ now()->translatedFormat('l, j F Y H:i') }} WIB</p>
        </div>

        <div class="elco-topbar-right">
            <div class="relative" id="profileWrapper">
                <button class="elco-topbar-profile-pill" onclick="toggleProfile()" aria-label="Profil">
                    <div class="elco-topbar-avatar">
                        {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                    </div>
                    <div class="elco-topbar-profile-info">
                        <span class="elco-topbar-profile-name">{{ auth()->user()->name }}</span>
                        <span class="elco-topbar-profile-role">Kasir</span>
                    </div>
                    <i class="ph ph-caret-down elco-topbar-caret"></i>
                </button>

                <div id="profileDropdown" class="elco-topbar-dropdown hidden" style="min-width:240px;">
                    <div class="elco-topbar-dropdown-header">
                        <p>{{ auth()->user()->name }}</p>
                        <span>{{ auth()->user()->email }}</span>
                    </div>
                    <a href="{{ route('profile.edit') }}" class="elco-topbar-notif-item">
                        <div class="elco-topbar-notif-icon" style="background:rgba(255,246,235,0.08);color:rgba(255,246,235,0.68);">
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
function toggleProfile() {
    document.getElementById('profileDropdown').classList.toggle('hidden');
}
document.addEventListener('click', function(e) {
    const profileW = document.getElementById('profileWrapper');
    if (profileW && !profileW.contains(e.target)) {
        document.getElementById('profileDropdown')?.classList.add('hidden');
    }
});

function updateElcoClock() {
    document.querySelectorAll('[data-elco-clock]').forEach(function (clock) {
        clock.textContent = new Intl.DateTimeFormat('id-ID', {
            timeZone: 'Asia/Jakarta',
            weekday: 'long',
            day: 'numeric',
            month: 'long',
            year: 'numeric',
            hour: '2-digit',
            minute: '2-digit',
            second: '2-digit',
            hour12: false,
        }).format(new Date()).replace(' pukul ', ', ') + ' WIB';
    });
}

updateElcoClock();
setInterval(updateElcoClock, 1000);
</script>
@endpush
