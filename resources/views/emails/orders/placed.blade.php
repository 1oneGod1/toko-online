<!DOCTYPE html>
<html>
<head>
    <title>Konfirmasi Pesanan</title>
    <style>
        body { font-family: Arial, sans-serif; color: #333; }
        .container { width: 90%; max-width: 600px; margin: 20px auto; border: 1px solid #ddd; padding: 20px; }
        .header { text-align: center; border-bottom: 1px solid #ddd; padding-bottom: 10px; margin-bottom: 20px; }
        .total { text-align: right; font-weight: bold; font-size: 1.2em; margin-top: 20px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h2>Terima Kasih Atas Pesanan Anda!</h2>
        </div>
        <p>Halo {{ $order->user->name }},</p>
        <p>Pesanan Anda dengan nomor <strong>#{{ $order->id }}</strong> telah kami terima dan akan segera kami proses.</p>

        <h3>Detail Pesanan:</h3>
        <table width="100%" cellpadding="10" style="border-collapse: collapse;">
            <thead style="background-color: #f5f5f5;">
                <tr>
                    <th style="border: 1px solid #ddd; text-align: left;">Produk</th>
                    <th style="border: 1px solid #ddd; text-align: right;">Harga</th>
                </tr>
            </thead>
            <tbody>
                @foreach($order->items as $item)
                <tr>
                    <td style="border: 1px solid #ddd;">{{ $item->product->name }} (x{{ $item->quantity }})</td>
                    <td style="border: 1px solid #ddd; text-align: right;">Rp {{ number_format($item->price * $item->quantity) }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <div class="total">
            Total: Rp {{ number_format($order->total_amount) }}
        </div>

        <p>Pesanan akan dikirim ke alamat:</p>
        <p><em>{{ $order->shipping_address }}</em></p>

        <p>Terima kasih telah berbelanja di Toko Online kami!</p>
    </div>
</body>
</html>
