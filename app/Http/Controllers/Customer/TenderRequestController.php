<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\TenderRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Carbon\Carbon;

class TenderRequestController extends Controller
{
    /**
     * Create a new controller instance.
     */
    public function __construct()
    {
        $this->middleware('auth:customer');
    }

    /**
     * Display a listing of the customer's tender requests.
     */
    public function index()
    {
        $customer = Auth::guard('customer')->user();
        $tenderRequests = $customer->tenderRequests()->latest()->paginate(10);
        
        return view('customer.tender-requests.index', compact('tenderRequests'));
    }

    /**
     * Show the form for creating a new tender request.
     */
    public function create(Request $request)
    {
        $selectedDate = $request->get('date', now()->addDay()->format('Y-m-d'));
        $dateCarbon = Carbon::parse($selectedDate);
        
        // Get available slots for the selected date
        $availableSlots = TenderRequest::getAvailableSlots($dateCarbon);
        
        // Get available dates (next 30 days, excluding weekends)
        $availableDates = [];
        for ($i = 1; $i <= 30; $i++) {
            $date = now()->addDays($i);
            if (!$date->isWeekend()) {
                $availableDates[] = [
                    'value' => $date->format('Y-m-d'),
                    'label' => $date->format('l, j F Y'),
                    'available_slots' => count(TenderRequest::getAvailableSlots($date))
                ];
            }
        }
        
        $states = TenderRequest::getAustralianStates();
        
        return view('customer.tender-requests.create', compact(
            'availableDates', 
            'selectedDate', 
            'availableSlots', 
            'states'
        ));
    }

    /**
     * Store a newly created tender request.
     */
    public function store(Request $request)
    {
        $customer = Auth::guard('customer')->user();
        
        $validated = $request->validate([
            'property_address' => 'required|string|max:255',
            'suburb' => 'nullable|string|max:100',
            'state' => ['nullable', Rule::in(array_keys(TenderRequest::getAustralianStates()))],
            'postcode' => 'nullable|string|regex:/^[0-9]{4}$/',
            'number_of_windows' => 'required|integer|min:1|max:1000',
            'appointment_datetime' => 'required|date|after:now',
            'customer_notes' => 'nullable|string|max:1000',
            'preferred_contact_method' => 'required|in:phone,email',
        ]);
        
        // Verify the slot is still available
        $appointmentTime = Carbon::parse($validated['appointment_datetime']);
        $isSlotAvailable = !TenderRequest::where('appointment_datetime', $appointmentTime)
            ->whereIn('status', ['pending', 'confirmed'])
            ->exists();
            
        if (!$isSlotAvailable) {
            return back()->withErrors([
                'appointment_datetime' => 'This time slot is no longer available. Please select another time.'
            ])->withInput();
        }
        
        $validated['customer_id'] = $customer->id;
        $validated['status'] = 'pending';
        
        $tenderRequest = TenderRequest::create($validated);
        
        return redirect()->route('customer.tender-requests.show', $tenderRequest)
            ->with('success', 'Your tender request has been submitted successfully! We will contact you soon to confirm your appointment.');
    }

    /**
     * Display the specified tender request.
     */
    public function show(TenderRequest $tenderRequest)
    {
        $customer = Auth::guard('customer')->user();
        
        // Ensure the tender request belongs to the authenticated customer
        if ($tenderRequest->customer_id !== $customer->id) {
            abort(404);
        }
        
        return view('customer.tender-requests.show', compact('tenderRequest'));
    }

    /**
     * Get available slots for a specific date (AJAX endpoint).
     */
    public function getAvailableSlots(Request $request)
    {
        $date = Carbon::parse($request->get('date'));
        $slots = TenderRequest::getAvailableSlots($date);
        
        return response()->json([
            'slots' => $slots,
            'date' => $date->format('l, j F Y')
        ]);
    }
}