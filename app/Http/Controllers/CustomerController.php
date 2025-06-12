<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class CustomerController extends Controller
{
    /**
     * Display a listing of customers.
     */
    public function index(Request $request)
    {
        $query = Customer::query();
        
        // Search functionality
        if ($request->filled('search')) {
            $search = $request->get('search');
            $query->where(function ($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                  ->orWhere('last_name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%")
                  ->orWhere('suburb', 'like', "%{$search}%");
            });
        }
        
        // Filter by status
        if ($request->filled('status')) {
            if ($request->get('status') === 'active') {
                $query->active();
            } elseif ($request->get('status') === 'inactive') {
                $query->inactive();
            }
        }
        
        // Sort
        $sortBy = $request->get('sort', 'created_at');
        $sortDirection = $request->get('direction', 'desc');
        $query->orderBy($sortBy, $sortDirection);
        
        $customers = $query->withCount('tenders')->paginate(15);
        
        return view('admin.customers.index', compact('customers'));
    }

    /**
     * Show the form for creating a new customer.
     */
    public function create()
    {
        $states = Customer::getAustralianStates();
        $streetTypes = Customer::getStreetTypes();
        
        return view('admin.customers.create', compact('states', 'streetTypes'));
    }

    /**
     * Store a newly created customer.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|unique:customers,email',
            'phone' => 'required|string|max:20',
            'password' => 'required|string|min:8|confirmed',
            'street_number' => 'nullable|string|max:10',
            'street_name' => 'nullable|string|max:255',
            'street_type' => 'nullable|string|max:50',
            'unit_number' => 'nullable|string|max:10',
            'suburb' => 'nullable|string|max:100',
            'state' => ['nullable', Rule::in(array_keys(Customer::getAustralianStates()))],
            'postcode' => 'nullable|string|regex:/^[0-9]{4}$/',
            'date_of_birth' => 'nullable|date|before:today',
            'notes' => 'nullable|string|max:1000',
            'is_active' => 'boolean',
        ]);

        $customer = Customer::create($validated);

        return redirect()->route('admin.customers.show', $customer)
            ->with('success', 'Customer created successfully.');
    }

    /**
     * Display the specified customer.
     */
    public function show(Customer $customer)
    {
        $customer->load(['tenders' => function ($query) {
            $query->latest()->with('media');
        }]);
        
        return view('admin.customers.show', compact('customer'));
    }

    /**
     * Show the form for editing the specified customer.
     */
    public function edit(Customer $customer)
    {
        $states = Customer::getAustralianStates();
        $streetTypes = Customer::getStreetTypes();
        
        return view('admin.customers.edit', compact('customer', 'states', 'streetTypes'));
    }

    /**
     * Update the specified customer.
     */
    public function update(Request $request, Customer $customer)
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => ['required', 'email', Rule::unique('customers')->ignore($customer->id)],
            'phone' => 'required|string|max:20',
            'street_number' => 'nullable|string|max:10',
            'street_name' => 'nullable|string|max:255',
            'street_type' => 'nullable|string|max:50',
            'unit_number' => 'nullable|string|max:10',
            'suburb' => 'nullable|string|max:100',
            'state' => ['nullable', Rule::in(array_keys(Customer::getAustralianStates()))],
            'postcode' => 'nullable|string|regex:/^[0-9]{4}$/',
            'date_of_birth' => 'nullable|date|before:today',
            'notes' => 'nullable|string|max:1000',
            'is_active' => 'boolean',
        ]);

        $customer->update($validated);

        return redirect()->route('admin.customers.show', $customer)
            ->with('success', 'Customer updated successfully.');
    }

    /**
     * Reset customer password.
     */
    public function resetPassword(Customer $customer)
    {
        $newPassword = $customer->resetPassword();
        
        return redirect()->route('admin.customers.show', $customer)
            ->with('success', "Password reset successfully. New password: {$newPassword}");
    }

    /**
     * Toggle customer active status.
     */
    public function toggleStatus(Customer $customer)
    {
        $customer->update(['is_active' => !$customer->is_active]);
        
        $status = $customer->is_active ? 'activated' : 'deactivated';
        
        return redirect()->back()
            ->with('success', "Customer {$status} successfully.");
    }

    /**
     * Remove the specified customer.
     */
    public function destroy(Customer $customer)
    {
        // Check if customer has tenders
        if ($customer->tenders()->count() > 0) {
            return redirect()->back()
                ->with('error', 'Cannot delete customer with existing tenders. Deactivate instead.');
        }
        
        $customer->delete();
        
        return redirect()->route('admin.customers.index')
            ->with('success', 'Customer deleted successfully.');
    }
}
