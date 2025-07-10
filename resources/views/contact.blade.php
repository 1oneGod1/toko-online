<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hubungi Kami - Toko Online</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="{{ route('home') }}">Toko Online</a>
            <div class="navbar-nav">
                <a class="nav-link" href="{{ route('home') }}">Home</a>
                <a class="nav-link" href="{{ route('products.index') }}">Produk</a>
                <a class="nav-link active" href="{{ route('contact') }}">Kontak</a>
            </div>
        </div>
    </nav>

    <div class="container mt-4">
        <h1>Hubungi Kami</h1>
        <div class="row">
            <div class="col-md-6">
                <h3>Informasi Kontak</h3>
                <p><strong>Email:</strong> info@toko-online.com</p>
                <p><strong>Telepon:</strong> +62 21 1234 5678</p>
                <p><strong>Alamat:</strong> Jl. Contoh No. 123, Jakarta</p>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>