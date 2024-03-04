<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::get('/artists', 'App\Http\Controllers\API\ArtistApiController@index');
Route::get('/artists/{artist}', 'App\Http\Controllers\API\ArtistApiController@show');
Route::post('/artists', 'App\Http\Controllers\API\ArtistApiController@store');
Route::put('/artists/{artist}', 'App\Http\Controllers\API\ArtistApiController@update');
Route::delete('/artists/{artist}', 'App\Http\Controllers\API\ArtistApiController@destroy');

Route::get('/albums', 'App\Http\Controllers\API\AlbumApiController@index');
Route::get('/albums/{album}', 'App\Http\Controllers\API\AlbumApiController@show');
Route::post('/albums', 'App\Http\Controllers\API\AlbumApiController@store');
Route::put('/albums/{album}', 'App\Http\Controllers\API\AlbumApiController@update');
Route::delete('/albums/{album}', 'App\Http\Controllers\API\AlbumApiController@destroy');
