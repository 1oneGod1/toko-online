@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            @if(session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif

            <div class="card">
                <div class="card-header bg-success text-white">
                    <h4 class="mb-0">
                        <i class="fas fa-check-circle"></i> Pesanan Berhasil Dibuat
                    </h4>
                </div>
                <div class="card-body">
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <strong>Nomor Pesanan:</strong><br>
                            <span class="text-primary">{{ $order->order_number }}</span>
                        </div>
                        <div class="col-md-6">
                            <strong>Status:</strong><br>
                            <span class="badge bg-{{ $order->status_badge }}">{{ $order->status_label }}</span>
                        </div>
                    </div>

                    <div class="row mb-4">
                        <div class="col-md-6">
                            <h6>Informasi Pengiriman:</h6>
                            <address>
                                <strong>{{ $order->name }}</strong><br>
                                {{ $order->address }}<br>
                                <i class="fas fa-phone"></i> {{ $order->phone }}
                            </address>
                        </div>
                        <div class="col-md-6">
                            <h6>Metode Pembayaran:</h6>
                            @if($order->payment_method === 'cod')
                                <p><i class="fas fa-money-bill"></i> Bayar di Tempat (COD)</p>
                            @else
                                <p><i class="fas fa-university"></i> Transfer Bank</p>
                                @if($order->bankAccount)
                                    <div class="card">
                                        <div class="card-body py-2">
                                            <strong>{{ $order->bankAccount->bank_name }}</strong><br>
                                            <small>{{ $order->bankAccount->account_number }}</small><br>
                                            <small>a.n. {{ $order->bankAccount->account_holder_name }}</small>
                                        </div>
                                    </div>
                                @endif
                            @endif
                        </div>
                    </div>

                    <h6>Detail Pesanan:</h6>
                    <div class="table-responsive">
                        <table class="table">
                            <thead class="table-light">
                                <tr>
                                    <th>Produk</th>
                                    <th>Harga</th>
                                    <th>Qty</th>
                                    <th class="text-end">Subtotal</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($order->orderItems as $item)
                                <tr>
                                    <td>{{ $item->product->name ?? 'Produk tidak ditemukan' }}</td>
                                    <td>Rp {{ number_format($item->price, 0, ',', '.') }}</td>
                                    <td>{{ $item->quantity }}</td>
                                    <td class="text-end">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th colspan="3">Subtotal:</th>
                                    <th class="text-end">Rp {{ number_format($order->subtotal, 0, ',', '.') }}</th>
                                </tr>
                                @if($order->discount > 0)
                                <tr class="text-success">
                                    <th colspan="3">Diskon:</th>
                                    <th class="text-end">-Rp {{ number_format($order->discount, 0, ',', '.') }}</th>
                                </tr>
                                @endif
                                <tr class="table-primary">
                                    <th colspan="3">Total:</th>
                                    <th class="text-end">Rp {{ number_format($order->total, 0, ',', '.') }}</th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>

                    @if($order->payment_method === 'bank_transfer')
                        <div class="alert alert-info">
                            <h6><i class="fas fa-info-circle"></i> Instruksi Pembayaran:</h6>
                            <ol>
                                <li>Transfer sejumlah <strong>Rp {{ number_format($order->total, 0, ',', '.') }}</strong></li>
                                <li>Ke rekening {{ $order->bankAccount->bank_name ?? 'Bank' }} yang tertera di atas</li>
                                <li>Simpan bukti transfer</li>
                                <li>Hubungi kami untuk konfirmasi pembayaran</li>
                            </ol>
                        </div>
                    @endif

                    <div class="d-flex justify-content-between mt-4">
                        <a href="{{ route('products.index') }}" class="btn btn-outline-primary">
                            <i class="fas fa-shopping-cart"></i> Lanjut Belanja
                        </a>
                        <a href="{{ route('orders.index') }}" class="btn btn-primary">
                            <i class="fas fa-list"></i> Lihat Semua Pesanan
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection