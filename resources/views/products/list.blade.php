<x-layouts.app title="Daftar Produk">
    <div class="container">
        <!-- Form untuk Filter, Pencarian, dan Urutan -->
        <div class="card mb-4 shadow-sm">
            <div class="card-body">
                <h5 class="card-title">Filter Produk</h5>
                <form action="{{ route('products.index') }}" method="GET">
                    <div class="row g-3 align-items-end">
                        <div class="col-lg-4 col-md-12">
                            <label for="search" class="form-label">Cari Produk</label>
                            <input type="text" class="form-control" id="search" name="search" placeholder="Nama atau deskripsi..." value="{{ request('search') }}">
                        </div>
                        <div class="col-lg-2 col-md-6">
                            <label for="min_price" class="form-label">Harga Min.</label>
                            <input type="number" class="form-control" id="min_price" name="min_price" placeholder="Rp 0" value="{{ request('min_price') }}">
                        </div>
                        <div class="col-lg-2 col-md-6">
                            <label for="max_price" class="form-label">Harga Max.</label>
                            <input type="number" class="form-control" id="max_price" name="max_price" placeholder="Rp 1.000.000" value="{{ request('max_price') }}">
                        </div>
                        <div class="col-lg-2 col-md-6">
                            <label for="sort" class="form-label">Urutkan</label>
                            <select class="form-select" id="sort" name="sort">
                                <option value="">Paling Sesuai</option>
                                <option value="harga_asc" @selected(request('sort') == 'harga_asc')>Harga Terendah</option>
                                <option value="harga_desc" @selected(request('sort') == 'harga_desc')>Harga Tertinggi</option>
                                <option value="nama_asc" @selected(request('sort') == 'nama_asc')>Nama A-Z</option>
                                <option value="nama_desc" @selected(request('sort') == 'nama_desc')>Nama Z-A</option>
                            </select>
                        </div>
                        <div class="col-lg-2 col-md-6 d-grid">
                            <button type="submit" class="btn btn-primary">Terapkan</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="h3 mb-0">Daftar Produk ({{ $products->total() }} produk)</h1>
            @can('is-admin')
                <a href="{{ route('products.create') }}" class="btn btn-outline-primary">
                    + Tambah Produk
                </a>
            @endcan
        </div>

        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="row">
            @forelse ($products as $product)
                <div class="col-md-6 col-lg-4 col-xl-3 mb-4">
                    <div class="card h-100 product-card">
                        <span class="badge bg-primary position-absolute top-0 start-0 m-2">{{ $product->category->name }}</span>
                        <img src="{{ Str::startsWith($product->image, 'http') ? $product->image : asset('storage/' . $product->image) }}" 
                             class="card-img-top" 
                             alt="{{ $product->name }}" 
                             style="height: 200px; object-fit: cover;"
                             onerror="this.onerror=null;this.src='https://placehold.co/600x400/EBF4FA/313131?text=Gambar+Rusak';">
                        <div class="card-body d-flex flex-column">
                            <h5 class="card-title">{{ $product->name }}</h5>
                            <p class="card-text text-muted flex-grow-1 small">{{ Str::limit($product->description, 100) }}</p>
                            <p class="card-text h5 text-primary">Rp {{ number_format($product->price, 0, ',', '.') }}</p>
                            <div class="mt-auto pt-2">
                                <a href="{{ route('products.show', $product) }}" class="btn btn-dark w-100 mb-2">Lihat Detail</a>
                                @can('is-admin')
                                    <a href="{{ route('products.edit', $product) }}" class="btn btn-outline-secondary w-100">Ubah</a>
                                @endcan
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12">
                    <div class="alert alert-warning text-center">
                        <h4 class="alert-heading">Tidak Ada Produk</h4>
                        <p>Produk yang Anda cari tidak ditemukan. Coba ubah kata kunci atau filter Anda.</p>
                        <a href="{{ route('products.index') }}" class="btn btn-primary">Atur Ulang Filter</a>
                    </div>
                </div>
            @endforelse
        </div>

        {{-- Menampilkan link pagination --}}
        <div class="d-flex justify-content-center mt-4">
            {{ $products->links() }}
        </div>
    </div>
</x-layouts.app>
