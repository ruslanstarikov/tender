@extends('base')

@section('title', 'Contacts')

@section('content')
<div class="container mx-auto">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold">Contacts</h1>
        <button class="btn btn-primary">
            <i class="iconoir-plus text-lg"></i>
            Add New Contact
        </button>
    </div>

    <div class="text-center py-12">
        <i class="iconoir-user text-6xl text-base-content/30 mb-4 block"></i>
        <h2 class="text-xl font-semibold mb-2">Contacts Management</h2>
        <p class="text-base-content/60 mb-4">This section will manage your contacts and customers.</p>
        <div class="alert alert-info">
            <i class="iconoir-info-circle"></i>
            <span>Contact management functionality coming soon!</span>
        </div>
    </div>
</div>
@endsection