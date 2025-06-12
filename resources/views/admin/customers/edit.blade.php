@extends('base')

@section('title', 'Edit ' . $customer->full_name)

@section('content')
<div class="container mx-auto max-w-4xl">
    <div class="breadcrumbs text-sm mb-4">
        <ul>
            <li><a href="{{ route('admin.customers.index') }}" class="link link-primary">Customers</a></li>
            <li><a href="{{ route('admin.customers.show', $customer) }}" class="link link-primary">{{ $customer->full_name }}</a></li>
            <li>Edit</li>
        </ul>
    </div>
    
    <h1 class="text-3xl font-bold mb-6">Edit Customer: {{ $customer->full_name }}</h1>

    <form method="POST" action="{{ route('admin.customers.update', $customer) }}" class="space-y-6">
        @csrf
        @method('PUT')

        <!-- Personal Information -->
        <div class="card bg-base-100 shadow">
            <div class="card-body">
                <h2 class="card-title mb-4">
                    <i class="iconoir-user"></i>
                    Personal Information
                </h2>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="form-control">
                        <label class="label">
                            <span class="label-text">First Name <span class="text-error">*</span></span>
                        </label>
                        <input type="text" name="first_name" value="{{ old('first_name', $customer->first_name) }}" 
                               class="input input-bordered @error('first_name') input-error @enderror" 
                               required>
                        @error('first_name')
                            <label class="label">
                                <span class="label-text-alt text-error">{{ $message }}</span>
                            </label>
                        @enderror
                    </div>
                    
                    <div class="form-control">
                        <label class="label">
                            <span class="label-text">Last Name <span class="text-error">*</span></span>
                        </label>
                        <input type="text" name="last_name" value="{{ old('last_name', $customer->last_name) }}" 
                               class="input input-bordered @error('last_name') input-error @enderror" 
                               required>
                        @error('last_name')
                            <label class="label">
                                <span class="label-text-alt text-error">{{ $message }}</span>
                            </label>
                        @enderror
                    </div>
                    
                    <div class="form-control">
                        <label class="label">
                            <span class="label-text">Email <span class="text-error">*</span></span>
                        </label>
                        <input type="email" name="email" value="{{ old('email', $customer->email) }}" 
                               class="input input-bordered @error('email') input-error @enderror" 
                               required>
                        @error('email')
                            <label class="label">
                                <span class="label-text-alt text-error">{{ $message }}</span>
                            </label>
                        @enderror
                    </div>
                    
                    <div class="form-control">
                        <label class="label">
                            <span class="label-text">Phone <span class="text-error">*</span></span>
                        </label>
                        <input type="tel" name="phone" value="{{ old('phone', $customer->phone) }}" 
                               class="input input-bordered @error('phone') input-error @enderror" 
                               required>
                        @error('phone')
                            <label class="label">
                                <span class="label-text-alt text-error">{{ $message }}</span>
                            </label>
                        @enderror
                    </div>
                    
                    <div class="form-control">
                        <label class="label">
                            <span class="label-text">Date of Birth</span>
                        </label>
                        <input type="date" name="date_of_birth" value="{{ old('date_of_birth', $customer->date_of_birth?->format('Y-m-d')) }}" 
                               class="input input-bordered @error('date_of_birth') input-error @enderror">
                        @error('date_of_birth')
                            <label class="label">
                                <span class="label-text-alt text-error">{{ $message }}</span>
                            </label>
                        @enderror
                    </div>
                </div>
            </div>
        </div>

        <!-- Address Information -->
        <div class="card bg-base-100 shadow">
            <div class="card-body">
                <h2 class="card-title mb-4">
                    <i class="iconoir-map-pin"></i>
                    Address Information
                </h2>
                
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-4">
                    <div class="form-control">
                        <label class="label">
                            <span class="label-text">Unit Number</span>
                        </label>
                        <input type="text" name="unit_number" value="{{ old('unit_number', $customer->unit_number) }}" 
                               placeholder="e.g., 12A" 
                               class="input input-bordered @error('unit_number') input-error @enderror">
                        @error('unit_number')
                            <label class="label">
                                <span class="label-text-alt text-error">{{ $message }}</span>
                            </label>
                        @enderror
                    </div>
                    
                    <div class="form-control">
                        <label class="label">
                            <span class="label-text">Street Number</span>
                        </label>
                        <input type="text" name="street_number" value="{{ old('street_number', $customer->street_number) }}" 
                               placeholder="e.g., 123" 
                               class="input input-bordered @error('street_number') input-error @enderror">
                        @error('street_number')
                            <label class="label">
                                <span class="label-text-alt text-error">{{ $message }}</span>
                            </label>
                        @enderror
                    </div>
                    
                    <div class="form-control md:col-span-2">
                        <label class="label">
                            <span class="label-text">Street Name</span>
                        </label>
                        <input type="text" name="street_name" value="{{ old('street_name', $customer->street_name) }}" 
                               placeholder="e.g., Collins" 
                               class="input input-bordered @error('street_name') input-error @enderror">
                        @error('street_name')
                            <label class="label">
                                <span class="label-text-alt text-error">{{ $message }}</span>
                            </label>
                        @enderror
                    </div>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <div class="form-control">
                        <label class="label">
                            <span class="label-text">Street Type</span>
                        </label>
                        <select name="street_type" class="select select-bordered @error('street_type') select-error @enderror">
                            <option value="">Select type</option>
                            @foreach($streetTypes as $type)
                                <option value="{{ $type }}" {{ old('street_type', $customer->street_type) === $type ? 'selected' : '' }}>
                                    {{ $type }}
                                </option>
                            @endforeach
                        </select>
                        @error('street_type')
                            <label class="label">
                                <span class="label-text-alt text-error">{{ $message }}</span>
                            </label>
                        @enderror
                    </div>
                    
                    <div class="form-control">
                        <label class="label">
                            <span class="label-text">Suburb</span>
                        </label>
                        <input type="text" name="suburb" value="{{ old('suburb', $customer->suburb) }}" 
                               placeholder="e.g., Melbourne" 
                               class="input input-bordered @error('suburb') input-error @enderror">
                        @error('suburb')
                            <label class="label">
                                <span class="label-text-alt text-error">{{ $message }}</span>
                            </label>
                        @enderror
                    </div>
                    
                    <div class="form-control">
                        <label class="label">
                            <span class="label-text">State</span>
                        </label>
                        <select name="state" class="select select-bordered @error('state') select-error @enderror">
                            <option value="">Select state</option>
                            @foreach($states as $code => $name)
                                <option value="{{ $code }}" {{ old('state', $customer->state) === $code ? 'selected' : '' }}>
                                    {{ $code }} - {{ $name }}
                                </option>
                            @endforeach
                        </select>
                        @error('state')
                            <label class="label">
                                <span class="label-text-alt text-error">{{ $message }}</span>
                            </label>
                        @enderror
                    </div>
                    
                    <div class="form-control">
                        <label class="label">
                            <span class="label-text">Postcode</span>
                        </label>
                        <input type="text" name="postcode" value="{{ old('postcode', $customer->postcode) }}" 
                               placeholder="e.g., 3000" 
                               pattern="[0-9]{4}" 
                               maxlength="4" 
                               class="input input-bordered @error('postcode') input-error @enderror">
                        @error('postcode')
                            <label class="label">
                                <span class="label-text-alt text-error">{{ $message }}</span>
                            </label>
                        @enderror
                    </div>
                </div>
            </div>
        </div>

        <!-- Account Status -->
        <div class="card bg-base-100 shadow">
            <div class="card-body">
                <h2 class="card-title mb-4">
                    <i class="iconoir-settings"></i>
                    Account Settings
                </h2>
                
                <div class="form-control">
                    <label class="label cursor-pointer justify-start gap-3">
                        <input type="checkbox" name="is_active" value="1" 
                               class="checkbox" 
                               {{ old('is_active', $customer->is_active) ? 'checked' : '' }}>
                        <span class="label-text">Customer is active</span>
                    </label>
                    <label class="label">
                        <span class="label-text-alt text-base-content/60">
                            Inactive customers cannot log in to their account
                        </span>
                    </label>
                </div>
            </div>
        </div>

        <!-- Notes -->
        <div class="card bg-base-100 shadow">
            <div class="card-body">
                <h2 class="card-title mb-4">
                    <i class="iconoir-notes"></i>
                    Additional Notes
                </h2>
                
                <div class="form-control">
                    <label class="label">
                        <span class="label-text">Notes</span>
                    </label>
                    <textarea name="notes" rows="4" 
                              placeholder="Any additional notes about this customer..." 
                              class="textarea textarea-bordered @error('notes') textarea-error @enderror">{{ old('notes', $customer->notes) }}</textarea>
                    @error('notes')
                        <label class="label">
                            <span class="label-text-alt text-error">{{ $message }}</span>
                        </label>
                    @enderror
                </div>
            </div>
        </div>

        <!-- Account Information -->
        <div class="card bg-base-200">
            <div class="card-body">
                <h2 class="card-title mb-4">
                    <i class="iconoir-info-circle"></i>
                    Account Information
                </h2>
                
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-sm">
                    <div>
                        <span class="font-semibold">Member Since:</span>
                        <p>{{ $customer->created_at->format('j F Y') }}</p>
                    </div>
                    <div>
                        <span class="font-semibold">Last Login:</span>
                        <p>{{ $customer->last_login_at ? $customer->last_login_at->format('j F Y, g:i A') : 'Never' }}</p>
                    </div>
                    <div>
                        <span class="font-semibold">Total Tenders:</span>
                        <p>{{ $customer->tenders->count() }}</p>
                    </div>
                </div>
                
                <div class="alert alert-info mt-4">
                    <i class="iconoir-info-circle"></i>
                    <span>To reset the customer's password, use the "Reset Password" action from the customer detail page.</span>
                </div>
            </div>
        </div>

        <!-- Actions -->
        <div class="flex justify-end gap-4">
            <a href="{{ route('admin.customers.show', $customer) }}" class="btn btn-outline">
                Cancel
            </a>
            <button type="submit" class="btn btn-primary">
                <i class="iconoir-check"></i>
                Update Customer
            </button>
        </div>
    </form>
</div>
@endsection