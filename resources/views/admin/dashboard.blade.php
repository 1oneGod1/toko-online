<x-layouts.admin title="Dashboard Admin">
    <div class="container py-4">
        <h1 class="h3 mb-4">Dashboard Admin</h1>
        
        <!-- Statistik Utama -->
        <div class="row mb-4">
            <div class="col-md-3">
                <div class="card text-bg-primary mb-3">
                    <div class="card-body">
                        <h5 class="card-title">Total Pesanan</h5>
                        <p class="card-text fs-2">{{ $stats['total_orders'] }}</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card text-bg-warning mb-3">
                    <div class="card-body">
                        <h5 class="card-title">Pesanan Pending</h5>
                        <p class="card-text fs-2">{{ $stats['pending_orders'] }}</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card text-bg-success mb-3">
                    <div class="card-body">
                        <h5 class="card-title">Total Produk</h5>
                        <p class="card-text fs-2">{{ $stats['total_products'] }}</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card text-bg-info mb-3">
                    <div class="card-body">
                        <h5 class="card-title">Total Pengguna</h5>
                        <p class="card-text fs-2">{{ $stats['total_users'] }}</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <!-- Pesanan Terbaru -->
            <div class="col-md-6 mb-4">
                <div class="card shadow-sm h-100">
                    <div class="card-header bg-white">
                        <div class="d-flex justify-content-between align-items-center">
                            <h5 class="mb-0">Pesanan Terbaru</h5>
                            <a href="{{ route('admin.orders.index') }}" class="btn btn-sm btn-outline-primary">Lihat Semua</a>
                        </div>
                    </div>
                    <div class="card-body">
                        <table class="table table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th>ID</th>
                                    <th>Pelanggan</th>
                                    <th>Status</th>
                                    <th>Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($recent_orders as $order)
                                    <tr>
                                        <td>#{{ $order->id }}</td>
                                        <td>{{ $order->user->name }}</td>
                                        <td>
                                            <span class="badge rounded-pill text-bg-{{ $order->status == 'pending' ? 'warning' : ($order->status == 'completed' ? 'success' : 'info') }}">
                                                {{ ucfirst($order->status) }}
                                            </span>
                                        </td>
                                        <td>Rp {{ number_format($order->total_amount) }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center">Belum ada pesanan.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            
            <!-- Produk dengan Stok Menipis -->
            <div class="col-md-6 mb-4">
                <div class="card shadow-sm h-100">
                    <div class="card-header bg-white">
                        <div class="d-flex justify-content-between align-items-center">
                            <h5 class="mb-0">Produk dengan Stok Menipis</h5>
                            <a href="{{ route('products.index') }}" class="btn btn-sm btn-outline-primary">Lihat Semua</a>
                        </div>
                    </div>
                    <div class="card-body">
                        <table class="table table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th>Nama Produk</th>
                                    <th>Kategori</th>
                                    <th>Sisa Stok</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($low_stock_products as $product)
                                    <tr>
                                        <td>{{ $product->name }}</td>
                                        <td>{{ $product->category->name }}</td>
                                        <td>
                                            <span class="badge text-bg-{{ $product->stock == 0 ? 'danger' : 'warning' }}">
                                                {{ $product->stock }}
                                            </span>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="text-center">Tidak ada produk dengan stok menipis.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Link Cepat ke Menu Admin -->
        <div class="row mb-4">
            <div class="col-md-12">
                <div class="card shadow-sm">
                    <div class="card-header bg-white">
                        <h5 class="mb-0">Menu Admin</h5>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-3">
                                <a href="{{ route('products.create') }}" class="btn btn-outline-primary w-100 p-3">
                                    <i class="bi bi-plus-circle"></i> Tambah Produk
                                </a>
                            </div>
                            <div class="col-md-3">
                                <a href="{{ route('products.index') }}" class="btn btn-outline-success w-100 p-3">
                                    <i class="bi bi-box-seam"></i> Manajemen Produk
                                </a>
                            </div>                            <div class="col-md-3">
                                <a href="{{ route('products.create') }}" class="btn btn-outline-primary w-100 p-3">
                                    <i class="bi bi-plus-circle"></i> Tambah Produk Baru
                                </a>
                            </div>
                            <div class="col-md-3">
                                <a href="{{ route('products.index') }}" class="btn btn-outline-success w-100 p-3">
                                    <i class="bi bi-box-seam"></i> Manajemen Stok
                                </a>
                            </div>
                            <div class="col-md-3">
                                <a href="{{ route('admin.orders.index') }}" class="btn btn-outline-warning w-100 p-3">
                                    <i class="bi bi-list-check"></i> Kelola Pesanan
                                </a>
                            </div>
                            <div class="col-md-3">
                                <a href="{{ route('admin.users.index') }}" class="btn btn-outline-info w-100 p-3">
                                    <i class="bi bi-people"></i> Kelola Pengguna
                                </a>
                            </div>
                        </div>
                        
                        <div class="row g-3 mt-2">
                            <div class="col-md-3">
                                <a href="{{ route('categories.index') }}" class="btn btn-outline-secondary w-100 p-3">
                                    <i class="bi bi-tags"></i> Kelola Kategori
                                </a>
                            </div>
                            <div class="col-md-3">
                                <a href="{{ route('admin.landing.index') }}" class="btn btn-outline-primary w-100 p-3">
                                    <i class="bi bi-brush"></i> Pengaturan Halaman
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-layouts.admin>