<?php

namespace App\Http\Controllers;

use App\Models\Listing;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $listings = Listing::orderBy('updated_at', 'desc')->paginate(9);
        return view('home', compact('listings'));
    }
}
