<header class="h-24 px-8 flex items-center justify-between z-10">
    <div>
        <h1 class="text-2xl font-display font-bold text-elco-coffee">Dashboard Kasir</h1>
        <p class="text-sm text-gray-500 mt-1">
            {{ now()->translatedFormat('l, j F Y') }} •
            <span id="realtimeClock" class="font-medium text-elco-coffee"></span>
        </p>
    </div>
    <div class="flex items-center gap-3 pl-4 border-l border-gray-200 cursor-pointer group">
        <div class="w-11 h-11 rounded-full bg-gradient-to-br from-elco-coffee to-elco-mocha flex items-center justify-center text-white font-bold shadow-sm">
            {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
        </div>
        <div class="hidden md:block text-right">
            <p class="text-sm font-semibold text-gray-800">{{ auth()->user()->name }}</p>
            <p class="text-xs text-gray-500">Kasir</p>
        </div>
    </div>
</header>