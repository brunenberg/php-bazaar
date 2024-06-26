<div class="container mx-auto px-4">

    <h2 class="text-2xl font-bold mt-8">{!!__('Store page settings')!!}:</h2>

    <form action="{{ route('update-info') }}" class="mt-4" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="mt-4">
            <label for="winkelnaam" class="block">{!!__('Store name')!!}</label>
            <input name="winkelnaam" type="text" class="mt-1 p-2 border rounded-md w-full" value="{{$company->name}}">
        </div>

        <div class="mt-4">
            <label for="winkelbeschrijving" class="block">{!!__('Store description')!!}</label>
            <textarea name="winkelbeschrijving" type="text" class="mt-1 p-2 border rounded-md w-full h-24">{{$company->description}}</textarea>
        </div>

        <div class="mt-4">
            <label for="slug" class="block">{!!__('Url to store page')!!}</label>
            <input name="slug" type="text" class="mt-1 p-2 border rounded-md w-full" value="{{$company->slug}}">
            <script>
                const slugInput = document.querySelector('input[name=slug]');
                slugInput.addEventListener('input', () => {
                    slugInput.value = slugInput.value.replace(/\W+/g, '-').toLowerCase();
                });
            </script>
        </div>

        <div class="mt-4">
            <label for="image" class="block">{!!__('Store image')!!}</label>
            <input name="image" type="file" class="mt-1 p-2 border rounded-md w-full">
        </div>

        <div class="mt-4">
            <label for="achtergrondkleur" class="block">{!!__('Background color')!!}</label>
            <input name="achtergrondkleur" type="color" class="mt-1 p-1 border rounded-md w-full" value="{{$company->background_color}}">
        </div>

        <div class="mt-4">
            <label for="tekstkleur" class="block">{!!__('Text color')!!}</label>
            <input name="tekstkleur" type="color" class="mt-1 p-1 border rounded-md w-full" value="{{$company->text_color}}">
        </div>

        <div class="mt-4">
            <label class="block">{!!__('Select featured listings')!!}</label>
            <div class="mt-2 relative">
                <button type="button" class="inline-flex justify-between w-full rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" id="featuredListingsDropdown" aria-haspopup="true" aria-expanded="false">
                    {!!__('Select Listings')!!} (Max 3)
                    <span class="ml-2">⌵</span>
                </button>
        
                <div class="absolute z-10 mt-1 w-full bg-white border border-gray-300 rounded-md shadow-lg hidden" id="featuredListingsDropdownContent">
                    <div class="overflow-y-auto max-h-48">
                        @foreach($company->listings as $listing)
                            <label class="block px-4 py-2 hover:bg-gray-100">
                                @php
                                    $featuredListings = json_decode($company->featured_listings, true);
                                @endphp
                                <input type="checkbox" name="featured_listings[]" value="{{ $listing->id }}" class="mr-2" @if($featuredListings && in_array($listing->id, $featuredListings)) checked @endif>
                                {{ $listing->title }}
                            </label>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
        
        <script>
            document.addEventListener("DOMContentLoaded", function() {
                var dropdownButton = document.getElementById('featuredListingsDropdown');
                var dropdownContent = document.getElementById('featuredListingsDropdownContent');
                var checkboxes = dropdownContent.querySelectorAll('input[type="checkbox"]');
                var maxSelections = 3;
        
                dropdownButton.addEventListener('click', function() {
                    var expanded = dropdownButton.getAttribute('aria-expanded') === 'true' || false;
                    dropdownButton.setAttribute('aria-expanded', !expanded);
                    dropdownContent.classList.toggle('hidden');
                });
        
                checkboxes.forEach(function(checkbox) {
                    checkbox.addEventListener('change', function() {
                        var checkedCheckboxes = dropdownContent.querySelectorAll('input[type="checkbox"]:checked');
                        if (checkedCheckboxes.length > maxSelections) {
                            this.checked = false;
                        }
                    });
                });
            });
        </script>        

        <input type="hidden" name="companyId" value="{{ $company->id }}">
        <button type="submit" id="save_page_settings" class="mt-8 bg-blue-500 text-white px-4 py-2 rounded-md hover:bg-blue-600">{!!__('Save')!!}</button>
    </form>

    <h2 class="text-2xl font-bold mt-8 mb-3">{!!__('Layout')!!}:</h2>
    <div class="grid grid-cols-3 gap-4 mb-10">
        <div class="col-span-1 bg-gray-200 p-4">
            <!-- Content of the left column -->
            @foreach ($templates as $template)
                <div id="template-{{$template->id}}" class="rounded-xl p-3 border-b bg-blue-200">
                    <p class="font-bold">{{$template->name}}</p>
                    <p>{{$template->description}}</p>
                    <form action="{{route('add-template')}}" method="POST">
                        @csrf
                        <input type="hidden" name="companyId" value="{{$company->id}}">
                        <input type="hidden" name="templateId" value="{{$template->id}}">
                        <button type="submit" class="mt-2 bg-blue-500 text-white px-4 py-2 rounded-md hover:bg-blue-600">{!!__('Add')!!}</button>
                    </form>
                </div>
            @endforeach
        </div>
        <div class="col-span-2 bg-gray-200 p-4">
            <!-- Content of the right column -->
            @foreach ($activeTemplates->sortBy('pivot.order') as $template)
                <div class="rounded-xl p-3 border-b bg-blue-200">
                    <p class="font-bold">{{$template->name}}</p>
                    <p>{{$template->description}}</p>
                    <div class="flex flex-col">
                        @if (!$loop->first)
                            <form action="{{ route('templates.orderUp') }}" method="POST">
                                @csrf
                                <input type="hidden" name="pivotId" value="{{ $template->pivot->id }}">
                                <input type="hidden" name="companyId" value="{{ $company->id }}">
                                <button type="submit" class="max-w-5"><i class="fa-solid fa-arrow-up"></i></button>
                            </form>
                        @endif
                        @if (!$loop->last)
                            <form action="{{ route('templates.orderDown') }}" method="POST">
                                @csrf
                                <input type="hidden" name="companyId" value="{{ $company->id }}">
                                <input type="hidden" name="pivotId" value="{{ $template->pivot->id }}">
                                <button type="submit" class="max-w-5"><i class="fa-solid fa-arrow-down"></i></button>
                            </form>
                        @endif
                        <form action="{{route('remove-template')}}" method="POST">
                            @csrf
                            <input type="hidden" name="companyId" value="{{ $company->id }}">
                            <input type="hidden" name="pivotId" value="{{$template->pivot->id}}">
                            <button class="max-w-40 bg-red-500 text-white px-4 py-2 rounded-md hover:bg-red-600 mt-2">{!!__('Delete')!!}</button>
                        </form>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
    
    @include('components.bids')

    {{-- Show all contracts with accept en reject buttons --}}
    <div>
        <h2 class="text-2xl font-bold mt-8">{!!__('Contracts')!!}:</h2>
        <table class="w-full">
            <thead>
                <tr>
                    <th class="border border-gray-300">{!!__('Date')!!}</th>
                    <th class="border border-gray-300">{!!__('Contract')!!}</th>
                    <th class="border border-gray-300">{!!__('Signed')!!}</th>
                    <th class="border border-gray-300">{!!__('Actions')!!}</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($company->contracts as $contract)
                    <tr>
                        <td class="border border-gray-300">{{ $contract->created_at->format('Y-m-d') }}</td>
                        <td class="border border-gray-300">
                            <a href="{{asset('contracts/'.$contract->path)}}" class="text-blue-700 hover:underline" download>{!!__('Download')!!}</a>
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
                        <td class="border border-gray-300">
                            @if ($contract->signed === null)
                                <form action="{{route('contract/accept')}}" method="POST">
                                    @csrf
                                    <input type="hidden" name="contract_id" value="{{$contract->id}}">
                                    <button type="submit" class="bg-green-500 text-white px-4 py-2 rounded-md hover:bg-green-600">{!!__('Accept')!!}</button>
                                </form>
                                <form action="{{route('contract/reject')}}" method="POST">
                                    @csrf
                                    <input type="hidden" name="contract_id" value="{{$contract->id}}">
                                    <button type="submit" class="bg-red-500 text-white px-4 py-2 rounded-md hover:bg-red-600">{!!__('Deny')!!}</button>
                                </form>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    
</div>

<div>
    <h2 class="text-2xl font-bold mt-8">{!!__('API')!!}:</h2>
    <button id="copyUrlButton" class="mt-4 bg-blue-500 text-white px-4 py-2 rounded-md hover:bg-blue-600">{!!__('Copy API url')!!}</button>
    <button id="copyTokenButton" class="mt-4 bg-blue-500 text-white px-4 py-2 rounded-md hover:bg-blue-600">{!!__('Copy API token')!!}</button>
</div>

<script>
    document.getElementById('copyUrlButton').addEventListener('click', function() {
        var copyText = window.location.href + '/api/company/{{$company->id}}/listings';
        navigator.clipboard.writeText(copyText)
            .then(function() {
                console.log('URL gekopieerd naar klembord: ' + copyText);
            })
            .catch(function(error) {
                console.error('Kopiëren naar klembord mislukt: ', error);
            });
    });

    document.getElementById('copyTokenButton').addEventListener('click', function() {
        var xhr = new XMLHttpRequest();
        xhr.open('GET', '/get_personal_access_token', true);
        xhr.onreadystatechange = function() {
            if (xhr.readyState === XMLHttpRequest.DONE) {
                if (xhr.status === 200) {
                    var personalAccessToken = xhr.responseText;
                    navigator.clipboard.writeText(personalAccessToken)
                        .then(function() {
                            console.log('Token gekopieerd naar klembord: ' + personalAccessToken);
                        })
                        .catch(function(error) {
                            console.error('Kopiëren naar klembord mislukt: ', error);
                        });
                } else {
                    console.error('Er is een fout opgetreden bij het ophalen van het persoonlijke toegangstoken');
                }
            }
        };
        xhr.send();
    });
</script>
