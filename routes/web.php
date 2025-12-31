<?php

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Site\HomeController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LogController;

Route::get('/ic3-1311', [DashboardController::class, 'index'])->name('admin.dashboard');
Route::post('/log-collector', [LogController::class, 'store'])->name('log.collector');

Route::middleware('HomeMiddleWare')->name('site.')->group(function () {
    Route::get('/', [HomeController::class, 'index'])->name('home');
});
