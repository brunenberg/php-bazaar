@extends('layout')

@section('content')
<div class="mt-4 container mx-auto">
    <h1 class="text-2xl font-bold mb-4">{!!__('Your listings')!!}</h1>
    <a href="{{ route('create-listing-form') }}" id="create_new" class="mt-4 inline-block px-4 py-2 bg-green-500 text-white rounded-md hover:bg-green-600">{!!__('Create new listing')!!}</a>
    <form action="{{ route('upload-csv') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <input type="file" name="csv_file" accept=".csv">
        <button type="submit" class="mt-4 inline-block px-4 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-600">{!!__('Upload CSV')!!}</button>
    </form>
    <a href="{{ asset('pdfs/listings_documentation_' . auth()->user()->language . '.pdf') }}" download class="mt-4 inline-block px-4 py-2 bg-blue-200 text-black rounded-md hover:bg-blue-300">{!!__('Download CSV documentation')!!}</a>
    <a href="{{ asset('csvs/listings_example_' . auth()->user()->language . '.csv') }}" download class="mt-4 ml-2 inline-block px-4 py-2 bg-blue-200 text-black rounded-md hover:bg-blue-300">{!!__('Download CSV example')!!}</a>
    @if($listings)
        @foreach($listings as $listing)
        <div class="mt-4 p-4 border rounded-md">
            <h2 class="text-xl font-semibold">
                <a href="/listing/{{$listing->id}}" class="text-blue-500 hover:underline">{{ $listing->title }}</a>
            </h2>
            <div class="mt-2 flex space-x-4">
                <a href="{{ route('edit-listing', $listing->id) }}" class="px-4 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-600">{!!__('Edit')!!}</a>
                <form action="{{ route('delete-listing', $listing->id) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="px-4 py-2 bg-red-500 text-white rounded-md hover:bg-red-600">{!!__('Delete')!!}</button>
                </form>
                @if(!$listing->active)
                <form action="{{ route('activate-listing', $listing->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <button type="submit" class="px-4 py-2 bg-green-500 text-white rounded-md hover:bg-green-600">{!!__('Publish')!!}</button>
                </form>
                @else
                <form action="{{ route('deactivate-listing', $listing->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <button type="submit" class="px-4 py-2 bg-yellow-500 text-white rounded-md hover:bg-yellow-600">{!!__('Unpublish')!!}</button>
                </form>
                @endif
                @if(!$listing->image)
                <form action="{{ route('upload-listing-image', $listing->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <input type="file" name="listing_image" accept="image/*">
                    <button type="submit" class="px-4 py-2 bg-green-500 text-white rounded-md hover:bg-green-600">{!!__('Upload image')!!}</button>
                </form>
            @endif
            </div>
        </div>
        @endforeach
    @else
        <p>{!!__('No listings found')!!}.</p>
    @endif
</div>
@endsection
