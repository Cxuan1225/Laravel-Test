<?php

use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('dashboard');
});
Route::get('/dashboard', function () {
    return view('dashboard');
})->name('dashboard');
Route::get('/products', function () {
    return view('products');
})->name('products');

Route::get('/users', [UserController::class, 'index'])->name('users');

Route::post('/users/create',  [UserController::class, 'store'])->name('users.create');
Route::put('/users/update/{id}',  [UserController::class, 'update'])->name('users.update');
Route::post('/users/destroy/{id}',  [UserController::class, 'destroy'])->name('users.destroy');
Route::post('/users/bulk-delete', [UserController::class, 'bulkDelete'])->name('users.bulkDelete');
Route::get('users/export/', [UserController::class, 'export'])->name('users.export');
