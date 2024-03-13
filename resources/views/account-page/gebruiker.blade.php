@extends('layout')

@section('content')
    <div class="container" style="max-width: 400px;">
        <h2>Select Language</h2>
        <form action="{{ route('setlocale') }}" method="POST" style="display: grid; gap: 20px;">
            @csrf
            <div>
                <label for="locale" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Taal</label>
                <select id="locale" name="locale" class="bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" required="">
                    <option value="en" {{ app()->getLocale() == 'en' ? 'selected' : '' }}>English</option>
                    <option value="nl" {{ app()->getLocale() == 'nl' ? 'selected' : '' }}>Nederlands</option>
                </select>
            </div>
            <button type="submit" class="bg-blue-500 w-full text-white bg-primary-600 hover:bg-primary-700 focus:ring-4 focus:outline-none focus:ring-primary-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-primary-600 dark:hover:bg-primary-700 dark:focus:ring-primary-800">Opslaan</button>
        </form>
    </div>
@endsection
