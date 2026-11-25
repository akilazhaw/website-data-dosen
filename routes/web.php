<?php

use App\Http\Controllers\DosenController;
use Illuminate\Support\Facades\Route;

// Halaman utama langsung diarahkan ke sistem data dosen
Route::get('/', [DosenController::class, 'index'])->name('home');

// Resource Route untuk CRUD Dosen
Route::resource('dosen', DosenController::class);

// Route Reset Data Bawaan
Route::post('/dosen/reset-default', [DosenController::class, 'resetDefault'])->name('dosen.reset');
