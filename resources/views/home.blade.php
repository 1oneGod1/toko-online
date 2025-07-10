<x-layouts.app title="Selamat Datang di Toko Online">
    <!-- Hero Section -->
    <div class="container-fluid bg-dark text-white text-center py-5 mb-5" style="background: linear-gradient(rgba(0, 0, 0, 0.6), rgba(0, 0, 0, 0.6)), url('https://placehold.co/1920x600/333/555?text=Selamat+Datang'); background-size: cover; background-position: center;">
        <h1 class="display-4 fw-bold">Selamat Datang di Toko Online</h1>
        <p class="lead col-lg-6 mx-auto">Temukan ribuan produk berkualitas dengan harga terbaik. Belanja mudah, cepat, dan aman.</p>
        <a href="{{ route('products.index') }}" class="btn btn-primary btn-lg mt-3">Mulai Belanja Sekarang</a>
    </div>

    <div class="container">
        <!-- Featured Products Section -->
        <h2 class="text-center mb-4">Produk Unggulan Kami</h2>
        <div class="row">
            @forelse ($featuredProducts as $product)
                <div class="col-md-6 col-lg-3 mb-4">
                    <div class="card h-100 product-card shadow-sm">
                         <span class="badge bg-danger position-absolute top-0 start-0 m-2">Unggulan</span>
                        <img src="{{ Str::startsWith($product->image ?? '', 'http') ? $product->image : asset('storage/' . ($product->image ?? 'default.jpg')) }}" class="card-img-top" alt="{{ $product->name ?? 'Produk' }}" style="height: 180px; object-fit: cover;" onerror="this.src='https://placehold.co/600x400?text=Produk'">
                        <div class="card-body d-flex flex-column">
                            <h5 class="card-title">{{ $product->name }}</h5>
                            <p class="card-text h5 text-primary mt-auto">Rp {{ number_format($product->price ?? 0, 0, ',', '.') }}</p>
                            {{-- PERBAIKAN DI SINI: Mengirim objek $product langsung --}}
                            <a href="{{ route('products.show', $product) }}" class="btn btn-dark w-100 mt-2">Lihat Detail</a>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12">
                    <div class="alert alert-info text-center">
                        <h4>Jalankan migrasi database terlebih dahulu</h4>
                        <p>Tampaknya Anda belum menjalankan migrasi database. Silakan jalankan perintah berikut di terminal:</p>
                        <code>php artisan migrate:fresh --seed</code>
                    </div>
                </div>
            @endforelse
        </div>
        
        <!-- ... sisa kode tidak berubah ... -->
    </div>
</x-layouts.app>
