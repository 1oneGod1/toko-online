<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class LandingPageController extends Controller
{
    public function index()
    {
        $settings = Setting::pluck('value', 'key')->toArray();
        return view('admin.landing.index', compact('settings'));
    }
    
    public function update(Request $request)
    {
        $validated = $request->validate([
            'hero_title' => 'required|string|max:255',
            'hero_subtitle' => 'required|string|max:255',
        ]);
        
        foreach ($validated as $key => $value) {
            Setting::updateOrCreate(
                ['key' => $key],
                ['value' => $value]
            );
        }
        
        // Handle hero image if uploaded
        if ($request->hasFile('hero_image')) {
            $request->validate([
                'hero_image' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
            ]);
            
            $path = $request->file('hero_image')->store('hero-images', 'public');
            
            Setting::updateOrCreate(
                ['key' => 'hero_image'],
                ['value' => $path]
            );
        }
        
        return back()->with('success', 'Pengaturan halaman utama berhasil diperbarui.');
    }
}