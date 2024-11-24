<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Deal;
use App\Models\Service;

class DisplayDeal extends Controller
{
    /**
     * Display all active and visible deals.
     */
    public function index(Request $request)
    {
        $deals = Deal::all();

        $services = Service::all(); // Fetch all services for selection

        return view('web.deals', compact('deals', 'services'));
    }

    /**
     * Apply a deal to a selected service.
     */
    public function showServices(Deal $deal)
    {
        $services = $deal->services()->get();
        return view('web.services', compact('deal', 'services'));
    }
}
