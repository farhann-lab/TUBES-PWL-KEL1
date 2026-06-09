<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ELCO - Manager Dashboard</title>
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600&family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">

    <script src="https://unpkg.com/@phosphor-icons/web" defer></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11" defer></script>
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    @stack('styles')

    <style>
        .swal-elco-popup {
            border-radius: 24px !important;
            font-family: 'Inter', sans-serif !important;
        }
        .swal-elco-confirm {
            border-radius: 12px !important;
            font-weight: 600 !important;
            padding: 10px 24px !important;
        }
        .swal-elco-cancel {
            border-radius: 12px !important;
            font-weight: 500 !important;
            padding: 10px 24px !important;
            background: #f3f4f6 !important;
            color: #4b5563 !important;
        }
        /* ===== MANAGER THEME: MATCH DASHBOARD ===== */
        /* ===== ELCO MANAGER DARK GRADIENT THEME - ALL PAGES ===== */
:root {
    --elco-bg-dark: #1c100b;
    --elco-bg-coffee: #332018;
    --elco-bg-mocha: #563622;
    --elco-bg-caramel: #9b6235;

    --elco-glass: rgba(255, 245, 232, 0.12);
    --elco-glass-strong: rgba(255, 245, 232, 0.18);
    --elco-border: rgba(255, 238, 220, 0.18);

    --elco-text: #fff4e8;
    --elco-text-soft: rgba(255, 244, 232, 0.72);
    --elco-text-muted: rgba(255, 244, 232, 0.50);

    --elco-accent: #d08a49;
    --elco-accent-light: #f0b56d;
}

/* Background utama semua halaman */
body,
.manager-main {
    background:
        radial-gradient(circle at 15% 10%, rgba(208, 138, 73, 0.25), transparent 32%),
        radial-gradient(circle at 85% 18%, rgba(155, 98, 53, 0.38), transparent 36%),
        radial-gradient(circle at 50% 100%, rgba(86, 54, 34, 0.60), transparent 42%),
        linear-gradient(135deg, #1c100b 0%, #332018 48%, #563622 100%) !important;
    color: var(--elco-text) !important;
}

/* Area content semua halaman */
.manager-content {
    background: transparent !important;
}

/* Dashboard scene */
.glass-scene {
    background:
        radial-gradient(circle at 16% 10%, rgba(240, 181, 109, 0.20), transparent 32%),
        radial-gradient(circle at 90% 15%, rgba(155, 98, 53, 0.42), transparent 38%),
        radial-gradient(circle at 50% 100%, rgba(51, 32, 24, 0.70), transparent 42%),
        linear-gradient(135deg, #1c100b 0%, #332018 48%, #563622 100%) !important;
    color: var(--elco-text) !important;
}

/* Semua card dashboard dan halaman lain */
.glass-card,
.glass-card--promo,
.manager-content .bg-white,
.manager-content .rounded-3xl,
.manager-content .rounded-2xl,
.manager-content .shadow-soft,
.manager-content .shadow-hover {
    background:
        linear-gradient(135deg, rgba(255, 244, 232, 0.16), rgba(255, 244, 232, 0.07)) !important;
    backdrop-filter: blur(24px) saturate(145%) !important;
    -webkit-backdrop-filter: blur(24px) saturate(145%) !important;
    border: 1px solid rgba(255, 238, 220, 0.18) !important;
    box-shadow:
        0 16px 38px rgba(0, 0, 0, 0.22),
        inset 0 1px 0 rgba(255, 255, 255, 0.13) !important;
}

/* Text semua halaman */
.manager-content h1,
.manager-content h2,
.manager-content h3,
.manager-content h4,
.manager-content p,
.manager-content span,
.manager-content label,
.manager-content .text-gray-900,
.manager-content .text-gray-800,
.manager-content .text-gray-700 {
    color: var(--elco-text) !important;
}

.manager-content .text-gray-600,
.manager-content .text-gray-500,
.manager-content .text-gray-400,
.manager-content small {
    color: var(--elco-text-soft) !important;
}

/* Table semua halaman */
.manager-content table {
    color: var(--elco-text) !important;
}

.manager-content table thead tr,
.manager-content .bg-gray-50 {
    background: rgba(255, 244, 232, 0.08) !important;
}

.manager-content table th,
.manager-content table td {
    color: var(--elco-text-soft) !important;
    border-color: rgba(255, 238, 220, 0.12) !important;
}

.manager-content table tbody tr:hover {
    background: rgba(255, 244, 232, 0.09) !important;
}

/* Input semua halaman */
.manager-content input,
.manager-content select,
.manager-content textarea {
    background: rgba(255, 244, 232, 0.12) !important;
    border-color: rgba(255, 238, 220, 0.18) !important;
    color: var(--elco-text) !important;
}

.manager-content input::placeholder,
.manager-content textarea::placeholder {
    color: rgba(255, 244, 232, 0.45) !important;
}

.manager-content input:focus,
.manager-content select:focus,
.manager-content textarea:focus {
    border-color: rgba(240, 181, 109, 0.55) !important;
    box-shadow: 0 0 0 4px rgba(240, 181, 109, 0.13) !important;
}

/* Button utama */
.manager-content .bg-elco-coffee,
.manager-content .bg-elco-mocha,
.manager-content .btn-promo-solid {
    background: linear-gradient(135deg, #8b542e, #c37a3d, #f0b56d) !important;
    color: #ffffff !important;
    border: 1px solid rgba(255, 238, 220, 0.18) !important;
    box-shadow: 0 10px 24px rgba(0,0,0,0.20) !important;
}

/* Button kecil */
.manager-content .btn-detail,
.manager-content .promo-kelola,
.manager-content .link-all {
    background: rgba(255, 244, 232, 0.13) !important;
    color: #ffe3c4 !important;
    border: 1px solid rgba(255, 238, 220, 0.18) !important;
}

.manager-content .btn-detail:hover,
.manager-content .promo-kelola:hover,
.manager-content .link-all:hover {
    background: rgba(240, 181, 109, 0.22) !important;
    color: #ffffff !important;
}
/* Header kanan atas */
.manager-main > header {
    background:
        linear-gradient(90deg, rgba(28,16,11,0.72), rgba(51,32,24,0.76), rgba(86,54,34,0.74)) !important;
    backdrop-filter: blur(22px);
    -webkit-backdrop-filter: blur(22px);
    border-bottom: 1px solid rgba(255, 238, 220, 0.12) !important;
}

/* Popup kanan atas: notifikasi dan profil */
.manager-main > header .absolute,
.manager-main > header [x-show],
.manager-main > header [x-cloak] {
    background:
        linear-gradient(135deg, rgba(45, 26, 18, 0.96), rgba(86, 54, 34, 0.94)) !important;
    color: #fff4e8 !important;
    border: 1px solid rgba(255, 238, 220, 0.18) !important;
    box-shadow: 0 18px 42px rgba(0,0,0,0.35) !important;
}

/* Text dalam popup supaya nampak */
.manager-main > header .absolute *,
.manager-main > header [x-show] *,
.manager-main > header [x-cloak] * {
    color: #fff4e8 !important;
}

/* Text kecil di popup */
.manager-main > header .absolute .text-gray-400,
.manager-main > header .absolute .text-gray-500,
.manager-main > header .absolute .text-gray-600,
.manager-main > header [x-show] .text-gray-400,
.manager-main > header [x-show] .text-gray-500,
.manager-main > header [x-show] .text-gray-600 {
    color: rgba(255, 244, 232, 0.68) !important;
}

/* Garis dalam popup */
.manager-main > header .absolute .border-gray-100,
.manager-main > header .absolute .border-gray-200,
.manager-main > header [x-show] .border-gray-100,
.manager-main > header [x-show] .border-gray-200 {
    border-color: rgba(255, 238, 220, 0.14) !important;
}

/* Tombol icon kanan atas */
.manager-main > header button {
    background: rgba(255, 244, 232, 0.12) !important;
    color: #fff4e8 !important;
    border: 1px solid rgba(255, 238, 220, 0.14) !important;
}

.manager-main > header button:hover {
    background: rgba(255, 244, 232, 0.20) !important;
}

/* Supaya tulisan Manager Pusat nampak */
.manager-main > header h1,
.manager-main > header h2,
.manager-main > header h3,
.manager-main > header p,
.manager-main > header span,
.manager-main > header div,
.manager-main > header i {
    color: #fff4e8 !important;
}
    </style>
</head>
<body class="text-gray-800 antialiased flex h-screen overflow-hidden font-sans">

    @include('components.manager.sidebar')

    <main class="manager-main flex-1 flex flex-col h-screen overflow-hidden bg-[#F4F6F8]">        
        @include('components.manager.navbar')

    <div class="manager-content flex-1 overflow-y-auto p-4 pb-32 md:p-8 md:pt-0 md:pb-36 hide-scrollbar">
            @if(session('error'))
                <div class="mb-6 flex items-start gap-3 rounded-2xl border border-red-200 bg-red-50 px-5 py-4 text-red-700">
                    <i class="ph-fill ph-x-circle mt-0.5 text-xl"></i>
                    <div class="text-sm font-medium">{{ session('error') }}</div>
                </div>
            @endif

            @if($errors->any())
                <div class="mb-6 flex items-start gap-3 rounded-2xl border border-red-200 bg-red-50 px-5 py-4 text-red-700">
                    <i class="ph-fill ph-warning-circle mt-0.5 text-xl"></i>
                    <div>
                        <p class="text-sm font-semibold">Data belum bisa disimpan.</p>
                        <ul class="mt-1 list-disc pl-4 text-sm">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            @endif

            @yield('content')
        </div>
        
    </main>

    @stack('scripts')

</body>
</html>
