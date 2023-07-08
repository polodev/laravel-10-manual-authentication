@extends('layouts.app')
@section('content')
<h1>Confirm Password</h1>

<form  action="{{ route('password.confirm') }}" method="post">
    @csrf

    <!-- Password -->
    <label for="password">Password</label>
    <input type="password" name="password" id="password"  />

    <br>

    <!-- Submit button -->
    <button type="submit">Confirm Password</button>
</form>
@endsection
