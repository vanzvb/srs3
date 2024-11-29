<?php

use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\CRMXI3_Controller\CRMXIController;
use App\Http\Controllers\CRMXI3_Controller\CRMXIRedTagController;
use App\Http\Controllers\CRMXI3_Controller\CRXMIVehicleController;
use App\Http\Controllers\CRMXI3_Controller\SPCV3Controller;
use App\Http\Controllers\CRMXI3_Controller\SRS3HoaGroupController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\srs3\SrsAppointmentController as Srs3AppointmentController;
use App\Http\Controllers\srs3\SrsRequestController as Srs3RequestController;
use App\Http\Controllers\srs3\SrsRequestRenewalController as Srs3SrsRequestRenewalController;
use App\Http\Controllers\srs3\StickerController as Srs3StickerController;
use App\Http\Controllers\srs3\SubCategoryController as Srs3SubCategoryController;
use App\Http\Controllers\SRS_3\HoaApproverController as HoaApprover3Controller;
use App\Http\Controllers\srs3\SrsApptTimeSlotController as Srs3ApptTimeSlotController;
use App\Http\Controllers\SrsAppointmentController;
use App\Http\Controllers\SrsApptTimeSlotController;
use App\Http\Controllers\SrsRequestController;
use App\Http\Controllers\SrsRequestRenewal3Controller;
use App\Http\Controllers\SrsRequestRenewalController;
use App\Http\Controllers\SrsUserController;
use App\Http\Controllers\SubCategoryController;
use App\Http\Controllers\TransmittalController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SRS_3\HoaApproverController;














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
    // Test route
    Route::get('/vanz', [Srs3SrsRequestRenewalController::class, 'vanz']);

    // index for renewal
    Route::get('/sticker/renewal', [Srs3SrsRequestRenewalController::class, 'index']);
    // when submit is hit on renewal (this is to make a new renewal request)
    Route::post('/sticker/request/renewal', [Srs3SrsRequestRenewalController::class, 'renewalCheck']);

    // For Generating an Email Link sent to renewal requestor (index for renewal link)
    Route::get('/sr-renewal', [Srs3SrsRequestRenewalController::class, 'userRenewal'])->name('request.v3.user-renewal');

    // when "Submit Renewal" is clicked (this is the email form)
    Route::post('/sr-renewal', [Srs3SrsRequestRenewalController::class, 'processRenewal'])->name('request.v3.user-renewal.process');

    // index for new sticker application
    Route::get('/sticker/new', [Srs3RequestController::class, 'create']);
    // Submit new application
    Route::post('/sticker/request', [Srs3RequestController::class, 'store'])->name('request.v3.store');

    //Loads on change of sub cat in "New" Application
    Route::get('/sticker/request/requirements', [Srs3RequestController::class, 'getRequirements'])->name('getRequirements.v3');

    // for generating sub category onload (in new)
    Route::get('/sticker/request/sub_categories', [Srs3RequestController::class, 'getSubCategoriesV3'])->name('getSubCategoriesV3');

    Route::get('/sticker/request/hoa_types', [Srs3RequestController::class, 'getHoaTypes'])->name('getHoaTypes');

    // SRS Inbox
    Route::get('/requests', [Srs3RequestController::class, 'list'])->name('requests.v3');
    // Route::get('/requests/report', [Srs3RequestController::class, 'report'])->name('requests.report');
    Route::post('/requests/approve', [Srs3RequestController::class, 'approve'])->name('requests.approve.v3');
    Route::delete('/request/{srsRequest}', [Srs3RequestController::class, 'adminDestroy'])->name('request.delete.v3');

    // yajra get tables for inbox
    Route::get('/srs/i/requests/', [Srs3RequestController::class, 'getRequests'])->name('getRequests.v3');
    Route::post('/srs/i/requests/', [Srs3RequestController::class, 'getRequest'])->name('getRequest.v3');
    Route::get('/srs/request/{srsRequest}', [Srs3RequestController::class, 'show'])->name('srsRequest.v3.show');

    Route::prefix('sub-categories')->group(function () {
        Route::get('/', [Srs3SubCategoryController::class, 'index'])->name('v3.sub-categories.index');
        Route::post('/create', [Srs3SubCategoryController::class, 'store'])->name('v3.sub-categories.store');
        Route::get('/list', [Srs3SubCategoryController::class, 'list'])->name('v3.sub-categories.list');
        Route::get('/{subCategory}', [Srs3SubCategoryController::class, 'show'])->name('v3.sub-categories.show');
        Route::put('/{subCategory}', [Srs3SubCategoryController::class, 'edit'])->name('v3.sub-categories.edit');
        Route::delete('/{subCategory}', [Srs3SubCategoryController::class, 'destroy'])->name('v3.sub-categories.destroy');
    });

    // Transmittal Report

    Route::post('/sticker_export_excel_2', [Srs3StickerController::class, 'sticker_export_excel_2']);

    // Sticker Status
    Route::post('/sticker/request/status', [Srs3RequestController::class, 'checkStatus'])->name('request.v3.checkStatus');

    Route::get('/sticker/request/status', function () {
        return view('srs3.request.status');
    })->name('request.v3.status');


    // For Inbox (link Account)
    // Route::post('/srs/request/info', [SrsRequestController::class, 'updateInfo'])->name('request.edit_info');
    // Create new Account
    Route::post('/srs/account', [Srs3RequestController::class, 'storeCrm'])->name('crm.v3.store');

    // Search Account
    Route::post('/srs/account/search', [Srs3RequestController::class, 'searchCRM'])->name('srs.v3.search_account');

    // For Inbox (link account button)
    Route::post('/srs/request/cid', [Srs3RequestController::class, 'updateCid'])->name('request.v3.edit_accID');

    // Hoa Approval (Email Link)
    Route::get('sticker/request/hoa_approval', [Srs3RequestController::class, 'hoaApproval'])->name('request.v3.hoa.approval');
    Route::post('sticker/request/hoa_approval', [Srs3RequestController::class, 'hoaApproved'])->name('requests.v3.hoa.approved');
    Route::delete('srs/request/{srsRequest}', [Srs3RequestController::class, 'destroy'])->name('request.v3.destroy');

    // Appointment

    // When approved via Hoa Pres Email (will already send an email appointment to the requestor)
    // Route::get('/sticker_appointment', [Srs3AppointmentController::class, 'create'])->name('request.v3.appointment');

    // When Submit is hit in the Sticker Appointment Form
    // Route::post('/sticker_appointment', [Srs3AppointmentController::class, 'store'])->name('appointment.v3.store');

    // For generating available time slots in the appointment form (srs3.appointment.create)
    // Route::get('/sticker/appt/appt_timeslots', [Srs3ApptTimeSlotController::class, 'getAvailable'])->name('getAvailableTimeSlotsV3');

});

 Route::get('/srs/uploads/{id}/{date}/{name}/{hoa}/{category}', [SrsRequestController::class, 'showFile']);


Route::get('/sticker/request/status', function () {
    return view('srs.request.status');
})->name('request.status');

Route::post('/sticker/request/status', [SrsRequestController::class, 'checkStatus']);

// CRMXI ROUTES

// CRMXI Index
Route::get('crmxi', [CRMXIController::class, 'index']);

// CRMXI Load Table Data
Route::get('crmxi/crms', [CRMXIController::class, 'getCRMs'])->name('getcrmxi');
Route::get('crmxi_getSubCat/{id}', [CRMXIController::class, 'getSubCategories']);
Route::get('crmxi_getHoas/{id}', [CRMXIController::class, 'getHoaTypes']);
Route::get('crmxi_getVehicleOwnershipStatus/{id}', [CRMXIController::class, 'getVehicleOwnershipStatus']);
Route::get('crmxi_getZipcode/{id}', [CRMXIController::class, 'getZipcode']);
Route::post('insert_crm_account', [CRMXIController::class, 'insertAccount']);
Route::get('crmxi/crms_view_account/{account_id}', [CRMXIController::class, 'view_account'])->name('crms_view_account');
Route::get('crmxi/vehicles/{account_id}', [CRXMIVehicleController::class, 'vehicleList'])->name('getVehicles');
Route::post('insert_vehicle', [CRXMIVehicleController::class, 'insertVehicle']);
Route::get('check_plate_no/{plate_no}', [CRXMIVehicleController::class, 'checkExistingPlateNo']);
// Route::post('insert_vehicle', [CRXMIVehicleController::class, 'testInsert']);
Route::post('insert_crm_account_address', [CRMXIController::class, 'insertAddress']);

// CRMXi - JY 11/19/24
Route::delete('crmxi/delete-vehicle', [CRXMIVehicleController::class, 'deleteVehicle'])
    ->name('crms.delete-vehicle');
Route::delete('crmxi/delete-address', [CRXMIVehicleController::class, 'deleteAddress'])
    ->name('crms.delete-address');
// End CRMXi - JY 11/19/24

Route::get('/spc-V3', [SPCV3Controller::class, 'index'])->name('spc-V3');
Route::post('/spc-insert', [SPCV3Controller::class, 'spc_insert']);
Route::get('/get-price-spc/{id}', [SPCV3Controller::class, 'spc_show']);

Route::get('/spc3-hoa-group', [SRS3HoaGroupController::class, 'index'])->name('spc3-hoa-group');
Route::post('/hoa-group-insert', [SRS3HoaGroupController::class, 'hoa_group_insert']);

Route::get('/crmxi_redtag_master', [CRMXIRedTagController::class, 'index'])->name('crmxi-redtag-master');
Route::post('/insert_redtag_item', [CRMXIRedTagController::class, 'insert_redtag_item']);
Route::get('/delete_redtag_item', [CRMXIRedTagController::class, 'delete_redtag_item'])->name('delete-redtag-item');
Route::post('/insert_redtag', [CRMXIRedTagController::class, 'insert_redtag']);
Route::get('/remove_redtag', [CRMXIRedTagController::class, 'remove_redtag'])->name('remove-redtag');

Route::get('/crmMigration', [MigrationController::class, 'crm_migration']);


// CRMXI END ROUTES


// BILLING

Route::get('/view-spc/{crm_id}/{customer_id}', [SCPV3Controller::class, 'view_details']);
Route::post('/loadComputation', [SCPV3Controller::class, 'loadComputation']);
Route::post('/loadVehicle', [SCPV3Controller::class, 'loadVehicles']);
Route::post('/fetchVehicleDetails', [SCPV3Controller::class, 'fetchVehicleDetails']);
Route::post('/scp/invoice-process', [SCPV3Controller::class, 'invoice_process'])->name('scp_invoice3');
Route::get('/spc/invoice/{crm_id}/{invoice_no}', [SCPV3Controller::class, 'view_edit_invoice'])
    ->name('scp_invoice3.edit');
Route::post('/edit-billing', [SCPV3Controller::class, 'edit_billing'])->name('edit_billing.v3');

Route::post('/spc/cancelOr', [SCPV3Controller::class, 'cancelOr'])->name('spccancelOr');

Route::post('/spc/cancel_or_display', [SCPV3Controller::class, 'get_or']);

// PARENT TO CHILD v3 - For Patch 11/15/24
Route::get('/crm_p2c', [Crmxi2pcController::class, 'index'])->name('crmxi3_p2c');
Route::get('/crm_p2c/list', [Crmxi2pcController::class, 'list'])->name('crmxi3_p2c.list');
Route::get('/crm_p2c/{crm_id}', [Crmxi2pcController::class, 'edit'])->name('crmxi3_p2c.edit');
Route::post('/crm_p2c/set_parent/{crm_id}', [Crmxi2pcController::class, 'set_parent'])->name('crmxi3_p2c.set_parent');
Route::post('/crm_p2c/children_list', [Crmxi2pcController::class, 'children_list'])->name('crmxi3_p2c.children_list');
Route::post('/crm_p2c/set_child', [Crmxi2pcController::class, 'set_child'])->name('crmxi3_p2c.set_child');
Route::post('/crm_p2c/delete_child', [Crmxi2pcController::class, 'delete_child'])->name('crmxi3_p2c.delete_child');
Route::post('/crm_p2c/get_plate_numbers', [Crmxi2pcController::class, 'get_plate_numbers'])->name('crmxi3_p2c.get_plate_numbers');
// END PARENT TO CHILD

// For Patch 11/18/24
Route::post('/search/to-merge', [MergeCRMXiAccountController::class, 'searchMergeAccount'])
    ->name('search.merge.account');
Route::post('/merge-accounts', [MergeCRMXiAccountController::class, 'mergeAccounts'])
    ->name('merge.accounts');

// RED TAG

Route::post('/deleteTag', [CRMController::class, 'deleteTag'])->name('deleteTag');
Route::get('/edit-red-tags/{id}', [CRMController::class, 'editRedTag']);
Route::post('/update-red-tag', [CRMController::class, 'update_red_tag'])->name('update-red-tag');


// END BILLING

Route::prefix('sub-categories')->group(function () {
    Route::get('/', [SubCategoryController::class, 'index'])->name('sub-categories.index');
    Route::post('/create', [SubCategoryController::class, 'store'])->name('sub-categories.store');
    Route::get('/list', [SubCategoryController::class, 'list'])->name('sub-categories.list');
    Route::get('/{subCategory}', [SubCategoryController::class, 'show'])->name('sub-categories.show');
    Route::put('/{subCategory}', [SubCategoryController::class, 'edit'])->name('sub-categories.edit');
    Route::delete('/{subCategory}', [SubCategoryController::class, 'destroy'])->name('sub-categories.destroy');
});



// index for new
Route::get('/sticker/new', [SrsRequestController::class, 'create']);
// Submit new application
Route::post('/sticker/request', [SrsRequestController::class, 'store'])->name('request.store');

// when submit is hit on renewal 3.0
// Route::post('/sticker/request/renewalv3', [Srs3SrsRequestRenewalController::class, 'renewalCheck']);

// for generating sub category onchange (in new)
Route::get('/sticker/request/requirements', [SrsRequestController::class, 'getRequirements'])->name('getRequirements');
// for generating sub category onload (in new)
Route::get('/sticker/request/sub_categories', [SrsRequestController::class, 'getSubCategories'])->name('getSubCategories');
// for generating hoa onload (in new)
Route::get('/sticker/request/hoas', [SrsRequestController::class, 'getHoas'])->name('getHoas');
// for ? 
Route::get('/sticker/request/hoas/nr', [SrsRequestController::class, 'getNRHoas']);

// for HOA (after sender sent a new request)
Route::get('sticker/request/hoa_approval', [SrsRequestController::class, 'hoaApproval'])->name('request.hoa.approval');
Route::post('sticker/request/hoa_approval', [SrsRequestController::class, 'hoaApproved'])->name('requests.hoa.approved');
Route::delete('srs/request/{srsRequest}', [SrsRequestController::class, 'destroy'])->name('request.destroy');

// For Inbox (link Account)
Route::post('/srs/request/info', [SrsRequestController::class, 'updateInfo'])->name('request.edit_info');
Route::post('/srs/account', [SrsRequestController::class, 'storeCrm'])->name('crm.store');
Route::post('/srs/account/search', [SrsRequestController::class, 'searchCRM'])->name('srs.search_account');

// For Inbox (link account button)
Route::post('/srs/request/cid', [SrsRequestController::class, 'updateCid'])->name('request.edit_accID');

// Route::get('/sr-renewal', [SrsRequestRenewalController::class, 'userRenewal'])->name('request.user-renewal');

// when "Submit Renewal" is clicked
Route::post('/sr-renewal', [SrsRequestRenewalController::class, 'processRenewal'])->name('request.user-renewal.process');

Route::post('/save-form', [SrsRequestRenewalController::class, 'saveProgress'])->name('saveProgress');



// Route::group(['middleware' => 'guest'], function () {
//     Route::get('/admin-login', function () {
//         return view('auth.login2');
//     })->name('login');
//     Route::post('/admin-login', [SrsUserController::class, 'authenticate']);


    // Route::get('/hoa/login', function () {
    //     return view('auth.login2');
    // })->name('login.hoa');
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

Route::get('/hoa/login', function () {
    return view('auth.login2');
})->name('login.hoa'); // This name is already unique, so it's fine


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

// Route::post('/srs/request/info', [SrsRequestController::class, 'updateInfo'])->name('request.edit_info');
// Route::post('/srs/account', [SrsRequestController::class, 'storeCrm'])->name('crm.store');
// Route::post('/srs/account/search', [SrsRequestController::class, 'searchCRM'])->name('srs.search_account');

// Route::post('/srs/request/cid', [SrsRequestController::class, 'updateCid'])->name('request.edit_accID');

Route::post('/srs/request/payment', [SrsRequestController::class, 'closeRequest'])->name('request.close');


// Route::post('/appointments/reset', [SrsAppointmentController::class, 'reset'])->name('appointment.reset');
// Route::post('/appointments/resend', [SrsAppointmentController::class, 'resend'])->name('appointment.resend');



Route::group(['middleware' => ['auth', 'isOnline']], function () {
    Route::post('/admin/logout', [SrsUserController::class, 'logout'])->name('logout');

    // Approvers
    Route::prefix('/hoa-approvers')->group(function() {
    //     Route::get('/', [HoaApproverController::class, 'index'])->name('hoa-approvers.index');
    //     Route::get('/list', [HoaApproverController::class, 'list'])->name('hoa-approvers.list');
    //     Route::get('/{request_id}/{type?}/{year?}', [HoaApproverController::class, 'show'])->name('hoa-approvers.show');
        Route::get('/srs/uploads/{id}/{date}/{name}/{hoa}/{category}', [HoaApproverController::class, 'showFile']);

    //     Route::post('sticker/request/hoa_approval', [HoaApproverController::class, 'hoaApproved'])->name('hoa-approvers.approval');

    //     Route::delete('srs/request/{srsRequest}', [HoaApproverController::class, 'hoaReject'])->name('hoa-approvers.reject');
    });



});
    // HOA Presidents/Approvers 3.0
    Route::prefix('/hoa-approvers3')->group(function () {
        Route::get('/transmittal', [HoaApprover3Controller::class, 'transmittal'])->name('hoa-approvers3.transmittal');
        Route::get('/export-transmittal', [HoaApprover3Controller::class, 'exportTransmittal'])->name('export.transmittal');

        Route::get('/', [HoaApprover3Controller::class, 'index'])->name('hoa-approvers3.index');
        Route::get('/list', [HoaApprover3Controller::class, 'list'])->name('hoa-approvers3.list');
        Route::get('/{request_id}/{type?}/{year?}', [HoaApprover3Controller::class, 'show'])->name('hoa-approvers3.show');
        Route::get('/srs/uploads/{id}/{date}/{name}/{hoa}/{category}', [HoaApprover3Controller::class, 'showFile']);

        Route::post('sticker/request/hoa_approval', [HoaApprover3Controller::class, 'hoaApproved'])->name('hoa-approvers3.approval');

        Route::delete('srs/request/{srsRequest}', [HoaApprover3Controller::class, 'hoaReject'])->name('hoa-approvers3.reject');

    });

    //Invoice
    Route::post('/cancelOr', [InvoiceController::class, 'cancelOr'])->name('cancelOr');
    Route::post('/cancel_or_display', [InvoiceController::class, 'get_or'])->name('get_or');
    Route::post('/invoice-process', [InvoiceController::class, 'invoice_process'])->name('invoice');
    Route::post('/edit-billing', [InvoiceController::class, 'edit_billing'])->name('edit_billing');
    Route::get('/invoice/{crm_id}/{invoice_no}', [InvoiceController::class, 'index']);
    Route::get('/invoice_with_vat/{crm_id}/{invoice_no}', [InvoiceController::class, 'with_vat_index']);
    Route::get('/invoice_vat/{crm_id}/{invoice_no}', [InvoiceController::class, 'vat']);
    // Route::post('/crm/export', [InvoiceController::class, 'crm_export']);
    Route::post('/sticker_report', [InvoiceController::class, 'sticker_report']);
    Route::post('/sticker_report_cashier', [InvoiceController::class, 'sticker_report_cashier']);
    Route::post('/invoice_export', [InvoiceController::class, 'invoice_export']);
    Route::get('/invoice_access_report', [InvoiceController::class, 'filter_export_invoice']);
    Route::get('/crm_access_report', [InvoiceController::class, 'crm_export']);

    Route::get('/invoice_report_filter', [InvoiceController::class, 'invoice_report_export']);

 
    // When approved via Hoa Pres Email (will already send an email appointment to the requestor)
    Route::get('/sticker_appointment', [SrsAppointmentController::class, 'create'])->name('request.appointment');

     // For generating available time slots in the appointment form (srs3.appointment.create)
    Route::post('/sticker_appointment', [SrsAppointmentController::class, 'store'])->name('appointment.store');


    Route::get('/srs/appointments', [SrsAppointmentController::class, 'list']);

    Route::get('/appointments', [SrsAppointmentController::class, 'index'])->name('appointments');

    // Route::get('/sticker_appointment', [SrsAppointmentController::class, 'create'])->name('request.appointment');

    // Route::post('/sticker_appointment', [SrsAppointmentController::class, 'store'])->name('appointment.store');

    Route::get('/sticker/appt/appt_timeslots', [SrsApptTimeSlotController::class, 'getAvailable'])->name('getAvailableTimeSlots');

    // Route::post('/sticker_export_excel', [Srs3StickerController::class, 'sticker_export_excel']);

    // DO THIS
    // Route::post('/sticker_export_excel_2', [Srs3StickerController::class, 'sticker_export_excel_2']);
    // Route::post('/sticker_export_excel_2', [StickerController::class, 'sticker_export_pdf']);
    // Route::get('/sticker_access_report_cv', [Srs3StickerController::class, 'export_sticker_cv']);
    //End Invoice