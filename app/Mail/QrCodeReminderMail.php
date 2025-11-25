<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class QrCodeReminderMail extends Mailable
{
    use Queueable, SerializesModels;

    public string $data_qrcode;
    public string $message_content;
    
    public string $rawQrcodeData = ''; 

    public function __construct(array $emailData)
    {
        $this->data_qrcode = $emailData['data_qrcode'];
        $this->message_content = $emailData['message'] ?? 'Tidak ada pesan reminder yang disertakan.';
        $base64StringWithPrefix = $emailData['qrcode_base64'] ?? '';

        if (!empty($base64StringWithPrefix)) {
            $base64String = preg_replace('#^data:image/\w+;base64,#i', '', $base64StringWithPrefix);

            $this->rawQrcodeData = base64_decode($base64String);
        }
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            from: new Address(
                config('mail.from.address', 'no-reply@qrcodeapp.com'),
                config('mail.from.name', 'QR Code Sender')
            ),
            subject: 'QR Code Reminder '
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.qr-reminder', 
        );
    }
}