@extends('layouts.app')
@section('content')
<h1>Forgot Password</h1>

@include('partials.errors')

<form  action="{{ route('password.email') }}"  method="post">
    @csrf

        <!-- Email-->
    <label for="email">Email</label>
    <input type="email" name="email" id="email"  />
    <br>


    <!-- Submit button -->
    <button type="submit">submit</button>
</form>
@endsection
