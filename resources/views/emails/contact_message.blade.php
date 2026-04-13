<!DOCTYPE html>
<html>
<head>
    <title>Yeni İletişim Mesajı</title>
</head>
<body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333;">
    <h2>Yeni İletişim Mesajı Geldi</h2>
    <p>Sitenizin iletişim formundan yeni bir mesaj var.</p>
    
    <table border="1" cellpadding="10" cellspacing="0" style="border-collapse: collapse; width: 100%; max-width: 600px;">
        <tr>
            <td style="background-color: #f9f9f9; font-weight: bold; width: 150px;">Ad Soyad</td>
            <td>{{ $message->name }}</td>
        </tr>
        <tr>
            <td style="background-color: #f9f9f9; font-weight: bold;">E-Posta</td>
            <td>{{ $message->email }}</td>
        </tr>
        <tr>
            <td style="background-color: #f9f9f9; font-weight: bold;">Konu</td>
            <td>{{ $message->subject }}</td>
        </tr>
        <tr>
            <td style="background-color: #f9f9f9; font-weight: bold;">Mesaj</td>
            <td>{!! nl2br(e($message->message)) !!}</td>
        </tr>
    </table>
    
    <br>
    <p>Bu mesajı <a href="{{ route('admin.messages.show', $message->id) }}">Admin panelinden</a> de görüntüleyip okundu olarak işaretleyebilirsiniz.</p>
</body>
</html>
