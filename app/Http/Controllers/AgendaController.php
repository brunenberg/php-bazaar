<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AgendaController extends Controller
{
    public function index(Request $request)
    {
        $currentMonth = $request->input('month') ?? date('F');
        $currentYear = $request->input('year') ?? date('Y');
        $currentMonthTimestamp = strtotime('1 ' . $currentMonth . ' ' . $currentYear);
        $nextMonth = date('F', strtotime('+1 month', $currentMonthTimestamp));
        $previousMonth = date('F', strtotime('-1 month', $currentMonthTimestamp));
        $selectedMonth = date('Y-m-01', strtotime($currentMonth . ' ' . $currentYear)); 
        $days = cal_days_in_month(CAL_GREGORIAN, date('m', strtotime($currentMonth . ' ' . $currentYear)), date('Y', strtotime($currentMonth . ' ' . $currentYear)));

        if (auth()->user()->company) {
            $listings = auth()->user()->company->listings;
        } else {
            $listings = auth()->user()->listings;
        }

        $allRentalListings = $listings->where('type', 'verhuur');
        $allSoldListings = $listings->where('type', 'verkoop');
        $rentalListings = [];
        $soldListings = [];

        foreach ($allRentalListings as $listing) {
            $bought = $listing->bought()->where('user_bought.created_at', '>=', date('Y-m-01 00:00:00', strtotime($selectedMonth)))->where('user_bought.created_at', '<=', date('Y-m-t 23:59:59', strtotime($selectedMonth)))->get();
            foreach ($bought as $item) {
                $day = date('d', strtotime($item->created_at));
                $rentalListings[ltrim($day, '0')][] = $listing->title;
            }
        }

        foreach ($allSoldListings as $listing) {
            $bought = $listing->bought()->where('user_bought.created_at', '>=', date('Y-m-01 00:00:00', strtotime($selectedMonth)))->where('user_bought.created_at', '<=', date('Y-m-t 23:59:59', strtotime($selectedMonth)))->get();
            foreach ($bought as $item) {
                $day = date('d', strtotime($item->created_at));
                $soldListings[ltrim($day, 'o')][] = $listing->title;
            }
        }

        $returnDates = [];
        $boughtListingsSale = [];
        $boughtListingsRental = [];
        if (auth()->user()->user_type === 'gebruiker') {
            $boughtListings = auth()->user()->bought()->where('type', 'verhuur')->get();
            $boughtListingsSaleRaw = auth()->user()->bought()->where('type', 'verkoop')->get();
            
            foreach ($boughtListings as $listing) {
                $boughtDate = strtotime($listing->pivot->created_at);
                $rentalDays = $listing->rental_days;
                $returnDate = date('d', strtotime('+' . $rentalDays . ' days', $boughtDate));
                if (date('m', strtotime('+' . $rentalDays . ' days', $boughtDate)) == date('m', strtotime($currentMonth . ' ' . $currentYear))) {
                    $returnDates[ltrim($returnDate, '0')][] = $listing->title;
                }

                if (date('m', strtotime($listing->pivot->created_at)) == date('m', strtotime($currentMonth . ' ' . $currentYear))) {
                    $boughtListingsRental[date('d', strtotime($listing->pivot->created_at))][] = $listing->title;
                    $boughtListingsSale[date('d', strtotime($listing->pivot->created_at))][] = $listing->title;
                }
            }
        } else {
            foreach ($allRentalListings as $listing) {
                $bought = $listing->bought()->get();
                foreach ($bought as $item) {
                    $boughtDate = strtotime($item->created_at);
                    $rentalDays = $listing->rental_days;
                    $returnDate = date('d', strtotime('+' . $rentalDays . ' days', $boughtDate));
                    if (date('m', strtotime('+' . $rentalDays . ' days', $boughtDate)) == date('m', strtotime($currentMonth . ' ' . $currentYear))) {
                        $returnDates[ltrim($returnDate, '0')][] = $listing->title;
                    }
                }
            }
        }

        return view('agenda.index', compact('currentMonth', 'nextMonth', 'previousMonth', 'days', 'rentalListings', 'soldListings', 'currentYear', 'returnDates', 'boughtListingsRental', 'boughtListingsSale'));
    }
}