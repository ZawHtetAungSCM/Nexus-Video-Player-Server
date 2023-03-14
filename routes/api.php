<?php

use App\Http\Controllers\API\FileController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/', function () {
    return "OK";
});
Route::get('/videos', [FileController::class, 'index']);
Route::get('/video/{id}', [FileController::class, 'downloadFile']);
// Route::post('/file/upload', [FileController::class, 'store']);

Route::post('/video/encrypt', [FileController::class, 'encryptUploadFile']);
Route::get('/video/encrypt/{filename}', [FileController::class, 'downloadEncryptedFile']);

Route::post('/video/decrypt', [FileController::class, 'decryptUploadFile']);
Route::get('/video/decrypt/{filename}', [FileController::class, 'downloadDecryptedFile']);

