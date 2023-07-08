@extends('layouts.app')
@section('content')
Home - you are logged in as {{auth()->user()->name}}
<br>
<form action="{{ route('logout') }}" method="post">
  @csrf
  <button type="submit">Logout</button>
</form>
@endsection
