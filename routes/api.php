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
Route::post('users/otp', 'Api\UserController@sendAuthenticationToken');
Route::put('users/{user}/agent', 'Api\UserController@changeUserAgentPermission');

Route::post('admins', 'Api\AdminController@store');
Route::get('admins/roles/load', 'Api\AdminController@loadAllAdminRoles');
Route::post('admins/authenticate', 'Api\AdminController@authenticateAdmin');



Route::get('doctortypes/{doctortype}/doctors/active', 'Api\DoctorController@getActiveDoctorsByDoctorType');
Route::get('doctors/active', 'Api\DoctorController@getActiveDoctors');
Route::get('doctortypes/{doctortype}/doctors/approved', 'Api\DoctorController@getAllApprovedDoctorsByDoctortype');
Route::get('doctors/approved', 'Api\DoctorController@getAllApprovedDoctors');
Route::get('doctors/featured', 'Api\DoctorController@getAllFeaturedDoctors');
Route::get('doctors/pending', 'Api\DoctorController@getAllPendingDoctorRequest');
Route::post('doctors', 'Api\DoctorController@store');
Route::post('doctors/approve', 'Api\DoctorController@createApprovedDoctor');
Route::put('doctors/status', 'Api\DoctorController@changeActiveStatus');
Route::put('doctors/{doctor}', 'Api\DoctorController@update');
Route::put('doctors/{doctor}/approve', 'Api\DoctorController@evaluateDoctorJoiningRequest');
Route::put('doctors/{doctor}/booking', 'Api\DoctorController@changeDoctorBookingStatus');
Route::put('doctors/{doctor}/image', 'Api\DoctorController@changeDoctorMonogram');


Route::get('doctortypes', 'Api\DoctortypeController@index');
Route::post('doctortypes', 'Api\DoctortypeController@store');


Route::get('patientcheckups/{patientcheckup:code}', 'Api\PatientcheckupController@getDetailsFromCode');
Route::post('patientcheckups', 'Api\PatientcheckupController@store');
Route::post('patientcheckups/{patientcheckup:code}/call', 'Api\PatientcheckupController@sendCheckupCallNotification');
Route::put('patientcheckups/{patientcheckup}', 'Api\PatientcheckupController@update');


Route::get('doctors/{doctor}/doctorschedules', "Api\DoctorScheduleController@getDoctorSchedulesByDoctorFromPresentDate");
Route::post('doctorschedules', "Api\DoctorScheduleController@store");


Route::get('users/{user}/doctorappointments/upcoming', "Api\DoctorappointmentController@getUpcomingAppointmentsByUser");
Route::get('users/{user}/doctorappointments/history', "Api\DoctorappointmentController@getAppointmentHistoryByUser");
Route::get('patients/{patient}/doctorappointments/history', "Api\DoctorappointmentController@getAppointmentHistoryByPatient");
Route::get('doctors/{doctor}/doctorappointments/upcoming', "Api\DoctorappointmentController@getUpcomingDoctorAppointments");
Route::get('doctors/{doctor}/doctorappointments/{date}', "Api\DoctorappointmentController@getAllDoctorAppointmentsByDate");
Route::get('doctors/{doctor}/doctorappointments/{status}', "Api\DoctorappointmentController@getAllDoctorAppointmentsByStatus")->where('status', '[0-2]');
Route::post('doctorappointments', 'Api\DoctorappointmentController@store');
Route::put('doctorappointments/{doctorappointment}', 'Api\DoctorappointmentController@update');



Route::get('users/{user}/patients/default', 'Api\PatientController@getUserDefaultPatientProfile');
Route::get('users/{user}/patients', 'Api\PatientController@getPatientsByUser');
Route::post('patients', 'Api\PatientController@store');
Route::post('patients/{patient}/image', 'Api\PatientController@changePatientImage');
Route::put('patients/{patient}', 'Api\PatientController@update');



Route::get('patients/{patient}/prescriptions', 'Api\PatientprescriptionController@getPatientPrescriptionByPatient');
Route::get('patientprescriptions/{patientprescription}/image', 'Api\PatientprescriptionController@servePrescriptionImage');
Route::post('patientprescriptions', 'Api\PatientprescriptionController@store');


//Route::apiResource('checkupprescriptions', 'Api\CheckupprescriptionController');




Route::post('transactions', 'Api\TransactionController@store');
Route::put('transactions/{transaction}/status', 'Api\TransactionController@update');
Route::get('users/{user}/transactions/complete', 'Api\TransactionController@loadAllUserCompletedTransactions');


//twilio
//Route::get('access_token', 'Api\TwilioAccessTokenController@generate_token');
//twilio
