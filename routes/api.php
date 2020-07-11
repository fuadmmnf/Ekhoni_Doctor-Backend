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



Route::get('doctortypes/{doctortype}/doctors/active', 'Api\DoctorController@getActiveDoctorsByDoctorType');
Route::get('doctors/approved', 'Api\DoctorController@getAllApprovedDoctors');
Route::get('doctors/pending', 'Api\DoctorController@getAllPendingDoctorRequest');
Route::post('doctors', 'Api\DoctorController@store');
Route::post('doctors/approve', 'Api\DoctorController@createApprovedDoctor');
Route::put('doctors/status', 'Api\DoctorController@changeActiveStatus');
Route::put('doctors/{doctor}', 'Api\DoctorController@update');
Route::put('doctors/{doctor}/approve', 'Api\DoctorController@evaluateDoctorJoiningRequest');
Route::put('doctors/{doctor}/booking', 'Api\DoctorController@changeDoctorBookingStatus');


Route::get('doctortypes', 'Api\DoctortypeController@index');
Route::post('doctortypes', 'Api\DoctortypeController@store');


Route::post('patientcheckups', 'Api\PatientcheckupController@store');
Route::put('patientcheckups/{patientcheckup}', 'Api\PatientcheckupController@update');




Route::get('doctors/{doctor}/doctorappointments/today', "Api\DoctorappointmentController@getAllActiveDoctorAppointmentsToday");
Route::get('doctors/{doctor}/doctorappointments/{status}', "Api\DoctorappointmentController@getAllDoctorAppointmentsByStatus")->where('status', '[0-2]');
Route::post('doctorappointments', 'Api\DoctorappointmentController@store');
Route::put('doctorappointments/{doctorappointment}', 'Api\DoctorappointmentController@update');



Route::post('patients', 'Api\PatientController@store');
Route::put('patients/{patient}', 'Api\PatientController@update');



Route::get('patients/{patient}/prescriptions', 'Api\PatientprescriptionController@getPatientPrescriptionByPatient');
Route::get('patientprescriptions/{patientprescription}/image', 'Api\PatientprescriptionController@servePrescriptionImage');
Route::post('patientprescriptions', 'Api\PatientprescriptionController@store');


//Route::apiResource('checkupprescriptions', 'Api\CheckupprescriptionController');




Route::post('transactions', 'Api\TransactionController@store');
Route::put('transactions/{transaction}/status', 'Api\TransactionController@update');
Route::get('users/{user}/transactions/complete', 'Api\TransactionController@loadAllUserCompletedTransactions');


