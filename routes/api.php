<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DoctorController;
use App\Http\Controllers\PatientController;
use App\Http\Controllers\PharmacyController;
use App\Http\Controllers\DrugController;
use App\Http\Controllers\AuthController;


Route::post('/login',[AuthController::class,'login']);
Route::post('/register',[AuthController::class,'register']);

Route::group(['middleware' => ['logged']], function () {

    Route::post('/logout',[AuthController::class,'logout']);

    Route::post('/doctor/register',[DoctorController::class,'register']);
    Route::get('doctor',[DoctorController::class,'getDoctor']);
    Route::post('doctor/update/{id}',[DoctorController::class,'updateDoctor']);
    Route::post('doctor/delete/{id}',[DoctorController::class,'deleteDoctor']);
    
    Route::post('/patient/register',[PatientController::class,'register']);
    Route::get('patient',[PatientController::class,'getPatient']);
    Route::get('patient/{id}',[PatientController::class,'getSinglePatient']);
    Route::post('patient/update/{id}',[PatientController::class,'updatePatient']);
    Route::post('patient/delete/{id}',[PatientController::class,'deletePatient']);
    
    Route::get('pharmacy',[PharmacyController::class,'getPharmacy']);
    Route::post('add/pharmacy',[PharmacyController::class,'addPharmacy']);
    Route::post('pharmacy/update/{id}',[PharmacyController::class,'updatePharmacy']);
    Route::post('pharmacy/delete/{id}',[PharmacyController::class,'deletePharmacy']);
    
    
    Route::get('drugs',[DrugController::class,'getDrugs']);
    Route::post('add/drugs',[DrugController::class,'addDrugs']);
    Route::post('drugs/update/{id}',[DrugController::class,'updateDrugs']);
    Route::post('drugs/delete/{id}',[DrugController::class,'deleteDrugs']);

});


