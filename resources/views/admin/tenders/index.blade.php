@extends('base')

@section('title', 'Tenders')

@section('content')
<div class="container mx-auto">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold">Tenders</h1>
        <a href="{{ route('admin.tenders.create') }}" class="btn btn-primary">
            <i class="iconoir-plus text-lg"></i>
            Create New Tender
        </a>
    </div>

    @if($tenders->count() > 0)
        <div class="overflow-x-auto">
            <table class="table table-zebra w-full">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Project Title</th>
                        <th>Customer</th>
                        <th>Status</th>
                        <th>Work Start</th>
                        <th>Created</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($tenders as $tender)
                        <tr>
                            <td>{{ $tender->id }}</td>
                            <td>{{ $tender->project_title }}</td>
                            <td>{{ $tender->customer->name ?? 'N/A' }}</td>
                            <td>
                                <div class="badge badge-{{ $tender->tender_status == 'pending' ? 'warning' : ($tender->tender_status == 'approved' ? 'success' : 'error') }}">
                                    {{ ucfirst($tender->tender_status) }}
                                </div>
                            </td>
                            <td>{{ $tender->work_start_datetime ? \Carbon\Carbon::parse($tender->work_start_datetime)->format('M d, Y') : 'N/A' }}</td>
                            <td>{{ $tender->created_at->format('M d, Y') }}</td>
                            <td>
                                <div class="flex gap-2">
                                    <a href="{{ route('admin.tenders.show', $tender) }}" class="btn btn-sm btn-ghost">
                                        <i class="iconoir-eye"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="mt-6">
            {{ $tenders->links() }}
        </div>
    @else
        <div class="text-center py-12">
            <i class="iconoir-page-edit text-6xl text-base-content/30 mb-4 block"></i>
            <h2 class="text-xl font-semibold mb-2">No Tenders Yet</h2>
            <p class="text-base-content/60 mb-4">Get started by creating your first tender.</p>
            <a href="{{ route('admin.tenders.create') }}" class="btn btn-primary">
                <i class="iconoir-plus"></i>
                Create New Tender
            </a>
        </div>
    @endif
</div>
@endsection