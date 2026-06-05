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
    </style>
</head>
<body class="h-screen overflow-hidden bg-[#F4F6F8] text-gray-800 antialiased font-sans">
    <div class="flex h-full min-w-0">
        @include('components.kasir.sidebar')

        <main class="flex min-w-0 flex-1 flex-col overflow-hidden">
            @include('components.kasir.navbar')

            <section class="flex-1 overflow-y-auto px-4 pb-6 pt-0 md:px-8 md:pb-8 hide-scrollbar">
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