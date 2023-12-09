<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\CowController;
use App\Http\Controllers\Api\SoldController;
use App\Http\Controllers\Api\FarmController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\MarketController;
use App\Http\Controllers\Api\CowTypeController;
use App\Http\Controllers\Api\HistoryController;
use App\Http\Controllers\Api\CowSoldsController;
use App\Http\Controllers\Api\FarmCowsController;
use App\Http\Controllers\Api\MedicineController;
use App\Http\Controllers\Api\FarmUsersController;
use App\Http\Controllers\Api\farm_userController;
use App\Http\Controllers\Api\UserFarmsController;
use App\Http\Controllers\Api\cow_historyController;
use App\Http\Controllers\Api\HistoryCowsController;
use App\Http\Controllers\Api\CowHistoriesController;
use App\Http\Controllers\Api\ManufacturerController;
use App\Http\Controllers\Api\MarketMedicinesController;
use App\Http\Controllers\Api\CowTypeHistoriesController;
use App\Http\Controllers\Api\HistoryMedicinesController;
use App\Http\Controllers\Api\history_medicineController;
use App\Http\Controllers\Api\MedicineHistoriesController;
use App\Http\Controllers\Api\ManufacturerMedicinesController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::post('/login', [AuthController::class, 'login'])->name('api.login');

Route::middleware('auth:sanctum')
    ->get('/user', function (Request $request) {
        return $request->user();
    })
    ->name('api.user');

Route::name('api.')
    ->middleware('auth:sanctum')
    ->group(function () {
        Route::apiResource('cows', CowController::class);

        // Cow Solds
        Route::get('/cows/{cow}/solds', [
            CowSoldsController::class,
            'index',
        ])->name('cows.solds.index');
        Route::post('/cows/{cow}/solds', [
            CowSoldsController::class,
            'store',
        ])->name('cows.solds.store');

        // Cow Histories
        Route::get('/cows/{cow}/histories', [
            CowHistoriesController::class,
            'index',
        ])->name('cows.histories.index');
        Route::post('/cows/{cow}/histories/{history}', [
            CowHistoriesController::class,
            'store',
        ])->name('cows.histories.store');
        Route::delete('/cows/{cow}/histories/{history}', [
            CowHistoriesController::class,
            'destroy',
        ])->name('cows.histories.destroy');

        Route::apiResource('cow-types', CowTypeController::class);

        // CowType Histories
        Route::get('/cow-types/{cowType}/histories', [
            CowTypeHistoriesController::class,
            'index',
        ])->name('cow-types.histories.index');
        Route::post('/cow-types/{cowType}/histories', [
            CowTypeHistoriesController::class,
            'store',
        ])->name('cow-types.histories.store');

        Route::apiResource('farms', FarmController::class);

        // Farm Cows
        Route::get('/farms/{farm}/cows', [
            FarmCowsController::class,
            'index',
        ])->name('farms.cows.index');
        Route::post('/farms/{farm}/cows', [
            FarmCowsController::class,
            'store',
        ])->name('farms.cows.store');

        // Farm Users
        Route::get('/farms/{farm}/users', [
            FarmUsersController::class,
            'index',
        ])->name('farms.users.index');
        Route::post('/farms/{farm}/users/{user}', [
            FarmUsersController::class,
            'store',
        ])->name('farms.users.store');
        Route::delete('/farms/{farm}/users/{user}', [
            FarmUsersController::class,
            'destroy',
        ])->name('farms.users.destroy');

        Route::apiResource('histories', HistoryController::class);

        // History Medicines
        Route::get('/histories/{history}/medicines', [
            HistoryMedicinesController::class,
            'index',
        ])->name('histories.medicines.index');
        Route::post('/histories/{history}/medicines/{medicine}', [
            HistoryMedicinesController::class,
            'store',
        ])->name('histories.medicines.store');
        Route::delete('/histories/{history}/medicines/{medicine}', [
            HistoryMedicinesController::class,
            'destroy',
        ])->name('histories.medicines.destroy');

        // History Cows
        Route::get('/histories/{history}/cows', [
            HistoryCowsController::class,
            'index',
        ])->name('histories.cows.index');
        Route::post('/histories/{history}/cows/{cow}', [
            HistoryCowsController::class,
            'store',
        ])->name('histories.cows.store');
        Route::delete('/histories/{history}/cows/{cow}', [
            HistoryCowsController::class,
            'destroy',
        ])->name('histories.cows.destroy');

        Route::apiResource('manufacturers', ManufacturerController::class);

        // Manufacturer Medicines
        Route::get('/manufacturers/{manufacturer}/medicines', [
            ManufacturerMedicinesController::class,
            'index',
        ])->name('manufacturers.medicines.index');
        Route::post('/manufacturers/{manufacturer}/medicines', [
            ManufacturerMedicinesController::class,
            'store',
        ])->name('manufacturers.medicines.store');

        Route::apiResource('markets', MarketController::class);

        // Market Medicines
        Route::get('/markets/{market}/medicines', [
            MarketMedicinesController::class,
            'index',
        ])->name('markets.medicines.index');
        Route::post('/markets/{market}/medicines', [
            MarketMedicinesController::class,
            'store',
        ])->name('markets.medicines.store');

        Route::apiResource('medicines', MedicineController::class);

        // Medicine Histories
        Route::get('/medicines/{medicine}/histories', [
            MedicineHistoriesController::class,
            'index',
        ])->name('medicines.histories.index');
        Route::post('/medicines/{medicine}/histories/{history}', [
            MedicineHistoriesController::class,
            'store',
        ])->name('medicines.histories.store');
        Route::delete('/medicines/{medicine}/histories/{history}', [
            MedicineHistoriesController::class,
            'destroy',
        ])->name('medicines.histories.destroy');

        Route::apiResource('solds', SoldController::class);

        Route::apiResource('users', UserController::class);

        // User Farms
        Route::get('/users/{user}/farms', [
            UserFarmsController::class,
            'index',
        ])->name('users.farms.index');
        Route::post('/users/{user}/farms/{farm}', [
            UserFarmsController::class,
            'store',
        ])->name('users.farms.store');
        Route::delete('/users/{user}/farms/{farm}', [
            UserFarmsController::class,
            'destroy',
        ])->name('users.farms.destroy');
    });
