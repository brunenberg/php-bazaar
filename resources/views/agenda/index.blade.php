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
                                <div class="title">Verkoop:</div>
                                <div class="title">{{ $listing }}</div>
                            </div>
                        @endforeach
                    @endif
                    @if (array_key_exists($day, $rentalListings))
                        @foreach ($rentalListings[$day] as $listing)
                            <div class="event bg-green-100 p-2 rounded-lg mb-2">
                                <div class="title">Verhuur:</div>
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
                                <div class="title">Ontvangen:</div>
                                <div class="title">{{ $listing }}</div>
                            </div>
                        @endforeach
                    @endif
                </div>
            </div>
        @endfor
    </div>
</div>



@endsection