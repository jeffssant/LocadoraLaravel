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



Route::post('login', 'App\Http\Controllers\AuthController@login');


Route::group(['middleware' => 'jwt.auth'], function () {
    Route::apiResource('cliente', 'App\Http\Controllers\ClientController');
    Route::apiResource('carro', 'App\Http\Controllers\CarController');
    Route::apiResource('locacao', 'App\Http\Controllers\LocationController');
    Route::apiResource('marca', 'App\Http\Controllers\BrandController');
    Route::apiResource('modelo', 'App\Http\Controllers\TypeController');
    Route::post('logout', 'App\Http\Controllers\AuthController@logout');
    Route::post('refresh', 'App\Http\Controllers\AuthController@refresh');
    Route::post('me', 'App\Http\Controllers\AuthController@me');
});
