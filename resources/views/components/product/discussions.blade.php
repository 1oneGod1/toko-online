@props(['product'])

<div>
    <!-- Discussions Section -->
    <div class="mb-5">
        <h4 class="mb-3">Diskusi Produk ({{ $product->discussions->where('parent_id', null)->count() }})</h4>

        <!-- Form for starting a new discussion -->
        @auth
            <div class="card shadow-sm mb-4">
                <div class="card-body">
                    <h5 class="card-title">Mulai Diskusi Baru</h5>
                    <form action="{{ route('discussions.store', $product) }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <textarea name="message" class="form-control" rows="3" placeholder="Ada yang ingin ditanyakan tentang produk ini?" required></textarea>
                            @error('message')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                        <button type="submit" class="btn btn-primary">Kirim Pertanyaan</button>
                    </form>
                </div>
            </div>
        @else
            <div class="alert alert-info">
                <a href="{{ route('login') }}">Masuk</a> untuk memulai diskusi.
            </div>
        @endauth

        <!-- Display existing discussions -->
        @forelse ($product->discussions->where('parent_id', null) as $discussion)
            <div class="d-flex mb-3 border-bottom pb-3">
                <img src="https://ui-avatars.com/api/?name={{ urlencode($discussion->user->name) }}&background=random" class="rounded-circle me-3" style="width: 50px; height: 50px;" alt="{{ $discussion->user->name }}">
                <div class="w-100">
                    <div class="d-flex justify-content-between">
                        <h6 class="fw-bold">{{ $discussion->user->name }}</h6>
                        <small class="text-muted">{{ $discussion->created_at->diffForHumans() }}</small>
                    </div>
                    <p class="mt-2 mb-1">{{ $discussion->message }}</p>
                    
                    <!-- Replies -->
                    @if($discussion->replies->isNotEmpty())
                        @foreach($discussion->replies as $reply)
                            <div class="d-flex mt-3 ms-4">
                                <img src="https://ui-avatars.com/api/?name={{ urlencode($reply->user->name) }}&background=random" class="rounded-circle me-3" style="width: 40px; height: 40px;" alt="{{ $reply->user->name }}">
                                <div class="w-100">
                                    <div class="d-flex justify-content-between">
                                        <h6 class="fw-bold">{{ $reply->user->name }}</h6>
                                        <small class="text-muted">{{ $reply->created_at->diffForHumans() }}</small>
                                    </div>
                                    <p class="mb-1">{{ $reply->message }}</p>
                                </div>
                            </div>
                        @endforeach
                    @endif

                    <!-- Reply Form -->
                    @auth
                        <a class="small text-decoration-none" data-bs-toggle="collapse" href="#reply-form-{{ $discussion->id }}" role="button" aria-expanded="false" aria-controls="reply-form-{{ $discussion->id }}">
                            Balas
                        </a>
                        <div class="collapse mt-2" id="reply-form-{{ $discussion->id }}">
                            <form action="{{ route('discussions.store', $product) }}" method="POST">
                                @csrf
                                <input type="hidden" name="parent_id" value="{{ $discussion->id }}">
                                <div class="d-flex">
                                    <input type="text" name="message" class="form-control form-control-sm" placeholder="Tulis balasan..." required>
                                    <button type="submit" class="btn btn-sm btn-primary ms-2">Kirim</button>
                                </div>
                            </form>
                        </div>
                    @endauth
                </div>
            </div>
        @empty
            <p>Belum ada diskusi untuk produk ini.</p>
        @endforelse
    </div>
</div>