<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ?? 'Admin Panel' }} - Toko Online</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap" rel="stylesheet" />
    
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Custom Styles -->
    <style>
        body { font-family: 'Figtree', sans-serif; background-color: #f5f5f5; }
        .navbar-custom { background-color: #0D47A1; }
        .btn-manage { background-color: #FFAB00; color: #000; }
        .btn-save { background-color: #0D47A1; color: #fff; }
        .sidebar { background-color: #fff; min-height: calc(100vh - 56px); }
        .sidebar .nav-link { color: #333; padding: 0.5rem 1rem; }
        .sidebar .nav-link:hover { background-color: #f0f0f0; }
        .sidebar .nav-link.active { background-color: #0D47A1; color: #fff; }
    </style>
</head>
<body class="d-flex flex-column min-vh-100">
    <!-- Top Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark navbar-custom">
        <div class="container-fluid">
            <a class="navbar-brand fw-bold" href="{{ route('home') }}">Toko Online</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto align-items-center">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('home') }}">Lihat Toko</a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            {{ Auth::user()->name }}
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li><a class="dropdown-item" href="{{ route('profile.edit') }}">Edit Profil</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <form action="{{ route('logout') }}" method="POST">
                                    @csrf
                                    <button type="submit" class="dropdown-item">Keluar</button>
                                </form>
                            </li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <div class="col-md-3 col-lg-2 px-0 sidebar border-end shadow-sm">
                <div class="list-group rounded-0 border-0">
                    <a href="{{ route('admin.dashboard') }}" class="list-group-item list-group-item-action border-0 {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                        <i class="bi bi-speedometer2 me-2"></i> Dashboard
                    </a>
                    
                    <!-- Highlighted Management Links -->
                    <div class="list-group-item bg-light fw-bold text-uppercase small text-muted ps-3 py-2">
                        Manajemen Utama
                    </div>
                    
                    <a href="{{ route('admin.stock.index') }}" class="list-group-item list-group-item-action border-0 {{ request()->routeIs('admin.stock.*') ? 'active' : '' }}">
                        <i class="bi bi-boxes me-2"></i> Manajemen Stok
                    </a>
                    
                    <a href="{{ route('admin.orders.index') }}" class="list-group-item list-group-item-action border-0 {{ request()->routeIs('admin.orders.*') ? 'active' : '' }}">
                        <i class="bi bi-cart-check me-2"></i> Manajemen Pesanan
                    </a>
                    
                    <!-- Other Management Links -->
                    <div class="list-group-item bg-light fw-bold text-uppercase small text-muted ps-3 py-2">
                        Lainnya
                    </div>
                    
                    <a href="{{ route('products.index') }}" class="list-group-item list-group-item-action border-0 {{ request()->routeIs('products.*') && !request()->routeIs('products.show') ? 'active' : '' }}">
                        <i class="bi bi-box-seam me-2"></i> Manajemen Produk
                    </a>
                    <a href="{{ route('categories.index') }}" class="list-group-item list-group-item-action border-0 {{ request()->routeIs('categories.*') ? 'active' : '' }}">
                        <i class="bi bi-tag me-2"></i> Manajemen Kategori
                    </a>
                    <a href="{{ route('admin.users.index') }}" class="list-group-item list-group-item-action border-0 {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                        <i class="bi bi-people me-2"></i> Manajemen Pengguna
                    </a>
                    <a href="{{ route('admin.landing.index') }}" class="list-group-item list-group-item-action border-0 {{ request()->routeIs('admin.landing.*') ? 'active' : '' }}">
                        <i class="bi bi-window me-2"></i> Halaman Utama
                    </a>
                </div>
            </div>
            
            <!-- Main content -->
            <div class="col-md-9 col-lg-10 py-3">
                {{ $slot }}
            </div>
        </div>
    </div>

    <footer class="bg-white text-center text-muted py-3 mt-auto border-top">
        <div class="container">
            <p class="mb-0">&copy; {{ date('Y') }} Toko Online. Admin Panel.</p>
        </div>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>