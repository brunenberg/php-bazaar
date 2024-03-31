@extends('layout')

@section('content')
<div class="calendar p-4">
    <div class="header flex justify-center items-center mb-4">
        <a href="{{ route('agenda', ['month' => $previousMonth, 'year' => $currentYear]) }}" class="arrow">&lt;</a>
        <h2 class="text-lg font-semibold mx-5">{{ $currentMonth }}</h2>
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
                                <div class="title">Verkoop:</div>
                                <div class="title">{{ $listing }}</div>
                            </div>
                        @endforeach
                    @endif
                    @if (array_key_exists($day, $boughtListingsSale))
                        @foreach ($boughtListingsSale[$day] as $listing)
                            <div class="event bg-blue-100 p-2 rounded-lg mb-2">
                                <div class="title">Aankoop:</div>
                                <div class="title">{{ $listing }}</div>
                            </div>
                        @endforeach
                    @endif
                    @if (array_key_exists($day, $rentalListings))
                        @foreach ($rentalListings[$day] as $listing)
                            <div class="event bg-green-100 p-2 rounded-lg mb-2">
                                <div class="title">Start verhuur:</div>
                                <div class="title">{{ $listing }}</div>
                            </div>
                        @endforeach
                    @endif
                    @if (array_key_exists($day, $boughtListingsRental))
                        @foreach ($boughtListingsRental[$day] as $listing)
                            <div class="event bg-green-100 p-2 rounded-lg mb-2">
                                <div class="title">Ophalen verhuur:</div>
                                <div class="title">{{ $listing }}</div>
                            </div>
                        @endforeach
                    @endif
                    @if (array_key_exists($day, $returnDates))
                        @foreach ($returnDates[$day] as $listing)
                            <div class="event bg-purple-100 p-2 rounded-lg mb-2">
                                <div class="title">Einde verhuur:</div>
                                <div class="title">{{ $listing['listing']->title }}</div>
                                @php
                                    $dialogId = "return-dialog-$day";
                                @endphp
                                @if (!$listing['listing']->returned($listing['user_id']))
                                <dialog id="{{ $dialogId }}" class="p-10 rounded-lg">
                                    <h3 class="font-bold text-xl mb-5">Upload photo for return:</h3>
                                    <form action="{{ route('return') }}" method="POST" enctype="multipart/form-data">
                                        @csrf
                                        <input type="hidden" name="user_id" value="{{ $listing['user_id'] }}">
                                        <input type="hidden" name="listing_id" value="{{ $listing['listing']->id }}">
                                        <input type="file" name="image" id="return-image-{{ $day }}" accept="image/*">
                                        <button class="btn btn-primary px-2 mt-1 bg-blue-300 rounded-lg" onclick="closeDialog('{{ $dialogId }}')">Upload</button>
                                    </form>
                                    <button class="btn btn-primary px-2 mt-1 bg-red-300 rounded-lg mt-10" onclick="document.getElementById('{{ $dialogId }}').close()">Close</button>
                                </dialog>
                                <button class="btn btn-primary px-2 mt-1 bg-blue-300 rounded-lg" onclick="openDialog('{{ $dialogId }}')">Return</button>
                                @else
                                <p class="px-2 mt-1 bg-green-300 rounded-lg">Returned✔</p>
                                @endif
                            </div>
                        @endforeach
                    @endif
                    @if (array_key_exists($day, $listingDueDates))
                        @foreach ($listingDueDates[$day] as $listing)
                            <div class="event bg-red-100 p-2 rounded-lg mb-2">
                                <div class="title">Adertentie verloopt:</div>
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