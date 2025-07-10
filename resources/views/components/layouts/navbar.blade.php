<nav class="navbar navbar-expand-lg navbar-dark navbar-custom shadow-sm sticky-top">
    <div class="container">
        <a class="navbar-brand fw-bold" href="{{ route('home') }}">Toko Online</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav me-auto">
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}" href="{{ route('home') }}">Beranda</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('products.*') ? 'active' : '' }}" href="{{ route('products.index') }}">Produk</a>
                </li>
                
                @can('is-admin')
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle {{ request()->is('admin*') ? 'active' : '' }}" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        Admin Toko
                    </a>
                    <ul class="dropdown-menu">
                        @if(auth()->check() && auth()->user()->role === 'admin')
                            <li><a class="dropdown-item" href="{{ route('admin.dashboard') }}"><i class="bi bi-speedometer2"></i> Dashboard Admin</a></li>
                            <li><a class="dropdown-item" href="{{ route('admin.orders.index') }}"><i class="bi bi-list-check"></i> Manajemen Order</a></li>
                            <li><a class="dropdown-item" href="{{ route('admin.stock.index') }}"><i class="bi bi-box-seam"></i> Manajemen Stok</a></li>
                            <li><a class="dropdown-item" href="{{ route('products.index') }}"><i class="bi bi-box"></i> Manajemen Produk</a></li>
                            <li><a class="dropdown-item" href="{{ route('categories.index') }}"><i class="bi bi-tags"></i> Manajemen Kategori</a></li>
                            <li><a class="dropdown-item" href="{{ route('admin.users.index') }}"><i class="bi bi-people"></i> Manajemen Pengguna</a></li>
                            <li><a class="dropdown-item" href="{{ route('admin.landing.index') }}"><i class="bi bi-brush"></i> Pengaturan Halaman</a></li>
                            <li><hr class="dropdown-divider"></li>
                        @endif
                        <li><a class="dropdown-item" href="{{ route('orders.index') }}">Riwayat Pesanan</a></li>
                        <li><a class="dropdown-item" href="{{ route('logout') }}"
                               onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Keluar</a></li>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">@csrf</form>
                    </ul>
                </li>
                @endcan
            </ul>
            
            <ul class="navbar-nav ms-auto align-items-center">
                @auth
                    <li class="nav-item">
                        <a class="nav-link position-relative {{ request()->routeIs('cart.index') ? 'active' : '' }}" href="{{ route('cart.index') }}">
                            Keranjang
                            @php
                                $cartCount = \App\Models\Cart::where('user_id', auth()->id())->sum('quantity');
                            @endphp
                            @if($cartCount > 0)
                                <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                                    {{ $cartCount }}
                                </span>
                            @endif
                        </a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            {{ Auth::user()->name }}
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li><a class="dropdown-item" href="{{ route('orders.index') }}">Riwayat Pesanan</a></li>
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
                @else
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('login') }}">{{ __('Masuk') }}</a>
                    </li>
                    @if (Route::has('register'))
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('register') }}">{{ __('Daftar') }}</a>
                        </li>
                    @endif
                @endauth
            </ul>
        </div>
    </div>
</nav>