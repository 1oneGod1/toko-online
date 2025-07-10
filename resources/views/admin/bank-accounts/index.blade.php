@extends('layouts.app')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Manajemen Rekening Bank</h2>
        <a href="{{ route('admin.bank-accounts.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Tambah Rekening
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success" role="alert">
            {{ session('success') }}
        </div>
    @endif

    <div class="card">
        <div class="card-body">
            @if($bankAccounts->count() > 0)
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead class="table-light">
                            <tr>
                                <th>Bank</th>
                                <th>No. Rekening</th>
                                <th>Nama Pemilik</th>
                                <th>Status</th>
                                <th>Urutan</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($bankAccounts as $account)
                            <tr>
                                <td>{{ $account->bank_name }}</td>
                                <td>{{ $account->account_number }}</td>
                                <td>{{ $account->account_holder_name }}</td>
                                <td>
                                    @if($account->is_active)
                                        <span class="badge bg-success">Aktif</span>
                                    @else
                                        <span class="badge bg-secondary">Nonaktif</span>
                                    @endif
                                </td>
                                <td>{{ $account->sort_order }}</td>
                                <td>
                                    <a href="{{ route('admin.bank-accounts.edit', $account) }}" 
                                       class="btn btn-sm btn-warning">
                                        <i class="fas fa-edit"></i> Edit
                                    </a>
                                    <form action="{{ route('admin.bank-accounts.destroy', $account) }}" 
                                          method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger" 
                                                onclick="return confirm('Yakin ingin menghapus rekening ini?')">
                                            <i class="fas fa-trash"></i> Hapus
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="text-center py-4">
                    <i class="fas fa-university fa-3x text-muted mb-3"></i>
                    <h5>Belum ada rekening bank</h5>
                    <p class="text-muted">Tambahkan rekening bank untuk menerima pembayaran dari pelanggan.</p>
                    <a href="{{ route('admin.bank-accounts.create') }}" class="btn btn-primary">
                        Tambah Rekening Pertama
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection