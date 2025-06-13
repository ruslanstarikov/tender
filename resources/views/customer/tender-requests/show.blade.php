@extends('customer.layout')

@section('title', 'Tender Request Details')

@section('content')
<div class="max-w-4xl mx-auto">
    <!-- Header -->
    <div class="bg-base-100 p-6 rounded-lg shadow mb-6">
        <div class="flex justify-between items-start mb-4">
            <div>
                <h1 class="text-3xl font-bold">Tender Request #{{ $tenderRequest->id }}</h1>
                <p class="text-base-content/60">{{ $tenderRequest->property_address }}</p>
            </div>
            <span class="badge {{ $tenderRequest->status_badge_color }} badge-lg">{{ ucfirst($tenderRequest->status) }}</span>
        </div>
        
        <div class="flex gap-4">
            <a href="{{ route('customer.tender-requests.index') }}" class="btn btn-outline">
                <i class="iconoir-arrow-left"></i>
                Back to Requests
            </a>
            @if($tenderRequest->status === 'pending')
                <button class="btn btn-warning" onclick="alert('Contact support to modify your request')">
                    <i class="iconoir-edit"></i>
                    Modify Request
                </button>
            @endif
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Details -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Property Information -->
            <div class="bg-base-100 p-6 rounded-lg shadow">
                <h2 class="text-xl font-semibold mb-4">
                    <i class="iconoir-home"></i>
                    Property Information
                </h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="text-sm font-medium text-base-content/60">Address</label>
                        <p class="text-lg">{{ $tenderRequest->property_address }}</p>
                    </div>
                    @if($tenderRequest->suburb)
                        <div>
                            <label class="text-sm font-medium text-base-content/60">Suburb</label>
                            <p>{{ $tenderRequest->suburb }}</p>
                        </div>
                    @endif
                    @if($tenderRequest->state)
                        <div>
                            <label class="text-sm font-medium text-base-content/60">State</label>
                            <p>{{ $tenderRequest->state }}</p>
                        </div>
                    @endif
                    @if($tenderRequest->postcode)
                        <div>
                            <label class="text-sm font-medium text-base-content/60">Postcode</label>
                            <p>{{ $tenderRequest->postcode }}</p>
                        </div>
                    @endif
                    <div>
                        <label class="text-sm font-medium text-base-content/60">Number of Windows</label>
                        <p class="text-lg font-semibold">{{ $tenderRequest->number_of_windows }}</p>
                    </div>
                </div>
            </div>

            <!-- Appointment Information -->
            <div class="bg-base-100 p-6 rounded-lg shadow">
                <h2 class="text-xl font-semibold mb-4">
                    <i class="iconoir-calendar"></i>
                    Appointment Information
                </h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="text-sm font-medium text-base-content/60">Date & Time</label>
                        <p class="text-lg">{{ $tenderRequest->formatted_appointment }}</p>
                    </div>
                    <div>
                        <label class="text-sm font-medium text-base-content/60">Duration</label>
                        <p>3 hours</p>
                    </div>
                    <div>
                        <label class="text-sm font-medium text-base-content/60">Preferred Contact</label>
                        <p class="capitalize">{{ $tenderRequest->preferred_contact_method }}</p>
                    </div>
                </div>
            </div>

            <!-- Customer Notes -->
            @if($tenderRequest->customer_notes)
                <div class="bg-base-100 p-6 rounded-lg shadow">
                    <h2 class="text-xl font-semibold mb-4">
                        <i class="iconoir-notes"></i>
                        Your Notes
                    </h2>
                    <p class="whitespace-pre-wrap">{{ $tenderRequest->customer_notes }}</p>
                </div>
            @endif

            <!-- Admin Notes -->
            @if($tenderRequest->admin_notes)
                <div class="bg-base-100 p-6 rounded-lg shadow">
                    <h2 class="text-xl font-semibold mb-4">
                        <i class="iconoir-user-star"></i>
                        Notes from Our Team
                    </h2>
                    <p class="whitespace-pre-wrap">{{ $tenderRequest->admin_notes }}</p>
                </div>
            @endif
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Status Timeline -->
            <div class="bg-base-100 p-6 rounded-lg shadow">
                <h3 class="text-lg font-semibold mb-4">Status Timeline</h3>
                <div class="space-y-4">
                    <div class="flex items-center gap-3">
                        <div class="w-3 h-3 bg-primary rounded-full"></div>
                        <div>
                            <p class="font-medium">Request Submitted</p>
                            <p class="text-sm text-base-content/60">{{ $tenderRequest->created_at->format('j M Y, g:i A') }}</p>
                        </div>
                    </div>
                    
                    @if($tenderRequest->status !== 'pending')
                        <div class="flex items-center gap-3">
                            <div class="w-3 h-3 bg-primary rounded-full"></div>
                            <div>
                                <p class="font-medium">Status: {{ ucfirst($tenderRequest->status) }}</p>
                                <p class="text-sm text-base-content/60">{{ $tenderRequest->updated_at->format('j M Y, g:i A') }}</p>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Contact Information -->
            <div class="bg-base-100 p-6 rounded-lg shadow">
                <h3 class="text-lg font-semibold mb-4">Contact Information</h3>
                <div class="space-y-3">
                    <div>
                        <label class="text-sm font-medium text-base-content/60">Your Name</label>
                        <p>{{ $tenderRequest->customer->first_name }} {{ $tenderRequest->customer->last_name }}</p>
                    </div>
                    <div>
                        <label class="text-sm font-medium text-base-content/60">Email</label>
                        <p>{{ $tenderRequest->customer->email }}</p>
                    </div>
                    <div>
                        <label class="text-sm font-medium text-base-content/60">Phone</label>
                        <p>{{ $tenderRequest->customer->phone }}</p>
                    </div>
                </div>
            </div>

            <!-- Next Steps -->
            <div class="bg-base-100 p-6 rounded-lg shadow">
                <h3 class="text-lg font-semibold mb-4">What's Next?</h3>
                @if($tenderRequest->status === 'pending')
                    <div class="space-y-2">
                        <p class="text-sm">• We'll review your request within 24 hours</p>
                        <p class="text-sm">• You'll receive confirmation via {{ $tenderRequest->preferred_contact_method }}</p>
                        <p class="text-sm">• Our team will arrive at the scheduled time</p>
                    </div>
                @elseif($tenderRequest->status === 'confirmed')
                    <div class="space-y-2">
                        <p class="text-sm text-success">✓ Your appointment is confirmed!</p>
                        <p class="text-sm">• Our team will arrive at {{ $tenderRequest->appointment_datetime->format('g:i A') }}</p>
                        <p class="text-sm">• Please ensure property access is available</p>
                    </div>
                @elseif($tenderRequest->status === 'completed')
                    <div class="space-y-2">
                        <p class="text-sm text-success">✓ Inspection completed!</p>
                        <p class="text-sm">• We'll send you a detailed quote soon</p>
                        <p class="text-sm">• Check your email for updates</p>
                    </div>
                @elseif($tenderRequest->status === 'cancelled')
                    <div class="space-y-2">
                        <p class="text-sm text-error">This request has been cancelled</p>
                        <p class="text-sm">Contact us if you'd like to reschedule</p>
                    </div>
                @endif
            </div>

            <!-- Help -->
            <div class="bg-base-100 p-6 rounded-lg shadow">
                <h3 class="text-lg font-semibold mb-4">Need Help?</h3>
                <div class="space-y-2">
                    <div class="text-sm">
                        <i class="iconoir-phone"></i>
                        <span class="ml-2">(02) 1234 5678</span>
                    </div>
                    <div class="text-sm">
                        <i class="iconoir-mail"></i>
                        <span class="ml-2">support@windowtenders.com</span>
                    </div>
                    <p class="text-xs text-base-content/60 mt-2">Reference: Request #{{ $tenderRequest->id }}</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection