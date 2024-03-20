<div>
    <h2 class="text-2xl font-bold mt-8">{!!__('Bids')!!}:</h2>
    <div class="mb-10 max-h-48 overflow-y-auto">
        <table class="w-full mt-4">
            <thead>
                <tr>
                    <th class="text-left">{!!__('Bid')!!}</th>
                    <th class="text-left">{!!__('User')!!}</th>
                    <th class="text-left">{!!__('Listing')!!}</th>
                    <th class="text-left">{!!__('Date')!!}</th>
                    <th class="text-left">{!!__('Actions')!!}</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($bids->sortByDesc('created_at') as $bid)
                    <tr>
                        <td>€{{$bid->bid}}</td>
                        <td>{{$bid->user->email}}</td>
                        <td><a href="{{ route('listing.show', $bid->listing->id) }}" class="text-blue-500 hover:underline">{{$bid->listing->title}}</a></td>
                        <td>{{$bid->created_at}}</td>
                        <td class="flex">
                            <form action="{{route('listing/accept-bid')}}" method="POST">
                                @csrf
                                <input type="hidden" name="bidId" value="{{$bid->id}}">
                                <input type="hidden" name="listingId" value="{{$bid->listing_id}}">
                                <button type="submit" class="bg-green-500 text-white px-4 py-2 rounded-md hover:bg-green-600">{!!__('Accept')!!}</button>
                            </form>
                            <form action="{{route('listing/decline-bid')}}" method="POST">
                                @csrf
                                <input type="hidden" name="bidId" value="{{$bid->id}}">
                                <input type="hidden" name="listingId" value="{{$bid->listing_id}}">
                                <button type="submit" class="bg-red-500 text-white px-4 py-2 rounded-md hover:bg-red-600">{!!__('Decline')!!}</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>