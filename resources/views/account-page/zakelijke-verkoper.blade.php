<div class="container mx-auto px-4">

    <h2 class="text-2xl font-bold mt-8">Winkel pagina instellingen:</h2>

    <form action="{{ route('update-info') }}" class="mt-4" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="mt-4">
            <label for="winkelnaam" class="block">Winkelnaam:</label>
            <input name="winkelnaam" type="text" class="mt-1 p-2 border rounded-md w-full" value="{{$company->name}}">
        </div>

        <div class="mt-4">
            <label for="winkelbeschrijving" class="block">Winkelbeschrijving:</label>
            <textarea name="winkelbeschrijving" type="text" class="mt-1 p-2 border rounded-md w-full h-24">{{$company->description}}</textarea>
        </div>

        <div class="mt-4">
            <label for="slug" class="block">Url naar winkelpagina:</label>
            <input name="slug" type="text" class="mt-1 p-2 border rounded-md w-full" value="{{$company->slug}}">
        </div>

        <div class="mt-4">
            <label for="image" class="block">Winkelafbeelding:</label>
            <input name="image" type="file" class="mt-1 p-2 border rounded-md w-full">
        </div>

        <div class="mt-4">
            <label for="achtergrondkleur" class="block">Achtergrondkleur:</label>
            <input name="achtergrondkleur" type="color" class="mt-1 p-1 border rounded-md w-full" value="{{$company->background_color}}">
        </div>

        <div class="mt-4">
            <label for="tekstkleur" class="block">Tekstkleur:</label>
            <input name="tekstkleur" type="color" class="mt-1 p-1 border rounded-md w-full" value="{{$company->text_color}}">
        </div>

        <input type="hidden" name="companyId" value="{{ $company->id }}">
        <button type="submit" class="mt-8 bg-blue-500 text-white px-4 py-2 rounded-md hover:bg-blue-600">Opslaan</button>
    </form>

    <h2 class="text-2xl font-bold mt-8 mb-3">Layout:</h2>
    <div class="grid grid-cols-3 gap-4 mb-10">
        <div class="col-span-1 bg-gray-200 p-4">
            <!-- Inhoud van de linker kolom -->
            @foreach ($templates as $template)
                <div id="template-{{$template->id}}" class="rounded-xl p-3 border-b bg-blue-200">
                    <p class="font-bold">{{$template->name}}</p>
                    <p>{{$template->description}}</p>
                    <form action="{{route('add-template')}}" method="POST">
                        @csrf
                        <input type="hidden" name="companyId" value="{{$company->id}}">
                        <input type="hidden" name="templateId" value="{{$template->id}}">
                        <button type="submit" class="mt-2 bg-blue-500 text-white px-4 py-2 rounded-md hover:bg-blue-600">Toevoegen</button>
                    </form>
                </div>
            @endforeach
        </div>
        <div class="col-span-2 bg-gray-200 p-4">
            <!-- Inhoud van de rechter kolom -->
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
                            <button class="max-w-40 bg-red-500 text-white px-4 py-2 rounded-md hover:bg-red-600 mt-2">Verwijder</button>
                        </form>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
    
</div>
