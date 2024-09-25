<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BarangController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\MutasiController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
Route::post('signup', [AuthController::class, 'signup']);
Route::post('login', [AuthController::class, 'login']);
Route::middleware('auth:sanctum')->post('logout', [AuthController::class, 'logout']);


Route::middleware('auth:sanctum')->group(function(){
    Route::get('/supplier',[SupplierController::class,'index']);
    Route::get('/supplier/{kode_supplier}',[SupplierController::class,'findById']);
    Route::post('/supplier',[SupplierController::class,'create']);
    Route::patch('/supplier/{kode_supplier}',[SupplierController::class,'update']);
    Route::delete('/supplier/{kode_supplier}',[SupplierController::class,'delete']);
});

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/barang',[BarangController::class,'index']);
    Route::get('/barang/{kode_barang}',[BarangController::class,'findById']);
    Route::post('/barang',[BarangController::class,'create']);
    Route::patch('/barang/{kode_barang}',[BarangController::class,'update']);
    Route::delete('/barang/{kode_barang}',[BarangController::class,'delete']);
});

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/mutasi', [MutasiController::class, 'index']);
    Route::post('/mutasi', [MutasiController::class, 'create']);
    Route::get('/mutasi/barang/{barang_id}', [MutasiController::class, 'historyByBarang']);
    Route::get('/mutasi/user/{user_id}', [MutasiController::class, 'historyByUser']);
    Route::get('/mutasi/{id}', [MutasiController::class, 'findById']);
    Route::delete('/mutasi/{id}', [MutasiController::class, 'delete']);
});
