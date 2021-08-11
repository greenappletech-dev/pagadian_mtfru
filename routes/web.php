<?php

use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;
use \App\Http\Controllers\HomeController;
use \App\Http\Controllers\TricycleController;
use \App\Http\Controllers\OperatorController;
use \App\Http\Controllers\MtopApplicationController;
use \App\Http\Controllers\ChargeController;
use \App\Http\Controllers\SystemParameterController;
use \App\Http\Controllers\MTFRUReportController;
use \App\Http\Controllers\MtopApplicationChargeController;
use App\Http\Controllers\MtopViewRenewalController;
use App\Http\Controllers\DriverController;

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


Auth::routes(['verify'=> true]);

Route::group(['middleware'=> 'auth'], function() {

    Route::group(['middleware' => 'is_role_allowed'], function() {

        /* Home Route */
        Route::get('/',[HomeController::class, 'index']);
        Route::get('/home',[HomeController::class, 'index']);

        /* Log out */
        Route::get('/logout', '\App\Http\Controllers\Auth\LoginController@logout');

        /* Tricycle */
        Route::get('tricycle',[TricycleController::class, 'index']);
        Route::get('tricycle/getdata',[TricycleController::class, 'getdata']);
        Route::get('tricycle/search/{string}',[TricycleController::class, 'search']);
        Route::get('tricycle/edit/{id}',[TricycleController::class, 'edit']);
        Route::get('tricycle/destory/{id}',[TricycleController::class, 'destroy']);
        Route::get('tricycle/print/{id}', [TricycleController::class, 'print']);
        Route::get('tricycle/export', [TricycleController::class, 'export']);
        Route::get('tricycle/pdf/{size}/{orientation}', [TricycleController::class, 'pdf']);
        Route::post('tricycle/store',[TricycleController::class, 'store']);
        Route::patch('tricycle/update/{id}',[TricycleController::class, 'update']);

        /* Operator */
        Route::get('operator', [OperatorController::class, 'index']);
        Route::get('operator/getdata',[OperatorController::class, 'getdata']);
        Route::get('operator/edit/{id}',[OperatorController::class, 'edit']);
        Route::get('operator/destory/{id}',[OperatorController::class, 'destroy']);
        Route::get('operator/search/{string}',[OperatorController::class, 'search']);
        Route::post('operator/store',[OperatorController::class, 'store']);
        Route::patch('operator/update/{id}',[OperatorController::class, 'update']);
        Route::post('operator/upload/{id}', [OperatorController::class, 'upload']);

        /* MTOP */
        Route::get('mtop', [MtopApplicationController::class, 'index']);
        Route::get('mtop/getdata', [MtopApplicationController::class, 'getdata']);
        Route::get('mtop/getdata_filtered/{from}/{to}/{barangay_id}', [MtopApplicationController::class, 'getdata_filtered']);
        Route::get('mtop/pdf_application/{id}/{form_to_print}', [MtopApplicationController::class, 'pdfApplication']);
        Route::get('mtop_create', [MtopApplicationController::class, 'create']);
        Route::get('mtop_edit/{id}', [MtopApplicationController::class, 'edit']);
        Route::get('mtop/pdf/{size}/{orientation}/{from}/{to}/{barangay_id}', [MtopApplicationController::class, 'pdf']);
        Route::get('mtop/destroy/{id}', [MtopApplicationController::class, 'destroy']);
        Route::patch('mtop/approve/{id}/{type}', [MtopApplicationController::class, 'approve']);

        /* MTOP Entry */
        Route::get('mtop_entry/renew/{id}', [MtopApplicationController::class, 'renew']);
        Route::get('mtop_entry', [MtopApplicationController::class, 'create']);
        Route::get('mtop_edit/{id}', [MtopApplicationController::class, 'edit']);
        Route::get('mtop/search/{string}',[MtopApplicationController::class, 'search']);
        Route::get('mtop/search_new_operator/{string}',[MtopApplicationController::class, 'search_new_operator']);
        Route::get('mtop/tricycle/{id}',[MtopApplicationController::class, 'gettricycle']);
        Route::post('mtop/store', [MtopApplicationController::class, 'store']);


        /* MTOP Update Charges */
        Route::get('mtop_update/getdata_update', [MtopApplicationController::class, 'getdata_update']);
        Route::get('mtop_update/delete_charges/{id}', [MtopApplicationChargeController::class, 'destroy']);
        Route::post('mtop_update/add_charges', [MtopApplicationChargeController::class, 'store']);
        Route::patch('mtop_update/update/{id}', [MtopApplicationController::class, 'update']);

        /* MTOP List of Renewals */
        Route::get('mtop_renewal', [MtopViewRenewalController::class, 'index']);
        Route::get('mtop_renewal/getdata', [MtopViewRenewalController::class, 'getdata']);
        Route::get('mtop_renewal/getdata_filtered/{from}/{to}/{barangay_id}', [MtopViewRenewalController::class, 'filter']);
        Route::get('mtop_renewal/export/{from}/{to}/{barangay_id}',[MtopViewRenewalController::class, 'export']);

        /* Charges */
        Route::get('charges', [ChargeController::class, 'index']);
        Route::get('charges/getdata', [ChargeController::class, 'getdata']);
        Route::get('charges/edit/{id}', [ChargeController::class, 'edit']);
        Route::get('charges/destory/{id}', [ChargeController::class, 'destroy']);
        Route::post('charges/store', [ChargeController::class, 'store']);
        Route::patch('charges/update/{id}', [ChargeController::class, 'update']);

        /* System Parameter */
        Route::get('parameter',[SystemParameterController::class, 'index']);
        Route::get('parameter/getrecord',[SystemParameterController::class, 'getrecord']);
        Route::patch('parameter/update',[SystemParameterController::class, 'update']);

        /* User Account Route */
        Route::get('users',[UserController::class, 'index']);
        Route::get('users/getrecords', [UserController::class, 'getRecords']);
        Route::get('users/edit/{id}', [UserController::class, 'getUserToEdit']);
        Route::patch('users/update/{id}', [UserController::class, 'update']);
        Route::get('users/destroy/{id}', [UserController::class, 'destroy']);
        Route::post('users/store', [UserController::class, 'store']);

        /* Driver */
        Route::get('drivers', [DriverController::class, 'index']);
        Route::get('drivers/getrecord', [DriverController::class, 'getrecord']);
        Route::get('drivers/search/{string}', [DriverController::class, 'search']);
        Route::post('drivers/store', [DriverController::class, 'store']);
        Route::get('drivers/edit/{id}', [DriverController::class, 'edit']);
        Route::patch('drivers/update/{id}', [DriverController::class, 'update']);
        Route::get('drivers/destory/{id}', [DriverController::class, 'destroy']);
        Route::get('drivers/export', [DriverController::class, 'export']);

    });

});

