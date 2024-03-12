@extends('layout')

@section('content')
@include('partials._hero')

<h2 class="text-center font-bold pb-10">Laatste advertenties</h2>
<div class="text-center grid grid-cols-3 justify-items-center mx-36">
    
    <x-listing-card />
    <x-listing-card />
    <x-listing-card />
</div>


@endsection