<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\LoginController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\TenderController;

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
Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('login', [LoginController::class, 'login'])->name('login.submit');
    Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');
});


Route::prefix('admin')->name('admin.')->group(function () {
    // Tenders routes
    Route::get('tenders', [TenderController::class, 'index'])->name('tenders.index');
    Route::get('tenders/create', [TenderController::class, 'create'])->name('tenders.create');
    Route::post('tenders', [TenderController::class, 'store'])->name('tenders.store');
    Route::get('tenders/{tender}', [TenderController::class, 'show'])->name('tenders.show');
    
    // Contacts routes (placeholder)
    Route::get('contacts', function() {
        return view('admin.contacts.index');
    })->name('contacts.index');
    
    // Suppliers routes (placeholder)
    Route::get('suppliers', function() {
        return view('admin.suppliers.index');
    })->name('suppliers.index');
});
