<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>QR Code Scanner</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body, html {
            height: 100%;
        }
        .main-container {
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            background-color: #f8f9fa;
        }
        .scanner-card {
            width: 100%;
            max-width: 500px;
            border-radius: 15px;
        }
        #qr-reader {
            border-radius: 8px;
            border: 2px solid #dee2e6;
        }
        #qr-reader video {
            transform: scaleX(-1);
        }
    </style>
</head>
<body>
    <div class="main-container">
        <div class="col-lg-5 col-md-8 col-sm-10 col-11">
            <div class="card shadow-lg scanner-card">
                <div class="card-header text-center bg-dark text-white">
                    <h3>QR Code Scanner</h3>
                </div>
                <div class="card-body text-center p-4">
                    <p class="text-muted" id="instruction-text">Arahkan kamera ke QR code untuk memindai.</p>
                    <div id="qr-reader" class="mx-auto"></div>
                    <div id="scan-result" class="mt-3"></div>
                    <button id="scan-again-btn" class="btn btn-lg btn-outline-primary mt-3" style="display: none;">Pindai Lagi</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>
    <script>
        document.addEventListener('DOMContentLoaded', (event) => {
            const qrReaderElement = document.getElementById('qr-reader');
            const resultContainer = document.getElementById('scan-result');
            const scanAgainBtn = document.getElementById('scan-again-btn');
            const instructionText = document.getElementById('instruction-text');

            const html5QrcodeScanner = new Html5QrcodeScanner(
                "qr-reader", 
                { fps: 10, qrbox: { width: 250, height: 250 } }, 
                false
            );

            function onScanSuccess(decodedText, decodedResult) {
                html5QrcodeScanner.clear();
                qrReaderElement.style.display = 'none';
                
                resultContainer.innerHTML = `
                    <div class="alert alert-success">
                        <h4 class="alert-heading">Berhasil Dipindai!</h4>
                        <p>${decodedText}</p>
                    </div>
                `;
                instructionText.style.display = 'none';
                scanAgainBtn.style.display = 'block';
            }

            function onScanFailure(error) {
                // Fail silently. The scanner will continue trying.
            }

            function startScanner() {
                html5QrcodeScanner.render(onScanSuccess, onScanFailure);
                qrReaderElement.style.display = 'block';
                instructionText.style.display = 'block';
                resultContainer.innerHTML = '';
                scanAgainBtn.style.display = 'none';
            }

            scanAgainBtn.addEventListener('click', startScanner);

            // Start scanner on page load
            startScanner();
        });
    </script>
</body>
</html>