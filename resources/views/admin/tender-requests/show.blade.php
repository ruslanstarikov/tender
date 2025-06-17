@extends('base')

@section('title', 'View Tender Request')

@section('content')
<div class="container mx-auto">
    <!-- Header -->
    <div class="flex justify-between items-start mb-6">
        <div>
            <div class="breadcrumbs text-sm">
                <ul>
                    <li><a href="{{ route('admin.tender-requests.index') }}" class="link link-primary">Tender Requests</a></li>
                    <li>View Request</li>
                </ul>
            </div>
            <h1 class="text-3xl font-bold mt-2">Tender Request Details</h1>
        </div>
        <div class="flex gap-2">
            <a href="{{ route('admin.tender-requests.edit', $tenderRequest) }}" class="btn btn-primary">
                <i class="iconoir-edit"></i>
                Edit Request
            </a>
            @if($tenderRequest->status !== 'cancelled')
                <form action="{{ route('admin.tender-requests.convert-to-tender', $tenderRequest) }}" method="POST" class="inline">
                    @csrf
                    <button type="submit" class="btn btn-primary" onclick="return confirm('Convert this request to a tender?')">
                        <i class="iconoir-add-page"></i>
                        Convert to Tender
                    </button>
                </form>
            @endif
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Content -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Customer Information -->
            <div class="card bg-base-100 shadow">
                <div class="card-body">
                    <h2 class="card-title">
                        <i class="iconoir-user"></i>
                        Customer Information
                    </h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="label">
                                <span class="label-text font-semibold">Customer Name</span>
                            </label>
                            <p class="text-base">
                                <a href="{{ route('admin.customers.show', $tenderRequest->customer) }}" class="link link-primary">
                                    {{ $tenderRequest->customer->full_name }}
                                </a>
                            </p>
                        </div>
                        <div>
                            <label class="label">
                                <span class="label-text font-semibold">Contact Preference</span>
                            </label>
                            <p class="text-base capitalize">{{ $tenderRequest->preferred_contact_method }}</p>
                        </div>
                        <div>
                            <label class="label">
                                <span class="label-text font-semibold">Email</span>
                            </label>
                            <p class="text-base">
                                <a href="mailto:{{ $tenderRequest->customer->email }}" class="link link-primary">
                                    {{ $tenderRequest->customer->email }}
                                </a>
                            </p>
                        </div>
                        <div>
                            <label class="label">
                                <span class="label-text font-semibold">Phone</span>
                            </label>
                            <p class="text-base">
                                <a href="tel:{{ $tenderRequest->customer->phone }}" class="link link-primary">
                                    {{ $tenderRequest->customer->phone }}
                                </a>
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Property Information -->
            <div class="card bg-base-100 shadow">
                <div class="card-body">
                    <h2 class="card-title">
                        <i class="iconoir-map-pin"></i>
                        Property Information
                    </h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="label">
                                <span class="label-text font-semibold">Property Address</span>
                            </label>
                            <p class="text-base">{{ $tenderRequest->property_address }}</p>
                        </div>
                        <div>
                            <label class="label">
                                <span class="label-text font-semibold">Suburb</span>
                            </label>
                            <p class="text-base">{{ $tenderRequest->suburb }}</p>
                        </div>
                        <div>
                            <label class="label">
                                <span class="label-text font-semibold">State</span>
                            </label>
                            <p class="text-base">{{ $tenderRequest->state }}</p>
                        </div>
                        <div>
                            <label class="label">
                                <span class="label-text font-semibold">Postcode</span>
                            </label>
                            <p class="text-base">{{ $tenderRequest->postcode }}</p>
                        </div>
                        <div>
                            <label class="label">
                                <span class="label-text font-semibold">Number of Windows</span>
                            </label>
                            <p class="text-base">{{ $tenderRequest->number_of_windows }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Notes -->
            <div class="card bg-base-100 shadow">
                <div class="card-body">
                    <h2 class="card-title">
                        <i class="iconoir-notes"></i>
                        Notes
                    </h2>
                    <div class="space-y-4">
                        @if($tenderRequest->customer_notes)
                            <div>
                                <label class="label">
                                    <span class="label-text font-semibold">Customer Notes</span>
                                </label>
                                <p class="text-base whitespace-pre-wrap">{{ $tenderRequest->customer_notes }}</p>
                            </div>
                        @endif
                        @if($tenderRequest->admin_notes)
                            <div>
                                <label class="label">
                                    <span class="label-text font-semibold">Admin Notes</span>
                                </label>
                                <p class="text-base whitespace-pre-wrap">{{ $tenderRequest->admin_notes }}</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Status Information -->
            <div class="card bg-base-100 shadow">
                <div class="card-body">
                    <h2 class="card-title">Status Information</h2>
                    <div class="stats stats-vertical shadow-none">
                        <div class="stat">
                            <div class="stat-title">Current Status</div>
                            <div class="stat-value text-sm">
                                <div class="badge {{ $tenderRequest->status_badge_color }}">
                                    {{ ucfirst($tenderRequest->status) }}
                                </div>
                            </div>
                        </div>
                        <div class="stat">
                            <div class="stat-title">Appointment Date</div>
                            <div class="stat-value text-sm">{{ $tenderRequest->appointment_datetime->format('j M Y') }}</div>
                            <div class="stat-desc">{{ $tenderRequest->appointment_datetime->format('g:i A') }}</div>
                        </div>
                        <div class="stat">
                            <div class="stat-title">Created</div>
                            <div class="stat-value text-sm">{{ $tenderRequest->created_at->format('j M Y') }}</div>
                            <div class="stat-desc">{{ $tenderRequest->created_at->diffForHumans() }}</div>
                        </div>
                        @if($tenderRequest->updated_at->ne($tenderRequest->created_at))
                            <div class="stat">
                                <div class="stat-title">Last Updated</div>
                                <div class="stat-value text-sm">{{ $tenderRequest->updated_at->format('j M Y') }}</div>
                                <div class="stat-desc">{{ $tenderRequest->updated_at->diffForHumans() }}</div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection