@extends('layout')

@section('content')
@if (auth()->user()->user_type === 'admin')
    <div>
        <h2>Download new contract</h2>
        <form action="{{route('company/download-contract')}}" method="POST">
            @csrf
            <input type="hidden" name="company_id" value="{{$company->id}}">
            <button>Download</button>
        </form>

        <a href="/companies" class="text-blue-700 hover:underline">Back to companies</a>
    </div>
@endif


<div class="mx-20 mt-5 p-5 rounded bg-[{{$company->background_color}}] text-[{{$company->text_color}}]">
    <h1 class="font-bold text-3xl">{{$company->name}}</h1>
    @foreach ($templates->sortBy('pivot.order') as $template)
        <div>
            @include('company.templates.'.$template->name, ['company' => $company])
        </div>
    @endforeach
</div>
@endsection