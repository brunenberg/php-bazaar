@extends('layout')

@section('content')
@if (auth()->check() && auth()->user()->user_type === 'admin')
    <div class="mx-20 mt-5 p-5 rounded bg-gray-200">
        <h2 class="font-bold text-2xl pb-3">Download new contract</h2>
        <form action="{{route('company/download-contract')}}" method="POST">
            @csrf
            <input type="hidden" name="company_id" value="{{$company->id}}">
            <button type="submit" id="downloadContract" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Download</button>
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

<div class="mx-20 mt-5 p-5 rounded bg-[{{$company->background_color}}] text-[{{$company->text_color}}]">
    <h2 class="font-bold text-2xl pb-3">{!!__('Reviews')!!}</h2>
    <div class="grid grid-cols-4">
        @foreach ($company->reviews as $review)
            <div class="flex flex-col justify-between items-center border-b pb-3 mb-3 bg-white rounded mr-5 text-black">
                <p class="font-bold">{{$review->email}}</p>
                <div class="flex">
                    @for ($i = 0; $i < $review->pivot->rating; $i++)
                        <i class="fas fa-star text-primary-600"></i>
                    @endfor
                </div>
                <p class="text-sm">{{$review->pivot->review}}</p>
                @if (Auth::check() && $review->email === Auth::user()->email)
                    <form action="{{route('company/delete-review')}}" method="POST">
                        @csrf
                        <input type="hidden" name="company_id" value="{{$company->id}}">
                        <button type="submit" class="text-red-500 hover:text-red-700">{!!__('Delete')!!}</button>
                    </form>
                @endif
            </div>
        @endforeach
    </div>
</div>

@if (auth()->check() && auth()->user()->user_type === 'gebruiker')
    <div class="mx-20 mt-5 p-5 rounded bg-[{{$company->background_color}}] text-[{{$company->text_color}}]">
        <h2 class="font-bold text-2xl">{!!__('Write a review')!!}</h2>
        <form action="{{route('company/review')}}" method="POST">
            @csrf
            <input type="hidden" name="company_id" value="{{$company->id}}">
            <div class="flex flex-col gap-4">
                <div>
                    <label for="rating" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">{!!__('Rating')!!}</label>
                    <select id="rating" name="rating" class="bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" required="">
                        <option value="1">1</option>
                        <option value="2">2</option>
                        <option value="3">3</option>
                        <option value="4">4</option>
                        <option value="5">5</option>
                    </select>
                </div>
                <div>
                    <label for="review" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">{!!__('Review')!!}</label>
                    <textarea id="review" name="review" class="bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" required=""></textarea>
                </div>
                <button id="submit_review" type="submit" class="bg-blue-500 w-full text-white bg-primary-600 hover:bg-primary-700 focus:ring-4 focus:outline-none focus:ring-primary-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-primary-600 dark:hover:bg-primary-700 dark:focus:ring-primary-800">{!!__('Post')!!}</button>
            </div>
    </div>
@endif
@endsection