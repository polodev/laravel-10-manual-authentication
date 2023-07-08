<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Auth\Events\Registered;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Providers\RouteServiceProvider;



class RegisterController extends Controller
{
  public function show()
  {
      return view('auth.register');
  }

  public function handle()
  {
    request()->validate([
      'name' => ['required', 'string', 'max:255'],
      'email' => ['required', 'email', 'max:255'],
      'password' => ['required', 'string', 'min:6', 'confirmed']
    ]);

    $user = User::create([
      'name' => request('name'),
      'email' => request('email'),
      'password' => Hash::make(request('password'))
    ]);
    event(new Registered($user));
    Auth::login($user);
    return redirect()->to(RouteServiceProvider::HOME);
  }
}
