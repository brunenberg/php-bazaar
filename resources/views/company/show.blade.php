@extends('layout')

@section('content')
@if (auth()->user()->user_type === 'admin')
    <div class="mx-20 mt-5 p-5 rounded bg-gray-200">
        <h2 class="font-bold text-2xl pb-3">Download new contract</h2>
        <form action="{{route('company/download-contract')}}" method="POST">
            @csrf
            <input type="hidden" name="company_id" value="{{$company->id}}">
            <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Download</button>
        </form>

        <form action="{{route('company/upload-contract')}}" method="POST" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="company_id" value="{{$company->id}}">
            <input type="file" name="contract" class="mt-2" accept=".pdf">
            <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded mt-2">Upload new contract</button>
        </form>

        {{-- Show all contracts uploaden and if they are signed in a table--}}
        <h2 class="font-bold text-2xl pt-5 pb-3">Contracts</h2>
        <table class="w-full">
            <thead>
                <tr>
                    <th class="border border-gray-300">Date</th>
                    <th class="border border-gray-300">Contract</th>
                    <th class="border border-gray-300">Signed</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($company->contracts as $contract)
                    <tr>
                        <td class="border border-gray-300">{{ $contract->created_at->format('Y-m-d') }}</td>
                        <td class="border border-gray-300">
                            <a href="{{asset('contracts/'.$contract->path)}}" class="text-blue-700 hover:underline" download>Download</a>
                        </td>
                        <td class="border border-gray-300">
                            @if ($contract->signed === 1)
                                <i class="fas fa-check text-green-500"></i>
                            @elseif ($contract->signed === 0)
                                <i class="fas fa-times text-red-500"></i>
                            @else
                                <i class="fas fa-question text-gray-500"></i>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>


        <a href="/companies" class="mt-2 text-blue-700 hover:underline">Back to companies</a>
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