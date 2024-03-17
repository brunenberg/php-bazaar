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
        ]);
    
        $listingsCount = 0;
        if (auth()->user()->user_type === 'particuliere_verkoper') {
            $listingsCount = auth()->user()->listings->count();
        } else if (auth()->user()->user_type === 'zakelijke_verkoper') {
            $listingsCount = auth()->user()->company->listings->count();
        }
    
        if ($listingsCount >= 4) {
            return back()->with('error', 'You have reached the maximum number of listings.');
        }
    
        $imageName = time().'.'.$request->image->extension();  
        $request->image->move(public_path('images'), $imageName);
    
        $listing = new Listing;
        $listing->title = $request->title;
        $listing->description = $request->description;
        $listing->type = $request->type;
        $listing->image = $imageName;
    
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
            'type' => 'required|in:verkoop,verhuur', // Add this line
            'image' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);
    
        if ($request->hasFile('image')) {
            $imageName = time().'.'.$request->image->extension();  
            $request->image->move(public_path('images'), $imageName);
            $listing->image = $imageName;
        }
    
        $listing->title = $request->title;
        $listing->description = $request->description;
        $listing->type = $request->type; // Add this line
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
}
