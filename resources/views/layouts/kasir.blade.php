<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ELCO - Kasir</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600&family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">

    <script src="https://unpkg.com/@phosphor-icons/web"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')

    <style>
        .swal-elco-popup {
            border-radius: 24px !important;
            font-family: 'Inter', sans-serif !important;
        }

        .swal-elco-confirm,
        .swal-elco-cancel {
            border-radius: 12px !important;
            font-weight: 600 !important;
            padding: 10px 24px !important;
        }

        .swal-elco-cancel {
            background: #f3f4f6 !important;
            color: #4b5563 !important;
            font-weight: 500 !important;
        }

        :root {
            --cashier-bg-1: #17100d;
            --cashier-bg-2: #2b1a13;
            --cashier-bg-3: #5a3924;
            --cashier-card: rgba(255, 246, 235, 0.13);
            --cashier-card-strong: rgba(255, 246, 235, 0.19);
            --cashier-border: rgba(255, 238, 220, 0.18);
            --cashier-text: #fff6eb;
            --cashier-soft: rgba(255, 246, 235, 0.74);
            --cashier-muted: rgba(255, 246, 235, 0.54);
            --cashier-accent: #f0b56d;
            --cashier-caramel: #c37a3d;
            --cashier-teal: #2dd4bf;
            --cashier-red: #ff7b6e;
            --cashier-yellow: #ffd28a;
        }

        body,
        .kasir-main {
            background:
                radial-gradient(circle at 14% 8%, rgba(45, 212, 191, 0.12), transparent 28%),
                radial-gradient(circle at 86% 12%, rgba(240, 181, 109, 0.24), transparent 34%),
                radial-gradient(circle at 55% 100%, rgba(90, 57, 36, 0.70), transparent 42%),
                linear-gradient(135deg, var(--cashier-bg-1) 0%, var(--cashier-bg-2) 48%, var(--cashier-bg-3) 100%) !important;
            color: var(--cashier-text) !important;
        }

        .kasir-content {
            background: transparent !important;
        }

        .kasir-main > header {
            background: linear-gradient(90deg, rgba(23,16,13,0.72), rgba(43,26,19,0.78), rgba(90,57,36,0.72)) !important;
            border-bottom: 1px solid rgba(255, 238, 220, 0.12);
            backdrop-filter: blur(22px);
            -webkit-backdrop-filter: blur(22px);
        }

        .kasir-main > header h1,
        .kasir-main > header p,
        .kasir-main > header span,
        .kasir-main > header i {
            color: var(--cashier-text) !important;
        }

        .kasir-content .bg-white,
        .kasir-content .rounded-3xl,
        .kasir-content .rounded-2xl,
        .kasir-content .shadow-soft,
        .kasir-content .shadow-hover {
            background: linear-gradient(135deg, rgba(255, 246, 235, 0.16), rgba(255, 246, 235, 0.07)) !important;
            border: 1px solid rgba(255, 238, 220, 0.18) !important;
            box-shadow: 0 16px 38px rgba(0, 0, 0, 0.22), inset 0 1px 0 rgba(255,255,255,0.12) !important;
            backdrop-filter: blur(24px) saturate(145%);
            -webkit-backdrop-filter: blur(24px) saturate(145%);
        }

        .kasir-content h1,
        .kasir-content h2,
        .kasir-content h3,
        .kasir-content h4,
        .kasir-content p,
        .kasir-content span,
        .kasir-content label,
        .kasir-content td,
        .kasir-content th,
        .kasir-content .text-gray-900,
        .kasir-content .text-gray-800,
        .kasir-content .text-gray-700 {
            color: var(--cashier-text) !important;
        }

        .kasir-content .text-gray-600,
        .kasir-content .text-gray-500,
        .kasir-content .text-gray-400 {
            color: var(--cashier-soft) !important;
        }

        .kasir-content input,
        .kasir-content select,
        .kasir-content textarea {
            background: rgba(255, 246, 235, 0.12) !important;
            border-color: rgba(255, 238, 220, 0.18) !important;
            color: var(--cashier-text) !important;
        }

        .kasir-content option {
            color: #2b1a13;
        }

        .kasir-content .bg-elco-coffee,
        .kasir-content .bg-elco-mocha,
        .kasir-content .bg-gradient-to-r {
            background: linear-gradient(135deg, #8b542e, var(--cashier-caramel), var(--cashier-accent)) !important;
            color: #ffffff !important;
            border: 1px solid rgba(255, 238, 220, 0.18) !important;
        }

        .kasir-content .text-elco-coffee {
            color: #ffdcae !important;
        }

        .kasir-content .peer:checked + div,
        .kasir-content .peer-checked\:bg-elco-cream {
            background: rgba(45, 212, 191, 0.16) !important;
            border-color: rgba(45, 212, 191, 0.70) !important;
            color: #ccfbf1 !important;
        }

        .kasir-content table thead tr,
        .kasir-content .bg-gray-50 {
            background: rgba(255, 246, 235, 0.08) !important;
        }

        .kasir-content tr,
        .kasir-content .border-gray-50,
        .kasir-content .border-gray-100,
        .kasir-content .border-gray-200 {
            border-color: rgba(255, 238, 220, 0.12) !important;
        }

        .kasir-content .bg-emerald-100 {
            background: linear-gradient(135deg, #0f766e, #14b8a6) !important;
            color: #ecfeff !important;
        }

        .kasir-content .bg-yellow-100,
        .kasir-content .bg-orange-100,
        .kasir-content .bg-orange-50 {
            background: rgba(240, 181, 109, 0.18) !important;
            color: #ffe6bd !important;
        }

        .kasir-content .bg-red-100,
        .kasir-content .bg-red-50 {
            background: rgba(255, 123, 110, 0.16) !important;
            color: #ffd5d0 !important;
        }

        #profileDropdown {
            background: linear-gradient(135deg, rgba(37, 23, 17, 0.96), rgba(90, 57, 36, 0.94)) !important;
            border: 1px solid rgba(255, 238, 220, 0.18) !important;
            box-shadow: 0 18px 42px rgba(0,0,0,0.35) !important;
        }

        #profileDropdown * {
            color: var(--cashier-text) !important;
        }

        #profileDropdown a:hover,
        #profileDropdown button:hover {
            background: rgba(255, 246, 235, 0.10) !important;
        }
    </style>
</head>
<body class="h-screen overflow-hidden bg-[#F4F6F8] text-gray-800 antialiased font-sans">
    <div class="flex h-full min-w-0">
        @include('components.kasir.sidebar')

        <main class="kasir-main flex min-w-0 flex-1 flex-col overflow-hidden">
            @include('components.kasir.navbar')

            <section class="kasir-content flex-1 overflow-y-auto px-4 pb-32 pt-0 md:px-8 md:pb-36 hide-scrollbar">
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
                            <p class="text-sm font-semibold">Data belum bisa diproses.</p>
                            <ul class="mt-1 list-disc pl-4 text-sm">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                @endif

                @yield('content')
            </section>
        </main>
    </div>

    <script>
        window.addEventListener('pageshow', function (event) {
            if (event.persisted) {
                window.location.reload();
            }
        });
    </script>
    @stack('scripts')
</body>
</html>
