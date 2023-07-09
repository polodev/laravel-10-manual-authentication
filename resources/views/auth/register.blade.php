@extends('layouts.app')
@section('content')
<h1>Register</h1>

@include('partials.errors')

<form  action="{{ route('register') }}"  method="post">
    @csrf
    <!-- Name -->
    <label for="name">Name</label>
    <input type="text" name="name" id="name"  />
    <br>

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
    <button type="submit">Register</button>
</form>
@endsection
