@extends('layout')

@section('content')
    <div class="container">
        <h1 class="font-bold text-3xl p-4 pl-0">Companies</h1>
        <form action="{{ route('companies') }}" method="GET">
            <select name="filter">
                <option value="">Alle bedrijven</option>
                <option value="no_contracts">Bedrijven zonder contracten</option>
            </select>
            <button type="submit">Filter</button>
        </form>
        <table class="border-collapse w-full">
            <thead>
                <tr>
                    <th class="p-2 border border-gray-500">Name</th>
                    <th class="p-2 border border-gray-500"><a href="{{ route('companies', ['sortBy' => 'created_at']) }}">Creation Date</a></th>
                    <th class="p-2 border border-gray-500"><a href="{{ route('companies', ['sortBy' => 'contracts']) }}">Total Contracts</a></th>
                </tr>
            </thead>
            <tbody>
                @foreach ($companies as $company)
                <tr>
                    <td class="p-2 border border-gray-500"><a href="/company/{{$company->slug}}" class="text-blue-500 hover:underline">{{ $company->name }}</a></td>
                    <td class="p-2 border border-gray-500">{{ $company->created_at }}</td>
                    <td class="p-2 border border-gray-500">{{ $company->contracts->count() }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    {{$companies->links()}}
@endsection