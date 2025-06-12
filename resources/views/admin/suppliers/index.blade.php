@extends('base')

@section('title', 'Suppliers')

@section('content')
<div class="container mx-auto">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold">Suppliers</h1>
        <button class="btn btn-primary">
            <i class="iconoir-plus text-lg"></i>
            Add New Supplier
        </button>
    </div>

    <div class="text-center py-12">
        <i class="iconoir-truck text-6xl text-base-content/30 mb-4 block"></i>
        <h2 class="text-xl font-semibold mb-2">Supplier Management</h2>
        <p class="text-base-content/60 mb-4">This section will manage your suppliers and vendors.</p>
        <div class="alert alert-info">
            <i class="iconoir-info-circle"></i>
            <span>Supplier management functionality coming soon!</span>
        </div>
    </div>
</div>
@endsection