<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ContactAdminController extends Controller
{
    public function index()
    {
        return view('admin.contact.index');
    }

    public function destroy($id)
    {
        // Logic untuk menghapus kontak
        return redirect()->back()->with('success', 'Kontak berhasil dihapus.');
    }
}