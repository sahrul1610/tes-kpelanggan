<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\KeluhanPelangganController;
use App\Http\Controllers\DashboardController;
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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
Route::prefix('keluhan-pelanggan')->group(function () {
    Route::get('/', [KeluhanPelangganController::class, 'index']);
    Route::post('/add', [KeluhanPelangganController::class, 'store']);
    Route::get('/{id}', [KeluhanPelangganController::class, 'edit']);
    Route::put('/{id}', [KeluhanPelangganController::class, 'update']);
    Route::delete('/delete/{id}', [KeluhanPelangganController::class, 'destroy']);
    Route::get('/{id}/history', [KeluhanPelangganController::class, 'history']);
});

Route::get('/keluhan-pelanggan/export/{format}', [KeluhanPelangganController::class, 'export']);
Route::get('/keluhan-pelanggan/export/pdf', [KeluhanPelangganController::class, 'exportPdf']);
Route::get('/dashboard', [DashboardController::class, 'index']);
Route::get('/dashboard-data', [DashboardController::class, 'getChartData']);
Route::get('/keluhan-chart/status', [DashboardController::class, 'getStatusChartData']);
Route::get('/keluhan-chart/bar', [DashboardController::class, 'getBarChartData']);
Route::get('/top-10-keluhan', [DashboardController::class, 'getTop10Keluhan']);

Route::put('keluhan-pelanggan/{id}/update-status', [KeluhanPelangganController::class, 'updateStatus']);
Route::delete('keluhan-pelanggan/{id}/delete-status', [KeluhanPelangganController::class, 'deleteStatus']);

