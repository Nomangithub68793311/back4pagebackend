<?php
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\GetAllData;

Route::get('/sms/today/month/total/{id}', [GetAllData::class,'all_data'])->middleware('jwt.smsadminmiddleware');
