<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class LandingPageSettingController extends Controller
{
    /**
     * Display the landing page settings form.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // Fetch all settings and convert them to a simple key => value array
        $settings = DB::table('landing_page_settings')->pluck('value', 'key')->all();
        
        return view('admin.landing.index', compact('settings'));
    }

    /**
     * Update the landing page settings.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request)
    {
        $request->validate([
            'hero_title' => 'nullable|string|max:255',
            'hero_subtitle' => 'nullable|string|max:500',
            'hero_button_text' => 'nullable|string|max:100',
            'hero_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ]);

        $settingsToUpdate = $request->only(['hero_title', 'hero_subtitle', 'hero_button_text']);

        // Handle file upload
        if ($request->hasFile('hero_image')) {
            // Get current image path to delete it later
            $currentImagePath = DB::table('landing_page_settings')->where('key', 'hero_image')->value('value');

            // Store the new image
            $path = $request->file('hero_image')->store('public/landing');
            $settingsToUpdate['hero_image'] = $path; // Store the path, not the URL

            // Delete the old image if it exists
            if ($currentImagePath && Storage::exists($currentImagePath)) {
                Storage::delete($currentImagePath);
            }
        }

        // Using DB facade for simplicity to update or insert settings
        foreach ($settingsToUpdate as $key => $value) {
            DB::table('landing_page_settings')->updateOrInsert(
                ['key' => $key],
                ['value' => $value, 'created_at' => now(), 'updated_at' => now()]
            );
        }

        return back()->with('success', 'Pengaturan halaman utama berhasil diperbarui.');
    }
}
