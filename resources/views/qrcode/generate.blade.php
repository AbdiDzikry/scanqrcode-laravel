<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Generate QR Code</title>
</head>
<body>
    <h1>Generate QR Code</h1>

    <form action="{{ route('qrcode.generate') }}" method="POST">
        @csrf
        <label for="data">Data for QR Code:</label><br>
        <input type="text" id="data" name="data" required><br><br>
        <button type="submit">Generate QR Code</button>
    </form>

    <hr>

    <h2>Generated QR Code:</h2>
    <div id="qrcode-container">
        <!-- QR Code will be displayed here -->
    </div>

    <script>
        document.querySelector('form').addEventListener('submit', async function(event) {
            event.preventDefault();

            const formData = new FormData(this);
            const response = await fetch(this.action, {
                method: 'POST',
                body: formData
            });

            if (response.ok) {
                const base64Svg = await response.text(); // The response is now the base64 string
                
                // Create an image element with data URI
                const imgElement = document.createElement('img');
                imgElement.src = `data:image/svg+xml;base64,${base64Svg}`;
                imgElement.alt = "QR Code";
                imgElement.style.maxWidth = '200px';

                document.getElementById('qrcode-container').innerHTML = ''; // Clear previous content
                document.getElementById('qrcode-container').appendChild(imgElement);

            } else {
                document.getElementById('qrcode-container').innerHTML = '<p style="color: red;">Error generating QR Code.</p>';
            }
        });
    </script>
</body>
</html>
