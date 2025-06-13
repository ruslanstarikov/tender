<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class AuthController extends Controller
{
    /**
     * Show the customer registration form.
     */
    public function showRegistrationForm()
    {
        if (Auth::guard('customer')->check()) {
            return redirect()->route('customer.dashboard');
        }

        $states = Customer::getAustralianStates();
        $streetTypes = Customer::getStreetTypes();
        
        return view('customer.auth.register', compact('states', 'streetTypes'));
    }

    /**
     * Handle customer registration.
     */
    public function register(Request $request)
    {
        $validated = $request->validate([
            // Personal Information
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|unique:customers,email',
            'phone' => 'required|string|max:20',
            'password' => 'required|string|min:8|confirmed',
            'date_of_birth' => 'nullable|date|before:today',
            
            // Address
            'street_number' => 'nullable|string|max:10',
            'street_name' => 'nullable|string|max:255',
            'street_type' => 'nullable|string|max:50',
            'unit_number' => 'nullable|string|max:10',
            'suburb' => 'nullable|string|max:100',
            'state' => ['nullable', Rule::in(array_keys(Customer::getAustralianStates()))],
            'postcode' => 'nullable|string|regex:/^[0-9]{4}$/',
        ]);

        $validated['password'] = Hash::make($validated['password']);
        $validated['is_active'] = true;

        $customer = Customer::create($validated);

        Auth::guard('customer')->login($customer);

        return redirect()->route('customer.dashboard')
            ->with('success', 'Registration successful! Welcome to our platform.');
    }

    /**
     * Show the customer login form.
     */
    public function showLoginForm()
    {
        if (Auth::guard('customer')->check()) {
            return redirect()->route('customer.dashboard');
        }

        return view('customer.auth.login');
    }

    /**
     * Handle customer login.
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        if (Auth::guard('customer')->attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();

            return redirect()->intended(route('customer.dashboard'))
                ->with('success', 'Welcome back!');
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }

    /**
     * Handle customer logout.
     */
    public function logout(Request $request)
    {
        Auth::guard('customer')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('customer.login')
            ->with('success', 'You have been logged out successfully.');
    }
}