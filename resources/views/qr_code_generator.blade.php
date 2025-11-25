<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>QR Code Generator</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
</head>
<body class="bg-light">
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card shadow-sm">
                    <div class="card-header text-center bg-primary text-white">
                        <h3>QR Code Generator</h3>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('qr.generate') }}" method="POST">
                            @csrf
                            <div class="mb-3">
                                <label for="data" class="form-label">Data for QR Code</label>
                                <input type="text" class="form-control" id="data" name="data" required>
                            </div>
                            <div class="d-grid">
                                <button type="submit" class="btn btn-primary">Generate QR Code</button>
                            </div>
                        </form>

                        @if(isset($qrcode))
                            <div class="mt-4 text-center">
                                <h5>Generated QR Code:</h5>
                                <img src="{{ $qrcode }}" alt="Generated QR Code" class="img-fluid">
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
</body>
</html>