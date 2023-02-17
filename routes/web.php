<?php

use App\Http\Controllers\CertificateController;
use App\Http\Controllers\ExportController;
use App\Http\Controllers\FontController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SearchCertificateController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\TemplateController;
use App\Models\Certificate;
use Illuminate\Support\Facades\Route;

/**
 * Hashed URL to see certificate.
 */
Route::get('/c/{certificate:uuid}', function (Certificate $certificate) {
    return response()->file($certificate->path, [
        'Content-Disposition' => 'filename="'.$certificate->download_filename.'"',
    ]);
})->name('public.show-certificate');

/**
 * Homepage to search certificates.
 */
Route::controller(SearchCertificateController::class)
    ->name('search.')
    ->group(function () {
        Route::get('/', 'index')->name('index');
        Route::post('/', 'search')->name('search');
    });

/**
 * Admin Dashboard.
 */
Route::middleware('auth')->group(function () {
    Route::post('templates/{template}/regenerate', [TemplateController::class, 'regenerate'])->name('templates.regenerate');
    Route::resource('templates', TemplateController::class)->except('show');

    Route::resource('templates.certificates', CertificateController::class)->only(['index', 'show', 'create', 'store']);

    Route::get('settings', [SettingController::class, 'index'])->name('settings.index');

    Route::controller(ExportController::class)
        ->prefix('exports')
        ->name('exports.')
        ->group(function () {
            Route::get('{template}/participants', 'participants')->name('participants');
            Route::get('{template}/certificates', 'certificates')->name('certificates');
        });

    Route::controller(FontController::class)
        ->prefix('fonts')
        ->name('fonts.')
        ->group(function () {
            Route::post('/', 'store')->name('store');
            Route::delete('/', 'destroy')->name('destroy');
        });

    Route::controller(ProfileController::class)
        ->prefix('profile')
        ->name('profile.')
        ->group(function () {
            Route::get('/', 'edit')->name('edit');
            Route::patch('/', 'update')->name('update');
            Route::delete('/', 'destroy')->name('destroy');
        });
});

require __DIR__.'/auth.php';
