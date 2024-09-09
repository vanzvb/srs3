<?php

use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\ResetPasswordController;
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

// Auth::routes();

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

Route::get('sticker/request/hoa_approval', [SrsRequestController::class, 'hoaApproval'])->name('request.hoa.approval');

Route::get('/sr-renewal', [SrsRequestRenewalController::class, 'userRenewal'])->name('request.user-renewal');
// when "Submit Renewal" is clicked
Route::post('/sr-renewal', [SrsRequestRenewalController::class, 'processRenewal'])->name('request.user-renewal.process');

Route::post('/save-form', [SrsRequestRenewalController::class, 'saveProgress'])->name('saveProgress');



// Route::group(['middleware' => 'guest'], function () {
//     Route::get('/admin-login', function () {
//         return view('auth.login2');
//     })->name('login');
//     Route::post('/admin-login', [SrsUserController::class, 'authenticate']);


//     Route::get('/hoa/login', function () {
//         return view('auth.login2');
//     })->name('login.hoa');
// });
// Route::post('/admin-login', [LoginController::class, 'login']);
Route::group(['middleware' => 'guest'], function () {
    Route::get('/admin-login', function () {
        return view('auth.login2');
    })->name('admin.login'); // Changed the name to 'admin.login' to avoid conflicts

    // Route::post('/admin-login', [SrsUserController::class, 'authenticate'])->name('admin.authenticate');

    Route::get('/hoa/login', function () {
        return view('auth.login2');
    })->name('login.hoa'); // This name is already unique, so it's fine
});
Route::post('/admin-login', [LoginController::class, 'login']);
Route::get('/sticker/request/status', function () {
    return view('srs.request.status');
})->name('request.status');

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::get('/dashboard', [SrsRequestController::class, 'dashboard'])->name('dashboard');


Route::get('/srs/a/password/reset', [ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
Route::post('/srs/a/password/email', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');
Route::get('/srs/a/password/reset/{token}', [ResetPasswordController::class, 'showResetForm'])->name('password.reset');
Route::post('/srs/a/password/reset', [ResetPasswordController::class, 'reset'])->name('password.update');

Route::get('/requests', [SrsRequestController::class, 'list'])->name('requests');
Route::get('/requests/report', [SrsRequestController::class, 'report'])->name('requests.report');
Route::post('/requests/approve', [SrsRequestController::class, 'approve'])->name('requests.approve');
Route::delete('/request/{srsRequest}', [SrsRequestController::class, 'adminDestroy'])->name('request.delete');

Route::get('/srs/i/requests/', [SrsRequestController::class, 'getRequests'])->name('getRequests');
Route::post('/srs/i/requests/', [SrsRequestController::class, 'getRequest'])->name('getRequest');
// Route::get('/srs/i/appointments/', [SrsAppointmentController::class, 'getAppointments'])->name('getAppointments');

Route::post('/srs/request/info', [SrsRequestController::class, 'updateInfo'])->name('request.edit_info');
Route::post('/srs/account', [SrsRequestController::class, 'storeCrm'])->name('crm.store');
Route::post('/srs/account/search', [SrsRequestController::class, 'searchCRM'])->name('srs.search_account');

Route::post('/srs/request/cid', [SrsRequestController::class, 'updateCid'])->name('request.edit_accID');

Route::post('/srs/request/payment', [SrsRequestController::class, 'closeRequest'])->name('request.close');

// Route::get('/appointments', [SrsAppointmentController::class, 'index'])->name('appointments');
// Route::post('/appointments/reset', [SrsAppointmentController::class, 'reset'])->name('appointment.reset');
// Route::post('/appointments/resend', [SrsAppointmentController::class, 'resend'])->name('appointment.resend');