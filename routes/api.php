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
Route::get('/files', [FileController::class, 'index']);
Route::get('/files/{id}', [FileController::class, 'downloadFile']);
Route::post('/file/upload', [FileController::class, 'store']);

Route::post('/file/encrypt', [FileController::class, 'encryptUploadFile']);
Route::get('/file/encrypt/{filename}', [FileController::class, 'downloadEncryptedFile']);

Route::post('/file/decrypt', [FileController::class, 'decryptUploadFile']);
Route::get('/file/decrypt/{filename}', [FileController::class, 'downloadDecryptedFile']);

