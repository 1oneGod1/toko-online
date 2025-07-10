<x-layouts.app title="Manajemen Kupon">
    <div class="container">
        {{-- Header dengan judul dan tombol tambah kupon --}}
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="h3 mb-0">Manajemen Kupon</h1>
            <a href="{{ route('admin.coupons.create') }}" class="btn btn-primary">+ Tambah Kupon</a>
        </div>
        
        {{-- Alert untuk menampilkan pesan sukses --}}
        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        
        {{-- Card wrapper untuk tabel --}}
        <div class="card shadow-sm">
            <div class="card-body">
                <table class="table table-hover align-middle">
                    {{-- Header tabel --}}
                    <thead class="table-dark">
                        <tr>
                            <th>Kode</th>                      {{-- Kode kupon --}}
                            <th>Jenis</th>                     {{-- Jenis diskon (persen/nominal) --}}
                            <th>Nilai</th>                     {{-- Nilai diskon --}}
                            <th>Maksimal Penggunaan</th>       {{-- Batas maksimal kupon bisa digunakan --}}
                            <th>Jumlah Penggunaan</th>         {{-- Berapa kali sudah digunakan --}}
                            <th>Berlaku Sampai</th>            {{-- Tanggal kadaluarsa --}}
                            <th class="text-end">Aksi</th>     {{-- Tombol edit/hapus --}}
                        </tr>
                    </thead>
                    <tbody>
                        {{-- Loop untuk setiap kupon --}}
                        @forelse($coupons as $coupon)
                            <tr>
                                {{-- Tampilkan kode kupon --}}
                                <td>{{ $coupon->code }}</td>
                                
                                {{-- Tampilkan jenis kupon (ubah 'percent' jadi 'Persen', 'nominal' jadi 'Nominal') --}}
                                <td>{{ $coupon->type == 'percent' ? 'Persen' : 'Nominal' }}</td>
                                
                                {{-- Tampilkan nilai kupon dengan format yang sesuai --}}
                                <td>{{ $coupon->type == 'percent' ? $coupon->value . '%' : 'Rp ' . number_format($coupon->value) }}</td>
                                
                                {{-- Tampilkan maksimal penggunaan --}}
                                <td>{{ $coupon->max_uses }}</td>
                                
                                {{-- Tampilkan jumlah penggunaan (dari withCount('users')) --}}
                                <td>{{ $coupon->users_count }}</td>
                                
                                {{-- Tampilkan tanggal kadaluarsa dengan format yang readable --}}
                                <td>{{ $coupon->expires_at ? \Illuminate\Support\Carbon::parse($coupon->expires_at)->format('d M Y') : '-' }}</td>
                                
                                {{-- Kolom aksi dengan tombol edit dan hapus --}}
                                <td class="text-end">
                                    {{-- Tombol edit --}}
                                    <a href="{{ route('admin.coupons.edit', $coupon) }}" class="btn btn-sm btn-warning">Edit</a>
                                    
                                    {{-- Form untuk hapus kupon dengan konfirmasi JavaScript --}}
                                    <form action="{{ route('admin.coupons.destroy', $coupon) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus kupon ini?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger">Hapus</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            {{-- Jika tidak ada kupon, tampilkan pesan --}}
                            <tr>
                                <td colspan="7" class="text-center">Belum ada kupon.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            {{-- Footer card untuk pagination --}}
            <div class="card-footer bg-white">
                <div class="d-flex justify-content-center">
                    {{-- Links pagination Laravel --}}
                    {{ $coupons->links() }}
                </div>
            </div>
        </div>
    </div>
</x-layouts.app>
