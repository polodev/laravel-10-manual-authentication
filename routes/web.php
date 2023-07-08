<?php

use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/
require __dir__ . '/auth_routes.php';

Route::get('/home', [HomeController::class, 'home'])
  ->middleware('auth')
  ->name('home');

Route::get('/verified-home', [HomeController::class, 'veified_home'])
  ->middleware('verified')
  ->name('verified_home');


Route::get('/', function () {
    return view('welcome');
});
