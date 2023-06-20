<?php
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SingleTextController;
use App\Http\Controllers\ImportTextController;
use App\Http\Controllers\BulkTextController;

Route::post('/sms/send/single/{id}', [SingleTextController::class,'store'])->middleware('jwt.smsmiddleware');
Route::post('/sms/send/import/{id}', [ImportTextController::class,'store'])->middleware('jwt.smsmiddleware');
Route::post('/sms/send/file/{id}', [BulkTextController::class,'store'])->middleware('jwt.smsmiddleware');
Route::post('/sms/send/single/id/{id}', [SingleTextController::class,'single_id']);
Route::post('/sms/send/single/bearer', [SingleTextController::class,'single_Bearer'])->middleware('jwt.singlemiddleware');

