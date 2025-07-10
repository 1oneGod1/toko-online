@can('is-admin')
<!-- Admin dropdown menu component -->
<div class="dropdown">
    <button class="btn btn-primary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
        Admin Toko
    </button>
    <ul class="dropdown-menu">
        <li><a class="dropdown-item" href="{{ route('admin.dashboard') }}">Dashboard Admin</a></li>
        <li><a class="dropdown-item" href="{{ route('admin.orders.index') }}">Manajemen Pesanan</a></li>
        <li><a class="dropdown-item" href="{{ route('products.index') }}">Manajemen Produk</a></li>
        <li><a class="dropdown-item" href="{{ route('categories.index') }}">Manajemen Kategori</a></li>
        <li><a class="dropdown-item" href="{{ route('admin.users.index') }}">Manajemen Pengguna</a></li>
        <li><a class="dropdown-item" href="{{ route('admin.landing.index') }}">Pengaturan Halaman</a></li>
        <li><hr class="dropdown-divider"></li>
        <li><a class="dropdown-item" href="{{ route('home') }}">Kembali ke Toko</a></li>
    </ul>
</div>
@endcan