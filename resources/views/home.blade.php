@extends('layout')

@section('content')
@include('partials._hero')

<h2 class="text-center font-bold pb-10">{!!__('Latest advertisements')!!}</h2>

<div class="mb-5">
    <form action="{{ route('search') }}" method="GET" class="w-full flex justify-center">
        <input type="text" name="search" class="w-1/3 border-2 border-gray-300 p-2 rounded-xl" placeholder="{!!__('Search for a listing')!!}" value="{{ request('search') }}">
        <select name="sort_by" class="ml-3 border-2 border-gray-300 p-2 rounded-xl">
            <option value="updated_at_desc" {{ request('sort_by') == 'updated_at_desc' ? 'selected' : '' }}>{!!__('Sort by latest')!!}</option>
            <option value="updated_at_asc" {{ request('sort_by') == 'updated_at_asc' ? 'selected' : '' }}>{!!__('Sort by oldest')!!}</option>
            <option value="price_asc" {{ request('sort_by') == 'price_asc' ? 'selected' : '' }}>{!!__('Sort by price: low to high')!!}</option>
            <option value="price_desc" {{ request('sort_by') == 'price_desc' ? 'selected' : '' }}>{!!__('Sort by price: high to low')!!}</option>
        </select>
        <input type="text" name="min_price" class="ml-3 w-1/6 border-2 border-gray-300 p-2 rounded-xl" placeholder="{!!__('Minimum price')!!}" value="{{ request('min_price') }}">
        <input type="text" name="max_price" class="ml-3 w-1/6 border-2 border-gray-300 p-2 rounded-xl" placeholder="{!!__('Maximum price')!!}" value="{{ request('max_price') }}">
        <button type="submit" class="ml-3 bg-blue-500 text-white p-2 rounded-xl">{!!__('Search')!!}</button>
    </form>
</div>

<div class="text-center grid grid-cols-3 justify-items-center gap-4">
    @foreach ($listings->sortByDesc('updated_at') as $listing)
        <x-listing-card :listing="$listing"/>
    @endforeach
</div>

<div class="my-5">
    {{$listings->links()}}
</div>

@endsection
