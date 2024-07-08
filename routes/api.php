<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\OrganisationController;

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
