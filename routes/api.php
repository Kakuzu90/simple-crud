<?php

use App\Http\Controllers\VueController;
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

Route::get('/users', [VueController::class, 'index']);
Route::post('/user', [VueController::class, 'store']);
Route::get('/user/{id}', [VueController::class, 'show']);
Route::post('/user/{id}/update', [VueController::class, 'edit']);
Route::delete('/user/{id}/delete', [VueController::class, 'destroy']);

Route::get('/search/{search}/{orderBy}/{sort}', [VueController::class, 'search']);
