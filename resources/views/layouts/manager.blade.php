<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ELCO - Manager Dashboard</title>
    
    <link href="[https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600&family=Poppins:wght@400;500;600;700&display=swap](https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600&family=Poppins:wght@400;500;600;700&display=swap)" rel="stylesheet">

    <script src="[https://unpkg.com/@phosphor-icons/web](https://unpkg.com/@phosphor-icons/web)"></script>
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    @stack('styles')
</head>
<body class="text-gray-800 antialiased flex h-screen overflow-hidden font-sans">

    @include('components.manager.sidebar')

    <main class="flex-1 flex flex-col h-screen overflow-hidden bg-[#F4F6F8]">
        
        @include('components.manager.navbar')

        <div class="flex-1 overflow-y-auto p-8 pt-0 hide-scrollbar">
            @yield('content.manager')
        </div>
        
    </main>

    @stack('scripts')
</body>
</html>