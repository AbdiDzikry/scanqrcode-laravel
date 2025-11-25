<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Scan QR Code</title>
</head>
<body>
    <h1>Scan QR Code</h1>

    @if ($errors->any())
        <div style="color: red;">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('qrcode.scan') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <label for="qr_image">Upload QR Code Image:</label><br>
        <input type="file" id="qr_image" name="qr_image" accept="image/*" required><br><br>
        <button type="submit">Scan QR Code</button>
    </form>

    <hr>

    <div id="scan-result">
        <!-- Scan result will be displayed here -->
    </div>
</body>
</html>