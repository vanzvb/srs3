<?php

use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\srs3\SrsRequestRenewalController as Srs3SrsRequestRenewalController;
use App\Http\Controllers\srs3\SrsRequestController as Srs3RequestController;
use App\Http\Controllers\SrsRequestController;
use App\Http\Controllers\SrsRequestRenewal3Controller;
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


Route::get('/sticker/renewal', [SrsRequestRenewalController::class, 'index']);
// when submit is hit on renewal
Route::post('/sticker/request/renewal', [SrsRequestRenewalController::class, 'renewalCheck']);

// route for email link generation
Route::get('/sr-renewal', [SrsRequestRenewalController::class, 'userRenewal'])->name('request.user-renewal');

Route::prefix('v3')->group(function () {
    // index for renewal
    Route::get('/sticker/renewal', [Srs3SrsRequestRenewalController::class, 'index']);
    // when submit is hit on renewal
    Route::post('/sticker/request/renewal', [Srs3SrsRequestRenewalController::class, 'renewalCheck']);

    Route::get('/sr-renewal', [Srs3SrsRequestRenewalController::class, 'userRenewal'])->name('request.v3.user-renewal');

    // index for new sticker application
    Route::get('/sticker/new', [Srs3RequestController::class, 'create']);

    // SRS Inbox
    Route::get('/requests', [Srs3RequestController::class, 'list'])->name('requests.v3');
    // Route::get('/requests/report', [Srs3RequestController::class, 'report'])->name('requests.report');
    // Route::post('/requests/approve', [Srs3RequestController::class, 'approve'])->name('requests.approve');
    // Route::delete('/request/{srsRequest}', [Srs3RequestController::class, 'adminDestroy'])->name('request.delete');

    // yajra get tables for inbox
    Route::get('/srs/i/requests/', [Srs3RequestController::class, 'getRequests'])->name('getRequests.v3');
    Route::post('/srs/i/requests/', [Srs3RequestController::class, 'getRequest'])->name('getRequest.v3');
    Route::get('/srs/request/{srsRequest}', [Srs3RequestController::class, 'show'])->name('srsRequest.v3.show');
});

// index for new
Route::get('/sticker/new', [SrsRequestController::class, 'create']);
// Submit new application
Route::post('/sticker/request', [SrsRequestController::class, 'store'])->name('request.store');

// when submit is hit on renewal 3.0
// Route::post('/sticker/request/renewalv3', [Srs3SrsRequestRenewalController::class, 'renewalCheck']);

// for generating sub category onchange (in new)
Route::get('/sticker/request/requirements', [Srs3RequestController::class, 'getRequirements'])->name('getRequirements');
// for generating sub category onload (in new)
Route::get('/sticker/request/sub_categories', [SrsRequestController::class, 'getSubCategories'])->name('getSubCategories');
// for generating hoa onload (in new)
Route::get('/sticker/request/hoas', [SrsRequestController::class, 'getHoas'])->name('getHoas');
// for ? 
Route::get('/sticker/request/hoas/nr', [SrsRequestController::class, 'getNRHoas']);

Route::get('sticker/request/hoa_approval', [SrsRequestController::class, 'hoaApproval'])->name('request.hoa.approval');

// Route::get('/sr-renewal', [SrsRequestRenewalController::class, 'userRenewal'])->name('request.user-renewal');

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

// SRS Inbox
Route::get('/requests', [SrsRequestController::class, 'list'])->name('requests');
Route::get('/requests/report', [SrsRequestController::class, 'report'])->name('requests.report');
Route::post('/requests/approve', [SrsRequestController::class, 'approve'])->name('requests.approve');
Route::delete('/request/{srsRequest}', [SrsRequestController::class, 'adminDestroy'])->name('request.delete');

// yajra get tables for inbox
Route::get('/srs/i/requests/', [SrsRequestController::class, 'getRequests'])->name('getRequests');
Route::post('/srs/i/requests/', [SrsRequestController::class, 'getRequest'])->name('getRequest');
Route::get('/srs/request/{srsRequest}', [SrsRequestController::class, 'show']);
// Route::get('/srs/i/appointments/', [SrsAppointmentController::class, 'getAppointments'])->name('getAppointments');

Route::post('/srs/request/info', [SrsRequestController::class, 'updateInfo'])->name('request.edit_info');
Route::post('/srs/account', [SrsRequestController::class, 'storeCrm'])->name('crm.store');
Route::post('/srs/account/search', [SrsRequestController::class, 'searchCRM'])->name('srs.search_account');

Route::post('/srs/request/cid', [SrsRequestController::class, 'updateCid'])->name('request.edit_accID');

Route::post('/srs/request/payment', [SrsRequestController::class, 'closeRequest'])->name('request.close');

// Route::get('/appointments', [SrsAppointmentController::class, 'index'])->name('appointments');
// Route::post('/appointments/reset', [SrsAppointmentController::class, 'reset'])->name('appointment.reset');
// Route::post('/appointments/resend', [SrsAppointmentController::class, 'resend'])->name('appointment.resend');

Route::group(['middleware' => ['auth', 'isOnline']], function() {
    // Route::post('/admin/logout', [SrsUserController::class, 'logout'])->name('logout');

    // Approvers
    // Route::prefix('/hoa-approvers')->group(function() {
    //     Route::get('/', [HoaApproverController::class, 'index'])->name('hoa-approvers.index');
    //     Route::get('/list', [HoaApproverController::class, 'list'])->name('hoa-approvers.list');
    //     Route::get('/{request_id}/{type?}/{year?}', [HoaApproverController::class, 'show'])->name('hoa-approvers.show');
    //     Route::get('/srs/uploads/{id}/{date}/{name}/{hoa}/{category}', [HoaApproverController::class, 'showFile']);

    //     Route::post('sticker/request/hoa_approval', [HoaApproverController::class, 'hoaApproved'])->name('hoa-approvers.approval');

    //     Route::delete('srs/request/{srsRequest}', [HoaApproverController::class, 'hoaReject'])->name('hoa-approvers.reject');
    // });
});