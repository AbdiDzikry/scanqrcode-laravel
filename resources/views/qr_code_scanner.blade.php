<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>QR Code Scanner</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
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
        .card-header {
            /* Membuat header lebih gelap dan radius atas */
            background-color: #343a40 !important; 
            border-top-left-radius: 15px !important;
            border-top-right-radius: 15px !important;
        }
        .result-box {
            /* Gaya mirip alert sukses di gambar */
            background-color: #d1e7dd; 
            color: #0f5132;
            padding: 15px;
            margin-top: 15px;
            border-radius: 8px;
            font-size: 1.1em;
            text-align: left; /* Teks hasil pindaian rata kiri */
            border: 1px solid #badbcc;
        }
        .result-box strong {
            display: block;
            margin-bottom: 5px;
            font-size: 1.2em;
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
                <div class="card-header text-center bg-dark text-white p-3">
                    <h3 class="mb-0">QR Code Scanner</h3>
                </div>
                <div class="card-body text-center p-4">
                    <p class="text-muted" id="instruction-text">Arahkan kamera ke QR code untuk memindai.</p>
                    <div id="qr-reader" class="mx-auto"></div>
                    <div id="scan-result" class="mt-3"></div> 
                    <button id="scan-again-btn" class="btn btn-lg btn-outline-primary mt-4" style="display: none;">Pindai Lagi</button> 
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

            // Fungsi untuk mem-parsing data dari format string yang dibuat di Controller (Nama: X\nEmail: Y\nNo HP: Z)
            function parseScannedData(decodedText) {
                const data = {};
                // Pisahkan berdasarkan baris baru, lalu pisahkan key dan value
                decodedText.split('\n').forEach(line => {
                    const parts = line.split(': ');
                    if (parts.length >= 2) {
                        const key = parts[0].trim();
                        // Gabungkan kembali bagian value jika ada ':' di dalam data itu sendiri (misal URL)
                        const value = parts.slice(1).join(': ').trim(); 
                        data[key] = value;
                    }
                });
                return data;
            }

            function onScanSuccess(decodedText, decodedResult) {
                html5QrcodeScanner.clear().catch(console.error); // Pastikan .clear() dipanggil dengan aman
                qrReaderElement.style.display = 'none';
                
                // 1. Parsing data
                const parsedData = parseScannedData(decodedText);
                const nama = parsedData['Nama'] || 'Tidak Ada Data Nama';
                const email = parsedData['Email'] || 'Tidak Ada Data Email';
                const noHp = parsedData['No HP'] || 'Tidak Ada Data No HP';

                // 2. Tampilkan hasil dengan format rapi
                resultContainer.innerHTML = `
                    <div class="result-box text-center">
                        <strong>Berhasil Dipindai!</strong>
                        <div class="mt-2 text-start small">
                            Nama: ${nama}<br>
                            Email: ${email}<br>
                            No HP: ${noHp}
                        </div>
                    </div>
                `;
                
                // 3. Sembunyikan instruksi dan tampilkan tombol "Pindai Lagi"
                instructionText.style.display = 'none';
                scanAgainBtn.style.display = 'block';
            }

            function onScanFailure(error) {
                // Fail silently. The scanner will continue trying.
            }

            function startScanner() {
                // Pastikan untuk mereset elemen sebelum merender scanner
                resultContainer.innerHTML = '';
                scanAgainBtn.style.display = 'none';
                instructionText.style.display = 'block';

                html5QrcodeScanner.render(onScanSuccess, onScanFailure);
                qrReaderElement.style.display = 'block';
            }

            scanAgainBtn.addEventListener('click', startScanner);

            // Start scanner on page load
            startScanner();
        });
    </script>
</body>
</html>