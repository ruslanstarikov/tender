@extends('customer.layout')

@section('title', 'Dashboard')

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Welcome Section -->
    <div class="lg:col-span-2">
        <div class="bg-base-100 p-6 rounded-lg shadow">
            <h1 class="text-3xl font-bold mb-2">Welcome back, {{ $customer->first_name }}!</h1>
            <p class="text-base-content/60 mb-6">Manage your window tender requests and track appointments</p>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <a href="{{ route('customer.tender-requests.create') }}" class="btn btn-primary">
                    <i class="iconoir-plus"></i>
                    New Tender Request
                </a>
                <a href="{{ route('customer.tender-requests.index') }}" class="btn btn-outline">
                    <i class="iconoir-list"></i>
                    View All Requests
                </a>
            </div>
        </div>

        <!-- Recent Tender Requests -->
        <div class="bg-base-100 p-6 rounded-lg shadow mt-6">
            <h2 class="text-2xl font-semibold mb-4">Recent Requests</h2>
            
            @if($tenderRequests->count() > 0)
                <div class="space-y-4">
                    @foreach($tenderRequests as $request)
                        <div class="border border-base-300 rounded-lg p-4">
                            <div class="flex justify-between items-start mb-2">
                                <h3 class="font-semibold">{{ $request->property_address }}</h3>
                                <span class="badge {{ $request->status_badge_color }}">{{ ucfirst($request->status) }}</span>
                            </div>
                            <p class="text-sm text-base-content/60 mb-2">
                                <i class="iconoir-calendar"></i>
                                {{ $request->formatted_appointment }}
                            </p>
                            <p class="text-sm text-base-content/60 mb-3">
                                <i class="iconoir-frame"></i>
                                {{ $request->number_of_windows }} window{{ $request->number_of_windows !== 1 ? 's' : '' }}
                            </p>
                            <a href="{{ route('customer.tender-requests.show', $request) }}" class="btn btn-sm btn-outline">
                                View Details
                            </a>
                        </div>
                    @endforeach
                </div>
                
                @if($customer->tenderRequests()->count() > 5)
                    <div class="text-center mt-4">
                        <a href="{{ route('customer.tender-requests.index') }}" class="btn btn-outline btn-sm">
                            View All {{ $customer->tenderRequests()->count() }} Requests
                        </a>
                    </div>
                @endif
            @else
                <div class="text-center py-8">
                    <i class="iconoir-empty-page text-4xl text-base-content/30 mb-4"></i>
                    <p class="text-base-content/60 mb-4">You haven't submitted any tender requests yet.</p>
                    <a href="{{ route('customer.tender-requests.create') }}" class="btn btn-primary">
                        <i class="iconoir-plus"></i>
                        Create Your First Request
                    </a>
                </div>
            @endif
        </div>
    </div>

    <!-- Sidebar -->
    <div class="space-y-6">
        <!-- Account Info -->
        <div class="bg-base-100 p-6 rounded-lg shadow">
            <h3 class="text-lg font-semibold mb-4">Account Information</h3>
            <div class="space-y-2 text-sm">
                <div class="flex justify-between">
                    <span class="text-base-content/60">Name:</span>
                    <span>{{ $customer->first_name }} {{ $customer->last_name }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-base-content/60">Email:</span>
                    <span>{{ $customer->email }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-base-content/60">Phone:</span>
                    <span>{{ $customer->phone }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-base-content/60">Member since:</span>
                    <span>{{ $customer->created_at->format('M Y') }}</span>
                </div>
            </div>
        </div>

        <!-- Quick Stats -->
        <div class="bg-base-100 p-6 rounded-lg shadow">
            <h3 class="text-lg font-semibold mb-4">Quick Stats</h3>
            <div class="grid grid-cols-2 gap-4">
                <div class="text-center">
                    <div class="text-2xl font-bold text-primary">{{ $customer->tenderRequests()->count() }}</div>
                    <div class="text-sm text-base-content/60">Total Requests</div>
                </div>
                <div class="text-center">
                    <div class="text-2xl font-bold text-warning">{{ $customer->tenderRequests()->pending()->count() }}</div>
                    <div class="text-sm text-base-content/60">Pending</div>
                </div>
            </div>
        </div>

        <!-- Help & Support -->
        <div class="bg-base-100 p-6 rounded-lg shadow">
            <h3 class="text-lg font-semibold mb-4">Need Help?</h3>
            <p class="text-sm text-base-content/60 mb-4">Contact us if you have questions about your tender requests.</p>
            <div class="space-y-2">
                <div class="text-sm">
                    <i class="iconoir-phone"></i>
                    <span class="ml-2">(02) 1234 5678</span>
                </div>
                <div class="text-sm">
                    <i class="iconoir-mail"></i>
                    <span class="ml-2">support@windowtenders.com</span>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection