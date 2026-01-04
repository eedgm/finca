<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CowController;
use App\Http\Controllers\FarmController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\SoldController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\MarketController;
use App\Http\Controllers\CowTypeController;
use App\Http\Controllers\HistoryController;
use App\Http\Controllers\MedicineController;
use App\Http\Controllers\ManufacturerController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\PermissionController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return redirect(route('login'));
});

Route::middleware(['auth:sanctum', 'verified'])
    ->get('/', [FarmController::class, 'dashboard'])
    ->name('dashboard');

Route::prefix('/')
    ->middleware(['auth:sanctum', 'verified'])
    ->group(function () {
        Route::resource('cows', CowController::class);
        Route::get('cows-genealogy/{cow?}', [CowController::class, 'genealogy'])->name('cows.genealogy');
        Route::resource('cow-types', CowTypeController::class);
        Route::resource('farms', FarmController::class);
        Route::resource('histories', HistoryController::class);
        Route::resource('manufacturers', ManufacturerController::class);
        Route::resource('markets', MarketController::class);
        Route::resource('medicines', MedicineController::class);
        Route::resource('solds', SoldController::class);
        Route::resource('users', UserController::class);
        Route::resource('roles', RoleController::class);
        Route::resource('permissions', PermissionController::class);
    });
