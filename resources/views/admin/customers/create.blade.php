@extends('base')

@section('title', 'Create Customer')

@section('content')
<div class="max-w-3xl mx-auto bg-base-100 p-6 rounded-lg shadow">
    <h2 class="text-2xl font-semibold mb-4">Create New Customer</h2>

    @if ($errors->any())
        <div class="alert alert-error mb-4">
            <ul class="list-disc pl-5">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('admin.customers.store') }}">
        @csrf

        {{-- Personal Information --}}
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

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="label"><span class="label-text">Email <span class="text-error">*</span></span></label>
                    <input type="email" name="email" value="{{ old('email') }}" class="input input-bordered w-full" required>
                </div>
                <div>
                    <label class="label"><span class="label-text">Phone <span class="text-error">*</span></span></label>
                    <input type="tel" name="phone" value="{{ old('phone') }}" placeholder="(02) 1234 5678" class="input input-bordered w-full" required>
                </div>
            </div>

            <div>
                <label class="label"><span class="label-text">Date of Birth</span></label>
                <input type="date" name="date_of_birth" value="{{ old('date_of_birth') }}" class="input input-bordered w-full">
            </div>
        </div>

        <hr class="my-6">

        {{-- Address Information --}}
        <h3 class="text-xl font-semibold mb-2">Address Information</h3>
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

        {{-- Authentication --}}
        <h3 class="text-xl font-semibold mb-2">Authentication</h3>
        <div class="grid grid-cols-1 gap-4">
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="label"><span class="label-text">Password <span class="text-error">*</span></span></label>
                    <input type="password" name="password" class="input input-bordered w-full" required>
                </div>
                <div>
                    <label class="label"><span class="label-text">Confirm Password <span class="text-error">*</span></span></label>
                    <input type="password" name="password_confirmation" class="input input-bordered w-full" required>
                </div>
            </div>

            <label class="label cursor-pointer justify-start gap-2">
                <input type="checkbox" name="is_active" value="1" class="checkbox" {{ old('is_active', '1') ? 'checked' : '' }}>
                <span class="label-text">Customer is Active</span>
            </label>
        </div>

        <hr class="my-6">

        {{-- Additional Notes --}}
        <h3 class="text-xl font-semibold mb-2">Additional Notes</h3>
        <div class="grid grid-cols-1 gap-4">
            <div>
                <label class="label"><span class="label-text">Notes</span></label>
                <textarea name="notes" rows="3" placeholder="Any additional notes about this customer..." class="textarea textarea-bordered w-full">{{ old('notes') }}</textarea>
            </div>
        </div>

        <div class="mt-6">
            <button type="submit" class="btn btn-primary">Create Customer</button>
        </div>
    </form>
</div>
@endsection