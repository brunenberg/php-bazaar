@extends('layout')

@section('content')
    <div class="container">
        <h1 class="font-bold text-3xl p-4 pl-0">Companies</h1>
        @foreach ($companies as $company)
        <a href="/company/{{$company->slug}}"><p>{{ $company->name }}</p></a>
        @endforeach
    </div>
@endsection