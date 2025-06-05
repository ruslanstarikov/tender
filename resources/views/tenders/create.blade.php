@extends('base') {{-- or the name of your base layout, e.g. resources/views/layouts/app.blade.php --}}

@section('title', 'Create Tender')

@section('content')
    <div class="max-w-3xl mx-auto bg-base-100 p-6 rounded-lg shadow">
        <h2 class="text-2xl font-semibold mb-4">Create New Tender</h2>

        @if ($errors->any())
            <div class="alert alert-error mb-4">
                <ul class="list-disc pl-5">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('admin.tenders.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            {{-- Basic Tender Fields --}}
            <div class="grid grid-cols-1 gap-4">
                <div>
                    <label class="label"><span class="label-text">Project Title</span></label>
                    <input type="text" name="project_title" value="{{ old('project_title') }}" class="input input-bordered w-full" required>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="label"><span class="label-text">Address</span></label>
                        <input type="text" name="address" value="{{ old('address') }}" class="input input-bordered w-full" required>
                    </div>
                    <div>
                        <label class="label"><span class="label-text">Suburb</span></label>
                        <input type="text" name="suburb" value="{{ old('suburb') }}" class="input input-bordered w-full" required>
                    </div>
                    <div>
                        <label class="label"><span class="label-text">State</span></label>
                        <input type="text" name="state" value="{{ old('state') }}" class="input input-bordered w-full" required>
                    </div>
                    <div>
                        <label class="label"><span class="label-text">Post Code</span></label>
                        <input type="text" name="post_code" value="{{ old('post_code') }}" class="input input-bordered w-full" required>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="label"><span class="label-text">Work Start Date & Time</span></label>
                        <input type="datetime-local" name="work_start_datetime" value="{{ old('work_start_datetime') }}" class="input input-bordered w-full" required>
                    </div>
                    <div>
                        <label class="label"><span class="label-text">Work End Date & Time</span></label>
                        <input type="datetime-local" name="work_end_datetime" value="{{ old('work_end_datetime') }}" class="input input-bordered w-full" required>
                    </div>
                </div>

                <div>
                    <label class="label"><span class="label-text">Premises Entry Info (e.g. dogs, security)</span></label>
                    <textarea name="premises_entry_info" class="textarea textarea-bordered w-full" rows="3">{{ old('premises_entry_info') }}</textarea>
                </div>

                <div>
                    <label class="label"><span class="label-text">Frame Details (system, color, glass count)</span></label>
                    <textarea name="frame_details" class="textarea textarea-bordered w-full" rows="3">{{ old('frame_details') }}</textarea>
                </div>
            </div>

            <hr class="my-6">

            {{-- Media Upload Sections --}}
            <h3 class="text-xl font-semibold mb-2">Upload Media</h3>
            <p class="text-sm text-gray-600 mb-4">You may upload multiple photos/videos under each category. For Polycam models, upload under "Polycam Models."</p>

            {{-- Categories: inside, parking, outside, storage --}}
            <div class="grid grid-cols-1 gap-8">
                @foreach (['inside', 'parking', 'outside', 'storage'] as $category)
                    <div class="border rounded-lg p-4">
                        <h4 class="font-semibold text-lg capitalize mb-2">{{ ucfirst($category) }}:</h4>

                        {{-- Photos --}}
                        <div class="mb-4">
                            <label class="label"><span class="label-text">Photos ({{ ucfirst($category) }})</span></label>
                            <input type="file"
                                   name="media[{{ $category }}][photos][]"
                                   accept="image/*"
                                   multiple
                                   class="file-input file-input-bordered w-full" />
                        </div>

                        {{-- Videos --}}
                        <div>
                            <label class="label"><span class="label-text">Videos ({{ ucfirst($category) }})</span></label>
                            <input type="file"
                                   name="media[{{ $category }}][videos][]"
                                   accept="video/*"
                                   multiple
                                   class="file-input file-input-bordered w-full" />
                        </div>
                    </div>
                @endforeach

                {{-- ===== GLB (Polycam) Section ===== --}}
                <div class="border rounded-lg p-4">
                    <h4 class="font-semibold text-lg mb-2">Polycam Models (GLB):</h4>
                    <div>
                        <label class="label"><span class="label-text">Upload One or More `.glb` Files</span></label>
                        <input type="file"
                               name="media[polycam][models][]"
                               multiple
                               accept=".glb"
                               class="file-input file-input-bordered w-full" />
                        <p class="text-xs text-gray-500 mt-1">
                            Polycam exports glTF/GLB by default. Upload `.glb` here (each file can be up to your configured max).
                        </p>
                    </div>
                </div>
            </div>

            <div class="mt-6">
                <button type="submit" class="btn btn-primary">Create Tender</button>
            </div>
        </form>
    </div>
@endsection
