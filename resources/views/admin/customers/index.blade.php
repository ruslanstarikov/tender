@extends('base')

@section('title', 'Customers')

@section('content')
<div class="container mx-auto">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold">Customers</h1>
        <a href="{{ route('admin.customers.create') }}" class="btn btn-primary">
            <i class="iconoir-plus text-lg"></i>
            Add New Customer
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
                           placeholder="Name, email, phone, suburb..." 
                           class="input input-bordered w-64">
                </div>
                
                <div class="form-control">
                    <label class="label">
                        <span class="label-text">Status</span>
                    </label>
                    <select name="status" class="select select-bordered">
                        <option value="">All Customers</option>
                        <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Active Only</option>
                        <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Inactive Only</option>
                    </select>
                </div>
                
                <div class="form-control">
                    <label class="label">
                        <span class="label-text">Sort By</span>
                    </label>
                    <select name="sort" class="select select-bordered">
                        <option value="created_at" {{ request('sort') === 'created_at' ? 'selected' : '' }}>Date Created</option>
                        <option value="first_name" {{ request('sort') === 'first_name' ? 'selected' : '' }}>First Name</option>
                        <option value="last_name" {{ request('sort') === 'last_name' ? 'selected' : '' }}>Last Name</option>
                        <option value="email" {{ request('sort') === 'email' ? 'selected' : '' }}>Email</option>
                        <option value="last_login_at" {{ request('sort') === 'last_login_at' ? 'selected' : '' }}>Last Login</option>
                    </select>
                </div>
                
                <div class="form-control">
                    <button type="submit" class="btn btn-primary">
                        <i class="iconoir-search"></i>
                        Filter
                    </button>
                    <a href="{{ route('admin.customers.index') }}" class="btn btn-ghost btn-sm mt-1">
                        Clear
                    </a>
                </div>
            </form>
        </div>
    </div>

    @if($customers->count() > 0)
        <div class="overflow-x-auto">
            <table class="table table-zebra w-full">
                <thead>
                    <tr>
                        <th>Customer</th>
                        <th>Contact</th>
                        <th>Address</th>
                        <th>Tenders</th>
                        <th>Status</th>
                        <th>Last Login</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($customers as $customer)
                        <tr>
                            <td>
                                <div class="flex items-center gap-3">
                                    <div class="avatar placeholder">
                                        <div class="bg-neutral text-neutral-content rounded-full w-12">
                                            <span class="text-lg">{{ strtoupper(substr($customer->first_name, 0, 1)) }}</span>
                                        </div>
                                    </div>
                                    <div>
                                        <div class="font-semibold">
                                            <a href="{{ route('admin.customers.show', $customer) }}" class="link link-primary">
                                                {{ $customer->full_name }}
                                            </a>
                                        </div>
                                        <div class="text-sm text-base-content/60">ID: {{ $customer->id }}</div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="text-sm">
                                    <div class="flex items-center gap-1">
                                        <i class="iconoir-mail text-xs"></i>
                                        <span>{{ $customer->email }}</span>
                                    </div>
                                    <div class="flex items-center gap-1 mt-1">
                                        <i class="iconoir-phone text-xs"></i>
                                        <span>{{ $customer->phone }}</span>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="text-sm">
                                    {{ $customer->full_address ?: 'No address' }}
                                </div>
                            </td>
                            <td>
                                <div class="flex items-center gap-2">
                                    <span class="badge badge-info">{{ $customer->tenders_count }}</span>
                                    @if($customer->tenders_count > 0)
                                        <a href="{{ route('admin.customers.show', $customer) }}#tenders" class="text-xs link link-primary">
                                            View
                                        </a>
                                    @endif
                                </div>
                            </td>
                            <td>
                                @if($customer->is_active)
                                    <div class="badge badge-success">Active</div>
                                @else
                                    <div class="badge badge-error">Inactive</div>
                                @endif
                            </td>
                            <td>
                                <div class="text-sm">
                                    {{ $customer->last_login_at ? $customer->last_login_at->diffForHumans() : 'Never' }}
                                </div>
                            </td>
                            <td>
                                <div class="flex gap-1">
                                    <a href="{{ route('admin.customers.show', $customer) }}" class="btn btn-sm btn-ghost" title="View">
                                        <i class="iconoir-eye"></i>
                                    </a>
                                    <a href="{{ route('admin.customers.edit', $customer) }}" class="btn btn-sm btn-ghost" title="Edit">
                                        <i class="iconoir-edit"></i>
                                    </a>
                                    <div class="dropdown dropdown-end">
                                        <label tabindex="0" class="btn btn-sm btn-ghost">
                                            <i class="iconoir-more-vert"></i>
                                        </label>
                                        <ul tabindex="0" class="dropdown-content menu p-2 shadow bg-base-100 rounded-box w-52">
                                            <li>
                                                <form method="POST" action="{{ route('admin.customers.reset-password', $customer) }}">
                                                    @csrf
                                                    <button type="submit" class="w-full text-left" onclick="return confirm('Reset password for {{ $customer->full_name }}?')">
                                                        <i class="iconoir-lock"></i>
                                                        Reset Password
                                                    </button>
                                                </form>
                                            </li>
                                            <li>
                                                <form method="POST" action="{{ route('admin.customers.toggle-status', $customer) }}">
                                                    @csrf
                                                    @method('PATCH')
                                                    <button type="submit" class="w-full text-left">
                                                        <i class="iconoir-{{ $customer->is_active ? 'pause' : 'play' }}"></i>
                                                        {{ $customer->is_active ? 'Deactivate' : 'Activate' }}
                                                    </button>
                                                </form>
                                            </li>
                                            @if($customer->tenders_count === 0)
                                                <li>
                                                    <form method="POST" action="{{ route('admin.customers.destroy', $customer) }}">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="w-full text-left text-error" onclick="return confirm('Delete {{ $customer->full_name }}? This cannot be undone.')">
                                                            <i class="iconoir-trash"></i>
                                                            Delete
                                                        </button>
                                                    </form>
                                                </li>
                                            @endif
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
            {{ $customers->withQueryString()->links() }}
        </div>
    @else
        <div class="text-center py-12">
            <i class="iconoir-user text-6xl text-base-content/30 mb-4 block"></i>
            <h2 class="text-xl font-semibold mb-2">No Customers Found</h2>
            <p class="text-base-content/60 mb-4">
                @if(request()->hasAny(['search', 'status']))
                    No customers match your search criteria.
                @else
                    Get started by adding your first customer.
                @endif
            </p>
            <a href="{{ route('admin.customers.create') }}" class="btn btn-primary">
                <i class="iconoir-plus"></i>
                Add New Customer
            </a>
        </div>
    @endif
</div>
@endsection