<x-layouts.admin title="Pengaturan Halaman Utama">
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0">Pengaturan Halaman Utama</h5>
        </div>
        <div class="card-body">
            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <form action="{{ route('admin.landing.update') }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="row">
                    <div class="col-md-8">
                        <!-- Hero Title -->
                        <div class="mb-3">
                            <label for="hero_title" class="form-label">Judul Hero</label>
                            <input type="text" class="form-control" id="hero_title" name="hero_title" value="{{ old('hero_title', $settings['hero_title'] ?? 'Selamat Datang di Toko Online') }}">
                            @error('hero_title')
                                <div class="text-danger mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Hero Subtitle -->
                        <div class="mb-3">
                            <label for="hero_subtitle" class="form-label">Subjudul Hero</label>
                            <textarea class="form-control" id="hero_subtitle" name="hero_subtitle" rows="3">{{ old('hero_subtitle', $settings['hero_subtitle'] ?? 'Temukan ribuan produk berkualitas dengan harga terbaik. Belanja mudah, cepat, dan aman.') }}</textarea>
                            @error('hero_subtitle')
                                <div class="text-danger mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Hero Button Text -->
                        <div class="mb-3">
                            <label for="hero_button_text" class="form-label">Teks Tombol Hero</label>
                            <input type="text" class="form-control" id="hero_button_text" name="hero_button_text" value="{{ old('hero_button_text', $settings['hero_button_text'] ?? 'Mulai Belanja Sekarang') }}">
                            @error('hero_button_text')
                                <div class="text-danger mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-4">
                        <!-- Hero Image -->
                        <div class="mb-3">
                            <label for="hero_image" class="form-label">Gambar Latar Hero</label>
                            <input class="form-control" type="file" id="hero_image" name="hero_image">
                            <small class="form-text text-muted">Kosongkan jika tidak ingin mengubah gambar.</small>
                            @error('hero_image')
                                <div class="text-danger mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        @if(isset($settings['hero_image']) && $settings['hero_image'])
                            <div class="mb-3">
                                <p class="mb-1">Gambar Saat Ini:</p>
                                <img src="{{ Storage::url($settings['hero_image']) }}" alt="Hero Image" class="img-thumbnail" style="max-width: 100%;">
                            </div>
                        @else
                            <div class="mb-3">
                                <p class="mb-1">Tidak ada gambar saat ini.</p>
                            </div>
                        @endif
                    </div>
                </div>


                <button type="submit" class="btn btn-primary mt-3">Simpan Perubahan</button>
            </form>
        </div>
    </div>
</x-layouts.admin>