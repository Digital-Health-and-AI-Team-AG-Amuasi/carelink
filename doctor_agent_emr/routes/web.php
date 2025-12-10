<?php

declare(strict_types=1);

use App\Http\Controllers\AskLLMController;
use App\Http\Controllers\ConditionsController;
use App\Http\Controllers\MedicationsController;
use App\Http\Controllers\PatientMedicalFlagsController;
use App\Http\Controllers\Patients\PatientController;
use App\Http\Controllers\Patients\PatientRecordsController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\Users\PermissionsController;
use App\Http\Controllers\Users\RolesController;
use App\Http\Controllers\Users\UsersController;
use App\Http\Controllers\VisitsController;
use App\Http\Controllers\VitalsController;
use Illuminate\Support\Facades\Route;

Route::middleware(['web', 'auth'])->group(static function () {
    Route::get('/', static fn () => redirect()->route('patients.index'));

    Route::get('/emergency', [PatientMedicalFlagsController::class, 'showEmergencyPage']);

    Route::prefix('dashboards')->name('dashboards.')->group(static function () {
        Route::get('/main', static fn () => redirect()->route('patients.index'))->name('main');
    });

    Route::get('/select-patient', [PatientController::class, 'showForm'])->name('select.patient.form');
    Route::get('/view-records', [PatientController::class, 'getPatientsData'])->name('view.records');
    Route::post('/ask-llm', AskLLMController::class);

    Route::get('/settings', SettingsController::class)->name('settings');

    Route::prefix('patients')->name('patients.')->group(static function () {
        Route::get('/index', [PatientController::class, 'index'])->name('index');
        Route::get('/create', [PatientController::class, 'create'])->name('create');
        Route::post('/store', [PatientController::class, 'store'])->name('store');
        Route::get('/{patient}', [PatientController::class, 'view'])->name('view');
        Route::get('/edit/{patient}', [PatientController::class, 'edit'])->name('edit');
        Route::put('/{patient}/update', [PatientController::class, 'update'])->name('update');
        Route::delete('/delete/{patient}', [PatientController::class, 'destroy'])->name('destroy');
        Route::get('/{patient}/visits', [VisitsController::class, 'index'])->name('visits');
        Route::get('/{patient}/visits/create', [VisitsController::class, 'create'])->name('create-visit');
        Route::post('/{patient}/visits', [VisitsController::class, 'store'])->name('save-visit');
        Route::get('/{patient}/conditions', [ConditionsController::class, 'index'])->name('conditions');
        Route::get('/{patient}/reviews/', [PatientRecordsController::class, 'index'])->name('review');
        Route::get('/{patient}/review/create', [PatientRecordsController::class, 'create'])->name('create-review');
        Route::put('/patient/{patient}/review/{patientRecord}/update', [PatientRecordsController::class, 'update'])->name('update-review');
        Route::delete('/{patient}/review/{patientRecord}/delete', [PatientRecordsController::class, 'destroy'])->name('delete-review');
        Route::post('/{patient}/review', [PatientRecordsController::class, 'store'])->name('store-review');
        Route::get('/{patient}/review/{patientRecord}', [PatientRecordsController::class, 'show'])->name('show-review');
        Route::get('/all/delete-all', [PatientController::class, 'deleteAllSoftDeletedPatients']);
    });

    // todo: vitals history for patient

    Route::prefix('visits')->name('visits.')->group(static function () {
        Route::get('/{visit}', [VisitsController::class, 'view'])->name('view');
        Route::get('/{visit}/vitals/create', [VitalsController::class, 'create'])->name('vital.create');
        Route::post('/{visit}/vitals', [VitalsController::class, 'store'])->name('vital.store');
        Route::get('/{visit}/medications/create', [MedicationsController::class, 'create'])->name('medications.create');
        Route::post('/{visit}/medications', [MedicationsController::class, 'store'])->name('medications.store');
        Route::get('/{visit}/conditions/create', [ConditionsController::class, 'create'])->name('conditions.create');
        Route::post('/{visit}/conditions', [ConditionsController::class, 'store'])->name('conditions.store');
    });

    Route::prefix('auth')->name('auth.')->group(static function () {
        Route::prefix('users')->name('users.')->group(static function (): void {
            Route::get('/', [UsersController::class, 'index'])->name('index');
            Route::get('/create', [UsersController::class, 'create'])->name('create');
            Route::post('/store', [UsersController::class, 'store'])->name('store');
            Route::get('/{user}/roles', [UsersController::class, 'roles'])->name('roles');
            Route::patch('/{user}/roles', [UsersController::class, 'updateRoles'])->name('roles.update');
            // todo: edit and delete
        });

        Route::prefix('roles')->name('roles.')->group(static function (): void {
            Route::get('/', [RolesController::class, 'index'])->name('index');
            Route::get('/create', [RolesController::class, 'create'])->name('create');
            Route::post('/store', [RolesController::class, 'store'])->name('store');
            Route::get('/{role}/permissions', [RolesController::class, 'permissions'])->name('permissions');
            Route::patch('/{role}/permissions', [RolesController::class, 'updatePermissions'])->name('permissions.update');
            // todo: edit and delete
        });

        Route::prefix('permissions')->name('permissions.')->group(static function (): void {
            Route::get('/', [PermissionsController::class, 'index'])->name('index');
        });
    });
});

require __DIR__ . '/auth.php';
