@extends('layout')

@section('content')

@if (!$listing->active)
    <div class="mx-36 mt-20 bg-yellow-500 text-white p-6 rounded-lg shadow-md">
        <p>{!!__('This listing is not active')!!}</p>
    </div>
@endif

<div class="mx-36 mt-20">
    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="font-bold text-4xl">{{$listing->title}}</h1>
                <p class="text-2xl font-semibold text-gray-900 dark:text-white">€{{$listing->price}}</p>
            </div>
            <div class="flex justify-end [&>form]:ml-2">
                @if (Auth::check())
                    <form action="{{route('account/add-favorite')}}" method="POST">
                        @csrf
                        <input type="hidden" name="listing_id" value="{{$listing->id}}">
                        <button type="submit" name="add_favorite" class="bg-red-500 text-white px-4 py-2 rounded-lg w-full hover:bg-red-600 focus:outline-none focus:ring-4 focus:ring-red-300"><i class="fas fa-heart text-xl"></i></button>
                    </form>
                @endif
                @if (Auth::check() && Auth::user()->user_type === 'gebruiker' && $listing->active)
                    <form action="{{route('listing/add-to-cart')}}" method="POST">
                        @csrf
                        <input type="hidden" name="listing_id" value="{{$listing->id}}">
                        <button type="submit" id="add_to_cart" class="bg-blue-500 text-white px-4 py-2 rounded-lg w-full hover:bg-blue-600 focus:outline-none focus:ring-4 focus:ring-blue-300"><i class="fas fa-shopping-cart text-xl"></i></button>
                        </form>
                @endif
            </div>
        </div>
        <div class="flex flex-col items-start">
            <img src="{{url('/images/'.$listing->image)}}" alt="Advertentie foto" class="max-h-96 mt-4">
            <div class="bg-white rounded-lg shadow-md p-6 mt-5">
                <p><i class="fa-regular fa-user"></i>
                @if (!$listing->company_id == null)
                    <a href="{{ route('page.show', $listing->company->slug) }}">{{$listing->company->name}}</a>
                @else
                    {{$listing->user->email}}
                @endif
                </p>
            </div>
        </div>
        
        <p class="pt-5">{{$listing->description}}</p>
    </div>
</div>

@if($listing->bidding_allowed)
<div class="mx-36 mt-20 bg-white rounded-lg shadow-md p-6">
    <h2 class="font-bold text-2xl pb-3">{!!__('Bids')!!}</h2>
    <div class="bg-gray-100 p-5 rounded-lg">
        @foreach ($listing->bids->sortByDesc('bid') as $bid)
            <div class="flex flex-col justify-between items-center border-b pb-3 mb-3 bg-white rounded-md">
                <p class="text-lg font-semibold">€{{$bid->bid}}</p>
                @if (Auth::check() && $bid->user_id === Auth::user()->id)
                    <form action="{{route('listing/delete-bid')}}" method="POST">
                        @csrf
                        <input type="hidden" name="listing_id" value="{{$listing->id}}">
                        <input type="hidden" name="bid_id" value="{{$bid->id}}">
                        <button type="submit" class="text-red-500 hover:text-red-700 mt-2">{!!__('Retract')!!}</button>
                    </form>
                @endif
            </div>
        @endforeach
        @if (Auth::check())
            <div class="mt-5">
                <form action="{{route('listing/bid')}}" method="POST">
                    @csrf
                    <input type="hidden" name="listing_id" value="{{$listing->id}}">
                    <label for="bid" class="block mb-2 font-semibold">Bod toevoegen:</label>
                    <input type="number" name="bid" id="bid" class="p-2 border rounded-md w-full" required>
                    <button type="submit" class="mt-2 bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">{{__('Place Bid')}}</button>
                </form>
            </div>
        @else
            <p>{!!__('Please login to place a bid')!!}</p>
        @endif
    </div>
</div>
@endif


<div class="mx-36 mt-20 bg-white rounded-lg shadow-md p-6">
    <h2 class="font-bold text-2xl pb-3">{!!__('Reviews')!!}</h2>
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
                        <button type="submit" class="text-red-500 hover:text-red-700">{!!__('Delete')!!}</button>
                    </form>
                @endif
            </div>
        @endforeach
    </div>
</div>


<div class="mx-36 mt-20 bg-white rounded-lg shadow-md p-6">
    <h2 class="font-bold text-2xl pb-3">{!!__('Share this listing')!!}</h2>
    <img src="{{$qrCode}}" alt="QR Code">
</div>

@if (auth()->check() && auth()->user()->user_type === 'gebruiker')
<div class="mx-36 mt-20 bg-white rounded-lg shadow-md p-6">
    <h2 class="font-bold text-2xl pb-3">{!!__('Post a review')!!}</h2>
    <form action="{{route('listing/review')}}" method="POST">
        @csrf
        <input type="hidden" name="listing_id" value="{{$listing->id}}">
        <div class="flex flex-col gap-4">
            <div>
                <label for="rating" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">{!!__('Rating')!!}</label>
                <select id="rating" name="rating" class="bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" required="">
                    <option value="1">1</option>
                    <option value="2">2</option>
                    <option value="3">3</option>
                    <option value="4">4</option>
                    <option value="5">5</option>
                </select>
            </div>
            <div>
                <label for="review" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">{!!__('Review')!!}</label>
                <textarea id="review" name="review" class="bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" required=""></textarea>
            </div>
            <button type="submit" id="submit_review" class="bg-blue-500 w-full text-white bg-primary-600 hover:bg-primary-700 focus:ring-4 focus:outline-none focus:ring-primary-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-primary-600 dark:hover:bg-primary-700 dark:focus:ring-primary-800">{!!__('Post')!!}</button>
        </div>
</div>
@endif
@endsection