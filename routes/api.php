<?php

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

//Route::middleware('auth:api')->get('/user', function (Request $request) {
//    return $request->user();
//});
Route::post('users', 'Api\UserController@store');
Route::put('users/{user}/agent', 'Api\UserController@changeUserAgentPermission');

Route::post('admins', 'Api\AdminController@store');
Route::get('admins/roles/load', 'Api\AdminController@loadAllAdminRoles');
Route::post('admins/authenticate', 'Api\AdminController@authenticateAdmin');

Route::apiResource('checkupprescriptions', 'Api\CheckupprescriptionController');
Route::apiResource('doctorappointments', 'Api\DoctorappointmentController');
Route::apiResource('doctors', 'Api\DoctorController');
Route::apiResource('doctortypes', 'Api\DoctortypeController');
Route::apiResource('patientcheckups', 'Api\PatientcheckupController');
Route::apiResource('patients', 'Api\PatientController');
Route::apiResource('patientprescriptions', 'Api\PatientprescriptionController');



Route::post('transactions', 'Api\TransactionController@store');
Route::put('transactions/{transaction}/status', 'Api\TransactionController@update');
Route::get('users/{user}/transactions/complete', 'Api\TransactionController@loadAllUserCompletedTransactions');
