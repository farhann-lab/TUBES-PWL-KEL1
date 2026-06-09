<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ELCO - Admin Cabang</title>
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

        /* ===== ADMIN DARK GLASS THEME - VISUAL ONLY ===== */
:root {
    --admin-bg-1: #1c100b;
    --admin-bg-2: #332018;
    --admin-bg-3: #563622;
    --admin-card: rgba(255, 244, 232, 0.13);
    --admin-card-strong: rgba(255, 244, 232, 0.18);
    --admin-border: rgba(255, 238, 220, 0.18);
    --admin-text: #fff4e8;
    --admin-soft: rgba(255, 244, 232, 0.76);
    --admin-muted: rgba(255, 244, 232, 0.55);
    --admin-accent: #f0b56d;
    --admin-caramel: #c37a3d;
    --admin-green: #8ff0bf;
    --admin-red: #ff7b6e;
    --admin-yellow: #ffd28a;
    --admin-cyan: #22d3ee;
}

/* Background utama admin */
body,
body > main {
    background:
        radial-gradient(circle at 15% 10%, rgba(240, 181, 109, 0.20), transparent 32%),
        radial-gradient(circle at 85% 18%, rgba(155, 98, 53, 0.38), transparent 36%),
        radial-gradient(circle at 50% 100%, rgba(86, 54, 34, 0.60), transparent 42%),
        linear-gradient(135deg, var(--admin-bg-1) 0%, var(--admin-bg-2) 48%, var(--admin-bg-3) 100%) !important;
    color: var(--admin-text) !important;
}

/* Header admin */
body > main > header {
    background:
        linear-gradient(90deg, rgba(28,16,11,0.72), rgba(51,32,24,0.76), rgba(86,54,34,0.74)) !important;
    backdrop-filter: blur(22px);
    -webkit-backdrop-filter: blur(22px);
    border-bottom: 1px solid rgba(255, 238, 220, 0.12) !important;
}

body > main > header h1,
body > main > header p,
body > main > header span,
body > main > header i,
body > main > header div {
    color: var(--admin-text) !important;
}

/* Sidebar admin */
body > aside {
    background:
        radial-gradient(circle at 18% 8%, rgba(208, 138, 73, 0.30), transparent 34%),
        radial-gradient(circle at 85% 90%, rgba(91, 54, 35, 0.55), transparent 38%),
        linear-gradient(180deg, #1c100b 0%, #2a1811 45%, #3b2418 100%) !important;
    border-right: 1px solid rgba(255, 238, 220, 0.14) !important;
    box-shadow: 12px 0 36px rgba(0, 0, 0, 0.32) !important;
}

body > aside nav,
body > aside .flex-1,
body > aside .h-24 {
    background: transparent !important;
}

body > aside .w-10.h-10 {
    background: linear-gradient(135deg, #9b6235, #d08a49, #f0b56d) !important;
    box-shadow: 0 10px 24px rgba(0,0,0,0.25) !important;
}

body > aside span,
body > aside p,
body > aside i,
body > aside div {
    color: rgba(255, 244, 232, 0.86) !important;
}

body > aside .text-gray-400 {
    color: rgba(255, 244, 232, 0.58) !important;
}

body > aside nav a,
body > aside nav button {
    color: rgba(255, 244, 232, 0.76) !important;
    background: transparent !important;
}

body > aside nav a:hover,
body > aside nav button:hover {
    background: rgba(255, 244, 232, 0.12) !important;
    color: #ffffff !important;
}

body > aside nav a.bg-\[\#F6F3F0\],
body > aside nav a[class*="bg-[#F6F3F0]"] {
    background:
        linear-gradient(135deg, rgba(255, 244, 232, 0.24), rgba(208, 138, 73, 0.24)) !important;
    color: #ffffff !important;
    border: 1px solid rgba(255, 238, 220, 0.18) !important;
    box-shadow:
        inset 0 1px 0 rgba(255,255,255,0.16),
        0 12px 24px rgba(0,0,0,0.18) !important;
}

body > aside > button div {
    border-left-color: #f0b56d !important;
}

/* Semua card admin */
body > main .bg-white,
body > main .rounded-3xl,
body > main .rounded-2xl,
body > main .shadow-soft,
body > main .shadow-hover {
    background:
        linear-gradient(135deg, rgba(255, 244, 232, 0.16), rgba(255, 244, 232, 0.07)) !important;
    backdrop-filter: blur(24px) saturate(145%) !important;
    -webkit-backdrop-filter: blur(24px) saturate(145%) !important;
    border: 1px solid rgba(255, 238, 220, 0.18) !important;
    box-shadow:
        0 16px 38px rgba(0, 0, 0, 0.22),
        inset 0 1px 0 rgba(255, 255, 255, 0.13) !important;
}

/* Text admin */
body > main h1,
body > main h2,
body > main h3,
body > main h4,
body > main p,
body > main span,
body > main label,
body > main .text-gray-900,
body > main .text-gray-800,
body > main .text-gray-700 {
    color: var(--admin-text) !important;
}

body > main .text-gray-600,
body > main .text-gray-500,
body > main .text-gray-400,
body > main small {
    color: var(--admin-soft) !important;
}

/* Table admin */
body > main table {
    color: var(--admin-text) !important;
}

body > main table thead tr,
body > main .bg-gray-50 {
    background: rgba(255, 244, 232, 0.08) !important;
}

body > main table th,
body > main table td {
    color: var(--admin-soft) !important;
    border-color: rgba(255, 238, 220, 0.12) !important;
}

body > main table tbody tr:hover {
    background: rgba(255, 244, 232, 0.09) !important;
}

/* Input, select, textarea admin */
body > main input,
body > main select,
body > main textarea {
    background: rgba(255, 244, 232, 0.12) !important;
    border-color: rgba(255, 238, 220, 0.18) !important;
    color: var(--admin-text) !important;
}

body > main input::placeholder,
body > main textarea::placeholder {
    color: rgba(255, 244, 232, 0.45) !important;
}

body > main input:focus,
body > main select:focus,
body > main textarea:focus {
    border-color: rgba(240, 181, 109, 0.55) !important;
    box-shadow: 0 0 0 4px rgba(240, 181, 109, 0.13) !important;
}

/* Icon card admin */
body > main .w-12.h-12,
body > main .w-10.h-10,
body > main .w-9.h-9,
body > main .w-8.h-8 {
    background: rgba(255, 244, 232, 0.14) !important;
    border: 1.5px solid rgba(255, 244, 232, 0.45) !important;
    box-shadow:
        0 0 14px rgba(255, 244, 232, 0.10),
        inset 0 1px 0 rgba(255,255,255,0.20) !important;
}

body > main .text-orange-500 { color: #f0b56d !important; }
body > main .text-red-500 { color: #ff7b6e !important; }
body > main .text-yellow-500 { color: #ffd28a !important; }
body > main .text-blue-500 { color: #22d3ee !important; }
body > main .text-emerald-600,
body > main .text-emerald-700 {
    color: #8ff0bf !important;
}

/* Badge status */
body > main .bg-emerald-100 {
    background: linear-gradient(135deg, #16a34a, #22c55e) !important;
    color: #ffffff !important;
    border: 1px solid rgba(187, 247, 208, 0.65) !important;
}

body > main .bg-yellow-100 {
    background: linear-gradient(135deg, #ca8a04, #eab308) !important;
    color: #ffffff !important;
    border: 1px solid rgba(254, 240, 138, 0.65) !important;
}

body > main .bg-red-100 {
    background: linear-gradient(135deg, #dc2626, #ef4444) !important;
    color: #ffffff !important;
    border: 1px solid rgba(254, 202, 202, 0.65) !important;
}

/* Button utama */
body > main .bg-elco-coffee,
body > main .bg-elco-mocha,
body > main .bg-gradient-to-r {
    background: linear-gradient(135deg, #8b542e, #c37a3d, #f0b56d) !important;
    color: #ffffff !important;
    border: 1px solid rgba(255, 238, 220, 0.18) !important;
    box-shadow: 0 10px 24px rgba(0,0,0,0.20) !important;
}

/* Dropdown notifikasi dan profil */
#notifDropdown,
#profileDropdown {
    background:
        linear-gradient(135deg, rgba(45, 26, 18, 0.96), rgba(86, 54, 34, 0.94)) !important;
    color: var(--admin-text) !important;
    border: 1px solid rgba(255, 238, 220, 0.18) !important;
    box-shadow: 0 18px 42px rgba(0,0,0,0.35) !important;
}

#notifDropdown *,
#profileDropdown * {
    color: var(--admin-text) !important;
}

#notifDropdown a:hover,
#profileDropdown a:hover,
#profileDropdown button:hover {
    background: rgba(255, 244, 232, 0.10) !important;
}

    </style>
</head>
<body class="text-gray-800 antialiased flex h-screen overflow-hidden font-sans">

    {{-- Sidebar Admin --}}
    @include('components.admin.sidebar')

    <main class="flex-1 flex flex-col h-screen overflow-hidden bg-[#F4F6F8]">
        @include('components.admin.navbar')
        <div class="flex-1 overflow-y-auto p-8 pt-0 pb-32 hide-scrollbar">
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
