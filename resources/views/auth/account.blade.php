@extends('layout')

@section('content')

<x-favorites :favorites="$favorites"/>


@if (auth()->user()->user_type === 'zakelijke_verkoper')
    @include('account-page.zakelijke-verkoper')
@elseif (auth()->user()->user_type === 'gebruiker')
    @include('account-page.gebruiker')
@elseif (auth()->user()->user_type === 'particuliere_verkoper')
    @include('account-page.particuliere-verkoper')
@endif

<div class="my-20">
    <form action="{{ route('logout') }}" method="POST">
        @csrf
        <button type="submit" style="background-color: red; color: white; padding: 10px 20px; border: none; border-radius: 5px;">{!!__('Log out')!!}</button>
    </form>
</div>
@endsection
