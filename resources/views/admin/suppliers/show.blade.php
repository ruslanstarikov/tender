@extends('base')

@section('title', $supplier->company_name)

@section('content')
<div class="container mx-auto">
    <!-- Header -->
    <div class="flex justify-between items-start mb-6">
        <div>
            <div class="breadcrumbs text-sm">
                <ul>
                    <li><a href="{{ route('admin.suppliers.index') }}" class="link link-primary">Suppliers</a></li>
                    <li>{{ $supplier->company_name }}</li>
                </ul>
            </div>
            <div class="flex items-center gap-4 mt-2">
                @if($supplier->logo_url)
                    <div class="avatar">
                        <div class="w-16 rounded">
                            <img src="{{ $supplier->logo_url }}" alt="{{ $supplier->company_name }} logo" />
                        </div>
                    </div>
                @endif
                <div>
                    <h1 class="text-3xl font-bold">{{ $supplier->company_name }}</h1>
                    <div class="flex items-center gap-4 mt-2">
                        @if($supplier->is_active)
                            <div class="badge badge-success">Active</div>
                        @else
                            <div class="badge badge-error">Inactive</div>
                        @endif
                        @if($supplier->is_verified)
                            <div class="badge badge-success">
                                <i class="iconoir-check-circle text-xs mr-1"></i>
                                Verified
                            </div>
                        @else
                            <div class="badge badge-warning">
                                <i class="iconoir-warning-triangle text-xs mr-1"></i>
                                Unverified
                            </div>
                        @endif
                        <div class="badge badge-{{ $supplier->status_badge_color }}">
                            {{ \App\Models\Supplier::getOnboardingStatuses()[$supplier->onboarding_status] }}
                        </div>
                        <span class="text-sm text-base-content/60">ID: {{ $supplier->id }}</span>
                    </div>
                </div>
            </div>
        </div>
        <div class="flex gap-2">
            <a href="{{ route('admin.suppliers.edit', $supplier) }}" class="btn btn-primary">
                <i class="iconoir-edit"></i>
                Edit Supplier
            </a>
            <div class="dropdown dropdown-end">
                <label tabindex="0" class="btn btn-outline">
                    <i class="iconoir-more-vert"></i>
                    Actions
                </label>
                <ul tabindex="0" class="dropdown-content menu p-2 shadow bg-base-100 rounded-box w-56">
                    <li>
                        <form method="POST" action="{{ route('admin.suppliers.reset-password', $supplier) }}">
                            @csrf
                            <button type="submit" class="w-full text-left" onclick="return confirm('Reset password for {{ $supplier->company_name }}?')">
                                <i class="iconoir-lock"></i>
                                Reset Password
                            </button>
                        </form>
                    </li>
                    <li>
                        <form method="POST" action="{{ route('admin.suppliers.toggle-status', $supplier) }}">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="w-full text-left">
                                <i class="iconoir-{{ $supplier->is_active ? 'pause' : 'play' }}"></i>
                                {{ $supplier->is_active ? 'Deactivate' : 'Activate' }} Supplier
                            </button>
                        </form>
                    </li>
                    <li>
                        <form method="POST" action="{{ route('admin.suppliers.toggle-verification', $supplier) }}">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="w-full text-left">
                                <i class="iconoir-{{ $supplier->is_verified ? 'cancel' : 'check-circle' }}"></i>
                                {{ $supplier->is_verified ? 'Unverify' : 'Verify' }} Supplier
                            </button>
                        </form>
                    </li>
                    <li>
                        <form method="POST" action="{{ route('admin.suppliers.destroy', $supplier) }}">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="w-full text-left text-error" onclick="return confirm('Delete {{ $supplier->company_name }}? This cannot be undone.')">
                                <i class="iconoir-trash"></i>
                                Delete Supplier
                            </button>
                        </form>
                    </li>
                </ul>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Content -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Company Information -->
            <div class="card bg-base-100 shadow">
                <div class="card-body">
                    <h2 class="card-title">
                        <i class="iconoir-building"></i>
                        Company Information
                    </h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="label">
                                <span class="label-text font-semibold">Company Name</span>
                            </label>
                            <p class="text-base">{{ $supplier->company_name }}</p>
                        </div>
                        @if($supplier->abn)
                            <div>
                                <label class="label">
                                    <span class="label-text font-semibold">ABN</span>
                                </label>
                                <p class="text-base">{{ $supplier->formatted_abn }}</p>
                            </div>
                        @endif
                        @if($supplier->website)
                            <div>
                                <label class="label">
                                    <span class="label-text font-semibold">Website</span>
                                </label>
                                <p class="text-base">
                                    <a href="{{ $supplier->website }}" target="_blank" class="link link-primary">
                                        {{ $supplier->website }}
                                        <i class="iconoir-open-new-window text-xs ml-1"></i>
                                    </a>
                                </p>
                            </div>
                        @endif
                        @if($supplier->established_date)
                            <div>
                                <label class="label">
                                    <span class="label-text font-semibold">Established</span>
                                </label>
                                <p class="text-base">{{ $supplier->established_date->format('j F Y') }}</p>
                            </div>
                        @endif
                    </div>
                    @if($supplier->company_description)
                        <div class="mt-4">
                            <label class="label">
                                <span class="label-text font-semibold">Company Description</span>
                            </label>
                            <p class="text-base whitespace-pre-wrap">{{ $supplier->company_description }}</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Contact Person -->
            <div class="card bg-base-100 shadow">
                <div class="card-body">
                    <h2 class="card-title">
                        <i class="iconoir-user"></i>
                        Contact Person
                    </h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="label">
                                <span class="label-text font-semibold">Full Name</span>
                            </label>
                            <p class="text-base">{{ $supplier->full_name }}</p>
                        </div>
                        @if($supplier->position)
                            <div>
                                <label class="label">
                                    <span class="label-text font-semibold">Position</span>
                                </label>
                                <p class="text-base">{{ $supplier->position }}</p>
                            </div>
                        @endif
                        <div>
                            <label class="label">
                                <span class="label-text font-semibold">Email</span>
                            </label>
                            <p class="text-base">
                                <a href="mailto:{{ $supplier->email }}" class="link link-primary">
                                    {{ $supplier->email }}
                                </a>
                            </p>
                        </div>
                        <div>
                            <label class="label">
                                <span class="label-text font-semibold">Phone</span>
                            </label>
                            <p class="text-base">
                                <a href="tel:{{ $supplier->phone }}" class="link link-primary">
                                    {{ $supplier->phone }}
                                </a>
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Business Address -->
            <div class="card bg-base-100 shadow">
                <div class="card-body">
                    <h2 class="card-title">
                        <i class="iconoir-map-pin"></i>
                        Business Address
                    </h2>
                    @if($supplier->full_address)
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            @if($supplier->unit_number)
                                <div>
                                    <label class="label">
                                        <span class="label-text font-semibold">Unit Number</span>
                                    </label>
                                    <p class="text-base">{{ $supplier->unit_number }}</p>
                                </div>
                            @endif
                            @if($supplier->street_number)
                                <div>
                                    <label class="label">
                                        <span class="label-text font-semibold">Street Number</span>
                                    </label>
                                    <p class="text-base">{{ $supplier->street_number }}</p>
                                </div>
                            @endif
                            @if($supplier->street_name)
                                <div>
                                    <label class="label">
                                        <span class="label-text font-semibold">Street Name</span>
                                    </label>
                                    <p class="text-base">{{ $supplier->street_name }} {{ $supplier->street_type }}</p>
                                </div>
                            @endif
                            @if($supplier->suburb)
                                <div>
                                    <label class="label">
                                        <span class="label-text font-semibold">Suburb</span>
                                    </label>
                                    <p class="text-base">{{ $supplier->suburb }}</p>
                                </div>
                            @endif
                            @if($supplier->state)
                                <div>
                                    <label class="label">
                                        <span class="label-text font-semibold">State</span>
                                    </label>
                                    <p class="text-base">{{ $supplier->state }}</p>
                                </div>
                            @endif
                            @if($supplier->postcode)
                                <div>
                                    <label class="label">
                                        <span class="label-text font-semibold">Postcode</span>
                                    </label>
                                    <p class="text-base">{{ $supplier->postcode }}</p>
                                </div>
                            @endif
                        </div>
                        <div class="mt-4">
                            <label class="label">
                                <span class="label-text font-semibold">Full Address</span>
                            </label>
                            <p class="text-base">{{ $supplier->full_address }}</p>
                        </div>
                    @else
                        <p class="text-base-content/60">No address information available.</p>
                    @endif
                </div>
            </div>

            <!-- Business Details -->
            <div class="card bg-base-100 shadow">
                <div class="card-body">
                    <h2 class="card-title">
                        <i class="iconoir-tools"></i>
                        Business Details
                    </h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="label">
                                <span class="label-text font-semibold">Specialty Areas</span>
                            </label>
                            @if($supplier->specialty_areas && count($supplier->specialty_areas) > 0)
                                <div class="flex flex-wrap gap-1">
                                    @foreach($supplier->specialty_areas as $specialty)
                                        <div class="badge badge-outline">{{ $specialty }}</div>
                                    @endforeach
                                </div>
                            @else
                                <p class="text-base-content/60">Not specified</p>
                            @endif
                        </div>
                        @if($supplier->minimum_order_value)
                            <div>
                                <label class="label">
                                    <span class="label-text font-semibold">Minimum Order Value</span>
                                </label>
                                <p class="text-base">${{ number_format($supplier->minimum_order_value, 2) }}</p>
                            </div>
                        @endif
                        @if($supplier->lead_time_days)
                            <div>
                                <label class="label">
                                    <span class="label-text font-semibold">Lead Time</span>
                                </label>
                                <p class="text-base">{{ $supplier->lead_time_days }} days</p>
                            </div>
                        @endif
                    </div>
                    @if($supplier->certifications)
                        <div class="mt-4">
                            <label class="label">
                                <span class="label-text font-semibold">Certifications</span>
                            </label>
                            <p class="text-base whitespace-pre-wrap">{{ $supplier->certifications }}</p>
                        </div>
                    @endif
                </div>
            </div>

            @if($supplier->admin_notes)
                <!-- Admin Notes -->
                <div class="card bg-base-200 shadow">
                    <div class="card-body">
                        <h2 class="card-title">
                            <i class="iconoir-notes"></i>
                            Admin Notes
                        </h2>
                        <p class="text-base whitespace-pre-wrap">{{ $supplier->admin_notes }}</p>
                    </div>
                </div>
            @endif
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Quick Stats -->
            <div class="card bg-base-100 shadow">
                <div class="card-body">
                    <h2 class="card-title">Quick Info</h2>
                    <div class="stats stats-vertical shadow-none">
                        <div class="stat">
                            <div class="stat-title">Member Since</div>
                            <div class="stat-value text-sm">{{ $supplier->created_at->format('M Y') }}</div>
                        </div>
                        <div class="stat">
                            <div class="stat-title">Last Login</div>
                            <div class="stat-value text-sm">
                                {{ $supplier->last_login_at ? $supplier->last_login_at->diffForHumans() : 'Never' }}
                            </div>
                        </div>
                        @if($supplier->established_date)
                            <div class="stat">
                                <div class="stat-title">Years in Business</div>
                                <div class="stat-value text-sm">
                                    {{ now()->diffInYears($supplier->established_date) }} years
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Update Onboarding Status -->
            <div class="card bg-base-100 shadow">
                <div class="card-body">
                    <h2 class="card-title">Onboarding Status</h2>
                    <form method="POST" action="{{ route('admin.suppliers.onboarding-status', $supplier) }}">
                        @csrf
                        @method('PATCH')
                        
                        <div class="form-control mb-4">
                            <label class="label">
                                <span class="label-text">Status</span>
                            </label>
                            <select name="onboarding_status" class="select select-bordered select-sm">
                                @foreach(\App\Models\Supplier::getOnboardingStatuses() as $value => $label)
                                    <option value="{{ $value }}" {{ $supplier->onboarding_status === $value ? 'selected' : '' }}>
                                        {{ $label }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        
                        <div class="form-control mb-4">
                            <label class="label">
                                <span class="label-text">Admin Notes</span>
                            </label>
                            <textarea name="admin_notes" rows="3" 
                                      placeholder="Notes about status change..." 
                                      class="textarea textarea-bordered textarea-sm">{{ $supplier->admin_notes }}</textarea>
                        </div>
                        
                        <button type="submit" class="btn btn-primary btn-sm w-full">
                            Update Status
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection