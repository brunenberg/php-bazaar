@extends('layout')

@section('content')

<form action="{{ route('logout') }}" method="POST">
    @csrf
    <button type="submit" style="background-color: red; color: white;">Uitloggen</button>
</form>

@endsection