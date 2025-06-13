<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\LoginController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\TenderController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\SupplierController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});
// Admin Authentication Routes (no middleware)
Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('login', [LoginController::class, 'login'])->name('login.submit');
});

// Protected Admin Routes
Route::prefix('admin')->name('admin.')->middleware(['admin.auth'])->group(function () {
    Route::post('logout', [LoginController::class, 'logout'])->name('logout');
    Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');
});


// All other admin routes require authentication
Route::prefix('admin')->name('admin.')->middleware(['admin.auth'])->group(function () {
    // Tenders routes
    Route::get('tenders', [TenderController::class, 'index'])->name('tenders.index');
    Route::get('tenders/create', [TenderController::class, 'create'])->name('tenders.create');
    Route::post('tenders', [TenderController::class, 'store'])->name('tenders.store');
    Route::get('tenders/{tender}', [TenderController::class, 'show'])->name('tenders.show');
    
    // Supplier routes
    Route::resource('suppliers', SupplierController::class);
    Route::post('suppliers/{supplier}/reset-password', [SupplierController::class, 'resetPassword'])->name('suppliers.reset-password');
    Route::patch('suppliers/{supplier}/toggle-status', [SupplierController::class, 'toggleStatus'])->name('suppliers.toggle-status');
    Route::patch('suppliers/{supplier}/toggle-verification', [SupplierController::class, 'toggleVerification'])->name('suppliers.toggle-verification');
    Route::patch('suppliers/{supplier}/onboarding-status', [SupplierController::class, 'updateOnboardingStatus'])->name('suppliers.onboarding-status');
    
    // Customer routes
    Route::resource('customers', CustomerController::class);
    Route::post('customers/{customer}/reset-password', [CustomerController::class, 'resetPassword'])->name('customers.reset-password');
    Route::patch('customers/{customer}/toggle-status', [CustomerController::class, 'toggleStatus'])->name('customers.toggle-status');
});
