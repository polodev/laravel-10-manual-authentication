@extends('layouts.app')
@section('content')
<h1>Reset Password</h1>

@include('partials.errors')

<form  action="{{ route('password.update') }}"  method="post">

    @csrf

    <input type="hidden" name="token" value="{{ $token }}">

    <!-- Email-->
    <label for="email">Email</label>
    <input type="email" name="email" id="email"  />
    <br>

    <!-- Password -->
    <label for="password">Password</label>
    <input type="password" name="password" id="password"  />
    <br>

    <!-- Confirm password -->
    <label for="password_confirmation">Confirm password</label>
    <input type="password" name="password_confirmation"  id="password_confirmation" />
    <br>

    <!-- Submit button -->
    <button type="submit">Submit</button>
</form>
@endsection
