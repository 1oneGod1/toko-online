@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="row">
        <div class="col-12">
            <h1 class="mb-4">Keranjang Belanja</h1>
            
            @if(session('success'))
                <div class="alert alert-success" role="alert">
                    {{ session('success') }}
                </div>
            @endif
            
            @if(session('error'))
                <div class="alert alert-danger" role="alert">
                    {{ session('error') }}
                </div>
            @endif

            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @if($cartItems->count() > 0)
                <div class="row">
                    <!-- Kolom Kiri: Daftar Item -->
                    <div class="col-lg-8">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="mb-0">Item dalam Keranjang</h5>
                            </div>
                            <div class="card-body p-0">
                                @foreach($cartItems as $item)
                                <div class="p-3 border-bottom">
                                    <div class="row align-items-center">
                                        <div class="col-md-2">
                                            <img src="{{ $item->image ? asset('storage/' . $item->image) : 'https://via.placeholder.com/80' }}" 
                                                 alt="{{ $item->name }}" 
                                                 class="img-fluid rounded" style="max-height: 80px;">
                                        </div>
                                        <div class="col-md-4">
                                            <h6 class="mb-1">{{ $item->name }}</h6>
                                            <p class="text-muted small mb-0">Rp {{ number_format($item->price, 0, ',', '.') }}</p>
                                        </div>
                                        <div class="col-md-3">
                                            <form action="{{ route('cart.update') }}" method="POST" class="d-flex align-items-center">
                                                @csrf
                                                @method('PUT')
                                                <input type="hidden" name="id" value="{{ $item->id }}">
                                                <label class="me-2 small">Qty:</label>
                                                <input type="number" name="quantity" value="{{ $item->quantity }}" 
                                                       class="form-control form-control-sm" style="width: 70px;" 
                                                       min="1" onchange="this.form.submit()">
                                            </form>
                                        </div>
                                        <div class="col-md-2">
                                            <strong>Rp {{ number_format($item->price * $item->quantity, 0, ',', '.') }}</strong>
                                        </div>
                                        <div class="col-md-1">
                                            <a href="{{ route('cart.remove', $item->id) }}" 
                                               class="btn btn-sm btn-outline-danger"
                                               onclick="return confirm('Hapus item ini?')">
                                                <i class="fas fa-trash"></i>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                        
                        <div class="mt-3">
                            <a href="{{ route('products.index') }}" class="btn btn-outline-primary">
                                <i class="fas fa-arrow-left me-2"></i>Lanjut Belanja
                            </a>
                        </div>
                    </div>

                    <!-- Kolom Kanan: Ringkasan -->
                    <div class="col-lg-4">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="mb-0">Ringkasan Belanja</h5>
                            </div>
                            <div class="card-body">
                                <!-- Form Kupon -->
                                @if(!session()->has('coupon'))
                                    <form action="{{ route('coupons.store') }}" method="POST" class="mb-3">
                                        @csrf
                                        <label for="coupon_code" class="form-label">Kode Kupon</label>
                                        <div class="input-group">
                                            <input type="text" name="code" id="coupon_code" 
                                                   class="form-control" placeholder="Masukkan kupon">
                                            <button type="submit" class="btn btn-outline-secondary">Terapkan</button>
                                        </div>
                                    </form>
                                @else
                                    @php
                                        $couponData = session('coupon');
                                        $discountAmount = isset($couponData['discount']) ? $couponData['discount'] : 0;
                                        $couponCode = isset($couponData['code']) ? $couponData['code'] : 'Unknown';
                                    @endphp
                                    
                                    <div class="alert alert-success">
                                        <strong>Kupon {{ $couponCode }} aktif!</strong>
                                        <form action="{{ route('coupons.destroy') }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-link text-danger p-0 ms-2">Hapus</button>
                                        </form>
                                    </div>
                                @endif

                                <!-- Detail Harga -->
                                <hr>
                                <div class="d-flex justify-content-between mb-2">
                                    <span>Subtotal:</span>
                                    <span>Rp {{ number_format($total, 0, ',', '.') }}</span>
                                </div>
                                
                                @if(session()->has('coupon'))
                                    @php
                                        $couponData = session('coupon');
                                        $discountAmount = isset($couponData['discount']) ? $couponData['discount'] : 0;
                                        $couponCode = isset($couponData['code']) ? $couponData['code'] : 'Unknown';
                                    @endphp
                                    
                                    @if($discountAmount > 0)
                                        <div class="d-flex justify-content-between mb-2 text-success">
                                            <span>Diskon ({{ $couponCode }}):</span>
                                            <span>-Rp {{ number_format($discountAmount, 0, ',', '.') }}</span>
                                        </div>
                                    @endif
                                @endif
                                
                                <hr>
                                <div class="d-flex justify-content-between mb-3">
                                    <strong>Total:</strong>
                                    @php
                                        $discount = 0;
                                        if (session()->has('coupon')) {
                                            $couponData = session('coupon');
                                            $discount = isset($couponData['discount']) ? $couponData['discount'] : 0;
                                        }
                                        $finalTotal = $total - $discount;
                                    @endphp
                                    <strong>Rp {{ number_format($finalTotal, 0, ',', '.') }}</strong>
                                </div>

                                <!-- Tombol Checkout -->
                                <a href="{{ route('orders.create') }}" class="btn btn-primary w-100">
                                    Lanjutkan ke Pembayaran
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            @else
                <!-- Keranjang Kosong -->
                <div class="text-center py-5">
                    <div class="mb-4">
                        <i class="fas fa-shopping-cart fa-5x text-muted"></i>
                    </div>
                    <h3>Keranjang Anda Kosong</h3>
                    <p class="text-muted">Sepertinya Anda belum menambahkan produk apapun.</p>
                    <a href="{{ route('products.index') }}" class="btn btn-primary">
                        Mulai Belanja
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
