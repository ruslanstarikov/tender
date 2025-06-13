@extends('base')

@section('title', 'Create Supplier')

@section('content')
<div class="max-w-3xl mx-auto bg-base-100 p-6 rounded-lg shadow">
    <h2 class="text-2xl font-semibold mb-4">Create New Supplier</h2>

    @if ($errors->any())
        <div class="alert alert-error mb-4">
            <ul class="list-disc pl-5">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('admin.suppliers.store') }}" enctype="multipart/form-data">
        @csrf

        {{-- Company Information --}}
        <div class="grid grid-cols-1 gap-4">
            <div>
                <label class="label"><span class="label-text">Company Name <span class="text-error">*</span></span></label>
                <input type="text" name="company_name" value="{{ old('company_name') }}" class="input input-bordered w-full" required>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="label"><span class="label-text">ABN</span></label>
                    <input type="text" name="abn" value="{{ old('abn') }}" placeholder="12345678901" maxlength="11" class="input input-bordered w-full">
                </div>
                <div>
                    <label class="label"><span class="label-text">Website</span></label>
                    <input type="url" name="website" value="{{ old('website') }}" placeholder="https://example.com" class="input input-bordered w-full">
                </div>
            </div>

            <div>
                <label class="label"><span class="label-text">Established Date</span></label>
                <input type="date" name="established_date" value="{{ old('established_date') }}" class="input input-bordered w-full">
            </div>

            <div>
                <label class="label"><span class="label-text">Company Logo</span></label>
                <input type="file" name="logo" accept="image/*" class="file-input file-input-bordered w-full">
            </div>

            <div>
                <label class="label"><span class="label-text">Company Description</span></label>
                <textarea name="company_description" rows="3" placeholder="Brief description of the company and services..." class="textarea textarea-bordered w-full">{{ old('company_description') }}</textarea>
            </div>
        </div>

        <hr class="my-6">

        {{-- Contact Person --}}
        <h3 class="text-xl font-semibold mb-2">Contact Person</h3>
        <div class="grid grid-cols-1 gap-4">
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="label"><span class="label-text">First Name <span class="text-error">*</span></span></label>
                    <input type="text" name="first_name" value="{{ old('first_name') }}" class="input input-bordered w-full" required>
                </div>
                <div>
                    <label class="label"><span class="label-text">Last Name <span class="text-error">*</span></span></label>
                    <input type="text" name="last_name" value="{{ old('last_name') }}" class="input input-bordered w-full" required>
                </div>
            </div>

            <div>
                <label class="label"><span class="label-text">Position/Title</span></label>
                <input type="text" name="position" value="{{ old('position') }}" placeholder="e.g., Sales Manager, Owner, Director" class="input input-bordered w-full">
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="label"><span class="label-text">Email Address <span class="text-error">*</span></span></label>
                    <input type="email" name="email" value="{{ old('email') }}" placeholder="contact@company.com" class="input input-bordered w-full" required>
                </div>
                <div>
                    <label class="label"><span class="label-text">Phone Number <span class="text-error">*</span></span></label>
                    <input type="tel" name="phone" value="{{ old('phone') }}" placeholder="(02) 1234 5678" class="input input-bordered w-full" required>
                </div>
            </div>
        </div>

        <hr class="my-6">

        {{-- Business Address --}}
        <h3 class="text-xl font-semibold mb-2">Business Address</h3>
        <div class="grid grid-cols-1 gap-4">
            <div class="grid grid-cols-4 gap-4">
                <div>
                    <label class="label"><span class="label-text">Unit</span></label>
                    <input type="text" name="unit_number" value="{{ old('unit_number') }}" placeholder="12A" class="input input-bordered w-full">
                </div>
                <div>
                    <label class="label"><span class="label-text">Number</span></label>
                    <input type="text" name="street_number" value="{{ old('street_number') }}" placeholder="123" class="input input-bordered w-full">
                </div>
                <div class="col-span-2">
                    <label class="label"><span class="label-text">Street Name</span></label>
                    <input type="text" name="street_name" value="{{ old('street_name') }}" placeholder="Collins" class="input input-bordered w-full">
                </div>
            </div>

            <div class="grid grid-cols-4 gap-4">
                <div>
                    <label class="label"><span class="label-text">Type</span></label>
                    <select name="street_type" class="select select-bordered w-full">
                        <option value="">Select</option>
                        @foreach($streetTypes as $type)
                            <option value="{{ $type }}" {{ old('street_type') === $type ? 'selected' : '' }}>{{ $type }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="label"><span class="label-text">Suburb</span></label>
                    <input type="text" name="suburb" value="{{ old('suburb') }}" placeholder="Melbourne" class="input input-bordered w-full">
                </div>
                <div>
                    <label class="label"><span class="label-text">State</span></label>
                    <select name="state" class="select select-bordered w-full">
                        <option value="">Select</option>
                        @foreach($states as $code => $name)
                            <option value="{{ $code }}" {{ old('state') === $code ? 'selected' : '' }}>{{ $code }} - {{ $name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="label"><span class="label-text">Postcode</span></label>
                    <input type="text" name="postcode" value="{{ old('postcode') }}" placeholder="3000" pattern="[0-9]{4}" maxlength="4" class="input input-bordered w-full">
                </div>
            </div>
        </div>

        <hr class="my-6">

        {{-- Business Details --}}
        <h3 class="text-xl font-semibold mb-2">Business Details</h3>
        <div class="grid grid-cols-1 gap-4">
            <div>
                <label class="label"><span class="label-text">Specialty Areas</span></label>
                <div class="grid grid-cols-2 gap-4">
                    @foreach($specialtyAreas as $area)
                        <label class="label cursor-pointer justify-start gap-2">
                            <input type="checkbox" name="specialty_areas[]" value="{{ $area }}" class="checkbox" {{ in_array($area, old('specialty_areas', [])) ? 'checked' : '' }}>
                            <span class="label-text">{{ $area }}</span>
                        </label>
                    @endforeach
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="label"><span class="label-text">Minimum Order Value ($)</span></label>
                    <input type="number" name="minimum_order_value" value="{{ old('minimum_order_value') }}" placeholder="1000.00" step="0.01" min="0" class="input input-bordered w-full">
                </div>
                <div>
                    <label class="label"><span class="label-text">Lead Time (Days)</span></label>
                    <input type="number" name="lead_time_days" value="{{ old('lead_time_days') }}" placeholder="14" min="0" max="365" class="input input-bordered w-full">
                </div>
            </div>

            <div>
                <label class="label"><span class="label-text">Certifications</span></label>
                <textarea name="certifications" rows="3" placeholder="List relevant certifications, licenses, or accreditations..." class="textarea textarea-bordered w-full">{{ old('certifications') }}</textarea>
            </div>
        </div>

        <hr class="my-6">

        {{-- Login Credentials & Status --}}
        <h3 class="text-xl font-semibold mb-2">Login Credentials & Status</h3>
        <div class="grid grid-cols-1 gap-4">
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="label"><span class="label-text">Password <span class="text-error">*</span></span></label>
                    <input type="password" name="password" placeholder="Create secure password" class="input input-bordered w-full" required>
                </div>
                <div>
                    <label class="label"><span class="label-text">Confirm Password <span class="text-error">*</span></span></label>
                    <input type="password" name="password_confirmation" placeholder="Confirm password" class="input input-bordered w-full" required>
                </div>
            </div>

            <div>
                <label class="label"><span class="label-text">Onboarding Status</span></label>
                <select name="onboarding_status" class="select select-bordered w-full">
                    @foreach($onboardingStatuses as $value => $label)
                        <option value="{{ $value }}" {{ old('onboarding_status', 'pending') === $value ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <label class="label cursor-pointer justify-start gap-2">
                    <input type="checkbox" name="is_active" value="1" class="checkbox" {{ old('is_active', '1') ? 'checked' : '' }}>
                    <span class="label-text">Supplier is Active</span>
                </label>
                <label class="label cursor-pointer justify-start gap-2">
                    <input type="checkbox" name="is_verified" value="1" class="checkbox" {{ old('is_verified') ? 'checked' : '' }}>
                    <span class="label-text">Supplier is Verified</span>
                </label>
            </div>

            <div>
                <label class="label"><span class="label-text">Admin Notes</span></label>
                <textarea name="admin_notes" rows="3" placeholder="Internal notes about this supplier..." class="textarea textarea-bordered w-full">{{ old('admin_notes') }}</textarea>
            </div>
        </div>

        <div class="mt-6">
            <button type="submit" class="btn btn-primary">Create Supplier</button>
        </div>
    </form>
</div>
@endsection