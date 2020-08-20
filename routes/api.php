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

//User
Route::post('users', 'Api\UserController@store');
Route::post('users/otp', 'Api\UserController@sendAuthenticationToken');
Route::put('users/{user}/agent', 'Api\UserController@changeUserAgentPermission');

//Admin
Route::post('admins', 'Api\AdminController@store');
Route::get('admins/roles/load', 'Api\AdminController@loadAllAdminRoles');
Route::post('admins/authenticate', 'Api\AdminController@authenticateAdmin');


//Doctor
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


//Doctortype
Route::get('doctortypes', 'Api\DoctortypeController@index');
Route::post('doctortypes', 'Api\DoctortypeController@store');

//PatientCheckup
Route::get('patientcheckups/{patientcheckup:code}', 'Api\PatientcheckupController@getDetailsFromCode');
Route::get('doctors/{doctor}/patientcheckups/history', 'Api\PatientcheckupController@getPatientCheckupsByDoctor');
Route::post('patientcheckups', 'Api\PatientcheckupController@store');
Route::post('patientcheckups/call', 'Api\PatientcheckupController@sendCheckupCallNotification');
Route::put('patientcheckups/{patientcheckup}', 'Api\PatientcheckupController@update');


//DoctorSchedule
Route::get('doctors/{doctor}/doctorschedules', "Api\DoctorScheduleController@getDoctorSchedulesByDoctorFromPresentDate");
Route::post('doctorschedules', "Api\DoctorScheduleController@store");

//DoctorAppointment
Route::get('users/{user}/doctorappointments/upcoming', "Api\DoctorappointmentController@getUpcomingAppointmentsByUser");
Route::get('users/{user}/doctorappointments/history', "Api\DoctorappointmentController@getAppointmentHistoryByUser");
Route::get('patients/{patient}/doctorappointments/history', "Api\DoctorappointmentController@getAppointmentHistoryByPatient");
Route::get('doctors/{doctor}/doctorappointments/upcoming', "Api\DoctorappointmentController@getUpcomingDoctorAppointments");
Route::get('doctors/{doctor}/doctorappointments/{date}', "Api\DoctorappointmentController@getAllDoctorAppointmentsByDate");
Route::get('doctors/{doctor}/doctorappointments/{status}', "Api\DoctorappointmentController@getAllDoctorAppointmentsByStatus")->where('status', '[0-2]');
Route::post('doctorappointments', 'Api\DoctorappointmentController@store');
Route::put('doctorappointments/{doctorappointment}', 'Api\DoctorappointmentController@update');


//Patient
Route::get('users/{user}/patients/default', 'Api\PatientController@getUserDefaultPatientProfile');
Route::get('users/{user}/patients', 'Api\PatientController@getPatientsByUser');
Route::post('patients', 'Api\PatientController@store');
Route::post('patients/{patient}/image', 'Api\PatientController@changePatientImage');
Route::put('patients/{patient}', 'Api\PatientController@update');


//PatientPrescription
Route::get('patients/{patient}/prescriptions', 'Api\PatientprescriptionController@getPatientPrescriptionByPatient');
Route::get('patientprescriptions/{patientprescription:code}/image', 'Api\PatientprescriptionController@servePrescriptionImage');
Route::post('patientprescriptions', 'Api\PatientprescriptionController@store');

//CheckupPrescription
Route::get('doctors/{doctor}/checkupprescriptions/pending', 'Api\CheckupprescriptionController@getPendingPrescriptionByDoctor');
Route::get('patients/{patient}/checkupprescriptions/pending', 'Api\CheckupprescriptionController@getPendingPrescriptionByPatient');
Route::get('checkupprescriptions/{checkupprescription}/pdf', 'Api\CheckupprescriptionController@servePrescriptionPDF');
Route::put('checkupprescriptions/{checkupprescription}/pdf', 'Api\CheckupprescriptionController@storeCheckupPrescriptionPDF');



//Transaction
Route::post('transactions', 'Api\TransactionController@store');
Route::put('transactions/{transaction}/status', 'Api\TransactionController@update');
Route::get('users/{user}/transactions/complete', 'Api\TransactionController@loadAllUserCompletedTransactions');


//twilio
//Route::get('access_token', 'Api\TwilioAccessTokenController@generate_token');
//twilio
