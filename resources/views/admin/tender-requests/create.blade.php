@extends('base')

@section('title', 'Create Tender Request')

@section('content')
<div class="container mx-auto">
    <!-- Header -->
    <div class="flex justify-between items-start mb-6">
        <div>
            <div class="breadcrumbs text-sm">
                <ul>
                    <li><a href="{{ route('admin.tender-requests.index') }}" class="link link-primary">Tender Requests</a></li>
                    <li>New Request</li>
                </ul>
            </div>
            <h1 class="text-3xl font-bold mt-2">Create Tender Request</h1>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Form -->
        <div class="lg:col-span-2">
            <form action="{{ route('admin.tender-requests.store') }}" method="POST" class="card bg-base-100 shadow">
                @csrf

                <div class="card-body space-y-6">
                    <!-- Customer Selection -->
                    <div class="space-y-4">
                        <h2 class="card-title">
                            <i class="iconoir-user"></i>
                            Customer Information
                        </h2>
                        <div class="form-control">
                            <label class="label">
                                <span class="label-text">Select Customer</span>
                            </label>
                            <select name="customer_id" class="select select-bordered @error('customer_id') select-error @enderror" required>
                                <option value="">Select a customer...</option>
                                @foreach(\App\Models\Customer::orderBy('first_name')->get() as $customerOption)
                                    <option value="{{ $customerOption->id }}" {{ (old('customer_id', optional($customer)->id) == $customerOption->id) ? 'selected' : '' }}>
                                        {{ $customerOption->full_name }} ({{ $customerOption->email }})
                                    </option>
                                @endforeach
                            </select>
                            @error('customer_id')
                                <label class="label"><span class="label-text-alt text-error">{{ $message }}</span></label>
                            @enderror
                        </div>
                    </div>

                    <!-- Property Information -->
                    <div class="space-y-4">
                        <h2 class="card-title">
                            <i class="iconoir-map-pin"></i>
                            Property Information
                        </h2>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="form-control">
                                <label class="label">
                                    <span class="label-text">Property Address</span>
                                </label>
                                <input type="text" name="property_address" class="input input-bordered @error('property_address') input-error @enderror"
                                       value="{{ old('property_address') }}" required>
                                @error('property_address')
                                    <label class="label"><span class="label-text-alt text-error">{{ $message }}</span></label>
                                @enderror
                            </div>

                            <div class="form-control">
                                <label class="label">
                                    <span class="label-text">Suburb</span>
                                </label>
                                <input type="text" name="suburb" class="input input-bordered @error('suburb') input-error @enderror"
                                       value="{{ old('suburb') }}" required>
                                @error('suburb')
                                    <label class="label"><span class="label-text-alt text-error">{{ $message }}</span></label>
                                @enderror
                            </div>

                            <div class="form-control">
                                <label class="label">
                                    <span class="label-text">State</span>
                                </label>
                                <select name="state" class="select select-bordered @error('state') select-error @enderror" required>
                                    <option value="">Select a state...</option>
                                    @foreach(\App\Models\TenderRequest::getAustralianStates() as $code => $name)
                                        <option value="{{ $code }}" {{ old('state') === $code ? 'selected' : '' }}>
                                            {{ $name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('state')
                                    <label class="label"><span class="label-text-alt text-error">{{ $message }}</span></label>
                                @enderror
                            </div>

                            <div class="form-control">
                                <label class="label">
                                    <span class="label-text">Postcode</span>
                                </label>
                                <input type="text" name="postcode" class="input input-bordered @error('postcode') input-error @enderror"
                                       value="{{ old('postcode') }}" required>
                                @error('postcode')
                                    <label class="label"><span class="label-text-alt text-error">{{ $message }}</span></label>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Request Details -->
                    <div class="space-y-4">
                        <h2 class="card-title">
                            <i class="iconoir-page-edit"></i>
                            Request Details
                        </h2>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="form-control">
                                <label class="label">
                                    <span class="label-text">Number of Windows</span>
                                </label>
                                <input type="number" name="number_of_windows" class="input input-bordered @error('number_of_windows') input-error @enderror"
                                       value="{{ old('number_of_windows', 1) }}" min="1" required>
                                @error('number_of_windows')
                                    <label class="label"><span class="label-text-alt text-error">{{ $message }}</span></label>
                                @enderror
                            </div>

                            <div class="form-control">
                                <label class="label">
                                    <span class="label-text">Appointment Date & Time</span>
                                </label>
                                <input type="datetime-local" name="appointment_datetime" 
                                       class="input input-bordered @error('appointment_datetime') input-error @enderror"
                                       value="{{ old('appointment_datetime') }}" required>
                                @error('appointment_datetime')
                                    <label class="label"><span class="label-text-alt text-error">{{ $message }}</span></label>
                                @enderror
                            </div>

                            <div class="form-control">
                                <label class="label">
                                    <span class="label-text">Preferred Contact Method</span>
                                </label>
                                <select name="preferred_contact_method" class="select select-bordered @error('preferred_contact_method') select-error @enderror" required>
                                    <option value="">Select contact method...</option>
                                    <option value="email" {{ old('preferred_contact_method') === 'email' ? 'selected' : '' }}>Email</option>
                                    <option value="phone" {{ old('preferred_contact_method') === 'phone' ? 'selected' : '' }}>Phone</option>
                                </select>
                                @error('preferred_contact_method')
                                    <label class="label"><span class="label-text-alt text-error">{{ $message }}</span></label>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Notes -->
                    <div class="space-y-4">
                        <h2 class="card-title">
                            <i class="iconoir-notes"></i>
                            Notes
                        </h2>
                        <div class="space-y-4">
                            <div class="form-control">
                                <label class="label">
                                    <span class="label-text">Customer Notes</span>
                                </label>
                                <textarea name="customer_notes" class="textarea textarea-bordered h-24 @error('customer_notes') textarea-error @enderror">{{ old('customer_notes') }}</textarea>
                                @error('customer_notes')
                                    <label class="label"><span class="label-text-alt text-error">{{ $message }}</span></label>
                                @enderror
                            </div>

                            <div class="form-control">
                                <label class="label">
                                    <span class="label-text">Admin Notes</span>
                                </label>
                                <textarea name="admin_notes" class="textarea textarea-bordered h-24 @error('admin_notes') textarea-error @enderror">{{ old('admin_notes') }}</textarea>
                                @error('admin_notes')
                                    <label class="label"><span class="label-text-alt text-error">{{ $message }}</span></label>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card-actions justify-end p-6 pt-0">
                    <a href="{{ route('admin.tender-requests.index') }}" class="btn btn-ghost">Cancel</a>
                    <button type="submit" class="btn btn-primary">Create Request</button>
                </div>
            </form>
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Help Information -->
            <div class="card bg-base-100 shadow">
                <div class="card-body">
                    <h2 class="card-title">Help</h2>
                    <ul class="space-y-2 text-sm">
                        <li>
                            <i class="iconoir-info-circle text-info"></i>
                            Select an existing customer or create a new one first
                        </li>
                        <li>
                            <i class="iconoir-info-circle text-info"></i>
                            All times are in your local timezone
                        </li>
                        <li>
                            <i class="iconoir-info-circle text-info"></i>
                            Customer notes will be visible to the customer
                        </li>
                        <li>
                            <i class="iconoir-info-circle text-info"></i>
                            Admin notes are for internal use only
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection