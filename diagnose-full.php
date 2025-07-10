<?php

/**
 * Comprehensive diagnostic script for Toko Online
 * This will check all aspects of the system and fix common issues
 * Run with: php diagnose-full.php
 */

echo "=================================================================\n";
echo " TOKO ONLINE - COMPREHENSIVE DIAGNOSTICS\n";
echo "=================================================================\n\n";

// Set up Laravel environment
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Route;

// 1. Check Database Connection
echo "CHECKING DATABASE CONNECTION...\n";
try {
    DB::connection()->getPdo();
    echo "✅ Database connection successful: " . DB::connection()->getDatabaseName() . "\n\n";
} catch (\Exception $e) {
    echo "❌ Database connection failed: " . $e->getMessage() . "\n\n";
    exit(1);
}

// 2. Check required tables
echo "CHECKING DATABASE TABLES...\n";
$requiredTables = ['products', 'categories', 'users', 'reviews', 'discussions', 'orders', 'order_items', 'settings'];
$missingTables = [];

foreach ($requiredTables as $table) {
    if (!Schema::hasTable($table)) {
        $missingTables[] = $table;
    }
}

if (empty($missingTables)) {
    echo "✅ All required tables exist.\n\n";
} else {
    echo "❌ Missing tables: " . implode(", ", $missingTables) . "\n";
    echo "   Running migrations to create missing tables...\n";
    
    $output = [];
    exec('php artisan migrate', $output, $returnCode);
    
    if ($returnCode === 0) {
        echo "   ✅ Migrations completed successfully.\n\n";
    } else {
        echo "   ❌ Migrations failed: " . implode("\n   ", $output) . "\n\n";
    }
}

// 3. Check Product table structure
echo "CHECKING PRODUCT TABLE STRUCTURE...\n";
if (Schema::hasTable('products')) {
    $columns = Schema::getColumnListing('products');
    $requiredColumns = ['id', 'name', 'slug', 'description', 'price', 'stock', 'discount_price', 'category_id', 'image'];
    $missingColumns = array_diff($requiredColumns, $columns);
    
    if (empty($missingColumns)) {
        echo "✅ Product table has all required columns.\n";
    } else {
        echo "❌ Product table missing columns: " . implode(", ", $missingColumns) . "\n";
        echo "   Adding missing columns...\n";
        
        foreach ($missingColumns as $column) {
            switch ($column) {
                case 'stock':
                    DB::statement('ALTER TABLE products ADD stock INT DEFAULT 0 AFTER price');
                    echo "   Added stock column.\n";
                    break;
                case 'discount_price':
                    DB::statement('ALTER TABLE products ADD discount_price INT NULL AFTER stock');
                    echo "   Added discount_price column.\n";
                    break;
                case 'slug':
                    DB::statement('ALTER TABLE products ADD slug VARCHAR(255) AFTER name');
                    echo "   Added slug column.\n";
                    break;
                // Add other columns as needed
            }
        }
    }
    
    // Check products count
    $productCount = DB::table('products')->count();
    echo "   Found $productCount products in database.\n\n";
} else {
    echo "❌ Products table doesn't exist.\n\n";
}

// 4. Check Routes
echo "CHECKING ROUTES...\n";
$routes = Route::getRoutes();
$requiredRoutes = [
    'products.index', 'products.create', 'products.store', 'products.edit', 'products.update', 'products.destroy',
    'categories.index', 'categories.create', 'categories.store', 'categories.edit', 'categories.update', 'categories.destroy',
    'admin.dashboard', 'admin.orders.index', 'admin.users.index', 'admin.landing.index',
    'admin.stock.index', 'admin.stock.update', 'admin.stock.bulk-update',
    'reviews.store', 'discussions.store'
];

$missingRoutes = [];
foreach ($requiredRoutes as $route) {
    if (!$routes->hasNamedRoute($route)) {
        $missingRoutes[] = $route;
    }
}

if (empty($missingRoutes)) {
    echo "✅ All required routes exist.\n\n";
} else {
    echo "❌ Missing routes: " . implode(", ", $missingRoutes) . "\n";
    echo "   Please run: php update-web-routes.php\n\n";
}

// 5. Check Controllers
echo "CHECKING CONTROLLERS...\n";
$requiredControllers = [
    'app/Http/Controllers/ProductController.php',
    'app/Http/Controllers/CategoryController.php',
    'app/Http/Controllers/Admin/DashboardController.php',
    'app/Http/Controllers/Admin/OrderController.php',
    'app/Http/Controllers/Admin/UserController.php',
    'app/Http/Controllers/Admin/StockController.php',
    'app/Http/Controllers/ReviewController.php',
    'app/Http/Controllers/DiscussionController.php',
];

$missingControllers = [];
foreach ($requiredControllers as $controller) {
    if (!file_exists(__DIR__ . '/' . $controller)) {
        $missingControllers[] = $controller;
    }
}

if (empty($missingControllers)) {
    echo "✅ All required controllers exist.\n\n";
} else {
    echo "❌ Missing controllers: " . implode(", ", $missingControllers) . "\n\n";
}

// 6. Check Views
echo "CHECKING VIEWS...\n";
$requiredViews = [
    'resources/views/products/index.blade.php',
    'resources/views/products/show.blade.php',
    'resources/views/products/create.blade.php',
    'resources/views/products/edit.blade.php',
    'resources/views/admin/dashboard.blade.php',
    'resources/views/admin/stock/index.blade.php',
    'resources/views/components/product/reviews.blade.php',
    'resources/views/components/product/discussions.blade.php',
];

$missingViews = [];
foreach ($requiredViews as $view) {
    if (!file_exists(__DIR__ . '/' . $view)) {
        $missingViews[] = $view;
    }
}

if (empty($missingViews)) {
    echo "✅ All required views exist.\n\n";
} else {
    echo "❌ Missing views: " . implode(", ", $missingViews) . "\n\n";
}

// 7. Fix product management UI
echo "CHECKING PRODUCT MANAGEMENT UI...\n";

// Check and fix the admin dashboard
$dashboardPath = __DIR__ . '/resources/views/admin/dashboard.blade.php';
if (file_exists($dashboardPath)) {
    $dashboard = file_get_contents($dashboardPath);
    
    if (strpos($dashboard, 'Manajemen Produk') === false) {
        echo "❌ Admin dashboard missing product management button.\n";
        echo "   Fixing admin dashboard...\n";
        
        // Find the right spot to add the button
        $pos = strpos($dashboard, '<div class="row g-3">');
        if ($pos !== false) {
            $buttonCode = <<<'HTML'
                            <div class="col-md-3">
                                <a href="{{ route('products.create') }}" class="btn btn-outline-primary w-100 p-3">
                                    <i class="bi bi-plus-circle"></i> Tambah Produk
                                </a>
                            </div>
                            <div class="col-md-3">
                                <a href="{{ route('products.index') }}" class="btn btn-outline-success w-100 p-3">
                                    <i class="bi bi-box-seam"></i> Manajemen Produk
                                </a>
                            </div>
HTML;
            
            $newDashboard = substr($dashboard, 0, $pos + 23) . $buttonCode . substr($dashboard, $pos + 23);
            file_put_contents($dashboardPath, $newDashboard);
            echo "   ✅ Fixed admin dashboard.\n";
        }
    } else {
        echo "✅ Admin dashboard has product management button.\n";
    }
}

// Check and fix the admin sidebar
$adminLayoutPath = __DIR__ . '/resources/views/components/layouts/admin.blade.php';
if (file_exists($adminLayoutPath)) {
    $adminLayout = file_get_contents($adminLayoutPath);
    
    if (strpos($adminLayout, 'Manajemen Produk') === false) {
        echo "❌ Admin sidebar missing product management link.\n";
        echo "   Fixing admin sidebar...\n";
        
        // Find the right spot to add the link
        $pos = strpos($adminLayout, '<div class="list-group rounded-0 border-0">');
        if ($pos !== false) {
            $linkCode = <<<'HTML'
                    <a href="{{ route('products.index') }}" class="list-group-item list-group-item-action border-0 {{ request()->routeIs('products.*') && !request()->routeIs('products.show') ? 'active' : '' }}">
                        <i class="bi bi-box-seam me-2"></i> Manajemen Produk
                    </a>
HTML;
            
            $newLayout = substr($adminLayout, 0, $pos + 44) . $linkCode . substr($adminLayout, $pos + 44);
            file_put_contents($adminLayoutPath, $newLayout);
            echo "   ✅ Fixed admin sidebar.\n";
        }
    } else {
        echo "✅ Admin sidebar has product management link.\n";
    }
}

// 8. Create the ProductController if missing
echo "\nCHECKING PRODUCT CONTROLLER...\n";
$productControllerPath = __DIR__ . '/app/Http/Controllers/ProductController.php';
if (!file_exists($productControllerPath)) {
    echo "❌ ProductController doesn't exist. Creating it...\n";
    
    $productControllerContent = <<<'PHP'
<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')->except(['index', 'show']);
        $this->middleware('can:is-admin')->except(['index', 'show']);
    }
    
    public function index()
    {
        $products = Product::with('category')->latest()->paginate(12);
        return view('products.index', compact('products'));
    }
    
    public function create()
    {
        $categories = Category::all();
        return view('products.create', compact('categories'));
    }
    
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|max:255',
            'category_id' => 'required|exists:categories,id',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'discount_price' => 'nullable|numeric|min:0|lt:price',
            'description' => 'required',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);
        
        // Handle image upload
        $path = $request->file('image')->store('products', 'public');
        
        Product::create([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'description' => $request->description,
            'price' => $request->price,
            'stock' => $request->stock,
            'discount_price' => $request->discount_price,
            'category_id' => $request->category_id,
            'image' => $path,
        ]);
        
        return redirect()->route('products.index')
            ->with('success', 'Produk berhasil ditambahkan!');
    }
    
    public function show(Product $product)
    {
        $product->load(['category', 'reviews.user', 'discussions.user', 'discussions.replies.user']);
        return view('products.show', compact('product'));
    }
    
    public function edit(Product $product)
    {
        $categories = Category::all();
        return view('products.edit', compact('product', 'categories'));
    }
    
    public function update(Request $request, Product $product)
    {
        $request->validate([
            'name' => 'required|max:255',
            'category_id' => 'required|exists:categories,id',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'discount_price' => 'nullable|numeric|min:0|lt:price',
            'description' => 'required',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);
        
        $data = [
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'description' => $request->description,
            'price' => $request->price,
            'stock' => $request->stock,
            'discount_price' => $request->discount_price,
            'category_id' => $request->category_id,
        ];
        
        // Handle image upload if provided
        if ($request->hasFile('image')) {
            // Delete old image
            if ($product->image) {
                Storage::disk('public')->delete($product->image);
            }
            
            $data['image'] = $request->file('image')->store('products', 'public');
        }
        
        $product->update($data);
        
        return redirect()->route('products.index')
            ->with('success', 'Produk berhasil diperbarui!');
    }
    
    public function destroy(Product $product)
    {
        // Delete image
        if ($product->image) {
            Storage::disk('public')->delete($product->image);
        }
        
        $product->delete();
        
        return redirect()->route('products.index')
            ->with('success', 'Produk berhasil dihapus!');
    }
}
PHP;
    
    // Create the directory if it doesn't exist
    if (!file_exists(dirname($productControllerPath))) {
        mkdir(dirname($productControllerPath), 0777, true);
    }
    
    file_put_contents($productControllerPath, $productControllerContent);
    echo "✅ Created ProductController.\n";
} else {
    echo "✅ ProductController exists.\n";
    
    // Check if it has all necessary methods
    $controller = file_get_contents($productControllerPath);
    $methods = ['index', 'create', 'store', 'show', 'edit', 'update', 'destroy'];
    $missingMethods = [];
    
    foreach ($methods as $method) {
        if (strpos($controller, "function $method") === false) {
            $missingMethods[] = $method;
        }
    }
    
    if (!empty($missingMethods)) {
        echo "❌ ProductController missing methods: " . implode(", ", $missingMethods) . "\n";
    } else {
        echo "✅ ProductController has all necessary methods.\n";
    }
}

// 9. Check if Product index view exists, create if missing
echo "\nCHECKING PRODUCT INDEX VIEW...\n";
$productIndexPath = __DIR__ . '/resources/views/products/index.blade.php';
if (!file_exists($productIndexPath)) {
    echo "❌ Product index view doesn't exist. Creating it...\n";
    
    $productIndexContent = <<<'HTML'
<x-layouts.app title="Semua Produk">
    <div class="container py-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="h3">Semua Produk</h1>
            
            @can('is-admin')
                <a href="{{ route('products.create') }}" class="btn btn-primary">
                    <i class="bi bi-plus-circle"></i> Tambah Produk
                </a>
            @endcan
        </div>

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 row-cols-xl-4 g-4">
            @forelse($products as $product)
                <div class="col">
                    <div class="card h-100 shadow-sm product-card">
                        @can('is-admin')
                            <div class="admin-actions position-absolute top-0 end-0 m-2">
                                <div class="btn-group btn-group-sm">
                                    <a href="{{ route('products.edit', $product) }}" class="btn btn-light">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <form action="{{ route('products.destroy', $product) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus produk ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-light text-danger">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @endcan
                        <a href="{{ route('products.show', $product->slug) }}">
                            <img src="{{ Str::startsWith($product->image ?? '', ['http', 'https']) ? $product->image : asset('storage/' . ($product->image ?? 'default.jpg')) }}" 
                                class="card-img-top" 
                                alt="{{ $product->name }}"
                                onerror="this.src='https://placehold.co/600x400?text=No+Image'">
                        </a>
                        <div class="card-body">
                            <h5 class="card-title">{{ $product->name }}</h5>
                            <p class="card-text text-truncate">{{ $product->description }}</p>
                            
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    @if($product->discount_price)
                                        <p class="text-decoration-line-through text-muted mb-0">
                                            Rp {{ number_format($product->price, 0, ',', '.') }}
                                        </p>
                                        <p class="fw-bold text-danger">
                                            Rp {{ number_format($product->discount_price, 0, ',', '.') }}
                                        </p>
                                    @else
                                        <p class="fw-bold">
                                            Rp {{ number_format($product->price, 0, ',', '.') }}
                                        </p>
                                    @endif
                                    
                                    <div class="mt-2">
                                        <x-product.star-rating :rating="$product->average_rating" />
                                        <small class="text-muted ms-1">{{ $product->rating_count }}</small>
                                    </div>
                                </div>
                                <div>
                                    <span class="badge {{ $product->stock > 0 ? 'bg-success' : 'bg-danger' }}">
                                        {{ $product->stock > 0 ? 'Stok: ' . $product->stock : 'Stok habis' }}
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer bg-white border-top-0">
                            <a href="{{ route('products.show', $product->slug) }}" class="btn btn-outline-primary w-100">Detail</a>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12">
                    <div class="alert alert-info">
                        Belum ada produk yang tersedia.
                    </div>
                </div>
            @endforelse
        </div>

        <div class="mt-4">
            {{ $products->links() }}
        </div>
    </div>
</x-layouts.app>
HTML;
    
    // Create the directory if it doesn't exist
    if (!file_exists(dirname($productIndexPath))) {
        mkdir(dirname($productIndexPath), 0777, true);
    }
    
    file_put_contents($productIndexPath, $productIndexContent);
    echo "✅ Created Product index view.\n";
} else {
    echo "✅ Product index view exists.\n";
}

// 10. Check if Product create view exists, create if missing
echo "\nCHECKING PRODUCT CREATE VIEW...\n";
$productCreatePath = __DIR__ . '/resources/views/products/create.blade.php';
if (!file_exists($productCreatePath)) {
    echo "❌ Product create view doesn't exist. Creating it...\n";
    
    $productCreateContent = <<<'HTML'
<x-layouts.app title="Tambah Produk Baru">
    <div class="container py-4">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card shadow-sm">
                    <div class="card-header bg-white">
                        <div class="d-flex justify-content-between align-items-center">
                            <h5 class="mb-0">Tambah Produk Baru</h5>
                            <a href="{{ route('products.index') }}" class="btn btn-sm btn-outline-secondary">
                                <i class="bi bi-arrow-left"></i> Kembali
                            </a>
                        </div>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            
                            <div class="mb-3">
                                <label for="name" class="form-label">Nama Produk</label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name') }}" required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="mb-3">
                                <label for="category_id" class="form-label">Kategori</label>
                                <select class="form-select @error('category_id') is-invalid @enderror" id="category_id" name="category_id" required>
                                    <option value="">Pilih Kategori</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                            {{ $category->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('category_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="price" class="form-label">Harga (Rp)</label>
                                    <input type="number" class="form-control @error('price') is-invalid @enderror" id="price" name="price" value="{{ old('price') }}" min="0" required>
                                    @error('price')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <label for="stock" class="form-label">Stok</label>
                                    <input type="number" class="form-control @error('stock') is-invalid @enderror" id="stock" name="stock" value="{{ old('stock', 0) }}" min="0" required>
                                    @error('stock')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="mb-3">
                                <label for="discount_price" class="form-label">Harga Diskon (Opsional)</label>
                                <input type="number" class="form-control @error('discount_price') is-invalid @enderror" id="discount_price" name="discount_price" value="{{ old('discount_price') }}" min="0">
                                @error('discount_price')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="mb-3">
                                <label for="description" class="form-label">Deskripsi</label>
                                <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" rows="4" required>{{ old('description') }}</textarea>
                                @error('description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="mb-3">
                                <label for="image" class="form-label">Gambar Produk</label>
                                <input type="file" class="form-control @error('image') is-invalid @enderror" id="image" name="image" accept="image/*" required>
                                @error('image')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="d-grid">
                                <button type="submit" class="btn btn-primary">Simpan Produk</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-layouts.app>
HTML;
    
    file_put_contents($productCreatePath, $productCreateContent);
    echo "✅ Created Product create view.\n";
} else {
    echo "✅ Product create view exists.\n";
}

// 11. Check if Product edit view exists, create if missing
echo "\nCHECKING PRODUCT EDIT VIEW...\n";
$productEditPath = __DIR__ . '/resources/views/products/edit.blade.php';
if (!file_exists($productEditPath)) {
    echo "❌ Product edit view doesn't exist. Creating it...\n";
    
    $productEditContent = <<<'HTML'
<x-layouts.app title="Edit Produk">
    <div class="container py-4">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card shadow-sm">
                    <div class="card-header bg-white">
                        <div class="d-flex justify-content-between align-items-center">
                            <h5 class="mb-0">Edit Produk</h5>
                            <a href="{{ route('products.index') }}" class="btn btn-sm btn-outline-secondary">
                                <i class="bi bi-arrow-left"></i> Kembali
                            </a>
                        </div>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('products.update', $product) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')
                            
                            <div class="mb-3">
                                <label for="name" class="form-label">Nama Produk</label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', $product->name) }}" required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="mb-3">
                                <label for="category_id" class="form-label">Kategori</label>
                                <select class="form-select @error('category_id') is-invalid @enderror" id="category_id" name="category_id" required>
                                    <option value="">Pilih Kategori</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}" {{ (old('category_id', $product->category_id) == $category->id) ? 'selected' : '' }}>
                                            {{ $category->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('category_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="price" class="form-label">Harga (Rp)</label>
                                    <input type="number" class="form-control @error('price') is-invalid @enderror" id="price" name="price" value="{{ old('price', $product->price) }}" min="0" required>
                                    @error('price')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <label for="stock" class="form-label">Stok</label>
                                    <input type="number" class="form-control @error('stock') is-invalid @enderror" id="stock" name="stock" value="{{ old('stock', $product->stock) }}" min="0" required>
                                    @error('stock')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="mb-3">
                                <label for="discount_price" class="form-label">Harga Diskon (Opsional)</label>
                                <input type="number" class="form-control @error('discount_price') is-invalid @enderror" id="discount_price" name="discount_price" value="{{ old('discount_price', $product->discount_price) }}" min="0">
                                @error('discount_price')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="mb-3">
                                <label for="description" class="form-label">Deskripsi</label>
                                <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" rows="4" required>{{ old('description', $product->description) }}</textarea>
                                @error('description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="mb-3">
                                <label for="image" class="form-label">Gambar Produk</label>
                                @if($product->image)
                                    <div class="mb-2">
                                        <img src="{{ Str::startsWith($product->image, ['http', 'https']) ? $product->image : asset('storage/' . $product->image) }}" 
                                            class="img-thumbnail" 
                                            alt="{{ $product->name }}"
                                            style="max-height: 200px;">
                                    </div>
                                @endif
                                <input type="file" class="form-control @error('image') is-invalid @enderror" id="image" name="image" accept="image/*">
                                <div class="form-text">Biarkan kosong jika tidak ingin mengubah gambar.</div>
                                @error('image')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="d-grid">
                                <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-layouts.app>
HTML;
    
    file_put_contents($productEditPath, $productEditContent);
    echo "✅ Created Product edit view.\n";
} else {
    echo "✅ Product edit view exists.\n";
}

// 12. Check admin layout and navbar
echo "\nCHECKING ADMIN LAYOUT AND NAVBAR...\n";

// Check and ensure the admin layout exists
$adminLayoutPath = __DIR__ . '/resources/views/components/layouts/admin.blade.php';
if (!file_exists($adminLayoutPath)) {
    echo "❌ Admin layout doesn't exist. Please run: php artisan make:component layouts.admin --view\n";
} else {
    echo "✅ Admin layout exists.\n";
}

// Check and ensure the navbar has admin dropdown
$navbarPath = __DIR__ . '/resources/views/components/layouts/navbar.blade.php';
if (file_exists($navbarPath)) {
    $navbar = file_get_contents($navbarPath);
    
    if (strpos($navbar, 'Admin Toko') === false) {
        echo "❌ Navbar missing admin dropdown. Please check your navbar component.\n";
    } else {
        echo "✅ Navbar has admin dropdown.\n";
    }
} else {
    echo "❌ Navbar component doesn't exist. Please check your views structure.\n";
}

// 13. Summary and recommendations
echo "\n=================================================================\n";
echo " DIAGNOSIS SUMMARY\n";
echo "=================================================================\n\n";

echo "1. Run migrations if any tables are missing:\n   php artisan migrate\n\n";
echo "2. Update routes file if any routes are missing:\n   php update-web-routes.php\n\n";
echo "3. Clear all caches:\n   php artisan optimize:clear\n\n";
echo "4. Restart the Laravel server:\n   php artisan serve\n\n";

// Output diagnostic completion
echo "Diagnostics completed!\n";
echo "Please address any issues found and restart your application.\n";