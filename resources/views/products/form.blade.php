<x-layouts.app title="{{ isset($product) ? 'Ubah Produk' : 'Tambah Produk' }}">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-9">
                <div class="card shadow-sm">
                    <div class="card-header bg-white pb-0 border-0">
                         <div class="d-flex justify-content-between align-items-center">
                            <h1 class="h4 mb-0">{{ isset($product) ? 'Ubah Produk' : 'Tambah Produk' }}</h1>
                            @can('is-admin')
                                <a href="{{ route('categories.index') }}" class="btn btn-manage">Kelola Kategori</a>
                            @endcan
                        </div>
                        <hr>
                    </div>
                    <div class="card-body pt-0">
                        {{-- Pastikan action form sudah benar untuk create dan update --}}
                        <form action="{{ isset($product) ? route('products.update', $product) : route('products.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            {{-- Jika ini form edit, tambahkan method PUT --}}
                            @if(isset($product))
                                @method('PUT')
                            @endif

                            <div class="mb-3">
                                <label for="name" class="form-label">Nama Produk</label>
                                <input type="text" class="form-control" id="name" name="name" value="{{ old('name', $product->name ?? '') }}" placeholder="Contoh: Kemeja Flanel" required>
                            </div>
                            
                            <div class="mb-3">
                                <label for="description" class="form-label">Deskripsi</label>
                                <textarea class="form-control" id="description" name="description" rows="4" placeholder="Deskripsi singkat produk..." required>{{ old('description', $product->description ?? '') }}</textarea>
                            </div>

                            <div class="mb-3">
                                <label for="price" class="form-label">Harga (Rp)</label>
                                <input type="number" class="form-control" id="price" name="price" value="{{ old('price', $product->price ?? '') }}" placeholder="Contoh: 150000" required>
                            </div>
                            
                            <div class="mb-3">
                                <label for="discount_price" class="form-label">Harga Diskon (Rp) <small class="text-muted">(Opsional)</small></label>
                                <input type="number" class="form-control" id="discount_price" name="discount_price" value="{{ old('discount_price', $product->discount_price ?? '') }}" placeholder="Contoh: 120000">
                            </div>
                            
                            <div class="mb-3">
                                <label for="stock" class="form-label">Stok</label>
                                <input type="number" class="form-control" id="stock" name="stock" value="{{ old('stock', $product->stock ?? 0) }}" placeholder="Jumlah stok" required>
                            </div>

                            <div class="mb-3">
                                <label for="category_id" class="form-label">Kategori</label>
                                <select class="form-select" id="category_id" name="category_id" required>
                                    <option value="" disabled selected>Pilih Kategori</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}" @selected(old('category_id', $product->category_id ?? '') == $category->id)>
                                            {{ $category->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="mb-4">
                                <label for="photo" class="form-label">Foto Produk</label>
                                <input class="form-control" type="file" id="photo" name="photo">
                            </div>
                            
                            <div class="d-flex justify-content-start">
                                <button type="submit" class="btn btn-save px-4">
                                    {{ isset($product) ? 'Simpan Perubahan' : 'Simpan' }}
                                </button>
                                <a href="{{ route('products.index') }}" class="btn btn-link text-muted ms-3">Batal</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-layouts.app>
