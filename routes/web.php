<?php

use App\Http\Controllers\SrsRequestController;
use App\Http\Controllers\SrsRequestRenewalController;
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

// index for new
Route::get('/sticker/new', [SrsRequestController::class, 'create']);

// index for renewal
Route::get('/sticker/renewal', [SrsRequestRenewalController::class, 'index']);
// when submit is hit on renewal
Route::post('/sticker/request/renewal', [SrsRequestRenewalController::class, 'renewalCheck']);

// for generating sub category onchange (in new)
Route::get('/sticker/request/requirements', [SrsRequestController::class, 'getRequirements'])->name('getRequirements');
// for generating sub category onload (in new)
Route::get('/sticker/request/sub_categories', [SrsRequestController::class, 'getSubCategories'])->name('getSubCategories');
// for generating hoa onload (in new)
Route::get('/sticker/request/hoas', [SrsRequestController::class, 'getHoas'])->name('getHoas');
// for ? 
Route::get('/sticker/request/hoas/nr', [SrsRequestController::class, 'getNRHoas']);

Route::get('/sr-renewal', [SrsRequestRenewalController::class, 'userRenewal'])->name('request.user-renewal');
Route::post('/sr-renewal', [SrsRequestRenewalController::class, 'processRenewal'])->name('request.user-renewal.process');

Route::get('/sticker/request/status', function () {
    return view('srs.request.status');
})->name('request.status');

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
