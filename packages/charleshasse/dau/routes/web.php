<?php 

use Illuminate\Support\Facades\Route;
use CharlesHasse\Dau\Livewire\Login;
use CharlesHasse\Dau\Livewire\Register;

Route::middleware('web')->group(function () {

    Route::get('/login', Login::class)
        ->name('login');

    Route::get('/register', Register::class)
        ->name('register');

});
