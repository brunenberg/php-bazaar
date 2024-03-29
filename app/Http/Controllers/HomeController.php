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
}

