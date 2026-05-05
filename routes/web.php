<?php

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;


// include route
require __DIR__ . '/admin.php';

Route::get('/', function () {
    return Inertia::render('Home');
})->name('home');


// storage link 
Route::get('/storage-link', function () {
    Artisan::call('storage:link');
    return 'Storage link created';
});
