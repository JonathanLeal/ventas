<?php

use App\Http\Controllers\BodegaController;
use App\Http\Controllers\ClienteController;
use Illuminate\Support\Facades\Route;

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

Route::get('/bodegas', function () {
    return view('Bodegas');
});
Route::get('bodega/list', [BodegaController::class, 'obtenerBodegas'])->name('get.bodegas');
Route::get('bodega/{id}', [BodegaController::class, 'obtenerBodegaId'])->name('get.bodegaId');
Route::post('bodega/save', [BodegaController::class, 'guardarBodega'])->name('save.bodega');

Route::post('cliente/save', [ClienteController::class, 'clienteSave']);
