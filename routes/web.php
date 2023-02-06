<?php

use App\Http\Controllers\CertificateController;
use App\Http\Controllers\FontController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TemplateController;
use Illuminate\Support\Facades\Route;

/**
 * Public facing.
 */
Route::get('/c/{certificate:uuid}', [CertificateController::class, 'show']);

/**
 * Admin Dashboard.
 */
Route::redirect('/', '/templates');

Route::middleware('auth')->group(function () {
    Route::resource('templates', TemplateController::class);

    Route::resource('templates.certificates', CertificateController::class)->only(['index', 'show', 'create', 'store']);

    Route::delete('fonts', [FontController::class, 'destroy'])->name('fonts.destroy');
    Route::resource('fonts', FontController::class)->only('index', 'store');

    Route::controller(ProfileController::class)
        ->prefix('profile')
        ->name('profile.')
        ->group(function () {
            Route::get('/', 'edit')->name('edit');
            Route::patch('/', 'update')->name('update');
            Route::delete('/', 'ddestroy')->name('destroy');
        });
});

require __DIR__ . '/auth.php';
