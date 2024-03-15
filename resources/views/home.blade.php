@extends('layout')

@section('content')
@include('partials._hero')

<h2 class="text-center font-bold pb-10">{{__('Latest advertisements')}}</h2>
<div class="text-center grid grid-cols-3 justify-items-center gap-4">
    @foreach ($listings->sortByDesc('updated_at') as $listing)
        <x-listing-card :listing="$listing"/>
    @endforeach

    
</div>
<div class="my-5">
    {{$listings->links()}}
</div>





@endsection