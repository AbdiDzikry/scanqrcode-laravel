<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use chillerlan\QRCode\QRCode;
use chillerlan\QRCode\QROptions;

class QrCodeController extends Controller
{
    public function index()
    {
        return view('qr_code_scanner');
    }

    public function generateForm()
    {
        return view('qr_code_generator');
    }

    public function generate(Request $request)
    {
        $request->validate([
            'data' => 'required|string',
        ]);

        $options = new QROptions([
            'version'    => 5,
            'outputType' => QRCode::OUTPUT_IMAGE_PNG,
            'eccLevel'   => QRCode::ECC_L,
            'scale'      => 8,
            'imageBase64' => true,
        ]);

        $qrcode = (new QRCode($options))->render($request->input('data'));

        return view('qr_code_generator', ['qrcode' => $qrcode]);
    }
}