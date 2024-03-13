@extends('layout')

@section('content')

<form action="{{ route('logout') }}" method="POST">
    @csrf
    <button type="submit" style="background-color: red; color: white; padding: 10px 20px; border: none; border-radius: 5px;">Uitloggen</button>
</form>

@if (auth()->user()->user_type === 'zakelijke_verkoper')

    @include('account-page.zakelijke-verkoper')
    
@endif

@endsection