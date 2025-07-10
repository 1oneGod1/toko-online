<x-layouts.app title="Keranjang Belanja">
    <div class="container py-4">
        <h1 class="mb-4">Keranjang Belanja Anda</h1>

        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
        
        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if (empty($cart))
            <div class="alert alert-warning">
                Keranjang belanja Anda masih kosong.
                <a href="{{ route('products.index') }}" class="btn btn-primary ms-3">Mulai Belanja</a>
            </div>
        @else
            <form action="{{ route('cart.update') }}" method="POST">
                @csrf
                <div class="card shadow-sm">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0 align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th scope="col" class="ps-4">Produk</th>
                                    <th scope="col">Harga</th>
                                    <th scope="col" style="width: 150px;">Jumlah</th>
                                    <th scope="col" class="text-end">Subtotal</th>
                                    <th scope="col" class="text-center pe-4">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php $total = 0 @endphp
                                @foreach ($cart as $id => $details)
                                    @php $total += $details['price'] * $details['quantity'] @endphp
                                    <tr>
                                        <td class="ps-4">
                                            <div class="d-flex align-items-center">
                                                <img src="{{ Str::startsWith($details['image'], ['http', 'https']) ? $details['image'] : asset('storage/' . ($details['image'] ?? 'default.jpg')) }}" 
                                                     class="img-fluid rounded me-3" 
                                                     style="width: 60px; height: 60px; object-fit: cover;"
                                                     alt="{{ $details['name'] }}"
                                                     onerror="this.src='https://placehold.co/60x60?text=No+Image'">
                                                <div>
                                                    <h6 class="mb-0">{{ $details['name'] }}</h6>
                                                </div>
                                            </div>
                                        </td>
                                        <td>Rp {{ number_format($details['price'], 0, ',', '.') }}</td>
                                        <td>
                                            <input type="number" name="quantities[{{ $id }}]" class="form-control form-control-sm text-center" value="{{ $details['quantity'] }}" min="1">
                                        </td>
                                        <td class="text-end">Rp {{ number_format($details['price'] * $details['quantity'], 0, ',', '.') }}</td>
                                        <td class="text-center pe-4">
                                            <a href="{{ route('cart.remove', $id) }}" class="btn btn-sm btn-outline-danger" title="Hapus">
                                                <i class="bi bi-trash"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="card-footer bg-white">
                        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                            <div>
                                <button type="submit" class="btn btn-primary">
                                    <i class="bi bi-arrow-repeat"></i> Perbarui Keranjang
                                </button>
                                <a href="{{ route('products.index') }}" class="btn btn-outline-secondary">Lanjut Belanja</a>
                            </div>
                            <div class="text-end">
                                <h5 class="mb-0">Total: Rp {{ number_format($total, 0, ',', '.') }}</h5>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
            
            <div class="text-end mt-4">
                <a href="#" class="btn btn-success btn-lg">
                    <i class="bi bi-wallet2"></i> Lanjutkan ke Pembayaran
                </a>
            </div>
        @endif
    </div>
</x-layouts.app>
