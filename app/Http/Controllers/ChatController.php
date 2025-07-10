<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ChatController extends Controller
{
    public function store(Request $request, Product $product)
    {
        $request->validate([
            'message' => 'required|string|max:1000',
        ]);

        $product->chatMessages()->create([
            'user_id' => Auth::id(),
            'message' => $request->message,
        ]);

        return back()->with('success', 'Pesan Anda berhasil dikirim.');
    }
}
