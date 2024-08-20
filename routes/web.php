<?php

use App\Http\Controllers\SrsRequestController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;



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

Route::get('/', function () {
    return view('welcome');
});

Route::group(['middleware' => 'maintenance'], function () {
});

Auth::routes();

Route::get('/sticker/new', [SrsRequestController::class, 'create']);

Route::get('/sticker/request/status', function () {
    return view('srs.request.status');
})->name('request.status');

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
