<?php

use App\Http\Controllers\Auth\AdminAuthController;
use Illuminate\Support\Facades\Route;



Route::get('login', [AdminAuthController::class, 'loginPage'])->name('login');
Route::get('logout', [AdminAuthController::class, 'logout'])->name('logout');

Route::post('login', [AdminAuthController::class, 'login']);
Route::get('prof', [AdminAuthController::class, 'test']);
