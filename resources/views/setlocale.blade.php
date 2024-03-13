@extends('layout')

@section('content')
    <div class="container">
        <h2>Select Language</h2>
        <form action="{{ route('setlocale') }}" method="POST">
            @csrf
            <input type="hidden" name="locale" value="en">
            <button type="submit">English</button>
        </form>
        <form action="{{ route('setlocale') }}" method="POST">
            @csrf
            <input type="hidden" name="locale" value="nl">
            <button type="submit">Dutch</button>
        </form>
    </div>
@endsection
