<x-layouts.app title="{{ $product->name }}">
    <div class="container py-4">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ route('categories.show', $product->category->slug) }}">{{ $product->category->name }}</a></li>
                <li class="breadcrumb-item active" aria-current="page">{{ $product->name }}</li>
            </ol>
        </nav>

        <div class="row">
            <div class="col-md-4 mb-4">
                <div class="card border-0 shadow-sm">
                    <img src="{{ Storage::url($product->image) }}" class="card-img-top" alt="{{ $product->name }}">
                </div>
            </div>
            <div class="col-md-8">
                <h1 class="mb-2">{{ $product->name }}</h1>
                
                <div class="mb-2">
                    <x-product.star-rating :rating="$product->average_rating" />
                    <span class="ms-1">{{ $product->rating_count }} ulasan</span>
                </div>
                
                @if ($product->discount_price)
                    <p class="text-decoration-line-through text-muted">Rp {{ number_format($product->price, 0, ',', '.') }}</p>
                    <h3 class="text-primary mb-3">Rp {{ number_format($product->discount_price, 0, ',', '.') }}</h3>
                @else
                    <h3 class="text-primary mb-3">Rp {{ number_format($product->price, 0, ',', '.') }}</h3>
                @endif
                
                <div class="mb-3">
                    <span class="badge {{ $product->stock > 0 ? 'bg-success' : 'bg-danger' }}">
                        {{ $product->stock > 0 ? 'Stok: ' . $product->stock : 'Stok habis' }}
                    </span>
                </div>

                @if ($product->stock > 0)
                    <form action="{{ route('cart.add', $product) }}" method="POST" class="d-flex align-items-center">
                        @csrf
                        <div class="input-group me-3" style="width: 130px;">
                            <button class="btn btn-outline-secondary" type="button" id="decrementBtn">-</button>
                            <input type="number" name="quantity" class="form-control text-center" value="1" min="1" max="{{ $product->stock }}" id="quantityInput">
                            <button class="btn btn-outline-secondary" type="button" id="incrementBtn">+</button>
                        </div>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-cart-plus"></i> Tambah ke Keranjang
                        </button>
                    </form>

                    <script>
                        document.getElementById('decrementBtn').addEventListener('click', function() {
                            const input = document.getElementById('quantityInput');
                            if (input.value > 1) {
                                input.value = parseInt(input.value) - 1;
                            }
                        });

                        document.getElementById('incrementBtn').addEventListener('click', function() {
                            const input = document.getElementById('quantityInput');
                            if (parseInt(input.value) < {{ $product->stock }}) {
                                input.value = parseInt(input.value) + 1;
                            }
                        });
                    </script>
                @else
                    <button class="btn btn-secondary disabled">Stok Habis</button>
                @endif
                
                <div class="card mt-4 shadow-sm">
                    <div class="card-body">
                        <h3>Deskripsi</h3>
                        <p>{{ $product->description }}</p>
                    </div>
                </div>
                
                <!-- Tabs for Reviews and Discussions -->
                <ul class="nav nav-tabs mt-4 mb-3" id="productTabs" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="reviews-tab" data-bs-toggle="tab" data-bs-target="#reviews-tab-pane" type="button" role="tab" aria-controls="reviews-tab-pane" aria-selected="true">
                            Ulasan <span class="badge bg-secondary">{{ $product->rating_count }}</span>
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="discussions-tab" data-bs-toggle="tab" data-bs-target="#discussions-tab-pane" type="button" role="tab" aria-controls="discussions-tab-pane" aria-selected="false">
                            Diskusi <span class="badge bg-secondary">{{ $product->discussions()->count() }}</span>
                        </button>
                    </li>
                </ul>
                
                <div class="tab-content" id="productTabsContent">
                    <div class="tab-pane fade show active" id="reviews-tab-pane" role="tabpanel" aria-labelledby="reviews-tab" tabindex="0">
                        <x-product.reviews :product="$product" />
                    </div>
                    <div class="tab-pane fade" id="discussions-tab-pane" role="tabpanel" aria-labelledby="discussions-tab" tabindex="0">
                        <x-product.discussions :product="$product" />
                    </div>
                </div>
                
            </div>
        </div>
    </div>
</x-layouts.app>