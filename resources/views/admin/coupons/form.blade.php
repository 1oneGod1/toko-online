<x-layouts.app title="{{ isset($coupon) ? 'Edit Kupon' : 'Tambah Kupon' }}">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-7">
                <div class="card shadow-sm">
                    {{-- Header card --}}
                    <div class="card-header bg-dark text-white">
                        <h5 class="mb-0">{{ isset($coupon) ? 'Edit Kupon' : 'Tambah Kupon' }}</h5>
                    </div>
                    
                    <div class="card-body">
                        {{-- Form dengan action dinamis (store untuk tambah, update untuk edit) --}}
                        <form action="{{ isset($coupon) ? route('admin.coupons.update', $coupon) : route('admin.coupons.store') }}" method="POST">
                            @csrf
                            {{-- Jika edit, tambahkan method PUT --}}
                            @if(isset($coupon))
                                @method('PUT')
                            @endif
                            
                            {{-- Input kode kupon --}}
                            <div class="mb-3">
                                <label for="code" class="form-label">Kode Kupon</label>
                                <input type="text" 
                                       class="form-control @error('code') is-invalid @enderror" 
                                       id="code" 
                                       name="code" 
                                       value="{{ old('code', $coupon->code ?? '') }}" 
                                       required 
                                       autofocus
                                       placeholder="Contoh: DISKON10">
                                {{-- Tampilkan error validasi jika ada --}}
                                @error('code')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            {{-- Select jenis diskon --}}
                            <div class="mb-3">
                                <label for="type" class="form-label">Jenis Diskon</label>
                                <select class="form-select" id="type" name="type" required>
                                    {{-- Option untuk diskon persen --}}
                                    <option value="percent" @selected((old('type', $coupon->type ?? '') == 'percent'))>
                                        Persen (%)
                                    </option>
                                    {{-- Option untuk diskon nominal --}}
                                    <option value="nominal" @selected((old('type', $coupon->type ?? '') == 'nominal'))>
                                        Nominal (Rp)
                                    </option>
                                </select>
                            </div>
                            
                            {{-- Input nilai diskon --}}
                            <div class="mb-3">
                                <label for="value" class="form-label">Nilai Diskon</label>
                                <input type="number" 
                                       class="form-control @error('value') is-invalid @enderror" 
                                       id="value" 
                                       name="value" 
                                       value="{{ old('value', $coupon->value ?? '') }}" 
                                       required 
                                       min="1"
                                       placeholder="Contoh: 10 (untuk 10%) atau 50000 (untuk Rp 50.000)">
                                {{-- Tampilkan error validasi jika ada --}}
                                @error('value')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            {{-- Input maksimal penggunaan --}}
                            <div class="mb-3">
                                <label for="max_uses" class="form-label">Maksimal Penggunaan</label>
                                <input type="number" 
                                       class="form-control @error('max_uses') is-invalid @enderror" 
                                       id="max_uses" 
                                       name="max_uses" 
                                       value="{{ old('max_uses', $coupon->max_uses ?? 1) }}" 
                                       required 
                                       min="1"
                                       placeholder="Berapa kali kupon bisa digunakan">
                                {{-- Tampilkan error validasi jika ada --}}
                                @error('max_uses')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            {{-- Input tanggal kadaluarsa --}}
                            <div class="mb-3">
                                <label for="expires_at" class="form-label">Berlaku Sampai</label>
                                <input type="date" 
                                       class="form-control @error('expires_at') is-invalid @enderror" 
                                       id="expires_at" 
                                       name="expires_at" 
                                       value="{{ old('expires_at', isset($coupon) ? (is_string($coupon->expires_at) ? \Illuminate\Support\Carbon::parse($coupon->expires_at)->format('Y-m-d') : '') : '') }}" 
                                       required>
                                {{-- Tampilkan error validasi jika ada --}}
                                @error('expires_at')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            {{-- Tombol aksi --}}
                            <div class="d-flex justify-content-end">
                                {{-- Tombol batal (kembali ke index) --}}
                                <a href="{{ route('admin.coupons.index') }}" class="btn btn-secondary me-2">Batal</a>
                                {{-- Tombol simpan --}}
                                <button type="submit" class="btn btn-primary">Simpan</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-layouts.app>
