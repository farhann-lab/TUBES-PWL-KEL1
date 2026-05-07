<header class="h-24 px-8 flex items-center justify-between z-10">
            <div>
                <h1 class="text-2xl font-display font-bold text-elco-coffee">Dashboard</h1>
                <p class="text-sm text-gray-500 mt-1">Selasa, 6 Mei 2026</p>
            </div>

            <div class="flex items-center gap-6">
                <!-- Search -->
                <div class="relative hidden md:block">
                    <i class="ph ph-magnifying-glass absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 text-lg"></i>
                    <input type="text" placeholder="Cari cabang, transaksi, menu..." class="w-80 bg-white py-3 pl-11 pr-4 rounded-full shadow-soft focus:outline-none focus:ring-2 focus:ring-elco-mocha/20 text-sm smooth-transition">
                    <i class="ph ph-microphone absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 cursor-pointer hover:text-elco-coffee smooth-transition"></i>
                </div>

                <!-- Theme / Notification -->
                <div class="flex items-center gap-3">
                    <button class="w-10 h-10 bg-white rounded-full flex items-center justify-center shadow-soft hover:shadow-hover smooth-transition active:scale-95 text-gray-500 hover:text-elco-coffee">
                        <i class="ph ph-sun text-lg"></i>
                    </button>
                    <button class="w-10 h-10 bg-white rounded-full flex items-center justify-center shadow-soft hover:shadow-hover smooth-transition active:scale-95 text-gray-500 hover:text-elco-coffee relative">
                        <i class="ph ph-bell text-lg"></i>
                        <span class="absolute top-2 right-2 w-2 h-2 bg-red-500 rounded-full border-2 border-white"></span>
                    </button>
                </div>

                <!-- Profile -->
                <div class="flex items-center gap-3 pl-4 border-l border-gray-200 cursor-pointer group">
                    <img src="https://i.pravatar.cc/150?img=11" alt="Profile" class="w-11 h-11 rounded-full object-cover shadow-sm group-hover:shadow-md smooth-transition">
                    <div class="hidden md:block text-right">
                        <p class="text-sm font-semibold text-gray-800">Manager ELCO</p>
                        <p class="text-xs text-gray-500">Pusat Manager</p>
                    </div>
                    <i class="ph ph-caret-down text-gray-400 group-hover:text-elco-coffee smooth-transition"></i>
                </div>
            </div>
        </header>