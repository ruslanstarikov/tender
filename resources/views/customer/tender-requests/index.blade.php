@extends('customer.layout')

@section('title', 'My Tender Requests')

@section('content')
<div class="bg-base-100 p-6 rounded-lg shadow">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-3xl font-bold">My Tender Requests</h1>
            <p class="text-base-content/60">Track the status of your window inspection requests</p>
        </div>
        <a href="{{ route('customer.tender-requests.create') }}" class="btn btn-primary">
            <i class="iconoir-plus"></i>
            New Request
        </a>
    </div>

    @if($tenderRequests->count() > 0)
        <div class="overflow-x-auto">
            <table class="table table-zebra w-full">
                <thead>
                    <tr>
                        <th>Property Address</th>
                        <th>Windows</th>
                        <th>Appointment</th>
                        <th>Status</th>
                        <th>Submitted</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($tenderRequests as $request)
                        <tr>
                            <td>
                                <div>
                                    <div class="font-semibold">{{ $request->property_address }}</div>
                                    @if($request->suburb || $request->state)
                                        <div class="text-sm text-base-content/60">
                                            {{ implode(', ', array_filter([$request->suburb, $request->state, $request->postcode])) }}
                                        </div>
                                    @endif
                                </div>
                            </td>
                            <td>
                                <span class="badge badge-outline">{{ $request->number_of_windows }} window{{ $request->number_of_windows !== 1 ? 's' : '' }}</span>
                            </td>
                            <td>
                                <div class="text-sm">
                                    <div>{{ $request->appointment_datetime->format('j M Y') }}</div>
                                    <div class="text-base-content/60">{{ $request->appointment_datetime->format('g:i A') }}</div>
                                </div>
                            </td>
                            <td>
                                <span class="badge {{ $request->status_badge_color }}">{{ ucfirst($request->status) }}</span>
                            </td>
                            <td>
                                <span class="text-sm text-base-content/60">{{ $request->created_at->format('j M Y') }}</span>
                            </td>
                            <td>
                                <a href="{{ route('customer.tender-requests.show', $request) }}" class="btn btn-sm btn-outline">
                                    View
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        @if($tenderRequests->hasPages())
            <div class="mt-6">
                {{ $tenderRequests->links() }}
            </div>
        @endif
    @else
        <div class="text-center py-12">
            <i class="iconoir-empty-page text-6xl text-base-content/30 mb-4"></i>
            <h3 class="text-xl font-semibold mb-2">No tender requests yet</h3>
            <p class="text-base-content/60 mb-6">Start by creating your first window inspection request</p>
            <a href="{{ route('customer.tender-requests.create') }}" class="btn btn-primary">
                <i class="iconoir-plus"></i>
                Create Your First Request
            </a>
        </div>
    @endif
</div>
@endsection