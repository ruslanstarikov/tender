@extends('base')

@section('title', 'Create Supplier')

@section('content')
<div class="container mx-auto max-w-4xl">
    <div class="breadcrumbs text-sm mb-4">
        <ul>
            <li><a href="{{ route('admin.suppliers.index') }}" class="link link-primary">Suppliers</a></li>
            <li>Create New Supplier</li>
        </ul>
    </div>
    
    <h1 class="text-3xl font-bold mb-6">Add New Window Frame Supplier</h1>

    <form method="POST" action="{{ route('admin.suppliers.store') }}" enctype="multipart/form-data" class="space-y-6">
        @csrf

        <!-- Company Information -->
        <div class="card bg-base-100 shadow">
            <div class="card-body">
                <h2 class="card-title mb-4">
                    <i class="iconoir-building"></i>
                    Company Information
                </h2>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="form-control">
                        <label class="label">
                            <span class="label-text">Company Name <span class="text-error">*</span></span>
                        </label>
                        <input type="text" name="company_name" value="{{ old('company_name') }}" 
                               class="input input-bordered @error('company_name') input-error @enderror" 
                               required>
                        @error('company_name')
                            <label class="label">
                                <span class="label-text-alt text-error">{{ $message }}</span>
                            </label>
                        @enderror
                    </div>
                    
                    <div class="form-control">
                        <label class="label">
                            <span class="label-text">ABN (Australian Business Number)</span>
                        </label>
                        <input type="text" name="abn" value="{{ old('abn') }}" 
                               placeholder="12345678901" 
                               maxlength="11" 
                               class="input input-bordered @error('abn') input-error @enderror">
                        @error('abn')
                            <label class="label">
                                <span class="label-text-alt text-error">{{ $message }}</span>
                            </label>
                        @enderror
                    </div>
                    
                    <div class="form-control">
                        <label class="label">
                            <span class="label-text">Website</span>
                        </label>
                        <input type="url" name="website" value="{{ old('website') }}" 
                               placeholder="https://example.com" 
                               class="input input-bordered @error('website') input-error @enderror">
                        @error('website')
                            <label class="label">
                                <span class="label-text-alt text-error">{{ $message }}</span>
                            </label>
                        @enderror
                    </div>
                    
                    <div class="form-control">
                        <label class="label">
                            <span class="label-text">Established Date</span>
                        </label>
                        <input type="date" name="established_date" value="{{ old('established_date') }}" 
                               class="input input-bordered @error('established_date') input-error @enderror">
                        @error('established_date')
                            <label class="label">
                                <span class="label-text-alt text-error">{{ $message }}</span>
                            </label>
                        @enderror
                    </div>
                </div>
                
                <div class="form-control mt-4">
                    <label class="label">
                        <span class="label-text">Company Logo</span>
                    </label>
                    <input type="file" name="logo" accept="image/*" 
                           class="file-input file-input-bordered @error('logo') file-input-error @enderror">
                    @error('logo')
                        <label class="label">
                            <span class="label-text-alt text-error">{{ $message }}</span>
                        </label>
                    @enderror
                </div>
                
                <div class="form-control mt-4">
                    <label class="label">
                        <span class="label-text">Company Description</span>
                    </label>
                    <textarea name="company_description" rows="3" 
                              placeholder="Brief description of the company and services..." 
                              class="textarea textarea-bordered @error('company_description') textarea-error @enderror">{{ old('company_description') }}</textarea>
                    @error('company_description')
                        <label class="label">
                            <span class="label-text-alt text-error">{{ $message }}</span>
                        </label>
                    @enderror
                </div>
            </div>
        </div>

        <!-- Contact Person -->
        <div class="card bg-base-100 shadow">
            <div class="card-body">
                <h2 class="card-title mb-4">
                    <i class="iconoir-user"></i>
                    Contact Person
                </h2>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="form-control">
                        <label class="label">
                            <span class="label-text">First Name <span class="text-error">*</span></span>
                        </label>
                        <input type="text" name="first_name" value="{{ old('first_name') }}" 
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
                        <input type="text" name="last_name" value="{{ old('last_name') }}" 
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
                            <span class="label-text">Position/Title</span>
                        </label>
                        <input type="text" name="position" value="{{ old('position') }}" 
                               placeholder="e.g., Sales Manager, Owner" 
                               class="input input-bordered @error('position') input-error @enderror">
                        @error('position')
                            <label class="label">
                                <span class="label-text-alt text-error">{{ $message }}</span>
                            </label>
                        @enderror
                    </div>
                    
                    <div class="form-control">
                        <label class="label">
                            <span class="label-text">Email <span class="text-error">*</span></span>
                        </label>
                        <input type="email" name="email" value="{{ old('email') }}" 
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
                        <input type="tel" name="phone" value="{{ old('phone') }}" 
                               class="input input-bordered @error('phone') input-error @enderror" 
                               required>
                        @error('phone')
                            <label class="label">
                                <span class="label-text-alt text-error">{{ $message }}</span>
                            </label>
                        @enderror
                    </div>
                </div>
            </div>
        </div>

        <!-- Business Address -->
        <div class="card bg-base-100 shadow">
            <div class="card-body">
                <h2 class="card-title mb-4">
                    <i class="iconoir-map-pin"></i>
                    Business Address
                </h2>
                
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-4">
                    <div class="form-control">
                        <label class="label">
                            <span class="label-text">Unit Number</span>
                        </label>
                        <input type="text" name="unit_number" value="{{ old('unit_number') }}" 
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
                        <input type="text" name="street_number" value="{{ old('street_number') }}" 
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
                        <input type="text" name="street_name" value="{{ old('street_name') }}" 
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
                                <option value="{{ $type }}" {{ old('street_type') === $type ? 'selected' : '' }}>
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
                        <input type="text" name="suburb" value="{{ old('suburb') }}" 
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
                                <option value="{{ $code }}" {{ old('state') === $code ? 'selected' : '' }}>
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
                        <input type="text" name="postcode" value="{{ old('postcode') }}" 
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

        <!-- Business Details -->
        <div class="card bg-base-100 shadow">
            <div class="card-body">
                <h2 class="card-title mb-4">
                    <i class="iconoir-tools"></i>
                    Business Details
                </h2>
                
                <div class="form-control mb-4">
                    <label class="label">
                        <span class="label-text">Specialty Areas</span>
                    </label>
                    <div class="grid grid-cols-2 md:grid-cols-3 gap-2">
                        @foreach($specialtyAreas as $area)
                            <label class="label cursor-pointer justify-start gap-2">
                                <input type="checkbox" name="specialty_areas[]" value="{{ $area }}" 
                                       class="checkbox checkbox-sm" 
                                       {{ in_array($area, old('specialty_areas', [])) ? 'checked' : '' }}>
                                <span class="label-text text-sm">{{ $area }}</span>
                            </label>
                        @endforeach
                    </div>
                    @error('specialty_areas')
                        <label class="label">
                            <span class="label-text-alt text-error">{{ $message }}</span>
                        </label>
                    @enderror
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="form-control">
                        <label class="label">
                            <span class="label-text">Minimum Order Value ($)</span>
                        </label>
                        <input type="number" name="minimum_order_value" value="{{ old('minimum_order_value') }}" 
                               step="0.01" 
                               min="0" 
                               placeholder="e.g., 1000.00" 
                               class="input input-bordered @error('minimum_order_value') input-error @enderror">
                        @error('minimum_order_value')
                            <label class="label">
                                <span class="label-text-alt text-error">{{ $message }}</span>
                            </label>
                        @enderror
                    </div>
                    
                    <div class="form-control">
                        <label class="label">
                            <span class="label-text">Lead Time (Days)</span>
                        </label>
                        <input type="number" name="lead_time_days" value="{{ old('lead_time_days') }}" 
                               min="0" 
                               max="365" 
                               placeholder="e.g., 14" 
                               class="input input-bordered @error('lead_time_days') input-error @enderror">
                        @error('lead_time_days')
                            <label class="label">
                                <span class="label-text-alt text-error">{{ $message }}</span>
                            </label>
                        @enderror
                    </div>
                </div>
                
                <div class="form-control mt-4">
                    <label class="label">
                        <span class="label-text">Certifications</span>
                    </label>
                    <textarea name="certifications" rows="3" 
                              placeholder="List relevant certifications, licenses, or accreditations..." 
                              class="textarea textarea-bordered @error('certifications') textarea-error @enderror">{{ old('certifications') }}</textarea>
                    @error('certifications')
                        <label class="label">
                            <span class="label-text-alt text-error">{{ $message }}</span>
                        </label>
                    @enderror
                </div>
            </div>
        </div>

        <!-- Authentication -->
        <div class="card bg-base-100 shadow">
            <div class="card-body">
                <h2 class="card-title mb-4">
                    <i class="iconoir-lock"></i>
                    Login Credentials
                </h2>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="form-control">
                        <label class="label">
                            <span class="label-text">Password <span class="text-error">*</span></span>
                        </label>
                        <input type="password" name="password" 
                               class="input input-bordered @error('password') input-error @enderror" 
                               required>
                        @error('password')
                            <label class="label">
                                <span class="label-text-alt text-error">{{ $message }}</span>
                            </label>
                        @enderror
                    </div>
                    
                    <div class="form-control">
                        <label class="label">
                            <span class="label-text">Confirm Password <span class="text-error">*</span></span>
                        </label>
                        <input type="password" name="password_confirmation" 
                               class="input input-bordered" 
                               required>
                    </div>
                </div>
            </div>
        </div>

        <!-- Status Settings -->
        <div class="card bg-base-100 shadow">
            <div class="card-body">
                <h2 class="card-title mb-4">
                    <i class="iconoir-settings"></i>
                    Status Settings
                </h2>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="form-control">
                        <label class="label">
                            <span class="label-text">Onboarding Status</span>
                        </label>
                        <select name="onboarding_status" class="select select-bordered">
                            @foreach($onboardingStatuses as $value => $label)
                                <option value="{{ $value }}" {{ old('onboarding_status', 'pending') === $value ? 'selected' : '' }}>
                                    {{ $label }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                
                <div class="flex flex-wrap gap-4 mt-4">
                    <label class="label cursor-pointer justify-start gap-3">
                        <input type="checkbox" name="is_active" value="1" 
                               class="checkbox" 
                               {{ old('is_active', '1') ? 'checked' : '' }}>
                        <span class="label-text">Supplier is active</span>
                    </label>
                    
                    <label class="label cursor-pointer justify-start gap-3">
                        <input type="checkbox" name="is_verified" value="1" 
                               class="checkbox" 
                               {{ old('is_verified') ? 'checked' : '' }}>
                        <span class="label-text">Supplier is verified</span>
                    </label>
                </div>
                
                <div class="form-control mt-4">
                    <label class="label">
                        <span class="label-text">Admin Notes</span>
                    </label>
                    <textarea name="admin_notes" rows="3" 
                              placeholder="Internal notes about this supplier..." 
                              class="textarea textarea-bordered @error('admin_notes') textarea-error @enderror">{{ old('admin_notes') }}</textarea>
                    @error('admin_notes')
                        <label class="label">
                            <span class="label-text-alt text-error">{{ $message }}</span>
                        </label>
                    @enderror
                </div>
            </div>
        </div>

        <!-- Actions -->
        <div class="flex justify-end gap-4">
            <a href="{{ route('admin.suppliers.index') }}" class="btn btn-outline">
                Cancel
            </a>
            <button type="submit" class="btn btn-primary">
                <i class="iconoir-check"></i>
                Create Supplier
            </button>
        </div>
    </form>
</div>
@endsection