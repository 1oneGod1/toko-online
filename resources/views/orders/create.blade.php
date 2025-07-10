<x-layouts.app title="Checkout">
    <div class="container">
        <h1 class="mb-4">Formulir Checkout</h1>
        <form action="{{ route('orders.store') }}" method="POST">
            @csrf
            <div class="row">
                <div class="col-md-7">
                    <h4>Detail Pengiriman</h4>
                    <div class="mb-3">
                        <label for="shipping_address" class="form-label">Alamat Lengkap</label>
                        <textarea class="form-control" name="shipping_address" id="shipping_address" rows="4" required></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="payment_method" class="form-label">Metode Pembayaran</label>
                        <select name="payment_method" id="payment_method" class="form-select" required>
                            <option value="transfer_bank">Transfer Bank</option>
                            <option value="cod">Cash on Delivery (COD)</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-5">
                    <h4>Ringkasan Pesanan</h4>
                    <ul class="list-group mb-3">
                        @foreach($cartItems as $item)
                        <li class="list-group-item d-flex justify-content-between">
                            <span>{{ $item->product->name }} (x{{ $item->quantity }})</span>
                            <strong>Rp {{ number_format($item->quantity * $item->product->price) }}</strong>
                        </li>
                        @endforeach
                        <li class="list-group-item d-flex justify-content-between">
                            <span>Total</span>
                            <strong>Rp {{ number_format($subtotal) }}</strong>
                        </li>
                    </ul>
                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary">Buat Pesanan</button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</x-layouts.app>
