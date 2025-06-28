<?php

use App\Http\Controllers\ReconciliationController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;



Route::controller(ReconciliationController::class)->group(function () {
    Route::get('', 'index')->name('home');
    Route::post('reconciliation/upload', 'upload')->name('reconciliation.upload');
    Route::get('reconciliation', 'compare')->name('reconciliation');
});

Route::get('export/{type}', [ReconciliationController::class, 'export'])
    ->name('export');

Route::get('dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

require __DIR__ . '/settings.php';
require __DIR__ . '/auth.php';
