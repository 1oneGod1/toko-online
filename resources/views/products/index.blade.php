<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Produk - Toko Online</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="{{ route('home') }}">Toko Online</a>
            <div class="navbar-nav">
                <a class="nav-link" href="{{ route('home') }}">Home</a>
                <a class="nav-link" href="{{ route('products.index') }}">Produk</a>
            </div>
        </div>
    </nav>

    <div class="container mt-4">
        <h1>Daftar Produk</h1>
        
        <div class="row">
            @forelse ($products as $product)
                <div class="col-md-3 mb-4">
                    <x-product-card :product="$product" />
                </div>
            @empty
                <div class="col-12">
                    <div class="alert alert-info">Belum ada produk tersedia.</div>
                </div>
            @endforelse
        </div>

        <div class="d-flex justify-content-center">
            {{ $products->links() }}
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>