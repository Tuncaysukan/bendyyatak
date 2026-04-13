<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Sipariş Durumu Güncellendi</title>
</head>
<body style="font-family: Arial, sans-serif; background-color: #f9f9f9; padding: 20px;">

    <div style="max-width: 600px; margin: 0 auto; background: #ffffff; padding: 30px; border-radius: 8px; border: 1px solid #eee;">
        
        <h2 style="color: #1a1a2e; margin-bottom: 20px;">Merhaba {{ $order->customer_name }},</h2>
        
        <p style="font-size: 15px; color: #333; line-height: 1.6;">
            <strong>#{{ $order->order_no }}</strong> numaralı siparişinizin durumu güncellenmiştir.
        </p>

        <div style="background: #f4f5f9; padding: 16px; border-radius: 8px; margin: 20px 0;">
            <p style="margin: 0 0 10px 0; font-size: 14px;">Mevcut Durum:</p>
            @php 
                $statusLabels = [
                    'pending' => 'Bekliyor',
                    'confirmed' => 'Onaylandı',
                    'preparing' => 'Hazırlanıyor',
                    'shipped' => 'Kargolandı',
                    'delivered' => 'Teslim Edildi',
                    'cancelled' => 'İptal Edildi',
                    'refunded' => 'İade Edildi'
                ];
                $label = $statusLabels[$order->status] ?? $order->status;
            @endphp
            <h3 style="margin: 0; color: #1a1a2e;">{{ mb_strtoupper($label) }}</h3>
        </div>

        @if($note)
            <div style="border-left: 4px solid #c8a96e; padding-left: 12px; margin-bottom: 20px;">
                <p style="margin: 0; font-size: 14px; color: #666;"><strong>Not:</strong> {{ $note }}</p>
            </div>
        @endif

        @if($order->status === 'shipped')
            <div style="margin-bottom: 20px;">
                <p style="font-size: 14px; color: #333;">Kargo Şirketi: <strong>{{ $order->cargo_company }}</strong></p>
                <p style="font-size: 14px; color: #333;">Takip Numarası: <strong>{{ $order->cargo_tracking_no }}</strong></p>
            </div>
        @endif

        <p style="font-size: 15px; color: #333; line-height: 1.6;">
            Bizi tercih ettiğiniz için teşekkür ederiz.
        </p>
        
        <div style="margin-top: 40px; padding-top: 20px; border-top: 1px solid #eee; text-align: center; color: #888; font-size: 12px;">
            &copy; {{ date('Y') }} BendyyYatak. Tüm hakları saklıdır.
        </div>
    </div>

</body>
</html>
