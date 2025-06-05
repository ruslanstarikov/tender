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
    // Show create form
    Route::get('tenders/create', [TenderController::class, 'create'])->name('tenders.create');
    // Handle form submission
    Route::post('tenders', [TenderController::class, 'store'])->name('tenders.store');
    // Preview a tender
    Route::get('tenders/{tender}', [TenderController::class, 'show'])->name('tenders.show');
});
