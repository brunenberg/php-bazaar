@extends('layout')

@section('content')
<div class="calendar p-4">
    <div class="header flex justify-center items-center mb-4">
        <a href="{{ route('agenda', ['month' => $previousMonth, 'year' => $currentYear]) }}" class="arrow">&lt;</a>
        <h2 class="text-lg font-semibold mx-5">{!! __($currentMonth) !!}</h2>
        <a href="{{ route('agenda', ['month' => $nextMonth, 'year' => $currentYear]) }}" class="arrow">&gt;</a>
    </div>
    <div class="grid grid-cols-7 gap-2">
        <!-- Calendar grid goes here -->
        @for ($day = 1; $day <= $days; $day++)
            <div class="day border border-gray-200 rounded-lg p-2">
                <div class="date text-center">{{ $day }}</div>
                <div class="events mt-2">
                    {{-- If key in $boughtListings contains the $day show block--}}
                    @if (array_key_exists($day, $soldListings))
                        @foreach ($soldListings[$day] as $listing)
                            <div class="event bg-blue-100 p-2 rounded-lg mb-2">
                                <div class="title">{!!__('Sale')!!}:</div>
                                <div class="title">{{ $listing }}</div>
                            </div>
                        @endforeach
                    @endif
                    @if (array_key_exists($day, $boughtListingsSale))
                        @foreach ($boughtListingsSale[$day] as $listing)
                            <div class="event bg-blue-100 p-2 rounded-lg mb-2">
                                <div class="title">{!!__('Purchase')!!}:</div>
                                <div class="title">{{ $listing }}</div>
                            </div>
                        @endforeach
                    @endif
                    @if (array_key_exists($day, $rentalListings))
                        @foreach ($rentalListings[$day] as $listing)
                            <div class="event bg-green-100 p-2 rounded-lg mb-2">
                                <div class="title">{!!__('Start rental')!!}:</div>
                                <div class="title">{{ $listing }}</div>
                            </div>
                        @endforeach
                    @endif
                    @if (array_key_exists($day, $boughtListingsRental))
                        @foreach ($boughtListingsRental[$day] as $listing)
                            <div class="event bg-green-100 p-2 rounded-lg mb-2">
                                <div class="title">{!!__('Pick up rental')!!}:</div>
                                <div class="title">{{ $listing }}</div>
                            </div>
                        @endforeach
                    @endif
                    @if (array_key_exists($day, $returnDates))
                        @foreach ($returnDates[$day] as $listing)
                            <div class="event bg-purple-100 p-2 rounded-lg mb-2">
                                <div class="title">{!!__('End of rental')!!}:</div>
                                <div class="title">{{ $listing['listing']->title }}</div>
                                @php
                                    $dialogId = "return-dialog-$day";
                                @endphp
                                @if (!$listing['listing']->returned($listing['user_id']))
                                <dialog id="{{ $dialogId }}" class="p-10 rounded-lg">
                                    <h3 class="font-bold text-xl mb-5">{!!__('Upload photo for return')!!}:</h3>
                                    <form action="{{ route('return') }}" method="POST" enctype="multipart/form-data">
                                        @csrf
                                        <input type="hidden" name="user_id" value="{{ $listing['user_id'] }}">
                                        <input type="hidden" name="listing_id" value="{{ $listing['listing']->id }}">
                                        <input type="file" name="image" id="return-image-{{ $day }}" accept="image/*">
                                        <button class="btn btn-primary px-2 mt-1 bg-blue-300 rounded-lg" onclick="closeDialog('{{ $dialogId }}')">{!!__('Upload')!!}</button>
                                    </form>
                                    <button class="btn btn-primary px-2 mt-1 bg-red-300 rounded-lg mt-10" onclick="document.getElementById('{{ $dialogId }}').close()">{!!__('Close')!!}</button>
                                </dialog>
                                <button class="btn btn-primary px-2 mt-1 bg-blue-300 rounded-lg" onclick="openDialog('{{ $dialogId }}')">{!!__('Return')!!}</button>
                                @else
                                <button class="px-2 mt-1 bg-green-300 rounded-lg" onclick="openDialog('{{ $dialogId }}')">{!!__('Returned')!!}✔</button>
                                    @php
                                        $returnImage = $listing['listing']->returnImage($listing['user_id'], $listing['listing']->id);
                                    @endphp
                                <dialog id="{{ $dialogId }}" class="p-10 rounded-lg">
                                    <img src="{{ url('images/returns/' . $returnImage) }}" alt="">
                                    <button class="btn btn-primary px-2 mt-1 bg-red-300 rounded-lg mt-10" onclick="document.getElementById('{{ $dialogId }}').close()">Close</button>
                                </dialog>
                                @endif
                            </div>
                        @endforeach
                    @endif
                    @if (array_key_exists($day, $listingDueDates))
                        @foreach ($listingDueDates[$day] as $listing)
                            <div class="event bg-red-100 p-2 rounded-lg mb-2">
                                <div class="title">{!!__('Listing expires')!!}:</div>
                                <div class="title">{{ $listing }}</div>
                            </div>
                        @endforeach
                    @endif
                </div>
            </div>
        @endfor
    </div>
</div>

<script>
    function openDialog(dialogId) {
        var dialog = document.getElementById(dialogId);
        if (dialog) {
            dialog.showModal();
        }
    }

    function closeDialog(dialogId) {
        var dialog = document.getElementById(dialogId);
        if (dialog) {
            dialog.close();
        }
    }
</script>

@endsection