<?php

namespace App\Http\Controllers;

use App\Models\Discussion;
use App\Models\Product;
use Illuminate\Http\Request;

class DiscussionController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    public function store(Request $request, Product $product)
    {
        // Validate the request
        $request->validate([
            'message' => 'required|string|max:1000',
            'parent_id' => 'nullable|exists:discussions,id',
        ]);
        
        // Create discussion
        Discussion::create([
            'user_id' => auth()->id(),
            'product_id' => $product->id,
            'parent_id' => $request->parent_id,
            'message' => $request->message,
        ]);
        
        return back()->with('success', 'Pesan berhasil dikirim.');
    }
    
    public function destroy(Discussion $discussion)
    {
        // Check if user is authorized to delete the discussion
        if (auth()->id() !== $discussion->user_id && !auth()->user()->isAdmin()) {
            return back()->with('error', 'Anda tidak memiliki izin untuk menghapus pesan ini.');
        }
        
        $discussion->delete();
        
        return back()->with('success', 'Pesan berhasil dihapus.');
    }
}