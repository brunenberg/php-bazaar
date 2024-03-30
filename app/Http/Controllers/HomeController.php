<?php

namespace App\Http\Controllers;

use App\Models\Listing;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    private $listingsPerPage;

    public function __construct()
    {
        $this->listingsPerPage = 9;
    }

    public function index()
    {
        $listings = Listing::where('active', true)->orderBy('updated_at', 'desc')->paginate($this->listingsPerPage);
        return view('home', compact('listings'));
    }

    public function search(Request $request)
    {
        $query = Listing::query();

        if ($request->has('search')) {
            $query->where('title', 'like', '%' . $request->input('search') . '%');
            // Voeg meer voorwaarden toe indien nodig voor extra velden
        }

        if ($request->has('sort_by')) {
            $sort = explode('_', $request->input('sort_by'));
            $column = $sort[0];
            $direction = $sort[1] == 'asc' ? 'asc' : 'desc'; // Zorg ervoor dat de richting geldig is
            if ($column == 'price') {
                $query->orderBy('price', $direction);
            } else {
                $query->orderBy('updated_at', 'desc');
            }
        } else {
            $query->orderBy('updated_at', 'desc');
        }

        // Voeg prijsfilter toe
        if ($request->has('min_price')) {
            $query->where('price', '>=', $request->input('min_price'));
        }
        if ($request->has('max_price')) {
            $query->where('price', '<=', $request->input('max_price'));
        }

        $listings = $query->paginate($this->listingsPerPage);

        return view('home', compact('listings'));
    }
}
