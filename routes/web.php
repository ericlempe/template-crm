<?php

use App\Enums\Can;
use App\Http\Middleware\ShouldBeVerified;
use App\Livewire\Admin\{Dashboard, Users};
use App\Livewire\Auth\{EmailValidation, Login, Password, Register};
use App\Livewire\Welcome;
use Illuminate\Support\Facades\Route;

//region Guest
Route::redirect('/', '/login');
Route::get('/login', Login::class)->name('login')->middleware('guest');
Route::get('/register', Register::class)->name('auth.register')->middleware('guest');
Route::get('/email-validation', EmailValidation::class)->name('auth.email-validation')->middleware('auth');
Route::get('/logout', fn () => auth()->logout())->name('auth.logout');
Route::get('/password/recovery', Password\Recovery::class)->name('password.recovery');
Route::get('/password/reset', Password\Reset::class)->name('password.reset');
//endregion

//region Authenticated
Route::middleware(['auth', ShouldBeVerified::class])->group(function () {
    Route::get('/dashboard', Welcome::class)->name('dashboard');

    //region Admin
    Route::prefix('/admin')->middleware('can:' . Can::BE_AN_ADMIN->value)->group(function () {
        Route::get('/dashboard', Dashboard::class)->name('admin.dashboard');

        Route::get('/users', Users\Index::class)->name('admin.users');
    });
    //endregion
});
//endregion
