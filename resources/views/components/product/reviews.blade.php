@props(['product'])

<div class="product-reviews mb-5">
    <h3 class="h5 mb-4">Ulasan Produk</h3>
    
    <!-- Review Stats -->
    <div class="row align-items-center mb-4">
        <div class="col-md-3 text-center">
            <div class="display-4 fw-bold text-warning mb-1">{{ number_format($product->average_rating, 1) }}</div>
            <div class="mb-2">
                <x-product.star-rating :rating="$product->average_rating" />
            </div>
            <div class="text-muted">{{ $product->rating_count }} ulasan</div>
        </div>
        <div class="col-md-9">
            <div class="d-flex align-items-center mb-2">
                <div class="text-nowrap me-3">5 <i class="bi bi-star-fill text-warning"></i></div>
                <div class="progress flex-grow-1" style="height: 8px;">
                    @php $fiveStarPercent = $product->rating_count > 0 ? ($product->reviews->where('rating', 5)->count() / $product->rating_count) * 100 : 0; @endphp
                    <div class="progress-bar bg-success" role="progressbar" style="width: {{ $fiveStarPercent }}%"></div>
                </div>
                <div class="ms-3 text-muted small">{{ $product->reviews->where('rating', 5)->count() }}</div>
            </div>
            <div class="d-flex align-items-center mb-2">
                <div class="text-nowrap me-3">4 <i class="bi bi-star-fill text-warning"></i></div>
                <div class="progress flex-grow-1" style="height: 8px;">
                    @php $fourStarPercent = $product->rating_count > 0 ? ($product->reviews->where('rating', 4)->count() / $product->rating_count) * 100 : 0; @endphp
                    <div class="progress-bar bg-success" role="progressbar" style="width: {{ $fourStarPercent }}%"></div>
                </div>
                <div class="ms-3 text-muted small">{{ $product->reviews->where('rating', 4)->count() }}</div>
            </div>
            <div class="d-flex align-items-center mb-2">
                <div class="text-nowrap me-3">3 <i class="bi bi-star-fill text-warning"></i></div>
                <div class="progress flex-grow-1" style="height: 8px;">
                    @php $threeStarPercent = $product->rating_count > 0 ? ($product->reviews->where('rating', 3)->count() / $product->rating_count) * 100 : 0; @endphp
                    <div class="progress-bar bg-warning" role="progressbar" style="width: {{ $threeStarPercent }}%"></div>
                </div>
                <div class="ms-3 text-muted small">{{ $product->reviews->where('rating', 3)->count() }}</div>
            </div>
            <div class="d-flex align-items-center mb-2">
                <div class="text-nowrap me-3">2 <i class="bi bi-star-fill text-warning"></i></div>
                <div class="progress flex-grow-1" style="height: 8px;">
                    @php $twoStarPercent = $product->rating_count > 0 ? ($product->reviews->where('rating', 2)->count() / $product->rating_count) * 100 : 0; @endphp
                    <div class="progress-bar bg-warning" role="progressbar" style="width: {{ $twoStarPercent }}%"></div>
                </div>
                <div class="ms-3 text-muted small">{{ $product->reviews->where('rating', 2)->count() }}</div>
            </div>
            <div class="d-flex align-items-center">
                <div class="text-nowrap me-3">1 <i class="bi bi-star-fill text-warning"></i></div>
                <div class="progress flex-grow-1" style="height: 8px;">
                    @php $oneStarPercent = $product->rating_count > 0 ? ($product->reviews->where('rating', 1)->count() / $product->rating_count) * 100 : 0; @endphp
                    <div class="progress-bar bg-danger" role="progressbar" style="width: {{ $oneStarPercent }}%"></div>
                </div>
                <div class="ms-3 text-muted small">{{ $product->reviews->where('rating', 1)->count() }}</div>
            </div>
        </div>
    </div>
    
    <!-- User Review Form -->
    @auth
        @php $userReview = $product->reviews->where('user_id', auth()->id())->first(); @endphp
        <div class="card shadow-sm mb-4">
            <div class="card-body">
                <h5 class="mb-3">{{ $userReview ? 'Edit Ulasan Anda' : 'Berikan Ulasan' }}</h5>
                <form action="{{ route('reviews.store', $product) }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">Rating</label>
                        <div class="rating-input">
                            <div class="d-flex">
                                @for ($i = 5; $i >= 1; $i--)
                                    <div class="me-2">
                                        <input type="radio" name="rating" id="rating-{{ $i }}" value="{{ $i }}" {{ ($userReview && $userReview->rating == $i) ? 'checked' : '' }} required>
                                        <label for="rating-{{ $i }}" class="btn btn-outline-warning">
                                            {{ $i }} <i class="bi bi-star-fill"></i>
                                        </label>
                                    </div>
                                @endfor
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="comment" class="form-label">Komentar</label>
                        <textarea class="form-control" id="comment" name="comment" rows="3" placeholder="Bagikan pendapat Anda tentang produk ini">{{ $userReview ? $userReview->comment : '' }}</textarea>
                    </div>
                    <div class="d-flex justify-content-between">
                        <button type="submit" class="btn btn-primary">
                            {{ $userReview ? 'Perbarui Ulasan' : 'Kirim Ulasan' }}
                        </button>
                        @if ($userReview)
                            <form action="{{ route('reviews.destroy', $userReview) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus ulasan?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-outline-danger">Hapus Ulasan</button>
                            </form>
                        @endif
                    </div>
                </form>
            </div>
        </div>
    @else
        <div class="alert alert-info mb-4">
            <p class="mb-0">
                <i class="bi bi-info-circle"></i> Silahkan <a href="{{ route('login') }}">masuk</a> untuk memberikan ulasan.
            </p>
        </div>
    @endauth
    
    <!-- Reviews List -->
    @if ($product->rating_count > 0)
        <div class="reviews-list">
            @foreach ($product->reviews()->with('user')->latest()->get() as $review)
                <div class="card shadow-sm mb-3">
                    <div class="card-body">
                        <div class="d-flex justify-content-between mb-2">
                            <div>
                                <div class="d-flex align-items-center">
                                    <span class="fw-bold me-2">{{ $review->user->name }}</span>
                                    <span class="badge bg-light text-dark">
                                        @for ($i = 1; $i <= 5; $i++)
                                            @if ($i <= $review->rating)
                                                <i class="bi bi-star-fill text-warning"></i>
                                            @else
                                                <i class="bi bi-star text-warning"></i>
                                            @endif
                                        @endfor
                                    </span>
                                </div>
                                <small class="text-muted">{{ $review->created_at->diffForHumans() }}</small>
                            </div>
                            @if (auth()->check() && (auth()->id() === $review->user_id || auth()->user()->isAdmin()))
                                <div>
                                    <form action="{{ route('reviews.destroy', $review) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm text-danger" onclick="return confirm('Yakin ingin menghapus ulasan ini?')">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            @endif
                        </div>
                        <p class="mb-0">{{ $review->comment }}</p>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="text-center py-4 text-muted">
            <p class="mb-0">Belum ada ulasan untuk produk ini.</p>
        </div>
    @endif
</div>

<style>
.rating-input input[type="radio"] {
    display: none;
}

.rating-input label {
    cursor: pointer;
}

.rating-input input[type="radio"]:checked + label {
    background-color: #ffc107;
    color: #212529;
    border-color: #ffc107;
}

.star-rating {
    display: flex;
    flex-direction: row-reverse;
    justify-content: flex-end;
}
.star-rating input {
    display: none;
}
.star-rating label {
    font-size: 2rem;
    color: #ddd;
    cursor: pointer;
}
.star-rating input:checked ~ label,
.star-rating label:hover,
.star-rating label:hover ~ label {
    color: #ffc107;
}
</style>