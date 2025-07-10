<x-layouts.app title="Wishlist Saya">
    <div class="container">
        <h1 class="mb-4">Wishlist Saya</h1>
        
        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        @if ($wishlists->isEmpty())
            <div class="alert alert-warning">
                <h4>Wishlist Anda masih kosong</h4>
                <p>Mulai tambahkan produk favorit Anda ke wishlist.</p>
                <a href="{{ route('products.index') }}" class="btn btn-primary">Jelajahi Produk</a>
            </div>
        @else
            <div class="row">
                @foreach ($wishlists as $wishlist)
                    <div class="col-md-6 col-lg-4 mb-4">
                        <div class="card h-100">
                            @if($wishlist->product->image)
                                <img src="{{ asset('storage/' . $wishlist->product->image) }}" class="card-img-top" alt="{{ $wishlist->product->name }}" style="height: 200px; object-fit: cover;">
                            @else
                                <div class="card-img-top bg-light d-flex align-items-center justify-content-center" style="height: 200px;">
                                    <span class="text-muted">No Image</span>
                                </div>
                            @endif
                            
                            <div class="card-body d-flex flex-column">
                                <h5 class="card-title">{{ $wishlist->product->name }}</h5>
                                <p class="card-text flex-grow-1">{{ Str::limit($wishlist->product->description, 100) }}</p>
                                <div class="mt-auto">
                                    <p class="text-primary h5 mb-2">Rp {{ number_format($wishlist->product->price) }}</p>
                                    <div class="d-flex gap-2">
                                        <a href="{{ route('products.show', $wishlist->product) }}" class="btn btn-primary btn-sm flex-grow-1">Lihat Detail</a>
                                        <form action="{{ route('wishlist.destroy', $wishlist) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-outline-danger btn-sm" onclick="return confirm('Hapus dari wishlist?')">
                                                ❤️
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</x-layouts.app>