<!DOCTYPE html>
<html lang="en">
<head>
    <meta name="description" content="Campus Connect helps students discover campus skills, jobs, bookings, applications, and messages in one place.">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'Campus Connect' }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap');
        body { 
            font-family: 'Inter', system_ui, sans-serif; 
            overflow-x: hidden;
        }
        html {
            overflow-x: hidden;
        }
    </style>
    @stack('styles')
</head>
<body class="bg-gray-50">

    <!-- Fixed Navbar Component -->
    @include('components.navbar')

    <!-- ==================== FLASH MESSAGES ==================== -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-6">
        @if(session('success'))
            <div id="flash-success" class="bg-emerald-50 border border-emerald-200 text-emerald-800 px-6 py-4 rounded-2xl flex items-center justify-between gap-3 mb-4 shadow-sm">
                <div class="flex items-center gap-3">
                    <i class="fa-solid fa-circle-check text-emerald-600 text-xl flex-shrink-0"></i>
                    <p class="font-medium">{{ session('success') }}</p>
                </div>
                <button onclick="dismissFlash('flash-success')"
                        class="text-emerald-400 hover:text-emerald-700 transition-colors p-1 rounded-full hover:bg-emerald-100 flex-shrink-0"
                        aria-label="Dismiss">
                    <i class="fa-solid fa-xmark text-base"></i>
                </button>
            </div>
        @endif

        @if(session('error'))
            <div id="flash-error" class="bg-red-50 border border-red-200 text-red-800 px-6 py-4 rounded-2xl flex items-center justify-between gap-3 mb-4 shadow-sm">
                <div class="flex items-center gap-3">
                    <i class="fa-solid fa-circle-exclamation text-red-600 text-xl flex-shrink-0"></i>
                    <p class="font-medium">{{ session('error') }}</p>
                </div>
                <button onclick="dismissFlash('flash-error')"
                        class="text-red-400 hover:text-red-700 transition-colors p-1 rounded-full hover:bg-red-100 flex-shrink-0"
                        aria-label="Dismiss">
                    <i class="fa-solid fa-xmark text-base"></i>
                </button>
            </div>
        @endif
    </div>

    <!-- Main Content -->
    <main class="min-h-[calc(100vh-73px)]">
        @yield('content')
    </main>

    <!-- Scripts Stack -->
    @stack('scripts')

    <script>
        function dismissFlash(id) {
            const el = document.getElementById(id);
            if (!el) return;
            el.style.transition = 'opacity 0.4s ease, transform 0.4s ease, max-height 0.5s ease, margin 0.5s ease, padding 0.5s ease';
            el.style.opacity = '0';
            el.style.transform = 'translateY(-8px)';
            el.style.overflow = 'hidden';
            el.style.maxHeight = el.scrollHeight + 'px';
            setTimeout(() => {
                el.style.maxHeight = '0';
                el.style.marginBottom = '0';
                el.style.paddingTop = '0';
                el.style.paddingBottom = '0';
            }, 350);
            setTimeout(() => el.remove(), 850);
        }

        // Auto-dismiss after 20 seconds
        @if(session('success'))
            setTimeout(() => dismissFlash('flash-success'), 20000);
        @endif
        @if(session('error'))
            setTimeout(() => dismissFlash('flash-error'), 20000);
        @endif
    </script>

</body>
</html>