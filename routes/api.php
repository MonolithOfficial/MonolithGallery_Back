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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/v1/users', [App\Http\Controllers\UserController::class, 'createUserByAdmin']);
Route::put('/v1/make-admin', [App\Http\Controllers\UserController::class, 'makeAdmin']);
Route::put('/v1/remove-admin', [App\Http\Controllers\UserController::class, 'removeAdmin']);
Route::get('/v1/users', [App\Http\Controllers\UserController::class, 'getAllUsers']);
Route::post('/v1/registration', [App\Http\Controllers\UserController::class, 'createUser']);
Route::delete('/v1/delete-user', [App\Http\Controllers\UserController::class, 'deleteUser']);
Route::post('/v1/login', [App\Http\Controllers\UserController::class, 'loginUser']);


Route::post('/v1/add-album', [App\Http\Controllers\AlbumsController::class, 'addAlbum']);
Route::get('/v1/albums', [App\Http\Controllers\AlbumsController::class, 'getAlbumsByUserId']);
Route::get('/v1/album/{id}', [App\Http\Controllers\AlbumsController::class, 'getAlbum']);
Route::delete('/v1/delete-album/{id}', [App\Http\Controllers\AlbumsController::class, 'deleteAlbum']);
Route::post('/v1/add-image', [App\Http\Controllers\ImageController::class, 'addImage']);
Route::delete('/v1/delete-image/{id}', [App\Http\Controllers\ImageController::class, 'deleteImage']);



