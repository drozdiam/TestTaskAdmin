<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Auth::routes();



Route::middleware(['auth', 'checkActiveStatus'])->prefix('admin_panel')->group(function () {
    Route::get('/', function () {
        return redirect()->route('categories');
    });

    Route::get('/categories', function () {
        return view('admin/categories');
    })->name('categories');

    Route::get('/articles', function () {
        return view('admin/articles');
    })->name('articles');

    Route::get('/users', function () {
        return view('admin/users');
    })->name('users');
});


