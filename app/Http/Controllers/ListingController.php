<?php

namespace App\Http\Controllers;

use App\Models\Listing;
use Illuminate\Http\Request;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;

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
        $this->validateRequest($request);
    
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
    
        $listingData = [
            'title' => $request->title,
            'description' => $request->description,
            'type' => $request->type,
            'image' => $imageName,
            'price' => $request->price,
            'bidding_allowed' => $request->filled('bidding_allowed'),
            'rental_days' => $request->type == 'verhuur' ? $request->rental_days : null,
            'user_id' => auth()->user()->user_type === 'particuliere_verkoper' ? auth()->id() : null,
            'company_id' => auth()->user()->user_type === 'zakelijke_verkoper' ? auth()->user()->company->id : null,
        ];
    
        $this->createListing($listingData);
    
        return redirect()->route('listings')->with('success', 'You have successfully created a listing.');
    }

    private function validateRequest($request)
    {
        $request->validate([
            'title' => 'required',
            'description' => 'required',
            'type' => 'required|in:verkoop,verhuur',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'bidding_allowed' => 'nullable|boolean',
            'rental_days' => $request->type == 'verhuur' ? 'required|integer' : 'nullable',
            'price' => ['required', 'numeric', 'regex:/^\d+(\.\d{1,2})?$/'],
        ]);
    }

    private function validateCsvData($data)
    {
        $validator = Validator::make($data, [
            'title' => 'required',
            'description' => 'required',
            'type' => 'required|in:verkoop,verhuur',
            'image' => 'required|string',
            'bidding_allowed' => 'nullable|boolean',
            'rental_days' => $data['type'] == 'verhuur' ? 'required|integer' : 'nullable',
            'price' => ['required', 'numeric', 'regex:/^\d+(\.\d{1,2})?$/'],
        ]);
    
        if ($validator->fails()) {
            $errors = $validator->errors()->all();
    
            throw new \Exception(json_encode($errors));
        }
    }    
    
    
    private function createListing($data)
    {
        $listing = new Listing;
        $listing->title = $data['title'];
        $listing->description = $data['description'];
        $listing->type = $data['type'];
        $listing->image = $data['image'];
        $listing->price = $data['price'];
        $listing->bidding_allowed = $data['bidding_allowed'];
        $listing->rental_days = $data['rental_days'];
        $listing->user_id = $data['user_id'];
        $listing->company_id = $data['company_id'];
        $listing->save();
    }

    public function uploadCsv(Request $request)
    {
        
        $request->validate([
            'csv_file' => 'required|mimes:csv,txt',
        ]);
    
        $file = fopen($request->file('csv_file'), 'r');
        
        $header = fgetcsv($file);

        $rowNumber = 1;

        while (($row = fgetcsv($file)) !== false) {
            $rowNumber++;
            $listingData = [
                'type' => $row[0],
                'title' => $row[1],
                'description' => $row[2],
                'image' => $row[3],
                'price' => trim($row[4]),
                'bidding_allowed' => $row[5] === 'true' ? true : false,
                'rental_days' => $row[6] !== '' ? $row[6] : null,
                'user_id' => auth()->user()->user_type === 'particuliere_verkoper' ? auth()->id() : null,
                'company_id' => auth()->user()->user_type === 'zakelijke_verkoper' ? auth()->user()->company->id : null,
            ];
        
            try {
                $this->validateCsvData($listingData);
            } catch (\Exception $e) {
                $errors = json_decode($e->getMessage(), true);

                $errors = array_map(function ($error) use ($rowNumber) {
                    return "Row {$rowNumber}: {$error}";
                }, $errors);

                return redirect()->back()->withErrors(['csv_file' => $errors]);
            }
        
            $this->createListing($listingData);
        }
    
        fclose($file);
    
        return redirect()->route('listings')->with('success', 'CSV uploaded successfully.');
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
    
        $this->validateRequest($request);
    
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
}
