@extends('layout')

@section('content')
<form action="{{ route('create-listing') }}" class="mt-4" method="POST" enctype="multipart/form-data">
    @csrf
    <div class="mt-4">
        <label for="title" class="block">Titel:</label>
        <input name="title" type="text" class="mt-1 p-2 border rounded-md w-full">
    </div>

    <div class="mt-4">
        <label for="description" class="block">Beschrijving:</label>
        <textarea name="description" class="mt-1 p-2 border rounded-md w-full h-24"></textarea>
    </div>

    <div class="mt-4">
        <label for="image" class="block">Afbeelding:</label>
        <input name="image" type="file" class="mt-1 p-2 border rounded-md w-full">
    </div>

    <button type="submit" class="mt-8 bg-blue-500 text-white px-4 py-2 rounded-md hover:bg-blue-600">Opslaan</button>
</form>
@endsection