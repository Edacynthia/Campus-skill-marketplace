<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
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
</head>
<body class="bg-gray-50">

    <!-- Fixed Navbar Component -->
    @include('components.navbar')

    <!-- Main Content -->
    <main class="min-h-[calc(100vh-73px)]">
        @yield('content')
    </main>

    <!-- Scripts Stack -->
    @stack('scripts')

</body>
</html>