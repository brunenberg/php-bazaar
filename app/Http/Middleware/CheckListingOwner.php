<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\Listing;

class CheckListingOwner
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $listing = Listing::find($request->route('id'));
    
        if (!$listing) {
            return redirect()->back()->with('error', __('messages.listing_not_found'));
        }
    
        if ($listing->user_id != auth()->id() && (!auth()->user()->company || $listing->company_id != auth()->user()->company->id)) {
            return redirect()->back()->with('error', __('messages.no_listing_modify_permission'));
        }
    
        return $next($request);
    }
    
}
