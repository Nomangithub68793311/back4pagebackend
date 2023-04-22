
<?php
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PostController;

Route::post('/post/add/free/{id}',[PostController::class,'storeFree'])->middleware('jwt.postmiddleware');
Route::post('/post/add/ad/{id}',[PostController::class,'storeAd'])->middleware('jwt.postmiddleware');
Route::post('/post/add/multiple/{id}',[PostController::class,'storeMultiple'])->middleware('jwt.postmiddleware');
Route::post('/post/add/delete/{id}',[PostController::class,'destroy'])->middleware('jwt.postmiddleware');
Route::post('/post/add/edit/{id}/{idPost}',[PostController::class,'edit'])->middleware('jwt.postmiddleware');
Route::post('/post/multiple/edit/{id}/{idPost}', [PostController::class,'multipleEdit'])->middleware('jwt.postmiddleware');

Route::post('/post/add/renew/{id}', [PostController::class,'renew'])->middleware('jwt.postmiddleware');
Route::get('/post/preview/{id}/{idPost}', [PostController::class,'preview'])->middleware('jwt.postmiddleware');
Route::get('/post/multiple/data/{id}', [PostController::class,'multipleData'])->middleware('jwt.postmiddleware');
Route::get('/post/multiple/preview/{id}/{idPost}', [PostController::class,'multiplePreview'])->middleware('jwt.postmiddleware');
Route::post('/post/multiple/delete/{id}', [PostController::class,'multipleDestroy'])->middleware('jwt.postmiddleware');

Route::post('/post/success/{id}', [PostController::class,'update'])->middleware('jwt.postmiddleware');


Route::get('/posts/get/{id}', [PostController::class,'allData'])->middleware('jwt.postmiddleware');


 
