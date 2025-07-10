@extends('layouts.app')

@section('content')
<div class="container py-4">
    <h1 class="mb-4">Checkout</h1>

    @if ($errors->any())
        <div class="alert alert-danger">
            <p class="font-bold">Harap perbaiki error berikut:</p>
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('orders.store') }}" method="POST">
        @csrf
        <div class="row">
            {{-- Kolom Kiri: Alamat & Pembayaran --}}
            <div class="col-lg-8">
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">Alamat Pengiriman</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label for="name" class="form-label">Nama Lengkap</label>
                            <input type="text" name="name" id="name" class="form-control" 
                                   value="{{ old('name', auth()->user()->name) }}" required>
                        </div>
                        <div class="mb-3">
                            <label for="address" class="form-label">Alamat Lengkap</label>
                            <textarea name="address" id="address" rows="3" class="form-control" required>{{ old('address') }}</textarea>
                        </div>
                        <div class="mb-3">
                            <label for="phone" class="form-label">Nomor Telepon</label>
                            <input type="text" name="phone" id="phone" class="form-control" 
                                   value="{{ old('phone') }}" required>
                        </div>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">Metode Pembayaran</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="payment_method" 
                                       id="payment_cod" value="cod">
                                <label class="form-check-label" for="payment_cod">
                                    Bayar di Tempat (COD)
                                </label>
                            </div>
                        </div>
                        <div class="mb-3">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="payment_method" 
                                       id="payment_transfer" value="bank_transfer" checked>
                                <label class="form-check-label" for="payment_transfer">
                                    Transfer Bank
                                </label>
                            </div>
                        </div>

                        <!-- Bank Account Selection -->
                        <div id="bank_selection" class="mt-3">
                            <h6>Pilih Rekening Tujuan:</h6>
                            @if(isset($bankAccounts) && $bankAccounts->count() > 0)
                                @foreach($bankAccounts as $index => $bank)
                                <div class="card mb-2">
                                    <div class="card-body py-2">
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="bank_account_id" 
                                                   id="bank_{{ $bank->id }}" value="{{ $bank->id }}"
                                                   {{ $index === 0 ? 'checked' : '' }}>
                                            <label class="form-check-label w-100" for="bank_{{ $bank->id }}">
                                                <div class="d-flex justify-content-between align-items-center">
                                                    <div>
                                                        <strong>{{ $bank->bank_name }}</strong><br>
                                                        <small class="text-muted">{{ $bank->account_number }}</small><br>
                                                        <small class="text-muted">a.n. {{ $bank->account_holder_name }}</small>
                                                    </div>
                                                    <div class="text-primary fw-bold">{{ $bank->bank_name }}</div>
                                                </div>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            @else
                                <div class="alert alert-warning">
                                    <p class="mb-0">Belum ada rekening bank yang tersedia.</p>
                                    <small>Silakan hubungi admin untuk informasi pembayaran.</small>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            {{-- Kolom Kanan: Ringkasan Pesanan --}}
            <div class="col-lg-4">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">Ringkasan Pesanan</h5>
                    </div>
                    <div class="card-body">
                        @if(isset($cartItems))
                            @foreach($cartItems as $item)
                            <div class="d-flex mb-3 pb-3 border-bottom">
                                <img src="{{ $item->image ? asset('storage/' . $item->image) : 'https://via.placeholder.com/50' }}" 
                                     alt="{{ $item->name }}" class="me-3" style="width: 50px; height: 50px; object-fit: cover;">
                                <div class="flex-fill">
                                    <h6 class="mb-1">{{ $item->name }}</h6>
                                    <small class="text-muted">Jumlah: {{ $item->quantity }}</small>
                                    <div class="fw-bold">Rp {{ number_format($item->price * $item->quantity, 0, ',', '.') }}</div>
                                </div>
                            </div>
                            @endforeach
                        @endif
                        
                        <hr>
                        <div class="d-flex justify-content-between mb-2">
                            <span>Subtotal:</span>
                            <span>Rp {{ number_format($subtotal ?? 0, 0, ',', '.') }}</span>
                        </div>
                        
                        @if(isset($discount) && $discount > 0)
                        <div class="d-flex justify-content-between mb-2 text-success">
                            <span>Diskon:</span>
                            <span>-Rp {{ number_format($discount, 0, ',', '.') }}</span>
                        </div>
                        @endif
                        
                        <hr>
                        <div class="d-flex justify-content-between mb-3">
                            <strong>Total:</strong>
                            <strong>Rp {{ number_format($total ?? 0, 0, ',', '.') }}</strong>
                        </div>

                        <button type="submit" class="btn btn-primary w-100">
                            Buat Pesanan
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const codRadio = document.getElementById('payment_cod');
    const transferRadio = document.getElementById('payment_transfer');
    const bankSelection = document.getElementById('bank_selection');
    
    function toggleBankSelection() {
        if (transferRadio && transferRadio.checked) {
            bankSelection.style.display = 'block';
        } else {
            bankSelection.style.display = 'none';
        }
    }
    
    // Initial toggle
    toggleBankSelection();
    
    // Event listeners
    if (codRadio) codRadio.addEventListener('change', toggleBankSelection);
    if (transferRadio) transferRadio.addEventListener('change', toggleBankSelection);
});
</script>
@endsection
