<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <meta name="description" content="Campus Connect helps students and staff find skills, jobs, and trusted campus services within their university community.">

    <title>@yield('title', 'Campus Connect')</title>

    {{-- Font preconnect --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    {{-- Google Font --}}
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    {{-- Tailwind --}}
    <script src="https://cdn.tailwindcss.com"></script>

    {{-- Font Awesome --}}
    <link rel="stylesheet"
          href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"
          referrerpolicy="no-referrer">

    <style>
        body {
            font-family: 'Inter', system-ui, sans-serif;
            overflow-x: hidden;
        }

        html {
            overflow-x: hidden;
        }

        @media (max-width: 768px) {
            .animate-fade-in-up,
            .animate-pulse-slow {
                animation: none !important;
                opacity: 1 !important;
                transform: none !important;
            }
        }
    </style>

    @stack('styles')
</head>

<body class="bg-white">
    <main>
        @yield('content')
    </main>

    @stack('scripts')
</body>
</html>