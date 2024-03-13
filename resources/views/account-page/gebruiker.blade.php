<div class="container">
    <h2 class="text-2xl font-bold mb-4">Favorieten</h2>
    <div class="flex flex-wrap">
        @foreach ($favorites as $favorite)
            <div class="w-full sm:w-1/2 md:w-1/3 lg:w-1/4 px-4 mb-4">
                <div class="bg-white rounded-lg shadow-md overflow-hidden">
                    <img class="w-full h-40 object-cover object-center" src="{{url('images/'.$favorite->image)}}" alt="{{$favorite->title}}">
                    <div class="p-4">
                        <p class="text-lg font-semibold">{{$favorite->title}}</p>
                    </div>
                </div>
            </div>
        @endforeach
        @empty($favorites)
            <p class="w-full text-center">Geen favorieten gevonden.</p>
        @endempty
    </div>
</div>
