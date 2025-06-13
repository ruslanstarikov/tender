<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    /**
     * Create a new controller instance.
     */
    public function __construct()
    {
        $this->middleware('auth:customer');
    }

    /**
     * Show the customer dashboard.
     */
    public function index()
    {
        $customer = Auth::guard('customer')->user();
        
        // Get customer's tender requests (we'll create this relationship later)
        $tenderRequests = $customer->tenderRequests()->latest()->limit(5)->get();
        
        return view('customer.dashboard', compact('customer', 'tenderRequests'));
    }
}