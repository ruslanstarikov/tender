@extends('base')

@section('title', 'Edit ' . $supplier->company_name)

@section('content')
<div class="container mx-auto max-w-5xl">
    <!-- Header -->
    <div class="mb-8">
        <div class="breadcrumbs text-sm mb-4">
            <ul>
                <li><a href="{{ route('admin.suppliers.index') }}" class="link link-primary">Suppliers</a></li>
                <li><a href="{{ route('admin.suppliers.show', $supplier) }}" class="link link-primary">{{ $supplier->company_name }}</a></li>
                <li>Edit</li>
            </ul>
        </div>
        
        <div class="flex items-center gap-4">
            <div class="p-3 bg-warning/10 rounded-lg">
                <i class="iconoir-edit text-2xl text-warning"></i>
            </div>
            <div>
                <h1 class="text-3xl font-bold text-base-content">Edit Supplier</h1>
                <p class="text-base-content/60 mt-1">Update {{ $supplier->company_name }} details</p>
            </div>
        </div>
    </div>

    <form method="POST" action="{{ route('admin.suppliers.update', $supplier) }}" enctype="multipart/form-data" class="space-y-8">
        @csrf
        @method('PUT')

        <!-- Company Information -->
        <div class="card bg-base-100 shadow-lg">
            <div class="card-body p-8">
                <div class="flex items-center gap-3 mb-6">
                    <div class="p-2 bg-primary/10 rounded-lg">
                        <i class="iconoir-building text-xl text-primary"></i>
                    </div>
                    <h2 class="text-2xl font-semibold">Company Information</h2>
                </div>
                
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                    <!-- Company Name -->
                    <div class="form-control w-full">
                        <label class="label pb-2">
                            <span class="label-text font-medium text-base">Company Name <span class="text-error">*</span></span>
                        </label>
                        <input type="text" 
                               name="company_name" 
                               value="{{ old('company_name', $supplier->company_name) }}" 
                               placeholder="Enter company name"
                               class="input input-bordered input-lg w-full @error('company_name') input-error @enderror" 
                               required>
                        @error('company_name')
                            <label class="label pt-2">
                                <span class="label-text-alt text-error font-medium">{{ $message }}</span>
                            </label>
                        @enderror
                    </div>
                    
                    <!-- ABN -->
                    <div class="form-control w-full">
                        <label class="label pb-2">
                            <span class="label-text font-medium text-base">Australian Business Number (ABN)</span>
                        </label>
                        <input type="text" 
                               name="abn" 
                               value="{{ old('abn', $supplier->abn) }}" 
                               placeholder="12345678901" 
                               maxlength="11" 
                               class="input input-bordered input-lg w-full @error('abn') input-error @enderror">
                        @error('abn')
                            <label class="label pt-2">
                                <span class="label-text-alt text-error font-medium">{{ $message }}</span>
                            </label>
                        @enderror
                    </div>
                    
                    <!-- Website -->
                    <div class="form-control w-full">
                        <label class="label pb-2">
                            <span class="label-text font-medium text-base">Website</span>
                        </label>
                        <input type="url" 
                               name="website" 
                               value="{{ old('website', $supplier->website) }}" 
                               placeholder="https://example.com" 
                               class="input input-bordered input-lg w-full @error('website') input-error @enderror">
                        @error('website')
                            <label class="label pt-2">
                                <span class="label-text-alt text-error font-medium">{{ $message }}</span>
                            </label>
                        @enderror
                    </div>
                    
                    <!-- Established Date -->
                    <div class="form-control w-full">
                        <label class="label pb-2">
                            <span class="label-text font-medium text-base">Established Date</span>
                        </label>
                        <input type="date" 
                               name="established_date" 
                               value="{{ old('established_date', $supplier->established_date ? $supplier->established_date->format('Y-m-d') : '') }}" 
                               class="input input-bordered input-lg w-full @error('established_date') input-error @enderror">
                        @error('established_date')
                            <label class="label pt-2">
                                <span class="label-text-alt text-error font-medium">{{ $message }}</span>
                            </label>
                        @enderror
                    </div>
                </div>
                
                <!-- Company Logo -->
                <div class="form-control w-full mt-8">
                    <label class="label pb-2">
                        <span class="label-text font-medium text-base">Company Logo</span>
                    </label>
                    @if($supplier->logo_path)
                        <div class="mb-4">
                            <div class="text-sm text-base-content/60 mb-2">Current logo:</div>
                            <img src="{{ asset('storage/' . $supplier->logo_path) }}" alt="{{ $supplier->company_name }} logo" class="h-16 object-contain">
                        </div>
                    @endif
                    <input type="file" 
                           name="logo" 
                           accept="image/*" 
                           class="file-input file-input-bordered file-input-lg w-full @error('logo') file-input-error @enderror">
                    <label class="label pt-2">
                        <span class="label-text-alt text-base-content/60">Upload PNG, JPG, or GIF. Maximum file size: 2MB{{ $supplier->logo_path ? ' (leave empty to keep current logo)' : '' }}</span>
                    </label>
                    @error('logo')
                        <label class="label pt-1">
                            <span class="label-text-alt text-error font-medium">{{ $message }}</span>
                        </label>
                    @enderror
                </div>
                
                <!-- Company Description -->
                <div class="form-control w-full mt-8">
                    <label class="label pb-2">
                        <span class="label-text font-medium text-base">Company Description</span>
                    </label>
                    <textarea name="company_description" 
                              rows="4" 
                              placeholder="Brief description of the company and services offered..."
                              class="textarea textarea-bordered textarea-lg w-full @error('company_description') textarea-error @enderror">{{ old('company_description', $supplier->company_description) }}</textarea>
                    @error('company_description')
                        <label class="label pt-2">
                            <span class="label-text-alt text-error font-medium">{{ $message }}</span>
                        </label>
                    @enderror
                </div>
            </div>
        </div>

        <!-- Contact Person -->
        <div class="card bg-base-100 shadow-lg">
            <div class="card-body p-8">
                <div class="flex items-center gap-3 mb-6">
                    <div class="p-2 bg-success/10 rounded-lg">
                        <i class="iconoir-user text-xl text-success"></i>
                    </div>
                    <h2 class="text-2xl font-semibold">Contact Person</h2>
                </div>
                
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                    <!-- First Name -->
                    <div class="form-control w-full">
                        <label class="label pb-2">
                            <span class="label-text font-medium text-base">First Name <span class="text-error">*</span></span>
                        </label>
                        <input type="text" 
                               name="first_name" 
                               value="{{ old('first_name', $supplier->first_name) }}" 
                               placeholder="Enter first name"
                               class="input input-bordered input-lg w-full @error('first_name') input-error @enderror" 
                               required>
                        @error('first_name')
                            <label class="label pt-2">
                                <span class="label-text-alt text-error font-medium">{{ $message }}</span>
                            </label>
                        @enderror
                    </div>
                    
                    <!-- Last Name -->
                    <div class="form-control w-full">
                        <label class="label pb-2">
                            <span class="label-text font-medium text-base">Last Name <span class="text-error">*</span></span>
                        </label>
                        <input type="text" 
                               name="last_name" 
                               value="{{ old('last_name', $supplier->last_name) }}" 
                               placeholder="Enter last name"
                               class="input input-bordered input-lg w-full @error('last_name') input-error @enderror" 
                               required>
                        @error('last_name')
                            <label class="label pt-2">
                                <span class="label-text-alt text-error font-medium">{{ $message }}</span>
                            </label>
                        @enderror
                    </div>
                    
                    <!-- Position -->
                    <div class="form-control w-full">
                        <label class="label pb-2">
                            <span class="label-text font-medium text-base">Position/Title</span>
                        </label>
                        <input type="text" 
                               name="position" 
                               value="{{ old('position', $supplier->position) }}" 
                               placeholder="e.g., Sales Manager, Owner, Director"
                               class="input input-bordered input-lg w-full @error('position') input-error @enderror">
                        @error('position')
                            <label class="label pt-2">
                                <span class="label-text-alt text-error font-medium">{{ $message }}</span>
                            </label>
                        @enderror
                    </div>
                    
                    <!-- Email -->
                    <div class="form-control w-full">
                        <label class="label pb-2">
                            <span class="label-text font-medium text-base">Email Address <span class="text-error">*</span></span>
                        </label>
                        <input type="email" 
                               name="email" 
                               value="{{ old('email', $supplier->email) }}" 
                               placeholder="contact@company.com"
                               class="input input-bordered input-lg w-full @error('email') input-error @enderror" 
                               required>
                        @error('email')
                            <label class="label pt-2">
                                <span class="label-text-alt text-error font-medium">{{ $message }}</span>
                            </label>
                        @enderror
                    </div>
                    
                    <!-- Phone -->
                    <div class="form-control w-full lg:col-span-2">
                        <label class="label pb-2">
                            <span class="label-text font-medium text-base">Phone Number <span class="text-error">*</span></span>
                        </label>
                        <input type="tel" 
                               name="phone" 
                               value="{{ old('phone', $supplier->phone) }}" 
                               placeholder="(02) 1234 5678"
                               class="input input-bordered input-lg w-full @error('phone') input-error @enderror" 
                               required>
                        @error('phone')
                            <label class="label pt-2">
                                <span class="label-text-alt text-error font-medium">{{ $message }}</span>
                            </label>
                        @enderror
                    </div>
                </div>
            </div>
        </div>

        <!-- Business Address -->
        <div class="card bg-base-100 shadow-lg">
            <div class="card-body p-8">
                <div class="flex items-center gap-3 mb-6">
                    <div class="p-2 bg-info/10 rounded-lg">
                        <i class="iconoir-map-pin text-xl text-info"></i>
                    </div>
                    <h2 class="text-2xl font-semibold">Business Address</h2>
                </div>
                
                <div class="grid grid-cols-1 lg:grid-cols-4 gap-6 mb-6">
                    <!-- Unit Number -->
                    <div class="form-control w-full">
                        <label class="label pb-2">
                            <span class="label-text font-medium text-base">Unit Number</span>
                        </label>
                        <input type="text" 
                               name="unit_number" 
                               value="{{ old('unit_number', $supplier->unit_number) }}" 
                               placeholder="12A"
                               class="input input-bordered input-lg w-full @error('unit_number') input-error @enderror">
                        @error('unit_number')
                            <label class="label pt-2">
                                <span class="label-text-alt text-error font-medium">{{ $message }}</span>
                            </label>
                        @enderror
                    </div>
                    
                    <!-- Street Number -->
                    <div class="form-control w-full">
                        <label class="label pb-2">
                            <span class="label-text font-medium text-base">Street Number</span>
                        </label>
                        <input type="text" 
                               name="street_number" 
                               value="{{ old('street_number', $supplier->street_number) }}" 
                               placeholder="123"
                               class="input input-bordered input-lg w-full @error('street_number') input-error @enderror">
                        @error('street_number')
                            <label class="label pt-2">
                                <span class="label-text-alt text-error font-medium">{{ $message }}</span>
                            </label>
                        @enderror
                    </div>
                    
                    <!-- Street Name -->
                    <div class="form-control w-full lg:col-span-2">
                        <label class="label pb-2">
                            <span class="label-text font-medium text-base">Street Name</span>
                        </label>
                        <input type="text" 
                               name="street_name" 
                               value="{{ old('street_name', $supplier->street_name) }}" 
                               placeholder="Collins"
                               class="input input-bordered input-lg w-full @error('street_name') input-error @enderror">
                        @error('street_name')
                            <label class="label pt-2">
                                <span class="label-text-alt text-error font-medium">{{ $message }}</span>
                            </label>
                        @enderror
                    </div>
                </div>
                
                <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
                    <!-- Street Type -->
                    <div class="form-control w-full">
                        <label class="label pb-2">
                            <span class="label-text font-medium text-base">Street Type</span>
                        </label>
                        <select name="street_type" class="select select-bordered select-lg w-full @error('street_type') select-error @enderror">
                            <option value="">Select type</option>
                            @foreach($streetTypes as $type)
                                <option value="{{ $type }}" {{ old('street_type', $supplier->street_type) === $type ? 'selected' : '' }}>
                                    {{ $type }}
                                </option>
                            @endforeach
                        </select>
                        @error('street_type')
                            <label class="label pt-2">
                                <span class="label-text-alt text-error font-medium">{{ $message }}</span>
                            </label>
                        @enderror
                    </div>
                    
                    <!-- Suburb -->
                    <div class="form-control w-full">
                        <label class="label pb-2">
                            <span class="label-text font-medium text-base">Suburb</span>
                        </label>
                        <input type="text" 
                               name="suburb" 
                               value="{{ old('suburb', $supplier->suburb) }}" 
                               placeholder="Melbourne"
                               class="input input-bordered input-lg w-full @error('suburb') input-error @enderror">
                        @error('suburb')
                            <label class="label pt-2">
                                <span class="label-text-alt text-error font-medium">{{ $message }}</span>
                            </label>
                        @enderror
                    </div>
                    
                    <!-- State -->
                    <div class="form-control w-full">
                        <label class="label pb-2">
                            <span class="label-text font-medium text-base">State</span>
                        </label>
                        <select name="state" class="select select-bordered select-lg w-full @error('state') select-error @enderror">
                            <option value="">Select state</option>
                            @foreach($states as $code => $name)
                                <option value="{{ $code }}" {{ old('state', $supplier->state) === $code ? 'selected' : '' }}>
                                    {{ $code }} - {{ $name }}
                                </option>
                            @endforeach
                        </select>
                        @error('state')
                            <label class="label pt-2">
                                <span class="label-text-alt text-error font-medium">{{ $message }}</span>
                            </label>
                        @enderror
                    </div>
                    
                    <!-- Postcode -->
                    <div class="form-control w-full">
                        <label class="label pb-2">
                            <span class="label-text font-medium text-base">Postcode</span>
                        </label>
                        <input type="text" 
                               name="postcode" 
                               value="{{ old('postcode', $supplier->postcode) }}" 
                               placeholder="3000" 
                               pattern="[0-9]{4}" 
                               maxlength="4" 
                               class="input input-bordered input-lg w-full @error('postcode') input-error @enderror">
                        @error('postcode')
                            <label class="label pt-2">
                                <span class="label-text-alt text-error font-medium">{{ $message }}</span>
                            </label>
                        @enderror
                    </div>
                </div>
            </div>
        </div>

        <!-- Business Details -->
        <div class="card bg-base-100 shadow-lg">
            <div class="card-body p-8">
                <div class="flex items-center gap-3 mb-6">
                    <div class="p-2 bg-warning/10 rounded-lg">
                        <i class="iconoir-tools text-xl text-warning"></i>
                    </div>
                    <h2 class="text-2xl font-semibold">Business Details</h2>
                </div>
                
                <!-- Specialty Areas -->
                <div class="form-control w-full mb-8">
                    <label class="label pb-3">
                        <span class="label-text font-medium text-base">Specialty Areas</span>
                    </label>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        @foreach($specialtyAreas as $area)
                            <label class="label cursor-pointer justify-start gap-4 p-4 rounded-lg border border-base-300 hover:border-primary hover:bg-primary/5 transition-colors">
                                <input type="checkbox" 
                                       name="specialty_areas[]" 
                                       value="{{ $area }}" 
                                       class="checkbox checkbox-primary" 
                                       {{ in_array($area, old('specialty_areas', $supplier->specialty_areas ?? [])) ? 'checked' : '' }}>
                                <span class="label-text font-medium text-base">{{ $area }}</span>
                            </label>
                        @endforeach
                    </div>
                    @error('specialty_areas')
                        <label class="label pt-2">
                            <span class="label-text-alt text-error font-medium">{{ $message }}</span>
                        </label>
                    @enderror
                </div>
                
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                    <!-- Minimum Order Value -->
                    <div class="form-control w-full">
                        <label class="label pb-2">
                            <span class="label-text font-medium text-base">Minimum Order Value</span>
                        </label>
                        <label class="input input-bordered input-lg flex items-center gap-2">
                            <span class="text-base-content/60">$</span>
                            <input type="number" 
                                   name="minimum_order_value" 
                                   value="{{ old('minimum_order_value', $supplier->minimum_order_value) }}" 
                                   placeholder="1000.00" 
                                   step="0.01" 
                                   min="0" 
                                   class="grow @error('minimum_order_value') input-error @enderror">
                        </label>
                        @error('minimum_order_value')
                            <label class="label pt-2">
                                <span class="label-text-alt text-error font-medium">{{ $message }}</span>
                            </label>
                        @enderror
                    </div>
                    
                    <!-- Lead Time -->
                    <div class="form-control w-full">
                        <label class="label pb-2">
                            <span class="label-text font-medium text-base">Lead Time</span>
                        </label>
                        <label class="input input-bordered input-lg flex items-center gap-2">
                            <input type="number" 
                                   name="lead_time_days" 
                                   value="{{ old('lead_time_days', $supplier->lead_time_days) }}" 
                                   placeholder="14" 
                                   min="0" 
                                   max="365" 
                                   class="grow @error('lead_time_days') input-error @enderror">
                            <span class="text-base-content/60">days</span>
                        </label>
                        @error('lead_time_days')
                            <label class="label pt-2">
                                <span class="label-text-alt text-error font-medium">{{ $message }}</span>
                            </label>
                        @enderror
                    </div>
                </div>
                
                <!-- Certifications -->
                <div class="form-control w-full mt-8">
                    <label class="label pb-2">
                        <span class="label-text font-medium text-base">Certifications</span>
                    </label>
                    <textarea name="certifications" 
                              rows="4" 
                              placeholder="List relevant certifications, licenses, or accreditations..."
                              class="textarea textarea-bordered textarea-lg w-full @error('certifications') textarea-error @enderror">{{ old('certifications', $supplier->certifications) }}</textarea>
                    @error('certifications')
                        <label class="label pt-2">
                            <span class="label-text-alt text-error font-medium">{{ $message }}</span>
                        </label>
                    @enderror
                </div>
            </div>
        </div>

        <!-- Status & Admin Settings -->
        <div class="card bg-base-100 shadow-lg">
            <div class="card-body p-8">
                <div class="flex items-center gap-3 mb-6">
                    <div class="p-2 bg-error/10 rounded-lg">
                        <i class="iconoir-settings text-xl text-error"></i>
                    </div>
                    <h2 class="text-2xl font-semibold">Status & Admin Settings</h2>
                </div>
                
                <!-- Onboarding Status -->
                <div class="form-control w-full mb-8">
                    <label class="label pb-2">
                        <span class="label-text font-medium text-base">Onboarding Status</span>
                    </label>
                    <select name="onboarding_status" class="select select-bordered select-lg w-full">
                        @foreach($onboardingStatuses as $value => $label)
                            <option value="{{ $value }}" {{ old('onboarding_status', $supplier->onboarding_status) === $value ? 'selected' : '' }}>
                                {{ $label }}
                            </option>
                        @endforeach
                    </select>
                </div>
                
                <!-- Status Checkboxes -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                    <label class="label cursor-pointer justify-start gap-4 p-4 rounded-lg border border-base-300">
                        <input type="checkbox" 
                               name="is_active" 
                               value="1" 
                               class="checkbox checkbox-success" 
                               {{ old('is_active', $supplier->is_active) ? 'checked' : '' }}>
                        <div>
                            <span class="label-text font-medium text-base">Supplier is Active</span>
                            <div class="text-sm text-base-content/60 mt-1">Allow supplier to log in and access the system</div>
                        </div>
                    </label>
                    
                    <label class="label cursor-pointer justify-start gap-4 p-4 rounded-lg border border-base-300">
                        <input type="checkbox" 
                               name="is_verified" 
                               value="1" 
                               class="checkbox checkbox-info" 
                               {{ old('is_verified', $supplier->is_verified) ? 'checked' : '' }}>
                        <div>
                            <span class="label-text font-medium text-base">Supplier is Verified</span>
                            <div class="text-sm text-base-content/60 mt-1">Mark as verified after document review</div>
                        </div>
                    </label>
                </div>
                
                <!-- Admin Notes -->
                <div class="form-control w-full">
                    <label class="label pb-2">
                        <span class="label-text font-medium text-base">Admin Notes</span>
                    </label>
                    <textarea name="admin_notes" 
                              rows="3" 
                              placeholder="Internal notes about this supplier..."
                              class="textarea textarea-bordered textarea-lg w-full @error('admin_notes') textarea-error @enderror">{{ old('admin_notes', $supplier->admin_notes) }}</textarea>
                    @error('admin_notes')
                        <label class="label pt-2">
                            <span class="label-text-alt text-error font-medium">{{ $message }}</span>
                        </label>
                    @enderror
                </div>
            </div>
        </div>

        <!-- Actions -->
        <div class="flex justify-end gap-4 pb-8">
            <a href="{{ route('admin.suppliers.show', $supplier) }}" class="btn btn-outline btn-lg px-8">
                <i class="iconoir-cancel"></i>
                Cancel
            </a>
            <button type="submit" class="btn btn-warning btn-lg px-8">
                <i class="iconoir-check"></i>
                Update Supplier
            </button>
        </div>
    </form>
</div>
@endsection