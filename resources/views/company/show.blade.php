@extends('layout')

@section('content')
<div class="mx-20 mt-5 p-5 rounded bg-[{{$company->background_color}}] text-[{{$company->text_color}}]">
    <h1 class="font-bold text-3xl">{{$company->name}}</h1>
    

    @foreach ($templates->sortBy('pivot.order') as $template)
        <div>
            @include('company.templates.'.$template->name, ['company' => $company])
        </div>
    @endforeach
</div>
@endsection