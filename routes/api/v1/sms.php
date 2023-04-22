<?php
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SmsController;

Route::get('/send/sms/number/hello/mister/{number}/{message}', [SmsController::class,'sd_send_sms_api']);
