<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\QrCodeController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/qr-scanner', [QrCodeController::class, 'index'])->name('qr.scanner');
Route::get('/qr-generator', [QrCodeController::class, 'generateForm'])->name('qr.generator.form');
Route::post('/qr-generator', [QrCodeController::class, 'generate'])->name('qr.generate');