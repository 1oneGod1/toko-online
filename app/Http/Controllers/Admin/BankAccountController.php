<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BankAccount;
use Illuminate\Http\Request;

class BankAccountController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $bankAccounts = BankAccount::ordered()->get();
        return view('admin.bank-accounts.index', compact('bankAccounts'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.bank-accounts.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'bank_name' => 'required|string|max:255',
            'account_number' => 'required|string|max:50',
            'account_holder_name' => 'required|string|max:255',
            'is_active' => 'boolean',
            'sort_order' => 'integer|min:0'
        ]);

        BankAccount::create($validatedData);

        return redirect()->route('admin.bank-accounts.index')
                        ->with('success', 'Rekening bank berhasil ditambahkan.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(BankAccount $bankAccount)
    {
        return view('admin.bank-accounts.edit', compact('bankAccount'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, BankAccount $bankAccount)
    {
        $validatedData = $request->validate([
            'bank_name' => 'required|string|max:255',
            'account_number' => 'required|string|max:50',
            'account_holder_name' => 'required|string|max:255',
            'is_active' => 'boolean',
            'sort_order' => 'integer|min:0'
        ]);

        $bankAccount->update($validatedData);

        return redirect()->route('admin.bank-accounts.index')
                        ->with('success', 'Rekening bank berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(BankAccount $bankAccount)
    {
        $bankAccount->delete();

        return redirect()->route('admin.bank-accounts.index')
                        ->with('success', 'Rekening bank berhasil dihapus.');
    }
}