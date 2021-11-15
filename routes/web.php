<?php

use App\Http\Controllers\FvrApplicationAuxiliaryEngineController;
use \App\Http\Controllers\MtopApplicationChargeController;
use App\Http\Controllers\FvrApplicationChargeController;
use \App\Http\Controllers\SystemParameterController;
use \App\Http\Controllers\MtopApplicationController;
use App\Http\Controllers\MtopViewRenewalController;
use App\Http\Controllers\AuxiliaryEngineController;
use App\Http\Controllers\FvrApplicationController;
use App\Http\Controllers\FvrViewRenewalController;
use App\Http\Controllers\MTOPChargeListController;
use \App\Http\Controllers\MTFRUReportController;
use App\Http\Controllers\BoatCaptainController;
use \App\Http\Controllers\TricycleController;
use \App\Http\Controllers\OperatorController;
use App\Http\Controllers\BoatTypeController;
use \App\Http\Controllers\ChargeController;
use App\Http\Controllers\DriverController;
use App\Http\Controllers\ReportController;
use \App\Http\Controllers\HomeController;
use App\Http\Controllers\BancaController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\FvrReportController;
use App\Http\Controllers\PatchController;
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


Auth::routes(['verify'=> true]);

Route::group(['middleware'=> 'auth'], function() {

    Route::group(['middleware' => 'is_role_allowed'], function() {

        /* Home Route */
        Route::get('/',[HomeController::class, 'index']);
        Route::get('/home',[HomeController::class, 'index']);
        Route::get('home/transactioncount/{month}',[HomeController::class, 'transactioncount']);
        Route::get('home/monthlyrevenue/{year}',[HomeController::class, 'monthlyrevenue']);
        Route::get('home/dailytransactions/{month}',[HomeController::class, 'dailytransaction']);

        /* FVR Home Route */
        Route::get('home/fvrtransactioncount/{month}',[HomeController::class, 'fvrtransactioncount']);
        Route::get('home/fvrmonthlyrevenue/{year}',[HomeController::class, 'fvrmonthlyrevenue']);
        Route::get('home/fvrdailytransactions/{month}',[HomeController::class, 'fvrdailytransaction']);

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
        Route::patch('mtop/validity_date', [MtopApplicationController::class,'update_validity']);

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

        /* Master List */
        Route::get('master_list', [ReportController::class, 'master_list']);
        Route::get('master_list/getdata', [ReportController::class, 'master_list_getdata']);
        Route::get('master_list/export', [ReportController::class, 'master_list_export']);

        /* MTOP Charges List */
        Route::get('mtop_charges_list', [MTOPChargeListController::class, 'index']);
        Route::get('mtop_charges_list/filter/{body_number}', [MTOPChargeListController::class, 'filter']);
        Route::get('mtop_charges_list/pdf/{body_number}/{size}/{orientation}', [MTOPChargeListController::class, 'pdf']);
        Route::get('mtop_charges_list/export/{body_number}', [MTOPChargeListController::class, 'export']);

        /* MTOP Reports */
        Route::get('mtop_report', [ReportController::class, 'index']);
        Route::get('mtop_report/getdata', [ReportController::class, 'getdata']);
        Route::get('mtop_report/export/{type}/{from}/{to}/{barangay_id}', [ReportController::class, 'export']);
        Route::get('mtop_report/pdf/{type}/{from}/{to}/{barangay_id}/{size}/{orientation}', [ReportController::class, 'pdf']);


        /* Banca */
        Route::get('banca', [BancaController::class, 'index']);
        Route::get('banca/getdata', [BancaController::class, 'getdata']);
        Route::get('banca/destroy/{id}', [BancaController::class, 'destroy']);
        Route::get('banca/export', [BancaController::class, 'export']);


        /* Banca Entry */
        Route::get('banca/entry', [BancaController::class, 'entry']);
        Route::get('banca/search/{string}', [BancaController::class, 'search']);
        Route::post('banca/store', [BancaController::class, 'store']);

        /* Auxiliary Engine */
        Route::post('auxiliary_engine/store', [AuxiliaryEngineController::class, 'store']);
        Route::get('auxiliary_engine/edit/{id}', [AuxiliaryEngineController::class,'edit']);
        Route::post('auxiliary_engine/destory/{id}/{parent_id}', [AuxiliaryEngineController::class, 'destroy']);

        /* Banca Edit */
        Route::get('banca/edit/{id}', [BancaController::class, 'edit']);
        Route::patch('banca/update', [BancaController::class, 'update']);

        /* Boat Captain */
        Route::get('captain', [BoatCaptainController::class, 'index']);
        Route::get('captain/getrecord', [BoatCaptainController::class, 'getrecord']);
        Route::get('captain/search/{string}', [BoatCaptainController::class, 'search']);
        Route::post('captain/store', [BoatCaptainController::class, 'store']);
        Route::get('captain/edit/{id}', [BoatCaptainController::class, 'edit']);
        Route::patch('captain/update', [BoatCaptainController::class, 'update']);
        Route::get('captain/destory/{id}', [BoatCaptainController::class, 'destroy']);
        Route::get('captain/export', [BoatCaptainController::class, 'export']);
        Route::post('captain/upload/{id}', [BoatCaptainController::class, 'upload']);

        /* FVR */
        Route::get('fvr', [FvrApplicationController::class, 'index']);
        Route::get('fvr/getdata', [FvrApplicationController::class, 'getdata']);
        Route::get('fvr/getdata_filtered/{from}/{to}/{barangay_id}', [FvrApplicationController::class, 'getdata_filtered']);
        Route::get('fvr/pdf_application/{id}/{form_to_print}', [FvrApplicationController::class, 'pdfApplication']);
        Route::patch('fvr/payment', [FvrApplicationController::class, 'payment']);
        Route::get('fvr_edit/{id}', [FvrApplicationController::class, 'edit']);
        Route::get('fvr/pdf/{size}/{orientation}/{from}/{to}/{barangay_id}', [FvrApplicationController::class, 'pdf']);
        Route::get('fvr/destroy/{id}', [FvrApplicationController::class, 'destroy']);
        Route::patch('fvr/approve/{id}', [FvrApplicationController::class, 'approve']);
        Route::get('fvr/pdf_application/{id}/{form_to_print}', [FvrApplicationController::class, 'pdfApplication']);
        Route::get('fvr/pdf/{size}/{orientation}/{from}/{to}/{barangay_id}', [FvrApplicationController::class, 'pdf']);
        Route::get('fvr/checkorgroup/{id}', [FvrApplicationController::class, 'or_group']);


        /* FVR ENTRY */
        Route::get('fvr_entry', [FvrApplicationController::class, 'create']);
        Route::get('fvr/search/{string}', [FvrApplicationController::class, 'search']);
        Route::get('fvr/search_new_operator/{string}', [FvrApplicationController::class, 'search_new_operator']);
        Route::post('fvr/store', [FvrApplicationController::class, 'store']);

        /* FVR EDIT */
        Route::get('fvr_edit', [FvrApplicationController::class, 'edit']);
        Route::patch('fvr_edit/update', [FvrApplicationController::class, 'update']);
        Route::post('fvr_update/add_charges', [FvrApplicationChargeController::class, 'store']);
        Route::get('fvr_update/delete_charges/{id}', [FvrApplicationChargeController::class, 'destroy']);
        Route::post('fvr_auxiliary/store', [FvrApplicationAuxiliaryEngineController::class, 'store']);
        Route::patch('fvr_auxiliary/update', [FvrApplicationAuxiliaryEngineController::class, 'update']);
        Route::get('fvr_auxiliary/destroy/{id}', [FvrApplicationAuxiliaryEngineController::class, 'destroy']);

        /* FVR RENEWAL */
        Route::get('fvr_renewal', [FvrViewRenewalController::class, 'index']);
        Route::get('fvr_renewal/getdata', [FvrViewRenewalController::class, 'getdata']);
        Route::get('fvr_renewal/filter/{from}/{to}/{barangay_id}', [FvrViewRenewalController::class, 'filter']);
        Route::get('fvr_entry/renew/{id}', [FvrApplicationController::class, 'renew']);
        Route::get('fvr_renewal/export/{from}/{to}/{barangay_id}', [FvrViewRenewalController::class, 'export']);


        /* BOAT TYPE */
        Route::get('boat_type', [BoatTypeController::class, 'index']);
        Route::get('boat_type/getrecord', [BoatTypeController::class, 'getrecord']);
        Route::post('boat_type/store', [BoatTypeController::class, 'store']);
        Route::get('boat_type/edit/{id}', [BoatTypeController::class, 'edit']);
        Route::patch('boat_type/update/{id}', [BoatTypeController::class, 'update']);
        Route::get('boat_type/destroy/{id}', [BoatTypeController::class, 'destroy']);

        /* FVR REPORT */
        Route::get('fvr_report', [FvrReportController::class, 'index']);
        Route::get('fvr_report/getdata', [FvrReportController::class, 'getdata']);
        Route::get('fvr_report/pdf/{from}/{to}/{barangay_id}/{size}/{orientation}', [FvrReportController::class, 'pdf']);
        Route::get('fvr_report/export/{from}/{to}/{barangay_id}', [FvrReportController::class, 'export']);


        Route::get('patch/fix', [PatchController::class, 'change_old_status_to_transact_type']);
        Route::get('patch/update', [PatchController::class, 'update_old_application']);
        Route::get('patch/update_mobile', [PatchController::class, 'update_mobile_number']);
        Route::get('patch/drivers', [PatchController::class, 'separate_driver_fullname']);


        Route::get('test/{from}/{to}', [ReportController::class, 'monthly_accomplishment_report']);

    });

});

