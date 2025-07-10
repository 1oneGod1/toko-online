<x-layouts.app title="Riwayat Pesanan">
    <div class="container">
        <h1 class="mb-4">Riwayat Pesanan Anda</h1>
         @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        @forelse($orders as $order)
            <div class="card mb-3">
                <div class="card-header d-flex justify-content-between">
                    <span>Pesanan #{{ $order->id }} - {{ $order->created_at->format('d M Y') }}</span>
                    <span class="badge bg-info">{{ $order->status }}</span>
                </div>
                <div class="card-body">
                    <p><strong>Total:</strong> Rp {{ number_format($order->total_amount) }}</p>
                    <p><strong>Alamat:</strong> {{ $order->shipping_address }}</p>
                </div>
            </div>
        @empty
            <div class="alert alert-info">Anda belum memiliki riwayat pesanan.</div>
        @endforelse
        <div class="d-flex justify-content-center">
            {{ $orders->links() }}
        </div>
    </div>
</x-layouts.app>
