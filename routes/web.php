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
        Route::get('/{id}/{isReport}', [PatientController::class, 'viewPatient'])->name('view.patient');
        Route::get('/medicine/{id}', [PatientController::class, 'viewPatientMedicine'])->name('view.patient.medicine');
        Route::post('/create-patient', [PatientController::class, 'createPatient']);
        Route::post('/dispense-medicine', [PatientController::class, 'dispenseMedicine']);
        Route::post('/create-patient/form', [PatientController::class, 'createPatientForm']);
        Route::post('/create-patient/prenatal', [PatientController::class, 'createPrenatalForm']); 
        Route::post('/create-patient/postnatal', [PatientController::class, 'createPostnatalForm']); 
        Route::post('/create-patient/nutrition', [PatientController::class, 'createNutritionForm']);
        Route::post('/create-patient/deworming', [PatientController::class, 'createDewormingForm']);
        Route::post('/create-patient/vaccination', [PatientController::class, 'createVaccinationForm']);
    });
    
    Route::prefix('medicines')->group(function () {
        Route::get('/', [MedicineController::class, 'viewMedicine'])->name('view.medicines');
        Route::post('/dispense-barangay-medicine', [MedicineController::class, 'dispenseBarangayMedicine']);
        Route::post('/save-stock', [MedicineController::class, 'saveMedicineStock']);
        Route::post('/get-categories', [MedicineController::class, 'getCategories']);
        Route::post('/get-units', [MedicineController::class, 'getUnits']);
    });

    Route::prefix('history')->group(function () {
        Route::get('/{id}', [PatientController::class, 'viewMedicalHistory'])->name('view.history');
        Route::post('/save-allergy', [PatientController::class, 'saveAllergy']);
        Route::post('/save-medication', [PatientController::class, 'saveMedication']);
        Route::post('/save-maintenance', [PatientController::class, 'saveMaintenanceHistory']);
        Route::post('/save-vaccination', [PatientController::class, 'saveVaccinationHistory']);
        Route::post('/save-disease', [PatientController::class, 'saveDisease']);
        Route::post('/save-surgery', [PatientController::class, 'saveSurgery']);
        Route::post('/save-women', [PatientController::class, 'saveWomen']);
        Route::post('/save-habit', [PatientController::class, 'saveHabit']);
    });

    Route::prefix('maintenance')->group(function () {
        Route::get('/', [UserController::class, 'viewMaitenance'])->name('view.maintenance');
        Route::post('/save-maintenance', [UserController::class, 'saveMaintenance']);
    });

    Route::prefix('reports')->group(function () {
        Route::get('/', [UserController::class, 'viewReports'])->name('view.reports');
    });
});


