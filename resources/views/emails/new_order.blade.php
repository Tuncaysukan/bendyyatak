<!DOCTYPE html>
<html>
<head>
    <title>Yeni Sipariş</title>
</head>
<body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333;">
    <h2>Yeni Sipariş Alındı!</h2>
    <p>Sitenizden <strong>#{{ $order->order_no }}</strong> numaralı yeni bir sipariş oluşturuldu.</p>
    
    <h3>Sipariş Detayları</h3>
    <table border="1" cellpadding="10" cellspacing="0" style="border-collapse: collapse; width: 100%; max-width: 600px; margin-bottom: 20px;">
        <tr>
            <td style="background-color: #f9f9f9; font-weight: bold; width: 150px;">Müşteri</td>
            <td>{{ $order->customer_name }}</td>
        </tr>
        <tr>
            <td style="background-color: #f9f9f9; font-weight: bold;">E-Posta</td>
            <td>{{ $order->customer_email }}</td>
        </tr>
        <tr>
            <td style="background-color: #f9f9f9; font-weight: bold;">Telefon</td>
            <td>{{ $order->customer_phone }}</td>
        </tr>
        <tr>
            <td style="background-color: #f9f9f9; font-weight: bold;">Ödeme Yöntemi</td>
            <td>{{ strtoupper($order->payment_method) }}</td>
        </tr>
        <tr>
            <td style="background-color: #f9f9f9; font-weight: bold;">Tutar</td>
            <td>{{ number_format($order->total, 2) }} ₺</td>
        </tr>
    </table>

    <h3>Satın Alınan Ürünler</h3>
    <ul>
        @foreach($order->items as $item)
            <li>{{ $item->quantity }}x {{ $item->product_name }} - {{ number_format($item->total_price, 2) }} ₺</li>
        @endforeach
    </ul>
    
    <br>
    <p>Sipariş durumunu güncellemek ve detaylara erişmek için <a href="{{ route('admin.orders.show', $order->id) }}">Admin Paneli'ne gidin</a>.</p>
</body>
</html>
