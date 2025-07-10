<footer class="modern-footer">
    <div class="container">
        <div class="row">
            <!-- About Section -->
            <div class="col-lg-4 col-md-6 mb-4 mb-lg-0">
                <h5 class="footer-heading">Toko Online</h5>
                <p class="footer-text">Tujuan kami adalah menyediakan produk dengan kualitas terbaik dengan harga yang paling terjangkau. Belanja dengan kami dan rasakan perbedaannya.</p>
                <div class="footer-social-icons">
                    <a href="#" class="social-icon"><i class="bi bi-twitter"></i></a>
                    <a href="#" class="social-icon"><i class="bi bi-facebook"></i></a>
                    <a href="#" class="social-icon"><i class="bi bi-instagram"></i></a>
                    <a href="#" class="social-icon"><i class="bi bi-linkedin"></i></a>
                </div>
            </div>

            <!-- Quick Links Section -->
            <div class="col-lg-2 col-md-6 mb-4 mb-lg-0">
                <h5 class="footer-heading">Tautan Cepat</h5>
                <ul class="list-unstyled footer-links">
                    <li><a href="{{ route('home') }}">Beranda</a></li>
                    <li><a href="{{ route('products.index') }}">Produk</a></li>
                    <li><a href="#">Tentang Kami</a></li>
                    <li><a href="#">Kontak</a></li>
                </ul>
            </div>

            <!-- Help Section -->
            <div class="col-lg-3 col-md-6 mb-4 mb-lg-0">
                <h5 class="footer-heading">Bantuan</h5>
                <ul class="list-unstyled footer-links">
                    <li><a href="#">FAQ</a></li>
                    <li><a href="#">Kebijakan Pengiriman</a></li>
                    <li><a href="#">Kebijakan Pengembalian</a></li>
                    <li><a href="#">Syarat & Ketentuan</a></li>
                </ul>
            </div>

            <!-- Newsletter Section -->
            <div class="col-lg-3 col-md-6 mb-4 mb-lg-0">
                <h5 class="footer-heading">Berlangganan</h5>
                <p class="footer-text">Dapatkan info terbaru tentang produk baru dan penawaran spesial kami.</p>
                <form action="#">
                    <div class="input-group">
                        <input type="email" class="form-control" placeholder="Alamat email Anda" aria-label="Alamat email Anda">
                        <button class="btn btn-primary" type="submit">Daftar</button>
                    </div>
                </form>
            </div>
        </div>

        <div class="footer-bottom">
            <p>&copy; {{ date('Y') }} Toko Online. Semua Hak Cipta Dilindungi.</p>
        </div>
    </div>
</footer>
