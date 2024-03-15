@extends('layout')

@section('content')
<div class="mx-20 mt-5 p-5 rounded bg-[{{$company->background_color}}] text-[{{$company->text_color}}]">
    <h1 class="font-bold text-3xl">{{$company->name}}</h1>
    

    @foreach ($templates->sortBy('pivot.order') as $template)
        <div>
            @include('company.templates.'.$template->name, ['company' => $company])
        </div>
    @endforeach
</div>

<div class="mx-20 mt-5 p-5 rounded bg-[{{$company->background_color}}] text-[{{$company->text_color}}]">
    <h2 class="font-bold text-2xl pb-3">Reviews</h2>
    <div class="grid grid-cols-4">
        @foreach ($company->reviews as $review)
            <div class="flex flex-col justify-between items-center border-b pb-3 mb-3 bg-white rounded mr-5 text-black">
                <p class="font-bold">{{$review->email}}</p>
                <div class="flex">
                    @for ($i = 0; $i < $review->pivot->rating; $i++)
                        <i class="fas fa-star text-primary-600"></i>
                    @endfor
                </div>
                <p class="text-sm">{{$review->pivot->review}}</p>
                @if (Auth::check() && $review->email === Auth::user()->email)
                    <form action="{{route('company/delete-review')}}" method="POST">
                        @csrf
                        <input type="hidden" name="company_id" value="{{$company->id}}">
                        <button type="submit" class="text-red-500 hover:text-red-700">Delete</button>
                    </form>
                @endif
            </div>
        @endforeach
    </div>
</div>

@if (auth()->user()->user_type === 'gebruiker')
    <div class="mx-20 mt-5 p-5 rounded bg-[{{$company->background_color}}] text-[{{$company->text_color}}]">
        <h2 class="font-bold text-2xl">Schrijf een review</h2>
        <form action="{{route('company/review')}}" method="POST">
            @csrf
            <input type="hidden" name="company_id" value="{{$company->id}}">
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