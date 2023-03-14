<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FileController;
use App\Http\Controllers\PageController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Auth::routes();
// Page
Route::get('/', [PageController::class, 'home'])->name('home');
Route::get('/videos', [PageController::class, 'feed'])->name('feed');
Route::get('/convert', [PageController::class, 'convert'])->name('convert');
Route::get('/video/add', [PageController::class, 'create'])->name('addVideo');

// Create
Route::post('/video/upload', [FileController::class, 'uploadVideo'])->name('uploadVideo');
Route::post('/video', [FileController::class, 'store'])->name('uploadFile');

// Download
Route::get('/video/{id}', [FileController::class, 'downloadFile'])->name('downloadFile');

// Encrypt/Decrypt
Route::post('/video/encrypt', [FileController::class, 'encryptUploadFile'])->name('encryptFile');
Route::post('/video/decrypt', [FileController::class, 'decryptUploadFile'])->name('decryptFile');
