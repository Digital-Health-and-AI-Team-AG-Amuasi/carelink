<?php

declare(strict_types=1);

use App\Http\Controllers\PatientMedicalFlagsController;
use App\Http\Controllers\Patients\PatientController;
use App\Http\Controllers\Patients\PatientRecordsController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', static fn (Request $request) => $request->user());

Route::get('/patients/search', [PatientController::class, 'search']);

Route::post('/report_flag', [PatientMedicalFlagsController::class, 'store']);

Route::get('/patients/review/{patientRecord}', [PatientRecordsController::class, 'edit']);

Route::get('/get_emergency_flags', [PatientMedicalFlagsController::class, 'index']);
