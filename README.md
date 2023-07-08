# Laravel 10 Manual Authentication

## Manual auth in Laravel: registering

### Getting started

Registering a new user is by far the easiest of all authentication features in Laravel. You just create a new User model.

```php

// app/Http/Controllers/Auth/RegisterController.php

use App\Http\Controllers\Controller;
use App\Models\User;
use  Illuminate\Support\Facades\Hash;

class RegisterController extends Controller
{
    public function show()
    {
        return view('auth.register');
    }

    public function handle()
    {
        $user = User::create([
            'name' => request('name'),
            'email' => request('email'),
            'password' => Hash::make(request('password'))
        ]);
    }
}
```

Be sure to hash the password before storing it in the database.

### Validating

Next, we'll add some validation. 

```php
// app/Http/Controllers/Auth/RegisterController.php
request()->validate([
    'name' => ['required', 'string', 'max:255'],
    'email' => ['required', 'email', 'max:255'],
    'password' => ['required', 'string', 'min:8', 'confirmed']
]);
```

We use the confirmed validation rule to ensure that the user has confirmed the password. This rule fails when there is no password_confirmation in the request, so be sure to add it to your form.

### Events

When registering, dispatch the Registered event so that the user will get an email verification link sent to them.

```php

// app/Http/Controllers/Auth/RegisterController.php

use Illuminate\Auth\Events\Registered;

event(new Registered($user));

```

### Signing in

When using a Laravel package for authentication, the user is signed in after registering. It provides a better user experience because they don't have to login directly after registering.

```php
// app/Http/Controllers/Auth/RegisterController.php

use Illuminate\Support\Facades\Auth;

Auth::login($user);

```

### Redirecting

Of course, after a new user is created, you want to redirect them to a welcome or dashboard page. Add this at the end of the handle method:

```php
// app/Http/Controllers/Auth/RegisterController.php

use App\Providers\RouteServiceProvider;

return redirect()->to(RouteServiceProvider::HOME);

```

### Routing

Now that our register feature is done, we'll register the needed routes.

```php
// routes/web.php

use App\Http\Controllers\Auth\RegisterController;
use Illuminate\Support\Facades\Route;

Route::get('/register', [RegisterController::class, 'show'])->name('register');
Route::post('/register', [RegisterController::class, 'handle'])->name('register');
```

### Views

Finally, create an auth.register view with a form. For example:

```php
<!-- resources/views/auth/register.blade.php -->

<h1>Register</h1>

<form  action="{{ route('register') }}"  method="post">
    <!-- Name -->
    <label for="name">Name</label>
    <input type="text" name="name" id="name"  />

    <!-- Email-->
    <label for="email">Email</label>
    <input type="email" name="email" id="email"  />

    <!-- Password -->
    <label for="password">Password</label>
    <input type="password" name="password" id="password"  />

    <!-- Confirm password -->
    <label for="password_confirmation">Confirm password</label>
    <input type="password" name="password_confirmation"  id="password_confirmation" />

    <!-- Submit button -->
    <button type="submit">Register</button>
</form>

```

Registering in Laravel is one of the easiest features. If you at some point didn't follow the tutorial, here's the completed RegisterController:

```php

<?php

// app/Http/Controller/Auth/RegisterController.php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class RegisterController extends Controller
{
    public function show()
    {
        return  view('auth.register');
    }

    public function handle()
    {
        request()->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'password' => ['required', 'string', 'min:8', 'confirmed']
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
```

## Signing in and out

### Signing in

For this tutorial, we'll create an Auth\LoginController for the login functionality.

```php
// app/Http/Controllers/Auth/LoginController.php

use App\Http\Controllers\Controller;

class LoginController extends Controller
{
    public function show()
    {
        return view('auth.login');
    }

    public function handle()
    {
        // Signing in...
    }
}
```

Now we'll use the Auth facade to try to sign in the user:

```php
// app/Http/Controllers/Auth/LoginController.php

$success = auth()->attempt([
    'email' => request('email'),
    'password' => request('password')
], request()->has('remember'));
```

Note: after calling `auth()->attempt()` the user is automatically signed in.

In this case, I assume you have a "remember me" checkbox. If that is checked, it will be in the request, and if not it won't. You can also hardcode true or false if you don't have such a checkbox.

Next, you probably want to redirect the users after a successful login and show errors if it failed.

```php
// app/Http/Controllers/Auth/LoginController.php

use App\Providers\RouteServiceProvider;

if($success) {
    return redirect()->to(RouteServiceProvider::HOME);
}

return back()->withErrors([
    'email' => 'The provided credentials do not match our records.',
]);

```

### Views

Next, create an auth.login view and add a form, for example:

```php
<!-- resources/views/auth/login.blade.php -->

<h1>Login</h1>

<form  action="{{ route('login') }}"  method="post">
    @csrf

    <!-- Email-->
    <label for="email">Email</label>
    <input type="email" name="email" id="email"  />

    <!-- Password -->
    <label for="password">Password</label>
    <input type="password" name="password" id="password"  />

    <!-- Submit button -->
    <button type="submit">Login</button>
</form>
```

### Routing

The last step is to add login routes:

```php
// routes/web.php

use App\Http\Controllers\Auth\LoginController;
use Illuminate\Support\Facades\Route;

Route::get('/login', [LoginController::class, 'show'])
    ->name('login');

Route::post('/login', [LoginController::class, 'handle'])
    ->name('login');
```

And now you're done! Users can now login!

Finished controller

If something went too quickly, here is the full finished LoginController:

```php
<?php

// app/Http/Controllers/Auth/LoginController.php

namespace App\Http\Controllers\Auth\LoginController;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;

class LoginController extends Controller
{
    public function show()
    {
        return view('auth.login');
    }

    public function handle()
    {
        $success = auth()->attempt([
            'email' => request('email'),
            'password' => request('password')
        ], request()->has('remember'));

        if($success) {
            return redirect()->to(RouteServiceProvider::HOME);
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ]);
    }
}
```

### Signing out

Signing out is really simple:

```php
auth()->logout();
```

If you want it in a controller, you can use one similar to this one:

```php
<?php

// app/Http/Controllers/Auth/LogoutController.php

namespace App\Http\Controllers\Auth\LogoutController;

use App\Http\Controllers\Controller;

class LogoutController extends Controller
{
    public function handle()
    {
        auth()->logout();

        return redirect()->route('login');
    }
}
```

### Routing

Next, add the route:

```php
// routes/web.php

use App\Http\Controllers\Auth\LogoutController;
use Illuminate\Support\Facades\Route;

Route::post('/logout', [LogoutController::class, 'handle'])
    ->name('logout');
```

### Views

And finally, add this in your view:

```php
<!-- resources/views/layouts/app.blade.php -->

<form action="{{ route('logout') }}" method="post">
    @csrf

    <button type="submit">Logout</button>
</form>
```

## Password confirmation

First we'll create a controller to load a view:

```php
// app/Http/Controllers/Auth/PasswordConfirmationController.php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;

class PasswordConfirmationController extends Controller
{
    public function show()
    {
        return view('auth.confirm-password');
    }

    public function handle()
    {
        // Handling the response
    }
}
```

### Routing

Next, we'll add routes:

```php
// routes/web.php

use App\Http\Controllers\Auth\PasswordConfirmationController;
use Illuminate\Support\Facades\Route;

Route::get('/confirm-password', [PasswordConfirmationController::class, 'show'])
    ->middleware('auth')
    ->name('password.confirm');

Route::post('/confirm-password', [PasswordConfirmationController::class, 'handle'])
    ->middleware('auth')
    ->name('password.confirm');
```

### Views

After routing, we create a form for the user to fill in their password. For example:

```php
<!-- resources/views/auth/confirm-password.blade.php -->

<h1>Confirm Password</h1>

<form  action="{{ route('password.confirm') }}" method="post">
    @csrf

    <!-- Password -->
    <label for="password">Password</label>
    <input type="password" name="password" id="password"  />

    <!-- Submit button -->
    <button type="submit">Confirm Password</button>
</form>
```

### Controller logic

Finally, we'll add some code to the handle method:

First, we check if the password is correct:

```php
// app/Http/Controllers/Auth/PasswordConfirmationController.php

use Illuminate\Support\Facades\Hash;

if (!Hash::check(request()->password, auth()->user()->password)) {
    return back()->withErrors(['password' => 'The provided password does not match our records.']);
}
```

If the password was correct, we will tell Laravel that the password was correct.

```php
// app/Http/Controllers/Auth/PasswordConfirmationController.php

session()->passwordConfirmed();
Finally, we will redirect the user as intented after a success.
// app/Http/Controllers/Auth/PasswordConfirmationController.php

return redirect()->intended();
```

Where as registering and signing in and out does not use much of Laravel's authentication features, confirming a password does. However, you still have a lot of freedom as to how you want to implement it.

This is the finished `Auth\PasswordConfirmationController`:

```php
<?php

// app/Http/Controller/Auth/PasswordConfirmationController.php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
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
```
## Laravel: email verification

### Preparation

Before adding the verification functionality, we first have to prepare the User model.

Add `MustVerifyEmail` in your User model:

```php
// app/Models/User.php

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable implements MustVerifyEmail
{
    //
}
```

Next, verify that the Registered event is dispatched after registering:

```php
// app/Http/Controllers/Auth/RegisterController.php

use App\Http\Controllers\Controller;
use Illuminate\Auth\Events\Registered;

class RegisterController extends Controller
{
    public function show()
    {
        //
    }

    public function handle()
    {
        //

        event(new Registered($user));
    }
}
```

### Getting started

Now that we've done the preparation, we can get started.

The email verification feature exists of three parts:

A page to tell the user that they have to verify their email address

A button where a user can click to request another link

A route to verify the email address after clicking on a button in an email

1. A page to tell the user that they have to verify their email address

First, we'll create a controller called `Auth\EmailVerificationController`:

```php

// app/Http/Controllers/Auth/EmailVerificationController.php

use App\Http\Controllers\Controller;

class EmailVerificationController extends Controller
{
    public function show()
    {
        return view('auth.verify-email');
    }
}
```

Next, we'll create a view to tell the user that they have to verify their email address. For example:

```php
<!-- resources/views/auth/verify-email.blade.php -->

<h1>Verify email</h1>

<p>Please verify your email address by clicking the link in the mail we just sent you. Thanks!</p>
```

Finally, we'll add the necessary route:

```php
// routes/web.php

use Illuminate\Support\Facades\Route;

Route::get('/verify-email', [EmailVerificationController::class, 'show'])
    ->middleware('auth')
    ->name('verification.notice'); // <-- don't change the route name
```

2. A button where a user can click to request another link

In case the user can't find the link anymore, or it has expired, the user should be able to request another link.

First, we'll add the logic in the `EmailVerificationController`:

```php
// app/Http/Controllers/Auth/EmailVerificationController.php

use App\Http\Controllers\Controller;

class EmailVerificationController extends Controller
{
    public function request()
    {
        auth()->user()->sendEmailVerificationNotification();

        return back()
            ->with('success', 'Verification link sent!');
    }
}
```

Next, we'll add a form in our view to allow the user to request another link:

```php
<!-- resources/views/auth/verify-email.blade.php -->

<form action="{{ route('verification.request') }}" method="post">
    <button type="submit">Request a new link</button>
</form>
```

And finally, we'll add the necessary route to make this work:

```php
// routes/web.php

use Illuminate\Support\Facades\Route;

Route::post('/verify-email/request', [EmailVerificationController::class, 'request'])
    ->middleware('auth')
    ->name('verification.request');
```

3. A route to verify the email address after clicking on a button in an email

The last and most important step is to allow the user to click the link in the email we sent.


As always, we'll first add the controller method:

```php
// app/Http/Controllers/Auth/EmailVerificationController.php

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\EmailVerificationRequest;

class EmailVerificationController extends Controller
{
    public function verify(EmailVerificationRequest $request)
    {
        $request->fulfill();

        return redirect()->to('/home'); // <-- change this to whatever you want
    }
}
```

Afterward, we'll add the routing:

```php
// routes/web.php

use Illuminate\Support\Facades\Route;

Route::post('/verify-email/{id}/{hash}', [EmailVerificationController::class, 'verify'])
    ->middleware(['auth', 'signed']) // <-- don't remove "signed"
    ->name('verification.verify'); // <-- don't change the route name
```

### Protecting routes

For every route that you want to protect from unverified users, add the verified middleware. For example:

```php
// routes/web.php

use Illuminate\Support\Facades\Route;

Route::post('/posts', [PostController::class, 'create'])
    ->middleware(['auth', 'verified']) // <!-- add the "verified" middleware
    ->name('posts.create');
```




