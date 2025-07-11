<?php

use App\Http\Controllers\HospitalAdminDashboardController;
use App\Http\Controllers\HospitalAdminSampleRequestController;
use App\Http\Controllers\AdminUserController;
use App\Http\Controllers\BCCAdminBloodSampleController;
use App\Http\Controllers\BCCAdminDashboardController;
use App\Http\Controllers\DoctorBloodSampleController;
use App\Http\Controllers\DoctorSampleRequestController;
use App\Http\Controllers\NurseDashboardController;
use App\Http\Controllers\NurseBloodSampleController;
use App\Http\Controllers\NurseSampleRequestController;
use App\Http\Controllers\DoctorDashboardController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

Route::get('/', function () {
    if (!Auth::check()) {
        return redirect()->route('login');
    }

    $user = Auth::user();
    if ($user->hasRole('nurse')) {
        return redirect()->route('nurse.dashboard');
    } elseif ($user->hasRole('doctor')) {
        return redirect()->route('doctor.dashboard');
    } elseif ($user->hasRole('bcc_admin')) {
        return redirect()->route('bcc_admin.dashboard');
    } elseif ($user->hasRole('hospital_admin')) {
        return redirect()->route('hospital_admin.dashboard');
    }
    abort(403);
});


Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {

    // Nurse Routes
    Route::middleware(['role:nurse'])->group(function () {
        Route::prefix('nurse')->group(function () {
            Route::get('/dashboard', [NurseDashboardController::class, 'index'])->name('nurse.dashboard');

            Route::prefix('samples')->group(function () {
                Route::get('/', [NurseBloodSampleController::class, 'index'])->name('nurse.samples.index');
                Route::get('/view/{id}', [NurseBloodSampleController::class, 'show'])->name('nurse.samples.view');
                Route::get('/edit/{id?}', [NurseBloodSampleController::class, 'edit'])->name('nurse.samples.edit');
                Route::get('/delete/{id?}', [NurseBloodSampleController::class, 'delete'])->name('nurse.samples.delete');
                Route::post('/save', [NurseBloodSampleController::class, 'save'])->name('nurse.samples.save');
            });

            Route::prefix('requests')->group(function () {
                Route::get('/', [NurseSampleRequestController::class, 'index'])->name('nurse.requests.index');
                Route::get('/view/{id}', [NurseSampleRequestController::class, 'show'])->name('nurse.requests.view');
                Route::get('/evaluate/{id}', [NurseSampleRequestController::class, 'evaluate'])->name('nurse.requests.evaluate');
                Route::post('/evaluate/{id}', [NurseSampleRequestController::class, 'evaluateSubmit'])->name('nurse.requests.evaluate.submit');
            });
        });
    });

    // Doctor Routes
    Route::middleware(['role:doctor'])->group(function () {
        Route::prefix('doctor')->group(function () {
            Route::get('/dashboard', [DoctorDashboardController::class, 'index'])->name('doctor.dashboard');
            Route::prefix('samples')->group(function () {
                Route::get('/', [DoctorBloodSampleController::class, 'index'])->name('doctor.samples.index');
                Route::get('/view/{id}', [DoctorBloodSampleController::class, 'show'])->name('doctor.samples.view');
                Route::post('/request/{id}', [DoctorBloodSampleController::class, 'save'])->name('doctor.samples.request.submit');
            });
            Route::prefix('requests')->group(function () {
                Route::get('/', [DoctorSampleRequestController::class, 'index'])->name('doctor.requests.index');
                Route::get('/view/{id}', [DoctorSampleRequestController::class, 'show'])->name('doctor.requests.view');
                Route::get('/edit/{id}', [DoctorSampleRequestController::class, 'edit'])->name('doctor.requests.edit');
                Route::post('/edit/{id}', [DoctorSampleRequestController::class, 'save'])->name('doctor.requests.edit.submit');
                Route::get('/delete/{id?}', [DoctorSampleRequestController::class, 'delete'])->name('doctor.requests.delete');
            });
        });
    });

    // BCC Admin Routes
    Route::middleware(['role:bcc_admin'])->group(function () {
        Route::prefix('bcc-admin')->group(function () {
            Route::get('/dashboard', [BCCAdminDashboardController::class, 'index'])->name('bcc_admin.dashboard');
            Route::prefix('samples')->group(function () {
                Route::get('/', [BCCAdminBloodSampleController::class, 'index'])->name('bcc_admin.samples.index');
            });
        });
    });

    // Hospital Admin Routes
    Route::middleware(['role:hospital_admin'])->group(function () {
        Route::prefix('hospital-admin')->group(function () {
            Route::get('/dashboard', [HospitalAdminDashboardController::class, 'index'])->name('hospital_admin.dashboard');
            Route::prefix('requests')->group(function () {
                Route::get('/', [HospitalAdminSampleRequestController::class, 'index'])->name('hospital_admin.requests.index');
            });
        });
    });

    Route::middleware(['role:bcc_admin|hospital_admin'])->group(function () {
        Route::prefix('users')->group(function () {
            Route::get('/', [AdminUserController::class, 'index'])->name('facility_admin.users.index');
            Route::get('/edit/{id?}', [AdminUserController::class, 'edit'])->name('facility_admin.users.edit');
            Route::post('/save', [AdminUserController::class, 'save'])->name('facility_admin.users.save');
        });
    });
});
