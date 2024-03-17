@props(['purchases'])

<div class="container">
    <h2 class="text-2xl font-bold mb-4">{!!__('Purchases')!!}</h2>
    <div class="flex flex-wrap">
        @foreach ($purchases->sortByDesc(function ($purchase) {
            return $purchase->pivot->created_at;
        }) as $purchase)
            <div class="w-full sm:w-1/2 md:w-1/3 lg:w-1/4 px-4 mb-4">
                <div class="bg-white rounded-lg shadow-md overflow-hidden">
                    <img class="w-full h-40 object-cover object-center" src="{{url('images/'.$purchase->image)}}" alt="{{$purchase->title}}">
                    <div class="p-4 flex justify-between items-center">
                        <p class="text-lg font-semibold">{{$purchase->title}}</p>
                        <p>{{ $purchase->pivot->created_at->format('Y-m-d') }}</p>
                    </div>
                </div>
            </div>
        @endforeach
        @if (count($purchases) === 0)
            <p>{!!__('You don\'t have any purchases yet')!!}.</p>
        @endif
    </div>
</div>