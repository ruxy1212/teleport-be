<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\OrganisationController;

Route::get('/', function (Request $request) {
    return 'yaya';
});

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::prefix('auth')->middleware(['api'])->group(function(){
    Route::post('register', [AuthController::class, 'register'])->name('api.register');
    Route::post('login', [AuthController::class, 'login'])->name('api.login');    
});

Route::middleware(['api', 'check.for.jwt'])->group(function(){
    Route::get('users/{userId}', [AuthController::class, 'show'])->name('api.users.show');
    Route::get('organisations', [OrganisationController::class, 'index'])->name('api.organisations');
    Route::get('organisations/{orgId}', [OrganisationController::class, 'show'])->name('api.organisations.show');
    Route::post('organisations', [OrganisationController::class, 'store'])->name('api.organisations.store');
    Route::post('organisations/{orgId}/users', [OrganisationController::class, 'addUser'])->name('api.organisations.addUser');
});

// Route::middleware(['jwt.auth'])->group(function () {
//     Route::get('me', [AuthController::class, 'me']);
//     Route::get('users/{id}', [AuthController::class, 'me']); // User's own record

//     Route::get('organisations', [OrganisationController::class, 'index']);
//     Route::get('organisations/{orgId}', [OrganisationController::class, 'show']);
//     Route::post('organisations', [OrganisationController::class, 'store']);
//     Route::post('organisations/{orgId}/users', [OrganisationController::class, 'addUser']);
// });

// Route::group([
//     'middleware' => 'api',
//     'prefix' => 'auth'
// ], function ($router) {
//     Route::post('/register', [AuthController::class, 'register'])->name('register');
//     Route::post('/login', [AuthController::class, 'login'])->name('login');
//     Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:api')->name('logout');
//     Route::post('/refresh', [AuthController::class, 'refresh'])->middleware('auth:api')->name('refresh');
//     Route::post('/me', [AuthController::class, 'me'])->middleware('auth:api')->name('me');
// });
