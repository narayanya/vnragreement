<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CropController;
use App\Http\Controllers\CoreAPIController;
use App\Http\Controllers\CoreLocationController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\VarietyController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\FormarController;
use App\Http\Controllers\OrganiserController;
use App\Http\Controllers\SeasonController;
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

    // Core Location
    Route::get('/core/location', [CoreLocationController::class, 'index'])->name('core.location.index');
    Route::post('/core/location', [CoreLocationController::class, 'store'])->name('core.location.store');

    // Company
    Route::get('/master/company', [CompanyController::class, 'index'])->name('master.company.index');
    Route::post('/master/company', [CompanyController::class, 'store'])->name('master.company.store');
    Route::post('/master/company/sync', [CompanyController::class, 'syncFromApi'])->name('master.company.sync');

    // Variety
    Route::get('/master/variety', [VarietyController::class, 'index'])->name('master.variety.index');
    Route::post('/master/variety', [VarietyController::class, 'store'])->name('master.variety.store');
    Route::post('/master/variety/sync', [VarietyController::class, 'syncFromApi'])->name('master.variety.sync');

    // Employees
    Route::get('/master/employees', [EmployeeController::class, 'index'])->name('master.employees.index');

    // Farmers (Farmers)
    Route::get('/master/farmers',          [FormarController::class, 'index'])->name('master.farmers.index');
    Route::post('/master/farmers',         [FormarController::class, 'store'])->name('master.farmers.store');
    Route::patch('/master/farmers/{id}',   [FormarController::class, 'update'])->name('master.farmers.update');
    Route::delete('/master/farmers/{id}',  [FormarController::class, 'destroy'])->name('master.farmers.destroy');

    // Organiser
    Route::get('/master/organiser',         [OrganiserController::class, 'index'])->name('master.organiser.index');
    Route::post('/master/organiser',        [OrganiserController::class, 'store'])->name('master.organiser.store');
    Route::patch('/master/organiser/{id}',  [OrganiserController::class, 'update'])->name('master.organiser.update');
    Route::delete('/master/organiser/{id}', [OrganiserController::class, 'destroy'])->name('master.organiser.destroy');

    // Season
    Route::get('/master/season',          [SeasonController::class, 'index'])->name('seasons.index');
    Route::post('/master/season',         [SeasonController::class, 'store'])->name('seasons.store');
    Route::put('/master/season/{id}',     [SeasonController::class, 'update'])->name('seasons.update');
    Route::delete('/master/season/{id}',  [SeasonController::class, 'destroy'])->name('seasons.destroy');
});

require __DIR__.'/auth.php';
