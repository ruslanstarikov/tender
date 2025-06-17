@extends('base')

@section('title', $customer->full_name . ' - Tender Requests')

@section('content')
<div class="container mx-auto">
    <!-- Header -->
    <div class="flex justify-between items-start mb-6">
        <div>
            <div class="breadcrumbs text-sm">
                <ul>
                    <li><a href="{{ route('admin.customers.index') }}" class="link link-primary">Customers</a></li>
                    <li><a href="{{ route('admin.customers.show', $customer) }}" class="link link-primary">{{ $customer->full_name }}</a></li>
                    <li>Tender Requests</li>
                </ul>
            </div>
            <h1 class="text-3xl font-bold mt-2">{{ $customer->full_name }}'s Tender Requests</h1>
        </div>
        <div>
            <a href="{{ route('admin.tender-requests.create', ['customer_id' => $customer->id]) }}" class="btn btn-primary">
                <i class="iconoir-plus"></i>
                New Tender Request
            </a>
        </div>
    </div>

    <!-- Tender Requests List -->
    @if($tenderRequests->count() > 0)
        <div class="overflow-x-auto">
            <table class="table">
                <thead>
                    <tr>
                        <th>Address</th>
                        <th class="text-center">Windows</th>
                        <th>Appointment</th>
                        <th>Status</th>
                        <th class="text-right">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($tenderRequests as $request)
                        <tr>
                            <td>{{ $request->full_address }}</td>
                            <td class="text-center">{{ $request->number_of_windows }}</td>
                            <td>{{ $request->appointment_datetime->format('j M Y g:i A') }}</td>
                            <td>
                                <div class="badge {{ $request->status_badge_color }}">
                                    {{ ucfirst($request->status) }}
                                </div>
                            </td>
                            <td class="text-right">
                                <div class="flex justify-end gap-2">
                                    <a href="{{ route('admin.tender-requests.show', $request) }}" 
                                       class="btn btn-sm btn-ghost">
                                        <i class="iconoir-eye"></i>
                                    </a>
                                    <a href="{{ route('admin.tender-requests.edit', $request) }}" 
                                       class="btn btn-sm btn-ghost">
                                        <i class="iconoir-edit"></i>
                                    </a>
                                    @if($request->status !== 'cancelled')
                                        <form action="{{ route('admin.tender-requests.convert-to-tender', $request) }}" 
                                              method="POST" class="inline">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-primary" 
                                                    onclick="return confirm('Convert this request to a tender?')">
                                                <i class="iconoir-add-page"></i>
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        
        <!-- Pagination -->
        <div class="mt-4">
            {{ $tenderRequests->links() }}
        </div>
    @else
        <div class="text-center py-8">
            <i class="iconoir-page-edit text-4xl text-base-content/30 mb-2 block"></i>
            <p class="text-base-content/60 mb-4">No tender requests found for this customer.</p>
            <a href="{{ route('admin.tender-requests.create', ['customer_id' => $customer->id]) }}" class="btn btn-primary btn-sm">
                <i class="iconoir-plus"></i>
                Create First Tender Request
            </a>
        </div>
    @endif
</div>
@endsection