@if ($errors->any())
    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="mb-4">
    <label for="code" class="block text-sm font-medium text-gray-700">Kode Kupon</label>
    <input type="text" name="code" id="code" value="{{ old('code', $coupon->code ?? '') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" required>
</div>

<div class="mb-4">
    <label for="type" class="block text-sm font-medium text-gray-700">Tipe Diskon</label>
    <select name="type" id="type" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" required>
        <option value="percent" @selected(old('type', $coupon->type ?? '') == 'percent')>Persentase (%)</option>
        <option value="fixed" @selected(old('type', $coupon->type ?? '') == 'fixed')>Nominal Tetap (Rp)</option>
    </select>
</div>

<div class="mb-4">
    <label for="value" class="block text-sm font-medium text-gray-700">Nilai Diskon</label>
    <input type="number" name="value" id="value" value="{{ old('value', $coupon->value ?? '') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" required step="any">
</div>

<div class="mb-4">
    <label for="expires_at" class="block text-sm font-medium text-gray-700">Tanggal Kedaluwarsa (Opsional)</label>
    <input type="date" name="expires_at" id="expires_at" value="{{ old('expires_at', isset($coupon->expires_at) ? $coupon->expires_at->format('Y-m-d') : '') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
</div>

<div class="flex items-center justify-end mt-4">
    <a href="{{ route('admin.coupons.index') }}" class="text-gray-600 hover:text-gray-900 mr-4">Batal</a>
    <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
        Simpan Kupon
    </button>
</div>
