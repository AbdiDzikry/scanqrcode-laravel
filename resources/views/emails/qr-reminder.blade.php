<!DOCTYPE html>
<html>
<head>
</head>
<body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333;">
    <div style="max-width: 600px; margin: 20px auto; padding: 20px; border: 1px solid #ddd; border-radius: 8px;">
        <h1 style="color: #007bff; border-bottom: 2px solid #007bff; padding-bottom: 10px;">QR Code Reminder</h1>
        
        <p>Halo,</p>
        <p>Berikut adalah detail QR Code Anda:</p>

        <hr style="border: 0; border-top: 1px solid #eee; margin: 20px 0;">
        
        <p style="text-align: center;">Berikut adalah QR Code yang Anda buat:</p>
        
        <div style="text-align: center; margin: 20px 0;">
            <img src="{{ $message->embedData($rawQrcodeData, 'qrcode.png') }}" alt="Generated QR Code" style="width: 200px; height: 200px; border: 1px solid #ccc; padding: 5px; display: block; margin: 0 auto;">
        </div>
        
        <p style="margin-top: 30px;">Salam hormat,</p>
        <p style="font-weight: bold;">Tim QR Code Generator</p>
    </div>
</body>
</html>