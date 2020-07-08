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
Route::apiResource('users', 'Api\UserController');

Route::apiResource('admins', 'Api\AdminController');
Route::post('admins/authenticate', 'Api\AdminController@authenticateAdmin');
Route::get('admin', function (Request $request){
    return response()->json($request->user()->admin);
})->middleware('auth:sanctum');

Route::apiResource('adminpermissions', 'Api\AdminpermissionController');
Route::apiResource('checkupprescriptions', 'Api\CheckupprescriptionController');
Route::apiResource('checkuptransactions', 'Api\CheckuptransactionController');
Route::apiResource('doctorappointments', 'Api\DoctorappointmentController');
Route::apiResource('doctors', 'Api\DoctorController');
Route::apiResource('doctortypes', 'Api\DoctortypeController');
Route::apiResource('patientcheckups', 'Api\PatientcheckupController');
Route::apiResource('patients', 'Api\PatientController');
Route::apiResource('patientprescriptions', 'Api\PatientprescriptionController');
Route::apiResource('permissions', 'Api\PermissionController');
Route::apiResource('transactions', 'Api\TransactionController');
