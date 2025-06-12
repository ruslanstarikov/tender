@extends('base') {{-- your base layout --}}
@section('title', 'Tender Preview')

@section('content')
    <div class="max-w-4xl mx-auto bg-base-100 p-6 rounded-lg shadow">
        <h2 class="text-2xl font-semibold mb-4">Tender Preview</h2>

        {{-- Basic Tender Info --}}
        <div class="mb-6">
            <h3 class="font-semibold text-lg">Project Title:</h3>
            <p>{{ $tender->project_title }}</p>

            <h3 class="font-semibold text-lg mt-4">Address:</h3>
            <p>{{ $tender->property_address }}, {{ $tender->suburb }}, {{ $tender->state }} {{ $tender->post_code }}</p>

            <h3 class="font-semibold text-lg mt-4">Work Schedule:</h3>
            <p>
                {{ \Carbon\Carbon::parse($tender->work_start_datetime)->format('Y-m-d H:i') }}
                &mdash;
                {{ \Carbon\Carbon::parse($tender->work_end_datetime)->format('Y-m-d H:i') }}
            </p>

            <h3 class="font-semibold text-lg mt-4">Premises Entry Info:</h3>
            <p>{{ $tender->premises_entry_info ?? 'N/A' }}</p>

            <h3 class="font-semibold text-lg mt-4">Frame Details:</h3>
            <p>{{ $tender->frame_details ?? 'N/A' }}</p>
        </div>

        <hr class="my-6">

        @php $polyGroup = $tender->media->where('category', 'polycam'); @endphp
        @if ($polyGroup->isNotEmpty())
            <div class="mb-8">
                <h4 class="font-semibold text-lg capitalize mb-2">Polycam Models</h4>
                <div class="grid grid-cols-1 gap-6">
                    @foreach ($polyGroup as $media)
                        <div class="border rounded-lg bg-gray-100">
                            <div id="viewer-{{ $media->id }}" class="threejs-viewer w-full h-80"></div>
                            <input type="hidden"
                                   class="glb-url"
                                   value="{{ Storage::url($media->file_path) }}" />
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
        {{-- Media Preview --}}
        <div>
            <h3 class="text-xl font-semibold mb-4">Media</h3>

            @if ($tender->media->isEmpty())
                <p class="text-gray-500">No media uploaded for this tender.</p>
            @else
                {{-- Group media by category --}}
                @foreach (['inside', 'parking', 'outside', 'storage'] as $category)
                    @php
                        $grouped = $tender->media->where('category', $category);
                    @endphp

                    @if ($grouped->isNotEmpty())
                        <div class="mb-8">
                            <h4 class="font-semibold text-lg capitalize mb-2">{{ ucfirst($category) }}</h4>
                            <div class="grid grid-cols-3 gap-4">
                                @foreach ($grouped as $media)
                                    @if ($media->media_type === 'photo')
                                        {{-- Show an <img> for photos --}}
                                        <div class="border rounded overflow-hidden">
                                            <img src="{{ Storage::url($media->file_path) }}"
                                                 alt="Photo ({{ $media->category }})"
                                                 class="w-full h-48 object-cover" />
                                        </div>
                                    @elseif ($media->media_type === 'video')
                                        {{-- Show a <video> tag for videos --}}
                                        <div class="border rounded overflow-hidden">
                                            <video controls class="w-full h-48">
                                                <source src="{{ Storage::url($media->file_path) }}" type="video/mp4" />
                                                Your browser does not support the video tag.
                                            </video>
                                        </div>
                                    @endif
                                @endforeach
                            </div>
                        </div>
                    @endif
                @endforeach
            @endif
        </div>
    </div>
@endsection


@section('scripts')
    @vite('resources/js/three-viewer.js')
@endsection
