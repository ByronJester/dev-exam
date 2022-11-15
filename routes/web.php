<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\PatientController;
use App\Http\Controllers\MedicineController;

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

Route::middleware(['cors'])->group(function () {
    Route::get('/', function () {
        return redirect()->route('view.login');
    });
    
    
    Route::prefix('login')->group(function () {
        Route::get('/', [UserController::class, 'loginView'])->name('view.login');
        
    });
    
    Route::prefix('users')->group(function () {
        Route::get('/', [UserController::class, 'viewUsers'])->name('view.users');
        Route::post('/login', [UserController::class, 'loginAccount']);
        Route::post('/logout', [UserController::class, 'logoutAccount']);
        Route::post('/deactivate-reactivate', [UserController::class, 'changeStatus']);
        Route::post('/create-account', [UserController::class, 'saveUser']);
        Route::post('/change-password', [UserController::class, 'changePassword']);
    });
    
    Route::prefix('patients')->group(function () {
        Route::get('/', [PatientController::class, 'viewPatients'])->name('view.patients');
        Route::get('/{id}', [PatientController::class, 'viewPatient'])->name('view.patient');
        Route::get('/medicine/{id}', [PatientController::class, 'viewPatientMedicine'])->name('view.patient.medicine');
        Route::post('/create-patient', [PatientController::class, 'createPatient']);
        Route::post('/dispense-medicine', [PatientController::class, 'dispenseMedicine']);
        Route::post('/create-patient/form', [PatientController::class, 'createPatientForm']);
        Route::post('/create-patient/prenatal', [PatientController::class, 'createPrenatalForm']);
    });
    
    Route::prefix('medicines')->group(function () {
        Route::get('/', [MedicineController::class, 'viewMedicine'])->name('view.medicines');
        Route::post('/dispense-barangay-medicine', [MedicineController::class, 'dispenseBarangayMedicine']);
    });

    Route::prefix('maintenance')->group(function () {
        Route::get('/', [UserController::class, 'viewMaitenance'])->name('view.maintenance');
        Route::post('/save-maintenance', [UserController::class, 'saveMaintenance']);
    });

    Route::prefix('reports')->group(function () {
        Route::get('/', [UserController::class, 'viewReports'])->name('view.reports');
    });
});


