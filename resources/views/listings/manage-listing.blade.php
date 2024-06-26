@extends('layout')

@section('content')
<form action="{{ isset($listing) ? route('update-listing', $listing->id) : route('create-listing') }}" class="mt-4" method="POST" enctype="multipart/form-data">
    @csrf
    @if(isset($listing))
        @method('PUT')
    @endif
    <div class="mt-4">
        <label for="title" class="block">{!!__('Title')!!}:</label>
        <input name="title" type="text" value="{{ old('title', $listing->title ?? '') }}" class="mt-1 p-2 border rounded-md w-full">
    </div>

    <div class="mt-4">
        <label for="description" class="block">{!!__('Description')!!}:</label>
        <textarea name="description" class="mt-1 p-2 border rounded-md w-full h-24">{{ old('description', $listing->description ?? '') }}</textarea>
    </div>

    <div class="mt-4">
        <label for="type" class="block">{!!__('Type')!!}:</label>
        <select name="type" id="type" class="mt-1 p-2 border rounded-md w-full">
            <option value="" {{ (old('type', $listing->type ?? '') == '') ? 'selected' : '' }} disabled>{!!__('Select a type')!!}</option>
            {{-- Verkoop option --}}
            <option value="verkoop" {{ (old('type', $listing->type ?? '') == 'verkoop') ? 'selected' : '' }}>{!!__('Sale')!!}</option>
            <option value="verhuur" {{ (old('type', $listing->type ?? '') == 'verhuur') ? 'selected' : '' }}>{!!__('Rental')!!}</option>
        </select>
    </div>
    
    <div class="mt-4" id="bidding_allowed_div" style="display: {{ (old('type', $listing->type ?? '') == 'verkoop') ? 'block' : 'none' }}">
        <label for="bidding_allowed" class="block">{!!__('Bids allowed')!!}:</label>
        <input name="bidding_allowed" id="bidding_allowed" type="checkbox" value="1" {{ (old('bidding_allowed', $listing->bidding_allowed ?? '') == '1') ? 'checked' : '' }}>
    </div>

    <div class="mt-4" id="rental_days_div" style="display: {{ (old('type', $listing->type ?? '') == 'verhuur') ? 'block' : 'none' }}">
        <label for="rental_days" class="block">{!!__('Rental period')!!}:</label>
        <input name="rental_days" type="number" value="{{ old('rental_days', $listing->rental_days ?? '') }}" class="mt-1 p-2 border rounded-md w-full">

        <label for="wear_speed" class="block">{!!__('Wear speed')!!}:</label>
        <select name="wear_speed" class="mt-1 p-2 border rounded-md w-full">
            <option value="" {{ (old('type', $listing->wear_speed ?? '') == '') ? 'selected' : '' }} disabled>{!!__('Select speed')!!}</option>
            <option value="slow" {{ (old('type', $listing->wear_speed ?? '') == '1') ? 'selected' : '' }}>{!!__('Slow')!!}</option>
            <option value="normal" {{ (old('type', $listing->wear_speed ?? '') == '2') ? 'selected' : '' }}>{!!__('Average')!!}</option>
            <option value="fast" {{ (old('type', $listing->wear_speed ?? '') == '3') ? 'selected' : '' }}>{!!__('Fast')!!}</option>
        </select>
    </div>

    <script>
        document.getElementById('type').addEventListener('change', function() {
            var biddingAllowedDiv = document.getElementById('bidding_allowed_div');
            var rentalDaysDiv = document.getElementById('rental_days_div');
            if (this.value === 'verkoop') {
                biddingAllowedDiv.style.display = 'block';
            } else {
                biddingAllowedDiv.style.display = 'none';
            }

            if (this.value === 'verhuur') {
                document.getElementById('bidding_allowed').checked = false;
                rentalDaysDiv.style.display = 'block';
            } else {
                rentalDaysDiv.style.display = 'none';
            }
        });
    </script>
    
    <div class="mt-4">
        <label for="price" class="block">{!!__('Price')!!}:</label>
        <input name="price" type="number" step="any" value="{{ old('price', $listing->price ?? '') }}" class="mt-1 p-2 border rounded-md w-full">
    </div>

    <div class="mt-4">
        <label for="image" class="block">{!!__('Image')!!}:</label>
        <input name="image" type="file" accept=".jpeg,.jpg,.png,.gif,.svg" class="mt-1 p-2 border rounded-md w-full">
    </div>

    <button type="submit" id="save_listing" class="mt-8 bg-blue-500 text-white px-4 py-2 rounded-md hover:bg-blue-600">{!!__('Save')!!}</button>
</form>

@endsection
