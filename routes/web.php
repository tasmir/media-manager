<?php

use Illuminate\Support\Facades\Route;
use Tasmir\MediaManager\Http\Controllers\MediaFileController;

Route::prefix('files')->name('file.')->group(function () {
    Route::get('{slug}', [MediaFileController::class, 'show_file'])->where('slug', '.*')->name('show');
});

Route::middleware(['auth', 'web'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('files-picker', [MediaFileController::class, 'picker'])->name('files.picker');
    Route::post('ck-image-upload', [MediaFileController::class, 'ckImageUpload'])->name('ck.image.upload');
    Route::resource('files', MediaFileController::class);
});
