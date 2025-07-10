@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h4 class="mb-0">Tambah Rekening Bank</h4>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.bank-accounts.store') }}" method="POST">
                        @csrf
                        
                        <div class="mb-3">
                            <label for="bank_name" class="form-label">Nama Bank</label>
                            <input type="text" class="form-control @error('bank_name') is-invalid @enderror" 
                                   id="bank_name" name="bank_name" value="{{ old('bank_name') }}" 
                                   placeholder="Contoh: BCA, Mandiri, BRI">
                            @error('bank_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="account_number" class="form-label">Nomor Rekening</label>
                            <input type="text" class="form-control @error('account_number') is-invalid @enderror" 
                                   id="account_number" name="account_number" value="{{ old('account_number') }}"
                                   placeholder="Contoh: 1234567890">
                            @error('account_number')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="account_holder_name" class="form-label">Nama Pemilik Rekening</label>
                            <input type="text" class="form-control @error('account_holder_name') is-invalid @enderror" 
                                   id="account_holder_name" name="account_holder_name" value="{{ old('account_holder_name') }}"
                                   placeholder="Contoh: PT Toko Online Indonesia">
                            @error('account_holder_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="sort_order" class="form-label">Urutan Tampil</label>
                            <input type="number" class="form-control @error('sort_order') is-invalid @enderror" 
                                   id="sort_order" name="sort_order" value="{{ old('sort_order', 0) }}" min="0"
                                   placeholder="0 = tampil pertama">
                            @error('sort_order')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="is_active" name="is_active" value="1" 
                                       {{ old('is_active', true) ? 'checked' : '' }}>
                                <label class="form-check-label" for="is_active">
                                    Aktif (tampilkan kepada pelanggan)
                                </label>
                            </div>
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('admin.bank-accounts.index') }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left"></i> Kembali
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Simpan Rekening
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection