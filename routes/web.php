<?php

use App\Http\Controllers\BusinessProfileController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\JobController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\QuoteController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', DashboardController::class)
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::resource('clients', ClientController::class);
    Route::resource('jobs', JobController::class);
    Route::resource('quotes', QuoteController::class);
    Route::resource('invoices', InvoiceController::class);

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/settings/business', [BusinessProfileController::class, 'edit'])->name('settings.business.edit');
    Route::patch('/settings/business', [BusinessProfileController::class, 'update'])->name('settings.business.update');
});

require __DIR__.'/auth.php';
