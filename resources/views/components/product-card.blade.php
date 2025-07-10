@props(['product'])

<div class="card h-100 shadow-sm border-light">
    <a href="{{ route('products.show', $product->slug) }}">
        <img src="{{ Str::startsWith($product->image, ['http', 'https']) ? $product->image : asset('storage/' . ($product->image ?? 'default.jpg')) }}" 
             class="card-img-top" 
             alt="{{ $product->name }}" 
             style="height: 200px; object-fit: cover;"
             onerror="this.src='https://placehold.co/600x400?text=No+Image'">
    </a>
    <div class="card-body d-flex flex-column">
        <h5 class="card-title mb-1">{{ Str::limit($product->name, 50) }}</h5>
        <p class="card-text text-muted small mb-2">{{ $product->category->name }}</p>
        
        <div class="mt-auto">
            @if($product->discount_price)
                <p class="card-text mb-0">
                    <span class="text-danger fw-bold">Rp {{ number_format($product->discount_price, 0, ',', '.') }}</span>
                    <s class="text-muted small">Rp {{ number_format($product->price, 0, ',', '.') }}</s>
                </p>
            @else
                <p class="card-text fw-bold mb-0">Rp {{ number_format($product->price, 0, ',', '.') }}</p>
            @endif

            <div class="d-flex justify-content-between align-items-center mt-2">
                <a href="{{ route('products.show', $product->slug) }}" class="btn btn-primary btn-sm">Lihat Detail</a>
                <small class="text-muted">Stok: {{ $product->stock }}</small>
            </div>
        </div>
    </div>
</div>
