@extends('layout')

@section('content')

<div class="mx-36 mt-20">
    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="flex items-center justify-between">
            <h1 class="font-bold text-4xl">{{$listing->title}}</h1>
            <form action="{{route('account/add-favorite')}}" method="POST">
                @csrf
                <input type="hidden" name="listing_id" value="{{$listing->id}}">
                <button type="submit" class="bg-red-500 text-white px-4 py-2 rounded-lg w-full hover:bg-red-600 focus:outline-none focus:ring-4 focus:ring-red-300"><i class="fas fa-heart text-xl"></i></button>
            </form>
        </div>
        <img src="{{url('/images/'.$listing->image)}}" alt="Advertentie foto" class="max-h-96 mt-4">
        <p class="pt-5">{{$listing->description}}</p>
    </div>
</div>


@endsection