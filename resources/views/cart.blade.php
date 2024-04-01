@extends('layout')

@section('content')
<div class="mx-auto mt-10 w-3/4 bg-white rounded-lg shadow-md p-6">
    <h1 class="text-3xl font-semibold mb-6">{!!__('Shopping cart')!!}</h1>
    @if (count($cartItems) > 0)
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        @foreach ($cartItems as $item)
        <div class="flex flex-col justify-between items-center border border-gray-200 rounded-lg p-4 bg-gray-50">
            <div class="text-center">
                <p class="font-semibold">{{$item->title}}</p>
                <p class="text-gray-600 text-sm">€{{$item->price}}</p> 
            </div>
            <form action="{{route('cart/remove')}}" method="POST">
                @csrf
                <input type="hidden" name="item" value="{{$item}}">
                <button type="submit" class="text-red-500 hover:text-red-700 mt-2">{!!__('Delete')!!}</button>
            </form>
        </div>
        @endforeach
    </div>
    <div class="flex justify-between mt-6">
        @php
            $total = 0;
            foreach ($cartItems as $item) {
                $total += $item->price;
            }
        @endphp
        <p class="text-lg">{!!__('Total')!!}: €{{number_format($total, 2)}}</p>
    <form action="{{route('cart/checkout')}}" method="POST">
        @csrf
        <button type="submit" id="checkout" class="col-span-4 bg-blue-500 text-white px-4 py-2 rounded-md hover:bg-blue-600 mt-4">{!!__('Check out')!!}</button>
    </form>
    @else
    <p class="text-lg text-center">{!!__('Your shopping cart is empty')!!}.</p>
    @endif
</div>
@endsection
