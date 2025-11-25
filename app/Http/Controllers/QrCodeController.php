<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use chillerlan\QRCode\QRCode;
use chillerlan\QRCode\QROptions;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use App\Mail\QrCodeReminderMail; 

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
            'data'    => 'required|string',
            'email'   => 'required|email',
            'phone'   => 'nullable|string',
            'message' => 'nullable|string',
        ]);

        $options = new QROptions([
            'version'     => 5,
            'outputType'  => QRCode::OUTPUT_IMAGE_PNG,
            'eccLevel'    => QRCode::ECC_L,
            'scale'       => 8,
            'imageBase64' => true,
        ]);

        $qrcode = (new QRCode($options))->render($request->input('data'));

        $emailData = [
            'data_qrcode'   => $request->input('data'),
            'message'       => $request->input('message') ?? 'Tidak ada pesan reminder yang disertakan.',
            'qrcode_base64' => $qrcode, 
            'phone_number'  => $request->input('phone'),
        ];
        
        try {
            Mail::to($request->input('email'))->send(new QrCodeReminderMail($emailData));
        } catch (\Exception $e) {
            Log::error('Failed to send QR code email: ' . $e->getMessage());

            return view('qr_code_generator', [
                'qrcode' => $qrcode,
                'email_sent' => false,
                'mail_error' => $e->getMessage(),
            ]);
        }

        if ($request->filled('phone')) {

        }
        
        return view('qr_code_generator', [
            'qrcode' => $qrcode, 
            'email_sent' => true
        ]);
    }
}