<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CropController;
use App\Http\Controllers\CoreAPIController;
use App\Http\Controllers\CoreLocationController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\VarietyController;
use App\Http\Controllers\VerticalController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\FormarController;
use App\Http\Controllers\OrganiserController;
use App\Http\Controllers\SeasonController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\OldLocationController;
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

    // Vertical (Crop Types - Synced Data)
    Route::get('/master/vertical', [VerticalController::class, 'index'])->name('master.vertical.index');

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
    Route::patch('/master/variety/{id}', [VarietyController::class, 'update'])->name('master.variety.update');
    Route::delete('/master/variety/{id}', [VarietyController::class, 'destroy'])->name('master.variety.destroy');
    Route::get('/master/variety/companies/list', [VarietyController::class, 'getCompanies'])->name('master.variety.companies');
    Route::post('/master/variety/sync', [VarietyController::class, 'syncFromApi'])->name('master.variety.sync');

    // Employees
    Route::get('/master/employees', [EmployeeController::class, 'index'])->name('master.employees.index');

    // Farmers (Farmers)
    Route::get('/master/farmers',                       [FormarController::class, 'index'])->name('master.farmers.index');
    Route::post('/master/farmers',                      [FormarController::class, 'store'])->name('master.farmers.store');
    Route::patch('/master/farmers/{id}',                [FormarController::class, 'update'])->name('master.farmers.update');
    Route::delete('/master/farmers/{id}',               [FormarController::class, 'destroy'])->name('master.farmers.destroy');

    // Farmer Land Details
    Route::get('/master/farmers/{fid}/land',            [FormarController::class, 'landIndex'])->name('master.farmers.land.index');
    Route::post('/master/farmers/{fid}/land',           [FormarController::class, 'landStore'])->name('master.farmers.land.store');
    Route::delete('/master/farmers/land/{flandid}',     [FormarController::class, 'landDestroy'])->name('master.farmers.land.destroy');

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

    // Users (Settings → User Management)
    Route::get('/settings/users',              [UserController::class, 'index'])->name('users.index');
    Route::post('/settings/users',             [UserController::class, 'store'])->name('users.store');
    Route::get('/settings/users/{user}/edit',  [UserController::class, 'edit'])->name('users.edit');
    Route::patch('/settings/users/{user}',     [UserController::class, 'update'])->name('users.update');
    Route::delete('/settings/users/{user}',    [UserController::class, 'destroy'])->name('users.destroy');
    // Legacy aliases so settings.users.* route calls still resolve
    Route::get('/settings/users/list',         [UserController::class, 'index'])->name('settings.users.index');
    Route::get('/settings/users/{user}/detail',[UserController::class, 'edit'])->name('settings.users.edit');
    // Stub routes referenced in users.blade.php (not yet implemented — return gracefully)
    Route::post('/settings/users/{user}/assign-role',   [UserController::class, 'assignRole'])->name('users.assignRole');
    Route::post('/settings/users/{user}/remove-role',   [UserController::class, 'removeRole'])->name('users.removeRole');
    Route::patch('/settings/users/{user}/pdf-download', [UserController::class, 'togglePdfDownload'])->name('users.togglePdfDownload');
    Route::post('/settings/users/sync',                 [UserController::class, 'sync'])->name('users.sync');

    // Old Location Master (read-only list)
    Route::get('/master/old-location', [OldLocationController::class, 'index'])->name('master.old-location.index');

    // Roles
    Route::get('/settings/roles',              [RoleController::class, 'index'])->name('settings.roles.index');
    Route::post('/settings/roles',             [RoleController::class, 'store'])->name('settings.roles.store');
    Route::patch('/settings/roles/{role}',     [RoleController::class, 'update'])->name('settings.roles.update');
    Route::patch('/settings/roles/{role}/toggle', [RoleController::class, 'toggle'])->name('settings.roles.toggle');
    Route::delete('/settings/roles/{role}',    [RoleController::class, 'destroy'])->name('settings.roles.destroy');
    // Legacy alias used by existing role.blade.php
    Route::post('/settings/store',             [RoleController::class, 'store'])->name('settings.store');
});

require __DIR__.'/auth.php';
