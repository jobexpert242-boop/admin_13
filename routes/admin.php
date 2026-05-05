<?php

use App\Http\Controllers\BackEnd\Auth\ForgotPasswordController;
use App\Http\Controllers\BackEnd\Auth\LoginController;
use App\Http\Controllers\BackEnd\Auth\RegisterController;
use App\Http\Controllers\BackEnd\Auth\ResetPasswordController;
use App\Http\Controllers\BackEnd\DashBoard\DashBoardController;
use App\Http\Controllers\BackEnd\Permission\PermissionController;
use App\Http\Controllers\BackEnd\Profile\ProfileController;
use App\Http\Controllers\BackEnd\Role\RoleController;
use App\Http\Controllers\BackEnd\SiteSetting\SiteSettingController;
use App\Http\Controllers\BackEnd\User\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/admin', function () {
    return redirect()->route('admin.dashboard');
});

Route::prefix('admin')->group(function () {
    // Register
    Route::get('/register', [RegisterController::class, 'show'])->name('admin.register');
    Route::post('/register', [RegisterController::class, 'store']);

    // Login
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'Adminlogin']);

    // logout 
    Route::post('/logout', [LoginController::class, 'logout'])->name('admin.logout');

    // forget password 
    Route::get('/forgetpassword', [ForgotPasswordController::class, 'create'])->name('admin.forgetpassword');
    Route::post('/forgetpassword', [ForgotPasswordController::class, 'store'])->middleware('throttle:3,60');

    Route::get('/reset-password/{token}', [ResetPasswordController::class, 'create'])->name('password.reset');
    Route::post('/reset-password', [ResetPasswordController::class, 'store']);
});


Route::middleware('auth')->group(function () {
    Route::prefix('admin')->group(function () {

        // dashboard 
        Route::get('/dashboard', [DashBoardController::class, 'dashBoard'])->name('admin.dashboard');

        // profile 
        Route::get('/profile', [ProfileController::class, 'edit'])->name('admin.profile');
        Route::post('/profile', [ProfileController::class, 'update'])->name('admin.profile.update');

         // Site Setting
        Route::get('/sitesettings', [SiteSettingController::class, 'siteSetting'])->name('admin.sitesetting');
        Route::post('/sitesetting/update', [SiteSettingController::class, 'siteSettingUpdate'])->name('admin.sitesetting.update');

        Route::resource('users', UserController::class);
        Route::resource('roles', RoleController::class);
        Route::resource('permissions', PermissionController::class);
    });
});


// 3 way use permission 
// Route::get('/users')->middleware('permission:view users');
// v-if="$page.props.auth.permissions.includes('delete users')"
// if (!auth()->user()->can('delete users')) {
//     abort(403);
// }