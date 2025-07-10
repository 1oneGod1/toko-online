<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ?? 'Toko Online' }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap" rel="stylesheet" />
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;700&display=swap" rel="stylesheet">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" xintegrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">

    <!-- Custom Styles -->
    <style>
        body { font-family: 'Figtree', sans-serif; background-color: #f5f5f5; }
        .navbar-custom { background-color: #0D47A1; }
        .btn-manage { background-color: #FFAB00; color: #000; }
        .btn-save { background-color: #0D47A1; color: #fff; }
        .product-card { transition: transform .2s, box-shadow .2s; border: 1px solid #eee; border-radius: .5rem; }
        .product-card:hover { transform: translateY(-5px); box-shadow: 0 8px 25px rgba(0,0,0,0.1); }
    </style>
    <link rel="stylesheet" href="{{ asset('css/dark-mode.css') }}">
    <link rel="stylesheet" href="{{ asset('css/modern-navbar.css') }}">
</head>
<body class="d-flex flex-column min-vh-100">
    <div class="min-vh-100">
        <x-layouts.navbar />
        <main>
            {{ $slot }}
        </main>
    </div>

    <x-layouts.footer />

    @vite('resources/js/app.js')
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" xintegrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <script src="{{ asset('js/theme-switcher.js') }}"></script>

    <!-- Custom Scripts -->
    @stack('scripts')
</body>
</html>
