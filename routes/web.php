<?php

use App\Enums\Can;
use App\Livewire\Admin\Dashboard;
use App\Livewire\Auth\Password\{Recovery, Reset};
use App\Livewire\Auth\{Login, Register};
use App\Livewire\Welcome;
use Illuminate\Support\Facades\Route;

//region Guest
Route::get('/login', Login::class)->name('login');
Route::get('/register', Register::class)->name('auth.register');
Route::get('/password/recovery', Recovery::class)->name('password.recovery');
Route::get('/password/reset', Reset::class)->name('password.reset');
//endregion

//region Authenticated
Route::middleware('auth')->group(function () {
    Route::get('/', Welcome::class)->name('dashboard');

    //region Admin
    Route::prefix('admin')->middleware('can:' . Can::BE_AN_ADMIN->value)->group(function () {
        Route::get('/dashborad', Dashboard::class)->name('admin.dashboard');
    });
    //endregion
});
//endregion
