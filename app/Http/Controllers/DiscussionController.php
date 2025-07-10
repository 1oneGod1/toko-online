<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class DiscussionController extends Controller
{
    /**
     * Store a newly created discussion or reply in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Product $product)
    {
        $request->validate([
            'message' => 'required|string|max:1000',
            'parent_id' => 'nullable|exists:discussions,id',
        ]);

        $product->discussions()->create([
            'user_id' => auth()->id(),
            'message' => $request->message,
            'parent_id' => $request->parent_id,
        ]);

        return back()->with('success', 'Pesan Anda berhasil dikirim!');
    }
}