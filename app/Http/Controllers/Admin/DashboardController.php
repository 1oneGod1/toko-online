<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        // Get counts for dashboard stats
        $stats = [
            'total_orders' => Order::count(),
            'pending_orders' => Order::where('status', 'pending')->count(),
            'total_products' => Product::count(),
            'total_users' => User::where('role', 'user')->count(),
        ];
        
        // Get recent orders
        $recent_orders = Order::with('user')
                              ->latest()
                              ->take(5)
                              ->get();
        
        // Get low stock products
        $low_stock_products = Product::where('stock', '<', 10)
                                    ->orderBy('stock', 'asc')
                                    ->take(5)
                                    ->get();
                                    
        return view('admin.dashboard', compact('stats', 'recent_orders', 'low_stock_products'));
    }
}