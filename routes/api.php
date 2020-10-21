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
Route::get('users/{user}', 'Api\UserController@getUser');
Route::get('users/{user}/detail', 'Api\UserController@getUserForAdmin');
Route::get('users/agents/{is_agent}', 'Api\UserController@getPatientUsers');
Route::get('users/search', 'Api\UserController@searchUsers');
Route::post('users', 'Api\UserController@store');
Route::post('users/otp', 'Api\UserController@sendAuthenticationToken');
Route::put('users/otp/validate', 'Api\UserController@validateAuthenticationToken');
Route::put('users/{user}/agent', 'Api\UserController@changeUserAgentPermission');
Route::put('users/{user}/password', 'Api\UserController@changePassword');
Route::put('users/password_forget', 'Api\UserController@handleForgottenPassword');

//Admin
Route::post('admins', 'Api\AdminController@store');
Route::get('admins/roles/load', 'Api\AdminController@loadAllAdminRoles');
Route::post('admins/authenticate', 'Api\AdminController@authenticateAdmin');


//Doctor
Route::get('doctortypes/{doctortype_id}/doctors/active', 'Api\DoctorController@getActiveDoctorsByDoctorType');
Route::get('doctortypes/{doctortype_id}/doctors/scheduleleft', 'Api\DoctorController@getAvailableScheduleDoctorsWithSlots');
Route::get('doctortypes/{doctortype}/doctors/approved', 'Api\DoctorController@getAllApprovedDoctorsByDoctortype');
Route::get('doctors/approved', 'Api\DoctorController@getAllApprovedDoctors');
Route::get('doctors/featured', 'Api\DoctorController@getAllFeaturedDoctors');
Route::get('doctors/pending', 'Api\DoctorController@getAllPendingDoctorRequest');
Route::get('doctors/search/approved', 'Api\DoctorController@searchDoctor');
Route::post('doctors', 'Api\DoctorController@store');
Route::post('doctors/approve', 'Api\DoctorController@createApprovedDoctor');
Route::post('doctors/{doctor}/image', 'Api\DoctorController@changeDoctorMonogram');
Route::put('doctors/status', 'Api\DoctorController@changeActiveStatus');
Route::put('doctors/{doctor}', 'Api\DoctorController@update');
Route::put('doctors/{doctor}/approve', 'Api\DoctorController@evaluateDoctorJoiningRequest');
Route::put('doctors/{doctor}/booking', 'Api\DoctorController@changeDoctorBookingStatus');


//Doctortype
Route::get('doctortypes', 'Api\DoctortypeController@index');
Route::post('doctortypes', 'Api\DoctortypeController@store');

//PatientCheckup
Route::get('patientcheckups/{patientcheckup:code}', 'Api\PatientcheckupController@getDetailsFromCode');
Route::get('doctors/{doctor}/patientcheckups/history', 'Api\PatientcheckupController@getPatientCheckupsByDoctor');
Route::get('patients/{patient}/patientcheckups/history', 'Api\PatientcheckupController@getPatientCheckupsByPatient');
Route::get('users/{user}/patientcheckups/history', 'Api\PatientcheckupController@getPatientCheckupsByUser');
Route::get('doctors/{doctor}/patientcheckups/missed/today', 'Api\PatientcheckupController@getMissedPatientCheckupsByDoctor');
Route::post('patientcheckups', 'Api\PatientcheckupController@store');
Route::post('patientcheckups/call', 'Api\PatientcheckupController@sendCheckupCallNotification');
Route::put('patientcheckups/{patientcheckup:code}', 'Api\PatientcheckupController@update');
Route::put('patientcheckups/{patientcheckup:code}/call/end', 'Api\PatientcheckupController@endCallSession');


//DoctorSchedule
Route::get('doctorschedules/free', [\App\Http\Controllers\Api\DoctorScheduleController::class, 'getAvaialbleFreeDoctorSchedules']);
Route::get('doctors/{doctor}/doctorschedules/type/{type}', "Api\DoctorScheduleController@getDoctorSchedulesByDoctorFromPresentDate");
Route::post('doctorschedules', "Api\DoctorScheduleController@store");
Route::delete('doctorschedules/{doctorschedule}', "Api\DoctorScheduleController@delete");

//DoctorAppointment
Route::get('doctorappointments/today', "Api\DoctorappointmentController@getUpcomingAppointmentsTodayForAdmin");
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
Route::delete('patients/{patient}', 'Api\PatientController@delete');

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



//Doctorpayments
Route::get('doctors/{doctor}/doctorpayments', 'Api\DoctorpaymentController@getDoctorPayments');
Route::post('doctorpayments', 'Api\DoctorpaymentController@store');
//twilio
//Route::get('access_token', 'Api\TwilioAccessTokenController@generate_token');
//twilio


//FreeRequests
Route::get('doctorschedules/{doctorschedule}/freerequests', [\App\Http\Controllers\Api\FreeRequestController::class, 'fetchRequestsBySchedule']);
Route::post('freerequests', [\App\Http\Controllers\Api\FreeRequestController::class, 'store']);
