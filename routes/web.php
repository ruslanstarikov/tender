<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\LoginController;
use App\Http\Controllers\Admin\TenderRequestController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\TenderController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\SupplierController;
// Customer-facing routes
use App\Http\Controllers\Customer\AuthController as CustomerAuthController;
use App\Http\Controllers\Customer\DashboardController as CustomerDashboardController;
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
    return redirect()->route('customer.login');
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

    // Tender Requests routes
    Route::get('tender-requests', [TenderRequestController::class, 'index'])->name('tender-requests.index');
    Route::get('tender-requests/create', [TenderRequestController::class, 'create'])->name('tender-requests.create');
    Route::post('tender-requests', [TenderRequestController::class, 'store'])->name('tender-requests.store');
    Route::get('tender-requests/{tenderRequest}', [TenderRequestController::class, 'show'])->name('tender-requests.show');
    Route::get('tender-requests/{tenderRequest}/edit', [TenderRequestController::class, 'edit'])->name('tender-requests.edit');
    Route::put('tender-requests/{tenderRequest}', [TenderRequestController::class, 'update'])->name('tender-requests.update');
    Route::post('tender-requests/{tenderRequest}/convert', [TenderRequestController::class, 'convertToTender'])->name('tender-requests.convert-to-tender');
    Route::get('customers/{customer}/tender-requests', [TenderRequestController::class, 'customerTenderRequests'])->name('tender-requests.customer-requests');
});

// Public customer routes (login, register)
Route::prefix('customer')->name('customer.')->group(function () {
    Route::get('register', [CustomerAuthController::class, 'showRegistrationForm'])->name('register');
    Route::post('register', [CustomerAuthController::class, 'register']);
    Route::get('login', [CustomerAuthController::class, 'showLoginForm'])->name('login');
    Route::post('login', [CustomerAuthController::class, 'login']);
});

// Protected customer routes
Route::prefix('customer')->name('customer.')->middleware(['customer.auth'])->group(function () {
    Route::post('logout', [CustomerAuthController::class, 'logout'])->name('logout');
    Route::get('dashboard', [CustomerDashboardController::class, 'index'])->name('dashboard');

    // Tender requests
    Route::get('tender-requests', [TenderRequestController::class, 'index'])->name('tender-requests.index');
    Route::get('tender-requests/create', [TenderRequestController::class, 'create'])->name('tender-requests.create');
    Route::post('tender-requests', [TenderRequestController::class, 'store'])->name('tender-requests.store');
    Route::get('tender-requests/{tenderRequest}', [TenderRequestController::class, 'show'])->name('tender-requests.show');

    // AJAX endpoints
    Route::get('api/available-slots', [TenderRequestController::class, 'getAvailableSlots'])->name('api.available-slots');
});

// API endpoints (can be accessed without authentication for now)
Route::prefix('api')->group(function () {
    Route::get('frame-types', [TenderController::class, 'getFrameTypes'])->name('api.frame-types');
});
