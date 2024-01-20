<?php

use App\Enums\Can;
use App\Livewire\{Admin, Auth, Welcome};
use Illuminate\Support\Facades\Route;

//region Guest
Route::get('/login', Auth\Login::class)->name('login');
Route::get('/register', Auth\Register::class)->name('auth.register');
Route::get('/password/recovery', Auth\Password\Recovery::class)->name('password.recovery');
Route::get('/password/reset', Auth\Password\Reset::class)->name('password.reset');
//endregion

//region Authenticated
Route::middleware('auth')->group(function () {
    Route::get('/', Welcome::class)->name('dashboard');

    //region Admin
    Route::prefix('admin')->middleware('can:' . Can::BE_AN_ADMIN->value)->group(function () {
        Route::get('/dashborad', Admin\Dashboard::class)->name('admin.dashboard');
        Route::get('/users', Admin\Users\Index::class)->name('admin.users');
    });
    //endregion
});
//endregion
