<div>
    <!-- Top Announcement Bar -->
    <div class="top-bar">
        NEW CUSTOMERS SAVE 10% WITH THE CODE: GET10
    </div>

    <!-- Main Navbar -->
    <nav class="navbar navbar-expand-lg modern-navbar">
        <div class="container-fluid">
            <!-- Logo -->
            <a class="navbar-brand" href="{{ route('home') }}">Toko Online</a>

            <!-- Centered Navigation Links -->
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav mx-auto">
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}" href="{{ route('home') }}">Beranda</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('products.index') ? 'active' : '' }}" href="{{ route('products.index') }}">Produk</a>
                    </li>
                    <!-- Add other main navigation links here -->
                </ul>
            </div>

            <!-- Right-side Action Icons -->
            <div class="navbar-actions">
                <button class="btn" id="theme-switcher" type="button" aria-label="Toggle theme">
                    <i class="bi bi-moon-fill"></i>
                </button>

                <a href="#" class="nav-link" aria-label="Search">
                    <i class="bi bi-search"></i>
                </a>

                @guest
                    <a href="{{ route('login') }}" class="nav-link" aria-label="Login">
                        <i class="bi bi-person"></i>
                    </a>
                @else
                    <div class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false" aria-label="User menu">
                            <i class="bi bi-person"></i>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                            <li><a class="dropdown-item" href="#">Profil Saya</a></li>
                            <li><a class="dropdown-item" href="{{ route('orders.index') }}">Riwayat Pesanan</a></li>
                            @can('is-admin')
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item" href="{{ route('admin.dashboard') }}">Admin Dashboard</a></li>
                            @endcan
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <a class="dropdown-item" href="{{ route('logout') }}"
                                   onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                    Keluar
                                </a>
                                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                    @csrf
                                </form>
                            </li>
                        </ul>
                    </div>
                @endguest

                <a href="{{ route('cart.index') }}" class="nav-link" aria-label="Cart">
                    <i class="bi bi-bag"></i>
                    @auth
                        @if(session('cart') && count(session('cart')) > 0)
                            <span class="badge bg-primary rounded-pill" style="font-size: 0.6rem; position: relative; top: -10px; left: -5px;">
                                {{ count(session('cart')) }}
                            </span>
                        @endif
                    @endauth
                </a>
                
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
            </div>
        </div>
    </nav>
</div>