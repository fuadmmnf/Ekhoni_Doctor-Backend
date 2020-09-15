<?php

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Session;
use niklasravnsborg\LaravelPdf\Facades\Pdf;

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
Route::get('/clear', function (){
    Artisan::call('optimize');
    Artisan::call('cache:clear');
    Artisan::call('view:clear');
    Artisan::call('key:generate');
    Artisan::call('config:clear');
    Artisan::call('config:cache');
    Session::flush();
    return 'Config and Route Cached. All Cache Cleared';
});
Route::get('/', function () {
    return view('welcome');
});

Route::get('/clear', function (){
//    Artisan::call('optimize');
    Artisan::call('cache:clear');
    Artisan::call('view:clear');
//    Artisan::call('key:generate');
    Artisan::call('config:cache');
//    Session::flush();
    return 'Config and Route Cached. All Cache Cleared';
});


Route::get('/prescriptions', function (){
    $data = [
        "doctor" => \App\Doctor::findOrFail(2),
        "patient" => \App\Patient::findOrFail(1),
        "checkup" => \App\Patientcheckup::findOrFail(1),
        "prescription" => [
            "disease_description" => "dffffffffffffffffff fffffffffffff fffffff isease description",
            "special_note" => 'asasdasdiasdisud',
            "medicine_descriptions" => [
                [
                    "name" => "napa",
                    "tags" => ["day", "before lunch", "dinner"],
                    "duration" => "15 days"
                ],
                [
                    "name" => "paracetamaul",
                    "tags" => ["day", "before lunch", "dinner"],
                    "duration" => "10 days"
                ]
            ],
            "test_descriptions" => [
                [
                    "name" => "xray",
                ]
            ],
        ]
    ];
    $pdf = PDF::loadView("pdf.prescriptions.checkupprescription", $data);
    return $pdf->stream('document.pdf');
});
