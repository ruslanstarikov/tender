@extends('base')

@section('title', $customer->full_name)

@section('content')
<div class="container mx-auto">
    <!-- Header -->
    <div class="flex justify-between items-start mb-6">
        <div>
            <div class="breadcrumbs text-sm">
                <ul>
                    <li><a href="{{ route('admin.customers.index') }}" class="link link-primary">Customers</a></li>
                    <li>{{ $customer->full_name }}</li>
                </ul>
            </div>
            <h1 class="text-3xl font-bold mt-2">{{ $customer->full_name }}</h1>
            <div class="flex items-center gap-4 mt-2">
                @if($customer->is_active)
                    <div class="badge badge-success">Active</div>
                @else
                    <div class="badge badge-error">Inactive</div>
                @endif
                <span class="text-sm text-base-content/60">Customer ID: {{ $customer->id }}</span>
            </div>
        </div>
        <div class="flex gap-2">
            <a href="{{ route('admin.customers.edit', $customer) }}" class="btn btn-primary">
                <i class="iconoir-edit"></i>
                Edit Customer
            </a>
            <div class="dropdown dropdown-end">
                <label tabindex="0" class="btn btn-outline">
                    <i class="iconoir-more-vert"></i>
                    Actions
                </label>
                <ul tabindex="0" class="dropdown-content menu p-2 shadow bg-base-100 rounded-box w-56">
                    <li>
                        <form method="POST" action="{{ route('admin.customers.reset-password', $customer) }}">
                            @csrf
                            <button type="submit" class="w-full text-left" onclick="return confirm('Reset password for {{ $customer->full_name }}?')">
                                <i class="iconoir-lock"></i>
                                Reset Password
                            </button>
                        </form>
                    </li>
                    <li>
                        <form method="POST" action="{{ route('admin.customers.toggle-status', $customer) }}">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="w-full text-left">
                                <i class="iconoir-{{ $customer->is_active ? 'pause' : 'play' }}"></i>
                                {{ $customer->is_active ? 'Deactivate' : 'Activate' }} Customer
                            </button>
                        </form>
                    </li>
                    @if($customer->tenders->count() === 0)
                        <li>
                            <form method="POST" action="{{ route('admin.customers.destroy', $customer) }}">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="w-full text-left text-error" onclick="return confirm('Delete {{ $customer->full_name }}? This cannot be undone.')">
                                    <i class="iconoir-trash"></i>
                                    Delete Customer
                                </button>
                            </form>
                        </li>
                    @endif
                </ul>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Customer Details -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Personal Information -->
            <div class="card bg-base-100 shadow">
                <div class="card-body">
                    <h2 class="card-title">
                        <i class="iconoir-user"></i>
                        Personal Information
                    </h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="label">
                                <span class="label-text font-semibold">First Name</span>
                            </label>
                            <p class="text-base">{{ $customer->first_name }}</p>
                        </div>
                        <div>
                            <label class="label">
                                <span class="label-text font-semibold">Last Name</span>
                            </label>
                            <p class="text-base">{{ $customer->last_name }}</p>
                        </div>
                        <div>
                            <label class="label">
                                <span class="label-text font-semibold">Email</span>
                            </label>
                            <p class="text-base">
                                <a href="mailto:{{ $customer->email }}" class="link link-primary">
                                    {{ $customer->email }}
                                </a>
                            </p>
                        </div>
                        <div>
                            <label class="label">
                                <span class="label-text font-semibold">Phone</span>
                            </label>
                            <p class="text-base">
                                <a href="tel:{{ $customer->phone }}" class="link link-primary">
                                    {{ $customer->phone }}
                                </a>
                            </p>
                        </div>
                        @if($customer->date_of_birth)
                            <div>
                                <label class="label">
                                    <span class="label-text font-semibold">Date of Birth</span>
                                </label>
                                <p class="text-base">{{ $customer->date_of_birth->format('j F Y') }}</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Address Information -->
            <div class="card bg-base-100 shadow">
                <div class="card-body">
                    <h2 class="card-title">
                        <i class="iconoir-map-pin"></i>
                        Address
                    </h2>
                    @if($customer->full_address)
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            @if($customer->unit_number)
                                <div>
                                    <label class="label">
                                        <span class="label-text font-semibold">Unit Number</span>
                                    </label>
                                    <p class="text-base">{{ $customer->unit_number }}</p>
                                </div>
                            @endif
                            @if($customer->street_number)
                                <div>
                                    <label class="label">
                                        <span class="label-text font-semibold">Street Number</span>
                                    </label>
                                    <p class="text-base">{{ $customer->street_number }}</p>
                                </div>
                            @endif
                            @if($customer->street_name)
                                <div>
                                    <label class="label">
                                        <span class="label-text font-semibold">Street Name</span>
                                    </label>
                                    <p class="text-base">{{ $customer->street_name }} {{ $customer->street_type }}</p>
                                </div>
                            @endif
                            @if($customer->suburb)
                                <div>
                                    <label class="label">
                                        <span class="label-text font-semibold">Suburb</span>
                                    </label>
                                    <p class="text-base">{{ $customer->suburb }}</p>
                                </div>
                            @endif
                            @if($customer->state)
                                <div>
                                    <label class="label">
                                        <span class="label-text font-semibold">State</span>
                                    </label>
                                    <p class="text-base">{{ $customer->state }}</p>
                                </div>
                            @endif
                            @if($customer->postcode)
                                <div>
                                    <label class="label">
                                        <span class="label-text font-semibold">Postcode</span>
                                    </label>
                                    <p class="text-base">{{ $customer->postcode }}</p>
                                </div>
                            @endif
                        </div>
                        <div class="mt-4">
                            <label class="label">
                                <span class="label-text font-semibold">Full Address</span>
                            </label>
                            <p class="text-base">{{ $customer->full_address }}</p>
                        </div>
                    @else
                        <p class="text-base-content/60">No address information available.</p>
                    @endif
                </div>
            </div>

            @if($customer->notes)
                <!-- Notes -->
                <div class="card bg-base-100 shadow">
                    <div class="card-body">
                        <h2 class="card-title">
                            <i class="iconoir-notes"></i>
                            Notes
                        </h2>
                        <p class="text-base whitespace-pre-wrap">{{ $customer->notes }}</p>
                    </div>
                </div>
            @endif
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Quick Stats -->
            <div class="card bg-base-100 shadow">
                <div class="card-body">
                    <h2 class="card-title">Quick Stats</h2>
                    <div class="stats stats-vertical shadow-none">
                        <div class="stat">
                            <div class="stat-title">Total Tenders</div>
                            <div class="stat-value text-primary">{{ $customer->tenders->count() }}</div>
                        </div>
                        <div class="stat">
                            <div class="stat-title">Member Since</div>
                            <div class="stat-value text-sm">{{ $customer->created_at->format('M Y') }}</div>
                        </div>
                        <div class="stat">
                            <div class="stat-title">Last Login</div>
                            <div class="stat-value text-sm">
                                {{ $customer->last_login_at ? $customer->last_login_at->diffForHumans() : 'Never' }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Tenders Section -->
    <div id="tenders" class="mt-8">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-2xl font-bold">Customer Tenders</h2>
            <a href="{{ route('admin.tenders.create') }}?customer_id={{ $customer->id }}" class="btn btn-primary btn-sm">
                <i class="iconoir-plus"></i>
                Create Tender
            </a>
        </div>
        
        @if($customer->tenders->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                @foreach($customer->tenders as $tender)
                    <div class="card bg-base-100 shadow">
                        <div class="card-body">
                            <h3 class="card-title text-lg">
                                <a href="{{ route('admin.tenders.show', $tender) }}" class="link link-primary">
                                    {{ $tender->project_title }}
                                </a>
                            </h3>
                            <div class="space-y-2 text-sm">
                                <div class="flex justify-between">
                                    <span class="text-base-content/60">Status:</span>
                                    <div class="badge badge-{{ $tender->tender_status == 'pending' ? 'warning' : ($tender->tender_status == 'approved' ? 'success' : 'error') }} badge-sm">
                                        {{ ucfirst($tender->tender_status) }}
                                    </div>
                                </div>
                                @if($tender->work_start_datetime)
                                    <div class="flex justify-between">
                                        <span class="text-base-content/60">Work Start:</span>
                                        <span>{{ \Carbon\Carbon::parse($tender->work_start_datetime)->format('M d, Y') }}</span>
                                    </div>
                                @endif
                                <div class="flex justify-between">
                                    <span class="text-base-content/60">Created:</span>
                                    <span>{{ $tender->created_at->format('M d, Y') }}</span>
                                </div>
                                @if($tender->media->count() > 0)
                                    <div class="flex justify-between">
                                        <span class="text-base-content/60">Media:</span>
                                        <span>{{ $tender->media->count() }} files</span>
                                    </div>
                                @endif
                            </div>
                            <div class="card-actions justify-end mt-4">
                                <a href="{{ route('admin.tenders.show', $tender) }}" class="btn btn-primary btn-sm">
                                    View Details
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="text-center py-8">
                <i class="iconoir-page-edit text-4xl text-base-content/30 mb-2 block"></i>
                <p class="text-base-content/60 mb-4">This customer has no tenders yet.</p>
                <a href="{{ route('admin.tenders.create') }}?customer_id={{ $customer->id }}" class="btn btn-primary btn-sm">
                    <i class="iconoir-plus"></i>
                    Create First Tender
                </a>
            </div>
        @endif
    </div>
</div>
@endsection