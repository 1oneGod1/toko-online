<x-layouts.admin title="Manajemen Stok Produk">
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="h3 mb-0">Manajemen Stok Produk</h1>
            <div>
                <a href="{{ route('products.create') }}" class="btn btn-primary">
                    <i class="bi bi-plus-circle"></i> Tambah Produk Baru
                </a>
            </div>
        </div>
        
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
        
        <div class="card shadow-sm">
            <div class="card-header bg-white d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Daftar Stok Produk</h5>
                <div>
                    <button type="button" class="btn btn-sm btn-outline-secondary" id="toggleEditMode">
                        <i class="bi bi-pencil"></i> Edit Massal
                    </button>
                    <button type="submit" form="stockForm" class="btn btn-sm btn-success d-none" id="saveAllBtn">
                        <i class="bi bi-save"></i> Simpan Semua
                    </button>
                </div>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.stock.bulk-update') }}" method="POST" id="stockForm">
                    @csrf
                    @method('PUT')
                    
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th width="5%">#</th>
                                    <th width="10%">Gambar</th>
                                    <th width="30%">Nama Produk</th>
                                    <th width="15%">Kategori</th>
                                    <th width="15%">Harga</th>
                                    <th width="10%">Stok</th>
                                    <th width="15%">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($products as $product)
                                    <tr>
                                        <td>{{ $product->id }}</td>
                                        <td>
                                            <img src="{{ Str::startsWith($product->image ?? '', 'http') ? $product->image : asset('storage/' . ($product->image ?? 'default.jpg')) }}" 
                                                class="img-thumbnail" 
                                                alt="{{ $product->name }}" 
                                                width="50" 
                                                onerror="this.src='https://placehold.co/50x50?text=Produk'">
                                        </td>
                                        <td>{{ $product->name }}</td>
                                        <td>{{ $product->category->name }}</td>
                                        <td>Rp {{ number_format($product->price, 0, ',', '.') }}</td>
                                        <td>
                                            <div class="input-group input-group-sm">
                                                <!-- Displayed when not in edit mode -->
                                                <span class="form-control single-view">
                                                    <span class="badge bg-{{ $product->stock > 10 ? 'success' : ($product->stock > 0 ? 'warning' : 'danger') }}">
                                                        {{ $product->stock }}
                                                    </span>
                                                </span>
                                                
                                                <!-- Displayed when in edit mode -->
                                                <input type="number" name="stocks[{{ $product->id }}]" value="{{ $product->stock }}" min="0" class="form-control bulk-edit d-none">
                                            </div>
                                        </td>
                                        <td>
                                            <!-- Quick Stock Update Form -->
                                            <form action="{{ route('admin.stock.update', $product) }}" method="POST" class="d-inline single-view">
                                                @csrf
                                                @method('PUT')
                                                <div class="btn-group btn-group-sm">
                                                    <button type="submit" name="stock" value="{{ $product->stock - 1 }}" class="btn btn-outline-danger" {{ $product->stock <= 0 ? 'disabled' : '' }}>
                                                        -1
                                                    </button>
                                                    <button type="submit" name="stock" value="{{ $product->stock + 1 }}" class="btn btn-outline-success">
                                                        +1
                                                    </button>
                                                    <a href="{{ route('products.edit', $product) }}" class="btn btn-outline-primary">
                                                        <i class="bi bi-pencil"></i>
                                                    </a>
                                                </div>
                                            </form>
                                            
                                            <!-- Hidden in bulk edit mode -->
                                            <span class="bulk-edit d-none">
                                                <a href="{{ route('products.edit', $product) }}" class="btn btn-sm btn-outline-primary">
                                                    <i class="bi bi-pencil"></i> Edit
                                                </a>
                                            </span>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center">Belum ada produk.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </form>
                
                <div class="mt-4">
                    {{ $products->links() }}
                </div>
            </div>
        </div>
    </div>
    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const toggleEditModeBtn = document.getElementById('toggleEditMode');
            const saveAllBtn = document.getElementById('saveAllBtn');
            const singleViewElements = document.querySelectorAll('.single-view');
            const bulkEditElements = document.querySelectorAll('.bulk-edit');
            
            toggleEditModeBtn.addEventListener('click', function() {
                // Toggle button text
                if (toggleEditModeBtn.innerHTML.includes('Edit Massal')) {
                    toggleEditModeBtn.innerHTML = '<i class="bi bi-x-circle"></i> Batal';
                    saveAllBtn.classList.remove('d-none');
                } else {
                    toggleEditModeBtn.innerHTML = '<i class="bi bi-pencil"></i> Edit Massal';
                    saveAllBtn.classList.add('d-none');
                }
                
                // Toggle visibility of elements
                singleViewElements.forEach(el => {
                    el.classList.toggle('d-none');
                });
                
                bulkEditElements.forEach(el => {
                    el.classList.toggle('d-none');
                });
            });
        });
    </script>
</x-layouts.admin>