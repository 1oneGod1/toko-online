<x-layouts.admin title="Manajemen Halaman Utama">
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="h3 mb-0">Pengaturan Halaman Utama</h1>
        </div>
        
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
        
        <div class="card shadow-sm">
            <div class="card-body">
                <form action="{{ route('admin.landing.update') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    
                    <div class="mb-4">
                        <h5>Hero Section</h5>
                        <hr>
                        
                        <div class="mb-3">
                            <label for="hero_title" class="form-label">Judul Hero</label>
                            <input type="text" class="form-control @error('hero_title') is-invalid @enderror" id="hero_title" name="hero_title" value="{{ old('hero_title', $settings['hero_title'] ?? 'Wujudkan Imajinasimu') }}" required>
                            @error('hero_title')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="mb-3">
                            <label for="hero_subtitle" class="form-label">Subtitle Hero</label>
                            <input type="text" class="form-control @error('hero_subtitle') is-invalid @enderror" id="hero_subtitle" name="hero_subtitle" value="{{ old('hero_subtitle', $settings['hero_subtitle'] ?? 'Temukan produk berkualitas tinggi dengan harga terbaik.') }}" required>
                            @error('hero_subtitle')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="mb-3">
                            <label for="hero_image" class="form-label">Gambar Hero <small class="text-muted">(Opsional)</small></label>
                            <input type="file" class="form-control @error('hero_image') is-invalid @enderror" id="hero_image" name="hero_image">
                            @error('hero_image')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            
                            @if(isset($settings['hero_image']))
                                <div class="mt-2">
                                    <p class="mb-1">Gambar Saat Ini:</p>
                                    <img src="{{ asset('storage/' . $settings['hero_image']) }}" alt="Hero Image" class="img-thumbnail" style="max-height: 200px;">
                                </div>
                            @endif
                        </div>
                    </div>
                    
                    <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-save"></i> Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-layouts.admin>