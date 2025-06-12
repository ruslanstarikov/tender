@extends('base')

@section('title', 'Suppliers')

@section('content')
<div class="container mx-auto">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold">Window Frame Suppliers</h1>
        <a href="{{ route('admin.suppliers.create') }}" class="btn btn-primary">
            <i class="iconoir-plus text-lg"></i>
            Add New Supplier
        </a>
    </div>

    <!-- Filters -->
    <div class="card bg-base-200 shadow-sm mb-6">
        <div class="card-body">
            <form method="GET" class="flex flex-wrap gap-4 items-end">
                <div class="form-control">
                    <label class="label">
                        <span class="label-text">Search</span>
                    </label>
                    <input type="text" name="search" value="{{ request('search') }}" 
                           placeholder="Company, contact, email, ABN..." 
                           class="input input-bordered w-64">
                </div>
                
                <div class="form-control">
                    <label class="label">
                        <span class="label-text">Status</span>
                    </label>
                    <select name="status" class="select select-bordered">
                        <option value="">All Suppliers</option>
                        <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Active Only</option>
                        <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Inactive Only</option>
                    </select>
                </div>
                
                <div class="form-control">
                    <label class="label">
                        <span class="label-text">Onboarding</span>
                    </label>
                    <select name="onboarding_status" class="select select-bordered">
                        <option value="">All Statuses</option>
                        @foreach($onboardingStatuses as $value => $label)
                            <option value="{{ $value }}" {{ request('onboarding_status') === $value ? 'selected' : '' }}>
                                {{ $label }}
                            </option>
                        @endforeach
                    </select>
                </div>
                
                <div class="form-control">
                    <label class="label">
                        <span class="label-text">Verification</span>
                    </label>
                    <select name="verified" class="select select-bordered">
                        <option value="">All</option>
                        <option value="verified" {{ request('verified') === 'verified' ? 'selected' : '' }}>Verified</option>
                        <option value="unverified" {{ request('verified') === 'unverified' ? 'selected' : '' }}>Unverified</option>
                    </select>
                </div>
                
                <div class="form-control">
                    <label class="label">
                        <span class="label-text">State</span>
                    </label>
                    <select name="state" class="select select-bordered">
                        <option value="">All States</option>
                        @foreach($states as $code => $name)
                            <option value="{{ $code }}" {{ request('state') === $code ? 'selected' : '' }}>
                                {{ $code }}
                            </option>
                        @endforeach
                    </select>
                </div>
                
                <div class="form-control">
                    <button type="submit" class="btn btn-primary">
                        <i class="iconoir-search"></i>
                        Filter
                    </button>
                    <a href="{{ route('admin.suppliers.index') }}" class="btn btn-ghost btn-sm mt-1">
                        Clear
                    </a>
                </div>
            </form>
        </div>
    </div>

    @if($suppliers->count() > 0)
        <div class="overflow-x-auto">
            <table class="table table-zebra w-full">
                <thead>
                    <tr>
                        <th>Company</th>
                        <th>Contact Person</th>
                        <th>Location</th>
                        <th>Specialties</th>
                        <th>Status</th>
                        <th>Verification</th>
                        <th>Onboarding</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($suppliers as $supplier)
                        <tr>
                            <td>
                                <div class="flex items-center gap-3">
                                    <div class="avatar">
                                        <div class="mask mask-squircle w-12 h-12">
                                            @if($supplier->logo_url)
                                                <img src="{{ $supplier->logo_url }}" alt="{{ $supplier->company_name }} logo" />
                                            @else
                                                <div class="bg-neutral text-neutral-content flex items-center justify-center">
                                                    <i class="iconoir-building text-lg"></i>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                    <div>
                                        <div class="font-semibold">
                                            <a href="{{ route('admin.suppliers.show', $supplier) }}" class="link link-primary">
                                                {{ $supplier->company_name }}
                                            </a>
                                        </div>
                                        @if($supplier->abn)
                                            <div class="text-sm text-base-content/60">ABN: {{ $supplier->formatted_abn }}</div>
                                        @endif
                                        @if($supplier->website)
                                            <div class="text-sm">
                                                <a href="{{ $supplier->website }}" target="_blank" class="link link-secondary text-xs">
                                                    <i class="iconoir-internet"></i> Website
                                                </a>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="text-sm">
                                    <div class="font-semibold">{{ $supplier->full_name }}</div>
                                    @if($supplier->position)
                                        <div class="text-base-content/60">{{ $supplier->position }}</div>
                                    @endif
                                    <div class="flex items-center gap-1 mt-1">
                                        <i class="iconoir-mail text-xs"></i>
                                        <span class="text-xs">{{ $supplier->email }}</span>
                                    </div>
                                    <div class="flex items-center gap-1">
                                        <i class="iconoir-phone text-xs"></i>
                                        <span class="text-xs">{{ $supplier->phone }}</span>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="text-sm">
                                    @if($supplier->suburb && $supplier->state)
                                        {{ $supplier->suburb }}, {{ $supplier->state }}
                                        @if($supplier->postcode)
                                            {{ $supplier->postcode }}
                                        @endif
                                    @else
                                        <span class="text-base-content/60">No location</span>
                                    @endif
                                </div>
                            </td>
                            <td>
                                <div class="text-sm">
                                    @if($supplier->specialty_areas && count($supplier->specialty_areas) > 0)
                                        @foreach(array_slice($supplier->specialty_areas, 0, 2) as $specialty)
                                            <div class="badge badge-outline badge-sm mb-1">{{ $specialty }}</div>
                                        @endforeach
                                        @if(count($supplier->specialty_areas) > 2)
                                            <div class="text-xs text-base-content/60">+{{ count($supplier->specialty_areas) - 2 }} more</div>
                                        @endif
                                    @else
                                        <span class="text-base-content/60">Not specified</span>
                                    @endif
                                </div>
                            </td>
                            <td>
                                @if($supplier->is_active)
                                    <div class="badge badge-success">Active</div>
                                @else
                                    <div class="badge badge-error">Inactive</div>
                                @endif
                            </td>
                            <td>
                                @if($supplier->is_verified)
                                    <div class="badge badge-success">
                                        <i class="iconoir-check-circle text-xs mr-1"></i>
                                        Verified
                                    </div>
                                @else
                                    <div class="badge badge-warning">
                                        <i class="iconoir-warning-triangle text-xs mr-1"></i>
                                        Unverified
                                    </div>
                                @endif
                            </td>
                            <td>
                                <div class="badge badge-{{ $supplier->status_badge_color }}">
                                    {{ $onboardingStatuses[$supplier->onboarding_status] }}
                                </div>
                            </td>
                            <td>
                                <div class="flex gap-1">
                                    <a href="{{ route('admin.suppliers.show', $supplier) }}" class="btn btn-sm btn-ghost" title="View">
                                        <i class="iconoir-eye"></i>
                                    </a>
                                    <a href="{{ route('admin.suppliers.edit', $supplier) }}" class="btn btn-sm btn-ghost" title="Edit">
                                        <i class="iconoir-edit"></i>
                                    </a>
                                    <div class="dropdown dropdown-end">
                                        <label tabindex="0" class="btn btn-sm btn-ghost">
                                            <i class="iconoir-more-vert"></i>
                                        </label>
                                        <ul tabindex="0" class="dropdown-content menu p-2 shadow bg-base-100 rounded-box w-56">
                                            <li>
                                                <form method="POST" action="{{ route('admin.suppliers.reset-password', $supplier) }}">
                                                    @csrf
                                                    <button type="submit" class="w-full text-left" onclick="return confirm('Reset password for {{ $supplier->company_name }}?')">
                                                        <i class="iconoir-lock"></i>
                                                        Reset Password
                                                    </button>
                                                </form>
                                            </li>
                                            <li>
                                                <form method="POST" action="{{ route('admin.suppliers.toggle-status', $supplier) }}">
                                                    @csrf
                                                    @method('PATCH')
                                                    <button type="submit" class="w-full text-left">
                                                        <i class="iconoir-{{ $supplier->is_active ? 'pause' : 'play' }}"></i>
                                                        {{ $supplier->is_active ? 'Deactivate' : 'Activate' }}
                                                    </button>
                                                </form>
                                            </li>
                                            <li>
                                                <form method="POST" action="{{ route('admin.suppliers.toggle-verification', $supplier) }}">
                                                    @csrf
                                                    @method('PATCH')
                                                    <button type="submit" class="w-full text-left">
                                                        <i class="iconoir-{{ $supplier->is_verified ? 'cancel' : 'check-circle' }}"></i>
                                                        {{ $supplier->is_verified ? 'Unverify' : 'Verify' }}
                                                    </button>
                                                </form>
                                            </li>
                                            <li>
                                                <form method="POST" action="{{ route('admin.suppliers.destroy', $supplier) }}">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="w-full text-left text-error" onclick="return confirm('Delete {{ $supplier->company_name }}? This cannot be undone.')">
                                                        <i class="iconoir-trash"></i>
                                                        Delete
                                                    </button>
                                                </form>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="mt-6">
            {{ $suppliers->withQueryString()->links() }}
        </div>
    @else
        <div class="text-center py-12">
            <i class="iconoir-truck text-6xl text-base-content/30 mb-4 block"></i>
            <h2 class="text-xl font-semibold mb-2">No Suppliers Found</h2>
            <p class="text-base-content/60 mb-4">
                @if(request()->hasAny(['search', 'status', 'onboarding_status', 'verified', 'state']))
                    No suppliers match your search criteria.
                @else
                    Get started by adding your first window frame supplier.
                @endif
            </p>
            <a href="{{ route('admin.suppliers.create') }}" class="btn btn-primary">
                <i class="iconoir-plus"></i>
                Add New Supplier
            </a>
        </div>
    @endif
</div>
@endsection