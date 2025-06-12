<?php

namespace App\Http\Controllers;

use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class SupplierController extends Controller
{
    /**
     * Display a listing of suppliers.
     */
    public function index(Request $request)
    {
        $query = Supplier::query();
        
        // Search functionality
        if ($request->filled('search')) {
            $search = $request->get('search');
            $query->where(function ($q) use ($search) {
                $q->where('company_name', 'like', "%{$search}%")
                  ->orWhere('first_name', 'like', "%{$search}%")
                  ->orWhere('last_name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%")
                  ->orWhere('suburb', 'like', "%{$search}%")
                  ->orWhere('abn', 'like', "%{$search}%");
            });
        }
        
        // Filter by status
        if ($request->filled('status')) {
            if ($request->get('status') === 'active') {
                $query->active();
            } elseif ($request->get('status') === 'inactive') {
                $query->where('is_active', false);
            }
        }
        
        // Filter by onboarding status
        if ($request->filled('onboarding_status')) {
            $query->byStatus($request->get('onboarding_status'));
        }
        
        // Filter by verification
        if ($request->filled('verified')) {
            if ($request->get('verified') === 'verified') {
                $query->verified();
            } elseif ($request->get('verified') === 'unverified') {
                $query->where('is_verified', false);
            }
        }
        
        // Filter by state
        if ($request->filled('state')) {
            $query->byState($request->get('state'));
        }
        
        // Sort
        $sortBy = $request->get('sort', 'created_at');
        $sortDirection = $request->get('direction', 'desc');
        $query->orderBy($sortBy, $sortDirection);
        
        $suppliers = $query->paginate(15);
        
        // Get filter options
        $states = Supplier::getAustralianStates();
        $onboardingStatuses = Supplier::getOnboardingStatuses();
        
        return view('admin.suppliers.index', compact('suppliers', 'states', 'onboardingStatuses'));
    }

    /**
     * Show the form for creating a new supplier.
     */
    public function create()
    {
        $states = Supplier::getAustralianStates();
        $streetTypes = Supplier::getStreetTypes();
        $specialtyAreas = Supplier::getSpecialtyAreas();
        $onboardingStatuses = Supplier::getOnboardingStatuses();
        
        return view('admin.suppliers.create', compact('states', 'streetTypes', 'specialtyAreas', 'onboardingStatuses'));
    }

    /**
     * Store a newly created supplier.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            // Company Information
            'company_name' => 'required|string|max:255',
            'abn' => 'nullable|string|size:11|unique:suppliers,abn',
            'website' => 'nullable|url|max:255',
            'company_description' => 'nullable|string|max:1000',
            'established_date' => 'nullable|date|before:today',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            
            // Contact Person
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'position' => 'nullable|string|max:100',
            'email' => 'required|email|unique:suppliers,email',
            'phone' => 'required|string|max:20',
            'password' => 'required|string|min:8|confirmed',
            
            // Address
            'street_number' => 'nullable|string|max:10',
            'street_name' => 'nullable|string|max:255',
            'street_type' => 'nullable|string|max:50',
            'unit_number' => 'nullable|string|max:10',
            'suburb' => 'nullable|string|max:100',
            'state' => ['nullable', Rule::in(array_keys(Supplier::getAustralianStates()))],
            'postcode' => 'nullable|string|regex:/^[0-9]{4}$/',
            
            // Business Details
            'specialty_areas' => 'nullable|array',
            'specialty_areas.*' => 'string|max:100',
            'certifications' => 'nullable|string|max:1000',
            'minimum_order_value' => 'nullable|numeric|min:0',
            'lead_time_days' => 'nullable|integer|min:0|max:365',
            
            // Status
            'is_active' => 'boolean',
            'is_verified' => 'boolean',
            'onboarding_status' => ['required', Rule::in(array_keys(Supplier::getOnboardingStatuses()))],
            'admin_notes' => 'nullable|string|max:1000',
        ]);

        // Handle logo upload
        if ($request->hasFile('logo')) {
            $logoPath = $request->file('logo')->store('suppliers/logos', 'public');
            $validated['logo_path'] = $logoPath;
        }

        $supplier = Supplier::create($validated);

        return redirect()->route('admin.suppliers.show', $supplier)
            ->with('success', 'Supplier created successfully.');
    }

    /**
     * Display the specified supplier.
     */
    public function show(Supplier $supplier)
    {
        return view('admin.suppliers.show', compact('supplier'));
    }

    /**
     * Show the form for editing the specified supplier.
     */
    public function edit(Supplier $supplier)
    {
        $states = Supplier::getAustralianStates();
        $streetTypes = Supplier::getStreetTypes();
        $specialtyAreas = Supplier::getSpecialtyAreas();
        $onboardingStatuses = Supplier::getOnboardingStatuses();
        
        return view('admin.suppliers.edit', compact('supplier', 'states', 'streetTypes', 'specialtyAreas', 'onboardingStatuses'));
    }

    /**
     * Update the specified supplier.
     */
    public function update(Request $request, Supplier $supplier)
    {
        $validated = $request->validate([
            // Company Information
            'company_name' => 'required|string|max:255',
            'abn' => ['nullable', 'string', 'size:11', Rule::unique('suppliers')->ignore($supplier->id)],
            'website' => 'nullable|url|max:255',
            'company_description' => 'nullable|string|max:1000',
            'established_date' => 'nullable|date|before:today',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            
            // Contact Person
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'position' => 'nullable|string|max:100',
            'email' => ['required', 'email', Rule::unique('suppliers')->ignore($supplier->id)],
            'phone' => 'required|string|max:20',
            
            // Address
            'street_number' => 'nullable|string|max:10',
            'street_name' => 'nullable|string|max:255',
            'street_type' => 'nullable|string|max:50',
            'unit_number' => 'nullable|string|max:10',
            'suburb' => 'nullable|string|max:100',
            'state' => ['nullable', Rule::in(array_keys(Supplier::getAustralianStates()))],
            'postcode' => 'nullable|string|regex:/^[0-9]{4}$/',
            
            // Business Details
            'specialty_areas' => 'nullable|array',
            'specialty_areas.*' => 'string|max:100',
            'certifications' => 'nullable|string|max:1000',
            'minimum_order_value' => 'nullable|numeric|min:0',
            'lead_time_days' => 'nullable|integer|min:0|max:365',
            
            // Status
            'is_active' => 'boolean',
            'is_verified' => 'boolean',
            'onboarding_status' => ['required', Rule::in(array_keys(Supplier::getOnboardingStatuses()))],
            'admin_notes' => 'nullable|string|max:1000',
        ]);

        // Handle logo upload
        if ($request->hasFile('logo')) {
            // Delete old logo if exists
            if ($supplier->logo_path) {
                Storage::disk('public')->delete($supplier->logo_path);
            }
            
            $logoPath = $request->file('logo')->store('suppliers/logos', 'public');
            $validated['logo_path'] = $logoPath;
        }

        $supplier->update($validated);

        return redirect()->route('admin.suppliers.show', $supplier)
            ->with('success', 'Supplier updated successfully.');
    }

    /**
     * Reset supplier password.
     */
    public function resetPassword(Supplier $supplier)
    {
        $newPassword = $supplier->resetPassword();
        
        return redirect()->route('admin.suppliers.show', $supplier)
            ->with('success', "Password reset successfully. New password: {$newPassword}");
    }

    /**
     * Toggle supplier active status.
     */
    public function toggleStatus(Supplier $supplier)
    {
        $supplier->update(['is_active' => !$supplier->is_active]);
        
        $status = $supplier->is_active ? 'activated' : 'deactivated';
        
        return redirect()->back()
            ->with('success', "Supplier {$status} successfully.");
    }

    /**
     * Toggle supplier verification status.
     */
    public function toggleVerification(Supplier $supplier)
    {
        $supplier->update(['is_verified' => !$supplier->is_verified]);
        
        $status = $supplier->is_verified ? 'verified' : 'unverified';
        
        return redirect()->back()
            ->with('success', "Supplier {$status} successfully.");
    }

    /**
     * Update supplier onboarding status.
     */
    public function updateOnboardingStatus(Request $request, Supplier $supplier)
    {
        $validated = $request->validate([
            'onboarding_status' => ['required', Rule::in(array_keys(Supplier::getOnboardingStatuses()))],
            'admin_notes' => 'nullable|string|max:1000',
        ]);

        $supplier->update($validated);

        return redirect()->back()
            ->with('success', 'Onboarding status updated successfully.');
    }

    /**
     * Remove the specified supplier.
     */
    public function destroy(Supplier $supplier)
    {
        // Delete logo if exists
        if ($supplier->logo_path) {
            Storage::disk('public')->delete($supplier->logo_path);
        }
        
        $supplier->delete();
        
        return redirect()->route('admin.suppliers.index')
            ->with('success', 'Supplier deleted successfully.');
    }
}
