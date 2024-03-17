@extends('layout')

@section('content')

@if (auth()->user()->user_type === 'zakelijke_verkoper')
    @include('account-page.zakelijke-verkoper')
@elseif (auth()->user()->user_type === 'gebruiker')
    @include('account-page.gebruiker')
@endif

<x-favorites :favorites="$favorites"/>

<div class="container" style="max-width: 400px;">
    <h2>{!!__('Select language')!!}</h2>
    <form action="{{ route('setlocale') }}" method="POST" style="display: grid; gap: 20px;">
        @csrf
        <div>
            <label for="locale" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">{!!__('Language')!!}</label>
            <select id="locale" name="locale" class="bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" required="">
                <option value="en" {{ app()->getLocale() == 'en' ? 'selected' : '' }}>English</option>
                <option value="nl" {{ app()->getLocale() == 'nl' ? 'selected' : '' }}>Nederlands</option>
            </select>
        </div>
        <button type="submit" class="bg-blue-500 w-full text-white bg-primary-600 hover:bg-primary-700 focus:ring-4 focus:outline-none focus:ring-primary-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-primary-600 dark:hover:bg-primary-700 dark:focus:ring-primary-800">{!!__('Save')!!}</button>
    </form>
</div>

<div class="my-20">
    <form action="{{ route('logout') }}" method="POST">
        @csrf
        <button type="submit" style="background-color: red; color: white; padding: 10px 20px; border: none; border-radius: 5px;">{!!__('Log out')!!}</button>
    </form>
</div>
@endsection
