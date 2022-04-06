<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::apiResource('cliente', 'App\Http\Controllers\ClientController');
Route::apiResource('carro', 'App\Http\Controllers\CarController');
Route::apiResource('locacao', 'App\Http\Controllers\LocationController');
Route::apiResource('marca', 'App\Http\Controllers\BrandController');
Route::apiResource('modelo', 'App\Http\Controllers\TypeController');
