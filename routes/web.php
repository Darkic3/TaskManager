<?php

use App\Http\Controllers\Site\HomeController;
use Illuminate\Support\Facades\Route;

Route::middleware('HomeMiddleWare')->name('site.')->group(function () {
    Route::get('/', [HomeController::class, 'index'])->name('home');
});
