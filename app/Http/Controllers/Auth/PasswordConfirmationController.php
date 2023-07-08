<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;


class PasswordConfirmationController extends Controller
{
  public function show()
  {
      return view('auth.confirm-password');
  }

  public function handle()
  {
      if (!Hash::check(request()->password, auth()->user()->password)) {
          return back()->withErrors(['password' => 'The provided password does not match our records.']);
      }

      session()->passwordConfirmed();

      return redirect()->intended();
  }
}
