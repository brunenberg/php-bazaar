<?php

namespace App\Http\Controllers;

use App\Models\Listing;
use Illuminate\Http\Request;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Support\Facades\Response;

class ListingController extends Controller
{
    public function index()
    {
        if (auth()->user()->user_type === 'particuliere_verkoper') {
            $listings = auth()->user()->listings;
        } else if (auth()->user()->user_type === 'zakelijke_verkoper') {
            $listings = auth()->user()->company->listings;
        }
        return view('listings.listing-index', compact('listings'));
    }

    public function create()
    {
        $company = auth()->user()->company;
        return view('listings.manage-listing', ['company' => $company]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required',
            'description' => 'required',
            'type' => 'required|in:verkoop,verhuur',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'bidding_allowed' => 'nullable|boolean',
            'wear_speed' => 'nullable|string',
            'rental_days' => $request->type == 'verhuur' ? 'required|integer' : 'nullable',
            'price' => ['required', 'numeric', 'regex:/^\d+(\.\d{1,2})?$/'],
        ]);
    
        $listingsCount = 0;
        if (auth()->user()->user_type === 'particuliere_verkoper') {
            $listingsCount = auth()->user()->listings->count();
        } else if (auth()->user()->user_type === 'zakelijke_verkoper') {
            $listingsCount = auth()->user()->company->listings->count();
        }
    
        if ($listingsCount >= 4) {
            return redirect()->back()->with('error', 'You have reached the maximum number of listings.');
        }
    
        $imageName = time().'.'.$request->image->extension();  
        $request->image->move(public_path('images'), $imageName);
    
        $listing = new Listing;
        $listing->title = $request->title;
        $listing->description = $request->description;
        $listing->type = $request->type;
        $listing->image = $imageName;
        if($request->wear_speed){
            $listing->wear_speed = $request->wear_speed;
            $listing->condition = 100;
        }
        $listing->price = $request->price;

        if ($request->type == 'verkoop') {
            $listing->bidding_allowed = $request->filled('bidding_allowed');
            $listing->rental_days = null;
        } else if ($request->type == 'verhuur') {
            $listing->bidding_allowed = false;
            $listing->rental_days = $request->rental_days;
        }
    
        if (auth()->user()->user_type === 'particuliere_verkoper') {
            $listing->user_id = auth()->id();
        } else if (auth()->user()->user_type === 'zakelijke_verkoper') {
            $listing->company_id = auth()->user()->company->id;
        }
    
        $listing->save();
    
        return redirect()->route('listings')->with('success', 'You have successfully created a listing.');
    }

    public function edit($id)
    {
        $listing = Listing::find($id);
    
        // Check if the listing exists and belongs to the authenticated user or their company
        if (!$listing || ($listing->user_id != auth()->id() && $listing->company_id != auth()->user()->company->id)) {
            return redirect()->back()->with('error', 'Listing not found.');
        }
    
        return view('listings.manage-listing', ['listing' => $listing]);
    }
    
    public function update(Request $request, $id)
    {
        $listing = Listing::find($id);
    
        // Check if the listing exists and belongs to the authenticated user or their company
        if (!$listing || ($listing->user_id != auth()->id() && $listing->company_id != auth()->user()->company->id)) {
            return redirect()->back()->with('error', 'Listing not found.');
        }
    
        $request->validate([
            'title' => 'required',
            'description' => 'required',
            'type' => 'required|in:verkoop,verhuur',
            'image' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'bidding_allowed' => 'nullable|boolean',
            'rental_days' => $request->type == 'verhuur' ? 'required|integer' : 'nullable',
            'price' => ['required', 'numeric', 'regex:/^\d+(\.\d{1,2})?$/'],
        ]);
    
        if ($request->hasFile('image')) {
            $imageName = time().'.'.$request->image->extension();  
            $request->image->move(public_path('images'), $imageName);
            $listing->image = $imageName;
        }

        if ($request->type == 'verkoop') {
            $listing->bidding_allowed = $request->filled('bidding_allowed');
            $listing->rental_days = null;
        } else if ($request->type == 'verhuur') {
            $listing->bidding_allowed = false;
            $listing->rental_days = $request->rental_days;
        }

        if($request->wear_speed){
            $listing->wear_speed = $request->wear_speed;
            if($listing->condition == null){
                $listing->condition = 100;
            }
        }
    
        $listing->title = $request->title;
        $listing->description = $request->description;
        $listing->type = $request->type;
        $listing->price = $request->price;
        $listing->save();
    
        return redirect()->route('listings')->with('success', 'Listing updated successfully.');
    }
    
    public function destroy($id)
    {
        $listing = Listing::find($id);
    
        // Check if the listing exists and belongs to the authenticated user or their company
        if (!$listing || ($listing->user_id != auth()->id() && $listing->company_id != auth()->user()->company->id)) {
            return redirect()->back()->with('error', 'Listing not found.');
        }
    
        // Delete the associated reviews
        $listing->reviews()->detach();
    
        // Delete the associated favorites
        $listing->users()->detach();
    
        $listing->delete();
    
        return redirect()->route('listings')->with('success', 'Listing deleted successfully.');
    }

    public function show($id)
    {
        $listing = Listing::find($id);
        if (!$listing) {
            abort(404);
        }
        $qrCode = $this->qr($listing);
        return view('listings.listing', compact('listing', 'qrCode'));
    }

    public function addReview(Request $request)
    {
        $validatedData = $request->validate([
            'review' => 'required|string',
            'rating' => 'required|numeric|min:1|max:5',
            'listing_id' => 'required|numeric'
        ]);

        $listing = Listing::find($validatedData['listing_id']);

        $existingReview = $listing->reviews()->where('user_id', auth()->user()->id)->first();
        if ($existingReview) {
            return redirect()->back()->with('error', 'You have already placed a review for this listing.');
        }

        $listing->reviews()->attach(auth()->user()->id, [
            'review' => $validatedData['review'],
            'rating' => $validatedData['rating']
        ]);

        return redirect()->back();
    }

    public function deleteReview(Request $request)
    {
        $validatedData = $request->validate([
            'listing_id' => 'required|numeric',
        ]);

        $listing = Listing::find($validatedData['listing_id']);
        $listing->reviews()->detach(auth()->user()->id);

        return redirect()->back();
    }

    public function qr($listing)
    {
        $qrPath = public_path('qr-codes/' . $listing->id . '.svg');
        $qrUrl = asset('qr-codes/' . $listing->id . '.svg');

        if (!file_exists($qrPath)) {
            QrCode::size(300)->generate(route('listing.show', $listing), $qrPath);
        }
        
        return $qrUrl;
    }

    public function allListings()
    {
        $listings = Listing::all();
        return $listings;
    }

    // Listings API for specific company
    public function companyListings($companyId)
    {
        $listings = Listing::where('company_id', $companyId)->get();
        return $listings;
    }

    // Activate listing
    public function activate($id)
    {
        $listing = Listing::find($id);
        $listing->active = true;
        $listing->expires_in_days = 7;
        $listing->created_at = now();
        $listing->save();
        return redirect()->back();
    }

    // Deactivate listing
    public function deactivate($id)
    {
        $listing = Listing::find($id);
        $listing->active = false;
        $listing->save();
        return redirect()->back();
    }

    public function return(Request $request)
    {
        $validatedData = $request->validate([
            'listing_id' => 'required|numeric',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'user_id' => 'required|numeric'
        ]);

        $listing = Listing::find($validatedData['listing_id']);

        if($listing->wear_speed == 'slow'){
            $listing->condition -= 5;
        } else if($listing->wear_speed == 'normal'){
            $listing->condition -= 10;
        } else if($listing->wear_speed == 'fast'){
            $listing->condition -= 15;
        }
        $listing->save();

        // Set returned to true in user_bought pivot table
        $listing->bought()->where('user_id', $validatedData['user_id'])->updateExistingPivot($validatedData['user_id'], ['returned' => true]);
        $date = now()->format('YmdHis');
        $imageName = $listing->id . '-' . $date . '.' . $request->image->extension();
        $listing->bought()->where('user_id', $validatedData['user_id'])->updateExistingPivot($validatedData['user_id'], ['return_image' => $imageName]);

        $request->image->move(public_path('images/returns'), $imageName);

        return redirect()->back();
    }
}
