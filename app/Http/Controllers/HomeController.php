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
    
        $query->where('active', true);
    
        if ($request->has('search')) {
            $query->where('title', 'like', '%' . $request->input('search') . '%');
        }
    
        if ($request->has('sort_by')) {
            $sort = explode('_', $request->input('sort_by'));
            $column = $sort[0];
            $direction = $sort[1] == 'asc' ? 'asc' : 'desc'; 
            if ($column == 'price') {
                $query->orderBy('price', $direction);
            } else {
                $query->orderBy('updated_at', 'desc');
            }
        } else {
            $query->orderBy('updated_at', 'desc');
        }
    
        if ($request->has('min_price')) {
            if($request->min_price > 0){
                $query->where('price', '>=', $request->input('min_price'));
            }   
        }
        if ($request->has('max_price')) {
            if($request->max_price > 0){
                $query->where('price', '<=', $request->input('max_price'));
            }  
        }
    
        $listings = $query->paginate($this->listingsPerPage);
    
        return view('home', compact('listings'));
    }
    
}
