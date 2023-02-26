<?php

use App\Http\Controllers\SearchCertificateController;
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
