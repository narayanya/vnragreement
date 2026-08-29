<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CropController;
use App\Http\Controllers\CoreAPIController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    
    Route::get('/master/crop', [CropController::class, 'index'])->name('crops.index');
    Route::post('/master/crop', [CropController::class, 'store'])->name('crops.store');
    Route::get('/master/crop/{id}', [CropController::class, 'show'])->name('crops.show');
    Route::patch('/master/crop/{id}', [CropController::class, 'update'])->name('crops.update');
    Route::delete('/master/crop/{id}', [CropController::class, 'destroy'])->name('crops.destroy');

    // Core API
    Route::get('/core/api', [CoreAPIController::class, 'index'])->name('core.api.index');
    Route::get('/core/api/sync', [CoreAPIController::class, 'sync'])->name('core_api_sync');
    Route::post('/core/api/import', [CoreAPIController::class, 'importAPISData'])->name('importAPISData');
});

require __DIR__.'/auth.php';
