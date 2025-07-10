<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Review;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    public function store(Request $request, Product $product)
    {
        // Validate the request
        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:1000',
        ]);
        
        // Check if user already reviewed this product
        $existingReview = Review::where('user_id', auth()->id())
                               ->where('product_id', $product->id)
                               ->first();
        
        if ($existingReview) {
            // Update existing review
            $existingReview->update([
                'rating' => $request->rating,
                'comment' => $request->comment,
            ]);
            
            $message = 'Ulasan berhasil diperbarui!';
        } else {
            // Create new review
            Review::create([
                'user_id' => auth()->id(),
                'product_id' => $product->id,
                'rating' => $request->rating,
                'comment' => $request->comment,
            ]);
            
            $message = 'Terima kasih atas ulasan Anda!';
        }
        
        return back()->with('success', $message);
    }
    
    public function destroy(Review $review)
    {
        // Check if user is authorized to delete the review
        if (auth()->id() !== $review->user_id && !auth()->user()->isAdmin()) {
            return back()->with('error', 'Anda tidak memiliki izin untuk menghapus ulasan ini.');
        }
        
        $review->delete();
        
        return back()->with('success', 'Ulasan berhasil dihapus.');
    }
}
