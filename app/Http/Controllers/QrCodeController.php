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
            'data'    => 'required|string', // Ini adalah NAMA
            'email'   => 'required|email',
            'phone'   => 'nullable|string',
            'message' => 'nullable|string',
        ]);

        // 1. GABUNGKAN SEMUA DATA PENTING MENJADI SATU STRING
        // Format: Key: Value\nKey2: Value2, yang akan di-parsing di JS.
        $combinedQrData = "Nama: " . $request->input('data') . "\n";
        $combinedQrData .= "Email: " . $request->input('email');
        
        // Tambahkan No HP hanya jika ada (menggunakan \n di awal agar selalu di baris baru)
        if ($request->filled('phone')) {
            $combinedQrData .= "\nNo HP: " . $request->input('phone');
        }

        // 2. Konfigurasi dan Generate QR Code (Base64)
        $options = new QROptions([
            'version'     => 5,
            'outputType'  => QRCode::OUTPUT_IMAGE_PNG,
            'eccLevel'    => QRCode::ECC_L,
            'scale'       => 8,
            'imageBase64' => true,
        ]);

        // MENGGUNAKAN $combinedQrData (Data Gabungan)
        $qrcode = (new QRCode($options))->render($combinedQrData); 

        // 3. Logika Pengiriman Email
        $emailData = [
            'data_qrcode'   => $combinedQrData, // Data Gabungan
            'message'       => $request->input('message') ?? 'Tidak ada pesan reminder yang disertakan.',
            'qrcode_base64' => $qrcode, 
            'phone_number'  => $request->input('phone'),
        ];
        
        try {
            // KIRIM EMAIL
            Mail::to($request->input('email'))->send(new QrCodeReminderMail($emailData));

            Log::info('QR Code email successfully sent to ' . $request->input('email'));
            
            // Logika SMS diabaikan untuk saat ini
            if ($request->filled('phone')) {
                // ...
            }

            return view('qr_code_generator', [
                'qrcode' => $qrcode, 
                'email_sent' => true
            ]);

        } catch (\Throwable $e) { 
            
            Log::error('FATAL Email Send Failure from generate(): ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);

            return view('qr_code_generator', [
                'qrcode' => $qrcode,
                'email_sent' => false,
                'mail_error' => 'Gagal kirim email: ' . $e->getMessage(),
            ]);
        }
    }
}