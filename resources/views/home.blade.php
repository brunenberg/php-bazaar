@extends('layout')

@section('content')
@include('partials._hero')

<h2 class="text-center font-bold pb-10">{{__('Latest advertisements') }}</h2>
<div class="text-center grid grid-cols-3 justify-items-center mx-36">
    
    <x-listing-card />
    <x-listing-card />
    <x-listing-card />

    @if(auth()->check())
    @if(auth()->user()->user_type === 'gebruiker')
        <p>Welcome, {{ auth()->user()->email }}! You are a regular user.</p>
    @elseif(auth()->user()->user_type === 'zakelijke_verkoper')
        <p>Welcome, {{ auth()->user()->email }}! You are a business seller.</p>
    @endif
    @else
        <p>Please sign in to see more content.</p>
    @endif
</div>




@endsection