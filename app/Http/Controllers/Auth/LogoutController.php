<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class LogoutController extends Controller
{
  public function handle()
  {
    auth()->logout();

    return redirect()->route('login');
  }
}
