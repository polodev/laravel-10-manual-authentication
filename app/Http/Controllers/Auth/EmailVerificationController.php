<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class EmailVerificationController extends Controller
{
  public function show()
  {
    return view('auth.verify-email');
  }
  public function request()
  {
    auth()->user()->sendEmailVerificationNotification();
    return back()
        ->with('success', 'Verification link sent!');
  }
  public function verify(EmailVerificationRequest $request)
    {
      $request->fulfill();
      return redirect()->to('/home'); // <-- change this to whatever you want
    }
}
