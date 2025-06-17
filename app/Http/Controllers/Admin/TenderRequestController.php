<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TenderRequest;
use App\Models\Customer;
use Illuminate\Http\Request;

class TenderRequestController extends Controller
{
    /**
     * Display a listing of the tender requests.
     */
    public function index()
    {
        $tenderRequests = TenderRequest::with('customer')
            ->orderBy('appointment_datetime')
            ->paginate(15);

        return view('admin.tender-requests.index', compact('tenderRequests'));
    }

    /**
     * Show tender requests for a specific customer.
     */
    public function customerTenderRequests(Customer $customer)
    {
        $tenderRequests = $customer->tenderRequests()
            ->orderBy('appointment_datetime')
            ->paginate(15);

        return view('admin.tender-requests.customer-requests', compact('customer', 'tenderRequests'));
    }

    /**
     * Show the form for creating a new tender request.
     */
    public function create(Request $request)
    {
        $customer = null;
        if ($request->has('customer_id')) {
            $customer = Customer::findOrFail($request->customer_id);
        }

        return view('admin.tender-requests.create', compact('customer'));
    }

    /**
     * Store a newly created tender request in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'property_address' => 'required|string|max:255',
            'suburb' => 'required|string|max:100',
            'state' => 'required|string|max:50',
            'postcode' => 'required|string|max:10',
            'number_of_windows' => 'required|integer|min:1',
            'appointment_datetime' => 'required|date',
            'customer_notes' => 'nullable|string',
            'admin_notes' => 'nullable|string',
            'preferred_contact_method' => 'required|in:email,phone',
        ]);

        $tenderRequest = TenderRequest::create($validated);

        return redirect()
            ->route('admin.tender-requests.show', $tenderRequest)
            ->with('success', 'Tender request created successfully.');
    }

    /**
     * Display the specified tender request.
     */
    public function show(TenderRequest $tenderRequest)
    {
        return view('admin.tender-requests.show', compact('tenderRequest'));
    }

    /**
     * Show the form for editing the specified tender request.
     */
    public function edit(TenderRequest $tenderRequest)
    {
        return view('admin.tender-requests.edit', compact('tenderRequest'));
    }

    /**
     * Update the specified tender request in storage.
     */
    public function update(Request $request, TenderRequest $tenderRequest)
    {
        $validated = $request->validate([
            'property_address' => 'required|string|max:255',
            'suburb' => 'required|string|max:100',
            'state' => 'required|string|max:50',
            'postcode' => 'required|string|max:10',
            'number_of_windows' => 'required|integer|min:1',
            'appointment_datetime' => 'required|date',
            'customer_notes' => 'nullable|string',
            'admin_notes' => 'nullable|string',
            'preferred_contact_method' => 'required|in:email,phone',
            'status' => 'required|in:pending,confirmed,completed,cancelled',
        ]);

        $tenderRequest->update($validated);

        return redirect()
            ->route('admin.tender-requests.show', $tenderRequest)
            ->with('success', 'Tender request updated successfully.');
    }

    /**
     * Convert tender request to tender.
     */
    public function convertToTender(TenderRequest $tenderRequest)
    {
        // This will be implemented when creating tender functionality
        return redirect()
            ->route('admin.tenders.create', [
                'customer_id' => $tenderRequest->customer_id,
                'tender_request_id' => $tenderRequest->id
            ]);
    }
}