<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\LogoutController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\EmailVerificationController;
use App\Http\Controllers\Auth\PasswordConfirmationController;

Route::get('/register', [RegisterController::class, 'show'])
  ->name('register');
Route::post('/register', [RegisterController::class, 'handle'])
  ->name('register');

Route::get('/login', [LoginController::class, 'show'])
  ->name('login');

Route::post('/login', [LoginController::class, 'handle'])
  ->name('login');

Route::post('/logout', [LogoutController::class, 'handle'])
  ->name('logout');

Route::get('/confirm-password', [PasswordConfirmationController::class, 'show'])
  ->middleware('auth')
  ->name('password.confirm');

Route::post('/confirm-password', [PasswordConfirmationController::class, 'handle'])
  ->middleware('auth')
  ->name('password.confirm');

Route::get('/verify-email', [EmailVerificationController::class, 'show'])
  ->middleware('auth')
  ->name('verification.notice'); // <-- don't change the route name

Route::post('/verify-email/request', [EmailVerificationController::class, 'request'])
  ->middleware('auth')
  ->name('verification.request');

Route::post('/verify-email/{id}/{hash}', [EmailVerificationController::class, 'verify'])
  ->middleware(['auth', 'signed']) // <-- don't remove "signed"
  ->name('verification.verify'); // <-- don't change the route name
