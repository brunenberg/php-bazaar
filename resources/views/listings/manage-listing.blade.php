@extends('layout')

@section('content')
<form action="{{ isset($listing) ? route('update-listing', $listing->id) : route('create-listing') }}" class="mt-4" method="POST" enctype="multipart/form-data">
    @csrf
    @if(isset($listing))
        @method('PUT')
    @endif
    <div class="mt-4">
        <label for="title" class="block">Titel:</label>
        <input name="title" type="text" value="{{ old('title', $listing->title ?? '') }}" class="mt-1 p-2 border rounded-md w-full">
    </div>

    <div class="mt-4">
        <label for="description" class="block">Beschrijving:</label>
        <textarea name="description" class="mt-1 p-2 border rounded-md w-full h-24">{{ old('description', $listing->description ?? '') }}</textarea>
    </div>

    <div class="mt-4">
        <label for="type" class="block">Type:</label>
        <select name="type" class="mt-1 p-2 border rounded-md w-full">
            <option value="verkoop" {{ (old('type', $listing->type ?? '') == 'verkoop') ? 'selected' : '' }}>Verkoop</option>
            <option value="verhuur" {{ (old('type', $listing->type ?? '') == 'verhuur') ? 'selected' : '' }}>Verhuur</option>
        </select>
    </div>

    <div class="mt-4">
        <label for="image" class="block">Afbeelding:</label>
        <input name="image" type="file" accept=".jpeg,.jpg,.png,.gif,.svg" class="mt-1 p-2 border rounded-md w-full">
    </div>

    <button type="submit" class="mt-8 bg-blue-500 text-white px-4 py-2 rounded-md hover:bg-blue-600">Opslaan</button>
</form>

@endsection
