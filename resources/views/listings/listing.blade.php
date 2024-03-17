@extends('layout')

@section('content')

<div class="mx-36 mt-20">
    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="flex items-center justify-between">
            <h1 class="font-bold text-4xl">{{$listing->title}}</h1>
            @if (Auth::check())
                <form action="{{route('account/add-favorite')}}" method="POST">
                    @csrf
                    <input type="hidden" name="listing_id" value="{{$listing->id}}">
                    <button type="submit" class="bg-red-500 text-white px-4 py-2 rounded-lg w-full hover:bg-red-600 focus:outline-none focus:ring-4 focus:ring-red-300"><i class="fas fa-heart text-xl"></i></button>
                </form>
            @endif
        </div>
        <img src="{{url('/images/'.$listing->image)}}" alt="Advertentie foto" class="max-h-96 mt-4">
        <p class="pt-5">{{$listing->description}}</p>
    </div>
</div>

<div class="mx-36 mt-20 bg-white rounded-lg shadow-md p-6">
    <h2 class="font-bold text-2xl pb-3">Reviews</h2>
    <div class="grid grid-cols-4">
        @foreach ($listing->reviews as $review)
            <div class="flex flex-col justify-between items-center border-b pb-3 mb-3 bg-white rounded mr-5">
                <p class="font-bold">{{$review->email}}</p>
                <div class="flex">
                    @for ($i = 0; $i < $review->pivot->rating; $i++)
                        <i class="fas fa-star text-primary-600"></i>
                    @endfor
                </div>
                <p class="text-sm">{{$review->pivot->review}}</p>
                @if (Auth::check() && $review->email === Auth::user()->email)
                    <form action="{{route('listing/delete-review')}}" method="POST">
                        @csrf
                        <input type="hidden" name="listing_id" value="{{$listing->id}}">
                        <button type="submit" class="text-red-500 hover:text-red-700">Delete</button>
                    </form>
                @endif
            </div>
        @endforeach
    </div>
</div>

<div class="mx-36 mt-20 bg-white rounded-lg shadow-md p-6">
    <h2 class="font-bold text-2xl pb-3">Deel deze advertentie</h2>
    <img src="{{$qrCode}}" alt="QR Code">
</div>


@if (auth()->check() && auth()->user()->user_type === 'gebruiker')
<div class="mx-36 mt-20 bg-white rounded-lg shadow-md p-6">
    <h2 class="font-bold text-2xl pb-3">Review Plaatsen</h2>
    <form action="{{route('listing/review')}}" method="POST">
        @csrf
        <input type="hidden" name="listing_id" value="{{$listing->id}}">
        <div class="flex flex-col gap-4">
            <div>
                <label for="rating" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Rating</label>
                <select id="rating" name="rating" class="bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" required="">
                    <option value="1">1</option>
                    <option value="2">2</option>
                    <option value="3">3</option>
                    <option value="4">4</option>
                    <option value="5">5</option>
                </select>
            </div>
            <div>
                <label for="review" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Review</label>
                <textarea id="review" name="review" class="bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" required=""></textarea>
            </div>
            <button type="submit" class="bg-blue-500 w-full text-white bg-primary-600 hover:bg-primary-700 focus:ring-4 focus:outline-none focus:ring-primary-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-primary-600 dark:hover:bg-primary-700 dark:focus:ring-primary-800">Plaatsen</button>
        </div>
</div>
@endif
@endsection