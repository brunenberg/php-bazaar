@if ($featuredListings != null)
<h2 class="font-bold text-2xl pb-3">Uitgelichte advertenties:</h2>
<div class="text-center grid grid-cols-3 justify-items-center gap-4">
    @foreach ($featuredListings as $listing)
        <x-listing-card :listing="$listing"/>
    @endforeach
</div>
@endif
