@props(['favorites'])

<div class="container">
    <h2 class="text-2xl font-bold mb-4">{!!__('Favourites')!!}</h2>
    <div class="flex flex-wrap">
        @foreach ($favorites as $favorite)
            <div class="w-full sm:w-1/2 md:w-1/3 lg:w-1/4 px-4 mb-4">
                <div class="bg-white rounded-lg shadow-md overflow-hidden">
                    <img class="w-full h-40 object-cover object-center" src="{{url('images/'.$favorite->image)}}" alt="{{$favorite->title}}">
                    <div class="p-4 flex justify-between items-center">
                        <p class="text-lg font-semibold">{{$favorite->title}}</p>
                        <form action="{{route('account/remove-favorite')}}" method="POST">
                            @csrf
                            <input type="hidden" name="listing_id" value="{{$favorite->pivot->listing_id}}">
                            <button type="submit" name="remove_favorite" value="Verwijderen" class="bg-red-500 text-white px-4 py-2 rounded-lg w-full hover:bg-red-600 focus:outline-none focus:ring-4 focus:ring-red-300">{!!__('Delete')!!}</button>
                        </form>
                    </div>
                </div>
            </div>
        @endforeach
        @if (count($favorites) === 0)
            <p>{!!__('You don\'t have any favourite listings yet')!!}.</p>
        @endif
    </div>
</div>